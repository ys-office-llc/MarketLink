require_relative 'ruby_require'

module TWEyes

  class Configure

    SERVER_ROOT      = 'server_root'
    APP_ROOT         = 'app_root'
    ETC_ROOT         = 'etc_root'
    VAR_ROOT         = 'var_root'
    APPLICATION_ROOT = 'application_root'
    COMMAND_ROOT     = 'command_root'
    DAEMON_ROOT      = 'daemon_root'
    LIBRARY_ROOT     = 'library_root'
    CONFIG_ROOT      = 'config_root'

    attr_accessor :account

    @@configure = {}

    public

    def initialize

      @account = {}
      @paths   = {}

      @paths[SERVER_ROOT]      = File::dirname(__FILE__)
                                 .split('/')[0..-4]
                                 .join('/')
      @paths[APP_ROOT]         = @paths[SERVER_ROOT]  + '/app'
      @paths[ETC_ROOT]         = @paths[SERVER_ROOT]  + '/etc'
      @paths[VAR_ROOT]         = @paths[SERVER_ROOT]  + '/var'
      @paths[COMMAND_ROOT]     = @paths[APP_ROOT]     + '/cmd'
      @paths[APPLICATION_ROOT] = @paths[APP_ROOT]     + '/ui'
      @paths[DAEMON_ROOT]      = @paths[COMMAND_ROOT] + '/daemon'
      @paths[LIBRARY_ROOT]     = @paths[COMMAND_ROOT] + '/lib'
      @paths[CONFIG_ROOT]      = @paths[ETC_ROOT]     + '/yml'

      configure
    end

    def get_paths

      @paths
    end

    def get_server_root

      @paths[SERVER_ROOT]
    end

    def get_etc_root

      @paths[ETC_ROOT]
    end

    def get_var_root

      @paths[VAR_ROOT]
    end

    def get_application_root

      @paths[APPLICATION_ROOT]
    end

    def get_library_root

      @paths[LIBRARY_ROOT]
    end

    def get_command_root

      @paths[COMMAND_ROOT]
    end

    def get_daemon_root

      @paths[DAEMON_ROOT]
    end

    def get_config_root

      @paths[CONFIG_ROOT]
    end

    def get_current

      @@configure
    end

    protected

    private

    def merge_yaml(original, append)
      append.each do |k, v|

        if v.kind_of?(Hash) and original.key?(k)
          original[k] = merge_yaml(original[k], v)
        else
          original[k] = v
        end

      end
  
      return original
    end
  
    def configure

      merged_yaml = {}
  
      Find.find(get_config_root).each do |file|
        if /[a-z_]+\.yml$/ =~ file
          yaml = YAML.load_file(file)
          merged_yaml = merge_yaml(merged_yaml, yaml)
        end
      end
  
      @@configure.merge!(merged_yaml)
    end

    class Yahoo < Configure

      public

      def initialize

        super

        @configure = @@configure['configure']['yahoo']
      end

      protected

      private

      class Auctions < Yahoo

        public

        def initialize
          super

          @configure = @configure['auctions']
        end

        def url_search

          @configure['url']['search']['self']
        end

        def url_closedsearch

          @configure['url']['closedsearch']['self']
        end

        def query_search

          @configure['query']['search']
        end

        def query_closedsearch

          @configure['query']['closedsearch']
        end

        def replaces

          @configure['replaces']
        end

        def  stock_image_urls_except

          @configure['stock_image_urls']['except']
        end

        protected

        private

      end ### class Auctions < Yahoo [END]

    end ### class Yahoo < Configure [END]

    class Ebay < Configure

      public

      def initialize

        super

        @configure = @@configure['configure']['ebay']
      end

      protected

      private

      class Com < Ebay

        public

        def initialize
          super

          @configure = @configure['com']
        end

        def url_active

          @configure['url']['active']['self']
        end

        def url_sold

          @configure['url']['sold']['self']
        end

        def query_active

          @configure['query']['active']
        end

        def query_sold

          @configure['query']['sold']
        end

        protected

        private

      end ### class Com < Ebay [END]

    end ### class Ebay < Configure [END]

    class MVC < Configure

      public

      def initialize

        super

        @configure = @@configure['configure']['mvc']
      end

      protected

      private

      class Potal < MVC

        public

        def initialize

          super

          @configure = @configure['potal']
        end

        def resources_limit

          @configure['resources']['limit']
        end

        def resources_factor_market_screening

          @configure['resources']['factor']['market_screening']
        end

        protected

        private

      end ### class Potal < MVC

      class System < MVC

        public

        def initialize
          super

          @configure = @configure['system']
        end

        protected

        private

        class Research < System
 
          public
 
          def initialize
            super
 
            @configure = @configure['research']
          end
 
          protected
 
          private
  
          class Ebay < Research
 
            public
 
            def initialize
              super
 
              @configure = @configure['ebay']
            end

            def get_query_active

              @configure['query']['active']
            end
 
            def get_query_sold

              @configure['query']['sold']
            end

            protected
 
            private
  
          end ### class Ebay Research [END]

          class Yahoo < Research

            public

            def initialize
              super

              @configure = @configure['yahoo']
            end

            protected

            private

            class Auctions < Yahoo

              public

              def initialize
                super

                @configure = @configure['auctions']
              end

              def get_query_selling

                @configure['query']['selling']
              end

              def get_query_sold

                @configure['query']['sold']
              end

              protected

              private

            end ### class Auctions < Yahoo [END]

          end ### class Yahoo < Research [END]

        end ### class Research < System [END]

      end ### class System < MVC [END]

    end ### class MVC < Configure [END]

    class Bootstrap < Configure
      public

      def initialize
        super
      end

      protected

      private

      class Loader < Bootstrap

        public

        def initialize
          super

          @configure = @@configure['configure']['bootstrap']['loader']
        end

        def get_timeout
          @configure['require_repeatedly']['timeout']
        end

        protected

        private

      end ### class Loader [END]

    end ### class Bootstrap [END]

    class System < Configure
      public

      def initialize
        super

        @configure = @@configure['configure']['system']
      end

      class User < System
        public

        def initialize
          super
        end

        protected

        private
      end ### class User {END]

      class DateX < System
        public

        def initialize
          super
        end

        def get_time
          Time.now
        end

        def get_today
          Date.today
        end

        def get_date_suffix
          get_time.strftime('%Y-%m-%d')
        end

        def get_date_time_suffix
          get_time.strftime('%Y-%m-%d_%H:%M')
        end

        protected

        private

      end ### class Date {END]

      class Directory < System

        public

        def initialize
          super
        end

        def get_relative_images_path

          @configure['relative_paths']['images']
        end

        def get_relative_spool_path

          @configure['relative_paths']['spool']
        end

        def get_log_path

          get_var_root+@configure['relative_paths']['log']
        end

        def get_spool_path

          get_var_root+@configure['relative_paths']['spool']
        end

        def get_run_path

          get_var_root+@configure['relative_paths']['run']
        end

        def get_archive_path

          get_var_root+@configure['relative_paths']['archive']
        end

        def get_tmp_path

          get_var_root+@configure['relative_paths']['tmp']
        end

        def get_migration_path

          get_var_root+@configure['relative_paths']['migration']
        end

        def get_htdocs_path

          get_server_root+@configure['relative_paths']['htdocs']
        end

        def get_images_path

          get_htdocs_path+get_relative_images_path
        end

        def get_htdocs_spool_path

          get_htdocs_path+get_relative_spool_path
        end

        protected

        private

      end ### class Directory {END]

      class Net < System

        public

        def initialize
          super
        end

        def get_domain

          resolver = Resolv::DNS.new()
          domain = get_server_root.split('/')[4..7].reverse.join('.')
          if resolver.getresources(
               domain,
               Resolv::DNS::Resource::IN::A
             ).size.zero?

            raise(
              TWEyes::Exception::Configure::System::Net,
              "#{domain}: Name or service not known"
            )
          end

          domain
        end

        def get_host

          get_domain.split(".")[0]
        end

        def get_http_schema
          @configure['protocols']['http']
        end

        def get_https_schema
          @configure['protocols']['https']
        end

        def get_http_uri
          get_http_schema+get_domain
        end

        def get_https_uri
          get_https_schema+get_domain
        end

        protected

        private

      end ### class Net [END]

      protected

      private

      class Products < System

        public

        def initialize

          super

          @configure = @configure['products']
        end

        def name

          @configure['name']['self']
        end

        def version

          sprintf("%s.%s.%s",
            @configure['version']['major'],
            @configure['version']['minor'],
            @configure['version']['bugfix']
          )
        end

        def name_version

          sprintf("%s (%s)", name, version)
        end

        protected

        private

      end ### class Products < System {END]

      class Resources < System

        public

        def initialize

          super

          @configure = @configure['resources']
        end

        def threads

          @configure['threads']
        end

        def retention_period

          @configure['retention_period']
        end

        protected

        private

      end ### class Resources < System {END]

    end ### class System [END]

    class Controller < Configure

      public

      def initialize
        super
        @configure = @@configure['configure']['controller']
      end

      protected

      private

      class ChatWork < Controller

        public

        def initialize
          super

          @configure = @configure['chatwork']
        end

        def url

          @configure['url']['self']
        end

        def url_tweyes_administrator

          @configure['url']['tweyes_administrator']
        end

        def account 

          @configure['account']
        end

        def password

          @configure['password']
        end

        protected

        private

      end ### class Monitor < Controller [END]

      class Monitor < Controller

        public

        def initialize
          super

          @configure = @configure['monitor']
        end

        protected

        private

        class Process < Monitor

          public

          def initialize
            super

            @configure = @configure['process']
          end

          def members

            @configure['members']
          end

          protected

          private

        end ### class Process < Monitor

      end ### class Monitor < Controller [END]

      class Stores < Controller

        public

        def initialize
          super

          @configure = @configure['stores']
        end

        def get_links
          @configure['links']
        end

        def get_replaces
          @configure['replaces']
        end

        protected

        private

        class Kitamura < Stores

          public

          def initialize
            super

            @configure = @configure['kitamura']
          end

          def get_new_arrival_url
            @configure['new_arrival']['url']
          end

          protected

          private

        end ### class Kitamura < Stores [END]

        class MapCamera < Stores

          public

          def initialize
            super

            @configure = @configure['map_camera']
          end

          def get_interval

            @configure['interval']
          end

          def get_new_arrival_url_search

            @configure['new_arrival']['url']['search']['self']
          end

          def get_new_arrival_parameters

            @configure['new_arrival']['parameters']['self']
          end

          def get_item_conditions

            @configure['item_conditions']
          end

          protected

          private

        end ### class MapCamera < Stores [END]

        class ChampCamera < Stores

          public

          def initialize

            super

            @configure = @configure['champ_camera']
          end

          def interval

            @configure['interval']
          end

          def new_arrival_page_limit

            @configure['new_arrival']['page_limit']
          end

          def new_arrival_url

            @configure['new_arrival']['url']['self']
          end

          def new_arrival_url_shop

            @configure['new_arrival']['url']['shop']['self']
          end

          def new_arrival_parameters

            @configure['new_arrival']['parameters']
          end

          protected

          private

        end ### class MapCamera < Stores [END]

        class Hardoff < Stores

          public

          def initialize
            super

            @configure = @configure['hardoff']
          end

          def get_interval

            @configure['interval']
          end

          def get_new_arrival_url_search

            @configure['new_arrival']['url']['search']['self']
          end

          def get_new_arrival_parameters

            @configure['new_arrival']['parameters']['self']
          end

          def get_item_conditions

            @configure['item_conditions']
          end

          protected

          private

        end ### class Hardoff < Stores [END]

        class CameraNoNaniwa < Stores

          public

          def initialize
            super

            @configure = @configure['camera_no_naniwa']
          end

          def get_interval

            @configure['interval']
          end

          def get_url

            @configure['url']['self']
          end

          def url_shop_goods_search

            @configure['url']['shop']['goods']['search']
          end

          def get_new_arrival_page_limit

            @configure['new_arrival']['page_limit']
          end

          def get_new_arrival_parameters

            @configure['new_arrival']['parameters']['self']
          end

          protected

          private

        end ### class CameraNoNaniwa < Stores [END]

        class FujiyaCamera < Stores

          public

          def initialize
            super

            @configure = @configure['fujiya_camera']
          end

          def get_interval

            @configure['interval']
          end

          def get_url

            @configure['url']['self']
          end

          def get_new_arrival_urls

            @configure['new_arrival']['urls']
          end

          protected

          private

        end ### class CameraNoNaniwa < Stores [END]

      end ### class Stores < Controller [END]

      class FreeMarkets < Controller

        public

        def initialize

          super

          @configure = @configure['free_markets']
        end

        protected

        private

        class Mercari < FreeMarkets

          public

          def initialize

            super

            @configure = @configure['mercari']
          end

          def new_arrival_urls

            @configure['new_arrival']['urls']
          end

          protected

          private

        end ### class Mercari < FreeMarkets [END]

        class Rakuma < FreeMarkets

          public

          def initialize

            super

            @configure = @configure['rakuma']
          end

          def new_arrival_urls

            @configure['new_arrival']['urls']
          end

          protected

          private

        end ### class Rakuma < FreeMarkets [END]

        class Fril < FreeMarkets

          public

          def initialize

            super

            @configure = @configure['fril']
          end

          def new_arrival_urls

            @configure['new_arrival']['urls']
          end

          protected

          private

        end ### class Fril < FreeMarkets [END]

      end ### class FreeMarkets < Controller [END]

      class JapanPost < Controller

        public

        def initialize
          super

          @configure = @configure['japan_post']
        end

        protected

        private

        class EMS < JapanPost

          public

          def initialize

            super

            @configure = @configure['ems']
          end

          def delivery_details_url

            @configure['delivery_details']['url']['self']
          end

          def delivery_details_url_direct

            @configure['delivery_details']['url']['direct']['self']
          end

          def delivery_details_parameters

            @configure['delivery_details']['parameters']
          end

          protected

          private

        end ### class EMS < JapanPost [END]

      end ### class JapanPost < Controller [END]

      class Amazon < Controller

        public

        def initialize
          super

          @configure = @configure['amazon']
        end

        protected

        private

        class Developer < Amazon

          public

          def initialize

            super

            @configure = @configure['developer']
          end

          protected

          private

          class Jp < Developer

            public

            def initialize

              super

              @configure = @configure['jp']
            end

            def get_url

              @configure['url']['self']
            end

            protected

            private

          end ### class Jp < Developer

        end ### class Developer < Amazon

        class Jp < Amazon

          public

          def initialize
            super
            @configure = @configure['jp']
          end

          protected

          private

          class Mechanize < Jp

            public

            def initialize
              super
              @configure = @configure['mechanize']
            end

            def get_interval
              @configure['interval']
            end

            def get_retry_limit
              @configure['retry']['limit']
            end

            def get_item_description_url
              @configure['url']['item_description']
            end

            def get_replaces_item_description
              @configure['replaces']['item_description']
            end

            def get_replaces_feature
              @configure['replaces']['feature']
            end

            protected

            private

          end ### class Mechanize < Jp [END]

        end ### class Jp < Amazon [END]

        class MWS < Amazon

          public

          def initialize
            super

            @configure = @configure['mws']
          end

          def get_core_xml_schema
            @configure['core']['xml']['schema']
          end

          protected

          private

          class Feeds < MWS

            public

            def initialize
              super

              @configure = @configure['feeds']
            end

            def get_post_product_data_xml_schema
              @configure['post_product_data']['xml']['schema']
            end

            def get_post_product_data_action
              @configure['post_product_data']['action']
            end

            def get_post_inventory_availability_data_xml_schema
              @configure['post_inventory_availability_data']['xml']['schema']
            end

            def get_post_inventory_availability_data_action
              @configure['post_inventory_availability_data']['action']
            end

            def get_post_product_pricing_data_xml_schema
              @configure['post_product_pricing_data']['xml']['schema']
            end

            def get_post_product_pricing_data_action
              @configure['post_product_pricing_data']['action']
            end

            def get_post_product_image_data_xml_schema
              @configure['post_product_image_data']['xml']['schema']
            end

            def get_post_product_image_data_action
              @configure['post_product_image_data']['action']
            end

            protected

            private

          end ### class Feeds < MWS  [END]

        end ### class MWS < Amazon [END]

      end ### class Amazon < Controller

      class Yahoo < Controller

        public

        def initialize
          super

          @configure = @configure['yahoo']
        end

        protected

        private

        class Auth < Yahoo

          public

          def initialize
            super

            @configure = @configure['auth']
          end

          def get_authorization_url
            @configure['url']['authorization']
          end

          def get_token_url
            @configure['url']['token']
          end

          protected

          private

        end ### class Auth [END]

        class Developer < Yahoo

          public

          def initialize
            super

            @configure = @configure['developer']
          end

          def get_dashboard_url

            @configure['url']['dashboard']
          end

          protected

          private

        end ### class Auth [END]

        class Auctions < Yahoo

          public

          def initialize
            super

            @configure = @configure['auctions']
          end

          def get_url
            @configure['url']
          end

          def get_interval
            @configure['interval']
          end

          protected

          private

          class Mechanize < Auctions

            public

            def initialize
              super

              @configure = @configure['mechanize']
            end

            def get_url_selling

              @configure['url']['selling']
            end

            def get_url_sold

              @configure['url']['sold']
            end

            def get_set_cookie_url_login

              @configure['set_cookie']['url']['login']
            end
            protected

            private

          end ### class Mechanize < Auctions [END]

        end ### class Auctions < Yahoo [END]

        class Shopping < Yahoo

          public

          def initialize
            super

            @configure = @configure['shopping']
          end

          protected

          private

        end ### class Shopping [END]

        class API < Yahoo

          public

          def initialize
            super

            @configure = @configure['api']
          end

          protected

          private

          class Shopping < API

            public

            def initialize
              super

              @configure = @configure['shopping']
            end

            def get_interval
              @configure['interval']
            end

            protected

            private

          end ### class Shopping < API [END]

        end ### class API < Yahoo [END]

      end ### class Yahoo [END]

      class Ebay < Controller

        public

        def initialize
          super

          @configure = @@configure['configure']['controller']['ebay']
        end

        protected

        private

        class Mechanize < Ebay

          public

          def initialize
            super

            @configure = @configure['mechanize']
          end

          def get_url_active
            @configure['url']['active']
          end

          def get_url_sold
            @configure['url']['sold']
          end

          protected

          private

        end ### class Mechanize < Ebay [END]

        class Trading < Ebay

          public

          def initialize
            super

            @configure = @configure['trading']
          end

          def get_interval
            @configure['interval']
          end

          def get_xml_schema
            @configure['xml']['schema']['self']
          end

          def get_get_item_call_name
            @configure['get_item']['call_name']
          end

          def get_get_item_xml_schema
            @configure['get_item']['xml']['schema']
          end

          def get_add_item_call_name
            @configure['add_item']['call_name']
          end

          def get_add_item_xml_schema
            @configure['add_item']['xml']['schema']
          end

          def get_relist_item_call_name
            @configure['relist_item']['call_name']
          end

          def get_relist_item_xml_schema
            @configure['relist_item']['xml']['schema']
          end

          def get_revise_item_call_name
            @configure['revise_item']['call_name']
          end

          def get_revise_item_xml_schema
            @configure['revise_item']['xml']['schema']
          end

          def get_end_item_call_name
            @configure['end_item']['call_name']
          end

          def get_end_item_xml_schema
            @configure['end_item']['xml']['schema']
          end

          def get_upload_site_hosted_pictures_call_name
            @configure['upload_site_hosted_pictures']['call_name']
          end

          def get_upload_site_hosted_pictures_xml_schema
            @configure['upload_site_hosted_pictures']['xml']['schema']
          end

          def get_get_my_ebay_selling_call_name
            @configure['get_my_ebay_selling']['call_name']
          end

          def get_get_my_ebay_selling_xml_schema
            @configure['get_my_ebay_selling']['xml']['schema']
          end

          def get_get_session_id_call_name

            @configure['get_session_id']['call_name']
          end

          def get_get_session_id_xml_schema

            @configure['get_session_id']['xml']['schema']
          end

          def get_fetch_token_call_name

            @configure['fetch_token']['call_name']
          end

          def get_fetch_token_xml_schema

            @configure['fetch_token']['xml']['schema']
          end

          def get_get_item_transactions_call_name

            @configure['get_item_transactions']['call_name']
          end

          def get_get_item_transactions_xml_schema

            @configure['get_item_transactions']['xml']['schema']
          end

          def get_get_orders_call_name

            @configure['get_orders']['call_name']
          end

          def get_get_orders_xml_schema

            @configure['get_orders']['xml']['schema']
          end

          def get_get_user_preferences_call_name

            @configure['get_user_preferences']['call_name']
          end

          def get_get_user_preferences_xml_schema

            @configure['get_user_preferences']['xml']['schema']
          end

          def get_complete_sale_call_name

            @configure['complete_sale']['call_name']
          end

          def get_complete_sale_xml_schema

            @configure['complete_sale']['xml']['schema']
          end

          protected

          private

        end ### class Trading < Ebay [END]

        class Finding < Ebay

          public

          def initialize

            super

            @configure = @configure['finding']
          end

          def get_interval
            @configure['interval']
          end

          def get_xml_namespace
            @configure['xml']['namespace']
          end

          def get_find_completed_items_operation_name
            @configure['find_completed_items']['operation_name']
          end

          def get_find_completed_items_xml_schema
            @configure['find_completed_items']['xml']['schema']
          end

          def get_find_items_by_keywords_operation_name
            @configure['find_items_by_keywords']['operation_name']
          end

          def get_find_items_by_keywords_xml_schema
            @configure['find_items_by_keywords']['xml']['schema']
          end

          protected

          private

          class Production < Finding

            public

            def initialize
              super

              @configure = @configure['production']
            end

            def get_endpoint
              @configure['endpoint']
            end
      
            def get_url
              @configure['url']
            end

            def get_service_name
              @configure['service_name']
            end

            def get_service_version
              @configure['service_version']
            end

            def get_global_id
              @configure['global_id']
            end

            def get_security_appname
              @configure['security_appname']
            end

            def get_request_data_format
              @configure['request_data_format']
            end

            protected

            private

          end ### class Production < Finding [END]

        end ### class Finding < Ebay [END]

        class BusinessPoliciesManagement < Ebay

          public

          def initialize

            super

            @configure = @configure['business_policies_management']
          end

          def get_interval

            @configure['interval']
          end

          def get_get_seller_profiles_operation_name

            @configure['get_seller_profiles']['operation_name']
          end

          def get_get_seller_profiles_xml_schema

            @configure['get_seller_profiles']['xml']['schema']
          end

          protected

          private

          class Production < BusinessPoliciesManagement

            public

            def initialize
              super

              @configure = @configure['production']
            end

            protected

            private

          end ### class Production < Finding [END]

        end ### class Finding < Ebay [END]

      end ### class Ebay < Controller [END]

    end ### class Controller [END]

    class API < Configure
      public

      def initialize
        super
      end

      protected

      private

      class Amazon < API

        public

        def initialize
          super
        end

        protected

        private

        class MWS < Amazon

          public

          def initialize
            super

            @configure = @@configure['configure']['api']['amazon']['mws']
          end

          def get_core_schema
            @configure['core']['xml']['schema']
          end

          protected

          private

          class Developer < MWS

            public

            def initialize
              super

              @configure = @configure['developer']
            end

            protected

            private

            class Jp < Developer

              public

              def initialize
                super

                @configure = @configure['jp']
              end

              def account_id

                @configure['account']['id']
              end

              def account_access_key

                @configure['account']['access_key']
              end

              def account_secret_key

                @configure['account']['secret_key']
              end

              protected

              private

            end ### Jp < Developer [END]

          end ### Developer < MWS [END]

          class SubmitFeed < MWS

            public

            def initialize
              super

              @configure = @configure['submit_feed']
            end

            def get_product_schema
              @configure['post_product_data']['xml']['schema']
            end

            def get_product_action
              @configure['post_product_data']['action']
            end

            def get_inventory_availability_schema
              @configure['post_inventory_availability_data']['xml']['schema']
            end

            def get_inventory_availability_action
              @configure['post_inventory_availability_data']['action']
            end

            def get_product_pricing_schema
              @configure['post_product_pricing_data']['xml']['schema']
            end

            def get_product_pricing_action
              @configure['post_product_pricing_data']['action']
            end

            def get_product_image_schema
              @configure['post_product_image_data']['xml']['schema']
            end

            def get_product_image_action
              @configure['post_product_image_data']['action']
            end

            protected

            private

          end ### class SubmitFeed [END]

          class Reports < MWS

            public

            def initialize
              super

              @configure = @configure['reports']
            end

            def get_merchant_listings_action
              @configure['get_merchant_listings_data']['action']
            end

            protected

            private

          end ### class Reports [END]

        end ### class MWS [END]

      end ### class Amazon [END]

      class Yahoo < API

        public

        def initialize
          super

          @configure = @@configure['configure']['api']['yahoo']
        end

        protected

        private

        class Auctions < Yahoo

          public

          def initialize
            super

            @configure = @configure['auctions']
          end

          def get_endpoint
            @configure['endpoint']
          end
      
          def get_url
            @configure['url']
          end

          def get_search

            @configure['search']
          end

          protected

          private

        end ### class Auctions < Yahoo [END]

        class Shopping < Yahoo

          public

          def initialize
            super

            @configure = @configure['shopping']
          end

          def get_endpoint
            @configure['endpoint']
          end

          def get_url
            @configure['url']
          end

          protected

          private

          class Circus < Shopping
 
            public
 
            def initialize
              super
 
              @configure = @configure['circus']
            end
 
            def get_endpoint
              @configure['endpoint']
            end
 
            def get_url
              @configure['url']
            end
 
            protected
 
            private
 
          end ### class Circus < Yahoo [END]

        end ### class Shopping < Yahoo [END]

      end ### class Yahoo [END]

      class Ebay < API

        public

        def initialize
          super

          @configure = @@configure['configure']['api']['ebay']
        end

        def get_compatibility_level
          @configure['compatibility_level']
        end

        protected

        private

        class Trading < Ebay

          public

          def initialize
            super

            @configure = @configure['trading']
          end

          def get_xml_schema
            @configure['xml']['schema']['self']
          end

          protected

          private

          class Production < Trading

            public

            def initialize
              super

              @configure = @configure['production']
            end

            def get_endpoint
              @configure['endpoint']
            end
      
            def get_url
              @configure['url']
            end

            def get_dev_name
              @configure['dev_name']
            end

            def get_app_name
              @configure['app_name']
            end

            def get_cert_name
              @configure['cert_name']
            end

            def get_ru_name

              @configure['ru_name']
            end

            def get_token

              @configure['token']
            end

            protected

            private

          end ### class Production < Trading [END]

        end ### class Trading < Ebay [END]

        class Finding < Ebay

          public

          def initialize

            super

            @configure = @configure['finding']
          end

          protected

          private

          class Production < Finding

            public

            def initialize
              super

              @configure = @configure['production']
            end

            def get_endpoint
              @configure['endpoint']
            end
      
            def get_url
              @configure['url']
            end

            def get_service_name
              @configure['service_name']
            end

            def get_service_version
              @configure['service_version']
            end

            def get_global_id
              @configure['global_id']
            end

            def get_security_appname
              @configure['security_appname']
            end

            def get_request_data_format
              @configure['request_data_format']
            end

            protected

            private

          end ### class Production < Finding [END]

        end ### class Finding < Ebay [END]

        class BusinessPoliciesManagement < Ebay

          public

          def initialize

            super

            @configure = @configure['business_policies_management']
          end

          protected

          private

          class Production < BusinessPoliciesManagement

            public

            def initialize

              super

              @configure = @configure['production']
            end

            def endpoint

              @configure['endpoint']
            end

            def url

              @configure['url']
            end

            def service_name

              @configure['service_name']
            end

            def service_version

              @configure['service_version']
            end

            def global_id

              @configure['global_id']
            end

            def request_data_format

              @configure['request_data_format']
            end

            protected

            private

          end ### class Production < BusinessPoliciesManagement [END]

        end ### class BusinessPoliciesManagement < Ebay [END]

      end ### class Ebay < API [END]

      class ChatWork < API

        public

        def initialize
          super

          @configure = @@configure['configure']['api']['chatwork']
        end

        def get_version

          @configure['version']
        end

        def get_url

          @configure['url']
        end

        def rooms

          @configure['rooms']
        end

        def get_interval_notify

          @configure['default']['interval']['notify']
        end

        def get_ratelimit

          @configure['default']['ratelimit']
        end

        def get_default_token

          @configure['default']['token']
        end

        def get_default_room_id

          @configure['default']['room_id']
        end

        def room_indices

          @configure['default']['room_indices']
        end

        protected

        private

      end ### class ChatWork [END]

    end ### class API [END]

    class Database < Configure

      public

      def initialize
        super
      end

      protected

      private

      class MySQL < Database

        public

        def initialize
          super

          @configure = @@configure['configure']['database']['mysql']
        end

        def get_host

          if ENV['ML_DB_HOST']

            ENV['ML_DB_HOST']
          else

            @configure['hostname']
          end
        end

        def get_database

          @configure['database']
        end

        def get_user

          @configure['username']
        end

        def get_password

          @configure['password']
        end

        protected

        private

        class M < MySQL

          public

          def initialize

            super

            @configure = @configure['m']
          end

          def get_host

            if ENV['ML_DB_HOST']

              ENV['ML_DB_HOST']
            else

              @configure['hostname']
            end
          end

          def get_database

            @configure['database']
          end

          def get_user

            @configure['username']
          end

          def get_password

            @configure['password']
          end

          protected

          private

        end ### class M < MySQL [END]

      end ### class MySQL < Database [END]

    end ### class Database [END]

    class Builder < Configure
      public

      def initialize
        super
        @configure = @@configure['configure']['builder']
      end

      protected

      private

      class MySQL < Builder

        public

        def initialize
          super

          @configure = @configure['mysql']
        end

        def get_tables
          @configure['tables']
        end

        protected

        private

        class Item < MySQL

          public

          def initialize
            super

            @configure = @configure['item']
          end

          def get_state

            @configure['state']
          end

          def get_interval

            @configure['interval']
          end

          protected

          private

        end ### class Item [END]

        class Bids < MySQL

          public

          def initialize
            super

            @configure = @configure['bids']
          end

          def get_state

            @configure['state']
          end

          def get_interval

            @configure['interval']
          end

          protected

          private

        end ### class Item [END]

      end ### class MySQL [END]

    end ### class Builder [END]

    class Driver < Configure

      class Web < Driver

        class Mechanize < Web
          public

          def initialize
            @configure = @@configure['configure']['driver']['web']['mechanize']
          end

          def get_user_agent_aliases
            @configure['user_agent_aliases']
          end

          protected

          private

        end ### Mechanize [END]

        class Selenium < Web
          public

          def initialize
            @configure = @@configure['configure']['driver']['web']['selenium']
          end

          def get_timeout
            @configure['timeout']
          end

          def headless?
            @configure['headless']
          end

          protected

          private

        end ### Selenium [END]

        class Proxies < Web

          public

          def initialize
            @configure = @@configure['configure']['driver']['web']['proxies']
          end

          protected

          private

          class MPP < Proxies

            public

            def initialize
              super
              @configure = @configure['mpp']
            end

            def get_api_key
              @configure['api_key']
            end

            def get_api_url
              @configure['url']['api']
            end

            protected

            private

          end ### MPP [END]

        end ### Proxies [END]

      end ### module Web [END]

    end ### class Driver

    class Formatter < Configure

      public

      def initialize
        super

        @configure = @@configure['configure']['formatter']
      end

      protected

      private

      class Exception < Formatter
        public

        def initialize
          super
        end

        protected

        private

      end ### class Exception < Formatter [END]

      class ChatWork < Formatter

        public

        def initialize
          super

          @configure = @configure['chatwork']
        end

        def request

          @configure['request']
        end

        def approve

          @configure['approve']
        end

        def discard

          @configure['discard']
        end

        protected

        private

      end ### class ChatWork < Formatter

      class Analysis < Formatter
        public

        def initialize
          super

          @configure = @configure['analysis']
        end

        def report
          @configure['report']
        end

        protected

        private

        class User < Analysis

          public

          def initialize
            super

            @configure = @configure['user']
          end

          def report
            @configure['report']
          end

          protected

          private

        end ### class User < Analysis [END]

        class System < Analysis

          public

          def initialize
            super

            @configure = @configure['system']
          end

          def report
            @configure['report']
          end

          protected

          private

        end ### class User < Analysis [END]

      end ### class Analysis < Formatter [END]

      class Orchestrator < Formatter
        public

        def initialize
          super

          @configure = @configure['orchestrator']
        end

        protected

        private

        class Yahoo < Orchestrator

          public

          def initialize
            super

            @configure = @configure['yahoo']
          end

          protected

          private

          class Auctions < Yahoo

            public

            def initialize
              super

              @configure = @configure['auctions']
            end

            def conduct 
              @configure['conduct']
            end

            protected

            private

          end ### class Auctions < Yahoo [END]

          class Shopping < Yahoo

            public

            def initialize
              super

              @configure = @configure['shopping']
            end

            def conduct 
              @configure['conduct']
            end

            protected

            private

          end ### class Shopping < Yahoo [END]

        end ### class Yahoo < Orchestrator [END]

        class Amazon < Orchestrator

          public

          def initialize
            super

            @configure = @configure['amazon']
          end

          protected

          private

          class Jp < Amazon

            public

            def initialize
              super

              @configure = @configure['jp']
            end

            def conduct
              @configure['conduct']
            end

            protected

            private

          end ### class Jp < Amazon [END]

        end ### class Amazon < Orchestrator [END]

        class Ebay < Orchestrator

          public

          def initialize
            super

            @configure = @configure['ebay']
          end

          protected

          private

          class Us < Ebay

            public

            def initialize
              super

              @configure = @configure['us']
            end

            def conduct
              @configure['conduct']
            end

            protected

            private

          end ### class Us < Ebay [END]

        end ### class Ebay < Orchestrator [END]

      end ### class Orchestrator [END]

      class Controller < Formatter

        public

        def initialize
          super

          @configure = @configure['controller']
        end

        protected

        private

        class Monitor < Controller

          public

          def initialize
            super

            @configure = @configure['monitor']
          end

          protected

          private

          class Process < Monitor

            public

            def initialize
              super

              @configure = @configure['process']
            end

            def reboot

              @configure['reboot']
            end

            def notice

              @configure['notice']
            end

            protected

            private

          end ### class Process < Monitor [END]

        end ### class Monitor < Controller [END]

        class Stores < Controller

          public

          def initialize
            super

            @configure = @configure['stores']
          end

          protected

          private

          class Kitamura < Stores

            public

            def initialize
              super

              @configure = @configure['kitamura']
            end

            def new_arrival
              @configure['new_arrival']
            end

            protected

            private

          end ### class Kitamura < Stores [END]

          class CameraNoNaniwa < Stores

            public

            def initialize
              super

              @configure = @configure['camera_no_naniwa']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class CameraNoNaniwa < Stores [END]

          class MapCamera < Stores

            public

            def initialize
              super

              @configure = @configure['map_camera']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class MapCamera < Stores [END]

          class ChampCamera < Stores

            public

            def initialize
              super

              @configure = @configure['champ_camera']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class ChampCamera < Stores [END]

          class Hardoff < Stores

            public

            def initialize

              super

              @configure = @configure['hardoff']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class Hardoff < Stores [END]

          class FujiyaCamera < Stores

            public

            def initialize
              super

              @configure = @configure['fujiya_camera']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class FujiyaCamera < Stores [END]

        end ### class Stores < Controller [END]

        class FreeMarkets < Controller

          public

          def initialize

            super

            @configure = @configure['free_markets']
          end

          protected

          private

          class Mercari < FreeMarkets

            public

            def initialize

              super

              @configure = @configure['mercari']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class Mercari < FreeMarkets [END]

          class Rakuma < FreeMarkets

            public

            def initialize

              super

              @configure = @configure['rakuma']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class Rakuma < FreeMarkets [END]

          class Fril < FreeMarkets

            public

            def initialize

              super

              @configure = @configure['fril']
            end

            def new_arrival

              @configure['new_arrival']
            end

            protected

            private

          end ### class Fril < FreeMarkets [END]

        end ### class FreeMarkets < Controller [END]

        class Support < Controller

          public

          def initialize

            super

            @configure = @configure['support']
          end

          def contact

            @configure['contact']
          end

          protected

          private

        end ### class Support < Controller [END]

        class Yahoo < Controller

          public

          def initialize
            super

            @configure = @configure['yahoo']
          end

          protected

          private

          class API < Yahoo

            public

            def initialize
              super

              @configure = @configure['api']
            end

            protected

            private

            class Auctions < API

              public

              def initialize
                super

                @configure = @configure['auctions']
              end

              def search

                @configure['search']
              end

              def my_selling_list

                @configure['my_selling_list']
              end

              def my_close_list

                @configure['my_close_list']
              end

              protected

              private

            end ### class Auctions < API [END]

          end ### class API < Yahoo [END]

          class Mechanize < Yahoo

            public

            def initialize
              super

              @configure = @configure['mechanize']
            end

            def get_captcha

              @configure['get_captcha']
            end

            def set_cookie

              @configure['set_cookie']
            end

            def purge_expired_cookies

              @configure['purge_expired_cookies']
            end

            protected

            private

          end ### class Mechanize < Yahoo [END]

          class Developer < Yahoo

            public

            def initialize
              super

              @configure = @configure['developer']
            end

            def get_application

              @configure['get_application']
            end

            def create_application

              @configure['create_application']
            end

            def update_application

              @configure['update_application']
            end

            protected

            private

          end ### class Developer < Yahoo [END]

          class Auth < Yahoo

            public

            def initialize
              super

              @configure = @configure['auth']
            end

            def manage_token

              @configure['manage_token']
            end

            protected

            private

          end ### class Auth < Yahoo [END]

          class Auctions < Yahoo

            public

            def initialize
              super

              @configure = @configure['auctions']
            end

            def add_item
              @configure['add_item']
            end

            def resubmit_item
              @configure['resubmit_item']
            end

            def end_item

              @configure['end_item']
            end

            def place_bids

              @configure['place_bids']
            end

            def place_value_comment

              @configure['place_value_comment']
            end

            def get_shipping_information

              @configure['get_shipping_information']
            end

            protected

            private

          end ### class Auctions < Yahoo [END]

          class Shopping < Yahoo

            public

            def initialize
              super

              @configure = @configure['shopping']
            end

            def add_item
              @configure['add_item']
            end

            def end_item
              @configure['end_item']
            end

            protected

            private

          end ### class Shopping < Yahoo [END]

        end ### class Yahoo [END]

        class Ebay < Controller

          public

          def initialize
            super

            @configure = @configure['ebay']
          end

          protected

          private

          class Us < Ebay

            public

            def initialize
              super

              @configure = @configure['us']
            end

            def add_item

              @configure['add_item']
            end

            def revise_item

              @configure['revise_item']
            end

            def relist_item

              @configure['relist_item']
            end

            def end_item

              @configure['end_item']
            end

            def get_session_id

              @configure['get_session_id']
            end

            def fetch_token

              @configure['fetch_token']
            end

            def get_item_transactions

              @configure['get_item_transactions']
            end

            protected

            private

          end ### class Us < Ebay [END]

        end ### class Ebay < Controller [END]

        class Amazon < Controller

          public

          def initialize
            super

            @configure = @configure['amazon']
          end

          protected

          private

          class Developer < Amazon

            public

            def initialize
              super

              @configure = @configure['developer']
            end

            protected

            private

            class Jp < Developer

              public

              def initialize
                super

                @configure = @configure['jp']
              end

              def register

                @configure['register']
              end

              protected

              private

            end ### class Developer < Amazon [END]

          end ### class Jp < Developer [END]

          class MWS < Amazon

            public

            def initialize
              super

              @configure = @configure['mws']
            end

            protected

            private

            class Feeds < MWS

              public

              def initialize
                super

                @configure = @configure['feeds']
              end

              def post_product_data
                @configure['post_product_data']
              end

              def post_inventory_availability_data
                @configure['post_inventory_availability_data']
              end

              def post_product_pricing_data
                @configure['post_product_pricing_data']
              end

              def post_product_image_data
                @configure['post_product_image_data']
              end

              protected

              private

            end ### class Feeds < MWS [END]

            class Orders < MWS

              public

              def initialize
                super

                @configure = @configure['orders']
              end

              def get_shipping_information 

                @configure['get_shipping_information']
              end

              protected

              private

            end ### class Orders < MWS [END]

          end ### class MWS < Amazon [END]

        end ### class Amazon < Controller [END]

      end ### class Controller [END]

    end ### class Formatter [END]
  
  end ### class Configure [END]

end ### module TWEyes [END]
