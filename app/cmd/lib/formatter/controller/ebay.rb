module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      class Ebay

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        protected

        private

        class Us < Ebay

          public

          def initialize(configure)

            super
          end

          def add_item(result)

            result.store(:tweyes_link, 
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+result['item_id'].to_s
            )
            result.store(:ebay_us_paypal_mailaddress,
              result['item_condition_ebay_us_paypal_mailaddress']
            )

            sprintf(@configure[:formatter][:controller][:ebay][:us]
              .add_item[result[:ack]],
              result
            )
          end

          def revise_item(result)

            result.store(:tweyes_link, 
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+result['item_id'].to_s
            )
            result.store(:ebay_us_paypal_mailaddress,
              result['item_condition_ebay_us_paypal_mailaddress']
            )
            result.store(:ebay_us_paypal_mailaddress,
              result['item_condition_ebay_us_paypal_mailaddress']
            )

            sprintf(@configure[:formatter][:controller][:ebay][:us]
              .revise_item[result[:ack]],
              result
            )
          end

          def relist_item(result)

            result.store(:tweyes_link, 
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+result['item_id'].to_s
            )
            result.store(:ebay_us_paypal_mailaddress,
              result['item_condition_ebay_us_paypal_mailaddress']
            )

            sprintf(@configure[:formatter][:controller][:ebay][:us]
              .relist_item[result[:ack]],
              result
            )
          end

          def end_item(item)
            sprintf(@configure[:formatter][:controller][:ebay][:us]
              .end_item['success'],
              item['item_product_name'],
              item['item_ebay_us_url'],
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          def get_session_id(result)

            result.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+result[:id].to_s
            )

            sprintf(@configure[:formatter][:controller][:ebay][:us]
              .get_session_id[result[:ack]],
              result
            )
          end

          def fetch_token(result)

            result.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+result[:id].to_s
            )

            sprintf(@configure[:formatter][:controller][:ebay][:us]
              .fetch_token[result[:ack]],
              result
            )
          end

          def get_item_transactions(result)

            result.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+result[:item_id].to_s
            )

            if result[:shipping_address_phone].nil?

              result.store(:shipping_address_phone, '')
            end

            sprintf(@configure[:formatter][:controller][:ebay][:us]
              .get_item_transactions[result[:ack]],
              result
            )
          end

          protected

          private

        end ### class Us < Ebay [END]

      end ### class Ebay [END]

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
