require_relative '../lib/application'

module TWEyes

  class Archive < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def run

      @logger[:system].open(File.basename(__FILE__))

      FileUtils.mkpath(
        sprintf("%s/%s",
          @configure[:system][:directory].get_archive_path,
          @configure[:system][:date].get_date_suffix
        )
      )
  
      @builder[:mysql][:common].show_tables.each do |table|
        f = open(sprintf("%s/%s/%s.sql",
              @configure[:system][:directory].get_archive_path,
              @configure[:system][:date].get_date_suffix,
              table.values.pop), 'w'
        )
        @builder[:mysql][:common]
        .query(sprintf("SELECT * FROM %s", table.values.pop)).each do |record|

          ARGV.each do |k|

            record.delete(k)
          end

          f.printf(
            "INSERT INTO %s (%s) VALUES (%s);\n",
             table.values.pop,
             record.keys.join(','),
             record.values.map do |e|

               case e
               when String
                 ### mysql2のescapeメソッド
                 sprintf("'%s'", @builder[:mysql][:common].escape(e))
               else
                 sprintf("'%s'", e)
               end

             end.join(',')
          )
        end
      end
    rescue => e
      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    protected

    private

  end ### class Archive [END]
end
