module TWEyes

  module Formatter ### 名前空間

    class ChatWork

      public

      def initialize(configure)
        super()

        @configure = configure
      end

      def request(account)

        if account[:message].to_s.size.zero?

          sprintf(
            @configure[:formatter][:chatwork][:self].request['success'],
            account
          )
        else

          sprintf(
            @configure[:formatter][:chatwork][:self].request['failure'],
            account
          )
        end
      end

      def approve(account)

        if account[:message].to_s.size.zero?

          sprintf(
            @configure[:formatter][:chatwork][:self].approve['success'],
            account
          )
        else

          sprintf(
            @configure[:formatter][:chatwork][:self].approve['failure'],
            account
          )
        end
      end

      def discard(account)

        if account[:message].to_s.size.zero?

          sprintf(
            @configure[:formatter][:chatwork][:self].discard['success'],
            account
          )
        else

          sprintf(
            @configure[:formatter][:chatwork][:self].discard['failure'],
            account
          )
        end
      end

      protected

      private

    end ### class ChatWork [END]
  
  end
end
