#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/auctions'

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

  auctions.snipe_end_item

  GC.start
  daemon.wait(20)
  GC.stat

end
