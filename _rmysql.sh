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
  ssh ${host}.${DOMAIN} "cd ${SERVICE_ROOT}/${container} ;  ./_sub.sh ; mysql -u${container} -p${container}_pw market_link_services_camera_${container} < ${1}"

done
