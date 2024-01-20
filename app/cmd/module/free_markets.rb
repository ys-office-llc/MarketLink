require_relative '../lib/application'

module TWEyes

  class FreeMarkets < Application

    public

    def initialize

      super

      @builder[:mysql][:common].connect(@database[:mysql])

      @markets = Hash.new { |h,k| h[k] = {} }
    end

    def research

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

###      collect(:mercari)
      collect(:rakuma)
###      collect(:fril)

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX
        @controller[:flow][:self].initializeX(@logger)

        unless @controller[:flow][:chatwork][:self].permit?

          next
        end

        @builder[:mysql][:research][:free_markets][:search][:self]
        .get_records
        .each do |param|

          begin

            notify(param, :fril)
            notify(param, :rakuma)
            notify(param, :mercari)
          rescue TWEyes::Exception::Controller::FreeMarkets => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork]
              .room_indices['research']['new_arrival'],
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

    protected

    private

    def initializeX
    end

    def register_to_database(param, market, item)

      id = @builder[:mysql][:research][:free_markets][:watch][:self]
           .get_id(
             {
               user_id: param[:research_free_markets_search_user_id],
               item_id: item[:item_id],
               name: item[:name],
             }
           )

      if id

        item.delete(:update_at)
        @builder[:mysql][:research][:free_markets][:watch][:self]
        .update(
          id,
          item
        )
      else
        item[:name] = @builder[:mysql][:common]
                      .escape(
                        item[:name]
                      )

        item[:seller] = @builder[:mysql][:common]
                        .escape(
                          item[:seller]
                        )

        if item[:description].to_s.size > 0
           item[:description] = @builder[:mysql][:common]
                                .escape(
                                  CGI.escapeHTML(
                                    item[:description]
                                  )
                                )
        end

        @builder[:mysql][:research][:free_markets][:watch][:self]
        .insert(
          item.merge(
            research_free_markets_search_id: param[:research_free_markets_search_id],
            market: market,
          )
        )
      end
    end

    def create_hash(market, item)

      case market
      when :fril
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      when :mercari
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      when :rakuma
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      end
    end

    def escape(name)

      replaces = @configure[:controller][:stores][:self].get_replaces

      URI.escape(name.gsub(
                        /[#{replaces.keys.join}]/,
                        replaces
                      ).to_ascii
                       .strip
      )
    end

    def hashed_links(name)

      links = @configure[:controller][:stores][:self].get_links

      {
        links_mnrate:
          sprintf(links['mnrate'], escape(name)),
        links_aucfan:
          sprintf(links['aucfan'], escape(name)),
        links_yahoo_auctions_past:
          sprintf(links['yahoo']['auctions']['past'], escape(name)),
        links_yahoo_auctions_current:
          sprintf(links['yahoo']['auctions']['current'], escape(name)),
        links_ebay_past:
          sprintf(links['ebay']['past'], escape(name)),
        links_ebay_current:
          sprintf(links['ebay']['current'], escape(name))
      }
    end

    def notify(param, market)

      @markets[market].each do |k,v|

pp v

        item = {}

        case param[:research_free_markets_search_stock]
        when /^existence$/

          unless v[:stock].match(/^〇$/)

            next
          end
        when /^not_existence$/

          unless v[:stock].match(/^‐$/)

            next
          end
        end

        if param[
             :research_free_markets_search_title_include_everything
           ].to_s.size > 0

          tokens  = [] 
          matches = []

          tokens = param[:research_free_markets_search_title_include_everything]
                   .split(/\s+/)

          tokens.each do |token|

            if v[:name].to_s.match(/#{Regexp.escape(token)}/i)

              matches.push(sprintf("%s: %s", token, v[:name]))
            end
          end

          ### 全てを含む
          unless tokens.size == matches.size

            next
          end
        end

        if param[
             :research_free_markets_search_title_include_either
           ].to_s.size > 0

          matches = []

          param[:research_free_markets_search_title_include_either]
          .split(/\s+/)
          .each do |token|

            if v[:name].to_s.match(/#{Regexp.escape(token)}/i)

              matches.push(sprintf("%s: %s", token, v[:name]))
            end
          end

          ### いずれかを含む＝全部マッチしない
          if matches.empty?

            next
          end
        end

        if param[
             :research_free_markets_search_title_not_include
           ].to_s.size > 0

          matches = []

          param[:research_free_markets_search_title_not_include]
          .split(/\s+/)
          .each do |token|

            if v[:name].to_s.match(/#{Regexp.escape(token)}/i)

              matches.push(sprintf("%s: %s", token, v[:name]))
            end
          end

          ### 含まない＝ひとつでも引っかかれば
          if matches.size > 0

            next
          end
        end

        if param[
             :research_free_markets_search_description_include_either
           ].to_s.size > 0

          matches = []

          param[:research_free_markets_search_description_include_either]
          .split(/\s+/)
          .each do |token|

            if v[:description].to_s.match(/#{Regexp.escape(token)}/i)

              matches.push(sprintf("%s: %s", token, v[:description]))
            end
          end

          ### いずれかを含む＝全部マッチしない
          if matches.empty?

            next
          end
        end

        if param[
             :research_free_markets_search_description_not_include
           ].to_s.size > 0

          matches = []

          param[:research_free_markets_search_description_not_include]
          .split(/\s+/)
          .each do |token|

            if v[:description].to_s.match(/#{Regexp.escape(token)}/i)

              matches.push(sprintf("%s: %s", token, v[:description]))
            end
          end

          ### 含まない＝ひとつでも引っかかれば
          if matches.size > 0

            next
          end
        end

        if param[
             "research_free_markets_search_rank_#{market}".to_sym
           ].to_s
            .size
            .zero? or
           param["research_free_markets_search_rank_#{market}".to_sym].match("N;")

          next
        end

        unless PHP.unserialize(
                 param["research_free_markets_search_rank_#{market}".to_sym]
               ).map{|i| i.force_encoding('UTF-8')}
                .include?(v[:rank])

          next
        end

        if param[:research_free_markets_search_min_price] > 0 and
           param[:research_free_markets_search_min_price] > v[:price]

          next
        end

        if param[:research_free_markets_search_max_price] > 0 and
           param[:research_free_markets_search_max_price] < v[:price]

          next
        end

        if param[:research_free_markets_search_min_rating_good] > 0 and
           param[:research_free_markets_search_min_rating_good] > v[:rating_good]

          next
        end

        if param[:research_free_markets_search_max_rating_good] > 0 and
           param[:research_free_markets_search_max_rating_good] < v[:rating_good]

          next
        end

        if param[:research_free_markets_search_min_rating_normal] > 0 and
           param[:research_free_markets_search_min_rating_normal] > v[:rating_normal]

          next
        end

        if param[:research_free_markets_search_max_rating_normal] > 0 and
           param[:research_free_markets_search_max_rating_normal] < v[:rating_normal]

          next
        end

        if param[:research_free_markets_search_seller_include]
           .to_s
           .size > 0 and
           !param[:research_free_markets_search_seller_include]
            .split(',')
            .include?(v[:seller])

          next
        end

        if param[:research_free_markets_search_seller_not_include]
           .to_s
           .size > 0 and
           param[:research_free_markets_search_seller_not_include]
           .split(',')
           .include?(v[:seller])

          next
        end

        if @controller[:flow][:self].interrupt(create_hash(market, v))

          next
        end

        if param[:research_free_markets_search_action]
           .to_s
           .match(/^(database|all)$/) and
           @configure[:mvc][:potal][:self]
           .resources_limit[
             @configure[:system][:user].account['account_contract_id']
           ] *
           @configure[:mvc][:potal][:self]
           .resources_factor_market_screening[
             @configure[:system][:user].account['account_contract_id']
           ] > @builder[:mysql][:research][:free_markets][:watch][:self]
               .count

          register_to_database(param, market, v)
        end

        item = v.dup
        item[:price] = item[:price].to_yen

        if param[:research_free_markets_search_chatwork_to]
           .to_s
           .match(/^grant$/)

          item.store(:to, @api[:chatwork].to_reshape)
        else

          item.store(:to, '')
        end

        if param[:research_free_markets_search_action]
           .to_s
           .match(/^(chatwork|all)$/)

          @api[:chatwork].push_message(
            @configure[:api][:chatwork]
            .room_indices['research']['free_markets'],
            @formatter[:controller][:free_markets][market][:self]
            .new_arrival(
              item.merge(param).merge(hashed_links(v[:name]))
            )
          )
        end
      end
    end

    def set_proxy

      @driver[:web][:mechanize].set_proxy_random(
        @driver[:web][:proxies][:mpp].fetch_proxies_full
      )
    end

    def set_user_agent_alias

      @driver[:web][:mechanize].set_user_agent_alias
    end

    def initialize_mechanize

      set_proxy
      set_user_agent_alias

      @controller[:free_markets][:fril][:self].initializeX(
        @driver
      )

      @controller[:free_markets][:mercari][:self].initializeX(
        @driver
      )

      @controller[:free_markets][:rakuma][:self].initializeX(
        @driver
      )
    end

    def collect(market)

      begin

        initialize_mechanize
        @markets.store(
          market,
          @controller[:free_markets][market][:self].new_arrival
        )
      rescue TWEyes::Exception::Controller::FreeMarkets => e

        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['system'],
          @formatter[:exception].handle(e))

        sleep(30)

        retry
      end
    end

  end ### class FreeMarkets [END]
end
