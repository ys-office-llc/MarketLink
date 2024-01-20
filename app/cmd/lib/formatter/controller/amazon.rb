module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      class Amazon

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        protected

        private

        class Developer < Amazon

          public

          def initialize(configure)
            super
          end

          class Jp < Developer

            public

            def register(account)

              account.store(:tweyes_link,
                @configure[:system][:net]
                .get_https_uri+'/setting/account/get/'+account[:id].to_s
              )

              sprintf(
                @configure[:formatter][:controller][:amazon][:developer][:jp][:self]
                .register[account[:ack]],
                account
              )
            end

            protected

            private

          end ### class Jp < Developer [END]

          protected

          private

        end ### class Developer < Amazon [END]

        class MWS < Amazon

          public

          def initialize(configure)
            super
          end

          protected

          private

          class Feeds < MWS

            public

            def initialize(configure)
              super
            end

            def post_product_data(item)
              set_link(item)
              sprintf(
                @configure[:formatter][:controller][:amazon][:mws][:feeds]
                .post_product_data[item[:errors][:status_code].to_s],
                item
              )
            end

            def post_inventory_availability_data(item)
              set_link(item)
              sprintf(
                @configure[:formatter][:controller][:amazon][:mws][:feeds]
                .post_inventory_availability_data[
                  item[:errors][:status_code].to_s
                ],
                item
              )
            end

            def post_product_pricing_data(item)
              set_link(item)
              sprintf(
                @configure[:formatter][:controller][:amazon][:mws][:feeds]
                .post_product_pricing_data[item[:errors][:status_code].to_s],
                item
              )
            end

            def post_product_image_data(item)
              set_link(item)
              sprintf(
                @configure[:formatter][:controller][:amazon][:mws][:feeds]
                .post_product_image_data[item[:errors][:status_code].to_s],
                item
              )
            end

            protected

            private

            def set_link(item)
              item.store(:tweyes_link,
                @configure[:system][:net]
                .get_https_uri+'/item/get/'+item[:item_id].to_s
              )
            end

          end ### class Feeds < MWS [END]

          class Orders < MWS

            public

            def initialize(configure)

              super
            end

            def get_shipping_information(shipping_information)

              sprintf(
                @configure[:formatter][:controller][:amazon][:mws][:orders]
                .get_shipping_information['success'],
                shipping_information
              )
            end

          end ### class Feeds < MWS [END]

        end ### class MWS < Amazon [END]

      end ### class Amazon [END]

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
