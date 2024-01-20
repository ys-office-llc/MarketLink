module TWEyes

  module Orchestrator ### 名前空間用なので機能を持たせるとバグるよ

    class Member

      @@participants = []
      @@items        = nil
      @@api          = nil
      @@builder      = nil
      @@controller   = nil
      @@formatter    = nil
      @@logger       = nil

      public

      def initialize(configure)
        super()

        @configure     = configure
        @@participants = []
        @participants  = []
      end

      def initializeX(api, builder, controller, formatter, logger)

        @@api        = api
        @@builder    = builder
        @@controller = controller
        @@formatter  = formatter
        @@logger     = logger
      end

      def join(market)

        @@participants.push(market)
      end

      def collect(caller)

        ### 三次元以上用Hash
        @@items = Hash.new{|h,k| h[k] = Hash.new(&h.default_proc)}

        if @@participants.include?(:amazon_jp)
          @@items[:amazon].store(:jp, caller.call(:amazon_jp))
        end

        if @@participants.include?(:yahoo_auctions)
          @@items[:yahoo][:auctions]
            .store(:selling, caller.call(:yahoo_selling))
          @@items[:yahoo][:auctions]
            .store(:sold, caller.call(:yahoo_sold))
          @@items[:yahoo][:auctions]
            .store(:not_sold, caller.call(:yahoo_not_sold))
        end

        if @@participants.include?(:yahoo_shopping)
          @@items[:yahoo]
            .store(:shopping, caller.call(:yahoo_shopping))
        end

        if @@participants.include?(:ebay_us)
          @@items[:ebay][:us]
            .store(:active, caller.call(:ebay_active))
          @@items[:ebay][:us]
            .store(:sold, caller.call(:ebay_sold))
          @@items[:ebay][:us]
            .store(:unsold, caller.call(:ebay_unsold))
        end

pp @@items
=begin
        pp @@items[:yahoo][:auctions][:selling]
        pp @@items[:yahoo][:auctions][:sold]
        pp @@items[:yahoo][:auctions][:not_sold]
        pp @@items[:ebay][:us][:active]
        pp @@items[:ebay][:us][:sold]
        pp @@items[:ebay][:us][:unsold]
        pp @@items[:amazon][:jp]
=end
      end

      protected

      def switch(item)

        case item[@keys[:state]]
        when @configure[:builder][:mysql][:item].get_state['waiting']

          case_waiting(item)
        when @configure[:builder][:mysql][:item].get_state['exhibit']

          case_exhibit(item)
        when @configure[:builder][:mysql][:item].get_state['selling']

          case_selling(item)
        when @configure[:builder][:mysql][:item].get_state['payment']

          case_payment(item)
        when @configure[:builder][:mysql][:item].get_state['shipment']

          case_shipment(item)
        end
      end

      def get_class_suffix

        self.class
            .to_s
            .split('::')[-2..-1]
            .map{|e|e.downcase}
            .join('_')
      end

      def adjust_participants

        @participants = @@participants.dup
        @participants.delete(@self.to_sym)
      end

      def finale(item, id)

        update_state(item, @self, id)
        push_message(item, id)
      end

      def reset_do_repeat(item)

        @@builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            do_repeat: PHP.serialize(nil)
          }
        )
      end

      def update_sale_price(item, key)

        @@builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            sprintf("%s_sale_price", @self).to_sym => 
              @@items[@parent][@child][:sold][
                item[@keys[:item]]
              ][key]
          }
        )
      end

      def update_state(item, key, id)

        @@builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            sprintf("%s_state_id", key).to_sym => id
          }
        )
      end

      def update_num_watch(item, key, num_watch)

        @@builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            sprintf("%s_num_watch", key).to_sym => num_watch
          }
        )
      end

      def update_time_left(item, key, time_left)

        @@builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            sprintf("%s_time_left", key).to_sym => time_left
          }
        )
      end

      def update_end_time(item, key, end_time)

        @@builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            sprintf("%s_end_time", key).to_sym => end_time
          }
        )
      end

      def update_current_price(item, key, current_price)

        @@builder[:mysql][:item][:self].update(
          item['item_id'],
          {
            sprintf("%s_current_price", key).to_sym => current_price 
          }
        )
      end

      def push_message(item, id)

        @@api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['item'],
          @@formatter[:orchestrator][@parent][@child].conduct(
            item,
            @@builder[:mysql][:item][:self].get_state_names[id]
          )
        )
      end

      def push_message_change_state(participant, item, id)

        @@api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['item'],
          @@formatter[:orchestrator][
            participant.to_s.split('_')[0].to_sym
          ][
            participant.to_s.split('_')[1].to_sym
          ].conduct(
            item,
            @@builder[:mysql][:item][:self].get_state_names[id]
          )
        )
      end

      def reserve_end_item(item)

        parent = nil
        child  = nil

        @participants.each do |participant|

          if item[
               sprintf("item_%s_state_id", participant)
             ] == @configure[:builder][:mysql][:item]
                 .get_state['exhibit']

           push_message_change_state(
             participant,
             item,
             @configure[:builder][:mysql][:item]
             .get_state['reserve_end_item']
           )

           update_state(
             item,
             participant,
             @configure[:builder][:mysql][:item]
             .get_state['reserve_end_item']
            )
          end
        end
      end

      def payment_item(item)

        @participants.each do |participant|

=begin
          if item[
               sprintf("item_%s_state_id", participant)
             ] == @configure[:builder][:mysql][:item]
                 .get_state['waiting']
=end

=begin
  除外している商品も入金状態へスイッチするコード
=end
          case item[sprintf("item_%s_state_id", participant)]
          when @configure[:builder][:mysql][:item].get_state['exclude'],
               @configure[:builder][:mysql][:item].get_state['waiting']

            update_state(
              item,
              participant,
              @configure[:builder][:mysql][:item]
              .get_state['payment']
            )
          end
        end
      end

      def shipment_item(item)

        @participants.each do |participant|

          if item[
               sprintf("item_%s_state_id", participant)
             ] == @configure[:builder][:mysql][:item]
                 .get_state['payment']

            update_state(
              item,
              participant,
              @configure[:builder][:mysql][:item]
              .get_state['shipment']
            )
          end
        end
      end

      def determine_waiting_listing(item, participant)

        auction_id = item['item_yahoo_auctions_stockless_item_id'].to_s

        if auction_id.size > 0 and
           ping_yahoo_auctions(auction_id)

           update_state(
             item,
             participant,
             @configure[:builder][:mysql][:item]
             .get_state['reserve_add_item']
           )
        else
        end
      end

      def bidding_instructions(item)

        auction_id = item['item_yahoo_auctions_stockless_item_id'].to_s

        if auction_id.size > 0 and
           ping_yahoo_auctions(auction_id)

          @@builder[:mysql][:item][:self].update(
            item['item_id'],
            {
              request_to_bids: 1,
            }
          )
        end
      end

      def ping_yahoo_auctions(auction_id)

        item = {}

        item = @@controller[:yahoo][:api][:auctions]
               .auction_item(auction_id)

        case item[:status]
        when 'open'

          true
        when 'closed', 'cancelled'

          false
        end
      end

      private

    end ### class Member

  end ### module Orchestrator [END]

end ### module TWEyes [END]
