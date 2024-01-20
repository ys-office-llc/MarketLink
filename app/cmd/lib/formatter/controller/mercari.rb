module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      module FreeMarkets ### 名前空間

      class Mercari

        public

        def initialize(configure)

          super()

          @configure = configure
        end

        def new_arrival(item)

          item.store(:tweyes_link,
            @configure[:system][:net]
            .get_https_uri+'/research/free/markets/search/get/'+
            item[:research_free_markets_search_id].to_s
          )

          sprintf(@configure[:formatter][:controller][:free_markets][:mercari][:self]
            .new_arrival,
            item
          )
        end

        protected

        private

      end ### class Kitamura [END]

    end ### module Stores

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
