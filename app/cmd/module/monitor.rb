require_relative '../lib/application'

module TWEyes

  class Monitor < Application

    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def clean(opts)

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )
      @controller[:monitor][:process][:self].initializeX(
        @api,
        @formatter,
        @controller[:unix][:command][:self]
      )
      @controller[:monitor][:process][:self]
      .kill_the_zombies(opts)
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    def ping(opts)

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )
      @controller[:monitor][:process][:self].initializeX(
        @api,
        @formatter,
        @controller[:unix][:command][:self]
      )

      begin

        id = @builder[:mysql][:monitor][:process][:self]
             .todays_registered?

        if id === 0

          @builder[:mysql][:monitor][:process][:self].insert(
            @controller[:monitor][:process][:self].ping(opts)
          )
        else

          @builder[:mysql][:monitor][:process][:self].update(
            id,
            @controller[:monitor][:process][:self].ping(opts)
          )
        end
      rescue TWEyes::Exception::Controller::Monitor => e

        @api[:chatwork].push_message(0,
          @formatter[:exception].handle(e))
      end
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    protected

    private

  end ### class Monitor [END]
end
