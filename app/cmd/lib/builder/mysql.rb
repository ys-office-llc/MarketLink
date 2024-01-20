module TWEyes

  module Builder ### 名前空間

    class MySQL

      @@connector = nil

      public

      def initialize(configure)

        super()

        @configure = configure

        @table_values = Hash.new {|h, k| h[k] = {}}
        @tables = @configure[:builder][:mysql][:self].get_tables
      end

      def query(query)

        @@connector.query(query)
      end

      def connect(connector)

        @@connector = connector
      end

      def escape(string)

        @@connector.escape(string)
      end

      def show_tables

        @@connector.query('SHOW TABLES')
      end

      def insert(table, key_values)

        key_values.merge!(fill_common_use(table))
        q = sprintf("INSERT INTO %s (%s) VALUES (%s)",
              table,
              key_values.keys.join(','),
              key_values.values.map{|i|"'"+i.to_s+"'"}.join(',')
            )
        @@connector.query(q)

        return key_values[:id]
      end

      def update(id, table, key_values)

        key_value_pairs = []
    
        key_values.merge!(fill_common_use(table))
        key_values.delete(:id)
        key_values.delete(:created_at)
        key_values.each do |k, v|
          key_value_pairs.push(sprintf("%s='%s'", k, v))
        end
    
        q = sprintf("UPDATE %s SET %s WHERE id='%s'",
              table,
              key_value_pairs.join(","),
              id
            )
        @@connector.query(q)
      end

      def delete(id, table)

        q = sprintf("UPDATE %s SET deleted=1 WHERE id='%s'",
              table,
              id
            )

        @@connector.query(q)
      end

      def desc_table(table)

        fields = []

        @@connector.query(sprintf("DESCRIBE %s", table)).each do |record|
          fields.push(record['Field'])
        end

        fields
      end

      def registered?(table, query_hash)

        query_hash.store(:deleted, 0)
    
        q = sprintf("
              SELECT id FROM %s WHERE %s",
              table,
              query_hash.map{|k,v| sprintf("%s='%s'", k, v)}.join(' AND ')
            ).strip

        @@connector.query(q).each do |res|
          return true
        end
    
        return false
      end

      def get_id(table, query_hash)

        result = nil

        query_hash.store(:deleted, 0)

        q = sprintf("
              SELECT id FROM %s WHERE %s",
              table,
              query_hash.map{|k,v| sprintf("%s='%s'", k, v)}.join(' AND ')
            ).strip

        @@connector.query(q).each do |res|
          result = res['id']
        end

        result
      end

      protected

      def delete_other_than_retention_period(table, period)

        q = sprintf("
              UPDATE
                %s
              SET
                deleted=1
              WHERE
                (`created_at` < DATE_SUB(CURDATE(), INTERVAL %s)) AND
                user_id='%s' AND
                deleted=0",
              table,
              period,
              @configure[:system][:user].account['id'] 
            ).strip

        @@connector.query(q)
      end

      def expired?(table, key, period)

        result = nil

        q = sprintf("
              SELECT
                id
              FROM
                %s
              WHERE
                (`%s` < DATE_SUB(CURDATE(), INTERVAL %s)) AND
                user_id='%s' AND
                deleted=0",
              table,
              key,
              period,
              @configure[:system][:user].account['id'] 
            ).strip

        @@connector.query(q).each do |record|

          result = record['id']
        end

        if result

          true
        else

          false
        end
      end

      def count(table)

        count = 0

        @@connector.query(
          sprintf(
            "SELECT COUNT(*) AS count
             FROM %s
             WHERE user_id=%s AND
                   deleted = 0",
            table,
            @configure[:system][:user].account['id']
          )
        ).each do |res|

          count = res['count']
        end

        count
      end

      def count_current_date(table)

        count = 0

        @@connector.query(
          sprintf(
            "SELECT COUNT(*) AS count
             FROM %s
             WHERE created_at = CURRENT_DATE() AND
                   user_id=%s AND
                   deleted = 0",
            table,
            @configure[:system][:user].account['id']
          )
        ).each do |res|

          count = res['count']
        end

        count
      end

      def todays_registered?(table, query_hash)
        result = nil

        query_hash.store(:deleted, 0)
    
        q = sprintf("
              SELECT id,created_at FROM %s WHERE %s",
              table,
              query_hash.map{|k,v| sprintf("%s='%s'", k, v)}.join(' AND ')
            ).strip

        @@connector.query(q).each do |res|

          ### Time Object を Date Object へ変換し今日の日付と比較
          ### 登録されているならば、1 以上が返る
          if res['created_at'].to_date ===@configure[:system][:date].get_today
            result = res['id'].to_i
          end

        end
    
        return result.to_i
      end

      def build_select_fields(table)
        desc_table(table).map do |field|
          sprintf("%s.%s AS %s_%s",
            table,
            field,
            table,
            field
          )
        end.join(', ')
      end

      def get_tables_values(table)
        re_table = Regexp.new(table)
        show_tables.each do |t|
          if re_table =~ t.values.shift
            @table_values[t.values.shift].store(0, '----')
            get_values(t.values.shift).each do |v|
              @table_values[t.values.shift].store(v['id'], v['name'])
            end
          end
        end
      end

      def get_values(table) 
        @@connector.query(sprintf("
          SELECT id,name
          FROM %s",
          table 
        ))
      end

      def get_maximum_id(table)

        ix = nil

        q = sprintf("
              SELECT MAX(id) AS global_maximum FROM %s",
              table
            ).strip

        @@connector.query(q).each do |record|

          if record['global_maximum'].nil?

            return sprintf(
                     "1%03d",
                     @configure[:system][:user].account['id']
                   )
          else

            ix = record['global_maximum'].to_s[0..-4].to_i

            return (
                     ix.succ.to_s +
                     sprintf(
                       "%03d",
                       @configure[:system][:user].account['id']
                     )
                   ).to_i
          end
        end
      end

      def delete_disused_record(table, id)

        q = sprintf("
              DELETE FROM %s WHERE id='%s'",
              table,
              id
            ).strip
        @@connector.query(q)

        return id
      end

      def fill_common_use(table)

        {
          id:          assign_appropriate_primary_id(table),
          user_id:     @configure[:system][:user].account['id'],
          created_at:  @configure[:system][:date].get_date_suffix,
          modified_at: @configure[:system][:date].get_date_suffix
        }
      end

      def assign_appropriate_primary_id(table)

        pool_deleted_ids = []
        id = nil

        q = sprintf("
              SELECT id
              FROM   %s
              WHERE  user_id='%s' AND
                     deleted='1'",
              table,
              @configure[:system][:user].account['id']
            ).strip

        @@connector.query(q).each do |record|

          pool_deleted_ids.push(record['id'])
        end

        if pool_deleted_ids.size > 0

          return delete_disused_record(table, pool_deleted_ids.shift)
        else

          return get_maximum_id(table)
        end
      end

      private

      class Account < MySQL

        TABLE = 'account'

        public

        def initialize(configure)

          super
        end

        def expired?(key, period)

          result = nil

          q = sprintf("
                SELECT
                  id
                FROM
                  %s
                WHERE
                  (`%s` < DATE_SUB(CURDATE(), INTERVAL %s)) AND
                  id='%s' AND
                  deleted=0",
                TABLE,
                key,
                period,
                @configure[:system][:user].account['id']
              ).strip

          @@connector.name = :m
          @@connector.query(q).each do |record|

            result = record['id']
          end
          @@connector.name = nil

          if result

            true
          else

            false
          end
        end

        def registered?(query_hash)

          @@connector.name = :m
          super(TABLE, query_hash)
          @@connector.name = nil
        end

        def update(id, key_values)

          @@connector.name = :m
          super(id, TABLE, key_values)
          @@connector.name = nil
        end

        def get_records

          result = nil

          q = sprintf("
                SELECT t1.*
                FROM   %s AS t1, administrator_host AS t2
                WHERE  t1.accommodated_host_id = t2.id AND
                       t2.name = '%s' AND
                       t1.deleted = 0",
                TABLE,
                @configure[:system][:net].get_domain
              ).strip

          if ENV['ML_USER_ID'].to_i > 0

            q = q + sprintf(
                      " AND t1.id='%s'",
                      ENV['ML_USER_ID'].to_i
                    )
          end

          @@connector.name = :m
          result = @@connector.query(q)
          @@connector.name = nil

          result
        end

        protected

        private

        def fill_common_use(table)
          {
            id:          assign_appropriate_primary_id(table),
            created_at:  @configure[:system][:date].get_date_suffix,
            modified_at: @configure[:system][:date].get_date_suffix
          }
        end

        def assign_appropriate_primary_id(table)

          pool_deleted_ids = []
          id = nil

          q = sprintf("
                SELECT id FROM %s WHERE deleted='1'",
                table
              ).strip
          @@connector.query(q).each do |record|

            pool_deleted_ids.push(record['id'])
          end

          if pool_deleted_ids.size > 0

            return delete_disused_record(table, pool_deleted_ids.shift)
          else

            return get_maximum_id(table)
          end
        end

        def get_maximum_id(table)

          q = sprintf("
                SELECT MAX(id) AS global_maximum FROM %s",
                table
              ).strip

          @@connector.query(q).each do |record|

            if record["global_maximum"].nil?

              return 1
            else

              return record["global_maximum"].succ
            end
          end
        end

      end ### class Account [END]

      class Monitor < MySQL

        public

        def initialize(configure)
          super
        end

        protected

        def fill_common_use(table)
          {
            id:          assign_appropriate_primary_id(table),
            created_at:  @configure[:system][:date].get_date_suffix,
            modified_at: @configure[:system][:date].get_date_suffix
          }
        end

        def todays_registered?(table)

          result = nil

          q = sprintf("
                SELECT id,created_at FROM %s",
                table
              ).strip

          @@connector.query(q).each do |res|

            ### Time Object を Date Object へ変換し今日の日付と比較
            ### 登録されているならば、1 以上が返る
            if res['created_at'].to_date === @configure[:system][:date].get_today

              result = res['id'].to_i
            end

          end

          return result.to_i
        end

        private

        def assign_appropriate_primary_id(table)

          pool_deleted_ids = []
          id = nil

          q = sprintf("
                SELECT id FROM %s WHERE deleted='1'",
                table
              ).strip
          @@connector.query(q).each do |record|

            pool_deleted_ids.push(record['id'])
          end

          if pool_deleted_ids.size > 0

            return delete_disused_record(table, pool_deleted_ids.shift)
          else

            return get_maximum_id(table)
          end
        end

        def get_maximum_id(table)

          q = sprintf("
                SELECT MAX(id) AS global_maximum FROM %s",
                table
              ).strip

          @@connector.query(q).each do |record|

            if record["global_maximum"].nil?

              return 1
            else

              return record["global_maximum"].succ
            end
          end
        end

        class Process < Monitor

          public

          def initialize(configure)

            super

            @t1 = @tables['monitor']['process']['self']
          end

          def registered?(query_hash)

            super(@t1, query_hash)
          end

          def todays_registered?

            super(@t1)
          end

          def get_id(query_hash)
            super(@t1, query_hash)
          end

          def insert(key_values)
            super(@t1, key_values)
          end

          def update(id, key_values)
            super(id, @t1, key_values)
          end

          def delete(id)
            super(id, @t1)
          end

          def get_records
            records = []
            @@connector.query(sprintf("
              SELECT %s
              FROM   %s
              WHERE  %s.deleted = 0",
              build_select_fields(@t1),
              @t1,
              @t1
            )).each do |record|

              records.push(record.symbolize_keys)
            end

            records
          end

          protected

          private

        end ### class Process < Monitor

      end ### class Monitor < MySQL

      class System < MySQL

        public

        def initialize(configure)
          super
        end

        protected

      def fill_common_use(table)
        {
          id:          assign_appropriate_primary_id(table),
          created_at:  @configure[:system][:date].get_date_suffix,
          modified_at: @configure[:system][:date].get_date_suffix
        }
      end

        private

        class Research < System

          public

          def initialize(configure)
            super
          end

          protected

          private

          class Analysis < Research

            def initialize(configure)
              super

              @t1 = @tables['system']['research']['analysis']['self']
            end

            def registered?(query_hash)
              super(@t1, query_hash)
            end

            def get_id(query_hash)
              super(@t1, query_hash)
            end

            def insert(key_values)
              super(@t1, key_values)
            end

            def update(id, key_values)
              super(id, @t1, key_values)
            end

            def delete(id)
              super(id, @t1)
            end

            def get_records
              records = []
              @@connector.query(sprintf("
                SELECT %s
                FROM   %s
                WHERE  %s.deleted = 0",
                build_select_fields(@t1),
                @t1,
                @t1
              )).each do |record|
                records.push(record.symbolize_keys)
              end

              records
            end

            class Archive < Analysis

              public

              def initialize(configure)

                super

                @t1 = @tables['system']['research']['analysis']['archive']['self']
              end

            end ### class Archive < Analysis

          end ### class Analysis < Research

        end ### class Research < System

      end ### class System < MySQL [END]

      class Research < MySQL

        public

        def initialize(configure)
          super
        end

        protected

        private

        class Yahoo < Research
 
          public
 
          def initialize(configure)
            super
          end
 
          protected
 
          private

          class Auctions < Yahoo

            public
 
            def initialize(configure)
              super
            end

            protected

            private
   
            class Search < Auctions

              public
   
              def initialize(configure)
                super

                @t1 = @tables['research']['yahoo']['auctions']['search']['self'] 
              end

              def registered?(query_hash)
                super(@t1, query_hash)
              end
    
              def insert(key_values)
                super(@t1, key_values)
              end
    
              def update(id, key_values)
                super(id, @t1, key_values)
              end
    
              def get_records
                records = []
                @@connector.query(sprintf("
                  SELECT %s
                  FROM   %s
                  WHERE  %s.user_id = '%s' AND
                         %s.deleted = 0",
                  build_select_fields(@t1),
                  @t1,
                  @t1,
                  @configure[:system][:user].account['id'],
                  @t1
                )).each do |record|
                  merge = {}
                  merge = record.dup
            
                  record.each do |k,v|
                    @configure[:api][:yahoo][:auctions]
                      .get_search
                      .keys.each do |k2|
                      if k.match(/#{k2}_id$/)
                        merge.store(
                          k,
                          @configure[:api][:yahoo][:auctions]
                          .get_search[k2][v]
                        )
                      end
                    end
                  end

                  records.push(merge)
                end

                records
              end
   
              protected
   
              private
  
            end ### class Search < Auctions [END]

          end ### class Auctions < Yahoo [END]

        end ### class Yahoo < Research [END]

        class Analysis < Research

          public

          def initialize(configure)
            super
          end

          def initialize(configure)
            super

            @t1 = @tables['research']['analysis']['self']
          end

          def registered?(query_hash)
            super(@t1, query_hash)
          end

          def insert(key_values)
            super(@t1, key_values)
          end

          def update(id, key_values)
            super(id, @t1, key_values)
          end

          def get_records
            records = []
            @@connector.query(sprintf("
              SELECT %s
              FROM   %s
              WHERE  %s.user_id = '%s' AND
                     %s.deleted = 0",
              build_select_fields(@t1),
              @t1,
              @t1,
              @configure[:system][:user].account['id'],
              @t1
            )).each do |record|
              records.push(record.symbolize_keys)
            end

            records
          end

          protected

          private

          class Archive < Analysis

            public

            def initialize(configure)
              super

              @t1 = @tables['research']['analysis']['archive']['self']
            end

            def todays_registered?(query_hash)
              super(@t1, query_hash)
            end

            def delete_other_than_retention_period(period)

              super(@t1, period)
            end

            def count_current_date

              super(@t1)
            end

            protected

            private

          end ### class Archive < Analysis

        end ### class Analysis < Research [END]

        class NewArrival < Research
 
          public
 
          def initialize(configure)
            super
          end
 
          def initialize(configure)
            super

            @t1 = @tables['research']['new']['arrival']['self'] 
          end

          def registered?(query_hash)
            super(@t1, query_hash)
          end
    
          def insert(key_values)
            super(@t1, key_values)
          end
    
          def update(id, key_values)
            super(id, @t1, key_values)
          end
    
          def get_records
            records = []
            @@connector.query(sprintf("
              SELECT %s
              FROM   %s
              WHERE  %s.user_id = '%s' AND
                     %s.deleted = 0",
              build_select_fields(@t1),
              @t1,
              @t1,
              @configure[:system][:user].account['id'],
              @t1
            )).each do |record|
              records.push(record.symbolize_keys)
            end

            records
          end
   
          protected
 
          private
  
        end ### class NewArrival < Research [END]

        class Stores < Research

          public

          def initialize(configure)
            super

            @t1 = @tables['research']['stores']['self']
          end

          def get_id(query_hash)

            super(@t1, query_hash)
          end
    
          def count

            super(@t1)
          end

          def registered?(query_hash)

            super(@t1, query_hash)
          end

          def insert(key_values)

            super(@t1, key_values)
          end

          def update(id, key_values)

            super(id, @t1, key_values)
          end

          def delete_other_than_retention_period(period)

            super(@t1, period)
          end

          def get_records

            records = []
            @@connector.query(sprintf("
              SELECT %s
              FROM   %s
              WHERE  %s.user_id = '%s' AND
                     %s.deleted = 0",
              build_select_fields(@t1),
              @t1,
              @t1,
              @configure[:system][:user].account['id'],
              @t1
            )).each do |record|
              records.push(record.symbolize_keys)
            end

            records
          end

          protected

          private

        end ### class Stores < Research [END]

        class WatchList < Research
 
          public
 
          def initialize(configure)
            super

            @t1 = @tables['research']['watch']['list']['self'] 
          end

          def count

            super(@t1)
          end
    
          def registered?(query_hash)
            super(@t1, query_hash)
          end
    
          def get_id(query_hash)
            super(@t1, query_hash)
          end
    
          def insert(key_values)
            super(@t1, key_values)
          end
    
          def update(id, key_values)
            super(id, @t1, key_values)
          end
    
          def delete(id)
            super(id, @t1)
          end

          def get_records
            records = []
            @@connector.query(sprintf("
              SELECT %s
              FROM   %s
              WHERE  %s.user_id = '%s' AND
                     %s.deleted = 0",
              build_select_fields(@t1),
              @t1,
              @t1,
              @configure[:system][:user].account['id'],
              @t1
            )).each do |record|
              records.push(record)
            end

            records
          end
   
          protected
 
          private
  
        end ### class WatchList < Research [END]

        class FreeMarkets < Research

          public

          def initialize(configure)

            super

            @tables = @tables['research']['free']['markets']
          end

          def get_id(query_hash)

            super(@t1, query_hash)
          end

          def count

            super(@t1)
          end

          def registered?(query_hash)

            super(@t1, query_hash)
          end

          def insert(key_values)

            super(@t1, key_values)
          end

          def update(id, key_values)

            super(id, @t1, key_values)
          end

          def delete_other_than_retention_period(period)

            super(@t1, period)
          end

          def get_records

            records = []
            @@connector.query(sprintf("
              SELECT %s
              FROM   %s
              WHERE  %s.user_id = '%s' AND
                     %s.deleted = 0",
              build_select_fields(@t1),
              @t1,
              @t1,
              @configure[:system][:user].account['id'],
              @t1
            )).each do |record|
              records.push(record.symbolize_keys)
            end

            records
          end

          protected

          private

          class Search < FreeMarkets

            public

            def initialize(configure)

              super

              @t1 = @tables['search']['self']
            end

          end ### class Search < FreeMarkets [END]

          class Watch < FreeMarkets

            public

            def initialize(configure)

              super

              @t1 = @tables['watch']['self']
            end

          end ### class Watch < FreeMarkets [END]

        end ### class FreeMarkets < Research [END]

      end ### class Research < MySQL [END]

      class Item < MySQL

        public

        def initialize(configure)
          super

          @tables = @configure[:builder][:mysql][:self].get_tables
          @t1     = @tables['item']['self'] 
          @t2     = @tables['setting']['item']['maker']
          @t3     = @tables['setting']['item']['category']
          @t4     = @tables['item']['state'] 
        end

        def registered?(query_hash)

          super(@t1, query_hash)
        end

        def get_id(query_hash)

          super(@t1, query_hash)
        end

        def insert(key_values)

          super(@t1, key_values)
        end

        def update(id, key_values)

          super(id, @t1, key_values)
        end

        def delete(id)

          super(id, @t1)
        end

        def get_state_names

          get_tables_values(@t4)

          @table_values[@t4]
        end
 
        def get_records
          records = []
          @@connector.query(sprintf("
            SELECT %s
            FROM   %s
            WHERE  %s.user_id = '%s' AND
                   #{if ENV['ML_ITEM_ID'].to_i > 0

                     "#{@t1}.id='#{ENV['ML_ITEM_ID'].to_i}' AND"
                   end}
                   %s.deleted = 0",
            build_select_fields(@t1),
            @t1,
            @t1,
            @configure[:system][:user].account['id'],
            @t1
          )).each do |record|
            merge = {}
            merge = record.dup
            record.each do |k,v|
              has_key =  k.sub('_id', '')
              if @table_values.key?(has_key)
                merge.store(has_key, @table_values[has_key][v])
              end
            end
            records.push(merge)
          end

          records
        end

        protected

        private

        class Yahoo < Item

          public

          def initialize(configure)
            super
            @t1 = @tables['item']['self']
          end

          protected

          private

          class Auctions < Yahoo

            public

            def initialize(configure)
              super

              @t4 = @tables['setting']['item']['condition']['yahoo']['auctions']
            end

            def get_records
              records = []
              get_tables_values(@t2)
              get_tables_values(@t3)
              get_tables_values(@t4)
              @@connector.query(sprintf("
                SELECT %s, %s, %s, %s
                FROM   %s, %s, %s, %s
                WHERE  %s.maker_id = %s.id AND
                       %s.category_id = %s.id AND
                       %s.yahoo_auctions_condition_id = %s.id AND
                       %s.user_id = '%s' AND
                       %s.deleted = 0",
                build_select_fields(@t1),
                build_select_fields(@t2),
                build_select_fields(@t3),
                build_select_fields(@t4),
                @t1,
                @t2,
                @t3,
                @t4,
                @t1,
                @t2,
                @t1,
                @t3,
                @t1,
                @t4,
                @t1,
                @configure[:system][:user].account['id'],
                @t1
              )).each do |record|
                merge = {}
                merge = record.dup
                record.each do |k,v|
                  has_key =  k.sub('_id', '')
                  if @table_values.key?(has_key)
                    merge.store(has_key, @table_values[has_key][v])
                  end
                end
                records.push(merge)
              end
  
              records
            end

            protected

            private

          end ### class Auctions < Yahoo [END]

        end ### class Yahoo < Item [END]

        class Ebay < Item

          public

          def initialize(configure)
            super
          end

          protected

          private

          class Policy < Ebay

            public

            def initialize(configure)

              super

              @t1 = @tables['setting']['item']['condition']['ebay']['us']['policy']
            end

            protected

            private

            class Payment < Policy
  
              public
  
              def initialize(configure)
  
                super
  
                @t1 = @t1['payment']
              end
  
              protected
  
              private

            end ### class Payment < Policy [END]

            class Return < Policy

              public

              def initialize(configure)

                super

                @t1 = @t1['return']
              end

              protected

              private

            end ### class return < Policy [END]

            class Shipping < Policy

              public

              def initialize(configure)

                super

                @t1 = @t1['shipping']
              end

              protected

              private

            end ### class Shipping < Policy [END]

          end ### class Policy < Ebay [END]

          class Us < Ebay

            public

            def initialize(configure)
              super

              @t4 = @tables['setting']['item']['condition']['ebay']['us']['self']
            end

            def get_records
              records = []
              get_tables_values(@t2)
              get_tables_values(@t3)
              get_tables_values(@t4)
              @@connector.query(sprintf("
                SELECT %s, %s, %s, %s
                FROM   %s, %s, %s, %s
                WHERE  %s.maker_id = %s.id AND
                       %s.category_id = %s.id AND
                       %s.ebay_us_condition_id = %s.id AND
                       %s.user_id = '%s' AND
                       #{if ENV['ML_ITEM_ID'].to_i > 0

                          "#{@t1}.id='#{ENV['ML_ITEM_ID'].to_i}' AND"
                         end}
                       %s.deleted = 0",
                build_select_fields(@t1),
                build_select_fields(@t2),
                build_select_fields(@t3),
                build_select_fields(@t4),
                @t1,
                @t2,
                @t3,
                @t4,
                @t1,
                @t2,
                @t1,
                @t3,
                @t1,
                @t4,
                @t1,
                @configure[:system][:user].account['id'],
                @t1
              )).each do |record|
                merge = {}
                merge = record.dup
                record.each do |k,v|
                  has_key =  k.sub('_id', '')
                  if @table_values.key?(has_key)
                    merge.store(has_key, @table_values[has_key][v])
                  end
                end
                records.push(merge)
              end
  
              records
            end

            protected

            private

          end ### class Us < Ebay [END]

        end ### class Ebay < Item [END]

        class Amazon < Item

          public

          def initialize(configure)
            super
          end

          protected

          private

          class Jp < Amazon

            public

            def initialize(configure)
              super

              @t4 = @tables['setting']['item']['grade']
            end

            def get_records
              records = []
              get_tables_values(@t2)
              get_tables_values(@t3)
              get_tables_values(@t4)
              @@connector.query(sprintf("
                SELECT %s, %s, %s, %s
                FROM   %s, %s, %s, %s
                WHERE  %s.maker_id = %s.id AND
                       %s.category_id = %s.id AND
                       %s.grade_id = %s.id AND
                       %s.user_id = '%s' AND
                       %s.deleted = 0",
                build_select_fields(@t1),
                build_select_fields(@t2),
                build_select_fields(@t3),
                build_select_fields(@t4),
                @t1,
                @t2,
                @t3,
                @t4,
                @t1,
                @t2,
                @t1,
                @t3,
                @t1,
                @t4,
                @t1,
                @configure[:system][:user].account['id'],
                @t1
              )).each do |record|
                merge = {}
                merge = record.dup
                record.each do |k,v|
                  has_key =  k.sub('_id', '')
                  if @table_values.key?(has_key)
                    merge.store(has_key, @table_values[has_key][v])
                  end
                end
                records.push(merge)
              end
  
              records
            end

            protected

            private

          end ### class Jp < Amazon [END]

        end ### class Amazon < Item [END]

      end ### class Item [END]

      class Bids < MySQL

        public

        def initialize(configure)

          super

          @tables = @configure[:builder][:mysql][:self].get_tables
          @t1     = @tables['bids']['self']
          @t2     = @tables['research']['watch']['list']['self']
          @t3     = @tables['bids']['state']
        end

        def get_id(query_hash)

          query_hash.store(
            :user_id,
            @configure[:system][:user].account['id']
          )

          super(@t1, query_hash)
        end

        def registered?(query_hash)

          super(@t1, query_hash)
        end

        def insert(key_values)

          super(@t1, key_values)
        end

        def update(id, key_values)

          super(id, @t1, key_values)
        end

        def delete(id)

          super(id, @t1)
        end

        def get_state_names

          get_tables_values(@t3)

          @table_values[@t3]
        end

        def get_records

          records = []

          @@connector.query(sprintf("
            SELECT %s
            FROM   %s
            WHERE  user_id = '%s' AND
                   deleted = 0",
            build_select_fields(@t1),
            @t1,
            @configure[:system][:user].account['id']
          )).each do |record|

            records.push(record)
          end

          records
        end

=begin
        def get_records

          records = []

          @@connector.query(sprintf("
            SELECT %s
            FROM   %s
            WHERE  %s.user_id = '%s' AND
                   %s.deleted = 0",
            build_select_fields(@t1),
            @t1,
            @t1,
            @configure[:system][:user].account['id'],
            @t1
          )).each do |record|

            records.push(record)
          end

          records
        end
=end

        protected

        private

      end ### class Item [END]

      class Auth < MySQL

        public

        def initialize(configure)
          super

          @tables = @configure[:builder][:mysql][:self].get_tables
        end

        protected

        private

        class Yahoo < Auth
  
          public
  
          def initialize(configure)
            super
          end
  
          protected
  
          private
  
          class Seller < Yahoo
  
            public
  
            def initialize(configure)
              super

              @t1 = @tables['authorize']['yahoo']['seller']['self']
            end

            def registered?(query_hash)
              super(@t1, query_hash)
            end

            def insert(key_values)
              super(@t1, key_values)
            end

            def update(id, key_values)
              super(id, @t1, key_values)
            end

            def get_token
              @@connector.query(sprintf("
                SELECT *
                FROM   %s
                WHERE  user_id = '%s' AND
                       deleted = 0",
                @t1,
                @configure[:system][:user].account['id']
              )).each do |record|
                return record
              end
            end
  
            protected
  
            private
  
          end ### class Seller < Yahoo [END]

          class Buyer < Yahoo

            public

            def initialize(configure)
              super

              @t1 = @tables['authorize']['yahoo']['buyer']['self']
            end

            def registered?(query_hash)
              super(@t1, query_hash)
            end

            def insert(key_values)
              super(@t1, key_values)
            end

            def update(id, key_values)
              super(id, @t1, key_values)
            end

            def get_token
              @@connector.query(sprintf("
                SELECT *
                FROM   %s
                WHERE  user_id = '%s' AND
                       deleted = 0",
                @t1,
                @configure[:system][:user].account['id']
              )).each do |record|
                return record
              end
            end

            protected

            private

          end ### class Seller < Yahoo [END]

        end ### class Yahoo < Auth [END]

      end ### class Auth [END]

      class Profiling < MySQL

        public

        def initialize(configure)

          super

          @tables = @configure[:builder][:mysql][:self]
                    .get_tables['profiling']
        end

        protected

        private

        class APIS < Profiling

          public

          def initialize(configure)

            super

            @tables = @tables['apis']
          end

          protected

          private

          class Yahoo < APIS

            public

            def initialize(configure)

              super

              @tables = @tables['yahoo']
            end

            protected

            private

            class Auctions < Yahoo

              public

              def initialize(configure)

                super

                @tables = @tables['auctions']
                @t1     = nil
              end

              def registered?(query_hash)

                super(@t1, query_hash)
              end

              def insert(key_values)

                super(@t1, key_values)
              end

              def update(id, key_values)

                super(id, @t1, key_values)
              end

              def get_today_calls

                calls = 0

                @@connector.query(sprintf("
                  SELECT *
                  FROM   %s
                  WHERE  user_id = '%s' AND
                         deleted = 0 AND
                         api_date_of_call = '%s'",
                  @t1,
                  @configure[:system][:user].account['id'],
                  @configure[:system][:date].get_date_suffix
                )).each do |record|

                  calls += record['api_numof_calls'].to_i
                end

                calls
              end

              def get_today_calls_by_api(call_method, api_name)

                calls = 0

                @@connector.query(sprintf("
                  SELECT *
                  FROM   %s
                  WHERE  user_id = '%s' AND
                         deleted = 0 AND
                         api_call_method = '%s' AND
                         api_name = '%s' AND
                         api_date_of_call = '%s'",
                  @t1,
                  @configure[:system][:user].account['id'],
                  call_method,
                  api_name,
                  @configure[:system][:date].get_date_suffix
                )).each do |record|

                  calls = record['api_numof_calls'].to_i
                end

                calls
              end

              def get_id(query_hash)

                query_hash.store(
                  :user_id,
                  @configure[:system][:user].account['id']
                )

                super(@t1, query_hash)
              end

              def get_records

                records = []

                @@connector.query(sprintf("
                  SELECT %s
                  FROM   %s
                  WHERE  %s.user_id = '%s' AND
                         %s.deleted = 0",
                  build_select_fields(@t1),
                  @t1,
                  @t1,
                  @configure[:system][:user].account['id'],
                  @t1
                )).each do |record|

                  records.push(record.symbolize_keys)
                end

                records
              end

              protected

              private

              class Seller < Auctions

                public

                def initialize(configure)

                  super

                  @t1 = @tables['seller']['self']
                end

                protected

                private

              end ### class Seller < Auctions [END]

              class Buyer < Auctions

                public

                def initialize(configure)

                  super

                  @t1 = @tables['buyer']['self']
                end

                protected

                private

              end ### class Buyer < Auctions [END]

            end ### class Auctions < Yahoo [END]

          end ### class Yahoo < API [END]

        end ### class API < Profiling [END]

      end ### class Profiling < MySQL [END]

      class Support < MySQL

        public

        def initialize(configure)
          super

          @tables = @configure[:builder][:mysql][:self].get_tables
        end

        protected

        private

        class Contact < Support

          public

          def initialize(configure)
            super
          end

          def initialize(configure)
            super

            @t1 = @tables['support']['contact']['self']
          end

          def registered?(query_hash)

            super(@t1, query_hash)
          end

          def insert(key_values)

            super(@t1, key_values)
          end

          def update(id, key_values)

            super(id, @t1, key_values)
          end

          def get_records

            records = []

            @@connector.query(sprintf("
              SELECT %s
              FROM   %s
              WHERE  %s.user_id = '%s' AND
                     %s.deleted = 0 AND
                     %s.submitted = 0",
              build_select_fields(@t1),
              @t1,
              @t1,
              @configure[:system][:user].account['id'],
              @t1,
              @t1
            )).each do |record|

              records.push(record.symbolize_keys)

            end

            records
          end

          protected

          private

        end ### class Contact < Support [END]

      end ### class Support < MySQL [END]

      class Setting < MySQL

        public

        def initialize(configure)

          super

          @tables = @configure[:builder][:mysql][:self]
                    .get_tables
          @t1     = @tables['setting']
        end

        protected

        private

        class Item < Setting

          public

          def initialize(configure)

            super

            @t1 = @t1['item']
          end

          protected

          private

          class MyPattern < Item

            public

            def initialize(configure)

              super

              @t1 = @t1['my_pattern']
            end

            def get_record_by_id(id)

              q = sprintf("
                    SELECT *
                    FROM %s
                    WHERE user_id = '%s' AND
                          deleted = 0    AND
                          id      = '%s'",
                    @t1,
                    @configure[:system][:user].account['id'],
                    id
                  )

              @@connector.query(q)
              .each do |record|

                record
              end
            end

            protected

            private

          end ### class MyPattern < Item [END]

        end ### class Item < Setting [END]

      end ### class Setting < MySQL [END]

    end ### class MySQL [END]
  
  end ### module Builder [END]

end ### module TWEyes [END]
