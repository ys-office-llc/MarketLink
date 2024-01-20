#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/maintenance'

daemon = TWEyes::Daemon.new
daemon.stop(File.basename(__FILE__))

user_id = ARGV.shift.to_i

if user_id > 0

  maintenance = TWEyes::Maintenance.new(user_id)
  maintenance.delete
else

  $stderr.printf("Usage: %s <user_id>\n", $0)
end
