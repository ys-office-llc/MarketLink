require_relative '../lib/application'

module TWEyes
  class Shopping < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def operate
      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )
      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX

        initializeX

        processed = []
        @builder[:mysql][:item].get_records.each do |item|

          begin
            case item['item_yahoo_shopping_state_id']
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_add_item']
              add_item(item)
              processed.push(item)
            when @configure[:builder][:mysql][:item]
                 .get_state['reserve_end_item']
              end_item(item)
              processed.push(item)
            end
          rescue TWEyes::Exception::Controller::Yahoo => e
            @api[:chatwork].push_message(0,
              @formatter[:exception].handle(e))
          end
        end

        if processed.size > 0
          publish
        end

      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    protected

    private

    def initializeX
      @api[:yahoo][:shopping][:self].initializeX(@builder)
      @controller[:yahoo][:api][:shopping][:self].initializeX(
        @api[:yahoo][:shopping][:self]
      )

      @api[:yahoo][:shopping][:circus].initializeX(@builder)
      @controller[:yahoo][:api][:shopping][:circus].initializeX(
        @api[:yahoo][:shopping][:circus]
      )
    end

    def add_item(item)
      @controller[:yahoo][:api][:shopping][:circus].edit_item(
        item,
        @controller[:yahoo][:api][:shopping][:self].get_category_name(item)
      )

      if item['item_large_image_01'].size > 0
        @controller[:yahoo][:api][:shopping][:circus].upload_item_image(item)
      end

      @controller[:yahoo][:api][:shopping][:circus].set_stock(
        item['item_yahoo_shopping_item_id'].split,
        1.to_s.split,
      )
      @api[:chatwork].push_message(0,
        @formatter[:controller][:yahoo][:shopping].add_item(item))

      @builder[:mysql][:item].update(
        item['item_id'],
        {
          yahoo_shopping_state_id: @configure[:builder][:mysql][:item]
                                   .get_state['exhibit']
        }
      )

      sleep(
        @configure[:controller][:yahoo][:api][:shopping]
        .get_interval
      )
    end

    def end_item(item)
      @controller[:yahoo][:api][:shopping][:circus].delete_item(item)

      @builder[:mysql][:item].update(
        item['item_id'],
        {
          yahoo_shopping_state_id: @configure[:builder][:mysql][:item]
                                   .get_state['waiting']
        }
      )

      @api[:chatwork].push_message(0,
        @formatter[:controller][:yahoo][:shopping].end_item(item))

      sleep(
        @configure[:controller][:yahoo][:api][:shopping]
        .get_interval
      )
    end

    def publish
      @controller[:yahoo][:api][:shopping][:circus].reserve_publish
    end

  end ### class Shopping [END]
end
