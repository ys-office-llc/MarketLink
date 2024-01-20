require_relative 'configure.rb'
require_relative 'bootstrap/loader.rb'

configure = TWEyes::Configure::Bootstrap::Loader.new
loader    = TWEyes::Bootstrap::Loader.new(configure)
loader.extend_require
