
date=$(date)
php /var/www/html/public/index.php synchroniser-all > /var/www/html/log.txt
content=$(cat /var/www/html/log.txt)
mail -s "Synchro du $date" jean-philippe.metivier@unicaen.fr <<< $content

