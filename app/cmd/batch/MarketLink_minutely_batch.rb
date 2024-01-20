#! /usr/bin/ruby2.2

require_relative '../module/cleaner'
require_relative '../module/daemon'
require_relative '../module/support'

cleaner = TWEyes::Cleaner.new
daemon  = TWEyes::Daemon.new
support = TWEyes::Support.new

daemon.stop(File.basename(__FILE__))
