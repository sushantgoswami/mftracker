#!/bin/bash

PATH=/bin:/usr/bin:/sbin:/usr/sbin

cd /usr/share/mftracker/mftracker-v01
rm -rf NAVAll.txt
wget --no-check-certificate -O /usr/share/mftracker/mftracker-v01/NAVAll.txt https://portal.amfiindia.com/spages/NAVAll.txt
curl -k https://mftracker.unibutton.com/mftracker-v01/service/update_nav.php?id=iCe4x5C7d0BAB9Ht3LUVh1P/KXG/twfoqpFw3bGYeZU=
curl -k https://mftracker.unibutton.com/mftracker-v01/service/collect_data_total.php?id=iCe4x5C7d0BAB9Ht3LUVh1P/KXG/twfoqpFw3bGYeZU=
