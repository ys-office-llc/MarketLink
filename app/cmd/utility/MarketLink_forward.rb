#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/support'

daemon  = TWEyes::Daemon.new
support = TWEyes::Support.new

daemon.stop(File.basename(__FILE__))

room_name = ARGV.shift.to_s.to_sym

case room_name
when :customer, :monitoring

  support.forward(room_name)
else

 $stderr.printf("Usage: %s <customer|monitoring>\n", $0)
end
