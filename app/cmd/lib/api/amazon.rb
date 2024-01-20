module TWEyes

  module API ### 名前空間

    class Amazon

      public

      def initialize(configure)
        super()
  
        @configure = configure
      end

      class MWSX < Amazon

        attr_reader :connector

        @@merchant         = nil
        @@access_key       = nil
        @@secret_key       = nil
        @@marketplace      = nil
        @@auth_token       = nil
        @@marketplace_list = []
  
        public
  
        def initialize(configure)
          super

          @connector = nil
        end
  
        def initializeX(marketplace)
          @@merchant   = @configure[:system][:user]
                           .account[
                              sprintf("amazon_%s_merchant_id", marketplace)
                           ]
          @@access_key = @configure[:system][:user]
                           .account[
                              sprintf("amazon_%s_access_key", marketplace)
                           ]
          @@secret_key = @configure[:system][:user]
                           .account[
                              sprintf("amazon_%s_secret_key", marketplace)
                           ]
          @@marketplace = @configure[:system][:user]
                           .account[
                              sprintf("amazon_%s_marketplace_id", marketplace)
                           ]
          @@auth_token  = @configure[:system][:user]
                           .account[
                              sprintf("amazon_%s_auth_token", marketplace)
                           ]
        end
  
        protected
  
        private

        class Feeds < MWSX

          public

          def initialize(configure)
            super
          end

          def initializeX(marketplace)
            super
          end

          def connect
            @connector = MWS.feeds(
              merchant_id:            @@merchant,
              aws_access_key_id:      @@access_key,
              aws_secret_access_key:  @@secret_key,
              primary_marketplace_id: @@marketplace,
              auth_token:             @@auth_token,
            )
            @connector.connection.data[:debug_request]  = false
            @connector.connection.data[:debug_response] = false
          rescue => e
            raise(
              TWEyes::Exception::API::Amazon::MWS::Feeds.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

        end ### class Feeds [END]

        class Orders < MWSX

          public

          def initialize(configure)
            super
          end

          def initializeX(marketplace)
            super
          end

          def connect
            @connector = MWS.orders(
              merchant_id:            @@merchant,
              aws_access_key_id:      @@access_key,
              aws_secret_access_key:  @@secret_key,
              primary_marketplace_id: @@marketplace,
              auth_token:             @@auth_token,
            )
            @connector.connection.data[:debug_request]  = false
            @connector.connection.data[:debug_response] = false
          rescue => e
            raise(
              TWEyes::Exception::API::Amazon::MWS::Orders.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

        end ### class Orders [END]

        class Reports < MWSX

          public

          def initialize(configure)
            super
          end

          def initializeX(marketplace)
            super
          end

          def connect
            @connector = MWS.reports(
              merchant_id:            @@merchant,
              aws_access_key_id:      @@access_key,
              aws_secret_access_key:  @@secret_key,
              primary_marketplace_id: @@marketplace,
              auth_token:             @@auth_token,
            )
            @connector.connection.data[:debug_request]  = false
            @connector.connection.data[:debug_response] = false
          rescue => e
            raise(
              TWEyes::Exception::API::Amazon::MWS::Reports.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

        end ### class Reports [END]

        class Products < MWSX

          public

          def initialize(configure)
            super
          end

          def initializeX(marketplace)
            super
          end

          def connect
            @connector = MWS.products(
              merchant_id:            @@merchant,
              aws_access_key_id:      @@access_key,
              aws_secret_access_key:  @@secret_key,
              primary_marketplace_id: @@marketplace,
              auth_token:             @@auth_token,
            )
            @connector.connection.data[:debug_request]  = false
            @connector.connection.data[:debug_response] = false
          rescue => e
            raise(
              TWEyes::Exception::API::Amazon::MWS::Products.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

        end ### class Products [END]

        class FulfillmentInventory < MWSX

          public

          def initialize(configure)
            super
          end

          def initializeX(marketplace)
            super
          end

          def connect
            @connector = MWS.fulfillment_inventory(
              merchant_id:            @@merchant,
              aws_access_key_id:      @@access_key,
              aws_secret_access_key:  @@secret_key,
              primary_marketplace_id: @@marketplace,
              auth_token:             @@auth_token,
            )
            @connector.connection.data[:debug_request]  = false
            @connector.connection.data[:debug_response] = false
          rescue => e
            raise(
              TWEyes::Exception::API::Amazon::MWS::FulfillmentInventory.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

        end ### class Products [END]

      end ### class MWSX [END]

    end ### class Amazon [END]
  
  end

end
