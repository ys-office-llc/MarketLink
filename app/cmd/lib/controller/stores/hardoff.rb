module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module Stores ### 名前空間用なので機能を持たせるとバグるよ

      class Hardoff

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

          uri = sprintf("%s/cate/30000003",
            @configure[:controller][:stores][:hardoff][:self]
            .get_new_arrival_url_search
          )

          page = get(uri, {})
          @pages.push(page)

          analyze
        rescue => e

          raise(TWEyes::Exception::Controller::Stores::Hardoff.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        protected

        private

        def analyze_detail(link)

          detail = {}
          specs  = []
          stock  = nil
          maker  = nil

pp link

          page = get(link, {})

          if page.at('p[@class="p-goodsDetail__webNumber"]')

            id = page.at('p[@class="p-goodsDetail__webNumber"]')
                     .text
                     .gsub(/\D+/, '')
          end

          if page.at('a[@class="p-goodsDetail__brandLink"]')

            maker = page.at('a[@class="p-goodsDetail__brandLink"]')
                        .text
          end

          category = page.at('p[@class="p-goodsDetail__category"]')
                         .text

          code = page.at('li[@class="p-goodsDetail__techSpecsItem"]')
                     .text
                     .split("：")[1]

          rank = page.at('ul[@class="p-goodsDetail__label"]/li').text.to_ascii

=begin
          matches = page.at('li[@class="p-goodsDetail__referenceItem"]')
                        .text
                        .strip
                        .match(/登録日：(\d{4})年(\d{2})月(\d{2})日/)

          if matches

            update_at = sprintf(
                          "%s/%s/%s",
                          matches[1],
                          matches[2],
                          matches[3]
                        )
          end
=end

          price = page.at('p[@class="p-goodsDetail__price"]')
                      .text
                      .gsub(/\D+/, '')
                      .to_i

          if page.at('li[@class="c-status__item c-status__item--soldout"]')

            stock = '‐' 
          else

            stock = '〇' 
          end

          if page.at('div[@class="p-goodsGuide__body"]/table')

            page.at('div[@class="p-goodsGuide__body"]/table').children.each do |e|

              if e.search('th').text.match(/^特徴・備考$/)

                specs.push(e.search('td').text)
              end
            end
          end

          detail = {
            id: id,
            maker: maker,
            category: category,
            code: code,
            rank: rank,
            price: price,
            stock: stock,
            remarks: specs.join(',').to_zenkaku,
            link: link,
            update_at: '',
          }

          detail
rescue NoMethodError
pp 'NoMethodError'
pp link
sleep(6000)

        rescue Mechanize::ResponseCodeError => e

          detail
        end
    
        def analyze

          items = Hash.new{|h,k|h[k] = Hash.new(&h.default_proc)} 
          detail = {}

          @pages.each do |page|

            page.search('div[@class="p-goods__item"]').each do |e|

              detail = analyze_detail(
                e.at('a[@class="p-goods__link"]')[:href]
              )

pp detail

              items[detail[:id]] = {
                item_id: detail[:id],
                maker: detail[:maker],
                name: detail[:maker].to_s + ' ' + detail[:code],
                rank: detail[:rank],
                accessories: nil,
                price: detail[:price],
                remarks: detail[:remarks],
                link: detail[:link],
                stock: detail[:stock],
                update_at: detail[:update_at],
              }
              pp items[detail[:id]]
            end
          end

          items
        end

      end ### class Hardoff [END]

    end ### module Stores [END]

  end ### module Controller [END]

end ### module TWEyes [END]
