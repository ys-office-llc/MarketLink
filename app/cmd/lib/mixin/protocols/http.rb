module TWEyes
  module Mixin
    module Protocols
      module HTTP

        module MechanizeX

          @agent = nil

          def get(url, param)

            page = nil

            reconnect = {
              current: 0,
              limit: 10,
              waiting_for: 10,
            }

            begin

              (1..reconnect[:limit]).each do |i|

                if page = @agent.get(url, param)

                  return page
                else

                  sleep(reconnect[:waiting_for])
                end
              end

              page
            rescue => e

              pp e.class, e.message, reconnect

              if e.message.match(/^404|410/)

                nil
              elsif reconnect[:limit] > reconnect[:current]

                sleep(reconnect[:waiting_for])
                reconnect[:current] = reconnect[:current].succ

                retry
              else

                raise(
                  sprintf(
                    "Reconnection abandonment: %s, %s, %s",
                    e.class,
                    e.message,
                    reconnect
                  )
                )
              end
            end
          end

        end ### module MechanizeX [END]

        module FaradayX

          def get_connector(url)
            Faraday::Connection.new(url: url) do |builder|
              builder.use Faraday::Request::UrlEncoded
              builder.use Faraday::Adapter::NetHttp
              builder.use Faraday::Response::Logger
            end
          end

        end

        module NetHTTP

          def get(url, use_ssl, headers, params)
            uri       = URI.parse(url)
            uri.query = URI.encode_www_form(params)
            http      = Net::HTTP.new(uri.host, uri.port)
            request   = Net::HTTP::Get.new(uri.request_uri)
            if use_ssl
              http.use_ssl     = true
              http.verify_mode = OpenSSL::SSL::VERIFY_NONE
            end
            http.set_debug_output $stderr
            headers.each do |k,v|
              request[k] = v
            end

            http.request(request).body
          end

          def post(url, use_ssl, headers, params, account, password)
            uri     = URI.parse(url)
            http    = Net::HTTP.new(uri.host, uri.port)
            request = Net::HTTP::Post.new(uri.request_uri)

            if use_ssl
              http.use_ssl     = true
              http.verify_mode = OpenSSL::SSL::VERIFY_NONE
            end

            if account and password
              request.basic_auth(account, password)
            end

            if headers
              headers.each do |k,v|
                request[k] = v
              end
            end

            request.body = URI.encode_www_form(params)
            http.set_debug_output $stderr

            http.request(request).body
          end

          def post_multipart(url, use_ssl, headers, params, file)
            uri       = URI.parse(url)
            uri.query = URI.encode_www_form(params)
            http      = Net::HTTP.new(uri.host, uri.port)
            if use_ssl
              http.use_ssl     = true
              http.verify_mode = OpenSSL::SSL::VERIFY_NONE
            end
            request =  Net::HTTP::Post::Multipart.new(
              uri.request_uri,
              file: UploadIO.new(
                File.new(file[:path]),
                file[:mime_type],
                file[:name]
              )
            )
            http.set_debug_output $stderr
            headers.each do |k,v|
              request[k] = v
            end

            http.request(request).body
          end

        end

      end
    end
  end
end
