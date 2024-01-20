module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module FreeMarkets ### 名前空間用なので機能を持たせるとバグるよ

      class Fril

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
              @configure[:controller][:free_markets][:fril][:self]
              .new_arrival_urls['self'],
              {}
            )
          )

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::FreeMarkets::Fril.new(
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

        def analyze_detail(link)

          detail   = []
          category = []

          page = get(link, {})

          if page.nil?

            return detail
          end

          ### 管理用ユニークIDを入れる
          detail.push(
            link.match(/\/(\w+)$/)[1]
          )

          ### 商品名を入れる
          detail.push(
            page.at('h1[@class="item__name visible-lg visible-md"]')
                .text
          )

          ### 商品へのリンクを入れる
          detail.push(link)

          ### 商品説明を入れる
          detail.push(
            page.at('div[@class="item__description"]')
                .text
          )

          ### 商品画像へのリンクを入れる
          detail.push(
            page.at('img[@class="sp-image"]')[:src]
          )

          page.search('table[@class="item__details"]/tr').each do |tr|

            if tr.at('th').text.match(/カテゴリ/)

              tr.search('a').each do |a|

                category.push(a.text)
              end

              detail.push(category)
            end

            if tr.at('th').text.match(/商品の状態/)

              detail.push(tr.at('td').text)
            end

            if tr.at('th').text.match(/出品者/)

              detail.push(tr.at('td/a').text.strip)
              detail.push(tr.at('td/a')[:href])

              detail.push(0)
              detail.push(0)
              detail.push(0)
            end
          end

          ### 出品者情報へのリンク
          detail.push(
            page.at('p[@class="header-shopinfo__shop-name"]').text
          )

          ### 出品者名
          detail.push(
            page.at('img[@class="user img-circle img-responsive"]')[:src]
          )

          ### 評価（評価仕様が他の販路と異なるためダミーで0を入れている
          detail.push(0)
          detail.push(0)
          detail.push(0)

          detail.push(
            page.at('span[@class="item__value"]')
                .text
                .gsub(/\D+/, '')
                .to_i
          )

          if page.at('span[@id="btn_sold"]')

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

            page.search('a[@itemprop="name"]').each do |nd|

              sleep(10)
              detail = analyze_detail(nd[:href])

              if detail.size > 0

                ### 商品状態を記載していないケースがあるので強制的に挿入した
                if detail.size === 13

                  detail.insert(6, '目立った傷や汚れなし')
                end

                items[detail[0]] = {
                  item_id: detail[0],
                  name: detail[1],
                  link: detail[2],
                  description: detail[3][0..256],
                  img_uri: detail[4],
                  category: detail[5].join(' > '),
                  rank: detail[6],
                  seller: detail[7],
                  seller_uri: detail[8],
                  rating_good: detail[9],
                  rating_normal: detail[10],
                  rating_bad: detail[11],
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

      end ### class Fril [END]

    end ### module FreeMarkets [END]

  end ### module Controller [END]

end ### module TWEyes [END]
