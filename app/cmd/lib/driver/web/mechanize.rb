module TWEyes

  module Driver ### 名前空間

    module Web ### 名前空間

      class MechanizeX

        attr_reader :agent, :cookies_jar

        public

        def initialize(configure)
          super()

          @configure = configure

          @agent       = Mechanize.new
          @cookies_jar = Mechanize::CookieJar.new

          set_user_agent_alias
        end

        def re_initialize

          @agent = Mechanize.new
        end

        def set_proxy(ip_addr, port, user, password)

          ###pp sprintf("%s:%s", ip_addr, port)
          @agent.set_proxy(ip_addr, port, user, password)
        end

        def set_user_agent_alias

          user_agent = @configure[:driver][:web][:mechanize]
                       .get_user_agent_aliases.sample

          ###pp user_agent
          @agent.user_agent_alias = user_agent
        end

        def set_user_agent(user_agent)

          @agent.user_agent = user_agent
          ### pp @agent.user_agent
        end

        def set_proxy_random(proxy_hash)

          ph = proxy_hash.sample

          set_proxy(
            ph['proxy_ip'],
            ph['proxy_port'].to_i,
            ph['username'],
            ph['password']
          )
        end

        def build_query_string(param)

          Mechanize::Util.build_query_string(param)
        end

        def save_source(page)

          File.write(
            get_local_path('html'),
            page.body.toutf8
          )

          get_public_path('html')
        end

        protected

        private

        def post_connect_hooks

          @agent.post_connect_hooks.push(convert_utf8)
        end

        def convert_utf8

          Proc.new do |s, url, response, body|

            if %r|text| =~ response['Content-Type']

              body.gsub!(/^.*$/m, NKF.nkf("-wm0", body))

              body.gsub!(/<meta[^>]*>/) do |meta|

                meta.sub!(/Shift_JIS|SJIS|EUC-JP/i, "UTF-8")
              end

              response['Content-Type'] = 'text/html; charset=utf-8'
            end
          end
        end

        def get_local_path(suffix)

          sprintf("%s/%s_%s.%s",
            @configure[:system][:directory].get_htdocs_spool_path,
            @configure[:system][:user].account['user_name'],
            @configure[:system][:date].get_date_time_suffix,
            suffix
          )
        end

        def get_public_path(suffix)

          sprintf("%s%s/%s_%s.%s",
            @configure[:system][:net].get_https_uri,
            @configure[:system][:directory].get_relative_spool_path,
            @configure[:system][:user].account['user_name'],
            @configure[:system][:date].get_date_time_suffix,
            suffix
          )
        end
      
      end ### class 

    end ### module Web [END]
  
  end ### module Driver [END]

end ### module TWEyes
