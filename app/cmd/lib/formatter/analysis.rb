module TWEyes

  module Formatter ### 名前空間

    class Analysis

      public

      def initialize(configure)
        super()

        @configure = configure
      end

      protected

      private

      class User < Analysis

        public

        def initialize(configure)
          super
        end

        def report(param)
          sprintf(@configure[:formatter][:analysis][:user][:self]
            .report,
            param
          )
        end

        protected

        private

      end ### class User < Analysis [END]

      class System < Analysis

        public

        def initialize(configure)
          super
        end

        def report(param)
          sprintf(@configure[:formatter][:analysis][:system][:self]
            .report,
            param
          )
        end

        protected

        private

      end ### class System < Analysis [END]

    end ### class Analysis [END]
  
  end
end
