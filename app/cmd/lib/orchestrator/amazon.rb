module TWEyes

  module Orchestrator ### 名前空間用なので機能を持たせるとバグるよ

    class Amazon < Member

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

      class Jp < Amazon

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
            TWEyes::Exception::Orchestrator::Amazon::Jp.new(
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
        end

        def case_exhibit(item)

          adjust_participants
          if @@items[@parent][@child][
               item[@keys[:item]]
             ].to_s
              .size > 0

            state_record(__method__, 'OrderStatus', item[@keys[:item]])

            case @@items[@parent][@child][
                   item[@keys[:item]]
                 ]['OrderStatus']
            when 'Pending',
                 'Unshipped'

              reset_do_repeat(item)
              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['selling']
              )
              reserve_end_item(item)
            end
          end
        end

        def case_selling(item)

          adjust_participants
          if @@items[@parent][@child][
               item[@keys[:item]]
             ].to_s
              .size > 0

            state_record(__method__, 'OrderStatus', item[@keys[:item]])

            case @@items[@parent][@child][
                   item[@keys[:item]]
                 ]['OrderStatus']
            when 'Unshipped'

              payment_item(item)
              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['payment']
              )
            when 'Canceled'

              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['waiting']
              )
            end
          end
        end

        def case_payment(item)

          adjust_participants
          if @@items[@parent][@child][
               item[@keys[:item]]
             ].to_s
              .size > 0

            state_record(__method__, 'OrderStatus', item[@keys[:item]])

            case @@items[@parent][@child][
                   item[@keys[:item]]
                 ]['OrderStatus']
            when 'Shipped'

              shipment_item(item)
              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['shipment']
              )
            when 'Canceled'

              finale(
                item,
                @configure[:builder][:mysql][:item]
                .get_state['waiting']
              )
            end
          end
        end

        def case_shipment(item)
        end

      end ### class Jp < Amazon [END]

    end ### class Amazon < Member [END]

  end ### module Orchestrator [END]

end ### module TWEyes [END]
