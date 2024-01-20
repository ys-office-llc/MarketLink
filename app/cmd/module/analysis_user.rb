require_relative '../lib/application'
require_relative 'auth'

module TWEyes

  class AnalysisUser < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
      @auth = Auth.new
    end

    def report

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

        unless @controller[:flow][:contract][:self]
               .permit? and
               @controller[:flow][:contract][:market_screening][:self]
               .permit? and
               @controller[:flow][:chatwork][:self]
               .permit?

          next
        end

        @builder[:mysql][:research][:analysis][:self]
        .get_records
        .each do |param|

          initialize_mechanize
          initializeX('jp')

          notify = {}

          begin

            notify.merge!(get_sold_yahoo_auctions(param))
            notify.merge!(get_selling_yahoo_auctions(param))
            notify.store(
              :yahoo_auctions_numof_sold_m1,
               notify[:yahoo_auctions_numof_sold_m4] / 4
            )
            notify.store(
              :yahoo_auctions_index,
              (notify[:yahoo_auctions_numof_selling] /
               notify[:yahoo_auctions_numof_sold_m1].to_f).round(2)
            )

            notify.merge!(get_active_ebay_us(param))
            notify.merge!(get_sold_ebay_us(param))

            notify.store(
              :ebay_us_numof_sold_m1,
               notify[:ebay_us_numof_sold_m3] / 3
            )
            notify.store(
              :ebay_us_index,
              (notify[:ebay_us_numof_active] /
               notify[:ebay_us_numof_sold_m1].to_f).round(2)
            )

            begin

              if @controller[:flow][:amazon][:jp][:self].permit? and
                 param[:research_analysis_amazon_jp_asin].to_s.size.zero?

                asin = nil

                asin = @controller[:amazon][:mws][:products]
                       .get_asin_by_name(
                         param[:research_analysis_name]
                       )
                @builder[:mysql][:research][:analysis][:self].update(
                  param[:research_analysis_id],
                  {
                    amazon_jp_asin: asin,
                  }
                )
                param.store(
                  :research_analysis_amazon_jp_asin,
                  asin
                )
              end
            rescue TWEyes::Exception::Controller::Amazon::MWS::Products => e

              notify.store(:amazon_jp_asin, nil)
            end

            begin

              if @controller[:flow][:amazon][:jp][:self].permit? and
                 param[:research_analysis_amazon_jp_asin].size > 0

                notify.store(
                  :amazon_jp_asin,
                   param[:research_analysis_amazon_jp_asin]
                )
                notify.store(:amazon_jp_rankings,
                  @controller[:amazon][:mws][:products]
                  .get_sales_rankings(
                    'ASIN',
                    [
                      param[:research_analysis_amazon_jp_asin]
                    ]
                  )
                )

                notify.store(:amazon_jp_lowest_offer_listing_price,
                  @controller[:amazon][:mws][:products]
                  .get_lowest_offer_listing_price(
                    [
                      param[:research_analysis_amazon_jp_asin]
                    ]
                  )
                )
              else

                notify.store(:amazon_jp_rankings, 0)
              end
            rescue TWEyes::Exception::Controller::Amazon::MWS::Products => e

              notify.store(:amazon_jp_rankings, 0)
            end

            begin

              if @controller[:flow][:amazon][:jp][:self].permit? and
                 param[:research_analysis_amazon_jp_asin].size > 0

                notify.store(:amazon_jp_lowest_offer_listing_price,
                  @controller[:amazon][:mws][:products]
                  .get_lowest_offer_listing_price(
                    [
                      param[:research_analysis_amazon_jp_asin]
                    ]
                  )
                )
              else

                notify.store(:amazon_jp_lowest_offer_listing_price, 0)
              end
            rescue TWEyes::Exception::Controller::Amazon::MWS::Products => e

              notify.store(:amazon_jp_lowest_offer_listing_price, 0)
            end

            notify.store(
              :name,
              param[:research_analysis_name]
            )

            notify.store(
              :research_analysis_id,
              param[:research_analysis_id]
            )

            if param[:research_analysis_action]
               .to_s
               .match(/^(database|all)$/)

              id = @builder[:mysql][:research][:analysis][:archive][:self]
                   .todays_registered?(
                     {
                       research_analysis_id: param[:research_analysis_id]
                     }
                   )

              if id.zero?

                if @configure[:mvc][:potal][:self]
                   .resources_limit[
                     @configure[:system][:user].account['account_contract_id']
                   ] *
                   @configure[:mvc][:potal][:self]
                   .resources_factor_market_screening[
                     @configure[:system][:user].account['account_contract_id']
                   ] >= @builder[:mysql][:research][:analysis][:archive][:self]
                        .count_current_date

                  @builder[:mysql][:research][:analysis][:archive][:self]
                  .insert(notify)
                end
              else

                @builder[:mysql][:research][:analysis][:archive][:self]
                .update(id, notify)
              end
            end

            if param[:research_analysis_action]
               .to_s
               .match(/^(chatwork|all)$/)

              @api[:chatwork].push_message(
                @configure[:api][:chatwork]
                .room_indices['research']['analysis'],
                @formatter[:analysis][:user][:self]
                .report(notify.merge(param))
              )
            end 
          rescue TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize,
                 TWEyes::Exception::Controller::Ebay::Mechanize,
                 TWEyes::Exception::Controller::Ebay::Finding::Production,
                 TWEyes::Exception::Controller::Amazon::MWS::Products,
                 TWEyes::Exception::Database::MySQL => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork]
              .room_indices['research']['analysis'],
              @formatter[:exception].handle(e)
            )
          end
        end
      end
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    protected

    private

    def initializeX(marketplace)

      @controller[:ebay][:finding][:production][:self].initializeX(
        @api[:ebay][:finding][:production][:self]
      )
 
      if @controller[:flow][:amazon][:jp][:self].permit?

        @api[:amazon][:mws][:products].initializeX(marketplace)
        @api[:amazon][:mws][:products].connect
        @controller[:amazon][:mws][:products].initializeX(
          @api[:amazon][:mws][:products].connector,
          marketplace
        )
      end
    end

    def set_yahoo_auctions_query(param)

      query = {}

      query.store(
        :va,
        param[:research_analysis_yahoo_auctions_query_include_everything]
      )
      query.store(
        :vo,
        param[:research_analysis_yahoo_auctions_query_include_either]
      )
      query.store(
        :ve,
        param[:research_analysis_yahoo_auctions_query_not_include]
      )

      if param[:research_analysis_yahoo_auctions_category_id] > 0

        query.store(
          :auccat,
          param[:research_analysis_yahoo_auctions_category_id]
        )
      end

      if param[:research_analysis_yahoo_auctions_min_price] > 0

        query.store(
          :min,
          param[:research_analysis_yahoo_auctions_min_price]
        )
      end

      if param[:research_analysis_yahoo_auctions_max_price] > 0

        query.store(
          :max,
          param[:research_analysis_yahoo_auctions_max_price]
        )
      end

      query
    end

    def get_sold_yahoo_auctions(param)

      @controller[:yahoo][:auctions][:mechanize].get_sold(
        set_yahoo_auctions_query(param)
      )
    end

    def get_selling_yahoo_auctions(param)

      @controller[:yahoo][:auctions][:mechanize].get_selling(
        set_yahoo_auctions_query(param)
      )
    end

    def set_ebay_us_query(param)

      temporary = []
      query     = {}

      temporary.push(param[
        :research_analysis_ebay_us_query_include_everything
      ])

      if param[
        :research_analysis_ebay_us_query_include_either
      ].to_s.size > 0

        temporary.push("(#{param[
          :research_analysis_ebay_us_query_include_either
        ].split.join(', ')})")
      end

      if param[
           :research_analysis_ebay_us_query_not_include
         ].to_s.size > 0

         temporary.push(param[
           :research_analysis_ebay_us_query_not_include
         ].split.map{|i| i.sub(/^/, '-')}.join(' '))
       end

       query.store(
         :_nkw,
         temporary.join(' ').to_s.strip
       )

      if param[:research_analysis_ebay_us_min_price] > 0

        query.store(
          :_udlo,
          param[:research_analysis_ebay_us_min_price]
        )
      end

      if param[:research_analysis_ebay_us_max_price] > 0

        query.store(
          :_udhi,
          param[:research_analysis_ebay_us_max_price]
        )
      end

      if param[:research_analysis_ebay_us_category_id] > 0

        query.store(
          :_sacat,
          param[:research_analysis_ebay_us_category_id]
        )
      end

      query
    end

    def get_active_ebay_us(param)

      @controller[:ebay][:mechanize][:self].get_active(
        set_ebay_us_query(param)
      )
    end

    def get_sold_ebay_us(param)

      @controller[:ebay][:mechanize][:self].get_sold(
        set_ebay_us_query(param)
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

    def initialize_mechanize

      set_proxy
      set_user_agent_alias
      @controller[:yahoo][:auctions][:mechanize].initializeX(
        @driver[:web][:mechanize],
        @logger
      )

      @controller[:ebay][:mechanize][:self].initializeX(
        @driver[:web][:mechanize],
        @logger
      )
    end

  end ### class Analysis [END]
end
