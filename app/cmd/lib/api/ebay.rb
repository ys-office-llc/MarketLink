module TWEyes

  module API ### 名前空間

    class Ebay 
      include TWEyes::Mixin::Protocols::HTTP::FaradayX

      public

      def initialize(configure)
        super()

        @configure = configure
        @headers   = {}
        @headers['X-EBAY-API-COMPATIBILITY-LEVEL'] =
          @configure[:api][:ebay][:self].get_compatibility_level
        @headers['X-EBAY-API-SITEID'] = '0'
      end

      def initializeX
      end

      protected

      private

      class Trading < Ebay

        public

        def initialize(configure)
          super
        end

        def initializeX
          super
        end

        protected

        private

        class Production < Trading

          public

          def initialize(configure)
            super
            @headers['X-EBAY-API-DEV-NAME'] =
              @configure[:api][:ebay][:trading][:production].get_dev_name
            @headers['X-EBAY-API-APP-NAME'] =
              @configure[:api][:ebay][:trading][:production].get_app_name
            @headers['X-EBAY-API-APP-NAME'] =
              @configure[:api][:ebay][:trading][:production].get_app_name
            @headers['X-EBAY-API-CERT-NAME'] =
              @configure[:api][:ebay][:trading][:production].get_cert_name
            @endpoint = @configure[:api][:ebay][:trading][:production]
                        .get_endpoint
            @url      = @configure[:api][:ebay][:trading][:production]
                        .get_url
          end

          def initializeX
            super
          end

          def request_post(call_name, xml)
            response = get_connector(@endpoint).post do |request|
              request.url @url
              request.headers = @headers
              request.headers['X-EBAY-API-CALL-NAME'] = call_name
              request.body = xml
            end

            response.body
          rescue => e
            raise(
              TWEyes::Exception::API::Ebay::Trading::Production.new(
                e.class,
                e.backtrace), e)
          end

        end ### class Production < Trading [END]

      end ### class Trading < Ebay [END]

      class Finding < Ebay

        public

        def initialize(configure)
          super

          @headers = {}

          @headers['X-EBAY-SOA-SERVICE-NAME'] =
            @configure[:api][:ebay][:finding][:production][:self]
            .get_service_name
          @headers['X-EBAY-SOA-SERVICE-VERSION'] =
            @configure[:api][:ebay][:finding][:production][:self]
            .get_service_version
          @headers['X-EBAY-SOA-GLOBAL-ID'] =
            @configure[:api][:ebay][:finding][:production][:self]
            .get_global_id
          @headers['X-EBAY-SOA-SECURITY-APPNAME'] =
            @configure[:api][:ebay][:finding][:production][:self]
            .get_security_appname
          @headers['X-EBAY-SOA-REQUEST-DATA-FORMAT'] =
            @configure[:api][:ebay][:finding][:production][:self]
            .get_request_data_format
        end

        def initializeX
          super
        end

        protected

        private

        class Production < Finding

          public

          def initialize(configure)

            super

            @endpoint = @configure[:api][:ebay][:finding][:production][:self]
                        .get_endpoint
            @url      = @configure[:api][:ebay][:finding][:production][:self]
                        .get_url
          end

          def initializeX
            super
          end

          def request_post(operation_name, xml)
            response = get_connector(@endpoint).post do |request|
              request.url @url
              request.headers = @headers
              request.headers['X-EBAY-SOA-OPERATION-NAME'] = operation_name
              request.body = xml
            end

            response.body
          rescue => e
            raise(
              TWEyes::Exception::API::Ebay::Finding::Production.new(
                e.class,
                e.backtrace), e)
          end

        end ### class Production < Finding [END]

      end ### class Finding < Ebay [END]

      class BusinessPoliciesManagement < Ebay

        public

        def initialize(configure)
          super

          @headers = {}

          @headers['X-EBAY-SOA-SERVICE-NAME'] =
            @configure[:api][:ebay][
              :business_policies_management
            ][:production][:self]
            .service_name
          @headers['X-EBAY-SOA-SERVICE-VERSION'] =
            @configure[:api][:ebay][
              :business_policies_management
            ][:production][:self]
            .service_version
          @headers['X-EBAY-SOA-GLOBAL-ID'] =
            @configure[:api][:ebay][
              :business_policies_management
            ][:production][:self]
            .global_id
          @headers['X-EBAY-SOA-REQUEST-DATA-FORMAT'] =
            @configure[:api][:ebay][
              :business_policies_management
            ][:production][:self]
            .request_data_format
        end

        def initializeX

          super
        end

        protected

        private

        class Production < BusinessPoliciesManagement

          public

          def initialize(configure)

            super

            @endpoint = @configure[:api][:ebay][
                          :business_policies_management
                        ][:production][:self]
                        .endpoint
            @url      = @configure[:api][:ebay][
                          :business_policies_management
                        ][:production][:self]
                        .url
          end

          def initializeX

            super
          end

          def request_post(operation_name, auth_token, xml)

            response = get_connector(@endpoint).post do |request|

              request.url @url
              request.headers = @headers
              request.headers['X-EBAY-SOA-OPERATION-NAME'] = operation_name
              request.headers['X-EBAY-SOA-SECURITY-TOKEN'] = auth_token
              request.body = xml
            end

            response.body
          rescue => e
            raise(
              TWEyes::Exception::API::Ebay::BusinessPoliciesManagement::Production.new(
                e.class,
                e.backtrace), e)
          end

        end ### class Production < BusinessPoliciesManagement [END]

      end ### class BusinessPoliciesManagement < Ebay [END]

    end ### class Ebay [END]

  end ### module API [END]

end ### module TWEyes [END]
