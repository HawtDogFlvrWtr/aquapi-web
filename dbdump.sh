#!/bin/sh
FILE=db/aquapi.sql
DBSERVER=127.0.0.1
DATABASE=aquapi
USER=aquapi
PASS=aquapi
unalias rm 2> /dev/null
rm ${FILE} 2> /dev/null
mysqldump --ignore-table=aquapi.module_entries --ignore-table=aquapi.outlet_entries --ignore-table=aquapi.parameter_entries --ignore-table=aquapi.tankkeeping_entries --opt --user=${USER} --password=${PASS} ${DATABASE} > ${FILE}
mysqldump --no-data aquapi module_entries --opt --user=${USER} --password=${PASS} >> ${FILE}
mysqldump --no-data aquapi outlet_entries --opt --user=${USER} --password=${PASS} >> ${FILE}
mysqldump --no-data aquapi parameter_entries --opt --user=${USER} --password=${PASS} >> ${FILE}
mysqldump --no-data aquapi tankkeeping_entries --opt --user=${USER} --password=${PASS} >> ${FILE}