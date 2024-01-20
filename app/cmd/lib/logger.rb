module TWEyes

  module LoggerX

    class System

      public

      def initialize(configure)
        super()

        @configure = configure
      end

      def open(tag)
        if Syslog.opened?
          Syslog.close
        end

        unless Syslog.opened?
          Syslog.open(tag, Syslog::LOG_PID, Syslog::LOG_USER)
        end
      end

      def debug(string)
        Syslog.debug(string)
      end

      def info(string)
        Syslog.info(string)
      end

      def notice(string)
        Syslog.notice(string)
      end

      def warning(string)
        Syslog.warning(string)
      end

      def err(string)
        Syslog.err(string)
      end

      def crit(string)
        Syslog.crit(string)
      end

      def alert(string)
        Syslog.alert(string)
      end

      def emerg(string)
        Syslog.emerg(string)
      end

      protected

      private

      def close(tag)
        if Syslog.opened?
          Syslog.close
        end
      end

    end

    class User

      attr_accessor :channel

      public

      def initialize(configure)
        super()

        @configure = configure
        @channel = nil
      end

      def open
        log_path    = @configure[:system][:directory].get_log_path
        date_suffix = @configure[:system][:date].get_date_suffix
        account_id  = @configure[:system][:user].account['id']
        account     = @configure[:system][:user].account['user_name']

        FileUtils.mkdir_p(sprintf("%s/%s", log_path, account_id))
        @channel = Logger.new(sprintf("%s/%s/%s_%s.log",
                     log_path,
                     account_id,
                     account,
                     date_suffix
                   ))
      end

      def debug(string)
        @channel.debug(string)
      end

      def info(string)
        @channel.info(string)
      end

      def warn(string)
        @channel.warn(string)
      end

      def error(string)
        @channel.error(string)
      end

      def fatal(string)
        @channel.fatal(string)
      end

      def unknown(string)
        @channel.unknown(string)
      end

      protected

      private

    end
  
  end
end
