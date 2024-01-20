module TWEyes

  module Mixin ### この空間は以下はMix-in専用

    module Amazon ### 名前空間用なので機能を持たせるとバグるよ

      module MWS

        module Products

          def parse_get_lowest_offer_listing_price(result)

            result['Product']['LowestOfferListings'][
              'LowestOfferListing'
            ][0]['Price']['ListingPrice']['Amount'].to_i
          end

          def parse_get_title(result)

            product = nil
            title   = nil

            case result
            when Array

              if result[0]['Product']

                product = result[0]['Product']

              elsif result[1]['Product']

                product = result[1]['Product']
              end

              if product and product['AttributeSets']

                product['AttributeSets']['ItemAttributes']['Title']
              else

                return ''
              end
            when Hash

              if result and result['Product']

                result['Product']['AttributeSets']['ItemAttributes']['Title']
              else

                ''
              end
            end
          end

          def parse_get_sales_rankings(result)


            product    = nil
            sales_rank = nil

            case result
            when Array

              if result[0]['Product']
           
                product = result[0]['Product'] 

              elsif result[1]['Product']

                product = result[1]['Product'] 
              end

              if product['SalesRankings']

                sales_rank = product['SalesRankings']['SalesRank']
              else

                return 0
              end

              case sales_rank
              when Array
               
                sales_rank[0]['Rank'].to_i
              when Hash

                sales_rank['Rank'].to_i
              end

            when Hash

              result['Product']['SalesRankings']['SalesRank'][0]['Rank'].to_i
            end 
          end

          def parse_get_asin_by_name(result)

            if result['Products'].nil?

              return 'N/A'
            end

            product = result['Products']['Product']

            case product
            when Array

              product[0]['Identifiers']['MarketplaceASIN']['ASIN']
            when Hash

              product['Identifiers']['MarketplaceASIN']['ASIN']
            end
          end

        end ### module Feeds [END]

      end ### module MWS [END]

    end ### module Amazon [END]

  end ### Mixin [END]

end ### module TWEyes [END]
