module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module Stores ### 名前空間用なので機能を持たせるとバグるよ

      class CameraNoNaniwa

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

          page = get(
            @configure[:controller][:stores][:camera_no_naniwa][:self]
            .url_shop_goods_search,
            @configure[:controller][:stores][:camera_no_naniwa][:self]
            .get_new_arrival_parameters
          )

pp page.uri.to_s

          if page.at('span[@class="navipage_next_"]/a').nil?

            @pages.push(page)
          else

            while page.at('span[@class="navipage_next_"]/a')

              @pages.push(page)

              pp page.at('span[@class="navipage_next_"]/a')[:href]
              matches = page.at('span[@class="navipage_next_"]/a')[:href]
                            .match(/p=(\d+)&/)
 
              if @configure[:controller][:stores][:camera_no_naniwa][:self]
                 .get_new_arrival_page_limit < matches[1].to_i

                break
              end
 
              page = get(
                page.at('span[@class="navipage_next_"]/a')[:href],
                {}
              )
 
              sleep(
                @configure[:controller][:stores][:camera_no_naniwa][:self]
                .get_new_arrival_page_limit
              )
            end
          end

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::Stores::CameraNoNaniwa.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        protected

        private

        def analyze

          items = Hash.new{|h,k|h[k] = Hash.new(&h.default_proc)} 

          @pages.each do |page|

            page = Nokogiri::HTML.parse(page.body.toutf8)

            page.search('div[@class="StyleT_Item_ tile_item_"]').each do |e|

              pp @configure[:controller][:stores][:camera_no_naniwa][:self]
                 .get_url + e.at('div[@class="tile_elm_"]/a')[:href]

              ### http://cameranonaniwa.jp/shop/g/g2999991006826/
              if e.search('div[@class="icn_wrapper_"]/img').empty?

                next
              end

              case e.search('div[@class="icn_wrapper_"]/img')[0][:alt]
              when '新品'

                next
              when '中古', '委託'

                rank = e.search('div[@class="icn_wrapper_"]/img')[1][:alt]
                        .sub("＋", '+')
              else

                pp e.search('div[@class="icn_wrapper_"]/img')[0][:alt]
              end

              link = @configure[:controller][:stores][:camera_no_naniwa][:self]
                     .get_url + e.at('div[@class="tile_elm_"]/a')[:href]
              product = e.at('div[@class="tile_elm_"]/a')[:title]
              matches = product.match(/【中古】\((.+?)\)/)

              if matches 

                maker = matches[1]
              else

                maker = ''
              end

              accessories, remarks, price = get_comments(link)

              if e.at('div[@class="zaiko_"]')
                  .text
                  .to_s
                  .match(/在庫状況：○：/)

                stock = '〇'
              else

                stock = '‐'
              end

              ### 個別ページから取得すると値下げ価格も取得できるのでそうする。

              if price.nil?

                price = e.at('div[@class="price_"]')
                         .text
                         .strip
                         .gsub(/\D+/, '')
                         .to_i
              end

              ##id = e.at('div[@class="PLU_num_"]').text

              matches = e.at('div[@class="tile_elm_"]/a')[:href].match(/\d+/)

              if matches

                id = matches[0]

                items[id] = {
                  item_id: id,      # 商品コード
                  maker: maker,     # メーカー
                  name: product.sub(/【中古】/, ''),
                  rank: rank.sub("＋", '+'),  # ランク
                  price: price,      # 価格
                  accessories: accessories,
                  remarks: remarks,  # 備考
                  link: link,        # 商品詳細へのリンク
                  stock: stock,      # 在庫状況
                  update_at: @configure[:system][:date]
                             .get_date_suffix,
                }
                pp items[id]
              end

            end

          end

          items
        end

        def get_comments(url)

          page  = nil
          price = nil

          page = get(url, {})

          if page.at('span[@class="price_sale_"]')

            price = page.at('span[@class="price_sale_"]')
                        .text
                        .strip
                        .gsub(/\D+/, '')
                        .to_i
          end

          if page.at('div[@class="comment_area_"]/p')

            matches = page.at('div[@class="comment_area_"]/p')
                          .text
                          .match(/＜付属品＞(.+)＜商品状態＞(.+)/)
                          ### ＜保障＞の文字が化けて取得できないときあり
                          ###.match(/＜付属品＞(.+)＜商品状態＞(.+)＜保証＞/)
            if matches
              ### アクセサリ, 備考, 価格 の順番
              return matches[1].gsub('　', ' ')
                               .strip.to_zenkaku,
                     matches[2].gsub('　', ' ')
                               .strip.to_zenkaku,
                     price
             end
           end

          return ' ', ' ', price
        end

      end ### class CameraNoNaniwa [END]

    end ### module Stores [END]

  end ### module Controller [END]

end ### module TWEyes [END]
