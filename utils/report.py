#!/usr/bin/python3
######################################################################
# This program prin several reports on the status of the OGN DDB
######################################################################
import MySQLdb                  # the SQL data base routines
import json
import os
from ddbfuncs import *
import argparse
#
# --------------------------------------------------------------------------------------------------- #
#
print ("\n\nOGN DDB report utility:\n" )
parser = argparse.ArgumentParser(description="OGN DDB report utility program to check the health of the database")
parser.add_argument('-p',  '--print',     required=False,
                    dest='prt',   action='store', default=False)
args = parser.parse_args()
prt      = args.prt			# print on|off

lastfix={}				# table with the last fix found
url="http://glidertracking1.fai.org"
url="http://localhost"
lastfix, oldestdeviceseen = buildlastfix(lastfix,url,prt=False)		# build the lastfix table
cntlastfix=len(lastfix)			# number of entries on the lastfix table

import config				# import configuration values
DBuser=config.DBuser
DBpasswd=config.DBpasswd
DBname='glidernet_devicesdb'
print("MySQL: Database:", DBname)
conn1 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBname)

curs1 = conn1.cursor()			# cursor for devices destination			         
curs2 = conn1.cursor()			# cursor for devices destination			         
curs1.execute("SELECT COUNT(*) FROM devices;")
rowg = curs1.fetchone() 	
print("\n\nOGNDDB devices", rowg[0])
curs1.execute("SELECT COUNT(*) FROM trackedobjects;")
rowg = curs1.fetchone() 	
print("OGNDDB trackedobjects", rowg[0])
curs1.execute("SELECT COUNT(*) FROM users;")
rowg = curs1.fetchone() 	
print("OGNDDB users", rowg[0])
curs1.execute("SELECT COUNT(*) FROM tmpusers;")
rowg = curs1.fetchone() 	
print("OGNDDB temp users", rowg[0])
curs1.execute("SELECT COUNT(*) FROM tmpusers WHERE tusr_time < (unix_timestamp(NOW()) - 30*24*3600);")
rowg = curs1.fetchone() 	
print("OGNDDB temp users older than one month", rowg[0])

cnt=0					# counter of records
cnterr=0				# counter of devices defined as Flarm but not in the Flarm range
cntmany=0				# counter of devices defined as Flarm but not in the Flarm range
cntzero=0				# counter of devices defined as Flarm but not in the Flarm range
cntnonzero=0				# counter of devices defined as Flarm but not in the Flarm range
cntobj=0
cntdev=0
cntDEVok=0				# counter for device seen in LASTFIX and are OK with registration
cntICAOok = 0
cntICAOnotok = 0
cntFLARMok = 0
cntFLARMICAOok = 0
cntFLARMnotok = 0
cntOGNTok = 0
cntOGNTnotok = 0
cntFANETok = 0
cntFANETnotok = 0
cntFLARM = 0
cntOGNT = 0
cntNAVI = 0
cntUNKW = 0
cntseen = 0

print ("\nUsers with many devices registered:" )
curs1.execute("SELECT usr_id, usr_adress FROM users  ;")

rowg = curs1.fetchone() 		# find number of devices on the original table	
while rowg:				# go thru all the user
      usrid 		= rowg[0] 	# userid
      email 		= rowg[1] 	# email addr
      sql2="SELECT count(*)  as cnt  FROM devices where dev_userid = '"+str(usrid)+"' ;"
      curs2.execute(sql2)
      devrow = curs2.fetchone()		# find number of devices on the original table	
      count 		= devrow[0] 	# email addr
      if count == 0:
         #print ("User: ", email)
         cnterr += 1
      if count  > 30:
         if prt:
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

curs1.execute("SELECT dev_id, air_acreg, dev_type, dev_idtype FROM devices, trackedobjects WHERE dev_flyobj = air_id ;")
rowg = curs1.fetchone() 		# find number of devices on the original table	
while rowg:				# go thru all the user
      devid 		= rowg[0] 	# object ID
      reg 		= rowg[1] 	# registration
      devtype 		= rowg[2] 	# device type
      devidtype		= rowg[3] 	# device ID type
      
      if devtype == 1:			# FLarmICAO deprecated now
         IDID='ICA'+devid
         if (checkreg(reg,devid)):
             cntICAOok += 1		# should be 0
         else:
             cntICAOnotok += 1		# should be 0

      elif devtype == 2:		# FLARM (both ICAO & INTERNAL) IDs
         cntFLARM += 1 
         IDID='FLR'+devid
         r=checkreg(reg, devid)		# check if ID matches with registration ICAO check 
         if (r==0):			# not OK the ID do not match the registration
             if checkflarmlf(devid,lastfix): # check first if we had seen on the network
                   cntFLARMok  +=1
             else:   
                   cntFLARMnotok += 1	# probably wrong ID or not seen yet
         elif (r == 1 and devidtype == 2): # OK reg and ICAO match
             cntFLARMok += 1
             cntFLARMICAOok += 1
         elif (r == 2):			# if unkown ??? may be a good flarm
             #if=checkflarmint(devid)	# check the internal ID
             if (checkflarmint(devid) and devidtype == 1): # flarm range and ID type Internal
                cntFLARMok  +=1
             else:
                if checkflarmlf(devid,lastfix): # check if last seen on the network
                   cntFLARMok  +=1	# Ok in that case
                else:   
                   cntFLARMnotok += 1	# probably wrong ID or not seen yet
           
      elif devtype == 3:		# OGN tracker
         IDID='OGN'+devid
         cntOGNT += 1 
         if checkogntlf(devid,lastfix): # check if last seen on the network
                   cntOGNTok  +=1	# Ok in that case
         else:   
                   cntOGNTnotok += 1	# probably wrong ID or not seen

      elif devtype == 4:		# Naviter
         IDID='NAV'+devid
         cntNAVI += 1 

      elif devtype == 8:
         IDID='FAN'+devid
         if (checkfanet(devid,lastfix)):
             cntFANETok += 1
         else:
             cntFANETnotok += 1
      else:
             cntUNKW +=1 
      if checkwithlastfix(lastfix, devid, devtype, devidtype):
         cntDEVok +=1
      if IDID in lastfix:
            cntseen += 1

      cntdev += 1			# increase the counter
      rowg = curs1.fetchone()		# next device
# end of while


conn1.commit()				# commit the changes
#
# ------------- REPORTS ---------------------
#
print("Table DEVICES Records:", cntdev, \
 "\nDevices ICAO (deprecated) OK:", cntICAOok, ", Not OK", cntICAOnotok, \
 "\nFlarms: ", cntFLARM, "FLARM OK:", cntFLARMok, "(of those ICAO ok:", cntFLARMICAOok, "), Not OK", cntFLARMnotok, \
 "\nOGN Trackers:", cntOGNT, "OGNT seen and OK:", cntOGNTok, ", not seen:", cntOGNTnotok, \
 "\nFANET OK:", cntFANETok, ", Not OK", cntFANETnotok, \
 "\nNaviter devs:", cntNAVI, "\n\n")
print("Total:", cntICAOok+cntICAOnotok+cntFLARMok+cntFLARMnotok+cntOGNT+cntFANETok+cntFANETnotok+cntNAVI,cntUNKW)
print ("\n\nNumber of devices seen registered: ", cntseen, "out of:", len(lastfix), "since: ", oldestdeviceseen['lastFixTx'])
print ("\nNumber of devices seen properly registered: ", cntDEVok)

conn1.commit()				# close destination database
conn1.close()				# close destination database
