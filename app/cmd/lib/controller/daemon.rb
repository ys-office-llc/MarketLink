module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    class Daemon

      public

      def initialize(configure)
        super()

        @configure = configure
      end

      def initializeX
      end

      protected

      def daemonize(logger, myname)

        begin

          pid_file = sprintf("%s/%s",
            @configure[:system][:directory].get_run_path,
            myname
          )

          pid = File.read(pid_file).to_i
          if Process.kill(0, pid) === 1

            raise("#{myname} already running")
          end
        rescue Errno::ENOENT, Errno::ESRCH
        end
        Process.daemon
        open(pid_file, 'w') do |f|
          f.printf("%d", $$)
          logger[:system].info(sprintf("Daemonize: %s, %s)", myname, $$))
        end
      rescue => e

        raise(
          TWEyes::Exception::Controller::Daemon.new(
            e.class,
            e.backtrace
          ), e)
      end

      def nodaemonize(logger, myname)
        logger[:system].info(sprintf("No Daemonize: %s", myname))
      rescue => e
        raise(
          TWEyes::Exception::Controller::Daemon.new(
            e.class,
            e.backtrace
          ), e)
      end

      private

      class SignalX < Daemon

        public

        def initialize(configure)
          super
        end

        def daemonize(logger, myname)
          register(logger)
          super(logger, myname)
        end

        def nodaemonize(logger, myname)
          super(logger, myname)
        end

        private

        def register(logger)
          Signal.trap(:HUP) { _hup(logger) }
          Signal.trap(:INT) { _int(logger) }
          Signal.trap(:TERM) { _term(logger) }
        end

        def _hup(logger)
          logger[:system].info('Caught SIGHUP. terminated')
          exit! 0
        end

        def _int(logger)
          logger[:system].info('Caught SIGINT. terminated')
          exit! 0
        end

        def _term(logger)
          logger[:system].info('Caught SIGTERM. terminated')
          exit! 0
        end

      end ### class Signal [END]

    end ### class Daemon [END]

  end ### module Controller [END]

end ### module TWEyes [END]
