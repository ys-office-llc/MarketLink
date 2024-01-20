#! /bin/bash

CONTAINER_ROOT="$(pwd)"
CONTAINER="${CONTAINER_ROOT##*/}"

find ${CONTAINER_ROOT}/etc/schemas/ \
     -type f \
     -print \
     -exec perl -p -i -e "s[s00][${CONTAINER}]g" {} \;

exit 0
