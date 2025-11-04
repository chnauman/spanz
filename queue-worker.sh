#!/bin/bash
cd /home/u321813111/domains/codeadroits.com/public_html/spanz
/usr/bin/php artisan queue:work --stop-when-empty --tries=3 --timeout=60 >> storage/logs/queue.log 2>&1

