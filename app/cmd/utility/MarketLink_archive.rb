#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/archive'

daemon  = TWEyes::Daemon.new
archive = TWEyes::Archive.new

daemon.stop(File.basename(__FILE__))

archive.run
