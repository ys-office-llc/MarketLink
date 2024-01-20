module TWEyes

  module Mixin ### この空間は以下はMix-in専用

    module Yahoo ### 名前空間用なので機能を持たせるとバグるよ

      module  API

        def get_result_set(response)
          doc = REXML::Document.new(response)
          result_set = doc.elements['ResultSet']

          {
            available: result_set.attributes['totalResultsAvailable'].to_i,
            returned:  result_set.attributes['totalResultsReturned'].to_i,
            position:  result_set.attributes['firstResultPosition'].to_i,
          }
        end

        module Auctions

          def get_error(response)

            doc = REXML::Document.new(response)
            if doc.elements['/Error/Message']

              raise(
                TWEyes::Exception::Mixin::Yahoo::Auctions::API.new(),
                doc.elements['/Error/Message'].text
              )
            end
          end
  
          def get_items(item)

            result = {}
        
            if item.elements['AuctionID']
              result.store(:auction_id, item.elements['AuctionID'].text)
            end
        
            if item.elements['Title']
              result.store(:title, item.elements['Title'].text.gsub("'", ''))
            end
        
            if item.elements['CurrentPrice']

              result.store(
                :current_price,
                item.elements['CurrentPrice'].text.to_i
              )
            end
        
            if item.elements['HighestPrice']
              result.store(:highest_price, item.elements['HighestPrice'].text.to_i)
            end
        
            if item.elements['Winner/Id'] and item.elements['Winner/Id'].text
              result.store(:winner_id, item.elements['Winner/Id'].text)
            end
        
            if item.elements['Winner/ContactUrl'] and
               item.elements['Winner/ContactUrl'].text
              result.store(:contact_url, item.elements['Winner/ContactUrl'].text)
            end
        
            if item.elements['Progresses/Progress'] and
               item.elements['Progresses/Progress'].text
              result.store(:progress, item.elements['Progresses/Progress'].text)
            end
        
            if item.elements['EndTime']
              result.store(:end_time, item.elements['EndTime'].text)
            end
        
            if item.elements['AuctionItemUrl']
              result.store(:auction_item_url, item.elements['AuctionItemUrl'].text)
            end
        
            if item.elements['NumWatch']
              result.store(:num_watch, item.elements['NumWatch'].text)
            end
        
            return result
          end

          def get_search_result(xml)
            results = {}
  
            doc = REXML::Document.new(xml)
  
            REXML::XPath.match(
              doc,
              "/ResultSet/Result/Item")
            .each do |element|
              result  = {}
              element.elements.each do |e|
                if REXML::XPath.match(e).size.zero?
                  result[e.name.to_snake.to_sym] =
                    REXML::XPath.match(element, e.name)[0].text.strip
                else
                  REXML::XPath.match(e).each do |e2|
                    if REXML::XPath.match(e2).size.zero?
                      result[
                        sprintf("%s_%s",
                          e.name.to_snake,
                          e2.name.to_snake).to_sym
                      ] = REXML::XPath.match(e, e2.name)[0].text.strip
                    else
                      REXML::XPath.match(e2).each do |e3|
                        result[
                          sprintf("%s_%s",
                            e2.name.to_snake,
                            e3.name.to_snake).to_sym
                        ] = REXML::XPath.match(e2, e3.name)[0].text.strip
                      end
                    end
                  end
                end
              end
              results.store(result[:auction_id], result)
            end
  
            results
          end

          def get_close_watch_list_result(xml)
            get_open_watch_list_result(xml)
          end
  
          def get_open_watch_list_result(xml)
            results = {}

            doc = REXML::Document.new(xml)

            REXML::XPath.match(
              doc,
              "/ResultSet/Result")
            .each do |element|
              result = {}
              element.elements.each do |e|
                if REXML::XPath.match(e).size.zero?
                  result[e.name.to_snake.to_sym] =
                    REXML::XPath.match(element, e.name)[0].text.to_s.strip
                else
                  REXML::XPath.match(e).each do |e2|
                    if REXML::XPath.match(e2).size.zero?
                      result[
                        sprintf("%s_%s",
                          e.name.to_snake,
                          e2.name.to_snake).to_sym
                      ] = REXML::XPath.match(e, e2.name)[0].text.to_s.strip
                    else
                      REXML::XPath.match(e2).each do |e3|
                        result[
                          sprintf("%s_%s",
                            e2.name.to_snake,
                            e3.name.to_snake).to_sym
                        ] = REXML::XPath.match(e2, e3.name)[0].text.to_s.strip
                      end
                    end
                  end
                end
              end
              results.store(result[:auction_id], result)
            end

            results
          end

          def get_auction_item_result(xml)
            result = {}

            doc = REXML::Document.new(xml)

            REXML::XPath.match(
              doc,
              "/ResultSet/Result")
            .each do |element|
              element.elements.each do |e|
                if REXML::XPath.match(e).size.zero?
                  result[e.name.to_snake.to_sym] =
                    REXML::XPath.match(element, e.name)[0].text.to_s.strip
                else
                  REXML::XPath.match(e).each do |e2|
                    if REXML::XPath.match(e2).size.zero?
                      result[
                        sprintf("%s_%s",
                          e.name.to_snake,
                          e2.name.to_snake).to_sym
                      ] = REXML::XPath.match(e, e2.name)[0].text.to_s.strip
                    else
                      REXML::XPath.match(e2).each do |e3|
                        result[
                          sprintf("%s_%s",
                            e2.name.to_snake,
                            e3.name.to_snake).to_sym
                        ] = REXML::XPath.match(e2, e3.name)[0].text.to_s.strip
                      end
                    end
                  end
                end
              end
            end

            result
          end

          def get_category_tree_result(xml)

            results = {}

            doc = REXML::Document.new(xml)

            REXML::XPath.match(
              doc,
              "/ResultSet/Result")
            .each do |element|

              result_parent = {}
              element.elements.each do |e|

                if REXML::XPath.match(e).size.zero?

                  result_parent[e.name.to_snake.to_sym] =
                    REXML::XPath.match(element, e.name)[0].text.to_s.strip
                else

                  result_child = {}
                  REXML::XPath.match(e).each do |e2|

                    result_child[
                      sprintf("%s_%s",
                        e.name.to_snake,
                        e2.name.to_snake).to_sym
                    ] = REXML::XPath.match(e, e2.name)[0].text.to_s.strip
                  end
                  results.store(
                    result_child[:child_category_category_id],
                    result_child
                  )
                end
              end
              results.store(
                result_parent[:category_id],
                result_parent
              )
            end

            results
          end
        
        end ### module Auctions [END]

        module Shopping

          def get_error(response)
            doc = REXML::Document.new(response)

            if doc.elements['/Error/Message']
              raise(
                TWEyes::Exception::Mixin::Yahoo::Shopping::API.new(),
                doc.elements['/Error/Message'].text
              )
            end

            result_set = doc.elements['/ResultSet/Result']
            if result_set.elements['Status'] and
               result_set.elements['Status'].text === 'NG'
              raise(
                TWEyes::Exception::Mixin::Yahoo::Shopping::API.new(),
                sprintf("Error: Code=[%s], Target=[%s], Message=[%s]",
                  result_set.elements['Error/Code'].text,
                  result_set.elements['Error/Target'].text,
                  result_set.elements['Error/Message'].text
                )
              )
            end
          end

          def get_item_search_result(item)
            result = {}
        
            if item.elements['Name']
              result.store(:name, item.elements['Name'].text)
            end

            if item.elements['Description']
              result.store(:description, item.elements['Description'].text)
            end

            if item.elements['Headline']
              result.store(:headline, item.elements['Headline'].text)
            end

            if item.elements['Url']
              result.store(:url, item.elements['Url'].text)
            end

            if item.elements['ReleaseDate']
              result.store(:release_date, item.elements['ReleaseDate'].text)
            end

            if item.elements['Availability']
              result.store(:availability, item.elements['Availability'].text)
            end

            if item.elements['Code']
              result.store(:code, item.elements['Code'].text)
            end

            if item.elements['Condition']
              result.store(:condition, item.elements['Condition'].text)
            end

            if item.elements['PersonId']
              result.store(:person_id, item.elements['PersonId'].text)
            end

            if item.elements['ProductId']
              result.store(:product_id, item.elements['ProductId'].text)
            end

            if item.elements['ProductId']
              result.store(:product_id, item.elements['ProductId'].text)
            end

            if item.elements['Image']
              image = item.elements['Image'].elements
              result.store(:image_id, image['Id'].text)
              result.store(:image_small, image['Small'].text)
              result.store(:image_medium, image['Medium'].text)
            end

            if item.elements['Review']
              review = item.elements['Review'].elements
              result.store(:review_rate, review['Rate'].text.to_i)
              result.store(:review_count, review['Count'].text)
              result.store(:review_url, review['Url'].text)
            end

            if item.elements['Affiliate']
              review = item.elements['Affiliate'].elements
              result.store(:affiliate_rate, review['Rate'].text.to_i)
            end

            if item.elements['Price']
              result.store(:price_currency,
                item.elements['Price'].attributes['currency'])
              result.store(:price, item.elements['Price'].text.to_i)
            end

            if item.elements['Category']
              category = item.elements['Category'].elements
              result.store(:category_current_id,
                category['Current'].elements['Id'].text)
              result.store(:category_current_name,
                category['Current'].elements['Name'].text)
            end

            result
          end

          def get_get_stock_result(item)
            result = {}
        
            if item.elements['ItemCode']
              result.store(:item_code , item.elements['ItemCode'].text)
            end

            if item.elements['ItemCode']
              result.store(:quantity, item.elements['Quantity'].text.to_i)
            end

            result
          end ### get_get_stock_result(item) [END]

        end ### module Shopping [END]

      end ### module API [END]

    end ### module Yahoo [END]

  end ### Mixin [END]

end ### module TWEyes [END]
