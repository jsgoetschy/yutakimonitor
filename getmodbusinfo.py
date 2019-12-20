#!/usr/bin/env python3.5

# 2019/12/05 (c) Js Goetschy 
# Python module to retrieve informations from ModBus gateway Hitachi ATW-MBS-02
# Connected to a Hitachi Yutaki S Heat Pump, version before 2016
# -> Change registers if needed !
# Store infos to a sqlite database


from pymodbus.client.sync import ModbusTcpClient
import sqlite3
import datetime;
import math;

# Set variables
gateway = "192.168.0.4"
dbfile = '/home/yutaki/yutaki.db'
tbname = 'yutakidata'

largenum = 65536

# Collect ModBus Data
c = ModbusTcpClient(gateway)
result = c.read_input_registers(1077,4)
#print("register 1077,4 :",result.registers)
result2 = c.read_input_registers(1211,11)
#print("register 1211,11 :",result.registers)

#garbage code
blup = result.registers[1]

if blup >= 32768:
	outdoor_ambient_temperature = blup - largenum
	#print(outdoor_ambient_temperature)
else:
	outdoor_ambient_temperature = blup

operation_state = result.registers[0]
#outdoor_ambient_temperature = result.registers[1]
water_temp_set = result2.registers[8]
water_inlet_temperature = result.registers[2]
water_outlet_temperature = result.registers[3]

#print( "Out temp",outdoor_ambient_temperature)
query="INSERT INTO %s VALUES (%s,%s,%s,%s,%s,%s);" % (tbname,math.floor(datetime.datetime.now().timestamp()), operation_state, outdoor_ambient_temperature, water_temp_set, water_inlet_temperature, water_outlet_temperature)
#print(query)

# Commit to database
db = sqlite3.connect(dbfile)
cur = db.cursor()
cur.execute(query)
db.commit()
db.close()
