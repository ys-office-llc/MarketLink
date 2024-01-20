module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      class Support

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        def contact(result)

          sprintf(
            @configure[:formatter][:controller][:support][:self]
            .contact,
            result
          )

        end

        protected

        private

      end ### module Support

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
