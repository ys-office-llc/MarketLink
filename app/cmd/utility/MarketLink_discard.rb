#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/chatwork'

daemon = TWEyes::Daemon.new
daemon.stop(File.basename(__FILE__))

chatwork = TWEyes::ChatWork.new

chatwork.discard
