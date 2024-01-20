#! /bin/bash

CONTAINER_ROOT="$(pwd)"
CONTAINER="${CONTAINER_ROOT##*/}"

sudo chgrp -R -v www-data ${CONTAINER_ROOT}/htdocs/{images,spool} ${CONTAINER_ROOT}/var/tmp

exit 0
