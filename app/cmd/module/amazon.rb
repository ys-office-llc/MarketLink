require_relative '../lib/application'

module TWEyes
  class Amazon < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def collect

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account].get_records.each do |account|
        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX
        initializeX('jp')

        begin
          get_reports_except('jp')
          insert_quantity('jp')
          insert_products('jp').each do |k,v|
            item = {}

            begin
              query_hash = {
                amazon_jp_item_id: v[:sku]
              }
              unless @builder[:mysql][:item].registered?(query_hash)
                @builder[:mysql][:item].insert(insert_hash(v))
                @logger[:user].info(insert_hash(v))
              end
            rescue TWEyes::Exception::Database::MySQL => e
              @api[:chatwork].push_message(0,
                @formatter[:exception].handle(e))
            end
          end
        rescue TWEyes::Exception::Controller::Amazon => e
          @api[:chatwork].push_message(0,
            @formatter[:exception].handle(e))
        end

      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    def update

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )
      @builder[:mysql][:account].get_records.each do |account|
        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX
        initializeX('jp')

        begin
          get_reports('jp')
          insert_quantity('jp').each do |k,v|
            record = {}

            begin
              query_hash = {
                amazon_jp_item_id: v[:sku]
              }
              if @builder[:mysql][:item].registered?(query_hash)
                record = @builder[:mysql][:item].get_record_by_sku(v[:sku])
                sleep(@configure[:builder][:mysql][:item].get_interval)
                if v[:stock] != record['item_stock']
                  @builder[:mysql][:item].update(record['item_id'], update_hash(v))
                  @logger[:user].info(update_hash(v))
                end
                sleep(@configure[:builder][:mysql][:item].get_interval)
              end
            rescue TWEyes::Exception::Database::MySQL => e
              @api[:chatwork].push_message(0,
                @formatter[:exception].handle(e))
            end

          end
        rescue TWEyes::Exception::Controller::Amazon => e
          @api[:chatwork].push_message(0,
            @formatter[:exception].handle(e))
        end

      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    def operate

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
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:contract][:merchandise_management][:self].permit? and
               @controller[:flow][:amazon][:jp][:self].permit?

          next
        end

        initializeX('jp')

        ### Array の Hash
        items = Hash.new{ |h, k| h[k] = [] }
        @builder[:mysql][:item][:amazon][:jp]
        .get_records
        .each do |item|

          begin
            case item['item_amazon_jp_state_id']
            when @configure[:builder][:mysql][:item]
                 .get_state['waiting']

              set_asin(item)
              set_title(item)

              if @configure[:system][:user]
                 .account['vacation_end_date'].to_s.size > 0 and
                 @configure[:system][:date]
                 .get_today >  @configure[:system][:user]
                               .account['vacation_end_date'] and
                 item['item_amazon_jp_item_id'].to_s.size > 0

                items[:add_stock].push(item.symbolize_keys)
              end
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_add_item']

              items[:add_item].push(item.symbolize_keys)
            when @configure[:builder][:mysql][:item]
                 .get_state['exhibit']

              if @configure[:system][:user]
                 .account['vacation_begin_date'].to_s.size > 0 and
                 @configure[:system][:user]
                 .account['vacation_end_date'].to_s.size > 0 and
                 @configure[:system][:date].get_today.between?(
                   @configure[:system][:user].account['vacation_begin_date'],
                   @configure[:system][:user].account['vacation_end_date']
                 ) and
                 item['item_amazon_jp_item_id'].to_s.size > 0

                items[:end_item].push(item.symbolize_keys)
              end
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_relist_item']

              items[:add_item].push(item.symbolize_keys)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_revise_item']

              items[:add_item].push(item.symbolize_keys)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_end_item']

              items[:end_item].push(item.symbolize_keys)
            when @configure[:builder][:mysql][:item]
                 .get_state['payment']

              get_shipping_information('jp', item)
            end
          end

        end ### do |item| ... end

        begin

          add_item(items, 'jp') if items[:add_item].size > 0
          end_item(items, 'jp') if items[:end_item].size > 0
          add_stock(items, 'jp') if items[:add_stock].size > 0
        rescue TWEyes::Exception::Controller::Amazon::MWS::Products,
               TWEyes::Exception::Controller::Amazon::MWS::Feeds => e

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['item'],
            @formatter[:exception].handle(e))
        end

      end ### do |account| ... end
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

    def add_item(items, marketplace)  

      @controller[:amazon][:mws][:feeds].post_product_data(
        items,
        marketplace,
        __method__
      )

      @controller[:amazon][:mws][:feeds].post_inventory_availability_data(
        items,
        marketplace,
        __method__,
        1
      )
      @controller[:amazon][:mws][:feeds].post_product_pricing_data(
        items,
        marketplace,
        __method__
      )
      @controller[:amazon][:mws][:feeds].post_product_image_data(
        items,
        marketplace,
        __method__
      )
    rescue TWEyes::Exception::Controller::Amazon::MWS::Feeds => e

      if matches = e.message.match(/\((401 Unauthorized)\)/)

        items[__method__].each do |item|

          @builder[:mysql][:item][:self].update(
            item[:item_id],
            {
              amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                  .get_state['waiting']
            }
          )

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['item'],
            @formatter[:orchestrator][:amazon][:jp].conduct2(
            item,
            @builder[:mysql][:item][:self]
            .get_state_names[
              @configure[:builder][:mysql][:item]
              .get_state['waiting']
            ]
          )
        )
        end
      end
    end

    def end_item(items, marketplace)

      @controller[:amazon][:mws][:feeds].post_inventory_availability_data(
        items,
        marketplace,
        __method__,
        0
      )
    end

    def add_stock(items, marketplace)

      @controller[:amazon][:mws][:feeds].post_inventory_availability_data(
        items,
        marketplace,
        __method__,
        1
      )
    end

    def get_skus

      skus = []

      @builder[:mysql][:item].get_records.each do |record|
        skus.push(record[sprintf("item_amazon_%s_item_id", 'jp')])
      end

      skus
    end

    def set_proxy

      @driver[:web][:mechanize].set_proxy_random(
        @driver[:web][:proxies][:mpp].fetch_proxies_full
      )
    end

    def set_user_agent_alias

      @driver[:web][:mechanize].set_user_agent_alias
    end

    def initialize_mechanize

      set_proxy
      set_user_agent_alias
      @controller[:amazon][:jp][:mechanize].initializeX(
        @driver[:web][:mechanize].agent,
        @logger
      )
    end

    def get_description(asin)
      description = ''

      initialize_mechanize
      description = @controller[:amazon][:jp][:mechanize]
                    .get_item_description(asin)
      while @controller[:amazon][:jp][:mechanize].require_reset_flag
        initialize_mechanize
        description = @controller[:amazon][:jp][:mechanize]
                      .get_item_description(asin)
      end

      return description
    end

    def determine_the_condition(condition)
      case condition.to_s
      when 'NewItem' then 1
      else 2 ### 新品以外
      end
    end

    def insert_hash(values)
      {
        product_name: values[:product_name],
        amazon_jp_price: values[:amazon_jp_price],
        quantity: values[:quantity],
        stock: values[:stock],
        feature: values[:feature].to_s.normalize_single_quotation,
        small_image_01: values[:small_image_01],
        large_image_01: values[:large_image_01],
        description: get_description(values[:asin])
                     .to_s
                     .normalize_single_quotation,
        amazon_jp_url: @controller[:amazon][:jp][:mechanize].item_url,
        amazon_jp_item_id: values[:sku],
        amazon_jp_asin: values[:asin],
        yahoo_shopping_item_id: values[:exhibit_id].downcase,
        amazon_jp_state_id: @configure[:builder][:mysql][:item]
                            .get_state['exhibit'],
        yahoo_shopping_state_id: @configure[:builder][:mysql][:item]
                                 .get_state['reserve_add_item'],
        yahoo_auctions_state_id: @configure[:builder][:mysql][:item]
                                 .get_state['reserve_add_item'],
        ### 仮置き
        template_yahoo_auctions_id: 1,
        yahoo_condition_id: determine_the_condition(
                              values[:amazon_jp_condition]
                            )
      }
    end

    def update_hash(values)
      {
        amazon_jp_item_id: values[:sku],
        product_name: values[:product_name],
        quantity: values[:quantity],
        stock: values[:stock],
      }
    end

    def initializeX(marketplace)

      @api[:amazon][:mws][:feeds].initializeX(marketplace)
      @api[:amazon][:mws][:feeds].connect
      @controller[:amazon][:mws][:feeds].initializeX(
        @api,
        @formatter,
        @builder,
        @api[:amazon][:mws][:feeds].connector,
        marketplace
      )

      @api[:amazon][:mws][:reports].initializeX(marketplace)
      @api[:amazon][:mws][:reports].connect
      @controller[:amazon][:mws][:reports].initializeX(
        @api[:amazon][:mws][:reports].connector,
        marketplace
      )

      @api[:amazon][:mws][:fulfillment_inventory].initializeX(marketplace)
      @api[:amazon][:mws][:fulfillment_inventory].connect
      @controller[:amazon][:mws][:fulfillment_inventory].initializeX(
        @api[:amazon][:mws][:fulfillment_inventory].connector,
        marketplace
      )

      @api[:amazon][:mws][:products].initializeX(marketplace)
      @api[:amazon][:mws][:products].connect
      @controller[:amazon][:mws][:products].initializeX(
        @api[:amazon][:mws][:products].connector,
        marketplace
      )
    end

    def get_reports(marketplace)
      @controller[:amazon][:mws][:reports].get_merchant_listings_data
      @controller[:amazon][:mws][:reports].get_items
    end

    def get_reports_except(marketplace)
      @controller[:amazon][:mws][:reports].get_merchant_listings_data
      @controller[:amazon][:mws][:reports].get_items.each do |k,v|
        if get_skus.include?(v[:sku])
          @controller[:amazon][:mws][:reports].delete(v[:sku])
        end
      end

      @controller[:amazon][:mws][:reports].get_items
    end

    def insert_quantity(marketplace)
      @controller[:amazon][:mws][:fulfillment_inventory]
      .insert_total_supply_quantity
      @controller[:amazon][:mws][:reports].get_items
    end

    def insert_products(marketplace)
      @controller[:amazon][:mws][:products].insert_matching_product
      @controller[:amazon][:mws][:products].get_items
    end

    def set_title(item)

      if item['item_amazon_jp_asin'].to_s.size.zero?

        @builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            amazon_jp_asin: @controller[:amazon][:mws][:products]
            .get_asin_by_name(
              item['item_product_name']
            )
          }
        )
      end
    rescue TWEyes::Exception::Controller::Amazon::MWS::Products => e

      @logger[:system].err(
        sprintf(
          "%s::%s, %s, %s, %s",
          self.class,
          __method__,
          e.class,
          e.message,
          e.backtrace
        )
      )
    end

    def get_shipping_information(marketplace, item)

      result               = {}
      shipping_information = {}

      if item['item_wrote_shipping_information'].zero?

        @api[:amazon][:mws][:orders].initializeX(marketplace)
        @api[:amazon][:mws][:orders].connect

        @controller[:amazon][:mws][:orders].initializeX(
          @api[:amazon][:mws][:orders].connector,
          marketplace
        )
        result = @controller[:amazon][:mws][:orders]
                 .list_orders[
                   item["item_amazon_#{marketplace}_item_id"]
                 ]

        if result.size > 0 and
           result['ShippingAddress']

          shipping_information = {
            to: @api[:chatwork].to_reshape,
            item_stock_keeping_unit: item['item_stock_keeping_unit'],
            title: result['Title'],
            name: result['ShippingAddress']['Name'],
            postal_code: result['ShippingAddress']['PostalCode'],
            street_address: sprintf(
                              "%s %s %s %s %s",
                              result['ShippingAddress']['CountryCode'],
                              result['ShippingAddress']['StateOrRegion'],
                              result['ShippingAddress']['AddressLine1'],
                              result['ShippingAddress']['AddressLine2'],
                              result['ShippingAddress']['AddressLine3']
                            ),
            phone_number: result['ShippingAddress']['Phone'],
            ml_link: @configure[:system][:net]
                     .get_https_uri+'/item/get/'+item['item_id'].to_s,
          }

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['item'],
            @formatter[:controller][:amazon][:mws][:orders]
            .get_shipping_information(
              shipping_information
            )
          )

          @builder[:mysql][:item][:self].update(
            item['item_id'],
            {
              wrote_shipping_information: 1,
            }
          )
          if @configure[:system][:user]
             .account['chatwork_work_place_room1_id']
             .to_s
             .size > 0 and
             @configure[:system][:user]
             .account['chatwork_work_place_members']
             .to_s
             .size > 0

            @api[:chatwork].assign_tasks(
              @configure[:system][:user]
              .account['chatwork_work_place_room1_id'],
              @formatter[:controller][:amazon][:mws][:orders]
              .get_shipping_information(
                shipping_information
              ),
              (@configure[:system][:date].get_today + 1).to_time.to_i,
              @configure[:system][:user].account['chatwork_work_place_members']
            )
          end
        end
      end
    end
  
    def set_asin(item)

      if item['item_amazon_jp_asin'].to_s.size > 0

        @builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            amazon_jp_product_name:
              @builder[:mysql][:common].escape(
                @controller[:amazon][:mws][:products]
                .get_title(
                  'ASIN',
                  [
                    item['item_amazon_jp_asin']
                  ]
                )
              ),
          }
        )
      end
    rescue TWEyes::Exception::Controller::Amazon::MWS::Products => e

      @logger[:system].err(
        sprintf(
          "%s::%s, %s, %s, %s",
          self.class,
          __method__,
          e.class,
          e.message,
          e.backtrace
        )
      )
    end

  end ### class Amazon [END]
end
