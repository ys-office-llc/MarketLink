module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      module Stores ### 名前空間

      class FujiyaCamera

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        def new_arrival(item)
          item.store(:tweyes_link,
            @configure[:system][:net]
            .get_https_uri+'/research/new/arrival/get/'+
            item[:research_new_arrival_id].to_s
          )

          sprintf(
            @configure[:formatter][:controller][:stores][
              :fujiya_camera
            ][:self].new_arrival,
            item
          )
        end

        protected

        private

      end ### class FujiyaCamera [END]

    end ### module Stores

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
