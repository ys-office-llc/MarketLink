module TWEyes

  module Driver ### 名前空間

    module Web ### 名前空間

      class SeleniumX

        attr_reader :connector

        public

        def initialize(configure)
          super()

          @configure = configure

          timeout = @configure[:driver][:web][:selenium].get_timeout

          @connector = {
            driver: nil,
            waiter:  nil,
            timeout: {
              driver: {
                implicit_wait: timeout['driver']['implicit_wait'],
                script_timeout: timeout['driver']['script_timeout'],
                page_load: timeout['driver']['page_load'],
              },
              waiter: timeout['waiter'],
            },
            profile: {
              ### FireFox Profileがないと nil となるため、その場合
              ### firefox -p コマンドで "Selenium" という名前で作り直す
              ### Firefox48以降で、Selenium 3.0.0 以降はProfileの作り方が
              ### 大幅に変わった
              ### http://qiita.com/yssg/items/a054d67bc7c7fc39b276
              ###firefox: Selenium::WebDriver::Firefox::Profile.new
              firefox: Selenium::WebDriver::Firefox::Profile
                       .from_name('Selenium')
            },
            proxy: nil,
          }

        end

        def set_proxy(ipv4addr_port)

          if ipv4addr_port.to_s.size > 0

            @connector[:proxy] = Selenium::WebDriver::Proxy.new(
                                   http: ipv4addr_port,
                                 )
          end
        end
      
        def create_driver(browser)

          @connector[:waiter] = Selenium::WebDriver::Wait.new(
            :timeout => @connector[:timeout][:waiter]
          )

          case browser
          when :phantomjs

            phantomjs
          when :firefox

            headless
            firefox
          when :chrome

            chrome
          else
          end

        rescue => e

          raise(
            TWEyes::Exception::Driver::Web::Selenium.new(
              e.class,
              e.backtrace
            ), e)
        end
      
        def destroy_driver

          if @connector[:driver]

            @connector[:driver].close
            @connector[:driver].quit
          end

          if @configure[:driver][:web][:selenium].headless? and
             @headless

            @headless.destroy
          end
        rescue EOFError => e

          pp e.class, e.message
        rescue Errno::ECONNREFUSED => e

          pp e.class, e.message
        rescue Selenium::WebDriver::Error::NoSuchDriverError => e

          pp e.class, e.message
        rescue => e

          raise(
            TWEyes::Exception::Driver::Web::Selenium.new(
              e.class,
              e.backtrace
            ), e)
        end

        def capture

          @connector[:driver].save_screenshot(get_local_path('png'))

          get_public_path('png')
        end

        def save_source

          File.write(
            get_local_path('html'),
            @connector[:driver].page_source.encode('UTF-8')
          )

          get_public_path('html')
        end

        private

        def headless

          random = Random.new
          ### Headless Gem causes Errno::ECONNREFUSED
          ### 察してね。
          ### 'https://swdandruby.wordpress.com/2013/05/11/'+
          ### 'headless-gem-causes-errnoeconnrefused/'
          if @configure[:driver][:web][:selenium].headless?

            @headless = Headless.new(
              reuse: true,
              destroy_at_exit: false,
              display: random.rand(1..99)
            )
            @headless.start
          end
        end

        def phantomjs

          Selenium::WebDriver::PhantomJS.path = '/usr/local/bin/phantomjs'

          user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36'

          capabilities = Selenium::WebDriver::Remote::Capabilities
                         .phantomjs(
                           'phantomjs.page.settings.userAgent' => user_agent,
                           'phantomjs.cli.args' => [
                             '--cookies-file=/var/tmp/cookies.txt',
                             '--debug=true',
                             '--webdriver-loglevel=INFO',
                             "--webdriver-logfile=#{get_log_path(__method__)}",
                             if @connector[:proxy]

                               "--proxy=http://#{@configure[:system][:user]
                                .account['proxy_ipv4addr_pair_static']}"
                             end,
                           ],
                           proxy: @connector[:proxy]
                         )

          @connector[:driver] = Selenium::WebDriver.for(
            :phantomjs,
            :desired_capabilities => capabilities
          )

          manage_timeouts
        rescue Errno::ECONNREFUSED => e

          pp e.class, e.message
        end

        def firefox

          prefs = {
            proxy: @connector[:proxy],
          }

          @connector[:driver] = Selenium::WebDriver.for(
            :firefox,
          )
        rescue Errno::ECONNREFUSED => e

          pp e.class, e.message
        end

        def chrome

          options = Selenium::WebDriver::Chrome::Options.new

          if not ENV['DISABLE_CHROME_HEADLESS']
            options.add_argument('--headless')
          end

          @connector[:driver] = Selenium::WebDriver.for(
            :chrome,
            options: options
          )

          @connector[:driver].manage.timeouts.implicit_wait = 30
        rescue Errno::ECONNREFUSED => e

          pp e.class, e.message
        end

        def manage_timeouts

          @connector[:driver]
          .manage
          .timeouts
          .implicit_wait = @connector[:timeout][:driver][:implicit_wait]

          @connector[:driver]
          .manage
          .timeouts
          .script_timeout = @connector[:timeout][:driver][:script_timeout]

          @connector[:driver]
          .manage
          .timeouts
          .page_load = @connector[:timeout][:driver][:page_load]
        end

        def get_log_path(prefix)

          path = sprintf("/var/tmp/%s/%s",
            prefix,
            @configure[:system][:user].account['id'],
          )
          FileUtils.mkdir_p(path)

          sprintf("%s/%s_%s.log",
            path,
            prefix,
            @configure[:system][:date].get_date_suffix.gsub('-', '_')
          )
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

      end

    end ### class Selenium [END]
  
  end
end
