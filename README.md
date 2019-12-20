#################################
# YUTAKI HOWTO
#################################
# (c) JS GOETSCHY
#  v  2019121601
#################################

I only write this because I found to many related or exhastive topics on internet.

Here was my case :
 - I'm a lucky owner of a Heat Pump from Hitachi
 - Model is Yutaki S, it's a previous 2016 one. This is a splited heat pump with an external module and an internal one
 - I wanted to monitor this heatpump and was looking for many solutions.
My solution combines :
 - A Modbus gateway Hitachi ATW-MBS-02
	One interface is H-Link to the heatpump
	I used the IP/Ethernet interface to call it
 - A raspberry for web server and data collection
 - A little program in Python to collect data, based on pymodbus module, and store it in a sqlite3 database
 - A little interface based on highcharts for graphical restitution
That's it.

1. Material installation
 - I bought the ATW-MBS-02 at https://www.maison-energy.com/pompes-a-chaleur-R20/atw-mbs-02-M102921.html
 - 286, ok a bit expansive...
 - I pluged it in my wallmounted electrical box
 - you must connect to 230V at left of the gateway (protected in my configuration with a 2A breaker)
 - a to wire cable (phone...) connected from the right of the gateway 
 - to the heatpump on the vertical connector : the two connector are the bottom and the same than the external module
 - connect the ethernet with RJ45 to your network (for me thru a powerline module)

2. Establish the connectivity between raspberry & gateway
 - The default IP address of the gateway is 192.168.0.4
 - You can change it thru a little program on an USB stick sold with the gateway
 - Sorry I run linux only and the program is windows only :-(
 - So I choose to put a secondary IP interface on my raspberry
 - edit /etc/network/interfaces
	auto eth0:0
	iface eth0:0 inet static
	  address 192.168.0.1
	  netmask 255.255.255.0
 - my rasbian release uses dhcpcd to parameter the interfaces, so I add a "denyinterface eth0" to my /etc/dhcp/dhcpcd.conf
 - and put the correct configuration in /etc/network/interfaces
 - reboot
 - ping 192.168.0.4 should work

3. Prerequisites on raspberry
 - python must be installed (3.5 for me)
 - "pip install pymodbus" [for python3.5 : pip3.5 ...]
 - must also have a webserver with php and sqlite3 extansion
 - can download highcharts from https://www.highcharts.com/blog/download/, unzip it

4. Collector
 - create the database : sqlite3 yutaki.db 'CREATE TABLE yutakidata (timestamp TIMESTAMP, state INTEGER, temp_out INTEGER, temp_water_set INTEGER, temp_water_in INTEGER, temp_water_out INTEGER);'
 - download the python script here : http://js.goetschy.com/linux/yutaki/getmodbusinfo.py
 - I parametered some counters to collect : external temp, input water temp, output water temp, setup temp...
 - you can adapt to gather other values, you can find here the documentation with modbus registers : http://js.goetschy.com/linux/yutaki/PMML0419A_rev1.pdf and http://js.goetschy.com/linux/yutaki/PMML0420B_rev0.pdf
 - then put a regular poll in the crontab (I use a lockfile to avoid multiple jobs to be launched) : */2 * * * * flock -n /var/lock/yutaki /home/yutaki/getmodbusinfo.py >/dev/null 2>/dev/null
 - your database is feeding. Test : sqlite3 yutaki.db 'SELECT * FROM yutakidata ORDER BY timestamp DESC LIMIT 10;'

5. Graphs
 - download the php scripts here : http://js.goetschy.com/linux/yutaki/yutaki.tar.gz
 - move highcharts dir in the same directory
 - with few path adjustments, should work !

#################################
