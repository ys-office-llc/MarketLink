module TWEyes

  module Mixin ### この空間は以下はMix-in専用

    module Yahoo ### 名前空間用なので機能を持たせるとバグるよ

      module Auctions

        def php_unserialize(serialized_string)

          PHP.unserialize(

            if serialized_string.to_s.size.zero?

              'N;'
            else

              serialized_string
            end
          ).to_a.map{|i| i.force_encoding('UTF-8')}
        end

        def decide_other_id(categories)

          case categories
          when 'デジタルカメラ,コンパクトデジタルカメラ'

            '2084261657'
          when 'デジタルカメラ,デジタル一眼'

            '2084261641'
          when 'デジタルカメラ,ミラーレス一眼'

            '2084305451'
          when 'フィルムカメラ,レンジファインダー'

            '2084261690'
          when 'フィルムカメラ,一眼レフ,オートフォーカス'

            '2084044789'
          when 'フィルムカメラ,一眼レフ,マニュアルフォーカス'

            '2084044776'
          when 'レンズ,一眼カメラ用（オートフォーカス）'

            '2084261690'
          when 'レンズ,一眼カメラ用（マニュアルフォーカス）'

            '2084261700'
          when 'デジタルカメラ,バッテリー、充電器,一眼用'

            '2084261668'
          when 'デジタルカメラ,バッテリー、充電器,コンパクトデジタルカメラ用'

            '2084261681'
          when 'フィルムカメラ,大判、中判,中判'

            '2084204370'
          when 'フィルムカメラ,二眼レフ'

            '2084204387'
          when 'フィルムカメラ,その他'

            '23660'
          when 'ビデオカメラ,デジタルビデオカメラ,その他'

            '2084044928'
          when 'ビデオカメラ,アクセサリ,その他'

            '2084018950'
          when 'ビデオカメラ,その他'

            '23948'
          when 'レンズ,その他'

            '23716'
          when 'アクセサリー,ストロボ、照明,その他'

            '2084261981'
          when 'アクセサリー,フィルター,その他'

            '2084261993'
          when 'アクセサリー,三脚、一脚'

            '2084261967'
          when 'アクセサリー,その他'

            '23760'
          when '双眼鏡,その他'

            '2084263456'
          when '望遠鏡,その他'

            '2084055382'
          end
        end

        def close_button(driver, logger)

          begin

            js = sprintf('document.querySelector("#js-ListingModal").className="CrossListingModal js-modal-wrap ExistingSellCrossListingModal";')
            driver.execute_script(js)
            logger[:user].info("ダイアログを強制的に消しました。")
          rescue

            logger[:user].info("ダイアログはありません。")
          end

          begin

            if driver.find_element(:id, 'js-ListingModalClose').displayed?

              driver.find_element(:id, 'js-ListingModalClose').click
              logger[:user].info('画面を閉じました')
            end
          rescue Selenium::WebDriver::Error::NoSuchElementError

            logger[:user].info('閉じる必要のある画面はありません')
          end
        end
      
        def search_category(driver, logger, product_name)

          driver.connector[:driver]
                .find_element(
                  :xpath,
                  '//*[@id="CategorySelect"]/ul/li[2]/a'
                ).click
          sleep(1)
          driver.connector[:driver]
                .find_element(:name, 'p')
                .send_keys product_name
          sleep(1)
          driver.connector[:driver]
                .find_element(:name, 'submit')
                .click
          sleep(5)
          driver.connector[:driver]
                .find_element(:id, 'search_category_index1')
                .click
          sleep(1)
          driver.connector[:driver]
                .find_element(:id, 'search_category_submit')
                .click
          sleep(1)
          logger[:user].info('商品名からカテゴリーを検索しました')
        end

        def select_category(driver, logger, categories, maker)

=begin
          driver.find_element(:id, 'acMdCateChange').click
          driver.find_element(:css, '.Tab__item:nth-child(1) .Tab__itemIn').click
=end

          driver.find_element(:link_text, '家電、AV、カメラ').click
          sleep(1)
          logger[:user].info(sprintf(
            "%s : 出品カテゴリー=[%s]",
            __method__,
            '家電、AV、カメラ'
          ))

          driver.find_element(:link_text, 'カメラ、光学機器').click
          sleep(1)
          logger[:user].info(sprintf(
            "%s : 出品カテゴリー=[%s]",
            __method__,
            'カメラ、光学機器'
          ))

          if categories.nil?

            raise 'ヤフオクのカテゴリー指定がされていません'
          end

          categories.split(',').each do |e|

            if e.match('その他')

              driver.find_element(
                :xpath,
                sprintf("//li[@id='%s']//a[.='その他']",
                  decide_other_id(categories)
                )
              ).click
              sleep(1)
            else

              driver.find_element(:link_text, e).click
              sleep(1)
            end
            logger[:user].info(sprintf("出品カテゴリー=[%s]", e))
          end

          ###if 'アクセサリー,ストロボ、照明' == categories
          if categories.match('アクセサリー,ストロボ、照明')

            maker = maker + '用'
          end

=begin
          メーカーの指定がないカテゴリーの場合
=end
          case categories
          when 'アクセサリー,キャップ',
               'アクセサリー,ケース、バッグ,一眼カメラ用ケース',
               'アクセサリー,ケース、バッグ,コンパクトカメラ用ケース',
               'アクセサリー,ケース、バッグ,バッグ,ソフトバッグ',
               'アクセサリー,ケース、バッグ,バッグ,ハードケース',
               'アクセサリー,ケース、バッグ,バッグ,リュック、ザック',
               'アクセサリー,フード',
               'アクセサリー,フィルター,カラー',
               'アクセサリー,フィルター,クローズアップ',
               'アクセサリー,リモコン',
               'アクセサリー,防湿庫',
               'アクセサリー,露出計',
               'アクセサリー,レリーズ',
               'アクセサリー,その他',
               'フィルムカメラ,インスタント、ポラロイド',
               'アクセサリー,フィルター,クロス',
               'アクセサリー,フィルター,ステップアップリング',
               'アクセサリー,フィルター,ステップダウンリング',
               'アクセサリー,フィルター,ソフト、フォグ',
               'アクセサリー,フィルター,減光',
               'アクセサリー,フィルター,紫外線カット',
               'アクセサリー,フィルター,赤外線透過',
               'アクセサリー,フィルター,偏光',
               'アクセサリー,フィルター,保護',
               'アクセサリー,フィルター,その他',
               'アクセサリー,ミニスタジオ',
               'アクセサリー,リモコン',
               'アクセサリー,レリーズ',
               'アクセサリー,三脚、一脚,三脚',
               'アクセサリー,三脚、一脚,雲台、プレート',
               'アクセサリー,三脚、一脚,一脚',
               'アクセサリー,三脚、一脚,クランプ',
               'アクセサリー,三脚、一脚,その他',
               'アクセサリー,防湿庫',
               'アクセサリー,露出計',
               'アクセサリー,暗室関連用品',
               'アクセサリー,その他',
               'フィルム',
               '説明書',
               'カタログ',
               '顕微鏡',
               '双眼鏡,オリンパス',
               'フィルムカメラ,コンパクトカメラ',
               'フィルムカメラ,8ミリ',
               'フィルムカメラ,APS',
               'フィルムカメラ,大判、中判,大判',
               'フィルムカメラ,その他',
               'ビデオカメラ,8ミリビデオカメラ',
               'ビデオカメラ,アクセサリ,その他',
               'ビデオカメラ,アクセサリ,レンズ、フィルター',
               'ビデオカメラ,バッテリー、充電器',
               'ビデオカメラ,プロ用、業務用',
               'ビデオカメラ,記録媒体',
               'ビデオカメラ,その他',
               'レンズ,大判、中判カメラ用',
               'レンズ,コンパクトカメラ用',
               'レンズ,その他'

            driver.find_element(:id, "updateCategory").click
            sleep(1)
            logger[:user].info("「このカテゴリに出品する」押しました")

            return
          end

          if maker.match('その他')

            driver.find_element(
              :xpath,
              sprintf("//li[@id='%s']//a[.='その他']",
                decide_other_id(categories)
              )
            ).click
            sleep(1)
            logger[:user].info(sprintf("出品カテゴリー=[%s]", maker))
          else

            driver.find_element(:link_text, maker).click
            sleep(1)
            logger[:user].info(sprintf("出品カテゴリー=[%s]", maker))
          end

          driver.find_element(:id, "updateCategory").click
          sleep(1)
          logger[:user].info("「このカテゴリに出品する」押しました")
        end
      
        def click_insertion_ok(driver, logger)

          begin

            sleep(5)
            driver.find_element(:id, "auc_insertion_ok").click
            sleep(1)
            logger[:user].info("出品条件に同意しました")
          rescue Selenium::WebDriver::Error::ElementNotVisibleError => e

            logger[:user].info(
              sprintf(
                "%s=[%s]",
                __method__,
                parse_phantomjs_error_message(e.message)
              )
            )
          end
        end
      
        def enter_title(driver, logger, title)

          title_byte130 = title[0..130]

          driver.find_element(:id, "fleaTitleForm").clear
          sleep(1)
          ### [0..130]で130バイト以降はカットする
          driver.find_element(:id, "fleaTitleForm").send_keys title_byte130
          sleep(1)
          logger[:user].info(sprintf("タイトル=[%s]", title_byte130))
        end
      
        def switch_to_html(driver, logger)

          driver.find_element(:id, "aucHTMLtag").click
          sleep(1)
          logger[:user].info("HTMLタグ入力へ切換しました")
        end
      
        def enter_html_description(driver, logger, description)

=begin
          iframe = driver.connector[:driver].find_element(:id, 'rteEditorComposition0')
          driver.connector[:driver].switch_to.frame(iframe)
          logger[:user].info("フレームに入りました。")

          driver.connector[:driver].find_element(:tag_name, "body").clear
          logger[:user].info("入力領域をクリアしました")
          sleep(1)

          driver.connector[:driver].find_element(:tag_name, "body")
                .send_keys description
          logger[:user].info(sprintf("出品ページ=[%s]", description))

          driver.connector[:driver].switch_to.default_content
          logger[:user].info("フレームから戻りました。")
=end

          driver.connector[:driver].find_element(:name, "Description_plain_work")
                .clear
          logger[:user].info("入力領域をクリアしました")
          sleep(1)

          ### printf("%s", description.gsub(/[\r\n]/, ''))
          js = sprintf('document.querySelector(".descriptionArea__textArea").value = \'%s\';', description.gsub(/[\r\n]/, ''))
          driver.connector[:driver].execute_script(js)
          logger[:user].info(sprintf("出品ページ=[%s]", description))
          sleep(1)
        end

        def select_auc_shipping_who(driver, logger)

          who_id = 2
          select_auc_shipping_who = Selenium::WebDriver::Support::Select.new(
                                      driver.connector[:driver].find_element(
                                        :id,
                                        'auc_shipping_who'
                                      )
                                    )
          case who_id
          when 1 then

            select_auc_shipping_who.select_by(:value, 'seller')
          when 2 then

            select_auc_shipping_who.select_by(:value, 'buyer')
          else

            select_auc_shipping_who.select_by(:value, 'buyer')
          end

          logger[:user].info(sprintf("%s 送料負担 >  落札者", __method__))
          sleep(1)
        end
      
        def select_exhibits_style(
              driver,
              logger,
              style_id,
              style,
              start_price,
              end_price
            )
      
          driver.find_element(:id, "auc_StartPrice_auction").clear
          sleep(1)
          driver.find_element(:id, "auc_StartPrice_auction").send_keys start_price
          sleep(1)

          if end_price > 0

            if /is-close\Z/ =~ driver.find_element(:css, "#price_auction > div.Overhead.js-toggleExpand > dl").attribute('class')

              driver.find_element(:css, ".Overhead:nth-child(2) .Overhead__title").click
            end

            driver.find_element(:id, "auc_BidOrBuyPrice_auction").clear
            driver.find_element(:id, "auc_BidOrBuyPrice_auction")
                  .send_keys end_price
          end
        end
      
        def decide_end_time(configure, end_time_id)

          current_time = configure[:system][:date].get_time

          to_afternoon = 13

          case current_time.strftime("%p")
          when 'AM'

            return end_time_id - (to_afternoon + current_time
                                               .strftime("%H")
                                               .to_i)
          when 'PM'

            return end_time_id - current_time.strftime("%I").to_i
          end
        end
      
        def select_end_time(
              configure,
              driver,
              logger,
              period_id,
              period,
              end_time_id,
              end_time
            )

          today_date          = configure[:system][:date].get_today
          adjust_end_time     = decide_end_time(configure, end_time_id)
          select_closing_ymd  = Selenium::WebDriver::Support::Select.new(
                                  driver.connector[:driver]
                                        .find_element(
                                          :id,
                                          'ClosingYMD'
                                        )
                                )
          select_closing_time = Selenium::WebDriver::Support::Select.new(
                                  driver.connector[:driver]
                                        .find_element(
                                          :id,
                                          'ClosingTime'
                                        )
                                )

          case period_id
          when 1
            ### 出品期間が1日の場合で、12時間未満の場合は0～マイナスとなる
            ### つまり、出品予約した日の翌日の日にちとなるため、
            ### today_date + 1になる
            if adjust_end_time <= 0

              closing_ymd = (today_date + period_id).strftime("%Y-%m-%d")

              select_closing_ymd.select_by(:value, closing_ymd)
=begin
              driver.connector[:driver].find_element(
                :xpath,
                "//div[@id='modFormReqrd']/fieldset/div/table/tbody/tr[6]/td/div/select[1]//option[2]"
              ).click
=end
              sleep(1)
              driver.connector[:driver].find_element(
                :xpath,
                "//select[@id='ClosingTime']//option[#{end_time_id}]"
              ).click
              sleep(1)

              logger[:user].info(sprintf(
                "開催期間=[%s], 終了日=[%s]",
                period,
                closing_ymd 
              ))

              logger[:user].info(sprintf(
                "終了時間=[%s],index=[%s]",
                end_time,
                end_time_id
              ))
            else
              closing_ymd = today_date.strftime("%Y-%m-%d")

pp closing_ymd
              begin
                select_closing_ymd.select_by(:value, closing_ymd)
              rescue Selenium::WebDriver::Error::NoSuchElementError => e
                closing_ymd = (today_date + 1).strftime("%Y-%m-%d")
                select_closing_ymd.select_by(:value, closing_ymd)
              end
              sleep(1)
              driver.connector[:driver].find_element(
                :xpath,
                "//select[@id='ClosingTime']//option[#{adjust_end_time}]"
              ).click
              sleep(1)
              logger[:user].info(sprintf(
                "開催期間=[%s], 終了日=[%s]",
                period,
                closing_ymd 
              ))

              logger[:user].info(sprintf(
                "終了時間=[%s],index=[%s]",
                end_time,
                end_time_id
              ))
            end
          when 2..7

            ### 再出品時で7日、かつ22時～23時台の出品時のバグ対応(2016-10-20)
            begin

              closing_ymd = (today_date + period_id).strftime("%Y-%m-%d")
              logger[:user].info(sprintf("開催期間=[%s]", period))
              select_closing_ymd.select_by(:value, closing_ymd)
              sleep(1)
              logger[:user].info(sprintf("終了日=[%s]", closing_ymd))
              select_closing_time.select_by(:index, end_time_id -1)
              sleep(1)
            ### 該当の時間帯が存在しない場合は例外を捕捉する
            rescue Selenium::WebDriver::Error::NoSuchElementError => e
              if Regexp.new(
                   /^cannot locate element with index:\s\d\d?$/
                 ) =~ e.message
                closing_ymd = (today_date + period_id - 1)
                              .strftime("%Y-%m-%d")
                select_closing_ymd.select_by(:value, closing_ymd)
                sleep(1)
                select_closing_time.select_by(:index, end_time_id - 1)
                sleep(1)
              end
            end
            logger[:user].info(sprintf("終了時間=[%s]", end_time))
          end

          logger[:user].info(sprintf("%s=[%s]", __method__, driver.capture))
        end
      
        def select_shipping_origin(driver, logger, pref_from_id, pref_from)

          driver.find_element(
            :xpath,
            "//select[@name='loc_cd']/option[#{pref_from_id.succ}]"
          ).click
          sleep(1)

          logger[:user].info(sprintf(
            "%s 商品発送元の地域=[%s]",
            __method__,
            pref_from
          ))
        end
      
        def select_tips_shipping(driver, logger)

          driver.find_element(:id, "auc_shipping_buyer").click
          sleep(1)
          logger[:user].info(sprintf(
            "%s 送料負担 >  落札者",
            __method__
          ))
        end

        def select_dec_carriage(driver, logger)

          driver.find_element(:id, "auc_shipping_fixed").click
          sleep(1)
          logger[:user].info(sprintf(
            "%s 出品時に送料を入力する",
            __method__
          ))
        end

        def select_yahuneko(driver, logger, item)

          yahuneko = php_unserialize(
                       item['item_condition_yahoo_auctions_yahuneko']
                     )

          if yahuneko.size > 0

            logger[:user].info(sprintf(
              "%s : [配送方法] > [ヤフネコ！パック] > [%s（%s, %s）]",
              __method__,
              yahuneko.join('／'),
              item['item_condition_yahoo_auctions_yahuneko_total_lwh'],
              item['item_condition_yahoo_auctions_yahuneko_weight']
            ))
          end

          if yahuneko.include?('ネコポス')

            unless driver.connector[:driver]
                         .find_element(
                           :name,
                           'is_yahuneko_nekoposu_ship'
                         ).selected?

              driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_nekoposu_ship'
                    ).click
              sleep(1)
            end
          else

            if driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_nekoposu_ship'
                    ).selected?

              driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_nekoposu_ship'
                    ).click
              sleep(1)
            end
          end

          if yahuneko.include?('宅急便コンパクト')

            unless driver.connector[:driver]
                         .find_element(
                           :name,
                           'is_yahuneko_taqbin_compact_ship'
                         ).selected?

              driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_taqbin_compact_ship'
                    ).click
              sleep(1)
            end
          else

            if driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_taqbin_compact_ship'
                    ).selected?

              driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_taqbin_compact_ship'
                    ).click
              sleep(1)
            end
          end

          if yahuneko.include?('宅急便')

            unless driver.connector[:driver]
                         .find_element(
                           :name,
                           'is_yahuneko_taqbin_ship'
                         ).selected?

              driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_taqbin_ship'
                    ).click
              sleep(1)
            end

            ship_delivery_size = Selenium::WebDriver::Support::Select.new(
              driver.connector[:driver].find_element(
                :class_name,
                'delivery_size'
              )
            )
            ship_delivery_size.select_by(
              :index,
              item['item_condition_yahoo_auctions_yahuneko_total_lwh_id']
            )
            sleep(1)
            ship_delivery_weight = Selenium::WebDriver::Support::Select.new(
              driver.connector[:driver].find_element(
                :class_name,
                'delivery_weight'
              )
            )
            ship_delivery_weight.select_by(
              :index,
              item['item_condition_yahoo_auctions_yahuneko_weight_id']
            )
            sleep(1)
          else

            if driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_taqbin_ship'
                    ).selected?

              driver.connector[:driver]
                    .find_element(
                      :name,
                      'is_yahuneko_taqbin_ship'
                    ).click
              sleep(1)
            end
          end
        end

        def select_hacoboon(driver, logger, item)

          hacoboon = php_unserialize(
                       item['item_condition_yahoo_auctions_hacoboon']
                     )

          if hacoboon.size > 0

            logger[:user].info(sprintf(
              "%s : [配送方法] > [%s]",
              __method__,
              hacoboon.join,
              item['item_condition_yahoo_auctions_hacoboon_total_lwh'],
              item['item_condition_yahoo_auctions_hacoboon_weight']
            ))
          end

          if hacoboon.include?('はこBOON')

            unless driver.connector[:driver]
                         .find_element(
                           :id,
                           'shipping_hacoboon'
                         ).selected?

              driver.connector[:driver]
                    .find_element(
                      :id,
                      'shipping_hacoboon'
                    ).click
              sleep(1)
            end

            hb_itemsize = Selenium::WebDriver::Support::Select.new(
              driver.connector[:driver].find_element(
                :name,
                'hb_itemsize'
              )
            )
            hb_itemsize.select_by(
              :index,
              item['item_condition_yahoo_auctions_hacoboon_total_lwh_id']
            )
            sleep(1)
            hb_itemweight = Selenium::WebDriver::Support::Select.new(
              driver.connector[:driver].find_element(
                :name,
                'hb_itemweight'
              )
            )
            hb_itemweight.select_by(
              :index,
              item['item_condition_yahoo_auctions_hacoboon_weight_id']
            )
            sleep(1)
          else

            if driver.connector[:driver]
                    .find_element(
                      :id,
                      'shipping_hacoboon'
                    ).selected?

              driver.connector[:driver]
                    .find_element(
                      :id,
                      'shipping_hacoboon'
                    ).click
              sleep(1)
            end
          end
        end

        def select_hacoboonmini(driver, logger, item)

          hacoboonmini = php_unserialize(
                           item['item_condition_yahoo_auctions_hacoboonmini']
                         )

          if hacoboonmini.size > 0

            logger[:user].info(sprintf(
              "%s : [配送方法] > [%s]",
              __method__,
              hacoboonmini.join
            ))
          end

          if hacoboonmini.include?('はこBOON mini')

            unless driver.connector[:driver]
                         .find_element(
                           :id,
                           'shipping_hacoboonmini'
                         ).selected?

              driver.connector[:driver]
                    .find_element(
                      :id,
                      'shipping_hacoboonmini'
                    ).click
            end
            selected_hacoboonmini_cvs_pref = Selenium::WebDriver::Support::Select.new(
              driver.connector[:driver].find_element(
                :id,
                'selected_hacoboonmini_cvs_pref'
              )
            )
            selected_hacoboonmini_cvs_pref.select_by(
              :index,
              item['item_condition_yahoo_auctions_hacoboonmini_shipment_source_store_id']
            )
            sleep(1)
          else

            if driver.connector[:driver]
                    .find_element(
                      :id,
                      'shipping_hacoboonmini'
                    ).selected?

              driver.connector[:driver]
                    .find_element(
                      :id,
                      'shipping_hacoboonmini'
                    ).click
              sleep(1)
            end
          end
        end

        def check_shipping_other(driver, logger)

          driver.find_element(:id, "shipping_other").click
          sleep(1)
          logger[:user].info(sprintf(
            "%s 出品時に送料を入力する",
            __method__
          ))
        end

        def enter_shipfee_longdist(driver, cost_longdist)

          driver.connector[:driver]
                .find_element(
                  :id,
                  'auc_hokkaidoshipping1'
                ).clear
          sleep(1)
          driver.connector[:driver]
                .find_element(
                  :id,
                  'auc_hokkaidoshipping1'
                ).send_keys cost_longdist
          sleep(1)
          driver.connector[:driver]
                .find_element(
                  :id,
                  'auc_okinawashipping1'
                ).clear
          sleep(1)
          driver.connector[:driver]
                .find_element(
                  :id,
                  'auc_okinawashipping1'
                ).send_keys cost_longdist
          sleep(1)
          driver.connector[:driver]
                .find_element(
                  :id,
                  'auc_isolatedislandshipping1'
                ).clear
          sleep(1)
          driver.connector[:driver]
                .find_element(
                  :id,
                  'auc_isolatedislandshipping1'
                ).send_keys cost_longdist
          sleep(1)
        end

        def enter_shipfee_input1(
              driver,
              logger,
              shipname_id,
              shipname,
              cost,
              additional
            )

          begin

            driver.connector[:driver].find_element(
              :id,
              'auc_shipname_block1'
            )
          rescue Selenium::WebDriver::Error::NoSuchElementError

            if shipname_id > 0 and
               driver.connector[:driver].find_element(
                 :id,
                 'auc_shipname_standard1'
               ).attribute('disabled').nil?

              driver.connector[:driver].find_element(
                :id,
                'auc_add_shipform'
              ).click
              driver.connector[:driver].find_element(
                :css,
                '#auc_shipname_block1 > .CheckExpand__label'
              ).click

              cost_longdist = cost + additional
              auc_shipname_standard1 = Selenium::WebDriver::Support::Select.new(
                driver.connector[:driver].find_element(
                  :id,
                  'auc_shipname_standard1'
                )
              )

              logger[:user].info(sprintf(
                "[配送方法] > [その他の配送サービス] > "+
                "[%s（送料=[%s], 追加費用=[%s]）]",
                shipname,
                cost,
                additional
              ))

              auc_shipname_standard1.select_by(
                :value,
                shipname
              )
              sleep(1)

              driver.connector[:driver]
                    .find_element(
                      :id,
                      'auc_shipname_uniform_fee_data1'
                    ).clear
              sleep(1)

              driver.connector[:driver]
                    .find_element(
                      :id,
                      'auc_shipname_uniform_fee_data1'
                    ).send_keys cost
              sleep(1)

              enter_shipfee_longdist(driver, cost_longdist)
            end
          end
        end

        def select_istatus(driver, logger, istatus_id, istatus)

          select_istatus = Selenium::WebDriver::Support::Select.new(
                             driver.connector[:driver]
                               .find_element(
                                 :name,
                                 'istatus'
                               )
                             )

          case istatus_id
          ### 新品、未使用
          when 1 then

            ### driver.connector[:driver].find_element(:id, 'istatus_new').click
            select_istatus.select_by(:value, 'new')
          ### 未使用に近い
          when 2 then

            ### driver.connector[:driver].find_element(:id, 'istatus_used10').click
            select_istatus.select_by(:value, 'used10')
          ### 目立った傷や汚れなし
          when 3 then

            ### driver.connector[:driver].find_element(:id, 'istatus_used20').click
            select_istatus.select_by(:value, 'used20')
          ### やや傷や汚れあり
          when 4 then

            ### driver.connector[:driver].find_element(:id, 'istatus_used40').click
            select_istatus.select_by(:value, 'used40')
          ### 傷や汚れあり
          when 5 then

            ### driver.connector[:driver].find_element(:id, 'istatus_used60').click
            select_istatus.select_by(:value, 'used60')
          ### 全体的に状態が悪い
          when 6 then

            ### driver.connector[:driver].find_element(:id, 'istatus_used80').click
            select_istatus.select_by(:value, 'used80')
          else

            ### driver.connector[:driver].find_element(:id, 'istatus_used10').click
            select_istatus.select_by(:value, 'used60')
          end

          logger[:user].info(sprintf("商品の状態=[%s]", istatus))
          sleep(1)
        end

        def enter_istatus_commnet(driver, logger, remarks)

          driver.connector[:driver]
                .find_element(:id, 'auc_istatus_comment')
                .clear

          if remarks.to_s.size > 0

            driver.connector[:driver]
                  .find_element(
                    :id,
                    'auc_istatus_comment')
                  .send_keys remarks
          end

          logger[:user].info(sprintf("商品の状態 > コメント=[%s]", remarks))
          sleep(1)
        end
      
        def select_transport_days(driver, logger, transport_days_id, transport_days)
          case transport_days_id
          when 1,3,8 then
            if not driver.find_element(:id, sprintf("shipping%s", transport_days_id)).selected?
              driver.find_element(:id, sprintf("shipping%s", transport_days_id)).click
              sleep(1)
              logger[:user].info(sprintf("発送日までの日数=[%s]", transport_days))
            end
          else
            driver.find_element(:id, "shipping1").click
            sleep(1)
            driver.find_element(:id, "auc_deselect_shipperiod").click
            sleep(1)
          end
        end
      
        def select_return_policy(driver, logger, accept_retuns_id, accept_retuns)
          case accept_retuns_id
          when 1 then driver.find_element(:xpath, '//*[@id="retpolicy_no"]').click
          when 2 then driver.find_element(:xpath, '//*[@id="retpolicy_yes"]').click
          else
            driver.find_element(:xpath, '//*[@id="retpolicy_no"]').click
          end
          sleep(1)
          logger[:user].info(sprintf("[%s]", accept_retuns))
        end
      
        def enter_return_policy_comment(driver, logger, comment)

          driver.find_element(:xpath, '//*[@id="auc_retpolicy_comment"]').clear
          driver.find_element(:xpath, '//*[@id="auc_retpolicy_comment"]').send_keys comment
          logger[:user].info(sprintf("返品コメント=[%s]", comment))
        end

        def upload_images_legacy(configure, driver, logger, item)

          images_path = configure[:system][:directory].get_images_path

          ###driver.connector[:driver].find_element(:id, "acMdBtnImageUp").click
=begin
          driver.connector[:driver].find_element(
            :xpath, '//*[@id="ImageUpArea"]/div[4]/a'
          ).click
=end
          driver.connector[:driver].find_element(
            :link_text, '画像・編集登録画面'
          ).click
          logger[:user].info("ヤフオク画像アップロードへ遷移します")

          ### (1..3).each do |i|
          (1..10).each do |i|

            if item[sprintf("item_image_%02d", i)]
               .to_s
               .size > 0

              driver.connector[:driver]
                    .find_element(
                      :name,
                      sprintf(
                        "ImageFile%d", i)).send_keys images_path + '/' + item[sprintf("item_image_%02d", i)]
              logger[:user].info(sprintf("画像%d=[%s]", i, images_path + '/' + item[sprintf("item_image_%02d", i)]))
            end
          end

          driver.connector[:driver].find_element(:id, "cnfm_btn").click
          logger[:user].info("写真アップロード画面コンファームします")

          sleep(5)

          driver.connector[:driver].find_element(:id, "back_btn").click
          logger[:user].info("写真アップロード画面から戻ります")
        end
      
        def upload_images(configure, driver, logger, item)

          images_path = configure[:system][:directory].get_images_path

          (1..10).each do |i|

            if item[sprintf("item_image_%02d", i)]
               .to_s
               .size > 0

              driver.connector[:driver].find_element(
                :id,
                'selectFile'
              ).send_keys images_path + '/' + item[
                                                sprintf("item_image_%02d", i)
                                              ]

              sleep(1)
              logger[:user].info(sprintf("画像%d=[%s]", i, images_path + '/' + item[sprintf("item_image_%02d", i)]))
            end
          end
        end
      
        def set_options(
              driver,
              logger,
              style_id,
              do_snipe,
              reserve_price,
              attention_price
            )
      
=begin
          if not driver.find_element(:id, "auc_minBidRating").selected?

            driver.find_element(:id, "auc_minBidRating").click
            sleep(1)
            logger[:user].info("総合評価で制限をかけました")
          end
      
          if not driver.find_element(:id, "auc_badRatingRatio").selected?

            driver.find_element(:id, "auc_badRatingRatio").click
            sleep(1)
            logger[:user].info("非常に悪い・悪い評価の割合で制限をかけました")
          end
      
          if not driver.find_element(:id, "auc_AutoExtension").selected?

            driver.find_element(:id, "bidCreditLimit").click
            sleep(1)
            logger[:user].info("入札者認証制限ありにしました")
          end

      
          if not driver.find_element(:id, "auc_AutoExtension").selected?

            driver.find_element(:id, "auc_AutoExtension").click
            sleep(1)
            logger[:user].info("自動延長ありにしました")
          end
=end

          ### 0 円の場合は値をクリアにする（style_id = 1オークション形式のみ）
          if do_snipe.to_s.size.zero? or
             PHP.unserialize(do_snipe).nil? and
             reserve_price > 0 and
             style_id === 1

            driver.find_element(
              :xpath,
              "//*[@name='ReservePrice']"
            ).clear
            sleep(1)
            driver.find_element(
              :xpath,
              "//*[@name='ReservePrice']"
            ).send_keys reserve_price
            sleep(1)
            logger[:user].info(sprintf("最低落札価格=[%s]", reserve_price))
          elsif do_snipe.to_s.size.zero? or
                PHP.unserialize(do_snipe).nil? and
                reserve_price.zero? and
                style_id === 1

            driver.find_element(:xpath, "//*[@name='ReservePrice']").clear
            sleep(1)
            logger[:user].info(sprintf("最低落札価格=[%s]", reserve_price))
          end
      
          if attention_price > 0
            driver.find_element(:name, "featuredAmount").clear
            sleep(1)
            driver.find_element(:name, "featuredAmount").send_keys attention_price
            sleep(1)
            logger[:user].info(sprintf("注目のオークション=[%s]", attention_price))
          end
        end
      
        def click_confirm(driver, logger)

          driver.find_element(:css, ".Button--proceed").click
          sleep(1)
          logger[:user].info("確認ボタンを押下しました")
        end
      
        def click_exhibit(driver, logger)

          driver.find_element(:id, 'auc_preview_submit_up').click
          sleep(1)
          logger[:user].info("「ガイドラインと以下の注意事項に同意して出品する」")
        end
      
        def after_close_button(driver, logger)

          begin

            if driver.find_element(:css, '#yaucSellItemCmplt > div.ExhibitFinishModal.js-exhibitFinishModal').displayed?
              js = sprintf('document.querySelector(".js-exhibitFinishModal").style.display="none";')
              driver.execute_script(js)
              logger[:user].info("display:none へ変更しました")
            end
=begin
            if driver.find_element(:id, 'js-ListingModalClose').displayed?

              driver.find_element(:class, 'ExhibitFinishModal__doRefuse js-exhibitFinishModal-button').click
              logger[:user].info("画面を閉じました")
              sleep(1)
            end
=end
          rescue Selenium::WebDriver::Error::NoSuchElementError,
                 Selenium::WebDriver::Error::ElementNotVisibleError

            logger[:user].info("閉じる必要のある画面はありません")
          end
        end

        def get_url(driver, logger)

          url = driver.find_element(:link_text, "このオークションの商品ページを見る").attribute('href')
          logger[:user].info(sprintf("商品URL=[%s]", url))
      
          return url
        end

        def get_id(logger, url)

          url.scan(/\/auction\/(\w+)$/)[0][0]
        end

        module API

          def get_error(response)
            doc = REXML::Document.new(response)
            if doc.elements['/Error/Message']
              raise(
                TWEyes::Mixin::Yahoo::Auctions::API,
                doc.elements['/Error/Message'].text
              )
            end
          end

          def get_result_set(response)
            doc = REXML::Document.new(response)
            result_set = doc.elements['ResultSet']

            {
              available: result_set.attributes["totalResultsAvailable"].to_i,
              returned:  result_set.attributes["totalResultsReturned"].to_i,
              position:  result_set.attributes["firstResultPosition"].to_i,
            }
          end

        end

        def parse_phantomjs_error_message(message)

          JSON.parse(message.sub(/ \([.A-z]+\)/, ''))['errorMessage']
        end

      end ### module Auctions [END]

    end ### module Yahoo [END]

  end ### Mixin [END]

end ### module TWEyes [END]
