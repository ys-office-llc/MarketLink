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
  ssh ${host}.${DOMAIN} "mysql -u${container} -p${container}_pw market_link_services_camera_${container} -e 'select user_id, api_call_method as method, api_name as name, api_numof_calls as calls, api_date_of_call as date from profiling_apis_yahoo_auctions_buyer order by api_numof_calls desc' | grep `date +%Y-%m-%d`"
  echo

done
