require_relative '../lib/application'

module TWEyes
  class Ebay < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def manage_policy

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
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:ebay][:us][:self].permit?

          next
        end

        initializeX

        @controller[:ebay][:trading][:production]
        .get_user_preferences[:get_user_preferences]
        .each do |k, v|

          begin

            policy_type = v[:supported_seller_profile_profile_type]
                          .downcase
                          .sub(/_policy$/, '')
                          .to_sym

            v.update(
              v.map{|k2, v2|
                [
                  k2,
                  @builder[:mysql][:common]
                  .escape(Regexp.escape(v2))
                ]
              }.to_h
            )

            id = @builder[:mysql][:item][:ebay][:policy][policy_type][:self]
                 .get_id(
                   {
                     supported_seller_profile_profile_id:
                       v[:supported_seller_profile_profile_id],
                   }
                 )
  
            if id
  
              @builder[:mysql][:item][:ebay][:policy][policy_type][:self].update(id, v)
            else
  
              @builder[:mysql][:item][:ebay][:policy][policy_type][:self].insert(v)
            end
          rescue TWEyes::Exception::Controller::Ebay::Trading::Production,
                 TWEyes::Exception::API::Ebay::Trading::Production => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['item'],
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

    def set_ebay_auth_token

      result_ses = nil
      result_tok = nil

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @builder[:mysql][:account]
      .get_records
      .each do |account|

        begin

          @configure[:system][:user].account = account
          @logger[:user].open
          @api[:chatwork].initializeX
          initializeX

          unless @controller[:flow][:contract][:self].permit? and
                 @controller[:flow][:chatwork][:self].permit?

            next
          end

          if account['request_ebay_us_auth_token'] > 0

            result_ses = @controller[:ebay][:trading][:production]
                         .get_session_id

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:ebay][:us].get_session_id(
                result_ses.merge(
                  {
                    id: account['id'],
                    authentication_url: sprintf(
                      "https://signin.ebay.com/ws/eBayISAPI.dll?"+
                      "SignIn&RuName=%s&SessID=%s",
                      @configure[:api][:ebay][:trading][:production]
                      .get_ru_name,
                      result_ses[:session_id]
                    )
                  }
                )
              )
            )

            sleep(60)

            result_tok = @controller[:ebay][:trading][:production]
                         .fetch_token(result_ses[:session_id])

            @builder[:mysql][:account].update(
              account['id'],
              {
                ebay_us_auth_token: result_tok[:e_bay_auth_token],
                request_ebay_us_auth_token: 0,
              }
            )

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:ebay][:us].fetch_token(
                result_tok.merge(
                  {
                    id: account['id'],
                  }
                )
              )
            )
          end
        rescue TWEyes::Exception::Controller::Ebay::Trading::Production => e

          @builder[:mysql][:account].update(
            account['id'],
            {
              request_ebay_us_auth_token: 0,
            }
          )

          case e.method
          when :get_session_id
            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:ebay][:us].get_session_id(
                {
                  ack: 'failure',
                  errors: e.message,
                  id: account['id'],
                }
              )
            )
          when :fetch_token
            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:controller][:ebay][:us].fetch_token(
                {
                  ack: 'failure',
                  errors: e.message,
                  id: account['id'],
                }
              )
            )
          else

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['admin'],
              @formatter[:exception].handle(e))
          end
        end
      end
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
               @controller[:flow][:chatwork][:self].permit? and
               @controller[:flow][:ebay][:us][:self].permit?

          next
        end

        initializeX

        @builder[:mysql][:item][:ebay][:us]
        .get_records
        .each do |item|

          begin

            case item['item_ebay_us_state_id']
            when @configure[:builder][:mysql][:item]
                 .get_state['waiting']

              vacation_is_over(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_add_item']

              add_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['exhibit']

              on_vacation(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_relist_item']

              relist_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_revise_item']

              revise_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_end_item']

              end_item(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['payment']

=begin
        result = @controller[:ebay][:trading][:production]
                 .get_item_transactions(item)
complete_sale(item.symbolize_keys)
=end

              get_ems_tracking_number(item.symbolize_keys)
              get_item_transactions(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['shipment']

              delivery_details(item.symbolize_keys)
            end
          rescue TWEyes::Exception::Controller::Ebay => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['item'],
              @formatter[:exception].handle(e)
            )
            case e.method
            when :add_item, :relist_item
              @builder[:mysql][:item][:self].update(
                item['item_id'],
                {
                  ebay_us_state_id: @configure[:builder][:mysql][:item]
                                    .get_state['waiting']
                }
              )
            when :end_item, :revise_item
              @builder[:mysql][:item][:self].update(
                item['item_id'],
                {
                  ebay_us_state_id: @configure[:builder][:mysql][:item]
                                    .get_state['exhibit']
                }
              )
            end
          end

        end

      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    protected

    private

    def initializeX

      @api[:ebay][:trading][:production].initializeX
      @controller[:ebay][:trading][:production].initializeX(
        @api[:ebay][:trading][:production]
      )

      @api[:ebay][:business_policies_management][:production][:self]
      .initializeX

      @controller[:ebay][:business_policies_management][:production][:self]
      .initializeX(
        @api[:ebay][:business_policies_management][:production][:self]
      )
    end

    def vacation_is_over(item)

      if @configure[:system][:user]
         .account['vacation_end_date'].to_s.size > 0 and
         @configure[:system][:date]
         .get_today >  @configure[:system][:user]
                       .account['vacation_end_date'] and
         item['item_ebay_us_item_id'].to_s.size > 0

        @builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            do_repeat: PHP.serialize(['yahoo_auctions', 'ebay_us']),
          }
        )

        relist_item(item)
      end
    end

    def on_vacation(item)

      if @configure[:system][:user]
         .account['vacation_begin_date'].to_s.size > 0 and
         @configure[:system][:user]
         .account['vacation_end_date'].to_s.size > 0 and
         @configure[:system][:date].get_today.between?(
           @configure[:system][:user].account['vacation_begin_date'],
           @configure[:system][:user].account['vacation_end_date']
         )

        @builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            do_repeat: nil,
          }
        )

        end_item(item)
      end
    end

    def add_item(item)

      result = @controller[:ebay][:trading][:production].add_item(item)

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          ebay_us_item_id: result[:item_id],
          ebay_us_url: result[:listing_details_view_item_url],
          ebay_us_state_id: @configure[:builder][:mysql][:item]
                            .get_state['exhibit']
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:ebay][:us].add_item(
          item.merge(result)
        )
      )

      sleep(@configure[:controller][:ebay][:trading][:self].get_interval)
    end

    def relist_item(item)
      result = @controller[:ebay][:trading][:production].relist_item(item)

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          ebay_us_item_id: result[:item_id],
          ebay_us_url: result[:listing_details_view_item_url],
          ebay_us_state_id: @configure[:builder][:mysql][:item]
                            .get_state['exhibit']
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:ebay][:us].relist_item(
          item.merge(result)
        )
      )

      sleep(@configure[:controller][:ebay][:trading][:self].get_interval)
    end

    def revise_item(item)
      result = @controller[:ebay][:trading][:production].revise_item(item)

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          ebay_us_state_id: @configure[:builder][:mysql][:item]
                            .get_state['exhibit']
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:ebay][:us].revise_item(
          item.merge(result)
        )
      )

      sleep(@configure[:controller][:ebay][:trading][:self].get_interval)
    end

    def end_item(item)

      result = @controller[:ebay][:trading][:production].end_item(item)

      @builder[:mysql][:item][:self].update(
        item['item_id'],
        {
          ebay_us_state_id: @configure[:builder][:mysql][:item]
                            .get_state['waiting'],
          ebay_us_time_left: nil,
          ebay_us_num_watch: nil,
        }
      )

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['item'],
        @formatter[:controller][:ebay][:us].end_item(item)
      )
      sleep(@configure[:controller][:ebay][:trading][:self].get_interval)
    end

    def get_item_transactions(item)

      result = {}

      if item['item_wrote_shipping_information'].zero? and
         item['item_ebay_us_sale_price'] > 0

        result = @controller[:ebay][:trading][:production]
                 .get_item_transactions(item)

        @builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            wrote_shipping_information: 1,
          }
        )

        item.store(:to, @api[:chatwork].to_reshape)
        item['item_ebay_us_sale_price'] = (item['item_ebay_us_sale_price'] * 100).to_yen
        item['shipping_address_street2'] = item['shipping_address_street2']
        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['item'],
          @formatter[:controller][:ebay][:us]
          .get_item_transactions(
            item.symbolize_keys.merge(result)
          )
        )
        if @configure[:system][:user]
           .account['chatwork_work_place_room1_id']
           .to_s
           .size > 0 and
           @configure[:system][:user]
           .account['chatwork_work_place_members']
           .to_s
           .size > 0

          @api[:chatwork].assign_tasks(
            @configure[:system][:user]
            .account['chatwork_work_place_room1_id'],
            @formatter[:controller][:ebay][:us]
            .get_item_transactions(
              item.symbolize_keys.merge(result)
            ),
            (@configure[:system][:date].get_today + 1).to_time.to_i,
            @configure[:system][:user].account['chatwork_work_place_members']
          )
        end
        sleep(
          @configure[:controller][:ebay][:trading][:self]
          .get_interval
        )
      end
    end

    def complete_sale(item)

      @controller[:ebay][:trading][:production].complete_sale(item)
    end

    def get_ems_tracking_number(item)

      transactions = {}

      begin

        transactions = @controller[:ebay][:trading][:production]
                         .get_item_transactions(item)
      rescue TWEyes::Exception::Controller::Ebay::Trading::Production => e
      end

      if item[:item_ems_tracking_number]
         .to_s
         .size
         .zero? and
         transactions[
           :shipment_tracking_details_shipment_tracking_number
         ].to_s.size > 0

        @builder[:mysql][:item][:self].update(
          item[:item_id],
          {
            ems_tracking_number:
              transactions[
                :shipment_tracking_details_shipment_tracking_number
              ],
          }
        )
      end
    end

    def delivery_details(item)

      transactions = {}
      details      = {}

      if item[:item_ebay_us_sale_price] > 0 and
         !item[:item_ems_delivery_history]
          .to_s
          .match(/^お届け済み$/) and
         item[:item_ems_tracking_number]
         .to_s
         .size > 0

          initialize_mechanize
          details = @controller[:japan_post][:ems][:self]
          .delivery_details(
            item[:item_ems_tracking_number]
          )

          @builder[:mysql][:item][:self].update(
            item[:item_id],
            details
          )
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

      @controller[:japan_post][:ems][:self].initializeX(
        @driver
      )
    end

  end ### class Ebay [END]
end
