#! /bin/bash

CONTAINER_ROOT="$(pwd)"
SERVICE_ROOT="${CONTAINER_ROOT%/*}"
DOMAIN="$(echo ${SERVICE_ROOT} | ruby2.2 -ne 'print $_.chomp.split("/").reverse[0..2].join(".")')"
FROM="${CONTAINER_ROOT}"
TO='
'

for to in ${TO} ; do

  host="$(echo ${to} | cut -d':' -f1)"
  container="$(echo ${to} | cut -d':' -f2)"

  rsync -av \
        ${FROM}/etc/yml/configure/database/mysql.yml \
        ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}/etc/yml/configure/database/

done
