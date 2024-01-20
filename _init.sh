#! /bin/bash

CONTAINER_ROOT="$(pwd)"
CONTAINER="${CONTAINER_ROOT##*/}"

mysql -uroot < ${CONTAINER_ROOT}/etc/schemas/_initialize.sql

find ${CONTAINER_ROOT}/etc/schemas/ \
     -type f \
     -print \
     -exec perl -p -i -e "s[s00][${CONTAINER}]g" {} \;

for sql in $(find ${CONTAINER_ROOT}/etc/schemas/ -type f -name '[a-z]*.sql') ; do

  mysql -u${CONTAINER} \
        -p${CONTAINER}_pw \
        market_link_services_camera_${CONTAINER} < ${sql}

done

exit 0
