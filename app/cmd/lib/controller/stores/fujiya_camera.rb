module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module Stores ### 名前空間用なので機能を持たせるとバグるよ

      class FujiyaCamera

        public

        def initialize(configure)
          super()

          @configure = configure
          @agent     = nil
          @pages     = []
          @interval  = @configure[:controller][:stores][:fujiya_camera][:self]
                       .get_interval
          @home      = @configure[:controller][:stores][:fujiya_camera][:self]
                       .get_url
          @urls      = @configure[:controller][:stores][:fujiya_camera][:self]
                       .get_new_arrival_urls
        end

        def initializeX(driver)

          @agent = driver[:web][:mechanize].agent
        end

        def new_arrival

          ### 「次の50件」をダイジェスト化
          next50_digest = '60a6a392970005d0572e06e14c2c0b89'
      
          @urls.each do |url|
      
            page = @agent.get(sprintf("%s/shopbrand/%s", @home, url))

pp page.uri.to_s
      
            if page.search('li[@class="next"]/a').size.zero?
      
              @pages.push(page)
            else
      
              while Digest::MD5.hexdigest(
                      page.search('li[@class="next"]/a').pop.text
                    ) == next50_digest
      
                begin

                  @pages.push(page)
                  page =  @agent.get(
                            page
                            .search('li[@class="next"]/a')
                            .pop[:href]
                          )

                  sleep(@interval)
                rescue Mechanize::ResponseCodeError

                  sleep(@interval)
                end
              end
            end
          end

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::Stores::FujiyaCamera.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        protected

        private

        def analyze

          item  = nil
          items = Hash.new{|h,k|h[k] = Hash.new(&h.default_proc)} 

          @pages.each do |page|

             page.search(
               'div[@class="item_container well-sm"]'
             ).each do |node|

               item = node.at('div[@class="item_detail"]')
               cart = node.at('div[@class="cart_button"]')

               if cart and
                  cart.at('a[@class="empty_cart"]/img')

                 stock = '‐'
               else

                 stock = '〇'
               end

               if item

                 link    = @home+item.at('div[@id="itemname"]/a')[:href]
                 id      = link.match(/shopdetail\/(\d{12})\//)[1]
                 maker   = item.at('div[@id="itemname"]/a')
                               .text
                               .match(/^(\S+?)\s?\(?\s*/)[1]
                               .upcase
                 matches = item.at('div[@id="itemname"]/a')
                               .text
                               .gsub('＋', '+')
                               .sub(/\s*[A-C][AB]?→/, '')
                               .match(/(.+)\s(?:未使用品|[A-C][AB]?[+-]?|現状)ランク$/)
                 if matches

                   name = matches[1]
                 else

                   name = item.at('div[@id="itemname"]/a')
                 end

                 matches = item.at('div[@id="itemname"]/a')
                               .text
                               .to_s
                               .gsub('＋', '+')
                               .sub(/\s*[A-C][AB]?→/, '')
                               .match(/\s(未使用品|[A-C][AB]?[+-]?|現状)ランク$/)
                 if matches

                   rank = matches[1]
                 else

                   rank = nil
                 end

                 remarks = item.at(
                             'div[@class="item_note"]/font[@id="addinfo"]'
                           ).text
                            .to_s
                            .to_half_width_kana
                 price   = item.at(
                             'div[@class="item_price"]/font[@id="pricesize"]'
                           ).text
                            .gsub(/\D+/, '')
                            .to_i

                 items[id] = {
                   item_id: id,      # 商品コード
                   maker: maker,     # メーカー
                   name: name,       # 商品名 
                   rank: rank,       # ランク
                   price: price,     # 価格
                   accessories: '',  # 付属品はフジヤカメラはなし
                   remarks: remarks, # 備考
                   link: link,       # 商品詳細へのリンク
                   stock: stock,     # 在庫有無
                   update_at: @configure[:system][:date]
                              .get_date_suffix,
                 }
                 pp items[id]
               end
             end
           end

          items
        end

      end ### class FujiyaCamera [END]

    end ### module Stores [END]

  end ### module Controller [END]

end ### module TWEyes [END]
