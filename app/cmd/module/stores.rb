require_relative '../lib/application'

module TWEyes
  class Stores < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])

      @stores = Hash.new { |h,k| h[k] = {} }
    end

    def research

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

###      collect(:champ_camera)
      collect(:kitamura)
      collect(:map_camera)
      collect(:camera_no_naniwa)
###      collect(:fujiya_camera)
###      collect(:hardoff)

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

        @builder[:mysql][:research][:new][:arrival][:self]
        .get_records
        .each do |param|

          begin

            notify(param, :kitamura)
            notify(param, :map_camera)
            notify(param, :champ_camera)
            notify(param, :camera_no_naniwa)
###            notify(param, :fujiya_camera)
            notify(param, :hardoff)
          rescue TWEyes::Exception::Controller::Stores => e

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

    def register_to_database(param, store, item)

      id = @builder[:mysql][:research][:stores][:self]
           .get_id(
             {
               user_id: param[:research_new_arrival_user_id],
               item_id: item[:item_id],
               name: @builder[:mysql][:common]
                      .escape(item[:name],
                     )
             }
           )

      if id

        if :map_camera === store and
           item[:stock].match('‐')

          item.delete(:price)
        end

        item.delete(:update_at)
        @builder[:mysql][:research][:stores][:self]
        .update(
          id,
          item
        )
      else
        item[:name] = @builder[:mysql][:common]
                      .escape(
                        item[:name]
                      )

        if item[:accessories].to_s.size > 0
           item[:accessories] = @builder[:mysql][:common]
                                .escape(
                                  CGI.escapeHTML(
                                    item[:accessories]
                                  )
                                )
        end

        if item[:remarks].to_s.size > 0
           item[:remarks] = @builder[:mysql][:common]
                            .escape(
                              CGI.escapeHTML(
                                item[:remarks]
                              )
                            )
        end

        @builder[:mysql][:research][:stores][:self]
        .insert(
          item.merge(
            research_new_arrival_id: param[:research_new_arrival_id],
            store: store,
          )
        )
      end
    end

    def create_hash(store, item)

      case store
      when :kitamura
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      when :camera_no_naniwa
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      when :map_camera
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      when :champ_camera
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      when :hardoff
        {
          item_id: item[:item_id],
          name:    item[:name],
          stock:   item[:stock],
        }
      when :fujiya_camera
        {
          name:    item[:name],
          rank:    item[:rank],
          price:   item[:price],
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

    def notify(param, store)

      @stores[store].each do |k,v|

        item = {}

        case param[:research_new_arrival_stock]
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
             :research_new_arrival_title_include_everything
           ].to_s.size > 0

          tokens  = [] 
          matches = []

          tokens = param[:research_new_arrival_title_include_everything]
                   .split(/\s+|\//)

          tokens.each do |token|

            token.sub!(/f([0-9][0-9.]?)/, '\1')

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
             :research_new_arrival_title_include_either
           ].to_s.size > 0

          matches = []

          param[:research_new_arrival_title_include_either]
          .split(/\s+|\//)
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
             :research_new_arrival_title_not_include
           ].to_s.size > 0

          matches = []

          param[:research_new_arrival_title_not_include]
          .split(/\s+|\//)
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
             :research_new_arrival_remarks_include_either
           ].to_s.size > 0

          matches = []

          param[:research_new_arrival_remarks_include_either]
          .split(/\s+|\//)
          .each do |token|

            if v[:remarks].to_s.match(/#{Regexp.escape(token)}/i)

              matches.push(sprintf("%s: %s", token, v[:remarks]))
            end
          end

          ### いずれかを含む＝全部マッチしない
          if matches.empty?

            next
          end
        end

        if param[
             :research_new_arrival_remarks_not_include
           ].to_s.size > 0

          matches = []

          param[:research_new_arrival_remarks_not_include]
          .split(/\s+|\//)
          .each do |token|

            if v[:remarks].to_s.match(/#{Regexp.escape(token)}/i)

              matches.push(sprintf("%s: %s", token, v[:remarks]))
            end
          end

          ### 含まない＝ひとつでも引っかかれば
          if matches.size > 0

            next
          end
        end

        if param[
             "research_new_arrival_rank_#{store}".to_sym
           ].to_s
            .size
            .zero? or
           param["research_new_arrival_rank_#{store}".to_sym].match("N;")

          next
        end

        unless PHP.unserialize(
                 param["research_new_arrival_rank_#{store}".to_sym]
               ).map{|i| i.force_encoding('UTF-8')}
                .include?(v[:rank])

          next
        end

        if param[:research_new_arrival_min_price] > 0 and
           param[:research_new_arrival_min_price] > v[:price]

          next
        end

        if param[:research_new_arrival_max_price] > 0 and
           param[:research_new_arrival_max_price] < v[:price]

          next
        end

        if @controller[:flow][:self].interrupt(create_hash(store, v))

          next
        end

        if param[:research_new_arrival_action]
           .to_s
           .match(/^(database|all)$/) and
           @configure[:mvc][:potal][:self]
           .resources_limit[
             @configure[:system][:user].account['account_contract_id']
           ] *
           @configure[:mvc][:potal][:self]
           .resources_factor_market_screening[
             @configure[:system][:user].account['account_contract_id']
           ] > @builder[:mysql][:research][:stores][:self]
               .count

          register_to_database(param, store, v)
        end

        item = v.dup
        item[:price] = item[:price].to_yen

        if param[:research_new_arrival_chatwork_to]
           .to_s
           .match(/^grant$/)

          item.store(:to, @api[:chatwork].to_reshape)
        else

          item.store(:to, '')
        end

        if param[:research_new_arrival_action]
           .to_s
           .match(/^(chatwork|all)$/)

          @api[:chatwork].push_message(
            @configure[:api][:chatwork].room_indices['research']['new_arrival'],
            @formatter[:controller][:stores][store][:self]
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
      @controller[:stores][:kitamura][:self].initializeX(
        @driver[:web][:mechanize].agent
      )

      @controller[:stores][:camera_no_naniwa][:self].initializeX(
        @driver[:web][:mechanize].agent
      )

      @controller[:stores][:map_camera][:self].initializeX(
        @driver
      )

      @controller[:stores][:champ_camera][:self].initializeX(
        @driver
      )

      @controller[:stores][:fujiya_camera][:self].initializeX(
        @driver
      )

      @controller[:stores][:hardoff][:self].initializeX(
        @driver
      )
    end

    def collect(store)

      begin

        initialize_mechanize
        @stores.store(
          store,
          @controller[:stores][store][:self].new_arrival
        )
      rescue TWEyes::Exception::Controller::Stores => e

        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['system'],
          @formatter[:exception].handle(e))

        sleep(30)

        retry
      end
    end

  end ### class Stores [END]
end
