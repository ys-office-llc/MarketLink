#! /bin/bash

CONTAINER_ROOT="$(pwd)"
SERVICE_ROOT="${CONTAINER_ROOT%/*}"
DOMAIN="$(echo ${SERVICE_ROOT} | ruby2.2 -ne 'print $_.chomp.split("/").reverse[0..2].join(".")')"

FROM="${CONTAINER_ROOT}"
TO="$(cat ./containers)"

for to in ${TO} ; do

  host="$(echo ${to} | cut -d':' -f1)"
  container="$(echo ${to} | cut -d':' -f2)"

  echo "[ ${host}:${container} ]"
  ssh ${host}.${DOMAIN} "top -u yusuke -b -n 1 | head -n20"
  echo
  sleep 5

done
