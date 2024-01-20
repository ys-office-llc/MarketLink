#! /usr/bin/ruby2.2

require_relative '../module/cleaner'
require_relative '../module/daemon'

cleaner  = TWEyes::Cleaner.new
daemon   = TWEyes::Daemon.new

daemon.stop(File.basename(__FILE__))

cleaner.purge_logs
cleaner.delete_research_stores('2 DAY')
cleaner.delete_research_free_markets_watch('2 DAY')
