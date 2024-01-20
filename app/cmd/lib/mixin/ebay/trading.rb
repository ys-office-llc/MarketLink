module TWEyes

  module Mixin ### この空間は以下はMix-in専用

    module Ebay ### 名前空間用なので機能を持たせるとバグるよ

      module Trading

        def get_brand_mpn(maker, mpn)
          if mpn.nil?
            return [' ', ' ']
          else
            return [maker, mpn]
          end
        end

        def get_price_xml(listing_type, start_price, end_price)
          xml = []

          case listing_type
          when 'Auction'
            xml.push(sprintf("<StartPrice>%s</StartPrice>", start_price))
            if end_price > 0
              xml.push(sprintf("<BuyItNowPrice>%s</BuyItNowPrice>", end_price))
            end
          when 'FixedPriceItem'
            xml.push(sprintf("<StartPrice>%s</StartPrice>", start_price))
          end

          xml.join
        end

        def get_exclude_xml(exclude_list)
          xml = []

          exclude_list.split(',').each do |exclude|
            xml.push(sprintf(
              "<ExcludeShipToLocation>%s</ExcludeShipToLocation>",
              exclude
            ))
          end

          xml.join
        end

        def get_response(call_name, xml)

          basic   = {}
          message = nil
          errors  = Hash.new{ |h, k| h[k] = [] }

          doc = REXML::Document.new(xml)

          REXML::XPath.match(
            doc,
            "/#{call_name}Response/Errors")
          .each do |element|
            REXML::XPath.match(element, 'ShortMessage').each do |e|
              errors[:short_message].push(e.text.strip)
            end

            REXML::XPath.match(element, 'LongMessage').each do |e|
              errors[:long_message].push(e.text.strip)
            end

            REXML::XPath.match(element, 'ErrorCode').each do |e|
              errors[:error_code].push(e.text.strip)
            end

            REXML::XPath.match(element, 'SeverityCode').each do |e|
              errors[:severity_code].push(e.text.strip)
            end

            REXML::XPath.match(element, 'ErrorClassification').each do |e|
              errors[:error_classification].push(e.text.strip)
            end
          end

          if REXML::XPath.match(
               doc,
               "/#{call_name}Response/Message"
             ).size > 0

            message = Sanitize.clean(
                        REXML::XPath.match(
                          doc,
                          "/#{call_name}Response/Message"
                        )[0].text.strip,
                        Sanitize::Config::BASIC,
                      )
          end

          {
            message: message,
            timestamp: REXML::XPath.match(doc,
              "/#{call_name}Response/Timestamp")[0].text.strip,
            ack: REXML::XPath.match(doc,
              "/#{call_name}Response/Ack")[0].text.strip.downcase,
            errors: errors,
            version: REXML::XPath.match(doc,
              "/#{call_name}Response/Version")[0].text.strip,
            build: REXML::XPath.match(doc,
              "/#{call_name}Response/Build")[0].text.strip,
          }
        end

        def get_add_item_response(call_name, xml)
          fees = {}

          doc = REXML::Document.new(xml)
          REXML::XPath.match(
            doc,
            "/#{call_name}Response/Fees")
          .each do |element|
            REXML::XPath.match(element).each do |fee|
              fees[REXML::XPath.match(fee, 'Name')[0]
                .text
                .strip
                .to_snake
                .to_sym
              ] = REXML::XPath.match(fee, 'Fee')[0].text.strip.to_f
              #pp REXML::XPath.match(fee, 'Fee')[0].attributes['currencyID']
           end
         end

         {
           item_id: REXML::XPath.match(
             doc,
             "/#{call_name}Response/ItemID")[0].text.strip,
           start_time: REXML::XPath.match(
             doc,
             "/#{call_name}Response/StartTime")[0].text.strip,
           end_time: REXML::XPath.match(
             doc,
             "/#{call_name}Response/EndTime")[0].text.strip,
           fees: fees,
=begin
仕様を統一したいのと利用使途が不明なので、コメントにしておく。(2016-10-13)
           discount_reason: REXML::XPath.match(
             doc,
             "/#{call_name}Response/DiscountReason")[0].text.strip,
=end
          }
        end ### get_add_item_response(call_name, xml) [END]

        def get_revise_item_response(call_name, xml)
          get_add_item_response(call_name, xml)
        end

        def get_relist_item_response(call_name, xml)
          get_add_item_response(call_name, xml)
        end

        def get_get_my_ebay_selling_response(call_name, list, xml)
          elements = []
          items = {}
          xpath = nil

          doc = REXML::Document.new(xml)

          case list
          when 'SoldList'
            xpath = 'OrderTransactionArray/OrderTransaction/Order/TransactionArray/Transaction'
            elements = REXML::XPath.match(doc, "/#{call_name}Response/#{list}/#{xpath}")
            xpath = 'OrderTransactionArray/OrderTransaction/Transaction'
            elements.concat(REXML::XPath.match(doc, "/#{call_name}Response/#{list}/#{xpath}"))
          else
            xpath = 'ItemArray/Item'
            elements = REXML::XPath.match(doc, "/#{call_name}Response/#{list}/#{xpath}")
          end

          elements.each do |element|
            item  = {}
            element.elements.each do |e|
              if REXML::XPath.match(e).size.zero?
                item[e.name.to_snake.to_sym] =
                  REXML::XPath.match(element, e.name)[0].text.to_s.strip
              else
                REXML::XPath.match(e).each do |e2|
                  if REXML::XPath.match(e2).size.zero?
                    item[
                      sprintf("%s_%s",
                        e.name.to_snake,
                        e2.name.to_snake).to_sym
                    ] = REXML::XPath.match(e, e2.name)[0].text.to_s.strip
                  else
                    REXML::XPath.match(e2).each do |e3|
                      item[
                        sprintf("%s_%s",
                          e2.name.to_snake,
                          e3.name.to_snake).to_sym
                      ] = REXML::XPath.match(e2, e3.name)[0].text.to_s.strip
                    end
                  end
                end
              end
            end

            case list
            when 'SoldList'
              items.store(item[:item_item_id], item)
            else
              items.store(item[:item_id], item)
            end
          end

          items
        end

        def get_get_item_transactions_response(call_name, xml)

          transaction = {}

          doc = REXML::Document.new(xml)

          REXML::XPath.match(
            doc,
            "/#{call_name}Response/TransactionArray/Transaction")
          .each do |element|

            element.elements.each do |e|

              if REXML::XPath.match(e).size.zero?

                transaction[e.name.to_snake.to_sym] =
                  REXML::XPath.match(element, e.name)[0].text.to_s.strip
              else

                REXML::XPath.match(e).each do |e2|

                  if REXML::XPath.match(e2).size.zero?
                    transaction[
                      sprintf("%s_%s",
                        e.name.to_snake,
                        e2.name.to_snake).to_sym
                    ] = REXML::XPath.match(e, e2.name)[0].text.to_s.strip
                  else

                    REXML::XPath.match(e2).each do |e3|

                      if REXML::XPath.match(e3).size.zero?

                        transaction[
                          sprintf("%s_%s",
                            e2.name.to_snake,
                            e3.name.to_snake).to_sym
                        ] = REXML::XPath.match(e2, e3.name)[0].text.to_s.strip
                      else

                        REXML::XPath.match(e3).each do |e4|

                          transaction[
                            sprintf("%s_%s",
                              e3.name.to_snake,
                              e4.name.to_snake).to_sym
                          ] = REXML::XPath.match(e3, e4.name)[0].text.to_s.strip
                        end
                      end
                    end
                  end
                end
              end
            end
          end

          transaction
        end

        def get_get_item_response(call_name, xml)
          item = {}

          doc = REXML::Document.new(xml)

          REXML::XPath.match(
            doc,
            "/#{call_name}Response/Item")
          .each do |element|
            element.elements.each do |e|
              if REXML::XPath.match(e).size.zero?
                item[e.name.to_snake.to_sym] =
                  REXML::XPath.match(element, e.name)[0].text.strip
              else
                REXML::XPath.match(e).each do |e2|
                  if REXML::XPath.match(e2).size.zero?
                    item[
                      sprintf("%s_%s",
                        e.name.to_snake,
                        e2.name.to_snake).to_sym
                    ] = REXML::XPath.match(e, e2.name)[0].text.strip
                  else
                    REXML::XPath.match(e2).each do |e3|
                      item[
                        sprintf("%s_%s",
                          e2.name.to_snake,
                          e3.name.to_snake).to_sym
                      ] = REXML::XPath.match(e2, e3.name)[0].text.strip
                    end
                  end
                end
              end
            end
          end

          item
        end ### get_get_item_response(call_name, xml)

        def get_fetch_token_response(call_name, xml)

          get_get_session_id_response(call_name, xml)
        end

        def get_get_session_id_response(call_name, xml)

          result = {}

          doc = REXML::Document.new(xml)

          REXML::XPath.match(
            doc,
            "/#{call_name}Response")
          .each do |element|
            element.elements.each do |e|
              if REXML::XPath.match(e).size.zero?

                result[e.name.to_snake.to_sym] =
                  REXML::XPath.match(element, e.name)[0].text.strip
              end
            end
          end

          result
        end

        def get_get_user_preferences_response(call_name, xml)

          preferences = {}

          doc = REXML::Document.new(xml)

          REXML::XPath.match(
            doc,
            "/#{call_name}Response/SellerProfilePreferences/SupportedSellerProfiles")
          .each do |element|

            element.elements.each do |e|

              if REXML::XPath.match(e).size.zero?

                preference[e.name.to_snake.to_sym] =
                  REXML::XPath.match(element, e.name)[0].text.strip
              else

                preference  = {}

                REXML::XPath.match(e).each do |e2|

                  if REXML::XPath.match(e2).size.zero?

                    preference[
                      sprintf("%s_%s",
                        e.name.to_snake,
                        e2.name.to_snake).to_sym
                    ] = REXML::XPath.match(e, e2.name)[0].text.strip
                  else

                    REXML::XPath.match(e2).each do |e3|

                      preference[
                        sprintf("%s_%s_%s",
                          e.name.to_snake,
                          e2.name.to_snake,
                          e3.name.to_snake).to_sym
                      ] = REXML::XPath.match(e2, e3.name)[0].text.strip
                    end
                  end
                end

                preference.store(
                  :name,
                  preference[:supported_seller_profile_profile_name]
                )
                preference.delete(
                  :supported_seller_profile_profile_name
                )
                preferences.store(
                  preference[:supported_seller_profile_profile_id],
                  preference
                )
              end
            end
          end

          preferences
        end

      end ### module Trading [END]

    end ### module Ebay [END]

  end ### Mixin [END]

end ### module TWEyes [END]
