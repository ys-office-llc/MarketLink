module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    class Support

      public

      def initialize(configure)
        super()

        @configure = configure
        @logger    = nil
      end

      def initializeX
      end

      public

      def contact(message)

        message.merge(
          @configure[:system][:user].account.symbolize_keys
        )
      rescue => e

        raise(TWEyes::Exception::Controller::Support.new(
          e.class,
          e.backtrace,
          __method__
        ), e)
      end

      protected

      private

    end ### class Support [END]

  end ### module Controller [END]

end ### module TWEyes [END]
