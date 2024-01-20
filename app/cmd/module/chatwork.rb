require_relative '../lib/application'

module TWEyes

  class ChatWork < Application

    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def discard

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        begin

          if account['chatwork_id'].to_s.size > 0 and
             account['chatwork_contact_with_admin'].zero? and
             account['chatwork_account_id'].to_s.size.zero?

            @builder[:mysql][:account].update(
              account['id'],
              {
                chatwork_id: nil,
              }
            )

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['customer'],
              @formatter[:chatwork][:self].discard(account.symbolize_keys)
            )
          end
        rescue TWEyes::Exception::API::ChatWork => e

         account.merge!(
            message: e.message,
          )

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['customer'],
            @formatter[:chatwork][:self].approve(account.symbolize_keys)
          )
        end
      end
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def operate

      incoming_requests = []

      incoming_requests = @api[:chatwork].get_incoming_requests

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        if incoming_requests.size > 0

          request(incoming_requests, account)
        end

        create_rooms(account)
        delete_rooms(account)
      end
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    protected

    private

    def initializeX
    end

    def request(incoming_requests, account)

      response = nil

      begin

        incoming_requests.each do |request|

          if account['chatwork_id'].to_s.size > 0 and
             account['chatwork_contact_with_admin'].zero? and
             request['chatwork_id']
             .match(/^#{account['chatwork_id']}$/)

            response = @api[:chatwork]
                       .put_incoming_requests(
                         request['request_id']
                       )

            @builder[:mysql][:account].update(
              account['id'],
              {
                chatwork_contact_with_admin: 1,
                chatwork_account_id: response['account_id'],
              }
            )

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['customer'],
              @formatter[:chatwork][:self].approve(account.symbolize_keys)
            )
          end
        end
      rescue TWEyes::Exception::API::ChatWork => e

        account.merge!(
          message: e.message,
        )

        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['customer'],
          @formatter[:chatwork][:self].approve(account.symbolize_keys)
        )
      end
    end

    def create_rooms(account)

      if account['chatwork_create_rooms'] > 0 and
         account['chatwork_room1_id'].to_s.size.zero? and
         account['chatwork_room2_id'].to_s.size.zero? and
         account['chatwork_room3_id'].to_s.size.zero? and
         account['chatwork_room4_id'].to_s.size.zero? and
         account['chatwork_room5_id'].to_s.size.zero? and
         account['chatwork_account_id'].to_s.size > 0

        @builder[:mysql][:account].update(
          account['id'],
          {
            chatwork_create_rooms: 0,
            chatwork_room1_id:
              @api[:chatwork].create_rooms(
                "マーケットウォッチ（#{account['user_name_ja']}）",
                "マーケットウォッチ（#{account['user_name_ja']}）"
              )['room_id'],
            chatwork_room2_id:
              @api[:chatwork].create_rooms(
                "ヤフオクウォッチ（#{account['user_name_ja']}）",
                "ヤフオクウォッチ（#{account['user_name_ja']}）"
              )['room_id'],
            chatwork_room3_id:
              @api[:chatwork].create_rooms(
                "ストアウォッチ（#{account['user_name_ja']}）",
                "ストアウォッチ（#{account['user_name_ja']}）"
              )['room_id'],
            chatwork_room4_id:
              @api[:chatwork].create_rooms(
                "商品管理（#{account['user_name_ja']}）",
                "商品管理（#{account['user_name_ja']}）"
              )['room_id'],
            chatwork_room5_id:
              @api[:chatwork].create_rooms(
                "経営管理（#{account['user_name_ja']}）",
                "経営管理（#{account['user_name_ja']}）"
              )['room_id'],
            chatwork_room6_id:
              @api[:chatwork].create_rooms(
                "フリマウォッチ（#{account['user_name_ja']}）",
                "フリマウォッチ（#{account['user_name_ja']}）"
              )['room_id'],
          }
        )
      end
    rescue TWEyes::Exception::API::ChatWork => e

      @builder[:mysql][:account].update(
        account['id'],
        {
          chatwork_create_rooms: 0,
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['customer'],
        @formatter[:exception].handle(e)
      )
    end

    def delete_rooms(account)

      if account['chatwork_delete_rooms'] > 0

        account.select do |k, v|

          k.match(/^chatwork_room(\d)_id$/) and
          v.to_s.size > 0
        end.each do |n|

          @api[:chatwork].delete_rooms(n[1], :delete)
          @builder[:mysql][:account].update(
            account['id'],
            {
              "#{n[0]}": '',
            }
          )
        end

        @builder[:mysql][:account].update(
          account['id'],
          {
            chatwork_delete_rooms: 0,
            chatwork_id: nil,
            chatwork_account_id: nil,
            chatwork_contact_with_admin: 0,
          }
        )
      end
    rescue TWEyes::Exception::API::ChatWork => e

      @builder[:mysql][:account].update(
        account['id'],
        {
          chatwork_delete_rooms: 0,
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['customer'],
        @formatter[:exception].handle(e)
      )
    end
  end ### class ChatWork [END]
end
