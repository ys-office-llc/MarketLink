require_relative '../lib/application'
require_relative 'auth'

module TWEyes

  class Conductor < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
      @auth = Auth.new
    end

    def conduct

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

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:merchandise_management][:self].permit? and
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][:seller][:self]
               .permit? and
               @controller[:flow][:ebay][:us][:self]
               .permit?

          next
        end

        @auth.operate(:seller, account)
        initializeX

        begin

          orchestration
        rescue TWEyes::Exception::Orchestrator => e

          if e.message.match(/expired token/)

            @auth.operate(:seller, account)
            initializeX
            ###retry
          end

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['item'],
            @formatter[:exception].handle(e)
          )
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

    def welcome(market)

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s(%s)", self.class, __method__, market)
      )

      @orchestrator[:member].join(market)
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

    def orchestration

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @orchestrator[:member].collect(create_caller)

      @builder[:mysql][:item][:self]
      .get_records
      .each do |item|

        begin

          if @controller[:flow][:yahoo][:auctions][:seller][:self]
             .permit?

            @orchestrator[:yahoo][:auctions].conduct(item)
          end

          if @controller[:flow][:ebay][:us][:self]
             .permit?

            @orchestrator[:ebay][:us].conduct(item)
          end

          if @controller[:flow][:amazon][:jp][:self]
             .permit?

            @orchestrator[:amazon][:jp].conduct(item)
          end
        rescue TWEyes::Exception::Database::MySQL,
               TWEyes::Exception::Orchestrator => e

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['item'],
            @formatter[:exception].handle(e)
          )
        end
      end
    end

    def create_caller

      Proc.new do |market|
        case market
        when :yahoo_selling

          my_selling_list
        when :yahoo_sold

          my_close_list('sold')
          ###my_close_list_has_winner
        when :yahoo_not_sold

          my_close_list('not_sold')
        when :ebay_active

          get_my_ebay_selling('active_list')
        when :ebay_sold

          get_my_ebay_selling('sold_list')
        when :ebay_unsold

          get_my_ebay_selling('unsold_list')
        when :amazon_jp

          if @controller[:flow][:amazon][:jp][:self]
             .permit?

            list_orders('jp')
          end
        end
      end
    end

    def initializeX

      @orchestrator[:member].initializeX(
        @api,
        @builder,
        @controller,
        @formatter,
        @logger
      )

      @controller[:yahoo][:auctions][:self].initializeX(:seller)
      @controller[:yahoo][:auctions][:self].set_cookies_jar(
        @driver[:web][:mechanize].cookies_jar
      )

      @api[:yahoo][:auctions].initializeX(
        @builder[:mysql][:auth][:yahoo][:seller]
      )
      @controller[:yahoo][:api][:auctions].initializeX(
        @api[:yahoo][:auctions], :seller
      )

      @api[:yahoo][:shopping][:self].initializeX(
        @builder[:mysql][:auth][:yahoo][:seller]
      )
      @controller[:yahoo][:api][:shopping][:self].initializeX(
        @api[:yahoo][:shopping][:self], :seller
      )

      @api[:yahoo][:shopping][:circus].initializeX(
        @builder[:mysql][:auth][:yahoo][:seller]
      )
      @controller[:yahoo][:api][:shopping][:circus].initializeX(
        @api[:yahoo][:shopping][:circus], :seller
      )

      @api[:ebay][:trading][:production].initializeX
      @controller[:ebay][:trading][:production].initializeX(
        @api[:ebay][:trading][:production]
      )
    end

    def my_close_list(list)

      @controller[:yahoo][:api][:auctions].my_close_list(list)
    end

    def my_close_list_has_winner

      @driver[:web][:selenium].create_driver(:chrome)
      @controller[:yahoo][:auctions][:self].my_close_list_has_winner(
        @driver[:web][:selenium],
        @logger
      )
      @driver[:web][:selenium].destroy_driver
    end

    def my_selling_list
      @controller[:yahoo][:api][:auctions].my_selling_list

=begin
      @driver[:web][:selenium].create_driver(:chrome)
      @controller[:yahoo][:auctions][:self].my_selling_list(
        @driver[:web][:selenium],
        @logger
      )
      @driver[:web][:selenium].destroy_driver
=end
    end

    def list_orders(marketplace)
      @api[:amazon][:mws][:orders].initializeX(marketplace)
      @api[:amazon][:mws][:orders].connect

      @controller[:amazon][:mws][:orders].initializeX(
        @api[:amazon][:mws][:orders].connector,
        marketplace
      )
      @controller[:amazon][:mws][:orders].list_orders
    rescue TWEyes::Exception::Controller::Amazon::MWS::Orders => e

      if e.message.match(/401 Unauthorized/)

        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['item'],
          sprintf("[info][title]Amazon日本エラー通知[/title]"+
                  "・エラーメッセージ\t:\t%s[/info]", e.message)
        )
      else

        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['item'],
          @formatter[:exception].handle(e)
        )
      end
    end

    def get_stock
      items = []

      @builder[:mysql][:item].get_records.each do |item|
        items.push(item['item_yahoo_shopping_item_id'])
      end

      @controller[:yahoo][:api][:shopping][:circus].get_stock(items)
    end

    def get_my_ebay_selling(list)
      @controller[:ebay][:trading][:production].get_my_ebay_selling(list)
    end

  end ### Conductor < Application [END]

end ### module TWEyes
