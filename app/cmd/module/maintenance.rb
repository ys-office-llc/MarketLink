require_relative '../lib/application'

module TWEyes

  class Maintenance < Application

    public

    def initialize(user_id)

      super()

      @user_id = user_id

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def delete 

      describe = []
      table    = nil

      @logger[:system].open(File.basename(__FILE__))

      @builder[:mysql][:common]
      .show_tables
      .each do |t|

        table    = t.values[0]
        describe = @builder[:mysql][:common]
                   .desc_table(
                     table 
                   )

        if describe.include?('user_id')
       
          @builder[:mysql][:common]
          .query(
            sprintf(
              "UPDATE %s SET deleted=1 WHERE user_id='%s' AND deleted=0",
              table,
              @user_id
            )
          )
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

  end ### class Maintenance [END]
end
