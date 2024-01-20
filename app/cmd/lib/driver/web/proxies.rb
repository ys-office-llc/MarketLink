module TWEyes

  module Driver ### 名前空間

    module Web ### 名前空間

      class Proxies

        public

        def initialize(configure)
          super()
          @configure = configure
        end

        protected

        private

        class MPP < Proxies
  
          public
  
          def initialize(configure)
            super
          end

          def fetch_proxies_full

            proxies      = nil
            proxies_path = nil

            uri = sprintf(
                    "%s/fetchProxies/json/full/%s",
                    @configure[:driver][:web][:proxies][:mpp].get_api_url,
                    @configure[:driver][:web][:proxies][:mpp].get_api_key
                  )

            proxies_path = sprintf(
                             "%s/%s.json",
                             @configure[:system][:directory].get_spool_path,
                             __method__
                           )

            begin

              proxies = open(uri).read

              if JSON.parse(proxies).size.zero?

                raise('Service Suspension Notification')
              end

              File.write(proxies_path, proxies)

              JSON.parse(proxies)
            rescue Net::ReadTimeout,
                   Errno::ETIMEDOUT,
                   Errno::ECONNRESET => e

              JSON.parse(File.read(proxies_path))
            end
          rescue => e

            raise(TWEyes::Exception::Driver::Web::Proxies::MPP.new(), e)
          end

          protected
  
          private
  
        end ### class MPP [END]

      end ### class Proxies [END]

    end ### class Selenium [END]
  
  end
end
