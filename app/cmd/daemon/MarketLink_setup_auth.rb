#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/auctions'
require_relative '../module/ebay'
require_relative '../module/developer'
require_relative '../module/chatwork'

daemon = TWEyes::Daemon.new
myname = File.basename(__FILE__)

case ARGV.shift.to_s.to_sym
when :detach
  daemon.run(myname)
when :attach
  daemon.stop(myname)
else
  daemon.stop(myname)
end

GC.enable

loop do

  chatwork  = TWEyes::ChatWork.new
  developer = TWEyes::Developer.new
  ebay      = TWEyes::Ebay.new
  auctions  = TWEyes::Auctions.new

  chatwork.operate
  ebay.set_ebay_auth_token
  ebay.manage_policy
  auctions.purge_expired_cookies(:buyer, '4 WEEK')
  auctions.purge_expired_cookies(:seller, '4 WEEK')
  auctions.set_cookie(:buyer)
  auctions.set_cookie(:seller)
  developer.yahoo
  developer.amazon

  GC.start
  daemon.wait(40)
  GC.stat

end
