module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module Monitor ### 名前空間用なので機能を持たせるとバグるよ

      class ProcessX

        LIVE = 0
        DEAD = 1

        public

        def initialize(configure)
          super()

          @configure = configure
          @api       = nil
          @formatter = nil
          @cmd       = nil
        end

        def initializeX(api, formatter, cmd)

          @api       = api
          @formatter = formatter
          @cmd       = cmd
        end

        def kill_the_zombies(opts)

          results = {}
          cmd     = nil
          pid     = nil
          ppid    = nil

          results = find_the_zombies
          if results[:status].zero? and
             results[:stdout].size > 0

            results[:stdout].split(/\n/).each do |s|

              matches = s.match(/^#{ENV['USER']}\s+(.+)\sZ\s+(\d+)\s+(\d+)$/)

              if matches

                cmd, ppid, pid = *matches

                begin

                  if Process.kill(9, pid.to_i).zero?

                    raise("PID #{pid} process couldn't be ended")
                  end
                rescue Errno::EPERM

                  raise(
                    "CMD: #{cmd}, PPID: #{ppid}, PID: #{pid}"+
                    " process couldn't be ended"
                  )
                end
              end
            end
          else

            raise("#{results[:stderr]}")
          end
        rescue => e

          raise(TWEyes::Exception::Controller::Monitor::Process.new(
              e.class,
              e.backtrace,
              __method__
            ), e)

        end

        def ping(opts)

          pid    = nil
          key    = nil
          status = {}

          @configure[:controller][:monitor][:process][:self]
          .members.each do |member|

            unless member['monitoring']

              next
            end

            begin

              key = member['name'].to_snake.sub('.rb', '').to_sym
              pid = File.read(sprintf("%s/%s",
                      @configure[:system][:directory].get_run_path,
                      member['name']
                    )).to_i

              ### kill -0 (死活確認)
              if Process.kill(0, pid) === 1

                status.store(key, LIVE)
              end
            ### "No such process"
            rescue Errno::ESRCH => e

              if opts[:notice]

                @api[:chatwork].push_message(0,
                  @formatter[:controller][:monitor][:process][:self]
                  .notice({process: member['name']})
                )
              end

              if opts[:reboot]

                @api[:chatwork].push_message(0,
                  @formatter[:controller][:monitor][:process][:self]
                  .reboot(reboot(member['name']))
                )
              end

              status.store(key, DEAD)
            rescue Errno::ENOENT => e

              status.store(key, DEAD)
            end
          end

          status
        rescue => e

          raise(TWEyes::Exception::Controller::Monitor::Process.new(
              e.class,
              e.backtrace,
              __method__
            ), e)
        end

        protected

        private

        def find_the_zombies

          @cmd.exec(
            'ps -A -ouser,cmd,stat,ppid,pid'
          )
        end

        def reboot(proc)

          @cmd.exec(
            case proc
            when /manager\.rb/

              sprintf("%s/%s all detach all",
                @configure[:system][:directory].get_daemon_root,
                proc
              )
            else 

              sprintf("%s/%s detach",
                @configure[:system][:directory].get_daemon_root,
                proc
              )
            end
          )
        end
    
      end ### class ProcessX [END]

    end ### module Monitor [END]

  end ### module Controller [END]

end ### module TWEyes [END]
