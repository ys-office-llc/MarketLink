module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module FreeMarkets ### 名前空間用なので機能を持たせるとバグるよ

      class Rakuma

        include Sys
        include TWEyes::Mixin::Protocols::HTTP::MechanizeX

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
            get(
              @configure[:controller][:free_markets][:rakuma][:self]
              .new_arrival_urls['self'],
              {}
            )
          )

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::FreeMarkets::Rakuma.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
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

        def set_user_agent(user_agent)

          @driver[:web][:mechanize].set_user_agent(user_agent)
        end

        def add_https_schema(url)

          url.sub(/^\/\//, 'https://')
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

              @driver[:web][:mechanize].re_initialize
              set_proxy
              set_user_agent(
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '+
                'AppleWebKit/537.36 (KHTML, like Gecko)'+
                ' Chrome/58.0.3029.110 Safari/537.36'
              )
              if page = @driver[:web][:mechanize].agent.get(
                          add_https_schema(url),
                          param
                        )

                return page
              else

                sleep(reconnect[:waiting_for])
              end
            end

            page
          rescue => e

            if e.message.match(/^404/)

              nil
            elsif reconnect[:limit] > reconnect[:current]

              pp e.class, e.message, reconnect
              sleep(reconnect[:waiting_for])
              reconnect[:current] = reconnect[:current].succ

              retry
            else

              pp e.class, e.message, e.backtrace, reconnect

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

        def stocked?(link)
        end
    
        def analyze_profile(link)

          profile = []

          sleep(10)

          if @configure[:system][:net]
             .get_host
             .match(/^[po]\d\d/)

            begin

              Retryable.retryable(
                on: [
                  Net::ReadTimeout,
                  EOFError,
                ],
                tries: 3
              ) do |retries, exception|

                @se.create_driver(:chrome)
                @se.connector[:driver].get(link)

                profile.push(
                  @se.connector[:driver]
                     .find_element(
                       :class_name,
                       'icon--full-smile'
                     ).text.to_i
                )
                profile.push(
                  @se.connector[:driver]
                     .find_element(
                       :class_name,
                       'icon--smile'
                     ).text.to_i
                )
                profile.push(
                  @se.connector[:driver]
                     .find_element(
                       :class_name,
                       'icon--sad'
                     ).text.to_i
                )
              end
            rescue TWEyes::Exception::Driver::Web::Selenium => e

              if e.message.match(
                   /^Display socket is taken but lock file is missing/
                 ) or
                 e.message.match(
                   /^unable to connect to chromedriver/
                 ) or
                 e.message.match(
                   /^Net::ReadTimeout/
                 )

                retry
              else

              end
            ensure

              @se.destroy_driver
              kill_chrome_family
            end
          else

            profile = [0, 0, 0]
          end

          profile
        end

        def kill_chrome_family

          ProcTable.ps do |p|

            if Process.euid === p.uid and
               p.comm.match(/Xvfb|chromedriver|chrome/)

              pp p.comm
              Process.kill('TERM', p.pid)

              if p.state.match(/^Z$/)

                ### Process.kill('KILL', p.ppid)
              end
            end
          end
        end

        def analyze_detail(link)

          detail   = []
          profile  = []
          category = []

          page = re_acquire_when_failed(link, {})

          if page.nil?

            return detail
          end

          detail.push(
            link.match(/\/item\/(\w+)/)[1]
          )

          detail.push(
            page.at('h1[@class="heading__title"]').text
          )

          detail.push(
            add_https_schema(
              link
            )
          )

          detail.push(
            page.at('p[@class="sticky__body description__pre"]')
                .text
          )

          detail.push(
            add_https_schema(
              page.at('ul[@class="gallery__thumb"]/li/img')[:src]
            )
          )

          profile = analyze_profile(
                      add_https_schema(
                        page.at('a[@class="user-summary mb10"]')[:href]
                      )
                    )

          page.search('ul[@class="crumb"]/li/a')
              .each do |anchor|

            category.push(
              anchor.text
            )
          end

          detail.push(page.at('dt[@class="user-summary__name"]').text)
          detail.push(page.at('a[@class="user-summary mb10"]')[:href])
          detail.push(profile[0])
          detail.push(profile[1])
          detail.push(profile[2])
          detail.push(category[2..-1])

          page.search('li[@class="table__row"]').each do |row|

            if row.at('b')
                  .text
                  .match(
                    /^商品の状態$/
                  )

              detail.push(row.at('span').text)
            end
          end

          detail.push(
            page.at('dd[@class="product__price__value"]')
                .text
                .gsub(/\D+/, '')
                .to_i
          )

          if page.at(
               'a[@class="btn--xl btn--disabled product__cart-btn bg--weak"]'
             )

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

            page.search('a[@class="wall__item__title"]').each do |nd|

              sleep(10)
              detail = analyze_detail(nd[:href])

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

      end ### class Kitamura [END]

    end ### module Stores [END]

  end ### module Controller [END]

end ### module TWEyes [END]
