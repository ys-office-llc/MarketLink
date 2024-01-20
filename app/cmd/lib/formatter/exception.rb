module TWEyes

  module Formatter ### 名前空間

    class Exception

      public

      def initialize(configure)
        super()

        @configure = configure
        @exception = nil
      end

      def handle(exception)
        @exception = exception

        case exception
        when TWEyes::Exception::Controller::Daemon

          sprintf(
            "[info][title]Exception from Daemon[/title]【注意】%s[/info]",
            @exception.message.avoid_percent
          )
        when TWEyes::Exception::Controller::Yahoo::Auth
          case exception.method
          when :manage_token
            sprintf(
              @configure[:formatter][:controller][:yahoo][:auth]
                .manage_token['failure'],
                exception.method,
                exception.capture_url,
                exception.source_url,
                exception.message
            )
          else

            safety_net
          end
        when TWEyes::Exception::Controller::Yahoo::Auctions::AddItem
          sprintf(
            @configure[:formatter][:controller][:yahoo][:auctions][:self]
              .add_item['failure'],
              exception.item['item_product_name'],
              exception.capture_url,
              exception.source_url,
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+exception
              .item['item_id']
              .to_s,
              exception.item[:dec_error_box],
              exception.message
          )
        when TWEyes::Exception::Controller::Yahoo::Auctions::ResubmitItem
          sprintf(
            @configure[:formatter][:controller][:yahoo][:auctions][:self]
              .resubmit_item['failure'],
              exception.item['item_product_name'],
              exception.capture_url,
              exception.source_url,
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+exception
              .item['item_id']
              .to_s,
              exception.message
          )
        when TWEyes::Exception::Controller::Yahoo::Auctions::EndItem
          sprintf(
            @configure[:formatter][:controller][:yahoo][:auctions][:self]
              .end_item['failure'],
              exception.item['item_product_name'],
              exception.capture_url,
              exception.source_url,
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+exception
              .item['item_id']
              .to_s,
              exception.message
          )
        when TWEyes::Exception::Controller::Yahoo::API::Shopping,
             TWEyes::Exception::Controller::Yahoo::API::Shopping::Circus
          sprintf(
            @configure[:formatter][:controller][:yahoo][:shopping]
              .add_item['failure'],
              exception.item['item_product_name'],
              exception.message,
              @configure[:system][:net]
              .get_https_uri+'/item/get/'+exception
              .item['item_id']
              .to_s
          )
        when TWEyes::Exception::Controller::Yahoo::API::Auctions

          case exception.method
          when :my_close_list

            sprintf(
              @configure[:formatter][:controller][:yahoo][:api][
                :auctions
              ][:self].my_close_list['failure'],
              @configure[:system][:net].get_domain,
              exception.message
            )
          when :my_selling_list

            sprintf(
              @configure[:formatter][:controller][:yahoo][:api][
                :auctions
              ][:self].my_selling_list['failure'],
              @configure[:system][:net].get_domain,
              exception.message
            )
          else

            safety_net
          end
        when TWEyes::Exception::Controller::Ebay::Trading::Production

          case exception.method
          when :add_item

            sprintf(
              @configure[:formatter][:controller][:ebay][:us]
              .add_item['failure'], exception
              .item
              .symbolize_keys
              .merge(
                {
                  errors: exception.message,
                  tweyes_link: @configure[:system][:net]
                               .get_https_uri+'/item/get/'+exception
                               .item['item_id']
                               .to_s
                }
              )
            )
          when :relist_item
            sprintf(
              @configure[:formatter][:controller][:ebay][:us]
              .relist_item['failure'], exception
              .item
              .symbolize_keys
              .merge(
                {
                  errors: exception.message,
                  tweyes_link: @configure[:system][:net]
                               .get_https_uri+'/item/get/'+exception
                               .item['item_id']
                               .to_s
                }
              )
            )
          when :revise_item
            sprintf(
              @configure[:formatter][:controller][:ebay][:us]
              .revise_item['failure'], exception
              .item
              .symbolize_keys
              .merge(
                {
                  errors: exception.message,
                  tweyes_link: @configure[:system][:net]
                               .get_https_uri+'/item/get/'+exception
                               .item['item_id']
                               .to_s
                }
              )
            )
          when :end_item
            sprintf(
              @configure[:formatter][:controller][:ebay][:us]
              .end_item['failure'], exception
              .item
              .symbolize_keys
              .merge(
                {
                  errors: exception.message,
                  tweyes_link: @configure[:system][:net]
                               .get_https_uri+'/item/get/'+exception
                               .item['item_id']
                               .to_s
                }
              )
            )
          else

            safety_net
          end
        else
          if exception.instance_variable_defined?(:@parent_class) and
             exception.instance_variable_defined?(:@parent_backtrace)

            sprintf(
              "[info]"+
              "[title]捕捉できなかった例外エラー（%s）[/title]"+
              "システム管理者が確認します。\n\n"+
              "[title]TWEyes独自エラークラス[/title][code]%s[/code]\n"+
              "[title]発生元エラークラス[/title][code]%s[/code]\n"+
              "[title]エラーメッセージ[/title][code]%s[/code]\n"+
              "[title]ハンドルした場所のエラー詳細コードトレース結果[/title]"+
              "[code]%s[/code]"+
              "[title]発生元のエラー詳細コードトレース結果[/title]"+
              "[code]%s[/code]"+
              "[/info]",
              @configure[:system][:net].get_domain,
              exception.class,
              exception.parent_class,
              exception.message.avoid_percent,
              exception.backtrace.join("\n"),
              exception.parent_backtrace
            )
          else

            safety_net
          end
        end
      end

      protected

      private

      def safety_net

        sprintf(
          "[info][title]捕捉できなかった例外（%s）[/title]"+
          "[code]%s, %s, %s[/code][/info]",
          @configure[:system][:net].get_domain,
          @exception.class,
          @exception.message.avoid_percent,
          @exception.backtrace.join("\n"),
        )
      end

    end ### class Yahoo [END]
  
  end
end
