require_relative '../lib/application'

module TWEyes
  class Daemon < Application
    public

    def initialize
      super
    end

    def run(myname)
      @logger[:system].open(File.basename(__FILE__))

      begin

        @controller[:daemon][:signal].daemonize(@logger, myname)
      rescue TWEyes::Exception::Controller::Daemon => e

        @logger[:system].err(@formatter[:exception].handle(e))
        @api[:chatwork].push_message(
          @configure[:api][:chatwork].room_indices['system'],
          @formatter[:exception].handle(e)
        )
        exit(1)
      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
    end

    def stop(myname)
      @logger[:system].open(File.basename(__FILE__))

      begin 
        @controller[:daemon][:signal].nodaemonize(@logger, myname)
      rescue TWEyes::Exception::Controller::Daemon => e
        @logger[:system].err(@formatter[:exception].handle(e))
      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
    end

    def wait(interval)
      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(sprintf("%s seconds waiting", interval))
      sleep(interval)
    end

    protected

    private

  end ### class Daemon [END]
end
