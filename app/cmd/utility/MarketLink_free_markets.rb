#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/free_markets'

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

  free_markets = TWEyes::FreeMarkets.new

  free_markets.research

  GC.start
  daemon.wait(60)
  GC.stat

end
