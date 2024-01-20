module TWEyes

  module API ### 名前空間

    class Yahoo

      public

      def initialize(configure)
        super()

        @configure = configure
        @builder   = nil
        @headers   = {}
        @token     = nil
      end

      def initializeX(builder)
        @builder = builder

        if @builder.get_token.size > 0
          @token = @builder.get_token['access_token']
        end
      end

      protected

      private

      class Auctions < Yahoo
        include TWEyes::Mixin::Protocols::HTTP::NetHTTP

        public

        def initialize(configure)
          super

          @headers = {}
        end

        def initializeX(builder)
          super
        end

        def request_get(method, params)
          set_header(method)
          get(
            resolve(method),
            true,
            @headers,
            params
          ).gsub(/[\n\t]/, '')
        rescue => e
          raise(
            TWEyes::Exception::API::Yahoo::Auctions.new(),
            e
          )
        end

        protected

        private

        def resolve(method)
          sprintf("%s/%s",
            @configure[:api][:yahoo][:auctions].get_endpoint,
            @configure[:api][:yahoo][:auctions].get_url[method.to_s]
          )
        end

        def set_header(method)
          case method
          when :search
            @headers['User-Agent'] = sprintf("Yahoo AppID: %s",
              @configure[:system][:user].account['yahooapis_buyer_appid']
            )
          else
            @headers.delete('User-Agent')
            @headers['Authorization'] = sprintf("Bearer %s", @token)
          end
        end

      end ### class Auctions [END]

      class Shopping < Yahoo
        include TWEyes::Mixin::Protocols::HTTP::NetHTTP

        public

        def initialize(configure)
          super
        end

        def initializeX(builder)
          super
          @headers['User-Agent'] = sprintf("Yahoo AppID: %s",
            @configure[:system][:user].account['yahooapis_seller_appid']
          )
        end

        def request_get(method, params)
          get(
            resolve(method),
            true,
            @headers,
            params
          ).gsub(/[\n\t]/, '')
        rescue => e
          raise(
            TWEyes::Exception::API::Yahoo::Shopping.new(),
            e
          )
        end

        def request_post(method, params)
          post(
            resolve(method),
            true,
            @headers,
            params,
            nil,
            nil
          ).gsub(/[\n\t]/, '')
        rescue => e
          raise(
            TWEyes::Exception::API::Yahoo::Shopping.new(),
            e
          )
        end

        protected

        private

        def resolve(method)
          sprintf("%s/%s",
            @configure[:api][:yahoo][:shopping][:self].get_endpoint,
            @configure[:api][:yahoo][:shopping][:self].get_url[method.to_s]
          )
        end

        class Circus < Shopping
          include TWEyes::Mixin::Protocols::HTTP::NetHTTP
 
          public
 
          def initialize(configure)
            super
          end
 
          def initializeX(builder)
            super
            @headers['Authorization'] = sprintf("Bearer %s", @token)
            ### 設計に穴がある (2016-09-18)
            @headers.delete('User-Agent')
          end
 
          def request_get(method, params)
            get(
              resolve(method),
              true,
              @headers,
              params
            ).gsub(/[\n\t]/, '')
          rescue => e
            raise(
              TWEyes::Exception::API::Yahoo::Shopping::Circus.new(),
              e
            )
          end
 
          def request_post(method, params)
            post(
              resolve(method),
              true,
              @headers,
              params,
              nil,
              nil
            ).gsub(/[\n\t]/, '')
          rescue => e
            raise(
              TWEyes::Exception::API::Yahoo::Shopping::Circus.new(),
              e
            )
          end
 
          def request_post_multi(method, params, file)
            post_multipart(
              resolve(method),
              true,
              @headers,
              params,
              file
            ).gsub(/[\n\t]/, '')
          rescue => e
            raise(
              TWEyes::Exception::API::Yahoo::Shopping::Circus.new(),
              e
            )
          end
 
          protected
 
          private
 
          def resolve(method)
            sprintf("%s/%s",
              @configure[:api][:yahoo][:shopping][:circus].get_endpoint,
              @configure[:api][:yahoo][:shopping][:circus].get_url[method.to_s]
            )
          end
 
        end ### class Circus < Shopping [END]

      end ### class Shopping < Yahoo [END]

    end ### class Yahoo [END]
  
  end
end
