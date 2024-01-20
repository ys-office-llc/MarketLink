module TWEyes

  module API ### 名前空間

    class ChatWork

      include TWEyes::Mixin::Protocols::HTTP::FaradayX

      @@room_ids  = []
      @@rates     = {}
      @@token     = nil
      @@connector = nil
      @@url       = nil
      @@version   = nil

      public

      def initialize(configure)

        super()

        @configure = configure
        @@version  = @configure[:api][:chatwork].get_version
        @@url      = @configure[:api][:chatwork].get_url

        @rates = {
          limit: @configure[:api][:chatwork].get_ratelimit,
          remaining: @configure[:api][:chatwork].get_ratelimit,
          reset: @configure[:api][:chatwork].get_ratelimit,
        }

        @@token = @configure[:api][:chatwork].get_default_token
        @@room_ids = [
          @configure[:api][:chatwork].get_default_room_id,
          @configure[:api][:chatwork].rooms['customer'],
          @configure[:api][:chatwork].rooms['monitoring'],
        ]
      end

      def initializeX

        @@token = @configure[:system][:user]
                  .account['chatwork_api_tokens']
                  .split(',')
                  .sample
        @@room_ids = [
          @configure[:api][:chatwork].get_default_room_id,
          @configure[:api][:chatwork].rooms['customer'],
          @configure[:api][:chatwork].rooms['monitoring'],
          @configure[:system][:user].account['chatwork_room1_id'],
          @configure[:system][:user].account['chatwork_room2_id'],
          @configure[:system][:user].account['chatwork_room3_id'],
          @configure[:system][:user].account['chatwork_room4_id'],
          @configure[:system][:user].account['chatwork_room5_id'],
          @configure[:system][:user].account['chatwork_room6_id'],
        ]
      end

      def set_default_token

        @@token = @configure[:api][:chatwork].get_default_token
      end

      def create_rooms(name, description)

        ids = []

        ids.push(
          @configure[:system][:user]
          .account['chatwork_account_id']
        )
        ids.concat(
          @configure[:system][:user]
          .account['chatwork_members_readonly_ids']
          .to_s
          .split(',')
        )

        response = get_connector(@@url).post do |request|
          request.url sprintf("%s/rooms",
            @@version
          )
          request.headers = {
            'X-ChatWorkToken': @configure[:system][:user]
                               .account['chatwork_api_admin_token']
          }
          request.body = {
            description: description,
            icon_preset: 'business',
            members_admin_ids: @configure[:system][:user]
                               .account['chatwork_members_admin_ids'],
            members_member_ids: @configure[:system][:user]
                               .account['chatwork_members_member_ids'],
            members_readonly_ids: ids.join(','),
            name: name,
          }
        end
        @@rates[:limit]     = response.headers['x-ratelimit-limit'].to_i
        @@rates[:remaining] = response.headers['x-ratelimit-remaining'].to_i
        @@rates[:reset]     = response.headers['x-ratelimit-reset'].to_i

        decide_response_status(response)
      rescue => e

        raise(
          TWEyes::Exception::API::ChatWork.new(
            e.class,
            e.backtrace
          ), e)
      end

      def delete_rooms(room_id, action_type)

        response = get_connector(@@url).delete do |request|

          request.url sprintf("%s/rooms/%s",
            @@version,
            room_id
          )
          request.headers = {
            'X-ChatWorkToken': @configure[:system][:user]
                               .account['chatwork_api_admin_token']
          }
          request.body = {
            action_type: action_type.to_s,
          }
        end
        @@rates[:limit]     = response.headers['x-ratelimit-limit'].to_i
        @@rates[:remaining] = response.headers['x-ratelimit-remaining'].to_i
        @@rates[:reset]     = response.headers['x-ratelimit-reset'].to_i

        sleep(@configure[:api][:chatwork].get_interval_notify)

        decide_response_status(response)
      rescue => e

        raise(
          TWEyes::Exception::API::ChatWork.new(
            e.class,
            e.backtrace
          ), e)
      end

      def push_message(index, string)

        if @@room_ids.size === 1

          index = 0
        end

        response = get_connector(@@url).post do |request|
          request.url sprintf("%s/rooms/%s/messages",
            @@version,
            @@room_ids[index]
          )
          request.headers = {
            ### 'X-ChatWorkToken' => @@token
            'X-ChatWorkToken' => get_random_token
          }
          request.params[:body] = sprintf("%s", string[0..3072])
        end
        @@rates[:limit]     = response.headers['x-ratelimit-limit'].to_i
        @@rates[:remaining] = response.headers['x-ratelimit-remaining'].to_i
        @@rates[:reset]     = response.headers['x-ratelimit-reset'].to_i
    
        sleep(@configure[:api][:chatwork].get_interval_notify)

        decide_response_status(response)
      rescue => e

        raise(
          TWEyes::Exception::API::ChatWork.new(
            e.class,
            e.backtrace
          ), e)
      end

      def assign_tasks(room_id, body, limit, to_ids)

        response = get_connector(@@url).post do |request|
          request.url sprintf("%s/rooms/%s/tasks",
            @@version,
            room_id
          )
          request.headers = {
            'X-ChatWorkToken' => get_random_token
          }
          request.params[:body] = sprintf("%s", body[0..3072])
          request.params[:limit] = limit
          request.params[:to_ids] = to_ids
        end
        @@rates[:limit]     = response.headers['x-ratelimit-limit'].to_i
        @@rates[:remaining] = response.headers['x-ratelimit-remaining'].to_i
        @@rates[:reset]     = response.headers['x-ratelimit-reset'].to_i

        sleep(@configure[:api][:chatwork].get_interval_notify)

        decide_response_status(response)
      rescue => e

        raise(
          TWEyes::Exception::API::ChatWork.new(
            e.class,
            e.backtrace
          ), e)
      end

      def get_incoming_requests

        response = get_connector(@@url).get do |request|
          request.url sprintf("%s/incoming_requests",
            @@version
          )
          request.headers = {
            'X-ChatWorkToken': @configure[:api][:chatwork].get_default_token,
          }
        end
        @@rates[:limit]     = response.headers['x-ratelimit-limit'].to_i
        @@rates[:remaining] = response.headers['x-ratelimit-remaining'].to_i
        @@rates[:reset]     = response.headers['x-ratelimit-reset'].to_i

        decide_response_status(response)
      rescue => e

        raise(
          TWEyes::Exception::API::ChatWork.new(
            e.class,
            e.backtrace
          ), e)
      end

      def put_incoming_requests(request_id)

        response = get_connector(@@url).put do |request|
          request.url sprintf("%s/incoming_requests/%s",
            @@version,
            request_id
          )
          request.headers = {
            'X-ChatWorkToken': @configure[:system][:user]
                               .account['chatwork_api_admin_token']
          }
        end
        @@rates[:limit]     = response.headers['x-ratelimit-limit'].to_i
        @@rates[:remaining] = response.headers['x-ratelimit-remaining'].to_i
        @@rates[:reset]     = response.headers['x-ratelimit-reset'].to_i

        decide_response_status(response)
      rescue => e

        raise(
          TWEyes::Exception::API::ChatWork.new(
            e.class,
            e.backtrace
          ), e)
      end

      def to_reshape

        ids    = []
        names  = []
        shaped = []

        ids.push(
          @configure[:system][:user]
          .account['chatwork_account_id']
        )
        ids.concat(
          @configure[:system][:user]
          .account['chatwork_members_readonly_ids']
          .to_s
          .split(',')
        )
        names.push(
          @configure[:system][:user]
          .account['user_name_ja']
        )
        names.concat(
          @configure[:system][:user]
          .account['chatwork_members_readonly_names']
          .to_s
          .split(',')
        )

        if ids.size != names.size

          raise("閲覧メンバーのIDと名前が一致していません")
        end

        ids.each_with_index do |e, i|

          shaped.push(sprintf("[To:%s] %sさん", e, names[i]))
        end

        shaped.join("\n")
      rescue => e

        raise(
          TWEyes::Exception::API::ChatWork.new(
            e.class,
            e.backtrace
          ), e)
      end
      protected

      private

      def decide_response_status(response)

        case response.status
        when 200 then

          JSON.parse(response.body)
        when 204 then

          []
        when 400 then

          raise JSON.parse(response.body)['errors'].pop
        else

          doc = Nokogiri::HTML.parse(response.body)
          if doc.xpath('//title').empty?

            raise doc.xpath('//p').text
          else

            raise doc.xpath('//title').text
          end
        end
      end

      def get_random_token

        tokens = nil

        tokens = @configure[:system][:user]
                 .account['chatwork_api_tokens']


        if tokens.to_s.size > 0

          tokens.split(',').sample
        else

          @@token
        end
      end

    end ### class ChatWork [END]
  
  end
end
