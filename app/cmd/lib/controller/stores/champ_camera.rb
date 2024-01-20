module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module Stores ### 名前空間用なので機能を持たせるとバグるよ

      class ChampCamera

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

          page = nil

          page = get(
                   sprintf(
                     "%s/%s/used.php?",
                     @configure[:controller][:stores][:champ_camera][:self]
                     .new_arrival_url,
                     @configure[:controller][:stores][:champ_camera][:self]
                     .new_arrival_url_shop
                   ),
                   @configure[:controller][:stores][:champ_camera][:self]
                   .new_arrival_parameters
                 )

          pp page.uri.to_s

          if page.link_with(text: /次へ>>/).nil?

            @pages.push(page)
          else

            while page.link_with(text: /次へ>>/)

              @pages.push(page)

              Retryable.retryable(
                on: [
                  Net::HTTP::Persistent::Error,
                  Mechanize::ResponseCodeError,
                ],
                tries: 5
              ) do |retries, exception|

                page = page.link_with(text: /次へ>>/).click
              end

              if page.links.empty?

                break
              end

              if @configure[:controller][:stores][:champ_camera][:self]
                 .new_arrival_page_limit < page.link_with(text: /次へ>>/)
                                               .uri
                                               .to_s
                                               .match(/page=(\d+)/)[1]
                                               .to_i

                break
              end

              pp page.uri.to_s
              sleep(
                @configure[:controller][:stores][:champ_camera][:self]
                .interval
              )
            end
          end

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::Stores::ChampCamera.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        protected

        private

        def stocked?(link)

          page = nil

          page = get(link, {})

          if page and
             page.at('div[@id="headingLv1"]/h1') and
             page.at('div[@id="headingLv1"]/h1')
                 .text
                 .to_s
                 .match(
                   '該当する商品データがありません'
                 )

            '‐'
          else

            '〇'
          end
        rescue Mechanize::ResponseCodeError => e

          '‐'
        end
    
        def analyze_detail(link)

pp link

          detail      = []
          accessories = [] 
      
          page = get(link, {})

          if link.match(/jan=(\d+)&mode/)

            detail.push(
              link.match(/jan=(\d+)&mode/)[1]
            )
          end

          if page.at('td[@class="contain"]/font')
                 .to_s
                 .size > 0

            detail.push(
              page.at('td[@class="contain"]/font')
                  .text
                  .match(/([A-Z+]+)/)[1]
            )
          else

            detail.push(
              '取得できない'
            )
          end

          page.search('table[@id="item_list"]/tr/td').each do |nd|

            nd.search('li').each do |nd2|

              accessories.push(nd2.text)
            end
          end

          detail.push(accessories.join('　'))

pp detail

          detail
        rescue NoMethodError,
               Mechanize::ResponseCodeError,
               Net::HTTP::Persistent::Error => e

pp detail

          detail
        end
      
        def analyze

          items = Hash.new{|h,k|h[k] = Hash.new(&h.default_proc)} 

          @pages.each do |page|

            page.search('table[@id="hardstyle"]').each do |nd|

              if nd.at('td[@class="hardstyle39"]')

                maker = nd.at('td[@class="hardstyle39"]').text
              end

              if nd.at('td[@class="hardstyle7"]/a')

                link = sprintf(
                  "%s/%s/%s", 
                  @configure[:controller][:stores][:champ_camera][:self]
                  .new_arrival_url,
                  @configure[:controller][:stores][:champ_camera][:self]
                  .new_arrival_url_shop,
                  nd.at('td[@class="hardstyle7"]/a')[:href]
                )
                product_name = nd.at('td[@class="hardstyle7"]/a').text

                id, rank, accessories = *analyze_detail(link)
              end

              if nd.at('td[@class="hardpricein34"]')

                price = nd.at('td[@class="hardpricein34"]')
                          .text
                          .gsub(/\D+/, '')
                          .to_i
              end

              if nd.at('td[@class="hardexplain22"]')

                remarks = nd.at('td[@class="hardexplain22"]')
                            .text
              end

              if id and
                 maker and
                 product_name and
                 rank and
                 accessories and
                 remarks and
                 price and
                 link

                items[id] = {
                  item_id: id,              # アイテム番号
                  maker: maker,             # メーカー
                  name: maker + ' ' + product_name, # 商品名
                  rank: rank,               # ランク (C,B,AB,A,AA)
                  accessories: accessories, # 付属品
                  remarks: remarks,         # 備考欄
                  price: price,             # 商品価格
                  link: link,               # 個別商品リンク
                  update_at: @configure[:system][:date]
                             .get_date_suffix, # 更新日
                  stock: '※注意※店舗へメールにての在庫確認のため、在庫データの取得はできません',              # 在庫有無判断
                }
                pp items[id]
                sleep(
                  @configure[:controller][:stores][:champ_camera][:self]
                  .interval
                )
              end
            end
          end
      
          items
        end

      end ### class ChampCamera [END]

    end ### module Stores [END]

  end ### module Controller [END]

end ### module TWEyes [END]
