#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/for_testing'

daemon = TWEyes::Daemon.new
myname = File.basename(__FILE__)

###daemon.run(myname)

for_testing = TWEyes::ForTesting.new

for_testing.config
for_testing.operate
