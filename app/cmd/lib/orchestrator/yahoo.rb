module TWEyes

  module Orchestrator ### 名前空間用なので機能を持たせるとバグるよ

    class Yahoo < Member

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

      private

      class Auctions < Yahoo

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
            TWEyes::Exception::Orchestrator::Yahoo::Auctions.new(
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

=begin
          停止中ステータスなのに、ヤフオクでは出品中になっている場合は、
          出品停止が出来ていないため、出品状態へする。ヤフオクの状態と
          同期を取る
=end

          if @@items[@parent][@child][:selling][
               item[@keys[:item]]
             ].size > 0

            state_record(__method__, :selling, item[@keys[:item]])

            finale(
              item,
              @configure[:builder][:mysql][:item]
              .get_state['exhibit']
            )
          end
        end

        def case_exhibit(item)

          adjust_participants
          if @@items[@parent][@child][:sold][
               item[@keys[:item]]
             ].size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            case @@items[@parent][@child][:sold][
                   item[@keys[:item]]
                 ][:progress]
            when 'address_inputing',
                 'postage_inputing',
                 'sales_contract_fixed',
                 'money_received',
                 'preparation_for_shipment'

              reset_do_repeat(item)
              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['selling']
              )
              reserve_end_item(item)
            end
          elsif @@items[@parent][@child][:not_sold][
                  item[@keys[:item]]
                ].size > 0

            state_record(__method__, :not_sold, item[@keys[:item]])
=begin
  再出品フラグを付与する、しないで制御する (11/11)
=end

            if item['item_do_repeat'].to_s.size > 0 and
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
          elsif @@items[@parent][@child][:selling][
                  item[@keys[:item]]
                ].size > 0

            state_record(__method__, :selling, item[@keys[:item]])

            if @@items[@parent][@child][:selling][
                 item[@keys[:item]]
               ][:num_watch].to_i > 0

              update_num_watch(
                item, 
                @self,
                @@items[@parent][@child][:selling][
                  item[@keys[:item]]
                 ][:num_watch].to_i
              )
            end

            update_time_left(
              item,
              @self,
              Duration.new(
                Time.parse(
                  @@items[@parent][@child][:selling][
                    item[@keys[:item]]
                  ][:end_time]) - @configure[:system][:date]
                  .get_time
              ).format('%dd%Hh%Mm')
            )
            update_end_time(
              item,
              @self,
              @@items[@parent][@child][:selling][
                item[@keys[:item]]
              ][:end_time]
            )
            update_current_price(
              item,
              @self,
              @@items[@parent][@child][:selling][
                item[@keys[:item]]
              ][:current_price]
            )
          end
        end

        def case_selling(item)

          adjust_participants
          if @@items[@parent][@child][:sold][
               item[@keys[:item]]
             ].size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            case @@items[@parent][@child][:sold][
                   item[@keys[:item]]
                 ][:progress]
            when 'preparation_for_shipment'

              update_sale_price(item, :highest_price)
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
             ].size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            case @@items[@parent][@child][:sold][
                   item[@keys[:item]]
                 ][:progress]
            when 'shipping',
                 'complete'

              shipment_item(item)
              update_sale_price(item, :highest_price)
              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['shipment']
              )
            end
          end
        end

        def case_shipment(item)

          if @@items[@parent][@child][:sold][
               item[@keys[:item]]
             ].size > 0

            state_record(__method__, :sold, item[@keys[:item]])

            case @@items[@parent][@child][:sold][
                   item[@keys[:item]]
                 ][:progress]
            when 'shipping',
                 'complete'

              update_sale_price(item, :highest_price)
            end
          end
        end

      end ### class Auctions < Yahoo

      class Shopping < Yahoo

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
            TWEyes::Exception::Orchestrator::Yahoo::Shopping.new(
              e.class,
              e.backtrace,
              item
            ), e)
        end

        protected

        private

        def case_waiting(item)
        end

        def case_exhibit(item)
          adjust_participants
          if @@items[@parent][@child][item[@keys[:item]]].zero? and
             item['item_stock'] === 1
            reserve_end_item(item)
            finale(
              item,
              @configure[:builder][:mysql][:item]
              .get_state['payment']
            )
          end
        end

        def case_selling(item)
        end

        def case_payment(item)

          adjust_participants
          if @@items[@parent][@child][item[@keys[:item]]].zero?
            shipment_item(item)
            finale(
              item,
              @configure[:builder][:mysql][:item]
              .get_state['shipment']
            )
          end
        end

        def case_shipment(item)
        end

      end

    end ### class Shopping < Yahoo

  end ### module Orchestrator [END]

end ### module TWEyes [END]
