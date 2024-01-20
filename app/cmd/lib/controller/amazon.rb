module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    class Amazon

      include TWEyes::Mixin::Languages::XML

      public

      def initialize(configure)
        super()

        @configure = configure
      end

      def initializeX
      end

      protected

      private

      class Developer < Amazon

        public

        def initialize(configure)

          super

          @self        = get_class_suffix
          @parent      = @self.split('_')[0].to_sym
          @child       = @self.split('_')[1].to_sym
          @grand_child = @self.split('_')[2].to_sym
          @account     = nil
          @password    = nil
        end

        protected

        def initializeX

          @account  = @configure[:system][:user]
                     .account[
                       sprintf("%s_%s_account",
                         @parent,
                         @grand_child
                       )
                     ]
          @password  = @configure[:system][:user]
                       .account[
                         sprintf("%s_%s_password",
                           @parent,
                           @grand_child
                         )
                       ]
        end

        def get_class_suffix

          self.class
              .to_s
              .split('::')[-3..-1]
              .map{|e|e.downcase}
              .join('_')
        end

        private

        class Jp < Developer

          def initializeX

            super
          end

          def register(driver, logger)

pp @configure[:api][:amazon][:mws][:developer][:jp][:self].account_id

            driver.connector[:waiter].until do

              driver.connector[:driver].get(
                @configure[:controller][:amazon][:developer][:jp][:self]
                .get_url
              )
=begin
              driver.connector[:driver]
                .find_element(
                  :xpath,
                  '/html/body/div[1]/div[4]/div/div[2]/a/span'
              ).click
=end
              driver.connector[:driver]
                .find_element(
                  :name,
                  'email'
              ).clear
              driver.connector[:driver]
                .find_element(
                  :name,
                  'email'
              ).send_keys @account
              driver.connector[:driver]
                .find_element(
                  :name,
                  'password'
              ).clear
              driver.connector[:driver]
                .find_element(
                  :name,
                  'password'
              ).send_keys @password
              driver.connector[:driver]
                .find_element(
                  :xpath,
                  '//*[@id="signInSubmit"]'
              ).click
pp driver.capture
              ###
              driver.connector[:driver]
                .find_element(
                  :xpath,
                  '//*[@id="registrationForm"]/div[2]/input'
              ).click
              driver.connector[:driver]
                .find_element(
                  :id,
                  'applicationName'
              ).clear
              driver.connector[:driver]
                .find_element(
                  :id,
                  'applicationName'
              ).send_keys @configure[:system][:products].name
              driver.connector[:driver]
                .find_element(
                  :id,
                  'appDevMWSAccountId'
              ).clear
              driver.connector[:driver]
                .find_element(
                  :id,
                  'appDevMWSAccountId'
              ).send_keys @configure[:api][:amazon][
                            :mws
                          ][:developer][:jp][:self].account_id
pp driver.capture
              driver.connector[:driver]
                .find_element(
                  :xpath,
                  '//*[@id="aliveButton"]/button/div/div[1]'
              ).click
              ###
              driver.connector[:driver]
                .find_element(
                  :name,
                  'agreeCheckBox'
              ).click
              driver.connector[:driver]
                .find_element(
                  :name,
                  'understandCheckBox'
              ).click
              driver.connector[:driver]
                .find_element(
                  :xpath,
                  '//*[@id="aliveButton"]/button/div/div[1]'
              ).click
pp driver.capture

              {
                "#{@parent}_#{@grand_child}_merchant_id":
                  driver.connector[:driver]
                    .find_element(
                      :xpath,
                    '/html/body/div[2]/div[4]/table[1]/tbody/tr[2]/td'
                  ).text,
                "#{@parent}_#{@grand_child}_marketplace_id":
                  driver.connector[:driver]
                    .find_element(
                      :xpath,
                      '/html/body/div[2]/div[4]/table[1]/tbody/tr[3]/td'
                  ).text.sub(' (Amazon.co.jp)', ''),
                "#{@parent}_#{@grand_child}_access_key":
                  @configure[:api][:amazon][:mws][:developer][:jp][:self]
                  .account_access_key,
=begin
                  driver.connector[:driver]
                    .find_element(
                      :xpath,
                      '/html/body/div[2]/div[4]/table[2]/tbody/tr[3]/td'
                  ).text,
=end
                "#{@parent}_#{@grand_child}_secret_key":
                  @configure[:api][:amazon][:mws][:developer][:jp][:self]
                  .account_secret_key,
=begin
                  driver.connector[:driver]
                    .find_element(
                      :xpath,
                      '/html/body/div[2]/div[4]/table[2]/tbody/tr[4]/td'
                  ).text,
=end
                "#{@parent}_#{@grand_child}_auth_token":
                  driver.connector[:driver]
                    .find_element(
                      :xpath,
                      '/html/body/div[2]/div[4]/table[2]/tbody/tr[2]/td'
                  ).text,
              }
            end
          rescue => e

            raise(TWEyes::Exception::Controller::Amazon::Developer.new(
              e.class,
              e.backtrace,
              driver.capture,
              driver.save_source,
              __method__
            ), e)
          end

        end ### class Jp < Developer [END]

      end ### class Developer < Amazon [END]

      class Jp < Amazon

        public

        def initialize(configure)
          super
        end

        def initializeX
        end

        protected

        class MechanizeX < Jp

          attr_reader :item_url, :require_reset_flag

          def initialize(configure)
            super

            @agent       = nil
            @logger      = nil
            @item_url    = nil
            @replaces    = @configure[:controller][:amazon][:jp][:mechanize]
                           .get_replaces_item_description
            @re_replaces = Regexp.new(@replaces.keys.join('|'))
            @retry_limit = @configure[:controller][:amazon][:jp][:mechanize]
                           .get_retry_limit
            @interval    = @configure[:controller][:amazon][:jp][:mechanize]
                           .get_interval
            @require_reset_flag  = nil
            @require_reset_count = nil
          end

          def initializeX(agent, logger)
            @agent  = agent
            @logger = logger
          end

          def get_item_description(asin)
            description = nil
            details     = nil
            retry_count = 1
        
            @item_url = sprintf(
              @configure[:controller][:amazon][:jp][:mechanize]
              .get_item_description_url,
              asin
            )
        
            begin
              page = @agent.get(@item_url)

              if page.at('//input[@id="captchacharacters"]')
                @require_reset_flag = true
                @logger[:user].warn('Found input[@id="captchacharacters"]')
                sleep(@interval)

                return
              else
                @require_reset_flag = false
                @logger[:user].info('Not Found input[@id="captchacharacters"]')
              end

              @logger[:user].info(sprintf("%s", @item_url))
              description = page.at('//div[@id="productDescription"]/p')
              details     = page.at('//div[@id="productDetailsDiv"]')

              sleep(@interval)
        
              if description
                @logger[:user].info(
                  sprintf("productDescription=[%s]", description)
                )

                return description.text.gsub(@re_replaces, @replaces).strip
              elsif details
                @logger[:user].info(sprintf("productDetailsDiv=[%s]", details))

                return details.text.gsub(@re_replaces, @replaces).strip
              else
                @logger[:user].warn(sprintf("%s", page.body.toutf8))
              end
            rescue Errno::ETIMEDOUT, Mechanize::ResponseCodeError => e
              if /HTTPNotFound/ !~ e.message and @retry_limit >= retry_count
                @logger[:user].warn(
                  sprintf("RETRY(%s): %s, %s",
                    retry_count,
                    e.class,
                    e.message
                  )
                )
                retry_count = retry_count.succ
                sleep(@interval)

                retry
              else
                @logger[:user].error(
                  sprintf("GIVEUP: %s, %s", e.class, e.message)
                )
              end
            end
        
            description
          rescue => e
            raise(
              TWEyes::Exception::Controller::Amazon::Jp::Mechanize.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

        end ### class Mechanize < Jp [END]

      end ### class Jp Amazon [END]

      class MWS < Amazon

        @@items = Hash.new{|h,k| h[k] = {}}

        public

        def initialize(configure)
          super

          @api       = nil
          @builder   = nil
          @formatter = nil
          @connector = nil
          @market    = nil
          @merchant  = nil
        end

        def initializeX(connector, marketplace)
          @connector   = connector
          @marketplace = marketplace
          @merchant    = @configure[:system][:user]
                         .account[
                           sprintf("amazon_%s_merchant_id", marketplace)
                         ]
        end

        def get_items
          @@items
        end

        def delete(sku)
          @@items.delete(sku)
        end

        protected

        def resolve_action(method)
          eval(sprintf(
            "@configure[:controller][:amazon][:mws][:feeds].get_%s_action",
            method
          ))
        end

        def resolve_xml_schema(method)
          eval(sprintf(
            "@configure[:controller][:amazon][:mws][:feeds].get_%s_xml_schema",
            method
          ))
        end

        private

        class Feeds < MWS

          public

          def initialize(configure)
            super
          end

          def initializeX(api, formatter, builder, connector, marketplace)
            @api         = api
            @formatter   = formatter
            @builder     = builder
            @connector   = connector
            @marketplace = marketplace
            @merchant    = @configure[:system][:user]
                           .account[
                             sprintf("amazon_%s_merchant_id", marketplace)
                           ]

            @marketplace_id = @configure[:system][:user]
                              .account[
                                sprintf(
                                  "amazon_%s_marketplace_id",
                                  marketplace
                                )
                              ]
          end

          def post_product_data(items, marketplace, method)

            xmls = []

            items[method].each_with_index do |item, index|

              item[:item_amazon_jp_page] = item[:item_amazon_jp_page]
                                           .gsub('&', '&amp;')
              item[:item_amazon_jp_page] = item[:item_amazon_jp_page]
                                           .gsub('<BR>', '')
                                           .gsub('<br>', '')

              item.store(:feed_message_id, index.succ)
              item.store(:asin, item[:item_amazon_jp_asin])

              if item[:item_amazon_jp_item_id]
                 .to_s
                 .size
                 .zero?

                item.store(:sku, decide_sku(item))
              else

                item.store(:sku, item[:item_amazon_jp_item_id])
              end

              if item[:item_amazon_jp_product_name]
                 .to_s
                 .size
                 .zero?

                item.store(
                  :item_amazon_jp_product_name,
                  item[:item_product_name]
                )
              end

              xmls.push(sprintf(resolve_xml_schema(__method__), item))
            end

            xml =  sprintf(
              @configure[:controller][:amazon][:mws][:self]
              .get_core_xml_schema,
              @merchant,
              'Product',
              xmls.join
            )

dump_xml(xml)

            response = @connector.submit_feed(
              xml,
              resolve_action(__method__)
            )

pp response

            processing_report(
              items,
              get_result(resolve_action(__method__)),
              __method__,
              method
            )
          rescue => e
pp e.class, e.message, e.backtrace

            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Feeds.new(
                e.class,
                e.backtrace
              ), e)
          end

          def post_inventory_availability_data(
                items,
                marketplace,
                method,
                quantity = 1
              )

            xmls = []

            items[method].each_with_index do |item, index|

              if item[:item_amazon_jp_item_id].to_s.size > 0

                item.store(:sku, item[:item_amazon_jp_item_id])
              end

              if item[:item_amazon_jp_product_name]
                 .to_s
                 .size
                 .zero?

                item.store(
                  :item_amazon_jp_product_name,
                  item[:item_product_name]
                )
              end

              item.store(:feed_message_id, index.succ)
              item.store(:quantity, quantity)
              xmls.push(sprintf(resolve_xml_schema(__method__), item))
            end

            xml =  sprintf(
              @configure[:controller][:amazon][:mws][:self]
              .get_core_xml_schema,
              @merchant,
              'Inventory',
              xmls.join
            )

dump_xml(xml)

            response = @connector.submit_feed(
              xml,
              resolve_action(__method__)
            )

pp response

            processing_report(
              items,
              get_result(resolve_action(__method__)),
              __method__,
              method
            )
          rescue => e
            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Feeds.new(
                e.class,
                e.backtrace
              ), e)
          end

          def post_product_pricing_data(items, marketplace, method)
            xmls = []

            items[method].each_with_index do |item, index|

              item.store(:feed_message_id, index.succ)
              item.store(:currency, 'JPY')
              xmls.push(sprintf(resolve_xml_schema(__method__), item))

            end

            xml =  sprintf(
              @configure[:controller][:amazon][:mws][:self]
              .get_core_xml_schema,
              @merchant,
              'Price',
              xmls.join
            )

dump_xml(xml)

            response = @connector.submit_feed(
              xml,
              resolve_action(__method__)
            )

pp response

            processing_report(
              items,
              get_result(resolve_action(__method__)),
              __method__,
              method
            )
          rescue => e
            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Feeds.new(
                e.class,
                e.backtrace
              ), e)
          end

          def post_product_image_data(items, marketplace, method)

            xmls = []

            index = 0
            items[method].each_with_index do |item, i|

              item.store(:feed_message_id, i.succ)

              if item[:feed_message_ids].nil?
                item.store(:feed_message_ids, [])
              end

              image_data = {}
              item.select do |k, v|

                matches = k.to_s.match(/^item_image_\d\d/)
                matches and v.to_s.size > 0

              end.each_with_index do |image, index2|

                if index2 <= 8

                  index = index.succ
                  image_data.store(:sku, item[:sku])
                  image_data.store(:feed_message_id, index)
                  item[:feed_message_ids].push(index)
                  if index2.zero?

                    image_data.store(:image_type, 'Main')
                  else

                    image_data.store(:image_type, 'PT'+index2.to_s)
                  end
                  image_data.store(:image_location,
                    sprintf("%s/%s",
                      @configure[:system][:net]
                      .get_http_uri+
                      @configure[:system][:directory]
                      .get_relative_images_path,
                      image[1]
                    )
                  )
                  xmls.push(sprintf(resolve_xml_schema(__method__), image_data))
                end

                if index2 <= 5
                  index = index.succ
                  image_data.store(:sku, item[:sku])
                  image_data.store(:feed_message_id, index)
                  item[:feed_message_ids].push(index)
                  if index2.zero?
                    image_data.store(:image_type, 'MainOfferImage')
                  else
                    image_data.store(:image_type, 'OfferImage'+index2.to_s)
                  end
                  image_data.store(:image_location,
                    sprintf("%s/%s",
                      @configure[:system][:net]
                      .get_http_uri+
                      @configure[:system][:directory]
                      .get_relative_images_path,
                      image[1]
                    )
                  )

                  xmls.push(
                    sprintf(resolve_xml_schema(__method__), image_data)
                  )
                end
              end

            end

            xml =  sprintf(
              @configure[:controller][:amazon][:mws][:self]
              .get_core_xml_schema,
              @merchant,
              'ProductImage',
              xmls.join
            )

dump_xml(xml)

            response = @connector.submit_feed(
              xml,
              resolve_action(__method__),
              {
                marketplace_id_list: [ @marketplace_id ],
              }
            )

pp response

            processing_report(
              items,
              get_result(resolve_action(__method__)),
              __method__,
              method
            )
          rescue => e

            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Feeds.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

          def get_result(feed_type)

            result = nil

            loop do

              result = @connector.get_feed_submission_list(
                         {
                           max_count: 1,
                           feed_type_list: feed_type
                         }
                       ).parse
pp result
              if result.nil? or
                 result['FeedSubmissionInfo']['FeedProcessingStatus'].nil?

                sleep(60)
                next
              end

              case result['FeedSubmissionInfo']['FeedProcessingStatus']
              when '_DONE_' then
                pp result
                break
              else
                pp result
                sleep(60)
              end
            end

            @connector.get_feed_submission_result(
              result['FeedSubmissionInfo']['FeedSubmissionId']
            ).parse
          end

  def determines_the_state(summary)

    if summary['MessagesProcessed'].to_i > 0 and
       summary['MessagesProcessed'].to_i ==
       summary['MessagesSuccessful'].to_i
      return :success
    elsif summary['MessagesWithError'].to_i > 0
      return :error
    elsif summary['MessagesWithWarning'].to_i > 0
      return :warning
    end
  end

  def scan_result(feed_message_id, result)

pp __method__

    report_results = []
    report_result  = result['ProcessingReport']['Result']

    if report_result.nil?

      return { status_code: :success, result: '' }
    else

      if report_result.kind_of?(Hash)

        report_results.push(report_result)
      else

        report_results = report_result
      end

      report_results.each do |r|

pp feed_message_id, r['MessageID'].to_i

        if feed_message_id.to_i == r['MessageID'].to_i

          return case r['ResultCode']
          when 'Warning'
            {
              status_code: :warning,
              result: sprintf("ResultDescription=%{ResultDescription},"+
                              "ResultMessageCode=%{ResultMessageCode}",
                               r.symbolize_keys
                      )
            }
          when 'Error'
            {
              status_code: :error,
              result: sprintf("ResultDescription=%{ResultDescription},"+
                              "ResultMessageCode=%{ResultMessageCode}",
                               r.symbolize_keys
                      )
            }
          end
        else

          return { status_code: :success, result: '' }
        end

      end

    end
  end

  def scan_image_result(feed_message_ids, result)

    report_results = []
    report_result  = result['ProcessingReport']['Result']

    if report_result.nil?

      return { status_code: :success, result: '' }
    else

      if report_result.kind_of?(Hash)

        report_results.push(report_result)
      else

        report_results = report_result
      end

      errors = Hash.new{ |h, k| h[k] = {} }
      report_results.each do |r|

        if feed_message_ids.include?(r['MessageID'].to_i)

          case r['ResultCode']
          when 'Warning'

            errors[:warning].store(
              r['ResultMessageCode'],
              r['ResultDescription']
            )
          when 'Error'

            errors[:error].store(
              r['ResultMessageCode'],
              r['ResultDescription']
            )
          end

        end

      end

      if errors[:error].size > 0

        return {
          status_code: :error,
          result: sprintf(
                    "ResultDescription=%s, ResultMessageCode=%s",
                    errors[:error].values.join("\n"),
                    errors[:error].keys.join("\n")
                  )
        }
      elsif errors[:warning]

        return {
          status_code: :warning,
          result: sprintf(
                    "ResultDescription=%s, ResultMessageCode=%s",
                    errors[:warning].values,
                    errors[:warning].keys
                  )
        }
       else

         return { status_code: :success, result: '' }
      end

    end
  end

  def processing_report(items, result, method, parent_method)

    items[parent_method]
    .each_with_index do |item, index|

      errors = {}

      case method
      when :post_product_image_data
        errors = scan_image_result(
          item[:feed_message_ids],
          result
        )
      else
        errors = scan_result(
          item[:feed_message_id],
          result
        )
      end

      item.store(:errors, errors)

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        eval(sprintf(
          "@formatter[:controller][:amazon][:mws][:feeds].%s(item)",
           method
        ))
      )

      item.store(:feed_message_id, 0)

      if item[:error_count].nil?

       item.store(:error_count, 0)
      end

      case errors[:status_code]
      when :success, :warning

        if parent_method == :add_item and
           method == :post_product_image_data

          if item[:error_count].zero?

            @builder[:mysql][:item][:self].update(
              item[:item_id],
              {
                amazon_jp_item_id: item[:sku],
                amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                    .get_state['exhibit']
              }
            )
          else

            @builder[:mysql][:item][:self].update(
              item[:item_id],
              {
                amazon_jp_item_id: nil,
                amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                    .get_state['waiting']
              }
            )
          end
        end

        if parent_method == :end_item and
           method == :post_inventory_availability_data

          @builder[:mysql][:item][:self].update(
            item[:item_id],
            {
              amazon_jp_item_id: item[:sku],
              amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                  .get_state['waiting']
            }
          )
        end

        if parent_method == :add_stock and
           method == :post_inventory_availability_data

          @builder[:mysql][:item][:self].update(
            item[:item_id],
            {
              amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                  .get_state['exhibit']
            }
          )
        end
      when :error

        item.store(:error_count, item[:error_count].succ)

        if parent_method == :add_item and
           method == :post_product_image_data

          @builder[:mysql][:item][:self].update(
            item[:item_id],
            {
              amazon_jp_item_id: nil,
              amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                  .get_state['waiting']
            }
          )
        end

        if parent_method == :end_item and
           method == :post_inventory_availability_data
          @builder[:mysql][:item][:self].update(
            item[:item_id],
            {
              amazon_jp_item_id: item[:sku],
              amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                  .get_state['exhibit']
            }
          )
        end

        if parent_method == :add_stock and
           method == :post_inventory_availability_data
          @builder[:mysql][:item][:self].update(
            item[:item_id],
            {
              amazon_jp_state_id: @configure[:builder][:mysql][:item]
                                  .get_state['waiting']
            }
          )
        end
      else
      end

      sleep(3)
    end
  end

          def decide_sku(item)

            sprintf("%s_%s_%s",
              item[:asin],
              @configure[:system][:date].get_date_suffix,
              SecureRandom.hex(8)
            ).upcase
          end

        end ### class Feeds < MWS [END}

        class Orders < MWS

          public

          def initialize(configure)
            super
          end

          def list_orders

            orders_hash = Hash.new{|h,k| h[k] = {}}

            response = @connector.list_orders(
              created_after: @configure[:system][:date].get_today - 14
            )
            orders = response.parse['Orders']

            if orders

              if orders['Order'].kind_of?(Array)

                orders['Order'].each do |elm|

                  order = list_order_items(elm['AmazonOrderId'])
                  if order.kind_of?(Array)

                    order.each do |o|

                      orders_hash.store(o['SellerSKU'], elm.merge!(o))
                    end
                  elsif order.kind_of?(Hash)

                    orders_hash.store(order['SellerSKU'], elm.merge!(order))
                  end
                end
                sleep(3)
              elsif orders['Order'].kind_of?(Hash)
                order = list_order_items(orders['Order']['AmazonOrderId'])
                orders_hash.store(
                  order['SellerSKU'],
                  orders['Order'].merge(order)
                )
              end
            end

            orders_hash
          rescue => e

            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Orders.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

          def list_order_items(order_id)
            @connector.list_order_items(order_id)
                      .parse['OrderItems']['OrderItem']
          end

        end ### class Orders < MWS [END}

        class Reports < MWS

          public

          def initialize(configure)
            super
          end

          def get_merchant_listings_data
            @connector.request_report(
              @configure[:api][:amazon][:mws][:reports]
              .get_merchant_listings_action
            )

            get_report_list_waiter(
              @configure[:api][:amazon][:mws][:reports]
              .get_merchant_listings_action
            )
          rescue => e
            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Reports.new(
                e.class,
                e.backtrace
              ), e)
          end
 
          protected
 
          private

          def get_report_list_waiter(report_type)
            result    = nil
            report_id = nil
            
            result = get_report_list(
              {
                max_count:        1,
                report_type_list: report_type
              }
            )

            report_id = result['ReportInfo']['ReportId']
            get_report(report_id).each do |row|

              @@items[row[2]] = {
                product_name: row[0],
                exhibit_id: row[1],
                sku: row[2],
                sprintf("amazon_%s_price",
                  @marketplace
                ).to_sym => row[3].to_i,
                quantity: correction_price(row[4]),
                start_time: row[5],
                type_id: row[6],
                description: row[7],
                asin: row[11],
                stock: correction_price(row[12]),
                fulfillment_channel: row[13],
              }
            end

          end

          def get_report_list(opts = {})
            @connector.get_report_list(opts).parse
          end
        
          def get_report(report_id)
            @connector.get_report(report_id).parse
          end
 
          def correction_price(price)
            if price.nil?
              0
            elsif price.kind_of?(String)
              price.to_i
            end
          end

        end ### class Reports [END]

        class FulfillmentInventory < MWS

          public

          def initialize(configure)
            super
          end

          def insert_total_supply_quantity
            response = []
        
            @@items.to_a.each_slice(50) do |item|
              skus = []
        
              item.each do |row|
                skus.push(row[1][:sku])
              end
        
              response = list_inventory_supply(skus)

              if response.kind_of?(Array)
                response.each do |res|
                  store_condition(res) ### 必ず先におくこと。
                  store_total_supply_quantity(res) ### 在庫がないのを削除するため。
                end
              elsif response.kind_of?(Hash)
                store_condition(response) ### 同上
                store_total_supply_quantity(response) ### 同上
              end
              sleep(3) ### 回復レート待ち
            end
          rescue => e
            raise(
              TWEyes::Exception::Controller::Amazon::MWS::FulfillmentInventory.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

          def list_inventory_supply(skus)
            @connector.list_inventory_supply(
              {
                seller_skus: skus
              }
            ).parse['InventorySupplyList']['member']
          end
        
          def store_condition(response)
            if response['Error']
              pp response['Error']
            else
              @@items[response['SellerSKU']]
              .store(:amazon_jp_condition, response['Condition'])
            end
          end

          def store_total_supply_quantity(response)
            quantity = nil
        
            sku = response['SellerSKU']
            fulfillment_channel = @@items[sku][:fulfillment_channel]
        
            if response['Error']
              pp response['Error']
            else
              if fulfillment_channel == 'AMAZON_JP'
                if get_in_stock_supply_quantity(response).zero?
                  @@items.delete(sku)
                else
                  @@items[sku].store(
                    :quantity,
                    get_total_supply_quantity(response)
                  )
                  @@items[sku].store(
                    :stock,
                    get_in_stock_supply_quantity(response)
                  )
                end
              elsif fulfillment_channel == 'DEFAULT'
                if @@items[sku][:stock].zero?
                  @@items.delete(sku)
                end
              end
            end
          end

          def get_total_supply_quantity(result)
            result['TotalSupplyQuantity'].to_i
          end
        
          def get_in_stock_supply_quantity(result)
            result['InStockSupplyQuantity'].to_i
          end

        end ### class FulfillmentInventory [END]

        class Products < MWS

          include TWEyes::Mixin::Amazon::MWS::Products

          public

          def initialize(configure)
            super
          end

          def get_title(id_type, ids)

            eval(sprintf(
              "parse_%s(get_matching_product_for_id(id_type, ids))",
              __method__
            ))
          rescue => e

            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Products.new(
                e.class,
                e.backtrace
              ), e)
          end

          def get_sales_rankings(id_type, ids)

            eval(sprintf(
              "parse_%s(get_matching_product_for_id(id_type, ids))",
              __method__
            ))
          rescue => e

            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Products.new(
                e.class,
                e.backtrace
              ), e)
          end

          def get_lowest_offer_listing_price(asins)

            eval(sprintf(
              "parse_%s(get_lowest_offer_listings_for_asin(asins))",
              __method__
            ))
          rescue => e

            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Products.new(
                e.class,
                e.backtrace
              ), e)
          end

          def get_asin_by_name(query)

            eval(sprintf(
              "parse_%s(list_matching_products(query))",
              __method__
            ))
          rescue => e

            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Products.new(
                e.class,
                e.backtrace
              ), e)
          end

          def list_matching_products(query)

            @connector.list_matching_products(query).parse
          end

          def get_matching_product_for_id(id_type, ids)

            @connector.get_matching_product(id_type, *ids).parse
          end

          def get_lowest_offer_listings_for_asin(asins)

            @connector.get_lowest_offer_listings_for_asin(
              *asins
            ).parse
          end

          def insert_matching_product
            response = []
        
            @@items.to_a.each_slice(10) do |item|
              for_insert = Hash.new{|h,k| h[k] = {}}
              asins = []
        
              item.each do |row|
                for_insert[row[1][:asin]].store(:sku, row[1][:sku])
                asins.push(row[1][:asin])
              end
        
              response = get_matching_product(asins.uniq)

              if response.kind_of?(Array)
                response.each do |res|

                  if res['Error']
                    delete(for_insert[res['ASIN']][:sku])
                    for_insert.delete(res['ASIN'])
                  else
                    for_insert[res['ASIN']].merge!(
                      get_hash(
                        res['Product']['AttributeSets']['ItemAttributes']
                      )
                    )
                  end
                end
              elsif response.kind_of?(Hash)
                if response['Error']
                  delete(for_insert[response['ASIN']][:sku])
                  for_insert.delete(response['ASIN'])
                else
                  for_insert[response['ASIN']].merge!(
                    get_hash(
                      response['Product']['AttributeSets']['ItemAttributes']
                    )
                  )
                end
              end
        
              for_insert.each do |k, v|
                @@items[v[:sku]].store(:feature, v[:feature])
                @@items[v[:sku]].store(
                  :small_image_01,
                  get_image(v[:small_image_url])
                )
                @@items[v[:sku]].store(
                  :large_image_01,
                  get_image(v[:large_image_url])
                )
              end
              sleep(3) ### 回復レート待ち
            end
          rescue => e
            raise(
              TWEyes::Exception::Controller::Amazon::MWS::Products.new(
                e.class,
                e.backtrace
              ), e)
          end

          protected

          private

          def get_hash(response)
            {
              large_image_url: get_large_image_url(response),
              small_image_url: get_small_image_url(response),
              feature:         get_feature(response)
            }
          end
        
          def get_matching_product(asins)
            @connector.get_matching_product(*asins).parse
          end
        
          def get_large_image_url(result)
            result['SmallImage']['URL'].sub('._SL', '._LL')
          end
        
          def get_small_image_url(result)
            result['SmallImage']['URL']
          end
        
          def get_feature(result)
            feature = result['Feature']

            if feature.kind_of?(Array)
              return feature.join("\n")
            elsif feature.kind_of?(String)
              return feature
            end
          end

          def get_image(url)
            if url
              images_path = sprintf("%s/%s",
                @configure[:system][:directory].get_images_path,
                File.basename(url)
              )

              open(images_path, 'wb') do |f|
                open(url) do |h|
                  f.write(h.read)
                end
              end

              return  @configure[:system][:directory]
                      .get_relative_images_path+'/'+
                      File.basename(url)
            else
              return ''
            end
          end

        end ### class Products [END]

      end ### class MWS [END]

    end ### class Amazon [END]

  end ### module Controller [END]

end ### module TWEyes [END]
