module TWEyes
  module Bootstrap
    class Loader
      public

      def initialize(configure)
        @configure = configure
        @libraries = []
      end

      def extend_require
        find_libraries
        require_repeatedly
      end

      protected

      private
    
      def find_libraries
        Find.find(@configure.get_library_root) do |f|
          if /[0-z_]+\.rb$/ =~ f
            @libraries.push(f)
          end
        end
      end
    
      def require_repeatedly
        exception = nil
    
        Timeout::timeout(@configure.get_timeout) do
          while @libraries.size > 0 do
            library = @libraries.shift
            begin
              require_relative library
            rescue NameError => exception
              @libraries.push(library)
            end
          end
        end
      rescue Timeout::Error
        raise exception.class, exception.message, exception.backtrace
      end

    end
  end
end
