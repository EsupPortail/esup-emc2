#!/bin/bash

current_date=$(date '+%Y-%m-%d_%H-%M-%S')

### SYNCHRO
cd /var/www/html
php public/index.php formation-gerer-formations        > /root/report_formations_${current_date}

### MAIL
mail -s "PrEECoG(Prod): Gestion formations" jean-philippe.metivier@unicaen.fr < /root/report_formations_${current_date}

### nettoyage
rm -fr /root/report_formations_${current_date}
