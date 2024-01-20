require_relative '../lib/application'
require_relative 'auth'

module TWEyes

  class AnalysisSystem < Application
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

      initialize_mechanize
      initializeX

      @builder[:mysql][:system][:research][:analysis][:self]
        .get_records
        .each do |param|

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

          active = @controller[:ebay][:finding][:production][:self]
                   .find_items_by_keywords(param)

          sold = @controller[:ebay][:finding][:production][:self]
                 .find_completed_items(param)

          notify.merge!(
            {
              ebay_us_min_price: sold.get_min(:selling_status_current_price),
              ebay_us_avg_price: sold.get_avg(:selling_status_current_price),
              ebay_us_max_price: sold.get_max(:selling_status_current_price),
            }
          )

          @builder[:mysql][:system][:research][:analysis][:archive][:self]
          .insert(notify)

          @api[:chatwork].push_message(0,
            @formatter[:analysis][:system][:self].report(notify.merge(param))
          )
        rescue TWEyes::Exception::Controller::Yahoo::Auctions::Mechanize,
               TWEyes::Exception::Controller::Ebay::Mechanize,
               TWEyes::Exception::Controller::Ebay::Finding::Production,
               TWEyes::Exception::Database::MySQL => e
          @api[:chatwork].push_message(0,
            @formatter[:exception].handle(e))
        end

      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    protected

    private

    def initializeX
      @controller[:ebay][:finding][:production][:self].initializeX(
        @api[:ebay][:finding][:production][:self]
      )
    end

    def set_yahoo_auctions_query(param)
      query = {}

      query.store(
        :p,
        param[:system_research_analysis_yahoo_auctions_query]
      )
      if param[:system_research_analysis_yahoo_auctions_min_price] > 0

        query.store(
          :min,
          param[:system_research_analysis_yahoo_auctions_min_price]
        )
      end

      if param[:system_research_analysis_yahoo_auctions_max_price] > 0

        query.store(
          :max,
          param[:system_research_analysis_yahoo_auctions_max_price]
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
      query = {}

      query.store(
        :_nkw,
        param[:system_research_analysis_ebay_us_query]
      )
      if param[:system_research_analysis_ebay_us_min_price] > 0

        query.store(
          :_udlo,
          param[:system_research_analysis_ebay_us_min_price]
        )
      end

      if param[:system_research_analysis_ebay_us_max_price] > 0

        query.store(
          :_udhi,
          param[:system_research_analysis_ebay_us_max_price]
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
