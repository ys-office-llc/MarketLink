#! /usr/bin/ruby2.2

require_relative '../module/daemon'
require_relative '../module/command'

daemon  = TWEyes::Daemon.new
command = TWEyes::Command.new

daemon.stop(File.basename(__FILE__))

operation = ARGV.shift.to_s.to_sym

case operation
when :boot

  command.boot
when :halt

  command.halt
when :reboot

  command.halt
  command.boot
else

 $stderr.printf("Usage: %s <boot>\n", $0)
end
