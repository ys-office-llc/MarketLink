module TWEyes

  module Orchestrator ### 名前空間用なので機能を持たせるとバグるよ

    class Ebay < Member

      public

      def initialize(configure)
        super

        @participants = []
        @self         = get_class_suffix
        @parent       = @self.split('_')[0].to_sym
        @child        = @self.split('_')[1].to_sym

        @keys = {
          item:  sprintf("item_%s_item_id", @self),
          state: sprintf("item_%s_state_id", @self)
        }
      end

      def initializeX
      end

      protected

      class Us < Ebay

        public

        def initialize(configure)
          super
        end

        def initializeX
        end

        def conduct(item)

          if @@items[@parent][@child].size > 0

            switch(item)
          end
        rescue => e

          raise(
            TWEyes::Exception::Orchestrator::Ebay::Us.new(
              e.class,
              e.backtrace,
              item
            ), e)
        end

        protected

        private

        def state_record(method, key, item_id)

          @@logger[:user].info(
            sprintf("%s::%s, [%s, %s], %s, %s",
              self.class,
              method,
              @@participants,
              @participants,
              key,
              @@items[@parent][@child][key][item_id]
            )
          )
        end

        def case_waiting(item)

          if @@items[@parent][@child][:unsold][
               item[@keys[:item]]
             ].to_s ### nil を文字列に変更しておく
              .size > 0

            determine_waiting_listing(item, @self)
          end
        end

        def case_exhibit(item)

          time_left = []

          ### 他の販路の出品を止めるために、オーケストレーションメンバー
          ### から自分を除く (:ebay_us)
          adjust_participants
          if @@items[@parent][@child][:sold][
               item[@keys[:item]]
             ].to_s ### nil を文字列に変更しておく
              .size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            case @@items[@parent][@child][:sold][
                   item[@keys[:item]]
                 ][:seller_paid_status]
            when 'NotPaid',
                 'PaidWithPayPal'

              bidding_instructions(item)
              reset_do_repeat(item)
              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['selling']
              )
              reserve_end_item(item)
            end
          ### 売れずに終了の場合は :unsold ハッシュにデータがある
          elsif @@items[@parent][@child][:unsold][
               item[@keys[:item]]
             ].to_s ### nil を文字列に変更しておく
              .size > 0

            state_record(__method__, :unsold, item[@keys[:item]])

=begin
  再出品フラグを付与する、しないで制御する (11/11)
=end

            ### nil もしくは "" ではないことをみる
            if item['item_do_repeat'].to_s.size > 0 and
               ### N; ではないことを見る nil になるので
               PHP.unserialize(item['item_do_repeat']) and
               PHP.unserialize(item['item_do_repeat'])
                  .include?(sprintf("%s_%s", @parent, @child))

              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['reserve_relist_item']
              )
            else

              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['waiting']
              )
            end
          elsif @@items[@parent][@child][:active][
               item[@keys[:item]]
             ].to_s ### nil を文字列に変更しておく
              .size > 0

            state_record(__method__, :active, item[@keys[:item]])

            if @@items[@parent][@child][:active][
                 item[@keys[:item]]
               ][:watch_count].to_i > 0

              update_num_watch(
                item,
                @self,
                @@items[@parent][@child][:active][
                  item[@keys[:item]]
                ][:watch_count].to_i
              )
            end

            matches = @@items[@parent][@child][:active][
                        item[@keys[:item]]
                      ][:time_left]
                      .match(/P(\d*)D?T(\d*)H?(\d*)M?(\d*)S?$/)

            if matches[1].to_s.size > 0

              time_left.push(sprintf("%sd", matches[1]))
            end

            if matches[2].to_s.size > 0

              time_left.push(sprintf("%sh", matches[2]))
            end

            if matches[3].to_s.size > 0

              time_left.push(sprintf("%sm", matches[3]))
            end

            update_time_left(
              item,
              @self,
              time_left.join
            )

            remaining_time_seconds =
              (matches[1].to_i * 24 * 60 * 60)+
              (matches[2].to_i * 60 * 60)+
              (matches[3].to_i * 60)+
               matches[4].to_i

            update_end_time(
              item,
              @self,
              (Time.parse(
                @@items[@parent][@child][:active][
                  item[@keys[:item]]
                ][:listing_details_start_time])+
                remaining_time_seconds
              ).getlocal
               .strftime('%Y/%m/%d %H:%M:%S')
            )

            if item['item_yahoo_auctions_stockless_item_id']
                .to_s
                .size > 0

              unless ping_yahoo_auctions(
                       item['item_yahoo_auctions_stockless_item_id']
                     )

                finale(
                  item,
                  @configure[:builder][:mysql][:item]
                  .get_state['reserve_end_item']
                )
                reserve_end_item(item)
              end
            end
          end
rescue NoMethodError => e

@@logger[:user].error(
  sprintf("%s (%s, %s, %s)",
    @@items[@parent][@child][:active][
      item[@keys[:item]]
    ][:time_left],
    e.class,
    e.message,
    e.backtrace
  )
)
        end

        def case_selling(item)

          adjust_participants
          if @@items[@parent][@child][:sold][
               item[@keys[:item]]
             ].to_s ### nil を文字列に変更しておく
              .size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            case @@items[@parent][@child][:sold][
                   item[@keys[:item]]
                 ][:seller_paid_status]
            when 'PaidWithPayPal'

              update_sale_price(item, :selling_status_current_price)
              payment_item(item)
              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['payment']
              )
            end
          end
        end

        def case_payment(item)

          adjust_participants
          if @@items[@parent][@child][:sold][
               item[@keys[:item]]
             ].to_s ### nil を文字列に変更しておく
              .size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            case @@items[@parent][@child][:sold][
                   item[@keys[:item]]
                 ][:seller_paid_status]
            when 'PaidWithPayPal'
              update_sale_price(item, :selling_status_current_price)

              if item['item_ems_tracking_number']
                 .to_s
                 .size > 0

                shipment_item(item)
                finale(
                  item,
                  @configure[:builder][:mysql][:item]
                  .get_state['shipment']
                )
              end
            ### 返金ケースに対応する。
            when 'Refunded'
                 finale(
                   item,
                   @configure[:builder][:mysql][:item]
                   .get_state['waiting']
                 )
            end
          end
        end

        def case_shipment(item)

          if @@items[@parent][@child][:sold][
               item[@keys[:item]]
             ].to_s ### nil を文字列に変更しておく
              .size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            update_sale_price(item, :selling_status_current_price)
          end
        end

      end ### class Us < Ebay [END]

    end ### class Ebay < Member [END]

  end ### module Orchestrator [END]

end ### module TWEyes [END]
