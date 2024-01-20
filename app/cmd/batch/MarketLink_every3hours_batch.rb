#! /usr/bin/ruby2.2

require_relative '../module/auctions'
require_relative '../module/daemon'

auctions = TWEyes::Auctions.new
daemon   = TWEyes::Daemon.new

daemon.stop(File.basename(__FILE__))

auctions.research
