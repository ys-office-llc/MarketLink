require_relative '../lib/application'
require_relative 'auth'

module TWEyes

  class ForTesting < Application

    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
      @auth = Auth.new
    end

    def config
    end

    def operate

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:chatwork][:self].permit?

          next
        end

        @auth.operate(:buyer, account)
        @api[:yahoo][:auctions].initializeX(
          @builder[:mysql][:auth][:yahoo][:buyer]
        )
        @controller[:yahoo][:api][:auctions].initializeX(
          @api[:yahoo][:auctions], :buyer
        )
      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(1, @formatter[:exception].handle(e))
    end

    protected

    private

    def initializeX
    end

  end ### class ForTesting [END]
end
