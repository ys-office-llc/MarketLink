module TWEyes

  module Formatter ### 名前空間

    module Orchestrator ### 名前空間

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

          def conduct(item, name)
            sprintf(@configure[:formatter][:orchestrator][:ebay][:us]
              .conduct,
              item['item_product_name'],
              name,
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          protected

          private

        end ### class Us [END]

      end ### class Ebay [END]

    end ### module Orchestrator

  end ### module Formatter

end ### module TWEyes
