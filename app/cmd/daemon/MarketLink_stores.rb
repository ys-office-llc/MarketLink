#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/stores'

daemon = TWEyes::Daemon.new
myname = File.basename(__FILE__)

case ARGV.shift.to_s.to_sym
when :detach
  daemon.run(myname)
when :attach
  daemon.stop(myname)
else
  daemon.stop(myname)
end

GC.enable
  
loop do

  stores = TWEyes::Stores.new

  stores.research

  GC.start
  daemon.wait(300)
  GC.stat

end
