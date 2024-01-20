module TWEyes

  module Database ### 名前空間

    class MySQL

      attr_accessor :name

      public

      def initialize(configure)

        super()

        @client = nil
        @name   = nil

        @configure = configure
      end

      def escape(string)

        result = nil

        connect
        result  = @client.escape(string)
        disconnect

        result
      rescue => e

        raise(
          TWEyes::Exception::Database::MySQL.new(
            e.class,
            e.backtrace,
          ), e)
      end

      def query(query)

        result = nil

        Retryable.retryable(
          on: [
            Mysql2::Error,
          ],
          tries: 3
        ) do |retries, exception|

pp query

          connect
          result = @client.query(query)
          disconnect
        end

        result
      rescue => e

        raise(
          TWEyes::Exception::Database::MySQL.new(
            e.class,
            e.backtrace,
          ), e)
      end

      protected

      private

      def connect

        @client = Mysql2::Client.new(
          host:     @configure[:database][:mysql][@name || :self].get_host,
          database: @configure[:database][:mysql][@name || :self].get_database,
          username: @configure[:database][:mysql][@name || :self].get_user,
          password: @configure[:database][:mysql][@name || :self].get_password
        )
      end

      def disconnect

        @client.close
      end

    end ### class MySQL [END]
  
  end
end
