#! /usr/bin/ruby2.6

require_relative '../module/daemon'
require_relative '../module/auctions'
### require_relative '../module/free_markets'

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

  auctions = TWEyes::Auctions.new
  ###free_markets = TWEyes::FreeMarkets.new

  auctions.watch
  ###free_markets.research

  GC.start
  daemon.wait(60)
  GC.stat

end
