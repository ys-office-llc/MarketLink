module TWEyes

  module Formatter ### 名前空間

    module Orchestrator ### 名前空間

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

          def conduct(item, name)
            sprintf(@configure[:formatter][:orchestrator][:yahoo][:auctions]
              .conduct,
              item['item_product_name'],
              name,
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          protected

          private

        end ### class Auctions [END]

        class Shopping < Yahoo

          public

          def initialize(configure)
            super
          end

          def add_item(item)
            sprintf(@configure[:formatter][:controller][:yahoo][:shopping]
              .add_item['success'],
              item['item_product_name'],
              item['item_start_price'].to_yen,
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          def end_item(item)
            sprintf(@configure[:formatter][:controller][:yahoo][:auctions]
              .end_item['success'],
              item['item_product_name'],
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          protected

          private

        end ### class Shopping [END]

      end ### class Yahoo [END]

    end ### module Orchestrator

  end ### module Formatter

end ### module TWEyes
