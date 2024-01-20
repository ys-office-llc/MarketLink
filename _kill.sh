#! /bin/sh

SERVER_ROOT="$(dirname ${0})"
expr "${SERVER_ROOT}" : "/.*" > /dev/null || \
SERVER_ROOT=`(cd "${SERVER_ROOT}" && pwd)`

DAEMON_PATH="${SERVER_ROOT}/app/cmd/daemon"
BATCH_PATH="${SERVER_ROOT}/app/cmd/batch"

killall $(ls ${DAEMON_PATH}) $(ls ${BATCH_PATH}) phantomjs Xvfb chromedriver chrome
