#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/mercari'

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

  mercari = TWEyes::Mercari.new

  mercari.operate

  GC.start
  daemon.wait(60)
  GC.stat

end
