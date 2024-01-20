module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module Stores ### 名前空間用なので機能を持たせるとバグるよ

      class Kitamura

       include TWEyes::Mixin::Protocols::HTTP::MechanizeX

        public

        def initialize(configure)

          super()

          @configure = configure
          @agent     = nil
          @pages     = []
        end

        def initializeX(agent)

          @agent = agent
        end

        def new_arrival

          @pages.push(
            get(
              @configure[:controller][:stores][:kitamura][:self]
              .get_new_arrival_url+'/buy/list.do',
              {
                keyword: '',
                ob: 'ud-',
                lc: 100,
              }
            )
          )

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::Stores::Kitamura.new(
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

          detail = []
      
          page = get(link, {})

          page.search('tr td p').each do |e|

            detail.push(e.text.to_s.to_zenkaku)
          end

          detail
        rescue Mechanize::ResponseCodeError => e

          detail
        end
      
        def analyze

          items = Hash.new{|h,k|h[k] = Hash.new(&h.default_proc)} 
          maker = nil

          @pages.each do |page|

            if page.nil?

              next
            end

            page.search('li[@class="item-element"]').each do |e|

              name = e.at('dt[@class="item-name"]/a')
                      .text
                      .to_half_width_kana

              matches = name.match(/^(\S+)\s/)

              if matches

                maker = matches[1]
              end

              link = @configure[:controller][:stores][:kitamura][:self]
                     .get_new_arrival_url+
                     e.at('dt[@class="item-name"]/a')[:href]

              ### リンクからハッシュのキー用のIDを取得
              id = link.match(/ac=(\d+)/)[1]

              ### 個別商品ページから「付属品」と「備考」を取得する
              accessories, remarks = *analyze_detail(link)[3..4]

              price = e.at('dd[@class="plice"]/a/span')
                       .text
                       .to_s
                       .strip
                       .gsub(/\D+/, '')
                       .to_i

              shop = e.at('dd[@class="shop"]').text

              rank = e.at('dd[@class="state"]').text.sub("状態：", '')

              update = e.at('dd[@class="date"]').text.sub("更新日：", '')

              items[id] = {
                item_id: id,              # アイテム番号
                maker: maker,             # メーカー
                name: name,               # 商品名
                rank: rank,               # ランク (C,B,AB,A,AA)
                accessories: accessories, # 付属品
                remarks: remarks,         # 備考欄
                price: price,             # 商品価格
                link: link,               # 個別商品リンク
                shop: shop,               # 店舗名
                update_at: update,        # 更新日
                stock: stocked?(link),    # 在庫有無判断
              }
              pp items[id]
            end
          end
      
          items
        end

      end ### class Kitamura [END]

    end ### module Stores [END]

  end ### module Controller [END]

end ### module TWEyes [END]
