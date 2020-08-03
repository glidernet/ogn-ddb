#!/usr/bin/python3
######################################################################
# This program migrate the current OGN DDB to the new format
######################################################################
import MySQLdb                  # the SQL data base routines
import json
import os

#
# --------------------------------------------------------------------------------------------------- #
#
print ("\n\nOGN DDB report utility:\n" )
sql1="SELECT count(*)  as cnt , usr_adress FROM devices, users where dev_userid = usr_id group by usr_id;"
sql1="SELECT usr_id, usr_adress FROM users ;"

prt=False
import config				# import configuration values
DBuser=config.DBuser
DBpasswd=config.DBpasswd
DBname='glidernet_devicesdb'
print("MySQL: Database:", DBname)
conn1 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBname)

curs1 = conn1.cursor()			# cursor for devices destination			         
curs2 = conn1.cursor()			# cursor for devices destination			         
curs1.execute(sql1)
rowg = curs1.fetchone() 		# find number of devices on the original table	

cnt=0					# counter of records
cnterr=0				# counter of devices defined as Flarm but not in the Flarm range
cntmany=0				# counter of devices defined as Flarm but not in the Flarm range


while rowg:				# go thru all the user
      usrid 		= rowg[0] 	# userid
      email 		= rowg[1] 	# userid
      sql2="SELECT count(*)  as cnt  FROM devices where dev_userid = '"+str(usrid)+"' ;"
      curs2.execute(sql2)
      devrow = curs2.fetchone()		# find number of devices on the original table	
      count 		= devrow[0] 	# email addr
      if count == 0:
         #print ("User: ", email)
         cnterr += 1
      if count  > 30:
         print ("User: ", email, "Devices:", count)
         cntmany += 1
      cnt += 1				# increase the counter
      rowg = curs1.fetchone()		# next device
# end of while

conn1.commit()				# commit the changes
#
# ------------- REPORTS ---------------------
#
curs1.execute("select count(*) from devices;")
rowg = curs1.fetchone() 	
print("\n\nOGNDDB devices", rowg[0])
curs1.execute("select count(*) from trackedobjects;")
rowg = curs1.fetchone() 	
print("OGNDDB trackedobjects", rowg[0])
curs1.execute("select count(*) from users;")
rowg = curs1.fetchone() 	
print("OGNDDB users", rowg[0])
print("Table USERS Records:", cnt, "Users with no devices", cnterr)
print("Table USERS Records:", cnt, "Users with many (>30) devices", cntmany)
conn1.commit()				# close destination database
conn1.close()				# close destination database
