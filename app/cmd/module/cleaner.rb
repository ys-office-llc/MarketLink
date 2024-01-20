require_relative '../lib/application'

module TWEyes

  class Cleaner < Application

    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def delete_other_than_retention_period

      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account

        @builder[:mysql][:research][:analysis][:archive][:self]
        .delete_other_than_retention_period(
          @configure[:system][:resources].retention_period[
            account['account_contract_id']
          ]
        )
      end
    end

    def delete_research_stores(period)

      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account

        @builder[:mysql][:research][:stores][:self]
        .delete_other_than_retention_period(
          period
        )
      end
    end

    def delete_research_free_markets_watch(period)

      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account

        @builder[:mysql][:research][:free_markets][:watch][:self]
        .delete_other_than_retention_period(
          period
        )
      end
    end

    def purge_archives

      days = 60 * 60 * 24 * 4

      purge_dirs(
        days,
        @configure[:system][:directory].get_archive_path + '/*',
      )

    end

    def purge_logs

      days = 60 * 60 * 24 * 4

      purge_files(
        @configure[:system][:directory].get_log_path,
        days
      )
    end

    def purge_captures

      days = 60 * 60 * 24 * 2

      @builder[:mysql][:account].get_records.each do |account|

        Dir.glob(sprintf("%s/%s_*.{png,html}",
          @configure[:system][:directory].get_htdocs_spool_path,
          account['user_name']
        )).each do |file|

          if (@configure[:system][:date].get_time -
              File.mtime(file)).to_i > days

            case File.ftype(file).to_sym
            when :file

              FileUtils.rm(file, {noop: false, verbose: true})
            end
          end
        end
      end
    end

    def purge_dirs(time_after, *directories)

      Dir.glob(directories).each do |directory|

        if (@configure[:system][:date].get_time -
            File.mtime(directory)).to_i > time_after

          case File.ftype(directory).to_sym
          when :directory

            FileUtils.rm_rf(
              directory,
              {
                noop:    false,
                verbose: true,
              }
            )
          end
        end
      end
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(0, @formatter[:exception].handle(e))
    end

    protected

    private

    def purge_files(directory, days_after)

      Find.find(directory).each do |file|

        if (@configure[:system][:date].get_time -
            File.mtime(file)).to_i > days_after

          case File.ftype(file).to_sym
          when :file

            FileUtils.rm(file, {noop: false, verbose: true})
          end
        end
      end
    end

  end ### class Cleaner [END]
end
