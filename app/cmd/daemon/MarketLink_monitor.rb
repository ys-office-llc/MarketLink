#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/monitor'

opts   = {}
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

ARGV.each do |arg|

  opts.store(arg.to_sym, true)
end

GC.enable

loop do

  monitor = TWEyes::Monitor.new

  monitor.ping(opts)
  monitor.clean(opts)

  GC.start
  daemon.wait(300)
  GC.stat

end
