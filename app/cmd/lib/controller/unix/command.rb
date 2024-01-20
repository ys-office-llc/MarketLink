module TWEyes

  module Controller ### 名前空間用なので機能を持たせるとバグるよ

    module UNIX ### 名前空間用なので機能を持たせるとバグるよ

      class Command

        public

        def initialize(configure)
          super()

          @configure = configure
        end

        def initializeX
        end

        def exec(cmd)

          o, e, s = Open3.capture3(cmd)

          {
            status: s.to_i,
            stdout: o,
            stderr: e,
            cmd: File.basename(cmd),
          }
        end

        protected

        private
    
      end ### class Command [END]

    end ### module UNIX [END]

  end ### module Controller [END]

end ### module TWEyes [END]
