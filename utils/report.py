#!/usr/bin/python3
######################################################################
# This program prin several reports on the status of the OGN DDB
######################################################################
import MySQLdb                  # the SQL data base routines
import json
import os
from funcs import *

#
# --------------------------------------------------------------------------------------------------- #
#
print ("\n\nOGN DDB report utility:\n" )
sql1="SELECT count(*)  as cnt , usr_adress FROM devices, users where dev_userid = usr_id group by usr_id;"

prt=False
import config				# import configuration values
DBuser=config.DBuser
DBpasswd=config.DBpasswd
DBname='glidernet_devicesdb'
print("MySQL: Database:", DBname)
conn1 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBname)

curs1 = conn1.cursor()			# cursor for devices destination			         
curs2 = conn1.cursor()			# cursor for devices destination			         
curs1.execute("select count(*) from devices;")
rowg = curs1.fetchone() 	
print("\n\nOGNDDB devices", rowg[0])
curs1.execute("select count(*) from trackedobjects;")
rowg = curs1.fetchone() 	
print("OGNDDB trackedobjects", rowg[0])
curs1.execute("select count(*) from users;")
rowg = curs1.fetchone() 	
print("OGNDDB users", rowg[0], "\n\n")

cnt=0					# counter of records
cnterr=0				# counter of devices defined as Flarm but not in the Flarm range
cntmany=0				# counter of devices defined as Flarm but not in the Flarm range
cntzero=0				# counter of devices defined as Flarm but not in the Flarm range
cntnonzero=0				# counter of devices defined as Flarm but not in the Flarm range
cntobj=0
cntdev=0
cntICAOok = 0
cntICAOnotok = 0
cntFLARMok = 0
cntFLARMnotok = 0
cntFANETok = 0
cntFANETnotok = 0
cntOGNT = 0
cntUNKW = 0

print ("\n\nUsers with many devices registered:\n" )
curs1.execute("SELECT usr_id, usr_adress FROM users ;")
rowg = curs1.fetchone() 		# find number of devices on the original table	
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

print("Table USERS Records:", cnt, "Users with no devices", cnterr)
print("Table USERS Records:", cnt, "Users with many (>30) devices", cntmany,"\n\n")

curs1.execute("SELECT air_id FROM trackedobjects ;")
rowg = curs1.fetchone() 		# find number of devices on the original table	
while rowg:				# go thru all the user
      objid 		= rowg[0] 	# object ID
      sql2="SELECT count(*)  as cnt  FROM devices where dev_flyobj = '"+str(objid)+"' ;"
      curs2.execute(sql2)
      devrow = curs2.fetchone()		# find number of devices on the original table	
      count 		= devrow[0] 	# email addr
      if count == 0:
         cntzero += 1
      if count  > 1:
         cntnonzero += 1
      cntobj += 1				# increase the counter
      rowg = curs1.fetchone()		# next device
# end of while


print("Table FLYOBJ Records:", cntobj, "Trackedobject with no devices", cntzero)
print("Table FLYOBJ Records:", cntobj, "Trackedobject with many (>1) devices", cntnonzero,"\n\n")

curs1.execute("SELECT dev_id, air_acreg, dev_type from devices, trackedobjects where dev_flyobj = air_id ;")
rowg = curs1.fetchone() 		# find number of devices on the original table	
while rowg:				# go thru all the user
      devid 		= rowg[0] 	# object ID
      reg 		= rowg[1] 	# registration
      devtype 		= rowg[2] 	# device type
      
      if devtype == 1:
         if (checkreg(reg,devid)):
             cntICAOok += 1
         else:
             cntICAOnotok += 1
      elif devtype == 2:
         r=checkreg(reg, devid)
         if (r==1 or r==0):		# either OK or not OK
             cntFLARMnotok += 1
         elif (r == 2):			# if unkown ??? may be a good flarm
             f=checkflarm(devid)
             if (checkflarm(devid)):
                cntFLARMok  +=1
             else:
                cntFLARMnotok += 1
           
      elif devtype == 3:
             cntOGNT += 1 
      elif devtype == 8:
         if (checkfanet(devid)):
             cntFANETok += 1
         else:
             cntFANETnotok += 1
      else:
             cntUNKW +=1 

      cntdev += 1			# increase the counter
      rowg = curs1.fetchone()		# next device
# end of while


conn1.commit()				# commit the changes
#
# ------------- REPORTS ---------------------
#
print("Table DEVICES Records:", cntdev, "Devices ICAO OK:", cntICAOok, ", Not OK", cntICAOnotok, ", FLARM OK:", cntFLARMok, ", Not OK", cntFLARMnotok, ", FANET OK:", cntFANETok, ", Not OK", cntFANETnotok, ", OGN Trackers:", cntOGNT,cntUNKW, "\n\n")
print("Total:", cntICAOok+cntICAOnotok+cntFLARMok+cntFLARMnotok+cntOGNT+cntFANETok+cntFANETnotok,cntUNKW)
conn1.commit()				# close destination database
conn1.close()				# close destination database
