#!/bin/bash

### BEGIN INIT INFO
# Provides: xplanet
# Required-Start:
# Required-Stop:
# Default-Start: 2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: Xplanet
# Description: Enable service provided by daemon.
### END INIT INFO

# TERRE
xplanet \
	-conf /var/www/html/Dashboard/ressources/xplanet/xplanet.conf \
	-output /var/www/html/Dashboard/ressources/xplanet/img/xplanet_earth.png \
	-wait 120 \
	-body earth \
	-latitude 35 \
	-longitude 10 \
	-geometry 500x500 &

# LUNE
xplanet \
	-conf /var/www/html/Dashboard/ressources/xplanet/xplanet.conf \
	-output /var/www/html/Dashboard/ressources/xplanet/img/xplanet_moon.png \
	-wait 600 \
	-body moon \
	-geometry 150x150 &
