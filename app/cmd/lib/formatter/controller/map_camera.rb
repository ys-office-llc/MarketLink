module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      module Stores ### 名前空間

      class MapCamera

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
              :map_camera
            ][:self].new_arrival,
            item
          )
        end

        protected

        private

      end ### class MapCamera [END]

    end ### module Stores

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
