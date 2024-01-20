#! /usr/bin/ruby2.6

require_relative '../module/daemon'
require_relative '../module/auctions'
require_relative '../module/ebay'
require_relative '../module/amazon'
require_relative '../module/conductor'

daemon = TWEyes::Daemon.new
myname = File.basename(__FILE__)

mode      = ARGV.shift.to_s.to_sym
operation = ARGV.shift.to_s.to_sym
market    = ARGV.shift.to_s.to_sym

case operation
when :detach
  daemon.run(myname)
when :attach
  daemon.stop(myname)
else
  daemon.stop(myname)
end

GC.enable

loop do

  auctions  = TWEyes::Auctions.new
  ebay      = TWEyes::Ebay.new
  amazon    = TWEyes::Amazon.new
  conductor = TWEyes::Conductor.new

  if mode == :operation or mode == :all

    case market  
    when :ebay
      ebay.operate
    when :amazon
      amazon.operate
    when :auctions
      auctions.operate
    when :all
      ebay.operate
      amazon.operate
      auctions.operate
    else
    end

  end

  if mode == :orchestration or mode == :all

    case market
    when :ebay
      conductor.welcome(:ebay_us)
    when :amazon
      conductor.welcome(:amazon_jp)
    when :auctions
      conductor.welcome(:yahoo_auctions)
    when :all
      conductor.welcome(:ebay_us)
      conductor.welcome(:amazon_jp)
      conductor.welcome(:yahoo_auctions)
    else
    end

    conductor.conduct

  end

  GC.start
  daemon.wait(180)
  GC.stat

end
