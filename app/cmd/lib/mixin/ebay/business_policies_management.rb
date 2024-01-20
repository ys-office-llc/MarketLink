module TWEyes

  module Mixin ### この空間は以下はMix-in専用

    module Ebay ### 名前空間用なので機能を持たせるとバグるよ

      module BusinessPoliciesManagement

        def get_response(call_name, xml)
          doc = REXML::Document.new(xml)

          {
            timestamp: REXML::XPath.match(doc,
              "/#{call_name}Response/timestamp")[0].text.strip,
            ack: REXML::XPath.match(doc,
              "/#{call_name}Response/ack")[0].text.strip.downcase,
            version: REXML::XPath.match(doc,
              "/#{call_name}Response/version")[0].text.strip,
          }
        end

        def get_pagination_output_response(call_name, xml)
          doc = REXML::Document.new(xml)

          {
            page_number: REXML::XPath.match(doc,
              "/#{call_name}Response/paginationOutput/pageNumber")[0].text.to_s.strip.to_i,
            entries_per_page: REXML::XPath.match(doc,
              "/#{call_name}Response/paginationOutput/entriesPerPage")[0].text.to_s.strip.to_i,
            total_pages: REXML::XPath.match(doc,
              "/#{call_name}Response/paginationOutput/totalPages")[0].text.to_s.strip.to_i,
            total_entries: REXML::XPath.match(doc,
              "/#{call_name}Response/paginationOutput/totalEntries")[0].text.to_s.strip.to_i,
          }
        end

        def get_find_completed_items_response(call_name, xml)
          get_find_items_by_keywords_response(call_name, xml)
        end

        def get_find_items_by_keywords_response(call_name, xml)
          items = {}

          doc = REXML::Document.new(xml)

          REXML::XPath.match(
            doc,
            "/#{call_name}Response/searchResult")
          .each do |element|
            element.elements.each do |e|
              item  = {}
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
              items.store(item[:item_item_id], item)
            end
          end

          items
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

      end ### module Trading [END]

    end ### module Ebay [END]

  end ### Mixin [END]

end ### module TWEyes [END]
