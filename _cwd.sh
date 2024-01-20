#! /bin/sh

SERVER_ROOT="$(dirname ${0})"
expr "${SERVER_ROOT}" : "/.*" > /dev/null || \
SERVER_ROOT=`(cd "${SERVER_ROOT}" && pwd)`

echo ${SERVER_ROOT}
