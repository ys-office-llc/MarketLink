module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    class ChatWork 

      ###include TWEyes::Mixin::Languages::XML

      public

      def initialize(configure)
        super()

        @configure = configure

        @self     = get_class_suffix
        @parent   = @self.split('_')[0].to_sym
        @child    = @self.split('_')[1].to_sym
        @account  = nil
        @password = nil
      end

      def initializeX

        @account  = @configure[:system][:user]
                   .account[
                     sprintf("%s_account",
                       @child
                     )
                   ]
        @password  = @configure[:system][:user]
                     .account[
                       sprintf("%s_password",
                         @child
                       )
                     ]
      end

      def request(driver, logger)

        driver.connector[:waiter].until do

          driver.connector[:driver].get(
            @configure[:controller][:chatwork][:self]
            .url_tweyes_administrator
          )
          driver.connector[:driver]
            .find_element(
              :id,
              '__ecf_form_default_1'
          ).click
          driver.connector[:driver]
            .find_element(
              :name,
              'email'
          ).clear
          driver.connector[:driver]
            .find_element(
              :name,
              'email'
          ).send_keys @account
          driver.connector[:driver]
            .find_element(
              :name,
              'password'
          ).clear
          driver.connector[:driver]
            .find_element(
              :name,
              'password'
          ).send_keys @password
          driver.connector[:driver]
            .find_element(
              :xpath,
              '//*[@id="chatwork"]/div/section/div/form/div[2]/input'
          ).click
          driver.connector[:driver]
            .find_element(
              :xpath,
              '//div[@class="_requestContact _actionButton button btnPrimary"]'
          ).click
        end
      rescue => e

        raise(TWEyes::Exception::Controller::ChatWork.new(
          e.class,
          e.backtrace,
          driver.capture,
          driver.save_source,
          __method__
        ), e)
      end

      def approve(driver, logger)

        element = nil

        driver.connector[:waiter].until do

          driver.connector[:driver].get(
            @configure[:controller][:chatwork][:self].url
          )
          driver.connector[:driver]
            .find_element(
              :name,
              'email'
          ).clear
          driver.connector[:driver]
            .find_element(
              :name,
              'email'
          ).send_keys @configure[:controller][:chatwork][:self].account
          driver.connector[:driver]
            .find_element(
              :name,
              'password'
          ).clear
          driver.connector[:driver]
            .find_element(
              :name,
              'password'
          ).send_keys @configure[:controller][:chatwork][:self].password
          driver.connector[:driver]
            .find_element(
              :xpath,
              '//*[@id="chatwork"]/div/section/div/form/div[2]/input'
          ).click
          driver.connector[:driver]
            .find_element(
              :xpath,
              '//*[@id="_addButton"]/span[1]'
          ).click
          driver.connector[:driver]
            .find_elements(
              :xpath,
              '//li[@class="_cwDDList"]'
          )[1].click
          sleep(1)
          driver.connector[:driver]
            .find_element(
              :id,
              '_contactWindowTabRequest'
          ).click
          element = driver.connector[:driver]
            .find_element(
              :xpath,
              '//div[@class="_acceptContactRequest '+
              '_actionButton button btnPrimary"]'
          )
          element.click
        end

        element.attribute('data-aid')
      rescue => e

        raise(TWEyes::Exception::Controller::ChatWork.new(
          e.class,
          e.backtrace,
          driver.capture,
          driver.save_source,
          __method__
        ), e)
      end

      protected

      def get_class_suffix

        self.class
            .to_s
            .split('::')[-2..-1]
            .map{|e|e.downcase}
            .join('_')
      end

      private

    end ### class ChatWork [END]

  end ### module Controller [END]

end ### module TWEyes [END]
