module TWEyes

  module Formatter ### 名前空間

    module Controller ### 名前空間

      class Monitor

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        protected

        private

        class Process < Monitor

          public

          def initialize(configure)

            super
          end

          def reboot(result)

            result.store(:host, @configure[:system][:net].get_domain)

            if result[:status].zero?

              sprintf(
                @configure[:formatter][:controller][:monitor][:process][:self]
                .reboot['success'],
                result
              )
            else

              sprintf(
                @configure[:formatter][:controller][:monitor][:process][:self]
                .reboot['failure'],
                result
              )
            end

          end

          def notice(result)

            result.store(:host, @configure[:system][:net].get_domain)
            sprintf(
              @configure[:formatter][:controller][:monitor][:process][:self]
              .notice['success'],
              result
            )

          end

          protected

          private

        end ### class Process < Monitor

      end ### class Monitor

    end ### module Controller

  end ### module Formatter

end ### module TWEyes
