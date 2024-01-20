require_relative '../lib/application'

module TWEyes

  class Migration < Application

    public

    def initialize(from_user_id, to_user_id)

      super()

      @from_user_id = from_user_id
      @to_user_id   = to_user_id

      purge_migration_path
      create_migration_path

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def convert

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

        if table.match(/^account$/) and
           describe.include?('id')

          @builder[:mysql][:common]
          .query(
            sprintf(
              "SELECT * FROM %s WHERE id='%s'",
              table,
              @from_user_id
            )
          ).each do |record|

            record.store(
              'id',
              sprintf("%d",
                @to_user_id
              ).to_i
            )
       
            to_yaml(table, record)
            to_sql(table, record)
          end
        end

        if describe.include?('user_id')
       
          @builder[:mysql][:common]
          .query(
            sprintf(
              "SELECT * FROM %s WHERE user_id='%s' AND deleted=0",
              table,
              @from_user_id
            )
          ).each do |record|

            record.select{|e| e.match(/_id$/)}.each do |e|

              if table.match(
                   /(^research_watch_list$|^item_)/
                 )

                next
              end

              if e[0].match(
                   /(^user_id|_state_id|_item_id|^item_id)$/
                 )

                next
              end

              if e[1] > 0

                record.store(
                  e[0],
                  sprintf("%d%03d", e[1], @to_user_id).to_i
                )
              end
            end

            record.store(
              'id',
              sprintf("%d%03d",
                record['id'],
                @to_user_id
              ).to_i
            )
            record.store(
              'user_id',
              sprintf("%d",
                @to_user_id
              ).to_i
            )
       
            to_yaml(table, record)
            to_sql(table, record)
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

    def create_migration_path

      FileUtils.mkpath(
        sprintf("%s/%s/%s",
          @configure[:system][:directory].get_migration_path,
          @configure[:system][:net].get_domain,
          @to_user_id
        )
      )
    end

    def purge_migration_path

      FileUtils.remove_entry_secure(
        sprintf("%s/%s/%s",
          @configure[:system][:directory].get_migration_path,
          @configure[:system][:net].get_domain,
          @to_user_id
        )
      )
    rescue => e
      pp e.class, e.message
    end

    def get_path(table, suffix)

      sprintf(
        "%s/%s/%s/%s.%s",
        @configure[:system][:directory].get_migration_path,
        @configure[:system][:net].get_domain,
        @to_user_id,
        table,
        suffix
      )
    end

    def to_yaml(table, record)

      fh   = nil
      path = nil

      path = get_path(table, :yml)

      if FileTest.exist?(path)

        fh = open(path, 'a')
      else

        fh = open(path, 'w')
      end

      fh.puts(record.to_yaml)

      fh.close
    end

    def to_sql(table, record)

      fh   = nil
      path = nil

      path = get_path(table, :sql)

      if FileTest.exist?(path)

        fh = open(path, 'a')
      else

        fh = open(path, 'w')
      end

      fh.printf(
        "INSERT INTO %s (%s) VALUES (%s);\n",
        table,
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
      fh.close
    end

  end ### class Migration [END]
end
