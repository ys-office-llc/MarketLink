module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module Stores ### 名前空間用なので機能を持たせるとバグるよ

      class MapCamera

       include TWEyes::Mixin::Protocols::HTTP::MechanizeX

        public

        def initialize(configure)
          super()

          @configure = configure
          @driver    = nil
          @agent     = nil
          @pages     = []
        end

        def initializeX(driver)

          @driver = driver
          @agent  = @driver[:web][:mechanize].agent
        end

        def new_arrival

          uri  = nil
          page = nil

          uri = sprintf("%s/%s",
            @configure[:controller][:stores][:map_camera][:self]
            .get_new_arrival_url_search,
            sprintf(
              @configure[:controller][:stores][:map_camera][:self]
              .get_new_arrival_parameters,
              @configure[:system][:date].get_time.strftime('%y%m%d')
          ))

          page = get(uri, {})

          if page.at('a[@id="page_under_next"]').nil?

            @pages.push(page)
          else

            while page.at('a[@id="page_under_next"]')

              @pages.push(page)
              pp page.at('a[@id="page_under_next"]')[:href]
              page = @agent.get(page.at('a[@id="page_under_next"]')[:href])
              sleep(
               @configure[:controller][:stores][:map_camera][:self]
                .get_interval
              )
            end
          end

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::Stores::MapCamera.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        protected

        private

        def analyze

          items = Hash.new{|h,k|h[k] = Hash.new(&h.default_proc)} 
          price = nil

          @pages.each do |page|

            page.search('div[@class="itembox"]').each do |e|

              next if e.search('p[@class="icon clearfix"]/img')
                       .size
                       .zero?

              condition = 
                @configure[:controller][:stores][:map_camera][:self]
                .get_item_conditions[
                  e.search('p[@class="icon clearfix"]/img')
                   .pop[:src]
                   .match(/0([1-8])/)[1]
                   .to_i
                ]

              next if e.at('p[@class="txt"]/a').nil?

              accessories = e.at('p[@class="txt"]/a')[:title]

              link    = e.at('p[@class="txt"]/a')[:href]
              id      = link.match(/item\/(\d+)$/)[1]
              matches = e.at('p[@class="txt"]/a')
                         .text
                         .match(/^(.+?\))(.+)\s?\[ID:/)

              next if matches.nil?

              maker   = matches[1].to_ascii.gsub(/[()]/, '').strip.upcase
              product = matches[2].strip

              ### 値下げされている時に値下げ価格を取得できるように
              e.search('span[@class="price"]').each do |node|

                if node.children[0]

                  price = node.children[0].text.to_s.gsub(/\D+/, '').to_i
                end

                if node.children[1]

                  price = node.children[1].text.to_s.gsub(/\D+/, '').to_i
                end
              end

              txtred = e.at('span[@class="price"]/span[@class="txtred"]')

              if txtred and
                 txtred.text
                       .to_s
                       .match('SOLD OUT')

                stock = '‐'
              else

                stock = '〇'
              end

              ### アクセス制御のためか500エラーとなる。
              ### 毎回IPv4アドレスをを変えても変わらないので、
              ### 一時止めておく (2016-11-03)
              ### remarks = get_item_caption(link)
              remarks = ''

              items[id] = {
                item_id: id,
                maker: maker,
                name: maker + ' ' + product,
                rank: condition,
                accessories: accessories,
                price: price,
                remarks: remarks,
                link: link,
                stock: stock,
                update_at: @configure[:system][:date]
                           .get_date_suffix,
              }
              pp items[id]
            end
          end

          items
        end

        def get_item_caption(url)

          page        = nil
          itemcaption = ''

          sleep(
            @configure[:controller][:stores][:map_camera][:self]
            .get_interval
          )

          @driver[:web][:mechanize].set_proxy_random(
            @driver[:web][:proxies][:mpp].fetch_proxies_full
          )
          @driver[:web][:mechanize].set_user_agent_alias

          page = @driver[:web][:mechanize].agent.get(url)

          itemcaption = page.at('//div[@class="itemcaption"]')

          if itemcaption

            return itemcaption.text.gsub(/(\s)/, '')
          end

          itemcaption
        end

      end ### class MapCamera [END]

    end ### module Stores [END]

  end ### module Controller [END]

end ### module TWEyes [END]
