module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      class Yahoo

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        protected

        private

        class Auctions < Yahoo

          public

          def initialize(configure)
            super
          end

          def add_item(item)
            sprintf(@configure[:formatter][:controller][:yahoo][:auctions][:self]
              .add_item['success'],
              item['item_yahoo_auctions_product_name'],
              item['item_yahoo_auctions_start_price'].to_yen,
              item['item_yahoo_auctions_end_price'].to_yen,
              item['item_yahoo_auctions_url'],
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          def resubmit_item(item)
            sprintf(@configure[:formatter][:controller][:yahoo][:auctions][:self]
              .resubmit_item['success'],
              item['item_yahoo_auctions_product_name'],
              item['item_yahoo_auctions_start_price'].to_yen,
              item['item_yahoo_auctions_end_price'].to_yen,
              item['item_yahoo_auctions_url'],
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          def end_item(item)
            sprintf(@configure[:formatter][:controller][:yahoo][:auctions][:self]
              .end_item['success'],
              item['item_yahoo_auctions_product_name'],
              item['item_yahoo_auctions_url'],
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          def place_bids(item)

            item.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/bids/get/'+item[:bids_id].to_s
            )

            if item[:message].nil?

              case item[:state_id]
              when @configure[:builder][:mysql][:bids]
                   .get_state['bidding']

                sprintf(
                  @configure[:formatter][:controller][:yahoo][:auctions][:self]
                  .place_bids['bids']['success'],
                  item
                )
              when @configure[:builder][:mysql][:bids]
                   .get_state['win']

                sprintf(
                  @configure[:formatter][:controller][:yahoo][:auctions][:self]
                  .place_bids['win'],
                  item
                )
              when @configure[:builder][:mysql][:bids]
                   .get_state['end']

                sprintf(
                  @configure[:formatter][:controller][:yahoo][:auctions][:self]
                  .place_bids['end'],
                  item
                )
              end
            else

              sprintf(
                @configure[:formatter][:controller][:yahoo][:auctions][:self]
                .place_bids['bids']['failure'],
                item
              )
            end
          end

          def place_value_comment(item)

            item.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+item[:item_id].to_s
            )

            if item[:message].nil?

                sprintf(
                  @configure[:formatter][:controller][:yahoo][:auctions][:self]
                  .place_value_comment['success'],
                  item
                )
            else

              sprintf(
                @configure[:formatter][:controller][:yahoo][:auctions][:self]
                .place_value_comment['failure'],
                item
              )
            end
          end

          def get_shipping_information(item)

            item.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+item[:item_id].to_s
            )

            if item[:message].nil?

                sprintf(
                  @configure[:formatter][:controller][:yahoo][:auctions][:self]
                  .get_shipping_information['success'],
                  item
                )
            else

              sprintf(
                @configure[:formatter][:controller][:yahoo][:auctions][:self]
                .get_shipping_information['failure'],
                item
              )
            end
          end

          protected

          private

        class API < Auctions

          public

          def initialize(configure)
            super
          end

          def search(item)

            item.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/research/yahoo/auctions/search/get/'+
              item['research_yahoo_auctions_search_id'].to_s
            )

            if item[:message].nil?

              sprintf(
                @configure[:formatter][:controller][:yahoo][:api][
                  :auctions
                ][:self].search['success'],
                item
              )
            else

              sprintf(
                @configure[:formatter][:controller][:yahoo][:api][
                  :auctions
                ][:self].search['failure'],
                item
              )
            end

          end

          protected

          private

        end ### class API < Auctions [END]

        end ### class Auctions [END]

        class Developer < Yahoo

          public

          def initialize(configure)
            super
          end

          def get_application(account)

            account.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+account[:id].to_s
            )

            sprintf(@configure[:formatter][:controller][:yahoo][:developer][:self]
              .get_application[account[:ack]],
              account
            )
          end

          def create_application(account)

            account.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+account[:id].to_s
            )

            sprintf(@configure[:formatter][:controller][:yahoo][:developer][:self]
              .create_application[account[:ack]],
              account
            )
          end

          def update_application(account)

            account.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+account[:id].to_s
            )
            account.store(
              :callback_url,
              @configure[:system][:net].get_http_uri
            )

            sprintf(@configure[:formatter][:controller][:yahoo][:developer][:self]
              .update_application[account[:ack]],
              account
            )
          end

          protected

          private

        end ### class Developer < Yahoo [END]

        class Mechanize < Yahoo

          public

          def initialize(configure)
            super
          end

          def get_captcha(account)

            account.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+account[:id].to_s
            )

            sprintf(@configure[:formatter][:controller][:yahoo][:mechanize][:self]
              .get_captcha[account[:ack]],
              account
            )
          end

          def set_cookie(account)

            account.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+account[:id].to_s
            )

            sprintf(@configure[:formatter][:controller][:yahoo][:mechanize][:self]
              .set_cookie[account[:ack]],
              account
            )
          end

          def purge_expired_cookies(account)

            account.store(:tweyes_link,
              @configure[:system][:net]
              .get_https_uri+'/setting/account/get/'+account[:id].to_s
            )

            sprintf(@configure[:formatter][:controller][:yahoo][:mechanize][:self]
              .purge_expired_cookies[account[:ack]],
              account
            )
          end

          protected

          private

        end ### class Mechanize < Yahoo [END]

        class Shopping < Yahoo

          public

          def initialize(configure)
            super
          end

          def add_item(item)
            sprintf(@configure[:formatter][:controller][:yahoo][:shopping]
              .add_item['success'],
              item['item_yahoo_auctions_product_name'],
              item['item_start_price'].to_yen,
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          def end_item(item)
            sprintf(@configure[:formatter][:controller][:yahoo][:shopping]
              .end_item['success'],
              item['item_yahoo_auctions_product_name'],
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          protected

          private

        end ### class Shopping [END]

      end ### class Yahoo [END]

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
