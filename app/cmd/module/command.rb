require_relative '../lib/application'

module TWEyes

  class Command < Application

    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
      @host = @configure[:system][:net].get_host
    end

    def boot

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @configure[:controller][:monitor][:process][:self]
      .members.each do |member|

        if @host.match(member['when'])

          cmd = sprintf("%s/%s %s",
            @configure[:system][:directory].get_daemon_root,
            member['name'],
            member['args']
          )

          puts cmd
          @controller[:unix][:command][:self].exec(cmd)
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def halt

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @configure[:controller][:monitor][:process][:self]
      .members.each do |member|

        if @host.match(member['when'])

          path = sprintf(
            "%s/%s",
            @configure[:system][:directory].get_run_path,
            member['name']
          )

          puts path

          if File.exist?(path)

            pid = File.read(path).to_i

            begin

              if Process.kill(0, pid) === 1

                Process.kill(15, pid)
                FileUtils.rm(path)
                @controller[:unix][:command][:self].exec(
                  'killall -HUP phantomjs'
                )
               end
            ### "No such process"
            rescue Errno::ESRCH => e 

              pp e.class, e.message, pid, path
            end
          end
        end
      end
    rescue => e

      @logger[:system].crit(
        @formatter[:exception].handle(e)
      )
      @api[:chatwork].push_message(
        @configure[:api][:chatwork]
        .room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    protected

    private

  end ### class Command [END]
end
