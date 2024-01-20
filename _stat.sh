#! /bin/sh

SERVER_ROOT="$(dirname ${0})"
expr "${SERVER_ROOT}" : "/.*" > /dev/null || \
SERVER_ROOT=`(cd "${SERVER_ROOT}" && pwd)`
RUN_PATH="${SERVER_ROOT}/var/run"

for proc in $(ls ${RUN_PATH}/*) ; do

  kill -0 `cat ${proc}` 2> /dev/null

  if [ x"0" = x"${?}" ] ;then

    echo "$(basename ${proc}) ALIVE"
  else

    echo "$(basename ${proc}) ***DEAD***"
  fi

done
