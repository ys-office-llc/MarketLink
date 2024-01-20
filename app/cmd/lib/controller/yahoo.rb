module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    class Yahoo

      include TWEyes::Mixin::Languages::XML

      @@account  = nil
      @@password = nil
      @@appid    = nil
      @@secret   = nil

      public

      def initialize(configure)

        super()

        @configure = configure
      end

      def initializeX(type)

        @@account  = @configure[:system][:user]
                     .account[sprintf("yahoo_%s_account", type)]
        @@password = @configure[:system][:user]
                     .account[sprintf("yahoo_%s_password", type)]
        @@appid    = @configure[:system][:user]
                     .account[sprintf("yahooapis_%s_appid", type)]
        @@secret   = @configure[:system][:user]
                     .account[sprintf("yahooapis_%s_secret", type)]
      end

      def set_cookies_jar(cookies_jar)

        @cookies_jar = cookies_jar
      end

      protected

      def login?(driver)

        account_from_masthead = nil

        begin
          account_from_masthead =
            driver.connector[:driver]
                  .find_element(
                    :xpath,
                    '//*[@id="masthead"]/div/div[2]/strong')
                  .text
        rescue Selenium::WebDriver::Error::NoSuchElementError

          return false
        else
        end

        if @@account == account_from_masthead
          return true
        else
          driver.connector[:driver]
                .find_element(:css, '.yjmthloginarea > a:nth-child(3)')
                .click
          return false
        end
      rescue => e

        raise(e.class, e.message)
      end

      def login(driver)

        driver.connector[:driver]
              .find_element(:id, 'username')
              .send_keys @@account

        driver.connector[:driver]
              .find_element(:id, 'passwd')
              .send_keys @@password

        driver.connector[:driver]
              .find_element(:id, '.save')
              .click
      rescue Selenium::WebDriver::Error::TimeOutError => e

        raise(e.class, e.message)
      end

      def enter_password(driver)

        begin

          driver.find_element(:id, 'passwd')
        rescue Selenium::WebDriver::Error::NoSuchElementError
        else

          driver.find_element(:id, 'passwd').send_keys @@password
          ###driver.find_element(:id, '.save').click
          driver.find_element(:id, 'btnSubmit').click
        end
      rescue Selenium::WebDriver::Error::TimeOutError => e

        raise(e.class, e.message)
      end

      def add_cookie(driver, logger)

        reconnect = {
          current: 0,
          limit: 3,
          waiting_for: 10,
        }

        cookie_jar_yaml_path = nil

        cookie_jar_yaml_path = sprintf(
          "%s/set_cookie/%s_%s.yml",
          @configure[:system][:directory].get_spool_path,
          @@account,
          Digest::MD5.hexdigest(@@password)
        )

        @cookies_jar.load(cookie_jar_yaml_path)

        driver.connector[:driver]
              .manage
              .delete_all_cookies

        js = nil

        @cookies_jar.cookies.each do |cookie|

          logger[:user].info(cookie)
          sleep(5)

          begin

            driver.connector[:driver]
                  .get("http://#{cookie.domain}")

            js = sprintf(
                   "document.cookie="+
                   "'%s=%s;path=/;domain=%s'",
                   cookie.name,
                   URI.escape(cookie.value),
                   cookie.domain
                 )

            logger[:user].info(js)
            driver.connector[:driver].execute_script(js)

=begin
            driver.connector[:driver]
                  .manage
                  .add_cookie(
                    {
                      name: cookie.name,
                      value: cookie.value,
                      domain: ".#{cookie.domain}",
                    }
                  )
=end
          rescue => e

            logger[:user].error(
              sprintf(
                "%s::%s, %s, %s, %s, %s, %s, [%s]",
                self.class,
                __method__, 
                driver.connector[:driver].current_url,
                e.class,
                e.message,
                e.backtrace,
                reconnect,
                driver.connector[:driver]
                      .page_source
                      .encode('UTF-8')
              )
            )

            if reconnect[:limit] > reconnect[:current]

              sleep(reconnect[:waiting_for])
              reconnect[:current] = reconnect[:current].succ

              retry
            else

              raise(
                e.class,
                sprintf(
                  "Reconnection abandonment: %s, %s",
                  e.message,
                  reconnect
                )
              )
            end
          end
        end
      end

      private

      class Auth < Yahoo

        include TWEyes::Mixin::Protocols::HTTP::NetHTTP

        public

        def initialize(configure)

          super
        end

        protected

        def manage_token(builder, driver, logger)

          weeks4  = (24 * 60 * 60 * 7 * 4)
          now     = @configure[:system][:date].get_time
          current = builder.get_token

          if current.empty?

            driver.create_driver(:chrome)
            add_cookie(driver, logger)
            new = get_token(driver, logger)
            driver.destroy_driver
            new.store(:created_datetime, now)
            builder.insert(new)
            logger[:user].info(
              sprintf("Take token (created_datetime: %s)",
                new[:created_datetime]
              )
            )
          ### 1 時間以上で 4 週間以内ならリフレッシュする
          elsif (current['created_datetime'] + current['expires_in']) < now and
                 now < (current['created_datetime'] + weeks4)

            refresh = refresh_token(current['refresh_token'])

            if refresh['error']

              case refresh['error_code'].to_i
              when 103, 104, 701, 1113, 1118, 4100, 4101, 4102

                driver.create_driver(:chrome)
                add_cookie(driver, logger)
                new = get_token(driver, logger)
                driver.destroy_driver
                new.store(:created_datetime, now)
                builder.update(current['id'], new)
              else

                driver.create_driver(:chrome)
                raise(sprintf("%s", refresh))
              end
            else

              refresh.store(:created_datetime, now)
              builder.update(current['id'], refresh)
              logger[:user].info(
                sprintf("Refresh token (created_datetime: %s)",
                  current['created_datetime']
                )
              )
            end
            ### 4 週間以上なら新たに取り直す
          elsif now > (current['created_datetime'] + weeks4)

            driver.create_driver(:chrome)
            add_cookie(driver, logger)
            new = get_token(driver, logger)
            driver.destroy_driver
            new.store(:created_datetime, now)
            builder.update(current['id'], new)
            logger[:user].info(
              sprintf("Re take token (created_datetime: %s)",
                new[:created_datetime]
              )
            )
          else

            logger[:user].info(
              sprintf("Token is still valid (created_datetime: %s)",
                current['created_datetime']
              )
            )
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auth.new(
            e.class,
            e.backtrace,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        private

        def get_token(driver, logger)

          params            = {}
          authorization_uri = nil
          code_uri          = nil
          message           = nil

          driver.connector[:waiter].until do

            authorization_uri = sprintf(
              @configure[:controller][:yahoo][:auth].get_authorization_url,
              @@appid,
              @configure[:system][:net].get_http_uri,
              SecureRandom.hex(16)
            )

pp authorization_uri

            driver.connector[:driver].get(authorization_uri)

            ### 初期の認証への「同意」をする場合
            begin

              driver.connector[:driver].find_element(:id, ".save")
            rescue Selenium::WebDriver::Error::NoSuchElementError
            else

              driver.connector[:driver].find_element(:id, ".save").click
            end

            enter_password(driver.connector[:driver])
            code_uri = driver.connector[:driver].current_url
            matches = code_uri.match(/&code=(\w+)$/)
            ### matches = code_uri.match(/\?code=([^&]+)&/)

pp driver.capture
pp code_uri
pp matches

            if matches

              params = {
                'grant_type': 'authorization_code',
                'code': matches[1],
                'redirect_uri': @configure[:system][:net].get_http_uri,
              }

              return JSON.parse(
                post(
                  @configure[:controller][:yahoo][:auth].get_token_url,
                  true,
                  nil,
                  params,
                  @@appid,
                  @@secret
                )
              )
            else

              begin

                message = driver.connector[:driver].find_element(
                            :xpath,
                            '//*[@id="themeBox"]/div[1]/h2'
                          )

                if message and
                   message.text
                          .split(/\n/)[0]
                          .match("パスワードは本日変更されています。")

                  raise("コード取得失敗（パスワードは本日変更されています。）")
                else

                  raise("コード取得失敗（原因不明）")
                end
              rescue Selenium::WebDriver::Error::NoSuchElementError => e

                raise("コード取得失敗（原因不明）")
              ensure
              end
            end
          end
        end

        def refresh_token(token)

          params = {
            'grant_type': 'refresh_token',
            'refresh_token': token,
          }

          return JSON.parse(
            post(
              @configure[:controller][:yahoo][:auth].get_token_url,
              true,
              nil,
              params,
              @@appid,
              @@secret
            )
          )
        end

        class Seller < Auth
  
          public
  
          def initialize(configure)
            super
          end

          def manage_token(builder, driver, logger)
            super(
              builder[:mysql][:auth][:yahoo][:seller],
              driver,
              logger
            )
          end
  
          protected
  
          private
  
        end ### class Seller < Auth

        class Buyer < Auth
  
          public
  
          def initialize(configure)
            super
          end
  
          def manage_token(builder, driver, logger)
            super(
              builder[:mysql][:auth][:yahoo][:buyer],
              driver,
              logger
            )
          end
  
          protected
  
          private
  
        end ### class Seller < Auth

      end ### class Auth < Yahoo [END]

      class Developer < Yahoo

        ###include TWEyes::Mixin::Yahoo::Developer

        public

        def initialize(configure)

          super

          @cookies_jar = nil
        end

        def get_application(driver, logger)

          elements = []

          add_cookie(driver, logger)

          driver.connector[:waiter].until do

            driver.connector[:driver].get(
              @configure[:controller][:yahoo][:developer][:self]
              .get_dashboard_url
            )
            driver.connector[:driver].find_element(
              :link_text,
              @configure[:system][:products].name
            ).click
            driver.connector[:driver].find_elements(
              :tag_name,
              'code'
            ).each do |element|

              elements.push(element.text.encode('UTF-8'))
            end
          end

          elements
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Developer.new(
            e.class,
            e.backtrace,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def create_application(driver, logger)

          add_cookie(driver, logger)

          driver.connector[:waiter].until do

            driver.connector[:driver].get(
              @configure[:controller][:yahoo][:developer][:self]
              .get_dashboard_url
            )

            begin

              driver.connector[:driver].find_element(
                :link_text,
                @configure[:system][:products].name
              )
            rescue Selenium::WebDriver::Error::NoSuchElementError
            else

              return
            end

            driver.connector[:driver].find_element(
              :link_text,
              '新しいアプリケーションを開発'
            ).click
            driver.connector[:driver].find_element(
              :name,
              'appname'
            ).clear
            driver.connector[:driver].find_element(
              :name,
              'appname'
            ).send_keys @configure[:system][:products].name
            driver.connector[:driver].find_element(
              :name,
              'url_website'
            ).clear
            driver.connector[:driver].find_element(
              :name,
              'url_website'
            ).send_keys @configure[:system][:net].get_http_uri
            driver.connector[:driver].find_element(
              :name,
              'scopelist[]'
            ).click
            driver.connector[:driver].find_element(
              :name,
              'guideline'
            ).click
            driver.connector[:driver].find_element(
              :id,
              'yjdn-submit'
            ).click
            driver.connector[:driver].find_element(
              :id,
              'yjdn-submit'
            ).click
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Developer.new(
            e.class,
            e.backtrace,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def update_application(driver, logger)

          add_cookie(driver, logger)

          driver.connector[:waiter].until do

            driver.connector[:driver].get(
              @configure[:controller][:yahoo][:developer][:self]
              .get_dashboard_url
            )
            driver.connector[:driver].find_element(
              :link_text,
              @configure[:system][:products].name
            ).click
            driver.connector[:driver].find_element(
              :name,
              'callback_fulluri'
            ).clear
            driver.connector[:driver].find_element(
              :name,
              'callback_fulluri'
            ).send_keys @configure[:system][:net].get_http_uri
            driver.connector[:driver].find_element(
              :xpath,
              '//*[@id="yos-dt-button-about-save"]/span/button'
            ).click
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Developer.new(
            e.class,
            e.backtrace,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        protected

        private

      end ### class Developer < Yahoo [END]

      class Auctions < Yahoo

        include TWEyes::Mixin::Yahoo::Auctions

        public

        def initialize(configure)

          super

          @cookies_jar   = nil
          @retry_limit   = 3
          @retry_current = 0
        end

        def my_close_list_has_winner(driver, logger)

          item = {}

          contact_progress = {
            '1商品を受け取りました' => 'address_inputing',
            '2商品を受け取りました' => 'postage_inputing',
            '3商品を受け取りました' => 'money_received',
            '4商品を受け取りました' => 'preparation_for_shipment',
            '5商品を受け取りました' => 'shipping',
            '商品を受け取りました' => 'complete',
          }

          items = Hash.new{|h,k| h[k] = {}}

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

            driver.connector[:waiter].until do

              driver.connector[:driver].get(
               'https://auctions.yahoo.co.jp/closeduser/jp/show/mystatus?select=closed&hasWinner=1'
              )
### pp driver.capture
              driver.connector[:driver].find_elements(
                :tag_name,
                'tr'
              ).each do |tr|

                td = tr.find_elements(:tag_name, 'td')

                if td.size == 11

                  date_time = td[4].text.match(/(\d+)月(\d+)日\s+(\d+)時(\d+)分/)

                  items[td[1].text] = {
                    auction_id: td[1].text,
                    title: td[2].text,
                    highest_price: td[3].text.gsub(/\D+/, '').to_i,
                    winner_id: td[5].text,
                    contact_url: td[6].find_element(:tag_name, 'a')
                                      .attribute('href'),
                    end_time: Time.parse(
                                sprintf("%s/%s %s:%s",
                                  date_time[0],
                                  date_time[1],
                                  date_time[2],
                                  date_time[3]
                                )
                              ).to_datetime.rfc3339,
                    progress: contact_progress[td[6].text],
                    auction_item_url: td[2].find_element(:tag_name, 'a')
                                           .attribute('href'),
                  }
                end
              end
            end
          end

          items
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::MyCloseListHasWinner.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def my_selling_list(driver, logger)

          item      = {}
          num_watch = 0

          items = Hash.new{|h,k| h[k] = {}}

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

            driver.connector[:waiter].until do

              driver.connector[:driver].get(
                'https://auctions.yahoo.co.jp/openuser/jp/show/mystatus?select=selling'
              )
### pp driver.capture
              driver.connector[:driver].find_elements(
                :tag_name,
                'tr'
              ).each do |tr|


                td = tr.find_elements(:tag_name, 'td')
                if td.size == 10

                  if td[0].text.match('商品ID')

                    next
                  end

                  if td[5].text.match('-')

                    num_watch = 0
                  else

                    num_watch = td[5].text.to_i
                  end

                  items[td[0].text] = {
                    auction_id: td[0].text,
                    title: td[1].text,
                    current_price: td[2].text.gsub(/\D+/, '').to_i,
                    auction_item_url: td[1].find_element(:tag_name, 'a')
                                           .attribute('href'),
                    num_watch: num_watch,
                  }
                end
              end
            end
          end

          items.each do |auction_id, item|

            driver.connector[:driver].get(item[:auction_item_url])
            items[auction_id][:end_time] = Time.parse(
              driver.connector[:driver].find_element(
                :xpath,
                '//*[@id="l-main"]/div/div[2]/div/div/div[1]/ul/li[4]/dl/dd'
              ).text
            ).to_datetime.rfc3339
          end

          items
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::MySellingList.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def place_bids(driver, logger, item)

          bidding_limit = item[:bids_bids_price]
          price         = 0

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

            driver.connector[:waiter].until do

              driver.connector[:driver].get(
                item[:bids_auction_item_url]
              )
              driver.connector[:driver].find_element(
                :link_text,
                '入札する'
              ).click
              driver.connector[:driver].find_element(
                :css,
                'input.js-validator-submit'
              ).click
              driver.connector[:driver].find_element(
                :class_name,
                'SubmitBox__button--bid'
              ).click

pp driver.capture
pp driver.connector[:driver].current_url

if driver.connector[:driver].current_url.match(/placebid$/)

  return true
end

              price = driver.connector[:driver].find_elements(
                        :tag_name,
                        'dd'
                      )[0].text.gsub(/\D+/, '').to_i

              while price < bidding_limit

pp price, bidding_limit

                driver.connector[:driver].find_element(
                  :class_name,
                  'SubmitBox__button--rebid'
                ).click

pp driver.connector[:driver].current_url
if driver.connector[:driver].current_url.match(/placebid$/)

  return true
end
                price = driver.connector[:driver].find_elements(
                          :tag_name,
                          'dd'
                        )[0].text.gsub(/\D+/, '').to_i
                sleep(1)
              end
            end

pp driver.capture
            return false
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::PlaceBids.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def place_value_comment(driver, logger, item)

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

          driver.connector[:waiter].until do

              driver.connector[:driver].get(
                item['item_yahoo_auctions_url']
              )
              driver.connector[:driver].find_element(
                :link_text,
                '評価する'
              ).click
              driver.connector[:driver].find_element(
                :id,
                'rate'
              ).clear
              driver.connector[:driver].find_element(
                :id,
                'rate'
              ).send_keys item['item_condition_yahoo_auctions_value_comment']
              driver.connector[:driver].find_element(
                :xpath,
                "//*[@id='decCheck']"
              ).click
              driver.connector[:driver].find_element(
                :xpath,
                "/html/body/table[2]/tbody/tr/td/form[1]/div/input"
              ).click
            end
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::PlaceValueComment.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def get_shipping_information(driver, logger, item)

          delivery_confirm     = nil
          delivery_index       = nil
          delivery_indecies    = [4, 5, 6, 7, 8, 9]
          shipping_information = []

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

          driver.connector[:waiter].until do

            driver.connector[:driver].get(
              item['item_yahoo_auctions_url']
            )

            begin

              driver.connector[:driver].find_element(
                :name,
                "tradingnavi_Issue--next"
              ).click
            rescue Selenium::WebDriver::Error::NoSuchElementError
            end

            driver.connector[:driver].find_element(
              :xpath,
              '//*[@id="modTradingNaviStep"]/div[2]/a'
            ).click
            sleep(3)

            delivery_indecies.each do |ix|

              begin

                delivery_confirm = driver.connector[:driver].find_element(
                  :xpath,
                  "//*[@id='yjMain']/div[#{ix}]/div/div[2]/p/a"
                )
                delivery_index = ix
                if delivery_index

                  break
                end
              rescue Selenium::WebDriver::Error::NoSuchElementError
              end
            end 

            if delivery_confirm and
               delivery_confirm
               .text
               .to_s
               .match(
                 /^お届け情報・お支払い情報などを確認する$/
               )

              delivery_confirm.click

              shipping_information = driver.connector[:driver].find_elements(
                :xpath,
                "//*[@id='yjMain']/div[#{delivery_index}]/div/div[3]/div[1]/table/tbody/tr/td/div/table/tbody/tr/td"
              )

              postal_code, street_address = shipping_information[1]
                                            .text
                                            .split(/\n/)

              {
                name: shipping_information[0].text,
                postal_code: postal_code,
                street_address: street_address,
                phone_number: shipping_information[2].text,
                delivery_method: shipping_information[3].text,
              }
            end
          end
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::GetShippingInformation.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def question_to_the_seller(driver, logger, item)

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

          driver.connector[:waiter].until do

            driver.connector[:driver].get(
              sprintf(
                "https://auctions.yahoo.co.jp/jp/show/qanda?aID=%s",
                item['research_watch_list_auction_id']
              )
            )
            driver.connector[:driver].find_element(
              :id,
              'comment'
            ).click
            driver.connector[:driver].find_element(
              :id,
              'comment'
            ).clear
            driver.connector[:driver].find_element(
              :id,
              'comment'
            ).send_keys sprintf("%s　様　商品への質問ではなく申し訳ありません。しかしもしカメラ転売をされているのであれば、カメラ転売自動リサーチツールに興味ありませんか？　http://m.1gnitestrategy.com/l/c/mQCW2hDt/gmFzfQRA/　2月3日（土）23:59まで公開中です。　ぜひチェックしてみてください。", item['research_watch_list_seller_id'])
            driver.connector[:driver].find_element(
              :xpath,
              '//*[@id="modFormSbt"]/div[1]/input[1]'
            ).click
sleep(5)
            driver.connector[:driver].find_element(
              :xpath,
              '//*[@id="modFormSbt"]/div[1]/table/tbody/tr/td[1]/form/input[1]'
            ).click
pp driver.capture
sleep(10)
          end
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::PlaceBids.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def description(driver, logger, item)

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

            logger[:user].info(
              sprintf(
                "説明文のみ入力をします。（%s（%s））",
                item['item_yahoo_auctions_product_name'],
                item['item_yahoo_auctions_url']
              )
            )

            driver.connector[:waiter].until do

              driver.connector[:driver].get(
                item['item_yahoo_auctions_url']
              )

              driver.connector[:driver]
                    .find_element(
                      :xpath,
                      '//*[@id="modAlertBox"]/div/div/div/div/div/div/div/div/p/strong/a'
                    ).click
              sleep(3)

              close_button(driver.connector[:driver], logger)
              click_insertion_ok(driver.connector[:driver], logger)

              enter_description(
                driver.connector[:driver],
                logger,
                item['item_yahoo_auctions_page']
              )

              click_confirm(driver.connector[:driver], logger)
              click_exhibit(driver.connector[:driver], logger)
              after_close_button(driver.connector[:driver], logger)
            end
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::AddItem.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def add_item(driver, logger, item)

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

            logger[:user].info(
              sprintf(
                "オークション新規出品を開始します。（%s（%s））",
                item['item_yahoo_auctions_product_name'],
                item['item_yahoo_auctions_url']
              )
            )

            driver.connector[:waiter].until do

              driver.connector[:driver].get(
                @configure[:controller][:yahoo][:auctions][:self].get_url
              )

              enter_password(driver.connector[:driver])

              case item['item_condition_yahoo_auctions_select_category_id']
              when 1
                select_category(
                  driver.connector[:driver],
                  logger,
                  item['item_category_yahoo_auctions'],
                  item['item_maker_yahoo_auctions']
                )
              when 2
                search_category(
                  driver,
                  logger,
                  item['item_product_name']
                )
              end

              close_button(driver.connector[:driver], logger)

              if item['item_image_01'].to_s.size > 0

                upload_images(
                  @configure,
                  driver,
                  logger,
                  item
                )
              end

              enter_title(
                driver.connector[:driver],
                logger,
                item['item_yahoo_auctions_product_name']
              )

              select_istatus(
                driver,
                logger,
                item['item_condition_yahoo_auctions_item_status_id'],
                item['item_condition_yahoo_auctions_item_status']
              )

              switch_to_html(driver.connector[:driver], logger)

              enter_html_description(
                driver,
                logger,
                item['item_yahoo_auctions_page']
              )

              select_shipping_origin(
                driver.connector[:driver],
                logger,
                item['item_condition_yahoo_auctions_shipping_origin_id'],
                item['item_condition_yahoo_auctions_shipping_origin']
              )

              select_auc_shipping_who(
                driver,
                logger
              )

              enter_shipfee_input1(
                driver,
                logger,
                item['item_condition_yahoo_auctions_shipname_standard_id'],
                item['item_condition_yahoo_auctions_shipname_standard'],
                item['item_condition_yahoo_auctions_delivery_cost'],
                item['item_condition_yahoo_auctions_delivery_additional_cost']
              )

              select_end_time(
                @configure,
                driver,
                logger,
                item['item_condition_yahoo_auctions_sales_period_id'],
                item['item_condition_yahoo_auctions_sales_period'],
                item['item_condition_yahoo_auctions_endtime_id'],
                item['item_condition_yahoo_auctions_endtime']
              )

              select_exhibits_style(
                driver.connector[:driver],
                logger,
                item['item_condition_yahoo_auctions_exhibits_style_id'],
                item['item_condition_yahoo_auctions_exhibits_style'],
                item['item_yahoo_auctions_start_price'],
                item['item_yahoo_auctions_end_price'],
              )

=begin
              set_options(
                driver.connector[:driver],
                logger,
                item['item_condition_yahoo_auctions_exhibits_style_id'],
                item['item_do_snipe'],
                item['item_yahoo_auctions_reserve_price'],
                item['item_condition_yahoo_auctions_attention_price'],
              )
=end

              click_confirm(driver.connector[:driver], logger)

              click_exhibit(driver.connector[:driver], logger)

              after_close_button(driver.connector[:driver], logger)
              item['item_yahoo_auctions_url'] =
                get_url(driver.connector[:driver], logger)
              item['item_yahoo_auctions_item_id'] =
                get_id(logger, item['item_yahoo_auctions_url'])
            end
          end
        rescue => e

          logger[:user].error(
            sprintf(
              "オークション出品エラー（%s（%s）%s, %s, %s）",
              item['item_yahoo_auctions_product_name'],
              item['item_yahoo_auctions_url'],
              e.class,
              e.message,
              e.backtrace
            )
          )

          item.store(:dec_error_box, get_dec_error_box(driver))

          if mod_alert_box(
               logger,
               driver
             ) and
             @retry_limit > @retry_current

            logger[:user].warn(
              sprintf(
                "Retry: @retry_limit: [%s], @retry_current: [%s]",
                @retry_limit,
                @retry_current
              )
            )
            @retry_current = @retry_current.succ

            retry
          else

            logger[:user].warn(
              sprintf(
                "Good bye: @retry_limit: [%s], @retry_current: [%s]",
                @retry_limit,
                @retry_current,
              )
            )
          end

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::AddItem.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def resubmit_item(driver, logger, item)

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

            logger[:user].info(
              sprintf(
                "オークション再出品を開始します。（%s（%s））",
                item['item_yahoo_auctions_product_name'],
                item['item_yahoo_auctions_url']
                  .sub('jp/auction/', 'sell/jp/show/resubmit?aID=')
              )
            )
  
            driver.connector[:waiter].until do
  
              driver.connector[:driver].get(
                item['item_yahoo_auctions_url']
                  .sub('jp/auction/', 'sell/jp/show/resubmit?aID=')
              )

              enter_password(driver.connector[:driver])
  
              close_button(driver.connector[:driver], logger)
  
              enter_title(
                driver.connector[:driver],
                logger,
                item['item_yahoo_auctions_product_name']
              )
  
              enter_html_description(
                driver,
                logger,
                item['item_yahoo_auctions_page']
              )

              select_exhibits_style(
                driver.connector[:driver],
                logger,
                item['item_condition_yahoo_auctions_exhibits_style_id'],
                item['item_condition_yahoo_auctions_exhibits_style'],
                item['item_yahoo_auctions_start_price'],
                item['item_yahoo_auctions_end_price'],
              )
  
              select_end_time(
                @configure,
                driver,
                logger,
                item['item_condition_yahoo_auctions_sales_period_id'],
                item['item_condition_yahoo_auctions_sales_period'],
                item['item_condition_yahoo_auctions_endtime_id'],
                item['item_condition_yahoo_auctions_endtime']
              )
  
              click_confirm(driver.connector[:driver], logger)
              click_exhibit(driver.connector[:driver], logger)
              after_close_button(driver.connector[:driver], logger)
            end
          end
        rescue => e

          logger[:user].error(
            sprintf(
              "オークション再出品エラー（%s（%s）%s, %s, %s）",
              item['item_yahoo_auctions_product_name'],
              item['item_yahoo_auctions_url'],
              e.class,
              e.message,
              e.backtrace
            )
          )

          item.store(:dec_error_box, get_dec_error_box(driver))

          if mod_alert_box(
               logger,
               driver
             ) and
             @retry_limit > @retry_current

            logger[:user].warn(
              sprintf(
                "Retry: @retry_limit: [%s], @retry_current: [%s]",
                @retry_limit,
                @retry_current
              )
            )
            @retry_current = @retry_current.succ

            retry
          else

            logger[:user].warn(
              sprintf(
                "Good bye: @retry_limit: [%s], @retry_current: [%s]",
                @retry_limit,
                @retry_current,
              )
            )
          end

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::ResubmitItem.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        def end_item(driver, logger, item)

          add_cookie(driver, logger)

          Retryable.retryable(
            on: [
              Net::ReadTimeout,
            ],
            tries: 3
          ) do |retries, exception|

          driver.connector[:waiter].until do

            driver.connector[:driver].get(
              item['item_yahoo_auctions_url']
              .sub("auction/", "show/cancelauction?aID=")
            )
            sleep(3)

            ###driver.connector[:driver].find_element(:link_text, 'ログイン').click

            ###login(driver)

            dec_js = nil
            begin
              dec_js = driver.connector[:driver]
                             .find_element(
                               :xpath,
                               "//div[@class='decJS']//p/strong")
                             .text
            rescue Selenium::WebDriver::Error::NoSuchElementError
            else
              case dec_js
              when 'このオークションはすでに終了したか、取り消されました。'

                logger[:user].info(
                  sprintf(
                    "%s（%s（%s））",
                    dec_js,
                    item['item_yahoo_auctions_product_name'],
                    item['item_yahoo_auctions_url']
                  )
                )
                return true
              end
            end

            driver.connector[:driver]
                  .find_element(
                    :xpath,
                    "/html/body/center[1]/form/table/tbody/tr[3]/td/input"
                  ).click
            logger[:user].info(
              sprintf(
                "オークションを終了しました。（%s（%s））",
                item['item_yahoo_auctions_product_name'],
                item['item_yahoo_auctions_url']
              )
            )
          end
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Yahoo::Auctions::EndItem.new(
            e.class,
            e.backtrace,
            item,
            driver.capture,
            driver.save_source,
            __method__
          ), e)
        end

        protected

        private

        def get_dec_error_box(driver)

          matches = nil

          begin

            matches = driver.connector[:driver].find_element(
               :xpath,
               '//*[@id="yaucSellItemDiscrpt"]/div[7]/p/strong'
             ).text
          rescue Selenium::WebDriver::Error::NoSuchElementError => e
          end

          matches
        end

        def mod_alert_box(logger, driver)

          box = nil

          logger[:user].warn(sprintf("%s=[%s]", __method__, driver.capture))
          logger[:user].warn(sprintf("%s=[%s]", __method__, driver.save_source))

          begin
      
            box = driver.connector[:driver].find_element(
                    :xpath,
                    '//*[@id="modAlertBox"]'+
                    '/div/div/div/div/div/div/div/div[1]/div/p/strong'
                  ).text.match(/(.+)/)[1]
          rescue Selenium::WebDriver::Error::NoSuchElementError => e

            logger[:user].warn(
              sprintf(
                "%s=[%s, %s]",
                __method__,
                e.class,
                e.message
              )
            )
          else
          end

pp box

          logger[:user].warn(
            sprintf(
              "modAlertBox: [%s]",
              box
            )
          )

          case box
          when /開催時間 終了時間を選択してください。/,
               /システムエラーが発生しました。/,
               /指定されたドキュメントは存在しません。/,
               /このオークションを設定しようとしているカテゴリは存在しません。/

            logger[:user].warn(
              sprintf(
                "modAlertBox: [%s]",
                box
              )
            )

            return true
          else

            logger[:user].warn(
              sprintf(
                "modAlertBox: [%s]",
                'Other error discovery',
              )
            )

            return false
          end
        end

=begin
- 2016-11-05 記念すべき日

  こちらの記事を参考にさせて頂きました。大感謝です。

  「Python requests で取得したログインセッションクッキーを Selenium ブラウザに
    渡してログインする」

  http://qiita.com/ytyng/items/5e6dad02a6adabc21fed

  Selenium Ruby Gems ドキュメント

  http://www.rubydoc.info/gems/selenium-webdriver/0.0.28/Selenium/WebDriver/Options#add_cookie-instance_method

- 2016-11-05 17:48 同日、後刻のこと

  結局解決できていなかった。PhantomJS のバグでCookieが引き渡せない。Firefox
  は引き渡せる。なので、

- 2016-11-05 22:03 安定化したもよう
  

        def add_cookie(driver, logger)

          cookie_jar_yaml_path = nil
  
          cookie_jar_yaml_path = sprintf(
            "%s/set_cookie/%s.yml",
            @configure[:system][:directory].get_spool_path,
            @@account
          )

          @cookies_jar.load(cookie_jar_yaml_path)

          driver.connector[:driver].manage.delete_all_cookies

          js = nil
          @cookies_jar.cookies.each do |cookie|

            driver.connector[:driver]
                  .get("http://#{cookie.domain}")

            js = sprintf(
                   "document.cookie="+
                   "'%s=%s;path=/;domain=%s'",
                   cookie.name,
                   URI.escape(cookie.value),
                   cookie.domain
                 )
pp js
            driver.connector[:driver].execute_script(js)
          end
        end
=end

        def waiter(logger)
          if @configure[:driver][:web][:selenium].headless?
            logger[:user].info(sprintf(
              "Headlessが有効なので%s秒待ちます", sleep(40)
            ))
          else
            logger[:user].info(sprintf(
              "Headlessが無効なので%s秒待ちます", sleep(60)
            ))
          end
        end

        class MechanizeX < Auctions

          include TWEyes::Mixin::Protocols::HTTP::MechanizeX

          def initialize(configure)
            super

            @driver = nil
            @logger = nil
            @agent  = nil
          end

          def initializeX(driver, logger)
            @driver = driver
            @logger = logger
            @agent  = @driver.agent
          end

          def search(param)

            query     = {}

            if param[
                 'research_yahoo_auctions_search_query_include_everything'
               ].to_s.size > 0

              query.store(
                :va,
                param['research_yahoo_auctions_search_query_include_everything']
              )
            end

            if param[
                 'research_yahoo_auctions_search_query_include_either'
               ].to_s.size > 0

              query.store(
                :vo,
                param['research_yahoo_auctions_search_query_include_either']
              )
            end

            if param[
                 'research_yahoo_auctions_search_query_not_include'
               ].to_s.size > 0

              query.store(
                :ve,
                param['research_yahoo_auctions_search_query_not_include']
              )
            end

            if param['research_yahoo_auctions_search_category_id'] > 0

              query.store(
                :auccat,
                param['research_yahoo_auctions_search_category_id']
              )
            end

            if param['research_yahoo_auctions_search_aucminprice'] > 0
              query.store(
                :aucminprice,
                param['research_yahoo_auctions_search_aucminprice']
              )
            end

            if param['research_yahoo_auctions_search_aucmaxprice'] > 0
              query.store(
                :aucmaxprice,
                param['research_yahoo_auctions_search_aucmaxprice']
              )
            end

            if param['research_yahoo_auctions_search_aucmin_bidorbuy_price'] > 0
              query.store(
                :aucmin_bidorbuy_price,
                param['research_yahoo_auctions_search_aucmin_bidorbuy_price']
              )
            end

            if param['research_yahoo_auctions_search_aucmax_bidorbuy_price'] > 0
              query.store(
                :aucmax_bidorbuy_price,
                param['research_yahoo_auctions_search_aucmax_bidorbuy_price']
              )
            end

            if param[
                 'research_yahoo_auctions_search_item_status'
               ].to_s.size > 0

              query.store(
                :item_status,
                case param['research_yahoo_auctions_search_item_status']
                when 'brand_new'
                  1
                when 'second_hand'
                  2
                else
                  0
                end
              )
            end

            if param[
                 'research_yahoo_auctions_search_listing_category'
               ].to_s.size > 0

              query.store(
                :store,
                case param['research_yahoo_auctions_search_listing_category']
                when 'store'
                  1
                when 'general'
                  2
                else
                  0
                end
              )
            end

            if param['research_yahoo_auctions_search_seller'].to_s.size > 0

              query.store(
                :seller,
                param['research_yahoo_auctions_search_seller']
              )
            end

            if param[
                 'research_yahoo_auctions_search_search_target'
               ].to_s.size > 0

              query.store(
                :f,
                case param['research_yahoo_auctions_search_search_target']
                when 'title_only'
                  '0x8'
                when 'title_description'
                  '0x4'
                else
                  '0x4'
                end
              )
            end

            query.store(:select, '05')

pp query
            page = get(
              'https://auctions.yahoo.co.jp/search/search',
              query
            )

pp @driver.save_source(page)
exit
          rescue => e

            raise(TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
          end

          def get_selling(param)

            param.merge!(
              @configure[:mvc][:system][:research][:yahoo][:auctions][:self]
              .get_query_selling
            )

            page = get(
              @configure[:controller][:yahoo][:auctions][:mechanize]
              .get_url_selling,
              param
            )

            if page and
               page.at('//p[@class="total"]/em') and
               page.at('//p[@class="total"]/em').text.to_i > 0

              {
                yahoo_auctions_numof_selling: page.at(
                                                '//p[@class="total"]/em'
                                              )
                                                  .text
                                                  .to_i,
                yahoo_auctions_uri_selling: page.uri.to_s,
              }
            else 

              {
                yahoo_auctions_numof_selling: 0,
                yahoo_auctions_uri_selling: page.uri.to_s
              }
            end
          rescue => e

            raise(TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
          end

          def get_captcha(type)

            max_try  = 5
            captcha  = nil
            account  = @configure[:system][:user]
                       .account[sprintf("yahoo_%s_account", type)]
            password = @configure[:system][:user]
                       .account[sprintf("yahoo_%s_password", type)]

            (1..max_try).each do |i|

              @agent.get(
                @configure[:controller][:yahoo][:auctions][:mechanize]
                .get_set_cookie_url_login
              )

              begin

                @agent.page.form_with(name: 'login_form') do |form|

                  form.field_with(name: 'login').value  = account
                  form.field_with(name: 'passwd').value = password
                  form.click_button
                end
              rescue NilClass
              end

              captcha = @agent.page.body
                              .match(%r!"https://captcha.yahoo.co.jp:443/[^"]+!)
                              .to_s
                              .gsub(/"/, '')

              if captcha.to_s.size.zero?

                sleep(3)
                next
              end
            end

            if captcha.to_s
                      .size
                      .zero?

              raise("#{self.class}, #{__method__}: キャプチャ取得失敗")
            end

            captcha
          rescue => e

            raise(TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
          end

          def set_cookie(type)

            page                 = nil
            captcha              = nil
            request              = nil
            cookie_jar_yaml_path = nil

            account  = @configure[:system][:user]
                       .account[sprintf("yahoo_%s_account", type)]
            password = @configure[:system][:user]
                       .account[sprintf("yahoo_%s_password", type)]
            captcha  = @configure[:system][:user]
                       .account[sprintf("yahoo_auctions_%s_captcha", type)]

            spool_directory = sprintf(
              "%s/%s",
              @configure[:system][:directory].get_spool_path,
              __method__
            )

            FileUtils.mkdir_p(spool_directory)
            
            cookie_jar_yaml_path = sprintf(
              "%s/%s_%s.yml",
              spool_directory,
              account,
              Digest::MD5.hexdigest(password)
            )

            @agent.page
                  .forms
                  .first
                  .fields_with(type: "text")
                  .first.value = captcha

            @agent.page
                  .forms
                  .first
                  .submit

            begin

              ### 文字認証をしたら再度ログインを求められる
              form = @agent.page.forms[0]
              form.fields_with(name: "login")[0].value  = account
              form.fields_with(name: "passwd")[0].value = password
              form.submit
            rescue => e

              raise(e.class, 'クッキー設定失敗')
            end

            ### ログイン成功、クッキーを保存
            @agent.cookie_jar.save_as(cookie_jar_yaml_path)
          rescue => e

            raise(TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
          end

          def get_sold(param)

            param.merge!(
              @configure[:mvc][:system][:research][:yahoo][:auctions][:self]
              .get_query_sold
            )

            page_end = get(
              @configure[:controller][:yahoo][:auctions][:mechanize]
              .get_url_sold,
              param
            )

            page_highest = get(
              @configure[:controller][:yahoo][:auctions][:mechanize]
              .get_url_sold,
              param.merge({'s1': 'cbids', 'o1': 'd'})
            )

            page_lowest = get(
              @configure[:controller][:yahoo][:auctions][:mechanize]
              .get_url_sold,
              param.merge({'s1': 'cbids', 'o1': 'a'})
            )

            if page_end and
               page_end.at('//p[@class="total"]/em') and
               page_end.at('//p[@class="total"]/em').text.to_i > 0

              {
                yahoo_auctions_numof_sold_m4:
                  page_end.at('//p[@class="total"]/em')
                          .text
                          .to_i,
                yahoo_auctions_min_price: 
                  page_end.search('//dl[@class="range"]/dd')[0]
                          .text
                          .gsub(/\D+/, '')
                          .to_i,
                yahoo_auctions_avg_price: 
                  page_end.search('//dl[@class="range"]/dd')[1]
                          .text
                          .gsub(/\D+/, '')
                          .to_i,
                yahoo_auctions_max_price: 
                  page_end.search('//dl[@class="range"]/dd')[2]
                          .text
                          .gsub(/\D+/, '')
                          .to_i,
                yahoo_auctions_uri_sold_end: page_end.uri.to_s,
                yahoo_auctions_uri_sold_highest: page_highest.uri.to_s,
                yahoo_auctions_uri_sold_lowest: page_lowest.uri.to_s,
              }
            else

              {
                yahoo_auctions_numof_sold_m4: 0,
                yahoo_auctions_min_price: 0,
                yahoo_auctions_max_price: 0,
                yahoo_auctions_avg_price: 0,
                yahoo_auctions_uri_sold_end: page_end.uri.to_s,
                yahoo_auctions_uri_sold_highest: page_highest.uri.to_s,
                yahoo_auctions_uri_sold_lowest: page_lowest.uri.to_s,
              }
            end

          rescue => e

            raise(TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
          end

          protected

          private

        end ### MechanizeX < Auctions [END]

      end ### class Auctions < Yahoo

      class API < Yahoo

        def initialize(configure)
          super

          @connector = nil
        end

        def initializeX(connector, type)
          @connector = connector

          @@account  = @configure[:system][:user]
                       .account[sprintf("yahoo_%s_account", type)]
          @@password = @configure[:system][:user]
                       .account[sprintf("yahoo_%s_password", type)]
          @@appid    = @configure[:system][:user]
                       .account[sprintf("yahooapis_%s_appid", type)]
          @@secret   = @configure[:system][:user]
                       .account[sprintf("yahooapis_%s_secret", type)]
        end

        class Auctions < API

          include TWEyes::Mixin::Yahoo::API
          include TWEyes::Mixin::Yahoo::API::Auctions

          public

          def initialize(configure)
            super
          end

          def initializeX(connector, type)
            super
          end

          def watch_list(auction_id)

            response = @connector.request_get(
              __method__,
              { auctionID: auction_id}
            )
            get_error(response)
            get_result_set(response)
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def delete_watch_list(auction_id)

            response = @connector.request_get(
              __method__,
              { auctionID: auction_id}
            )
            get_error(response)
            get_result_set(response)
          rescue => e
            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace
              ), e)
          end

          def open_watch_list

            results = {
              items: {},
              calls: 0,
            }

            response = @connector.request_get(__method__, {start: 1})
            get_error(response)
            result = get_result_set(response)

            if result[:returned] > 0

              last_page = (result[:available] / result[:returned].to_f).ceil
              (1..last_page).each do |n|
                response = @connector.request_get(__method__, {start: n})
                get_error(response)
                results[:items].merge!(
                  eval(sprintf("get_%s_result(response)", __method__))
                )
                results[:calls] += 1
              end
            end

            results
          rescue => e
            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace
              ), e)
          end

          def close_watch_list

            results = {
              items: {},
              calls: 0,
            }

            response = @connector.request_get(__method__, {start: 1})
            get_error(response)
            result = get_result_set(response)

            if result[:returned] > 0

              last_page = (result[:available] / result[:returned].to_f).ceil
              (1..last_page).each do |n|
                response = @connector.request_get(__method__, {start: n})
                get_error(response)
                results[:items].merge!(
                  eval(sprintf("get_%s_result(response)", __method__))
                )
                results[:calls] += 1
              end
            end

            results
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace
              ), e)
          end

          def search(param)

            temporary = []
            query     = {}
            results   = {
              items: {},
              calls: 0,
            }

            temporary.push(param[
              'research_yahoo_auctions_search_query_include_everything'
            ])

            if param[
                 'research_yahoo_auctions_search_query_include_either'
               ].to_s.size > 0

              temporary.push("(#{param[
                'research_yahoo_auctions_search_query_include_either'
              ]})")
            end

            if param[
                 'research_yahoo_auctions_search_query_not_include'
               ].to_s.size > 0

              temporary.push(param[
                'research_yahoo_auctions_search_query_not_include'
              ].split(/[\s　]+/).map{|i| i.sub(/^/, '-')}.join(' '))
            end

            query.store(
              :query,
              temporary.join(' ').to_s.strip
            )

pp query

=begin
            if param['research_yahoo_auctions_search_type_id'].size > 0
              query.store(
                :type,
                param['research_yahoo_auctions_search_type_id']
              )
            end
=end

            if param['research_yahoo_auctions_search_category_id'] > 0
              query.store(
                :category,
                param['research_yahoo_auctions_search_category_id']
              )
            end

=begin
            if param['research_yahoo_auctions_search_sort_id'].size > 0
              query.store(
                :sort,
                param['research_yahoo_auctions_search_sort_id']
              )
            end
=end

=begin
            if param['research_yahoo_auctions_search_order_id'].size > 0
              query.store(
                :order,
                param['research_yahoo_auctions_search_order_id']
              )
            end
=end

            if param['research_yahoo_auctions_search_aucminprice'] > 0
              query.store(
                :aucminprice,
                param['research_yahoo_auctions_search_aucminprice']
              )
            end

            if param['research_yahoo_auctions_search_aucmaxprice'] > 0
              query.store(
                :aucmaxprice,
                param['research_yahoo_auctions_search_aucmaxprice']
              )
            end

            if param['research_yahoo_auctions_search_aucmin_bidorbuy_price'] > 0
              query.store(
                :aucmin_bidorbuy_price,
                param['research_yahoo_auctions_search_aucmin_bidorbuy_price']
              )
            end

            if param['research_yahoo_auctions_search_aucmax_bidorbuy_price'] > 0
              query.store(
                :aucmax_bidorbuy_price,
                param['research_yahoo_auctions_search_aucmax_bidorbuy_price']
              )
            end

            if param[
                 'research_yahoo_auctions_search_item_status'
               ].to_s.size > 0

              query.store(
                :item_status,
                case param['research_yahoo_auctions_search_item_status']
                when 'brand_new'
                  1
                when 'second_hand'
                  2
                else
                  0
                end
              )
            end

            if param[
                 'research_yahoo_auctions_search_listing_category'
               ].to_s.size > 0

              query.store(
                :store,
                case param['research_yahoo_auctions_search_listing_category']
                when 'store'
                  1
                when 'general'
                  2
                else
                  0
                end
              )
            end

            if param['research_yahoo_auctions_search_seller'].to_s.size > 0

              query.store(
                :seller,
                param['research_yahoo_auctions_search_seller']
              )
            end

            if param[
                 'research_yahoo_auctions_search_search_target'
               ].to_s.size > 0

              query.store(
                :f,
                case param['research_yahoo_auctions_search_search_target']
                when 'title_only'
                  '0x8'
                when 'title_description'
                  '0x4'
                else
                  '0x4'
                end
              )
            end

            response = @connector.request_get(__method__, query)
            get_error(response)
            result = get_result_set(response)

            if result[:returned] > 0

              last_page = (result[:available] / result[:returned])
            else

              results[:items].merge!(
                eval(sprintf("get_%s_result(response)", __method__))
              )
              results[:calls] += 1

              return results
            end

            (1..last_page).each do |n|

              query[:page] = n

              pp query
              pp results[:calls]
              pp @configure[:api][:yahoo][:auctions]
                 .get_search['limit'][
                   @configure[:system][:user].account['account_contract_id']
                 ]

              if @configure[:api][:yahoo][:auctions]
                 .get_search['limit'][
                   @configure[:system][:user]
                   .account['account_contract_id']
                 ] < query[:page]

                break
              end

              response = @connector.request_get(
                           __method__,
                           query
                         )
              results[:items].merge!(
                eval(sprintf("get_%s_result(response)", __method__))
              )
              results[:calls] += 1
            end

            results
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace
              ), e)
          end

          def category_tree(category)

            response = @connector.request_get(
              __method__,
              {
                appid: @@appid,
                category: category,
              }
            )
            get_error(response)

            result = get_result_set(response)
            eval(sprintf("get_%s_result(response)", __method__))
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def bid_history(auction_id)

            response = @connector.request_get(
              __method__,
              {
                appid: @@appid,
                auctionID: auction_id
              }
            )
            get_error(response)

dump_xml(response)

            result = get_result_set(response)
            eval(sprintf("get_%s_result(response)", __method__))
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def auction_item(auction_id)

            response = @connector.request_get(
              __method__,
              {
                appid: @@appid,
                auctionID: auction_id
              }
            )
            get_error(response)

            result = get_result_set(response)
            eval(sprintf("get_%s_result(response)", __method__))
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def my_close_list(list)

            items = Hash.new{|h,k| h[k] = {}}

            response = @connector.request_get(__method__, {list: list})
            get_error(response)
            result = get_result_set(response)

            if result[:returned].zero?
              last_page = 0
            else
              last_page = (result[:available] / result[:returned].to_f).ceil
            end
        
            (1..last_page).each do |n|
              response = @connector.request_get(__method__, {list: list, start: n})
              get_error(response)
              doc = REXML::Document.new(response)
              doc.elements['/ResultSet'].each do |element|

                auction_id = element.elements['AuctionID'].text
                items[auction_id] = get_items(element)
              end
            end

            items
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def my_selling_list

            items = Hash.new{|h,k| h[k] = {}}

            response = @connector.request_get(__method__, {})
            get_error(response)
            result = get_result_set(response)
            if result[:returned].zero?
              last_page = 0
            else
              last_page = (result[:available] / result[:returned]) + 1
            end
        
            (1..last_page).each do |n|
              response = @connector.request_get(__method__, {:start => n})
              get_error(response)
              doc = REXML::Document.new(response)
              doc.elements['/ResultSet'].each do |element|
                auction_id = element.elements['AuctionID'].text
                items[auction_id] = get_items(element)
              end
            end

            items
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def my_won_list

            items = Hash.new{|h,k| h[k] = {}}

            response = @connector.request_get(__method__, {})
            get_error(response)
            result = get_result_set(response)
            if result[:returned].zero?
              last_page = 0
            else
              last_page = (result[:available] / result[:returned]) + 1
            end

            (1..last_page).each do |n|
              response = @connector.request_get(__method__, {:start => n})
              get_error(response)
              doc = REXML::Document.new(response)
              doc.elements['/ResultSet'].each do |element|
                auction_id = element.elements['AuctionID'].text
                items[auction_id] = get_items(element)
              end
            end

            items
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          def my_bid_list

            items = Hash.new{|h,k| h[k] = {}}

            response = @connector.request_get(__method__, {})
            get_error(response)
            result = get_result_set(response)
            if result[:returned].zero?
              last_page = 0
            else
              last_page = (result[:available] / result[:returned]) + 1
            end

            (1..last_page).each do |n|
              response = @connector.request_get(__method__, {:start => n})
              get_error(response)
              doc = REXML::Document.new(response)
              doc.elements['/ResultSet'].each do |element|
                auction_id = element.elements['AuctionID'].text
                items[auction_id] = get_items(element)
              end
            end

            items
          rescue => e

            raise(
              TWEyes::Exception::Controller::Yahoo::API::Auctions.new(
                e.class,
                e.backtrace,
                __method__
              ), e)
          end

          protected

          private

        end ### class Auctions < API

        class Shopping < API

          include TWEyes::Mixin::Yahoo::API
          include TWEyes::Mixin::Yahoo::API::Shopping

          public

          def initialize(configure)
            super
          end

          def initializeX(connector, type)
            super
            @seller_id = @configure[:system][:user]
                         .account['yahoo_shopping_seller_id']
          end

          def item_search(item)
            items = []

            response = @connector.request_get(
              __method__,
              {
                query: item['item_product_name'],
                seller_id: @seller_id,
              }
            )
            get_error(response)
            result = get_result_set(response)
            doc = REXML::Document.new(response)
            doc.elements['/ResultSet/Result'].each do |e|
              if e.attributes['index']
                items.push(eval sprintf("get_%s_result(e)", __method__))
              end
            end

            items
          rescue => e
            raise(
              TWEyes::Exception::Controller::Yahoo::API::Shopping.new(
                e.class,
                e.backtrace,
                item
              ),
              e
            )
          end ### def item_search [END]

          def get_category_name(item)
            category = nil

            item_search(item).each do |e|
              category = e[:category_current_name]
              if category
                ### パスは全角20文字（半角40文字）以内
                return category[0..19]
              end
            end

            'その他' ### カテゴリーにヒットしない場合の初期値（仮置き）
          end

          protected

          private

          class Circus < Shopping
 
            public
 
            def initialize(configure)
              super
            end
 
            def initializeX(connector, type)
              super
            end

            ### 在庫参照API
            def get_stock(codes)
              quantities = {}

              response = @connector.request_get(
                __method__,
                {
                  seller_id: @seller_id,
                  item_code: codes.join(','), 
                }
              )
              get_error(response)
              result = get_result_set(response)

              doc = REXML::Document.new(response)
              doc.elements['/ResultSet'].each do |e|
                quantity = eval sprintf("get_%s_result(e)", __method__)
                quantities.store(quantity[:item_code], quantity[:quantity])
              end

              quantities
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace,
                ), e)
            end ### def get_stock [END]

            def set_stock(codes, quantities)
              response = @connector.request_post(
                __method__,
                {
                  seller_id: @seller_id,
                  item_code: codes.join(','),
                  quantity: quantities.join(','),
                }
              )
              get_error(response)
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace,
                ), e)
            end ### def set_stock [END]

            def my_item_list
              items = Hash.new{|h,k| h[k] = {}}
              response = @connector.request_get(
                __method__,
                {
                  query: '手ぬぐい',
                  seller_id: @seller_id,
                  results:   100,
                }
              )
              get_error(response)
              result = get_result_set(response)

              items
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace,
                ), e)
            end ### def my_item_list [END]

            def edit_item(item, category_name)
              item['item_start_price'] =
                (item['item_amazon_jp_price'] * 1.00).to_i

              response = @connector.request_post(
                __method__,
                {
                  seller_id: @seller_id,
                  item_code: item['item_yahoo_shopping_item_id'],
                  path: category_name,
                  ### 商品名は全角75文字(半角150文字)以内で入力してください。
                  name: item['item_product_name'][0..74],
                  price: item['item_start_price'],
                  caption: item['item_description'].to_s+
                           item['item_feature'].to_s,
                  sp_additional: item['item_description'].to_s+
                                 item['item_feature'].to_s,
                }
              )
              get_error(response)
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace,
                  item
                ), e)
            end ### def edit_item [END]

            def delete_item(item)
              response = @connector.request_post(
                __method__,
                {
                  seller_id: @seller_id,
                  item_code: item['item_yahoo_shopping_item_id'],
                }
              )
              get_error(response)
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace,
                  item
                ), e)
            end ### def delete_item [END]

            def get_shop_category_list(item)
              response = @connector.request_get(
                __method__,
                {
                  query: item['item_product_name'],
                  seller_id: @seller_id,
                }
              )
              get_error(response)
              result = get_result_set(response)

              items
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace,
                  item
                ), e)
            end ### def get_shop_category_list [END]

            def upload_item_image(item)
              response = @connector.request_post_multi(
                __method__,
                {
                  seller_id: @seller_id
                },
                {
                  path: @configure[:system][:directory]
                        .get_htdocs_path+item['item_large_image_01'],
                  mime_type: 'image/jpeg',
                  name: item['item_yahoo_shopping_item_id']+'.jpg'
                }
              )
              get_error(response)
              result = get_result_set(response)
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace,
                  item
                ), e)
            end ### def upload_item_image [END]

            def reserve_publish
              response = @connector.request_post(
                __method__,
                {
                  seller_id: @seller_id,
                  mode: 1
                }
              )
              ###get_error(response)
            rescue => e
              raise(
                TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus.new(
                  e.class,
                  e.backtrace
                ), e)
            end ### def reserve_publish [END]

          end ### class Circus < Shopping [END]

        end ### class Shopping < API [END]

      end ### class API < Yahoo [END]

      class Shopping < Yahoo
        public

        def initialize(configure)
          super
        end

        protected

        private
      end

      private

    end ### class Shopping < Yahoo

  end ### module Controller [END]

end ### module TWEyes [END]
