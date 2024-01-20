module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module FreeMarkets ### 名前空間用なので機能を持たせるとバグるよ

      class Mercari

        public

        def initialize(configure)

          super()

          @configure = configure
          @driver    = nil
          @agent     = nil
          @se        = nil
          @pages     = []
        end

        def initializeX(driver)

          @driver = driver
          @agent  = @driver[:web][:mechanize].agent
          @se     = @driver[:web][:selenium]
        end

        def new_arrival

          @pages.push(
            re_acquire_when_failed(
              @configure[:controller][:free_markets][:mercari][:self]
              .new_arrival_urls['self'],
              {}
            )
          )

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::FreeMarkets::Mercari.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        def sell

          ###@se.create_driver(:phantomjs)
          @se.create_driver(:chrome)

          @se.connector[:driver]
             .get("https://www.mercari.com")

          js = sprintf(
                 "document.cookie="+
                 "'PHPSESSID=httpqlvtttau6pdao1abqun3lh;"+
                 "path=/;"+
                 "domain=.mercari.com'"
               )

          @se.connector[:driver].execute_script(js)

          @se.connector[:waiter].until do

            @se.connector[:driver].get(
              'https://www.mercari.com/jp/sell/'
            )

pp @se.capture

            @se.connector[:driver]
               .find_element(:name, 'image1')
               .send_keys '/var/www/vhosts/market-link.global/services/camera/s00/htdocs/images/4/147/thumbnail_01.jpg'
sleep(5)
            @se.connector[:driver]
               .find_element(:name, 'image2')
               .send_keys '/var/www/vhosts/market-link.global/services/camera/s00/htdocs/images/4/147/thumbnail_02.jpg'
sleep(5)
            @se.connector[:driver]
               .find_element(:name, 'image3')
               .send_keys '/var/www/vhosts/market-link.global/services/camera/s00/htdocs/images/4/147/thumbnail_03.jpg'

sleep(5)
            @se.connector[:driver]
               .find_element(:name, 'image4')
               .send_keys '/var/www/vhosts/market-link.global/services/camera/s00/htdocs/images/4/147/thumbnail_04.jpg'
sleep(5)

pp @se.capture

          end
        rescue => e

pp e.class, e.message, @se.capture
        end

        protected

        private

        def set_proxy

          @driver[:web][:mechanize].set_proxy_random(
            @driver[:web][:proxies][:mpp].fetch_proxies_full
          )
        end

        def set_user_agent_alias

          @driver[:web][:mechanize].set_user_agent_alias
        end

        def re_acquire_when_failed(url, param)

          page = nil

          reconnect = {
            current: 0,
            limit: 10,
            waiting_for: 10,
          }

          begin

            (1..reconnect[:limit]).each do |i|

              set_proxy
              set_user_agent_alias
              if page = @agent.get(url, param)

                return page
              else

                sleep(reconnect[:waiting_for])
              end
            end
          rescue => e

            if reconnect[:limit] > reconnect[:current]

              pp e.class, e.message, reconnect
              sleep(reconnect[:waiting_for])
              reconnect[:current] = reconnect[:current].succ

              retry
            else

              pp e.class, e.message, reconnect

              raise(
                sprintf(
                  "Reconnection abandonment: %s, %s, %s",
                  e.class,
                  e.message,
                  reconnect
                )
              )
            end
          end
        end

        def analyze_detail(link)

          detail = []

          page = re_acquire_when_failed(link, {})

          if page.at('h2[@class="deleted-item-name"]')

            return detail
          end

          detail.push(
            link.match(/jp\/(\w+)\//)[1]
          )

          detail.push(
            page.at('h2[@class="item-name"]')
                .text
          )

          detail.push(link)

          detail.push(
            page.at('div[@class="item-description f14"]')
                .text
          )

          detail.push(
            page.at('img[@class="owl-lazy"]')
                .attributes['data-src']
                .value
          )

          page.search('table[@class="item-detail-table"]/tr').each do |tr|

            if tr.at('th').text.match(/出品者/)

              detail.push(tr.at('td/a').text)
              detail.push(tr.at('td/a')[:href])

              tr.search('div[@class="item-user-ratings"]/span').each do |span|

                detail.push(span.text.to_i)
              end
            elsif tr.at('th').text.match(/カテゴリー/)

              detail.push(tr.at('td').text.strip.split(/\s+/))
            elsif tr.at('th').text.match(/商品の状態/)

              detail.push(tr.at('td').text)
            end
          end

          detail.push(
            page.at('span[@class="item-price bold"]')
                .text
                .gsub(/\D+/, '')
                .to_i
          )

          if page.at('div[@class="item-buy-btn disabled f18-24"]')

            detail.push('‐')
          else

            detail.push('○')
          end

          detail
        end
      
        def analyze

          items = Hash.new{|h,k|h[k] = Hash.new(&h.default_proc)} 
          detail = []

          @pages.each do |page|

            page.search('section[@class="items-box"]').each do |nd|

              sleep(10)
              detail = analyze_detail(nd.at('a')[:href])

              if detail.size > 0

                items[detail[0]] = {
                  item_id: detail[0],
                  name: detail[1],
                  link: detail[2],
                  description: detail[3][0..256],
                  img_uri: detail[4],
                  seller: detail[5],
                  seller_uri: detail[6],
                  rating_good: detail[7],
                  rating_normal: detail[8],
                  rating_bad: detail[9],
                  category: detail[10].join(' > '),
                  rank: detail[11],
                  price: detail[12],
                  stock: detail[13],
                  update_at: @configure[:system][:date]
                             .get_date_suffix,
                }

                pp items[detail[0]]
              end
            end
          end
      
          items
        end

      end ### class Mercari [END]

    end ### module FreeMarkets [END]

  end ### module Controller [END]

end ### module TWEyes [END]
