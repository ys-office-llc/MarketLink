#! /bin/bash

CONTAINER_ROOT="$(pwd)"
SERVICE_ROOT="${CONTAINER_ROOT%/*}"
DOMAIN="$(echo ${SERVICE_ROOT} | ruby2.2 -ne 'print $_.chomp.split("/").reverse[0..2].join(".")')"

FROM="${CONTAINER_ROOT}"
TO="$(cat ./containers) s00:m"

for to in ${TO} ; do

  host="$(echo ${to} | cut -d':' -f1)"
  container="$(echo ${to} | cut -d':' -f2)"

  echo "[ ${host}:${container} ]"

  rsync -a \
        ${FROM}/_{boot,halt,reboot,init,sub,chown,kill,stat}.sh ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}

  rsync --delete \
        -a \
        ${FROM}/app ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}

  rsync --delete \
        --exclude "etc/yml/configure/database/mysql.yml" \
        -a \
        ${FROM}/etc ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}

  rsync -a \
        ${FROM}/htdocs/{.htaccess,css,js,favicon.ico,index.php,index_error.php,spool} \
        ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}/htdocs/

  rsync -a \
        ${FROM}/htdocs/images/{ems.jpg,market_link_logo1.png,market_link_logo2.png,paypal.jpg} \
        ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}/htdocs/images

  ### ディレクトリのみコピー
  rsync -a \
        --include '*/' \
        --exclude '*' \
        ${FROM}/var/run ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}/var

  ### ディレクトリのみコピー
  rsync -a \
        --include '*/' \
        --exclude '*' \
        ${FROM}/var/tmp ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}/var

  rsync -a \
        ${FROM}/var/migration ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}/var

  rsync -a \
        ${FROM}/var/spool/fetch_proxies_full.json ${host}.${DOMAIN}:${SERVICE_ROOT}/${container}/var/spool
done
