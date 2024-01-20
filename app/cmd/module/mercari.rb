require_relative '../lib/application'
require_relative 'auth'

module TWEyes

  class Mercari < Application

    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
      @auth = Auth.new
    end

    def purge_expired_cookies(type, period)

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s %s to start", self.class, __method__, type)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][type][:self]
               .permit?

          next
        end

        account[:account] = account["yahoo_#{type}_account"]
        account[:type_ja] = get_type_ja(type)

        begin

          if @builder[:mysql][:account].expired?(
               "yahoo_auctions_#{type}_cookies_set_datetime",
               period
             )

            @builder[:mysql][:account].update(
              account['id'],
              {
                "yahooapis_#{type}_appid": nil,
                "yahooapis_#{type}_secret": nil,
                "yahoo_auctions_#{type}_cookies_is_set": 0,
                "yahoo_auctions_#{type}_cookies_set_datetime": nil,
              }
            )
 
            account.store(:to, @api[:chatwork].to_reshape)
            account[:ack] = 'success'
            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:yahoo][:mechanize][:self]
              .purge_expired_cookies(account.symbolize_keys)
            )
          end

        rescue TWEyes::Exception::Database::MySQL => e

          account[:message] = e.message
          account[:ack]     = 'failure'
          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['admin'],
            @formatter[:controller][:yahoo][:mechanize][:self]
            .purge_expired_cookies(account.symbolize_keys)
          )
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def set_cookie(type)

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:chatwork][:self].permit?

          next
        end

        if @controller[:flow][:yahoo][:auctions][type][:self]
           .set_cookie_permit? and
           @configure[:system][:user]
           .account[
             sprintf(
               "yahoo_auctions_%s_request_captcha",
               type
             )
           ] > 0

          if type === :seller

            account[:type] = '販売'
          elsif type === :buyer

            account[:type] = '仕入'
          end

          begin

            initialize_mechanize
            account[:captcha_url] = @controller[:yahoo][:auctions][:mechanize]
                                    .get_captcha(type)

            account[:ack] = 'success'
            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:yahoo][:mechanize][:self]
              .get_captcha(account.symbolize_keys)
            )
          rescue TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize => e

            account[:message]  = e.message
            account[:account]  = account["yahoo_#{type}_account"]
            account[:password] = account["yahoo_#{type}_password"]
            account[:captcha]  = account["yahoo_auctions_#{type}_captcha"]
            account[:ack] = 'failure'
            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:yahoo][:mechanize][:self]
              .get_captcha(account.symbolize_keys)
            )
            @builder[:mysql][:account].update(
              account['id'],
              {
                "yahoo_auctions_#{type}_captcha": nil,
                "yahoo_auctions_#{type}_request_captcha": 0,
                "yahoo_auctions_#{type}_cookies_is_set": 0,
              }
            )

            next
          end
          sleep(90)
        end
      end

      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:chatwork][:self].permit?

          next
        end

        if @controller[:flow][:yahoo][:auctions][type][:self]
           .set_cookie_permit? and
           @configure[:system][:user]
           .account[
             sprintf(
               "yahoo_auctions_%s_request_captcha",
               type
             )
           ] > 0

          if type === :seller

            account[:type] = '販売'
          elsif type === :buyer

            account[:type] = '仕入'
          end

          begin

            @controller[:yahoo][:auctions][:mechanize]
            .set_cookie(type)
            account[:ack] = 'success'
            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:yahoo][:mechanize][:self]
              .set_cookie(account.symbolize_keys)
            )
            @builder[:mysql][:account].update(
              account['id'],
              {
                "yahoo_auctions_#{type}_cookies_is_set": 1,
                "yahoo_auctions_#{type}_cookies_set_datetime":
                  @configure[:system][:date].get_date_time_suffix,
              }
            )
          rescue TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize => e

            account[:message] = e.message
            account[:account]  = account["yahoo_#{type}_account"]
            account[:password] = account["yahoo_#{type}_password"]
            account[:captcha]  = account["yahoo_auctions_#{type}_captcha"]
            account[:ack] = 'failure'
            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:yahoo][:mechanize][:self]
              .set_cookie(account.symbolize_keys)
            )
            @builder[:mysql][:account].update(
              account['id'],
              {
                "yahoo_auctions_#{type}_captcha": nil,
                "yahoo_auctions_#{type}_request_captcha": 0,
                "yahoo_auctions_#{type}_cookies_is_set": 0,
              }
            )
          end
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def category_tree

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

=begin
      Parallel.each(
        @builder[:mysql][:account].get_records,
        in_process: @builder[:mysql][:account].get_records.size
      ) do |account|
=end
      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:market_screening][:self].permit? and
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][:buyer][:self].permit?

          next
        end

        @controller[:flow][:self].initializeX(@logger)
        @auth.operate(:buyer, account)

        @api[:yahoo][:auctions].initializeX(
          @builder[:mysql][:auth][:yahoo][:buyer]
        )
        @controller[:yahoo][:api][:auctions].initializeX(
          @api[:yahoo][:auctions], :buyer
        )

        begin

pp @controller[:yahoo][:api][:auctions].category_tree(0)

        rescue TWEyes::Exception::Controller::Yahoo::API::Auctions => e

          @logger[:system].crit(
            @formatter[:exception].handle(e)
          )
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def watch

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        auction_ids      = {}
        open_watch_lists = []

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:market_screening][:self].permit? and
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][:buyer][:self].permit?

          next
        end

        @controller[:flow][:self].initializeX(@logger)
        @auth.operate(:buyer, account)
        @api[:yahoo][:auctions].initializeX(
          @builder[:mysql][:auth][:yahoo][:buyer]
        )
        @controller[:yahoo][:api][:auctions].initializeX(
          @api[:yahoo][:auctions], :buyer
        )

        @builder[:mysql][:research][:watch][:list][:self]
        .get_records
        .each do |item|

          if item['research_watch_list_delete_request'] > 0

            @builder[:mysql][:research][:watch][:list][:self]
            .delete(item['research_watch_list_id'])

            begin

              @controller[:yahoo][:api][:auctions]
              .delete_watch_list(
                item['research_watch_list_auction_id']
              )
              profiling_apis(
                :buyer,
                __method__,
                'delete_watch_list',
                1
              )
            rescue TWEyes::Exception::Controller::Yahoo::API::Auctions => e
              
              if e.message.match('ウォッチリストから削除できませんでした。')
              end
            end
          end

          auction_ids.store(
            item['research_watch_list_auction_id'],
            item['research_watch_list_id']
          )

        end

        begin

          close_watch_list
          open_watch_lists = open_watch_list
        rescue TWEyes::Exception::Controller::Yahoo::Auctions,
               TWEyes::Exception::Controller::Yahoo::API::Auctions,
               TWEyes::Exception::Database::MySQL => e

          if e.message.match('オークションにアクセスできませんでした。')

            pp e.message
          elsif e.message.match('ログインしてください。')

            pp e.message
          else

            @api[:chatwork].push_message(
              @configure[:api][:chatwork]
              .room_indices['research']['watch_list'],
              @formatter[:exception].handle(e)
            )
          end
        end

        ### ウォッチリストに存在しなくなったものをデータベースから削除
        auction_ids.each do |k,v|

          unless open_watch_lists.include?(k)
            @builder[:mysql][:research][:watch][:list][:self]
            .delete(v)
          end

        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def query

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:market_screening][:self].permit? and
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][:buyer][:self].permit?

          next
        end

        @controller[:flow][:self].initializeX(@logger)
        @controller[:yahoo][:auctions][:self].initializeX(:buyer)
        @controller[:yahoo][:auctions][:self].set_cookies_jar(
          @driver[:web][:mechanize].cookies_jar
        )

        @builder[:mysql][:research][:watch][:list][:self]
        .get_records
        .each do |item|

          begin

            if @controller[:flow][:self]
               .interrupt(
                 {
                   research_watch_list_seller_id: item['research_watch_list_seller_id'],
                 }
               )

              next
            end

            question_to_the_seller(item)
          rescue TWEyes::Exception::Controller::Yahoo::Auctions,
                 TWEyes::Exception::Database::MySQL => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork]
              .room_indices['research']['watch_list'],
                @formatter[:exception].handle(e)
            )
          end
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def research

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:market_screening][:self].permit? and
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][:buyer][:self].permit?

          next
        end

        @controller[:flow][:self].initializeX(@logger)
        @auth.operate(:buyer, account)
        @api[:yahoo][:auctions].initializeX(
          @builder[:mysql][:auth][:yahoo][:buyer]
        )
        @controller[:yahoo][:api][:auctions].initializeX(
          @api[:yahoo][:auctions], :buyer
        )

        @builder[:mysql][:research][:yahoo][:auctions][:search][:self]
        .get_records
        .each do |param|

          begin

            search(param).each do |k,v|

              if param['research_yahoo_auctions_search_aucminbids'] > 0 and
                 param['research_yahoo_auctions_search_aucminbids'] > v[:bids].to_i

                next
              end

              if param['research_yahoo_auctions_search_aucmaxbids'] > 0 and
                 param['research_yahoo_auctions_search_aucmaxbids'] < v[:bids].to_i

                next
              end

              if param['research_yahoo_auctions_search_stockless']
                 .to_s
                 .match(/^enable$/)

                register_to_item_db(param, v)
              end

              if param['research_yahoo_auctions_search_seller_except']
                   .split(',')
                   .include?(v[:seller_id])

                next
              end

              remaining_seconds = (
                Time.parse(v[:end_time]) - @configure[:system][:date]
                                           .get_time
              ).to_i

              ### 商品詳細を取得するケース
              ### 自動延長「あり／なし」
              ### 最低落札価格「あり／なし」
              if param['research_yahoo_auctions_search_is_automatic_extension']
                 .to_s.size > 0 or
                 param['research_yahoo_auctions_search_reserved']
                 .to_s.size and
                 remaining_seconds.between?(0, 10800)

                auction_item = @controller[:yahoo][:api][:auctions]
                               .auction_item(v[:auction_id])
                profiling_apis(
                  :buyer,
                  __method__,
                  'auction_item',
                  1
                )

                if param['research_yahoo_auctions_search_is_automatic_extension']
                   .to_s.size > 0 and
                   param['research_yahoo_auctions_search_is_automatic_extension']
                   .to_s.match(auction_item[:is_automatic_extension]).nil?

                  next
                end

                if param['research_yahoo_auctions_search_reserved']
                   .to_s.size > 0 and
                   auction_item[:reserved].nil?

                  next
                end

                ### 既に通知済みのものは止める
                if param[
                     'research_yahoo_auctions_search_action'
                   ].to_s.size > 0 and
                   @controller[:flow][:self]
                   .interrupt(
                     {
                       auction_id: v[:auction_id]
                     }
                   )

                  next
                end
              else

                ### 既に通知済みのものは止める
                if param[
                     'research_yahoo_auctions_search_action'
                   ].to_s.size > 0 and
                   @controller[:flow][:self]
                   .interrupt(
                     {
                       auction_id: v[:auction_id]
                     }
                   )

                  next
                end
              end

              begin

                if param['research_yahoo_auctions_search_chatwork_to']
                   .to_s
                   .match(/^grant$/)

                  v.store(:to, @api[:chatwork].to_reshape)
                else

                  v.store(:to, '')
                end

                v[:current_price] = v[:current_price].to_i.to_yen
                case param['research_yahoo_auctions_search_action']
                when 'watchlist'

                  @controller[:yahoo][:api][:auctions].watch_list(k)
                  profiling_apis(
                    :buyer,
                    __method__,
                    'watch_list',
                    1
                  )
                when 'chatwork'

                  @api[:chatwork].push_message(
                    @configure[:api][:chatwork]
                    .room_indices['research']['watch_list'],
                    @formatter[:controller][:yahoo][:auctions][:api]
                    .search(param.merge(v.merge(hashed_links(v[:title])))
                    )
                  )
                when 'all'

                  @controller[:yahoo][:api][:auctions].watch_list(k)
                  profiling_apis(
                    :buyer,
                    __method__,
                    'watch_list',
                    1
                  )
                  @api[:chatwork].push_message(
                    @configure[:api][:chatwork]
                    .room_indices['research']['watch_list'],
                    @formatter[:controller][:yahoo][:auctions][:api]
                    .search(param.merge(v.merge(hashed_links(v[:title])))
                    )
                  )
                when 'do_nothing'

                else
                end
              rescue TWEyes::Exception::Controller::Yahoo::API::Auctions => e

                pp e.method, e.message, e.backtrace
                case e.method
                when :watch_list

                  if e.message.match(/expired token/)

                    ### トークンの再設定
                    @auth.operate(:buyer, account)
                    @api[:yahoo][:auctions].initializeX(
                      @builder[:mysql][:auth][:yahoo][:buyer]
                    )
                    @controller[:yahoo][:api][:auctions].initializeX(
                      @api[:yahoo][:auctions], :buyer
                    )

                    retry
                  elsif e.message.match(
                          /オークションにアクセスできませんでした。/
                        )

                    pp e.message
                    retry
                  elsif e.message.match(
                          /ウォッチリストの登録数が制限を超えたため、これ以上登録できません。/
                        )

                    pp e.message
                    break
                  elsif e.message.match(
                          /Your Request was Forbidden/
                        )

                    pp e.message
                    break
                  end

                  v.store(:message, e.message)
                  @api[:chatwork].push_message(
                    @configure[:api][:chatwork]
                    .room_indices['research']['watch_list'],
                    @formatter[:controller][:yahoo][:auctions][:api].search(v))
                else
                  @api[:chatwork].push_message(
                    @configure[:api][:chatwork]
                    .room_indices['research']['watch_list'],
                    @formatter[:exception].handle(e))
                end
              end
            end
          rescue TWEyes::Exception::Controller::Yahoo::API::Auctions => e

            case e.message
            when /Your Request was Forbidden/
            end

            @api[:chatwork].push_message(
              @configure[:api][:chatwork]
              .room_indices['research']['watch_list'],
              @formatter[:exception].handle(e))
          end
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def snipe_auction_item

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      ### マルチプロセス(in_process)モードを有効にする
      Parallel.each(
        @builder[:mysql][:account].get_records,
        in_process: @builder[:mysql][:account].get_records.size
      ) do |account|
      ###@builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:market_screening][:self].permit? and
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][:buyer][:self].permit?

          next
        end

        @controller[:yahoo][:auctions][:self].initializeX(:buyer)
        @controller[:yahoo][:auctions][:self].set_cookies_jar(
          @driver[:web][:mechanize].cookies_jar
        )
        @auth.operate(:buyer, account)
        @api[:yahoo][:auctions].initializeX(
          @builder[:mysql][:auth][:yahoo][:buyer]
        )
        @controller[:yahoo][:api][:auctions].initializeX(
          @api[:yahoo][:auctions], :buyer
        )

        ### マルチスレッド(in_threads)モードを有効にする
        Parallel.each(
          @builder[:mysql][:bids][:self]
          .get_records,
          in_threads: @configure[:system][:resources].threads[
                        @configure[:system][:user]
                        .account['account_contract_id']
                      ]
        ) do |item|
        ###@builder[:mysql][:bids][:self].get_records.each do |item|

          auction_item      = nil
          end_time          = nil
          current_price     = nil
          remaining_seconds = nil

          spool_directory = sprintf(
            "%s/%s",
            @configure[:system][:directory].get_spool_path,
            __method__
          )

          FileUtils.mkdir_p(spool_directory)

          auction_id_path = sprintf(
            "%s/%s",
            spool_directory,
            item['research_watch_list_auction_id']
          )

          begin

            case item['bids_state_id']
            when @configure[:builder][:mysql][:bids]
                 .get_state['reserve_place_bids']

              if File.exists?(auction_id_path)

                end_time = Time.parse(File.read(auction_id_path))
              else

                auction_item = @controller[:yahoo][:api][:auctions]
                               .auction_item(
                                 item['research_watch_list_auction_id']
                               )

                profiling_apis(
                  :buyer,
                  __method__,
                  'auction_item',
                  1
                )

                File.open(auction_id_path, 'w') do |f|

                  f.printf("%s", auction_item[:end_time])

                end

                end_time = Time.parse(auction_item[:end_time])
              end

              remaining_seconds = (end_time - @configure[:system][:date]
                                   .get_time
                                  ).to_i

pp item['research_watch_list_auction_id']
pp item['research_watch_list_title']
pp end_time
pp @configure[:system][:date].get_time
pp remaining_seconds

              if remaining_seconds.between?(0, 40)

pp 'remaining_seconds.between?(0, 40)'

                auction_item = @controller[:yahoo][:api][:auctions]
                               .auction_item(
                                 item['research_watch_list_auction_id']
                               )

                current_price = auction_item[:price].to_i

pp current_price, item['bids_bids_price']

                if current_price < item['bids_bids_price']

                  place_bids(item.symbolize_keys.merge!(auction_item))
                else

                  ### 入札予約価格を上回っていたら削除
                  @builder[:mysql][:bids][:self].delete(item['bids_id'])
                end
              end
            when @configure[:builder][:mysql][:bids]
                 .get_state['bidding']

              end_time = Time.parse(File.read(auction_id_path))

              remaining_seconds = (end_time - @configure[:system][:date]
                                   .get_time
                                  ).to_i

pp item['research_watch_list_title']
pp end_time
pp @configure[:system][:date].get_time
pp remaining_seconds

              ### 終了時間を過ぎた
              if remaining_seconds < 0

                auction_item = @controller[:yahoo][:api][:auctions]
                               .auction_item(
                                 item['research_watch_list_auction_id']
                               )
                end_time = Time.parse(auction_item[:end_time])

pp auction_item[:status]

                case auction_item[:status]
                when 'open'
pp 'open'

                  ### 延長されているため、終了時間を書き換える
                  File.open(auction_id_path, 'w') do |f|

                    f.printf("%s", auction_item[:end_time])
                  end
                when 'cancelled'
pp 'cancelled'

                  ### 出品者がキャンセルしたため、削除
                  @builder[:mysql][:bids][:self].delete(item['bids_id'])
                  bids_finale(
                    item.symbolize_keys.merge!(auction_item),
                    @configure[:builder][:mysql][:bids].get_state['end']
                  )
                  purge_spool_snipe(auction_item[:auction_id], :auction_item)
                when 'closed'
pp 'closed'
                  ### マイ・オークション表示（落札分）にあれば落札
pp auction_item[:auction_id]
pp @controller[:yahoo][:api][:auctions].my_won_list
pp @controller[:yahoo][:api][:auctions].my_won_list.key?(auction_item[:auction_id])

                  if @controller[:yahoo][:api][:auctions]
                     .my_won_list
                     .has_key?(auction_item[:auction_id])

                    bids_finale(
                      item.symbolize_keys.merge!(auction_item),
                      @configure[:builder][:mysql][:bids].get_state['win']
                    )
                  else

                    ### なければ終了
                    bids_finale(
                      item.symbolize_keys.merge!(auction_item),
                      @configure[:builder][:mysql][:bids].get_state['end']
                    )
                  end
                  purge_spool_snipe(auction_item[:auction_id], :auction_item)
                else
                end
              end
            end
          rescue TWEyes::Exception::Database::MySQL,
                 TWEyes::Exception::Controller::Yahoo::Auctions,
                 TWEyes::Exception::Controller::Yahoo::API::Auctions => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork]
              .room_indices['research']['watch_list'],
              @formatter[:exception].handle(e))
            @driver[:web][:selenium].destroy_driver
          ensure
          end

        end
      end
    rescue Parallel::DeadWorker => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def snipe_end_item

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      ### マルチプロセス(in_process)モードを有効にする
      Parallel.each(
        @builder[:mysql][:account].get_records,
        ###in_process: @builder[:mysql][:account].get_records.size
        in_process: 5
      ) do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:merchandise_management][:self].permit? and
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:yahoo][:auctions][:seller][:self].permit?

          next
        end

        @controller[:yahoo][:auctions][:self].initializeX(:seller)
        @controller[:yahoo][:auctions][:self].set_cookies_jar(
          @driver[:web][:mechanize].cookies_jar
        )

        @auth.operate(:seller, account)
        @api[:yahoo][:auctions].initializeX(
          @builder[:mysql][:auth][:yahoo][:seller]
        )
        @controller[:yahoo][:api][:auctions].initializeX(
          @api[:yahoo][:auctions], :seller
        )

        ### マルチスレッド(in_threads)モードを有効にする
        Parallel.each(
          @builder[:mysql][:item][:yahoo][:auctions]
          .get_records,
          in_threads: @configure[:system][:resources].threads[
                        @configure[:system][:user]
                        .account['account_contract_id']
                      ]
        ) do |item|

          auction_item      = nil
          end_time          = nil
          price             = nil
          remaining_seconds = nil

          spool_directory = sprintf(
            "%s/%s",
            @configure[:system][:directory].get_spool_path,
            __method__
          )

          FileUtils.mkdir_p(spool_directory)

          begin

            case item['item_yahoo_auctions_state_id']
            when @configure[:builder][:mysql][:item]
                 .get_state['exhibit']

              ### チェックボックスを一度もON/OFFしていない場合
              ### if item['item_do_snipe'].nil?
              if item['item_do_snipe'].to_s.size.zero?

                next
              end

              ### チェックボックスを一度ON/OFFした場合は N; となり、
              ### それをPHP.unserializeするとnilになる
              if PHP.unserialize(item['item_do_snipe']).nil?

                next
              end

              unless PHP.unserialize(
                       item['item_do_snipe']
                     ).include?('yahoo_auctions')

                next
              end

pp item['item_yahoo_auctions_product_name']
pp item['item_yahoo_auctions_item_id']

              auction_id_path = sprintf(
                "%s/%s",
                spool_directory,
                item['item_yahoo_auctions_item_id']
              )

              if File.exists?(auction_id_path)

                end_time = Time.parse(File.read(auction_id_path))
              else

                auction_item = @controller[:yahoo][:api][:auctions]
                               .auction_item(
                                 item['item_yahoo_auctions_item_id']
                               )

                File.open(auction_id_path, 'w') do |f| 

                  f.printf("%s", auction_item[:end_time])

                end

                end_time = Time.parse(auction_item[:end_time])
              end

pp end_time
pp @configure[:system][:date].get_time

              remaining_seconds = (end_time - @configure[:system][:date]
                                   .get_time
                                  ).to_i

pp remaining_seconds

              if remaining_seconds.between?(0, 50)

pp 'remaining_seconds.between?(0, 50)'

                auction_item = @controller[:yahoo][:api][:auctions]
                               .auction_item(
                                 item['item_yahoo_auctions_item_id']
                               )

                end_time = Time.parse(auction_item[:end_time])
                price    = auction_item[:price].to_i

pp item['item_yahoo_auctions_item_id']
pp item['item_yahoo_auctions_product_name']
pp price, item['item_yahoo_auctions_reserve_price']
pp end_time, @configure[:system][:date].get_time

                remaining_seconds = (end_time - @configure[:system][:date]
                                     .get_time
                                    ).to_i

pp remaining_seconds

                if price < item['item_yahoo_auctions_reserve_price']

                  end_item(item)
                end
              elsif remaining_seconds < 0

                auction_item = @controller[:yahoo][:api][:auctions]
                               .auction_item(
                                 item['item_yahoo_auctions_item_id']
                               )
                end_time = Time.parse(auction_item[:end_time])

pp auction_item
pp remaining_seconds
pp end_time
pp auction_item[:status]

                if auction_item[:status] == 'open'

                  File.open(auction_id_path, 'w') do |f|

                    f.printf("%s", auction_item[:end_time])
                  end
                end
              end
            end
          rescue TWEyes::Exception::Controller::Yahoo::Auctions => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['item'],
              @formatter[:exception].handle(e))
            @driver[:web][:selenium].destroy_driver

          rescue TWEyes::Exception::Controller::Yahoo::API::Auctions => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['item'],
              @formatter[:exception].handle(e))
          ensure
          end

        end
      end
    rescue Parallel::DeadWorker => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def operate

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:contract][:merchandise_management][:self].permit? and
               @controller[:flow][:chatwork][:self].permit?

          next
        end

@controller[:free_markets][:mercari][:self].initializeX(
  @driver
)
@controller[:free_markets][:mercari][:self].sell
exit


        @builder[:mysql][:item][:yahoo][:auctions]
        .get_records
        .each do |item|

          begin
            case item['item_yahoo_auctions_state_id']
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_add_item']

              add_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_relist_item']

              resubmit_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_revise_item']

              end_item(item)
              resubmit_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_end_item']

              end_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['payment']

                get_shipping_information(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['shipment']

              if item['item_commented'].zero? and
                 item['item_yahoo_auctions_sale_price'] > 0 and
                 item['item_condition_yahoo_auctions_value_comment'].size > 0

                place_value_comment(item)
              end

            end
          rescue TWEyes::Exception::Controller::Yahoo::Auctions => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['item'],
              @formatter[:exception].handle(e))
            @driver[:web][:selenium].destroy_driver

            case e.method
            when :add_item, :resubmit_item

              @builder[:mysql][:item][:self].update(
                item['item_id'],
                {
                  yahoo_auctions_state_id:
                    @configure[:builder][:mysql][:item]
                    .get_state['waiting']
                }
              )
            when :end_item, :revise_item

              @builder[:mysql][:item][:self].update(
                item['item_id'],
                {
                  yahoo_auctions_state_id:
                    @configure[:builder][:mysql][:item]
                    .get_state['exhibit']
                }
              )
            end
          ensure
          end

        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    protected

    private

    def initializeX
    end

    def remove_file(path)

      if File.exists?(path) and
         File.ftype(path).to_sym === :file

        FileUtils.rm(path, {verbose: true})
      end
    end

    def get_as_save_image(url, spool_path, index)

      reconnect = {
        current: 0,
        limit: 3,
        waiting_for: 5,
      }

      initialize_mechanize

      @driver[:web][:mechanize]
      .agent
      .get(url)
      .save!(sprintf("%s/%02d.jpg", spool_path, index))
    rescue OpenSSL::SSL::SSLError => e

      case e.message
      when /^SSL_connect/

        pp e.class, e.message, reconnect

        if reconnect[:limit] > reconnect[:current]

          sleep(reconnect[:waiting_for])
          reconnect[:current] = reconnect[:current].succ

          retry
        else

          raise(
            e.class,
            sprintf(
              "Reconnection abandonment: %s, %s",
              e.message,
              reconnect
            )
          )
        end
      end
    end

    def stock_image_urls(details)

      image_urls = []

      details
      .select{|k, v| k.match(/^img_image/)}
      .each do |k, v|

        image_urls.push(v)
      end

      details[:description]
      .scan(/<\s*IMG\s+SRC=\"?(\S+)\"?/i)
      .each do |url|

=begin
        unless @configure[:yahoo][:auctions][:self]
               .stock_image_urls_except
               .include?(url[0]) or
=end
        unless details[:except_img_urls]
               .include?(url[0])

          image_urls.push(url[0])
        end
      end

      image_urls[0..11]
    end

    def create_thumbnail(spool_path, index)

      thumbnail_path = sprintf(
                         "%s/thumbnail_%02d.jpg",
                         spool_path,
                         index
                       )

      remove_file(thumbnail_path)

      original = Magick::ImageList.new(
                   sprintf(
                     "%s/%02d.jpg", spool_path,
                     index
                   )
                 )
      thumbnail = original.resize_to_fit(100, 100)
      thumbnail.write(thumbnail_path)
      FileUtils.chmod(
        0666,
        thumbnail_path,
        {
          verbose: true,
        }
      )
    end

    def update_image(id, index)

      @builder[:mysql][:item][:self].update(
        id,
        {
          "#{sprintf("image_%02d", index)}": sprintf(
             "%s/%s/%02d.jpg",
             @configure[:system][:user].account['id'],
             id,
             index
           ),
          "#{sprintf("thumbnail_image_%02d", index)}": sprintf(
            "%s/%s/thumbnail_%02d.jpg",
            @configure[:system][:user].account['id'],
            id,
            index
          ),
        }
      )
    end

    def get_as_save_images(details, id)

      spool_path = sprintf("%s/%s/%s",
                     @configure[:system][:directory].get_images_path,
                     @configure[:system][:user].account['id'],
                     id
                   )

      FileUtils.mkpath(
        spool_path,
        {
          mode: 0777,
          verbose: false
        }
      )

      stock_image_urls(details)
      .each_with_index do |url, i|

        get_as_save_image(url, spool_path, i.succ)
        create_thumbnail(spool_path, i.succ)
        update_image(id, i.succ)
      end
    end

    def get_my_pattern(param)

      my_pattern = {}

      @builder[:mysql][:setting][:item][:my_pattern][:self]
      .get_record_by_id(
        param['research_yahoo_auctions_search_my_pattern_id']
      ).each do |record|

        my_pattern = record
      end

      my_pattern
    end

    def register_to_item_db(param, item)

      details    = {}
      my_pattern = {}

      details = @controller[:yahoo][:api][:auctions]
                .auction_item(item[:auction_id])

      details.store(
        :except_img_urls,
        param['research_yahoo_auctions_search_except_img_urls']
        .to_s
        .strip
        .split(/\r\n/)
      )

      my_pattern = get_my_pattern(param)

      unless @builder[:mysql][:item][:self]
             .registered?(
               {
                 yahoo_auctions_stockless_item_id: details[:auction_id],
                 user_id: @configure[:system][:user]
                          .account['id'],
               }
             )

        id = @builder[:mysql][:item][:self].insert(
               {
                 yahoo_auctions_state_id: 0,
                 ebay_us_state_id: 1,
                 amazon_jp_state_id: 0,
                 product_name: details[:title],
                 yahoo_auctions_stockless_item_id: details[:auction_id],
                 yahoo_auctions_stockless_url: details[:auction_item_url],
                 cost_price: details[:bidorbuy].to_i,
                 my_pattern_id: param[
                   'research_yahoo_auctions_search_my_pattern_id'
                 ],
                 maker_id: my_pattern['maker_id'].to_i,
                 category_id: my_pattern['category_id'].to_i,
                 grade_id: my_pattern['grade_id'].to_i,
                 description_id: my_pattern['description_id'].to_i,
                 accessories_id: my_pattern['accessories_id'].to_i,
                 remarks_ja: my_pattern['remarks_ja'].to_s,
                 remarks_en: my_pattern['remarks_en'].to_s,
                 yahoo_auctions_template_id: my_pattern[
                   'yahoo_auctions_template_id'
                 ].to_i,
                 ebay_us_template_id: my_pattern['ebay_us_template_id'].to_i,
                 yahoo_auctions_condition_id: my_pattern[
                   'yahoo_auctions_condition_id'
                 ].to_i,
                 ebay_us_condition_id: my_pattern['ebay_us_condition_id'].to_i,
               }
             )

        get_as_save_images(details, id)
      end
    end

    def close_watch_list

      results = {}

      results = @controller[:yahoo][:api][:auctions]
               .close_watch_list

      profiling_apis(
        :buyer,
        __method__,
        __method__,
        results[:calls]
      )

      results[:items].each_value do |item|

        id = @builder[:mysql][:research][:watch][:list][:self]
             .get_id(
               {
                 auction_id: item[:auction_id],
                 user_id: @configure[:system][:user]
                          .account['id'],
               }
             )

        if id

          @builder[:mysql][:research][:watch][:list][:self]
          .delete(id)
        end

        @controller[:yahoo][:api][:auctions]
        .delete_watch_list(
          item[:auction_id]
        )
        profiling_apis(
          :buyer,
          __method__,
          __method__,
          1
        )
      end
    rescue TWEyes::Exception::Controller::Yahoo::API::Auctions => e

      if e.message.match(
           /^Your Request was Forbidden$/
         )

        @logger[:system].crit(
          @formatter[:exception].handle(e)
        )
        @api[:chatwork].push_message(
          @configure[:api][:chatwork]
          .room_indices['system'],
          @formatter[:exception].handle(e)
        )
        sleep(10)
      end
    end

    def open_watch_list

      lists   = []
      results = {}

      results = @controller[:yahoo][:api][:auctions]
               .open_watch_list

      profiling_apis(
        :buyer,
        __method__,
        __method__,
        results[:calls]
      )

      results[:items].each_value do |item|

        lists.push(item[:auction_id])

        ### 契約数を上回った場合はスキップ
        if @configure[:mvc][:potal][:self]
           .resources_limit[
             @configure[:system][:user].account['account_contract_id']
           ] *
           @configure[:mvc][:potal][:self]
           .resources_factor_market_screening[
             @configure[:system][:user].account['account_contract_id']
           ] <= @builder[:mysql][:research][:watch][:list][:self]
               .count

          next
        end

        unless @builder[:mysql][:research][:watch][:list][:self]
               .registered?(
                 {
                   auction_id: item[:auction_id],
                   user_id: @configure[:system][:user]
                            .account['id'],
                 }
               )

          item.merge!(
            @controller[:yahoo][:api][:auctions]
            .auction_item(item[:auction_id])
          )
          profiling_apis(
            :buyer,
            __method__,
            'auction_item',
            1
          )

          item[:title] = @builder[:mysql][:common]
                         .escape(
                           item[:title]
                         )

          if item[:description].to_s.size > 0
             item[:description] = @builder[:mysql][:common]
                                  .escape(
                                    CGI.escapeHTML(
                                      item[:description]
                                    )
                                  )
          end

          @builder[:mysql][:research][:watch][:list][:self]
          .insert(item)
        else

          item[:title] = @builder[:mysql][:common]
                         .escape(
                           item[:title]
                         )

          @builder[:mysql][:research][:watch][:list][:self]
          .update(
            @builder[:mysql][:research][:watch][:list][:self]
            .get_id(
              {
                auction_id: item[:auction_id],
                user_id: @configure[:system][:user]
                         .account['id'],
              }
            ),
            item
          )
        end
      end

      lists
    rescue TWEyes::Exception::Controller::Yahoo::API::Auctions => e

      if e.message.match(
           /^Your Request was Forbidden$/
         )

        @logger[:system].crit(
          @formatter[:exception].handle(e)
        )
        @api[:chatwork].push_message(
          @configure[:api][:chatwork]
          .room_indices['system'],
          @formatter[:exception].handle(e)
        )
      end

      lists
    end

    def search(param)

      results = {}

      results = @controller[:yahoo][:api][:auctions].search(param)

      profiling_apis(
        :buyer,
        __method__,
        __method__,
        results[:calls]
      )

      results[:items]
    end

    def add_item(item)

      set_proxy_static
      @driver[:web][:selenium].create_driver(:phantomjs)
      @controller[:yahoo][:auctions][:self].add_item(
        @driver[:web][:selenium],
        @logger,
        item
      )

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          yahoo_auctions_item_id: item['item_yahoo_auctions_item_id'],
          yahoo_auctions_url: item['item_yahoo_auctions_url'],
          yahoo_auctions_state_id: @configure[:builder][:mysql][:item]
                                     .get_state['exhibit']
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:yahoo][:auctions][:self].add_item(item)
      )

      @driver[:web][:selenium].destroy_driver
      sleep(@configure[:controller][:yahoo][:auctions][:self].get_interval)
    end

    def resubmit_item(item)

      set_proxy_static
      ###@driver[:web][:selenium].create_driver(:chrome)
      @driver[:web][:selenium].create_driver(:phantomjs)
      @controller[:yahoo][:auctions][:self].resubmit_item(
        @driver[:web][:selenium],
        @logger,
        item
      )

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          yahoo_auctions_state_id: @configure[:builder][:mysql][:item]
                                   .get_state['exhibit'],
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:yahoo][:auctions][:self].resubmit_item(item)
      )

      @driver[:web][:selenium].destroy_driver
      sleep(@configure[:controller][:yahoo][:auctions][:self].get_interval)
    end

    def end_item(item)

      auction_id_path = sprintf(
        "%s/snipe_%s/%s",
        @configure[:system][:directory].get_spool_path,
        __method__,
        item['item_yahoo_auctions_item_id']
      )

      if File.exists?(auction_id_path) and
         File.ftype(auction_id_path).to_sym === :file

        FileUtils.rm(auction_id_path)
      end

      set_proxy_static
      @driver[:web][:selenium].create_driver(:phantomjs)
      ###@driver[:web][:selenium].create_driver(:chrome)
      @controller[:yahoo][:auctions][:self].end_item(
        @driver[:web][:selenium],
        @logger,
        item
      )

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          yahoo_auctions_state_id: @configure[:builder][:mysql][:item]
                                   .get_state['waiting'],
          yahoo_auctions_time_left: nil,
          yahoo_auctions_num_watch: nil,
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:yahoo][:auctions][:self].end_item(item)
      )

      @driver[:web][:selenium].destroy_driver
      sleep(@configure[:controller][:yahoo][:auctions][:self].get_interval)
    end

    def purge_spool_snipe(auction_id, method)

      auction_id_path = sprintf(
        "%s/snipe_%s/%s",
        @configure[:system][:directory].get_spool_path,
        method,
        auction_id
      )

      if File.exists?(auction_id_path)

        FileUtils.rm(auction_id_path)
      end
    end

    def bids_finale(item, state_id)

pp __method__

pp state_id

      @builder[:mysql][:bids][:self].update(
        item[:bids_id],
        {
          state_id: state_id
        }
      )

      item.store(:state_id, state_id)

pp item[:state_id]

pp item

      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['research']['watch_list'],
        @formatter[:controller][:yahoo][:auctions][:self]
        .place_bids(item)
      )
    end

    def place_bids(item)

      @driver[:web][:selenium].create_driver(:phantomjs)
      @controller[:yahoo][:auctions][:self].place_bids(
        @driver[:web][:selenium],
        @logger,
        item
      )

      item.store(:price, item[:price].to_i)
      bids_finale(
        item,
        @configure[:builder][:mysql][:bids].get_state['bidding']
      )

      @driver[:web][:selenium].destroy_driver
    rescue TWEyes::Exception::Controller::Yahoo::Auctions::PlaceBids => e

pp 'rescue TWEyes::Exception::Controller::Yahoo::Auctions::PlaceBids'
pp e.class, e.message, e.backtrace, e.capture_url

      item.store(:message, e.message)
      item.store(:capture_url, e.capture_url)

      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['research']['watch_list'],
        @formatter[:controller][:yahoo][:auctions][:self]
        .place_bids(item)
      )
      @driver[:web][:selenium].destroy_driver
    end

    def place_value_comment(item)

      @driver[:web][:selenium].create_driver(:phantomjs)
      @controller[:yahoo][:auctions][:self].place_value_comment(
        @driver[:web][:selenium],
        @logger,
        item
      )

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          commented: 1
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:yahoo][:auctions][:self]
        .place_value_comment(item.symbolize_keys)
      )

#      @driver[:web][:selenium].destroy_driver
    rescue TWEyes::Exception::Controller::Yahoo::Auctions::PlaceValueComment => e

pp 'rescue TWEyes::Exception::Controller::Yahoo::Auctions::PlaceValueComment'
pp e.class, e.message, e.backtrace, e.capture_url

      item.store(:message, e.message)
      item.store(:capture_url, e.capture_url)

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:yahoo][:auctions][:self]
        .place_value_comment(item.symbolize_keys)
      )
#      @driver[:web][:selenium].destroy_driver
    end

    def get_shipping_information(item)

      shipping_information = {}

      if item['item_wrote_shipping_information'].zero? and
         item['item_yahoo_auctions_sale_price'] > 0

        @driver[:web][:selenium].create_driver(:phantomjs)
        shipping_information = @controller[:yahoo][:auctions][:self]
        .get_shipping_information(
          @driver[:web][:selenium],
          @logger,
          item
        )

        @builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            wrote_shipping_information: 1
          }
        )

        item.store(:to, @api[:chatwork].to_reshape)
        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['item'],
          @formatter[:controller][:yahoo][:auctions][:self]
          .get_shipping_information(
            item.symbolize_keys.merge(
              shipping_information
            )
          )
        )
      end
    rescue TWEyes::Exception::Controller::Yahoo::Auctions::GetShippingInformation => e

      item.store(:message, e.message)
      item.store(:capture_url, e.capture_url)
      item.store(:source_url, e.source_url)

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          wrote_shipping_information: 1
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:yahoo][:auctions][:self]
        .get_shipping_information(item.symbolize_keys)
      )
    end

    def question_to_the_seller(item)

      @driver[:web][:selenium].create_driver(:phantomjs)
      @controller[:yahoo][:auctions][:self]
      .question_to_the_seller(
        @driver[:web][:selenium],
        @logger,
        item
      )
      @driver[:web][:selenium].destroy_driver
    rescue TWEyes::Exception::Controller::Yahoo::Auctions::PlaceValueComment => e

      item.store(:message, e.message)
      item.store(:capture_url, e.capture_url)

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:yahoo][:auctions][:self]
        .place_value_comment(item.symbolize_keys)
      )
      @driver[:web][:selenium].destroy_driver
    end

    def set_proxy_static

      @driver[:web][:selenium].set_proxy(
        @configure[:system][:user]
        .account['proxy_ipv4addr_pair_static']
      )
    end
    def set_proxy

      @driver[:web][:mechanize].set_proxy_random(
        @driver[:web][:proxies][:mpp].fetch_proxies_full
      )
    end

    def set_user_agent_alias

      @driver[:web][:mechanize].set_user_agent_alias
    end

    def re_initialize

      @driver[:web][:mechanize].re_initialize
    end

    def initialize_mechanize

      re_initialize
      set_proxy
      set_user_agent_alias
      @controller[:yahoo][:auctions][:mechanize].initializeX(
        @driver[:web][:mechanize],
        @logger
      )
    end

    def escape(name)

      replaces = @configure[:yahoo][:auctions][:self].replaces

      #URI.escape(
        name.gsub(/[#{replaces.keys.join}]/, replaces)
            .to_ascii
            .strip
      #)
    end

    def hashed_links(name)

      {
        links_yahoo_auctions_search:
          sprintf("%s?%s", 
            @configure[:yahoo][:auctions][:self].url_search,
            @configure[:yahoo][:auctions][:self]
            .query_search.merge({p: escape(name)}).to_q
          ),
        links_yahoo_auctions_closedsearch:
          sprintf("%s?%s", 
            @configure[:yahoo][:auctions][:self].url_closedsearch,
            @configure[:yahoo][:auctions][:self]
            .query_closedsearch.merge({p: escape(name)}).to_q
          ),
        links_ebay_active:
          sprintf("%s?%s", 
            @configure[:ebay][:com][:self].url_active,
            @configure[:ebay][:com][:self]
            .query_active.merge({_nkw: escape(name)}).to_q
          ),
        links_ebay_sold:
          sprintf("%s?%s", 
            @configure[:ebay][:com][:self].url_sold,
            @configure[:ebay][:com][:self]
            .query_sold.merge({_nkw: escape(name)}).to_q
          ),
      }
    end

    def get_type_ja(type)

      if type === :seller

        '販売'
      elsif type === :buyer

        '仕入'
      end
    end

    def profiling_apis(type, method, api_name, api_calls)

      calls = @builder[:mysql][:profiling][:api][:yahoo][:auctions][type][:self]
              .get_today_calls_by_api(method, api_name)

      id = @builder[:mysql][:profiling][:api][:yahoo][:auctions][type][:self]
           .get_id(
             api_call_method: method,
             api_name: api_name,
             api_date_of_call: @configure[:system][:date].get_date_suffix,
           )

      if id

        @builder[:mysql][:profiling][:api][:yahoo][:auctions][type][:self]
        .update(
          id,
          {
            api_numof_calls: calls + api_calls,
          }
        )
      else

        @builder[:mysql][:profiling][:api][:yahoo][:auctions][type][:self]
        .insert(
          {
            api_call_method: method,
            api_name: api_name,
            api_numof_calls: api_calls,
            api_date_of_call: @configure[:system][:date].get_date_suffix,
          }
        )
      end
    end

  end ### class Auctions [END]
end
