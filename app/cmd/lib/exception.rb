module TWEyes

  class Exception < StandardError

    attr_reader :parent_class, :parent_backtrace, :method

    def initialize(parent_class = '', parent_backtrace = [], method = '')

      @parent_class     = parent_class
      @parent_backtrace = parent_backtrace.join("\n")
      @method           = method
    end

    class Database < Exception

      class MySQL < Database
      end

    end

    class Builder < Exception

      class MySQL < Builder

        class Account < MySQL
        end

        class Item < MySQL
        end

        class Auth < MySQL
        end

        class Support < MySQL
        end

      end

    end

    class Mixin < Exception

      class Yahoo < Mixin

        class Auctions < Yahoo

          class API < Auctions
          end

        end

        class Shopping < Yahoo
          class API < Shopping
          end
        end

      end

    end

    class Builder < Exception

    class MySQL < Builder
      class Item < MySQL
      end
      class Account < MySQL
      end
      class Auth < MySQL
      end
    end

    end

    class Configure < Exception

      class System < Configure

        class Net < System
        end

      end

    end

    class Bootstrap < Exception

      class Loader < Bootstrap
      end

    end

    class Database < Exception

      class MySQL < Database
      end

    end

    class API < Exception

      attr_reader  :parent_class,
                   :parent_backtrace,
                   :method

      def initialize(
            parent_class     = '',
            parent_backtrace = [],
            method           = ''
          )
        @parent_class     = parent_class
        @parent_backtrace = parent_backtrace.join("\n")
        @method           = method
      end

      class Amazon < API

        class MWS < Amazon

          class Feeds < MWS

          end

          class Orders < MWS

          end

          class Reports < MWS

          end

          class Products < MWS

          end

          class FulfillmentInventory < MWS

          end

        end

      end

      class Yahoo < API

        class Auctions < Yahoo
        end

        class Shopping < Yahoo

          class Circus < Shopping
          end

        end

      end

      class Ebay < API

        class Trading < Ebay

          class Production < Trading
          end

        end

        class Finding < Ebay

          class Production < Finding
          end

        end

        class BusinessPoliciesManagement < Ebay

          class Production < BusinessPoliciesManagement 
          end

        end

      end

      class ChatWork < API
      end

    end

    class Orchestrator < Exception

      attr_reader :parent_class, :parent_backtrace, :item

      def initialize(parent_class = '', parent_backtrace = [], item = {})
        @parent_class     = parent_class
        @parent_backtrace = parent_backtrace.join("\n")
        @item             = item
      end

      class Amazon < Orchestrator 

        class Jp < Amazon
        end

      end

      class Ebay < Orchestrator 

        class Us < Ebay
        end

      end

      class Yahoo < Orchestrator 

        class Auctions < Yahoo
        end

        class Shopping < Yahoo
        end

      end
    end

    class Controller < Exception

      class Stores < Controller

        attr_reader  :parent_class,
                     :parent_backtrace,
                     :method

        def initialize(
              parent_class     = '',
              parent_backtrace = [],
              method           = ''
            )
          @parent_class     = parent_class
          @parent_backtrace = parent_backtrace.join("\n")
          @method           = method 
        end

        class Kitamura < Stores
        end

        class MapCamera < Stores
        end

        class ChampCamera < Stores
        end

        class CameraNoNaniwa < Stores
        end

        class FujiyaCamera < Stores
        end

        class Hardoff < Stores
        end

      end

      class FreeMarkets < Controller

        attr_reader  :parent_class,
                     :parent_backtrace,
                     :method

        def initialize(
              parent_class     = '',
              parent_backtrace = [],
              method           = ''
            )
          @parent_class     = parent_class
          @parent_backtrace = parent_backtrace.join("\n")
          @method           = method
        end

        class Mercari < FreeMarkets
        end

        class Rakuma < FreeMarkets
        end

        class Fril < FreeMarkets
        end

      end

      class JapanPost < Controller

        attr_reader  :parent_class,
                     :parent_backtrace,
                     :method

        def initialize(
              parent_class     = '',
              parent_backtrace = [],
              method           = ''
            )
          @parent_class     = parent_class
          @parent_backtrace = parent_backtrace.join("\n")
          @method           = method
        end

        class EMS < JapanPost
        end

      end

      class Support < Controller

        class Contact < Support

        end

      end

      class Monitor < Controller

        attr_reader  :parent_class,
                     :parent_backtrace,
                     :method

        def initialize(
              parent_class     = '',
              parent_backtrace = [],
              method           = ''
            )
          @parent_class     = parent_class
          @parent_backtrace = parent_backtrace.join("\n")
          @method           = method
        end

        class Process < Monitor

        end

      end

      class Daemon < Controller
      end

      class Ebay < Controller

        class Mechanize < Ebay

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :method

          def initialize(
                parent_class     = '',
                parent_backtrace = [],
                method           = ''
              )
            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @method           = method
          end

        end

        class Trading < Ebay

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :method,
                       :item

          def initialize(
                parent_class     = '',
                parent_backtrace = [],
                method           = '',
                item             = {}
              )
            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @method           = method 
            @item             = item
          end

          class Production < Trading

          end

        end

        class Finding < Ebay

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :method,
                       :item

          def initialize(
                parent_class     = '',
                parent_backtrace = [],
                method           = '',
                item             = {}
              )
            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @method           = method
            @item             = item
          end

          class Production < Finding

          end

        end

      end

      class ChatWork < Controller

        attr_reader  :parent_class,
                     :parent_backtrace,
                     :capture_url,
                     :source_url,
                     :method

        def initialize(
              parent_class = '',
              parent_backtrace = [],
              capture_url = '',
              source_url  = '',
              method      = ''
            )
          @parent_class     = parent_class
          @parent_backtrace = parent_backtrace.join("\n")
          @capture_url      = capture_url
          @source_url       = source_url
          @method           = method
        end

      end

      class Amazon < Controller

        class Developer < Amazon

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :capture_url,
                       :source_url,
                       :method

          def initialize(
                parent_class = '',
                parent_backtrace = [],
                capture_url = '',
                source_url  = '',
                method      = ''
              )
            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @capture_url      = capture_url
            @source_url       = source_url
            @met
          end

        end

        class Jp < Amazon

          class Mechanize < Jp
          end

        end

        class MWS < Amazon

          class Feeds < MWS
          end

          class Orders < MWS
          end

          class Reports < MWS
          end

          class FulfillmentInventory < MWS
          end

          class Products < MWS
          end

        end

      end

      class Yahoo < Controller

        class Auth < Yahoo

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :capture_url,
                       :source_url,
                       :method

          def initialize(
                parent_class = '',
                parent_backtrace = [],
                capture_url = '',
                source_url  = '',
                method      = ''
              )
            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @capture_url      = capture_url 
            @source_url       = source_url 
            @method           = method
          end

        end

        class Developer < Yahoo

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :capture_url,
                       :source_url,
                       :method

          def initialize(
                parent_class = '',
                parent_backtrace = [],
                capture_url = '',
                source_url  = '',
                method      = ''
              )
            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @capture_url      = capture_url
            @source_url       = source_url
            @method           = method
          end

        end

        class Auctions < Yahoo

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :item,
                       :capture_url,
                       :source_url,
                       :method

          def initialize(
                parent_class = '',
                parent_backtrace = [],
                item = {},
                capture_url = '',
                source_url  = '',
                method      = ''
              )

            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @item             = item
            @capture_url      = capture_url 
            @source_url       = source_url 
            @method           = method
          end

          class Mechanize < Auctions

            attr_reader  :parent_class,
                         :parent_backtrace,
                         :method

            def initialize(
                  parent_class     = '',
                  parent_backtrace = [],
                  method           = ''
                )
              @parent_class     = parent_class
              @parent_backtrace = parent_backtrace.join("\n")
              @method           = method
            end

          end

          class AddItem < Auctions
          end

          class ResubmitItem < Auctions
          end

          class EndItem < Auctions
          end

          class MySellingList < Auctions
          end

          class MyCloseListHasWinner < Auctions
          end

          class MyCloseListHasNoWinner < Auctions
          end

          class PlaceBids < Auctions
          end

          class PlaceValueComment < Auctions
          end

          class GetShippingInformation < Auctions
          end

        end

        class Shopping < Yahoo

        end

        class API < Yahoo

          attr_reader  :parent_class,
                       :parent_backtrace,
                       :item

          def initialize(
                parent_class = '',
                parent_backtrace = [],
                item = {}
              )
            @parent_class     = parent_class
            @parent_backtrace = parent_backtrace.join("\n")
            @item             = item
          end

          class Auctions < API

            attr_reader  :parent_class,
                         :parent_backtrace,
                         :method

            def initialize(
                  parent_class = '',
                  parent_backtrace = [],
                  method = ''
                )
              @parent_class     = parent_class
              @parent_backtrace = parent_backtrace.join("\n")
              @method           = method
            end

          end

          class Shopping < API

            class Circus < Shopping
            end

          end

        end

      end

    end

    class Driver < Exception

      class Web < Driver

        class Selenium < Web
        end

        class Mechanize < Web
        end

        class Proxies < Web

          class MPP < Proxies
          end

        end

      end

    end

  end ### class Exception [END]

end
