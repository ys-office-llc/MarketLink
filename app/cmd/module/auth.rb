require_relative '../lib/application'

module TWEyes

  class Auth < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def operate(type, account)

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s(%s) to start", self.class, __method__, type)
      )

      @configure[:system][:user].account = account
      @logger[:user].open
      @api[:chatwork].initializeX

      begin
        case type
        when :seller

          seller
        when :buyer

          buyer
        end
      rescue TWEyes::Exception::Controller::Yahoo::Auth => e

        @driver[:web][:selenium].destroy_driver

        @api[:chatwork].push_message(
          @configure[:api][:chatwork]
          .room_indices['system'],
          @formatter[:exception].handle(e)
        )
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

    def seller

      @controller[:yahoo][:auth][:seller].initializeX(:seller)
      @controller[:yahoo][:auth][:seller].set_cookies_jar(
        @driver[:web][:mechanize].cookies_jar
      )
      set_proxy_static

      @controller[:yahoo][:auth][:seller].manage_token(
        @builder,
        @driver[:web][:selenium],
        @logger
      )
    end

    def buyer

      @controller[:yahoo][:auth][:buyer].initializeX(:buyer)
      @controller[:yahoo][:auth][:buyer].set_cookies_jar(
        @driver[:web][:mechanize].cookies_jar
      )
      set_proxy_static

      @controller[:yahoo][:auth][:buyer].manage_token(
        @builder,
        @driver[:web][:selenium],
        @logger
      )
    end

    def set_proxy_static

      @driver[:web][:selenium].set_proxy(
        @configure[:system][:user]
        .account['proxy_ipv4addr_pair_static']
      )
    end

  end ### class Auth [END]
end
