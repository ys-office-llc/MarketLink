module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    class Ebay

      include TWEyes::Mixin::Languages::XML

      public

      def initialize(configure)
        super()

        @configure = configure
      end

      def initializeX
      end

      protected

      def resolve_call_name(method)

        eval(sprintf(
          "@configure[:controller][:ebay][:trading][:self].get_%s_call_name",
          method
        ))
      end

      def resolve_operation_name(method)

        eval(sprintf(
          "@configure[:controller][:ebay][:%s][:self].get_%s_operation_name",
          resolve_class,
          method
        ))
      end

      def resolve_xml_schema(method)

        eval(sprintf(
          "@configure[:controller][:ebay][:%s][:self].get_%s_xml_schema",
          resolve_class,
          method
        ))
      end

      def merge(item)

        {
          item_ebay_us_price_xml:
            get_price_xml(
              item['item_condition_ebay_us_listing_type'],
              item['item_ebay_us_start_price'],
              item['item_ebay_us_end_price']
            )
        }
      end

      private

      def resolve_class

        self.class
            .to_s
            .split('::')[-2]
            .to_snake
      end

      class MechanizeX < Ebay

        include TWEyes::Mixin::Protocols::HTTP::MechanizeX

        def initialize(configure)

          super

          @driver = nil
          @logger = nil
          @agent  = nil

          @exchange_rate = nil
        end

        def initializeX(driver, logger)

          @driver = driver
          @logger = logger
          @agent  = @driver.agent

          @exchange_rate = get_exchange_rate
        end

        def get_sold(param)

          page            = nil
          page_highest    = nil
          img_src_highest = nil
          bidsolds        = []

          param.merge!(
            @configure[:mvc][:system][:research][:ebay][:self]
            .get_query_sold
          )
 
          page = get(
            @configure[:controller][:ebay][:mechanize][:self]
            .get_url_sold,
            param
          )

          bidsolds = collect_bidsold(page)

          img_src_highest = get(
            @configure[:controller][:ebay][:mechanize][:self]
            .get_url_sold,
            param.merge(
              {
                '_sop': 16,
              }
            )
          )

          if param[:_udlo].to_i > 0

            param.update(_udlo: param[:_udlo] * @exchange_rate)
          end

          if param[:_udhi].to_i > 0

            param.update(_udhi: param[:_udhi] * @exchange_rate)
          end

          page_end = get(
            @configure[:controller][:ebay][:mechanize][:self]
            .get_url_sold,
            param
          )

          page_highest = get(
            @configure[:controller][:ebay][:mechanize][:self]
            .get_url_sold,
            param.merge(
              {
                '_sop': 16,
              }
            )
          )

          page_lowest = get(
            @configure[:controller][:ebay][:mechanize][:self]
            .get_url_sold,
            param.merge(
              {
                '_sop': 15,
              }
            )
          )

          if page and
             page.at('//span[@class="rcnt"]') and
             page.at('//span[@class="rcnt"]').text.to_i > 0

            {
              ebay_us_numof_sold_m3: page.at('//span[@class="rcnt"]')
                                         .text
                                         .to_i,
              ebay_us_uri_sold_end: page_end.uri.to_s,
              ebay_us_uri_sold_highest: page_highest.uri.to_s,
              ebay_us_img_uri_sold_highest: img_src_highest.at(
                                              '//a[@class="img imgWr2"]/img'
                                            )[:src],
              ebay_us_uri_sold_lowest: page_lowest.uri.to_s,
              ebay_us_min_price: bidsolds.min,
              ebay_us_avg_price: bidsolds.avg,
              ebay_us_max_price: bidsolds.max,
            }
          else
pp @driver.save_source(page)

sleep(60)

            {
              ebay_us_numof_sold_m3: 0,
              ebay_us_uri_sold_end: page_end.uri.to_s,
              ebay_us_uri_sold_highest: page_highest.uri.to_s,
              ebay_us_uri_sold_lowest: page_lowest.uri.to_s,
              ebay_us_img_uri_sold_highest: img_src_highest,
              ebay_us_min_price: 0,
              ebay_us_avg_price: 0,
              ebay_us_max_price: 0,
            }
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Ebay::Mechanize.new(
            e.class,
            e.backtrace,
            __method__
          ), e)
        end

        def get_active(param)

          page = nil

          param.merge!(
            @configure[:mvc][:system][:research][:ebay][:self]
            .get_query_active
          )

          page = get(
            @configure[:controller][:ebay][:mechanize][:self]
            .get_url_active,
            param
          )

          if param[:_udlo].to_i > 0

            param.update(_udlo: param[:_udlo] * @exchange_rate)
          end

          if param[:_udhi].to_i > 0

            param.update(_udhi: param[:_udhi] * @exchange_rate)
          end

          page_jp = get(
            @configure[:controller][:ebay][:mechanize][:self]
            .get_url_sold,
            param
          )

          if page and
             page.at('//span[@class="rcnt"]') and
             page.at('//span[@class="rcnt"]').text.to_i > 0

            {
              ebay_us_numof_active: page.at('//span[@class="rcnt"]')
                                        .text
                                        .to_i,
              ebay_us_uri_active: page_jp.uri.to_s,
            }

          else
            {
              ebay_us_numof_active: 0,
              ebay_us_uri_active: page_jp.uri.to_s,
            }
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Ebay::Mechanize.new(
            e.class,
            e.backtrace,
            __method__
          ), e)
        end

        private

        def get_exchange_rate

          page = nil

          (1..10).each do |i|

            if page.nil?

              page = get('http://info.finance.yahoo.co.jp/fx', {})

              sleep(5)
            end
          end

          if page and
             page.title
                 .match(/^FX・外国為替 - Yahoo!ファイナンス$/)

            page.at('#USDJPY_top_bid')
                .text
                .to_i
          else

            100
          end
        end

        def collect_bidsold(page)

          rcnt     = nil
          bidsold  = nil
          solds    = []
          bidsolds = []

          if page.at('//span[@class="rcnt"]')

            rcnt = page.at('//span[@class="rcnt"]').text.to_i
          end

          if rcnt.to_i > 0

            solds = page.search(
                      '//li[@class="lvprice prc"]/span[@class="bold bidsold"]'
                    )

            solds[0..(rcnt - 1)].each do |sold|

              bidsold = sold.text
                            .to_s
                            .strip
                            .match(/\$([\d,]+)\.\d+/)

              if bidsold

                bidsolds.push(bidsold[1].gsub(/\D+/, '').to_i)
              end
            end
          end

          bidsolds
        end

      end ### class MechanizeX < Ebay

      class Finding < Ebay

        include TWEyes::Mixin::Ebay::Finding

        def initialize(configure)
          super

          @connector = nil
        end

        def initializeX(connector)
          @connector = connector
        end

        def resolve_xml_schema(method)
          eval(sprintf(
            "@configure[:controller][:ebay][:finding][:self].get_%s_xml_schema",
            method
          ))
        end

        class Production < Finding

          public

          def initialize(configure)
            super
          end

          def initializeX(connector)
            super
          end

          def find_completed_items(param)

            responses = {}

            query = build_query(param)

pp query

            response = @connector.request_post(
              resolve_operation_name(__method__),
              sprintf(resolve_xml_schema(__method__), query)
            )

            result = get_response(resolve_operation_name(__method__), response)
            pagination = get_pagination_output_response(
                           resolve_operation_name(__method__),
                           response
                         )

            (pagination[:page_number]..pagination[:total_pages]).each do |n|
              query[:page_number] = n
              response = @connector.request_post(
                resolve_operation_name(__method__),
                sprintf(resolve_xml_schema(__method__), query)
              )

              case result[:ack]
              when 'success', 'warning'
                responses.merge!(eval(sprintf(
                  "get_%s_response('%s', response)",
                  __method__,
                  resolve_operation_name(__method__)
                )))
              when 'failure'
              end
            end

            responses
          rescue => e
            raise(
              TWEyes::Exception::Controller::Ebay::Finding::Production.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def find_items_by_keywords(param)

            responses = {}

            query = build_query(param)

            response = @connector.request_post(
              resolve_operation_name(__method__),
              sprintf(resolve_xml_schema(__method__), query)
            )

            result = get_response(resolve_operation_name(__method__), response)
            pagination = get_pagination_output_response(
                           resolve_operation_name(__method__),
                           response
                         )

            (pagination[:page_number]..pagination[:total_pages]).each do |n|
              query[:page_number] = n
              response = @connector.request_post(
                resolve_operation_name(__method__),
                sprintf(resolve_xml_schema(__method__), query)
              )

              case result[:ack]
              when 'success', 'warning'
                responses.merge!(eval(sprintf(
                  "get_%s_response('%s', response)",
                  __method__,
                  resolve_operation_name(__method__)
                )))
              when 'failure'
              end
            end

            responses
          rescue => e
            raise(
              TWEyes::Exception::Controller::Ebay::Finding::Production.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          protected

          private

          def build_query(param)

            temporary = []
            query     = {}

            query = {
              xmlns: @configure[:controller][:ebay][:finding][:self]
                     .get_xml_namespace,
              page_number: 1
            }

            if param[:system_research_analysis_ebay_us_query].to_s.size > 0
              query.store(
                :query,
                param[:system_research_analysis_ebay_us_query]
              )
            end

            if param[:system_research_analysis_ebay_us_max_price].to_s.size > 0
              query.store(
                :max_price,
                param[:system_research_analysis_ebay_us_max_price]
              )
            end

            if param[:system_research_analysis_ebay_us_min_price].to_s.size > 0
              query.store(
                :min_price,
                param[:system_research_analysis_ebay_us_min_price]
              )
            end

            temporary.push(param[
              :research_analysis_ebay_us_query_include_everything
            ])

            if param[
              :research_analysis_ebay_us_query_include_either
            ].to_s.size > 0

              temporary.push("(#{param[
                :research_analysis_ebay_us_query_include_either
              ].split.join(', ')})")
            end

            if param[
              :research_analysis_ebay_us_query_not_include
            ].to_s.size > 0

              temporary.push(param[
                :research_analysis_ebay_us_query_not_include
              ].split.map{|i| i.sub(/^/, '-')}.join(' '))
            end

            query.store(
              :query,
              temporary.join(' ').to_s.strip
            )

            query.store(
              :category_id,
              param[:research_analysis_ebay_us_category_id]
            )

            if param[:research_analysis_ebay_us_max_price].to_s.size > 0
              query.store(
                :max_price,
                param[:research_analysis_ebay_us_max_price]
              )
            end

            if param[:research_analysis_ebay_us_min_price].to_s.size > 0
              query.store(
                :min_price,
                param[:research_analysis_ebay_us_min_price]
              )
            end

            query
          end

        end ### class Production < Finding

      end ### class Finding < Ebay

      class BusinessPoliciesManagement < Ebay

        include TWEyes::Mixin::Ebay::BusinessPoliciesManagement

        def initialize(configure)
          super

          @connector = nil
        end

        def initializeX(connector)

          @connector = connector
        end

        def resolve_xml_schema(method)

          eval(sprintf(
            "@configure[:controller][:ebay][:business_policies_management][:self].get_%s_xml_schema",
            method
          ))
        end

        class Production < BusinessPoliciesManagement

          public

          def initialize(configure)

            super
          end

          def initializeX(connector)

            super
          end

          def get_seller_profiles(item)

            responses = {}

            response = @connector.request_post(
              resolve_operation_name(__method__),
              @configure[:system][:user].account['ebay_us_auth_token'],
              sprintf(resolve_xml_schema(__method__), item)
            )

            result = get_response(resolve_operation_name(__method__), response)
            pagination = get_pagination_output_response(
                           resolve_operation_name(__method__),
                           response
                         )

            (pagination[:page_number]..pagination[:total_pages]).each do |n|
              query[:page_number] = n
              response = @connector.request_post(
                resolve_operation_name(__method__),
                sprintf(resolve_xml_schema(__method__), query)
              )

              case result[:ack]
              when 'success', 'warning'
                responses.merge!(eval(sprintf(
                  "get_%s_response('%s', response)",
                  __method__,
                  resolve_operation_name(__method__)
                )))
              when 'failure'
              end
            end

            responses
          rescue => e
            raise(
              TWEyes::Exception::Controller::Ebay::Finding::Production.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          protected

          private

        end ### class Production < Finding

      end ### class Finding < Ebay

      class Trading < Ebay

        include TWEyes::Mixin::Ebay::Trading

        def initialize(configure)
          super

          @connector = nil
        end

        def initializeX(connector)
          @connector = connector
        end

        class Production < Trading

          public

          def initialize(configure)
            super
          end

          def initializeX(connector)
            super
          end

          def add_item(item)

            item.store(:item_image_xml, upload_site_hosted_pictures(item))
            item.merge!(merge(item))
            item.merge!(@configure[:system][:user].account)

            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__),
                item.symbolize_keys
              ).gsub(/\x08/, '')
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack] 
            when 'success', 'warning'
              result_item = eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__) 
              ))
              return result.merge(result_item.merge(get_item(result_item)))
            when 'failure'

              item.store(:message, result[:message])

              raise result[:errors].to_s
            end
          rescue => e

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

          def revise_item(item)

            item.merge!(merge(item))
            item.merge!(@configure[:system][:user].account)
            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__),
                item.symbolize_keys
              ).gsub(/\x08/, '')
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'
              result_item = eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__)
              ))
              return result.merge(result_item.merge(get_item(result_item)))
            when 'failure'

              item.store(:message, result[:message])

              raise result[:errors].to_s
            end
          rescue => e
            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

          def relist_item(item)

            item.merge!(merge(item))
            item.merge!(@configure[:system][:user].account)
            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__),
                item.symbolize_keys
              ).gsub(/\x08/, '')
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'
              result_item = eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__)
              ))
              return result.merge(result_item.merge(get_item(result_item)))
            when 'failure'

              item.store(:message, result[:message])

              raise result[:errors].to_s
            end
          rescue => e
            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

          def end_item(item)
            item.merge!(@configure[:system][:user].account)
            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(resolve_xml_schema(__method__), item.symbolize_keys)
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'
              return result
            when 'failure'
              raise result[:errors].to_s
            end
          rescue => e
            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

          def get_item(result_item)
            result_item.merge!(@configure[:system][:user].account)
            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__),
                result_item.symbolize_keys
              )
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack] 
            when 'success', 'warning'
              return eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__) 
              ))
            when 'failure'
              raise result[:errors].to_s
            end
          end

          def get_item_transactions(item)

            item.merge!(@configure[:system][:user].account)
            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(resolve_xml_schema(__method__), item.symbolize_keys)
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'

              return eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__) 
              )).merge(result)
            when 'failure'
              raise result[:errors].to_s
            end
          rescue => e

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

          def get_orders

            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__), 
                {
                  token: @configure[:system][:user]
                         .account['ebay_us_auth_token'],
                  create_time_from: '2017-03-01T20:34:44.000Z',
                  create_time_to: '2017-03-17T20:34:44.000Z',
                }
              )
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'

              return eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__)
              )).merge(result)
            when 'failure'
              raise result[:errors].to_s
            end
          rescue => e

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

          def complete_sale(item)

            item.merge!(@configure[:system][:user].account)
dump_xml(
  sprintf(resolve_xml_schema(__method__), item.symbolize_keys)
)
sleep(60000)
            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(resolve_xml_schema(__method__), item.symbolize_keys)
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'

              return eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__)
              )).merge(result)
            when 'failure'
              raise result[:errors].to_s
            end
          rescue => e

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

          def get_user_preferences

            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__),
                {
                  token: @configure[:system][:user]
                         .account['ebay_us_auth_token'],
                }
              )
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'

              return { "#{__method__}": eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__)
              ))}.merge(result)
            when 'failure'
              raise result[:errors].to_s
            end
          rescue => e

pp e.class, e.message, e.backtrace
exit

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def get_my_ebay_selling(list)

            Retryable.retryable(
              on: [
                Net::ReadTimeout,
              ],
              tries: 3
            ) do |retries, exception|

              response = @connector.request_post(
                resolve_call_name(__method__),
                sprintf(
                  resolve_xml_schema(__method__)[list],
                  @configure[:system][:user].account.symbolize_keys
                )
              )

              result = get_response(resolve_call_name(__method__), response)
              case result[:ack]
              when 'success', 'warning'
                return eval(sprintf(
                  "get_%s_response('%s', '%s', response)",
                  __method__,
                  resolve_call_name(__method__),
                  list.to_camel
                ))
              when 'failure'
                raise result[:errors].to_s
              end
            end
          rescue => e

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def get_session_id

            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__),
                {
                  token: @configure[:api][:ebay][:trading][:production]
                         .get_token,
                  ru_name: @configure[:api][:ebay][:trading][:production]
                           .get_ru_name,
                }
              )
            )

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'
              return eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__)
              )).merge(result)
            when 'failure'
              raise result[:errors].to_s
            end
          rescue => e

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def fetch_token(session_id)

            response = @connector.request_post(
              resolve_call_name(__method__),
              sprintf(
                resolve_xml_schema(__method__),
                {
                  token: @configure[:api][:ebay][:trading][:production]
                         .get_token,
                  session_id: session_id,
                }
              )
            )

###dump_xml(response)

            result = get_response(resolve_call_name(__method__), response)
            case result[:ack]
            when 'success', 'warning'
              return eval(sprintf(
                "get_%s_response('%s', response)",
                __method__,
                resolve_call_name(__method__)
              )).merge(result)
            when 'failure'
              raise result[:errors].to_s
            end
          rescue => e

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          protected

          private

          def upload_site_hosted_pictures(item)

            request      = nil
            response     = nil
            pictures_xml = []

            item.select do |k, v|

              if Regexp.new(/^item_image_\d\d/) =~ k and
                 v.to_s.size > 0

                request = sprintf(
                  resolve_xml_schema(__method__),
                  {
                    ebay_us_auth_token: @configure[:system][:user]
                                        .account['ebay_us_auth_token'],
                    item_image: sprintf(
                                  "%s/%s",
                                  @configure[:system][:net]
                                  .get_http_uri+
                                  @configure[:system][:directory]
                                  .get_relative_images_path,
                                  v
                                )
                  }
                )

                Retryable.retryable(
                  on: [
                    RuntimeError,
                  ],
                  tries: 5
                ) do |retries, exception|

                  response = @connector.request_post(
                    resolve_call_name(__method__),
                    request
                  )

                  doc = REXML::Document.new(response)
                  doc.elements.each(
                    '/UploadSiteHostedPicturesResponse'
                  ) do |e|

                    if e.elements['Errors/ShortMessage']

                      case e.elements['Errors/ShortMessage'].text
                      when /^Copy from ExternalPictureURL failed/

                        raise(e.elements['Errors/ShortMessage'].text)
                      end
                    end
                  end
                end

dump_xml(response)

                doc = REXML::Document.new(response)
                doc.elements.each('/UploadSiteHostedPicturesResponse') do |e|
                  case e.elements['Ack'].text
                  when 'Success'
                    e.elements.each('SiteHostedPictureDetails') do |e|
                      pictures_xml.push(
                        sprintf("<PictureURL>%s</PictureURL>",
                          e.elements['FullURL'].text
                        )
                      )
                    end
                  when 'Warning'
                    e.elements.each('SiteHostedPictureDetails') do |e|
                      pictures_xml.push(
                        sprintf("<PictureURL>%s</PictureURL>",
                          e.elements['FullURL'].text
                        )
                      )
                    end
                    e.elements.each('Errors') do |e|
                      pp e.elements["LongMessage"].text
                    end
                  when 'Failure'
                    e.elements.each('Errors') do |e|
                      pp e.elements["LongMessage"].text
                    end
                  end
                end
              end
            end

            pictures_xml.join
          rescue => e

            item.store(:message, '')

            raise(
              TWEyes::Exception::Controller::Ebay::Trading::Production.new(
                e.class,
                e.backtrace,
                __method__,
                item
              ), e)
          end

        end ### class Production < Trading

      end ### class Trading < Ebay

    end ### class Ebay

  end ### module Controller [END]

end ### module TWEyes [END]
