require_relative '../lib/application'
require_relative 'auth'

module TWEyes

  class Developer < Application

    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
      @auth = Auth.new
    end

    def yahoo

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:chatwork][:self].permit?

          next
        end

        collect_application(account, :seller)
        collect_application(account, :buyer)
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def amazon

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account].get_records.each do |account|

        result = {}

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:chatwork][:self].permit?

          next
        end

        begin

          unless account[sprintf("amazon_%s_account", :jp)]
                 .to_s.size > 0 and
                 account[sprintf("amazon_%s_password", :jp)]
                 .to_s.size > 0 and
                 account[sprintf("amazon_%s_marketplace_id", :jp)]
                 .to_s.size.zero? and
                 account[sprintf("amazon_%s_merchant_id", :jp)]
                 .to_s.size.zero? and
                 account[sprintf("amazon_%s_access_key", :jp)]
                 .to_s.size.zero? and
                 account[sprintf("amazon_%s_secret_key", :jp)]
                 .to_s.size.zero? and
                 account[sprintf("amazon_%s_auth_token", :jp)]
                 .to_s.size.zero?

            next
          end

          @controller[:amazon][:developer][:jp][:self].initializeX
          ###set_proxy_static
          @driver[:web][:selenium].create_driver(:phantomjs)
          result = @controller[:amazon][:developer][:jp][:self].register(
            @driver[:web][:selenium],
            @logger
          )
          @builder[:mysql][:account].update(account['id'], result)

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['admin'],
            @formatter[:controller][:amazon][:developer][:jp][:self]
            .register(
              account.symbolize_keys.merge(result).merge(
                {
                  ack: 'success',
                }
              )
            )
          )
          @driver[:web][:selenium].destroy_driver
        rescue TWEyes::Exception::Controller::Amazon::Developer => e

          @builder[:mysql][:account].update(
            account['id'],
            {
              amazon_jp_account: nil,
              amazon_jp_password: nil,
            }
          )
          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['admin'],
            @formatter[:controller][:amazon][:developer][:jp][:self]
            .register(
              account.symbolize_keys.merge(
                {
                  ack: 'failure',
                  capture: e.capture_url,
                  source: e.source_url,
                  message: e.message,
                }
              )
            )
          )
          @driver[:web][:selenium].destroy_driver
        ensure
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    protected

    private

    def initializeX

    end

    def collect_application(account, type) 

      application = []

      if account["yahoo_#{type}_account"].to_s.size > 0 and
         account["yahoo_#{type}_password"].to_s.size > 0 and
         account["yahooapis_#{type}_appid"].to_s.size.zero? and
         account["yahooapis_#{type}_secret"].to_s.size.zero? and
         account["yahoo_auctions_#{type}_cookies_is_set"] > 0

        @controller[:yahoo][:developer][:self].initializeX(type)
        @controller[:yahoo][:developer][:self].set_cookies_jar(
          @driver[:web][:mechanize].cookies_jar
        )

        begin

          create_application(account, type)
          update_application(account, type)
          application = get_application(account, type)
        rescue TWEyes::Exception::Controller::Yahoo::Developer => e

          @builder[:mysql][:account].update(
            account['id'],
            {
              "yahoo_auctions_#{type}_captcha": nil,
              "yahoo_auctions_#{type}_request_captcha": 0,
              "yahoo_auctions_#{type}_cookies_is_set": 0,
            }
          )

          @api[:chatwork].push_message(
            @configure[:api][:chatwork]
            .room_indices['customer'],
            @formatter[:exception].handle(e)
          )
        else

          @builder[:mysql][:account].update(
            account['id'],
            {
              "yahooapis_#{type}_appid": application[0],
              "yahooapis_#{type}_secret": application[1],
              "yahoo_auctions_#{type}_request_captcha": 0,
              "yahoo_auctions_#{type}_captcha": nil,
            }
          )
        end
      end
    end

    def get_application(account, type)

      application = []

      ###set_proxy_static
      @driver[:web][:selenium].create_driver(:phantomjs)
      application = @controller[:yahoo][:developer][:self].get_application(
        @driver[:web][:selenium],
        @logger
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['admin'],
        @formatter[:controller][:yahoo][:developer][:self]
        .get_application(
          account.symbolize_keys.merge(
            {
              ack: 'success',
              application_id: application[0],
              application_secret: application[1],
              type: get_type_ja(type),
            }
          )
        )
      )

      @driver[:web][:selenium].destroy_driver

      application
    rescue TWEyes::Exception::Controller::Yahoo::Developer => e

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['admin'],
        @formatter[:controller][:yahoo][:developer][:self]
        .update_application(
          account.symbolize_keys.merge(
            {
              ack: 'failure',
              account: account["yahoo_#{type}_account"],
              password: account["yahoo_#{type}_password"],
              capture_url: e.capture_url,
              message: e.message,
              type: get_type_ja(type),
            }
          )
        )
      )

      @driver[:web][:selenium].destroy_driver
      raise(e.class, sprintf("%s, %s", __method__, e.message))
    end

    def create_application(account, type)

      ###set_proxy_static
      @driver[:web][:selenium].create_driver(:phantomjs)
      @controller[:yahoo][:developer][:self].create_application(
        @driver[:web][:selenium],
        @logger
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['admin'],
        @formatter[:controller][:yahoo][:developer][:self]
        .create_application(
          account.symbolize_keys.merge(
            {
              ack: 'success',
              type: get_type_ja(type),
            }
          )
        )
      )

      @driver[:web][:selenium].destroy_driver
    rescue TWEyes::Exception::Controller::Yahoo::Developer => e

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['admin'],
        @formatter[:controller][:yahoo][:developer][:self]
        .create_application(
          account.symbolize_keys.merge(
            {
              ack: 'failure',
              account: account["yahoo_#{type}_account"],
              password: account["yahoo_#{type}_password"],
              capture_url: e.capture_url,
              message: e.message,
              type: get_type_ja(type),
            }
          )
        )
      )

      @driver[:web][:selenium].destroy_driver
      raise(e.class, sprintf("%s, %s", __method__, e.message))
    end

    def update_application(account, type)

      set_proxy_static
      @driver[:web][:selenium].create_driver(:phantomjs)
      @controller[:yahoo][:developer][:self].update_application(
        @driver[:web][:selenium],
        @logger
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['admin'],
        @formatter[:controller][:yahoo][:developer][:self]
        .update_application(
          account.symbolize_keys.merge(
            {
              ack: 'success',
              type: get_type_ja(type),
            }
          )
        )
      )

      @driver[:web][:selenium].destroy_driver
    rescue TWEyes::Exception::Controller::Yahoo::Developer => e

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['admin'],
        @formatter[:controller][:yahoo][:developer][:self]
        .update_application(
          account.symbolize_keys.merge(
            {
              ack: 'failure',
              account: account["yahoo_#{type}_account"],
              password: account["yahoo_#{type}_password"],
              capture_url: e.capture_url,
              message: e.message,
              type: get_type_ja(type),
            }
          )
        )
      )

      @driver[:web][:selenium].destroy_driver
      raise(e.class, sprintf("%s, %s", __method__, e.message))
    end

    def set_proxy

      @driver[:web][:mechanize].set_proxy_random(
        @driver[:web][:proxies][:mpp].fetch_proxies_full
      )
    end

    def set_user_agent_alias

      @driver[:web][:mechanize].set_user_agent_alias
    end

    def re_initialize

      @driver[:web][:mechanize].re_initialize
    end

    def initialize_mechanize

      re_initialize
      set_proxy
      set_user_agent_alias
      @controller[:yahoo][:auctions][:mechanize].initializeX(
        @driver[:web][:mechanize],
        @logger
      )
    end

    def get_type_ja(type)

      if type === :seller

        '販売'
      elsif type === :buyer

        '仕入'
      end
    end

    def set_proxy_static

      @driver[:web][:selenium].set_proxy(
        @configure[:system][:user]
        .account['proxy_ipv4addr_pair_static']
      )
    end

  end ### class Developer [END]
end
