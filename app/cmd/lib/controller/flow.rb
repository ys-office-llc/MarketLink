module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    class Flow

      public

      def initialize(configure)

        super()

        @configure = configure
        @logger    = nil
      end

      def initializeX(logger)

        @logger = logger
      end

      def interrupt(unique_hash)

        spool_directory = sprintf(
          "%s/%s/%s",
          @configure[:system][:directory].get_spool_path,
          __method__,
          @configure[:system][:user].account['id']
        )

        FileUtils.mkdir_p(spool_directory)

        hexdigest  = Digest::MD5.hexdigest(unique_hash.to_s)
        spool_path = sprintf("%s/%s", spool_directory, hexdigest)
    
        if File.exists?(spool_path)

          return true
        else

          f = open(spool_path, 'w')
          @logger[:user].info(sprintf(
            "%s::%s: %s: %s",
            self.class,
            __method__,
            unique_hash.to_s,
            hexdigest
          ))
          f.printf("%s", unique_hash.to_s)
          f.close
    
          return false
        end

      end

      protected

      def get_class_suffix

        self.class
            .to_s
            .split('::')[-2..-1]
            .map{|e|e.downcase}
            .join('_')
      end

      private

      class Contract < Flow

        public

        def initialize(configure)

          super
        end

        def permit?

          if @configure[:system][:user]
             .account['account_authority_level_id'] > 0 and
             @configure[:system][:user]
             .account['account_contract_id'] > 0 and
             @configure[:system][:user]
             .account['account_is_payment_id'] > 0

            true
          else

            false
          end
        end

        protected

        private

        class MarketScreening < Contract

          public

          def initialize(configure)

            super
          end

          def permit?

            if @configure[:system][:user]
               .account['market_screening']
               .to_s
               .match(/^enable$/)

              true
            else

              false
            end
          end

          protected

          private

        end ### class MarketScreening < Contract

        class MerchandiseManagement < Contract

          public

          def initialize(configure)

            super
          end

          def permit?

            if @configure[:system][:user]
               .account['merchandise_management']
               .to_s
               .match(/^enable$/)

              true
            else

              false
            end
          end

          protected

          private

        end ### class MerchandiseManagement < Contract

      end ### class Contract < Flow

      class ChatWork < Flow

        public

        def initialize(configure)

          super

          @self   = get_class_suffix
          @parent = @self.split('_')[0].to_sym
          @child  = @self.split('_')[1].to_sym
        end

        def permit?

          if @configure[:system][:user].account[
               sprintf("%s_api_admin_token", @child)
             ].to_s.size > 0 and
             @configure[:system][:user].account[
               sprintf("%s_api_tokens", @child)
             ].to_s.size > 0 and
             @configure[:system][:user].account[
               sprintf("%s_account_id", @child)
             ].to_s.size > 0 and
             @configure[:system][:user].account[
               sprintf("%s_room1_id", @child)
             ].to_s.size > 0 and
             @configure[:system][:user].account[
               sprintf("%s_room2_id", @child)
             ].to_s.size > 0 and
             @configure[:system][:user].account[
               sprintf("%s_room3_id", @child)
             ].to_s.size > 0 and
             @configure[:system][:user].account[
               sprintf("%s_room4_id", @child)
             ].to_s.size > 0 and
             @configure[:system][:user].account[
               sprintf("%s_room5_id", @child)
             ].to_s.size > 0

            true
          else 

            false
          end
        end

        protected

        private

      end ### class ChatWork < Flow

      class Yahoo < Flow

        public

        def initialize(configure)

          super

          @self        = get_class_suffix
          @parent      = @self.split('_')[0].to_sym
          @child       = @self.split('_')[1].to_sym
          @grand_child = @self.split('_')[2].to_sym
        end

        protected

        def get_class_suffix

          self.class
              .to_s
              .split('::')[-3..-1]
              .map{|e|e.downcase}
              .join('_')
        end

        private

        class Auctions < Yahoo

          public

          protected

          def permit?

            if @configure[:system][:user].account[
                 sprintf("%s_%s_account", @parent, @grand_child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%s_%s_password", @parent, @grand_child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%sapis_%s_appid", @parent, @grand_child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%sapis_%s_secret", @parent, @grand_child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%s_%s_%s_cookies_is_set",
                   @parent,
                   @child,
                   @grand_child
                 )
               ] > 0

              true
            else

              false
            end
          end

          def set_cookie_permit?

            if @configure[:system][:user].account[
                 sprintf("%s_%s_account", @parent, @grand_child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%s_%s_password", @parent, @grand_child)
               ].to_s.size > 0

              true
            else

              false
            end
          end

          private

          class Seller < Auctions

            public

            def permit?

              super
            end

            def set_cookie_permit?

              super
            end

            protected

            private

          end ### class Seller < Auctions

          class Buyer < Auctions

            public

            def permit?

              super
            end

            def set_cookie_permit?

              super
            end

            protected

            private

          end ### class Buyer < Auctions

        end ### class Auctions < Yahoo

        protected

        private

      end ### class Yahoo < Flow

      class Ebay < Flow

        public

        def initialize(configure)

          super

          @self   = get_class_suffix
          @parent = @self.split('_')[0].to_sym
          @child  = @self.split('_')[1].to_sym
        end

        class Us < Ebay

          public

          def permit?

            if @configure[:system][:user].account[
                 sprintf("%s_%s_auth_token", @parent, @child)
               ].to_s.size > 0

              true
            else

              false
            end
          end

          protected

          private

        end ### class Jp < Amazon

        protected

        private

      end ### class Amazon < Flow

      class Amazon < Flow

        public

        def initialize(configure)

          super

          @self   = get_class_suffix
          @parent = @self.split('_')[0].to_sym
          @child  = @self.split('_')[1].to_sym
        end

        class Jp < Amazon

          public

          def permit?

            if @configure[:system][:user].account[
                 sprintf("%s_%s_marketplace_id", @parent, @child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%s_%s_merchant_id", @parent, @child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%s_%s_access_key", @parent, @child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%s_%s_secret_key", @parent, @child)
               ].to_s.size > 0 and
               @configure[:system][:user].account[
                 sprintf("%s_%s_auth_token", @parent, @child)
               ].to_s.size > 0

              true
            else

              false
            end
          end

          protected

          private

        end ### class Jp < Amazon

        protected

        private

      end ### class Amazon < Flow

    end ### class Flow

  end ### module Controller [END]

end ### module TWEyes [END]
