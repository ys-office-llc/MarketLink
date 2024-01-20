#! /usr/bin/ruby2.2

require_relative '../module/analysis_user'
require_relative '../module/archive'
require_relative '../module/cleaner'
require_relative '../module/daemon'

analysis_user = TWEyes::AnalysisUser.new
archive       = TWEyes::Archive.new
cleaner       = TWEyes::Cleaner.new
daemon        = TWEyes::Daemon.new

daemon.stop(File.basename(__FILE__))

analysis_user.report
archive.run
cleaner.purge_logs
cleaner.purge_archives
cleaner.purge_captures
cleaner.delete_other_than_retention_period
