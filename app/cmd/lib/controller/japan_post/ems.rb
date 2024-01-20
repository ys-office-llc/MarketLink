module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module JapanPost ### 名前空間用なので機能を持たせるとバグるよ

      class EMS

        include TWEyes::Mixin::Protocols::HTTP::MechanizeX

        public

        def initialize(configure)
          super()

          @configure = configure
          @driver    = nil
          @agent     = nil
          @pages     = []
        end

        def initializeX(driver)

          @driver = driver
          @agent  = @driver[:web][:mechanize].agent
        end

        def delivery_details(tracking_number)

           @pages.push(
             get(
               sprintf(
                 "%s/%s?",
                 @configure[:controller][:japan_post][:ems][:self]
                 .delivery_details_url,
                 @configure[:controller][:japan_post][:ems][:self]
                 .delivery_details_url_direct
              ),
              @configure[:controller][:japan_post][:ems][:self]
              .delivery_details_parameters.merge(
                {
                  reqCodeNo1: tracking_number,
                }
              )
            )
          )

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::JapanPost::EMS.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        protected

        private

        def analyze

          result = []

          @pages.each do |page|

            page
            .search('table[@summary="履歴情報"]')
            .each do |table|

              result = []
              table.search('tr/td').each do |td|

                result.push(td.text)
              end
            end
          end

pp result

          {
            ems_acceptance_datetime: result[-0],
            ems_delivery_history: result[-5],
            ems_arrival_datetime: result[-6],
          }
        end

      end ### class EMS [END]

    end ### module JapanPost [END]

  end ### module Controller [END]

end ### module TWEyes [END]
