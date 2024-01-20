module TWEyes

  module Formatter ### 名前空間

    module Orchestrator ### 名前空間

      class Amazon

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        protected

        private

        class Jp < Amazon

          public

          def initialize(configure)
            super
          end

          def conduct(item, name)
            sprintf(@configure[:formatter][:orchestrator][:amazon][:jp]
              .conduct,
              item['item_product_name'],
              name,
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item['item_id'].to_s
            )
          end

          def conduct2(item, name)
            sprintf(@configure[:formatter][:orchestrator][:amazon][:jp]
              .conduct,
              item[:item_product_name],
              name,
              @configure[:system][:net]
                .get_https_uri+'/item/get/'+item[:item_id].to_s
            )
          end

          protected

          private

        end ### class Jp [END]

      end ### class Amazon [END]

    end ### module Orchestrator

  end ### module Formatter

end ### module TWEyes
