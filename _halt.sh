#! /bin/sh

SERVER_ROOT="$(dirname ${0})"
expr "${SERVER_ROOT}" : "/.*" > /dev/null || \
SERVER_ROOT=`(cd "${SERVER_ROOT}" && pwd)`

${SERVER_ROOT}/app/cmd/utility/MarketLink_command.rb halt
