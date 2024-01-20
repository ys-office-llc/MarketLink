#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/migration'

daemon = TWEyes::Daemon.new
daemon.stop(File.basename(__FILE__))

from_user_id = ARGV.shift.to_i
to_user_id   = ARGV.shift.to_i

if from_user_id > 0 and to_user_id

  migration = TWEyes::Migration.new(from_user_id, to_user_id)
  migration.convert
else

  $stderr.printf("Usage: %s <from_user_id> <to_user_id>\n", $0)
end
