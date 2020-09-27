#!/usr/bin/python3
######################################################################
# This program migrate the current OGN DDB to the new format
######################################################################
import MySQLdb                  # the SQL data base routines
import json
import os
import csv
import argparse
import urllib.request
import urllib.error
import urllib.parse
from ddbfuncs import *

def repl (text, objid):		# replace the registration mistypes
    
    if len(text) == 0 or text[0] == " ":
       return ("R-"+str(objid))
    text=text.replace("_","-")
    text=text.replace(" ","")
    if text[0] == "-":
       text="NOREG"+text
       if len(text) > 7:
          text=text[0:7]
    return(text)

def build(conn1):		# build the JSON table with all the devices in a format compatible with V1.0
#
# build the download table
#
    curs1 = conn1.cursor()	# cursor for device objects         
    curs3 = conn1.cursor()	# cursor for flying objects         
    curs4 = conn1.cursor()     	# cursor for aircraft 
    curs5 = conn1.cursor() 	# cursor for device types        
    curs5.execute("select * from devtypes;")	# get the table of device types (ICAO, Flarm, OGNT, ....)
    devtypes=curs5.fetchall()	# get all types 
    #print (devtypes)
    curs1.execute("select * from devices;")	# get all the devices, one by one
    rowg1 = curs1.fetchone()
    #{"devices":[{"device_type":"F","device_id":"000000","aircraft_model":"HPH 304CZ-17","registration":"OK-7777","cn":"KN","tracked":"Y","identified":"Y"},
    download=[]
    while rowg1 :		# go thru all devices
        devtype=devtypes[rowg1[2]][2]		# the device type converted
        curs3.execute("select * from trackedobjects where air_id = "+str(rowg1[3])+" ;")	# get the flying objects
        rowg2 = curs3.fetchone()
        selcmd="select ac_type from aircraftstypes where ac_id = "+str(rowg2[1])		# get the aircraft type
        curs4.execute(selcmd)
        rowg3 = curs4.fetchone()
        if rowg3 == None:	# in case of no aircraft type
           actype="NONE"
        else:
           actype=rowg3[0]
        if rowg1[5]:
           tracked='N'
        else:
           tracked='Y'
        if rowg1[6]:
           identified='N'
        else:
           identified='Y'
        if rowg1[8]==1:
           idtype='INTERNAL'
        else:
           idtype='ICAO'
        r={			# entries on table
           "device_type":     devtype,  
           "device_id":       rowg1[0],
           "aircraft_model":  actype,
           "registration":    rowg2[2], 
           "cn":              rowg2[3], 
           "tracked":         tracked,  
           "identified":      identified, 
           "device_idtype":   idtype, 
           "uniqueid":        rowg1[9]  
          }

        download.append(r)
        rowg1 = curs1.fetchone()
        rowg2 = curs3.fetchone()
    return(download)

def buildjson(conn1):		# build the JSON table with all the devices in a format compatible with V1.0
    download=build(conn1)
    jsondownload={"devices": download}
    j=json.dumps(jsondownload, indent=4)
    j=json.dumps(jsondownload)
    return j

def buildcsv(conn1,csvfilename, prt=False):	# build the CSV file
    download=build(conn1)
    r={					# entries on table
           "device_type":     1,  
           "device_id":       1,
           "aircraft_model":  " ",
           "registration":    " ",
           "cn":              " ",
           "tracked":         "Y",
           "identified":      "Y",
           "device_idtype":   1,  
           "uniqueid":        1
      }
    dd= r.keys()
    ddl=[]
    for d in dd:
        ddl.append(d)
    if prt:
       print (ddl)

    with open(csvfilename, 'w', newline='') as csvfile:
         fieldnames = ddl
         writer = csv.DictWriter(csvfile, fieldnames=fieldnames,quoting=csv.QUOTE_ALL, quotechar="'")
         ddl=[]
         for d in dd:
             ddl.append(d.upper())
         writer.writeheader()
         #csvfile.write(ddl)
         for ll in download:
             writer.writerow(ll)
    csvfile.close()
    return

#
# --------------------------------------------------------------------------------------------------- #
#
pgmver="2.0"
print ("\n\nOGN DDB migration utility",pgmver,":\n" )
print (    "==============================:\n" )
#----------------------aprslog.py start-----------------------#
parser = argparse.ArgumentParser(description="OGN DDB migration utility")
parser.add_argument('-p',  '--print',     required=False,
                    dest='prt',   action='store', default=False)
parser.add_argument('-t',  '--TRK',     required=False,
                    dest='trk',   action='store', default=False)
parser.add_argument('-o',  '--ORIGIN',     required=False,
                    dest='origin',   action='store', default='')
parser.add_argument('-d',  '--DEST',     required=False,
                    dest='dest',   action='store', default='')
args = parser.parse_args()
prt      = args.prt			# print on|off
trk      = args.trk			# migrate trkdevices on|off
origin   = args.origin			# origin database
dest     = args.dest			# destination database

sqldropdb  ="DROP DATABASE IF EXISTS `glidernet_devicesdb` ; "
sqlcopyacft="truncate `glidernet_devicesdb`.`aircraftstypes` ; \
INSERT INTO `glidernet_devicesdb`.`aircraftstypes`(`ac_id`, `ac_type`, `ac_cat`) SELECT `ac_id`, `ac_type`, `ac_cat` FROM `glidernet_devicesdb_original`.`aircrafts`;"

sqlcopyusers="truncate `glidernet_devicesdb`.`users` ; \
INSERT INTO `glidernet_devicesdb`.`users`(`usr_id`, `usr_adress`, `usr_pw`) SELECT `usr_id`, `usr_adress`, `usr_pw` FROM `glidernet_devicesdb_original`.`users`;"

sqlcopytmpu="truncate `glidernet_devicesdb`.`tmpusers` ; \
INSERT INTO `glidernet_devicesdb`.`tmpusers`(`tusr_adress`, `tusr_pw`, `tusr_validation`, `tusr_time`) SELECT `tusr_adress`, `tusr_pw`, `tusr_validation`, `tusr_time` FROM `glidernet_devicesdb_original`.`tmpusers`;"

lastfix={}				# table with the last fix found
url="http://glidertracking1.fai.org"
url="http://localhost"
lastfix, oldestdeviceseen = buildlastfix(lastfix,url,prt=prt)		# build the lastfix table
cntlastfix=len(lastfix)			# number of entries on the lastfix table
#print(lastfix)
import config				# import configuration values
DBuser=config.DBuser
DBpasswd=config.DBpasswd
DBnameOrig='glidernet_devicesdb_original'
DBnameDest='glidernet_devicesdb'
if origin == ' ':
   DBnameOrig=origin
if dest == ' ':
   DBnameDest=dest
DBfile="./glidernet_devicesdb.sql"	# schema file
print("MySQL: Database:", DBnameOrig, DBnameDest)
conn  = MySQLdb.connect(user=DBuser, passwd=DBpasswd) # connect for seting the new DB
curs  = conn.cursor()			# cursor for devices destination
curs.execute(sqldropdb)			# delete the DB ... just in case
lines=''
for line in open(DBfile):		# build the database from schema definition
    line=line.strip()
    if prt:
       print("DB: ", line)
    if len(line) > 0 and line[0:2] != '--': 	# ignore comment lines 

       if line[-1] != ';':		# it is end of SQL line ???
          lines += line			# add to the previous line
       else:
          if len(lines) == 0:		# if it is a single line
             curs.execute(line)
          else:
             lines += line		# add this line and execute the SQL command
             curs.execute(lines)
             lines=''			# just clear the line
             
conn.close();				# close for the time being

print("MySQL: New Database built:",DBnameDest, "From:", DBfile)
					# connect both DBs
conn1 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBnameDest)
conn2 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBnameOrig)

curs1 = conn1.cursor()			# cursor for devices destination			         
curs2 = conn2.cursor()			# cursor devices origin         
					# copy the tables that do not change aircraft, users, tempusers
curs1.execute(sqlcopyacft)		# copy aircraftstypes
curs1.execute(sqlcopyusers)		# copy users table
curs1.execute(sqlcopytmpu)		# copy temp users table
curs1.execute("truncate devices;")	# delete all records just in case
curs1.execute("truncate trackedobjects;")
conn1.commit()				# commit the changes
oldestdeviceseen= oldestdeviceseen["lastFixTx"] #check on the LASTFIX table what is the oldest entry
print ("Devices seen since:", oldestdeviceseen, cntlastfix, "By:", url)
curs2.execute("select count(*) from devices;")
rowg = curs2.fetchone() 		# find number of devices on the original table	
print("DevicesDB Original", rowg[0])	# report number of devices origin

cnt=1					# counter of records
cnterr=0				# counter of devices defined as Flarm but not in the Flarm range
cntOK=0					# counter of devices defined as Flarm but it should be ICAO
cnticao=[0,0,0]				# counter of ICAO devices
cntICAO=0				# counter of ICAO devices 
cntICAON=0				# counter of ICAO devices 
cntICAOlf=0				# counter of ICAO devices 
cntINT=0				# counter of ICAO devices 
cntFLARM=0				# counter of FLARM devices
cntOGNT=0				# counter of OGN trackers
cntLF=0					# counter of LASTFIX corrected
cntFANET=0				# counter of OGN trackers
cntNAVITER=0				# counter of OGN trackers
cntdtype=[0,0,0,0]			# counter by device type
cntseen=0				# counter of devices seen recently

curs2.execute("select * from devices order by dev_id;")	# get all the devices on the original table
rowg2 = curs2.fetchone()		# one by one

while rowg2:				# go thru all the devices
      dev_id 		= rowg2[0] 	# device ID
      dev_type 		= rowg2[1] 	# device type (ICAO, Flarm, OGNT, ...)
      cntdtype[dev_type] += 1		# increase the counter by device type
      dev_actype 	= rowg2[2]	# aircraft type  
      dev_acreg 	= repl(rowg2[3],cnt)# aircraft registration like EC-xxx
      dev_accn 		= rowg2[4]	# competition ID 
      dev_userid 	= rowg2[5]	# Id of person registering the device 
      dev_notrack 	= rowg2[6]	# no tract requested 
      dev_noident 	= rowg2[7] 	# no identofication requested
      if dev_type == 1:			# if ICAO ID type
         IDID="ICA"+dev_id		# ID for search
         cntICAO += 1			# increase the counter
         cr=checkreg(dev_acreg, dev_id)
         cnticao[cr] += 1		# increas counter by error type 0, 1, 2
         devtyp=2			# the device tyep is a Flarm
         idtype=2 			# the ID Type is ICAO
         cntICAON += 1			# increase the counter
         #if not cr:  print ("CRicao:", cr, dev_acreg, dev_id)
      if dev_type == 2:			# if Flarm type
         devtyp=2			# the device tyep is a Flarm
         IDID="FLR"+dev_id		# ID for search
         cntFLARM += 1			# increase the counter
         cr=checkflarmint(dev_id)	# check if it is in a flarm addr range
         if not cr: 			# if it is not in a flarm range
            #print ("CRFlarm:", cr, dev_acreg, dev_id)
            cnterr += 1			# increase the error counter
            cr=checkreg(dev_acreg, dev_id) # check if it is a ICAO addr 
            if cr == 1:  		# if so ???
               #print ("CRicao+:", cr, dev_acreg, dev_id)
               cntOK += 1		# increase the counter
               idtype=2 		# the ID Type is ICAO
               cntICAON += 1		# increase the counter
            else:
               idtype=1			# address type is INTERNAL
               cntINT +=1		# address type is INTERNAL
         else:
               idtype=1			# address type is INTERNAL
               cntINT +=1		# address type is INTERNAL

         if IDID not in lastfix and idtype != 2: # try to see if in last fix
            TID = "ICA" + dev_id        # try as ICAOa
            if TID in lastfix:
               cntLF += 1		# increase the counter
               idtype=2 		# the ID Type is ICAO
               cntICAON += 1		# increase the counter
               cntICAOlf += 1		# increase the counter
               cntINT -=1		# address type is INTERNAL
            else:
               TID = "OGN" + dev_id     # try as OGN tracker
               if TID in lastfix:
                  if prt:
                     print ("OGNT fixed by LastFix:",  dev_id, dev_acreg, dev_accn, dev_actype)
                  cntLF += 1		# increase the counter
                  idtype=1 		# the ID Type is internal
                  devtyp=3		# the device tyep is a OGNT
      if dev_type == 3:			# if OGN tracker type
         devtyp=3			# the device tyep is a OGNT
         IDID="OGN"+dev_id		# ID for search
         cntOGNT += 1			# increase the counter
         cr=checkreg(dev_acreg, dev_id) # check if it is a ICAO addr 
         if cr == 1:  			# if so ???
               #print ("CRicao+:", cr, dev_acreg, dev_id)
               cntOK += 1		# increase the counter
               idtype=2 		# the ID Type is ICAO
               cntICAON += 1		# increase the counter
         else:
               idtype=1			# address type is INTERNAL
               cntINT +=1			# address type is INTERNAL
         if IDID not in lastfix and idtype != 2:	# try to see if in last fix
            TID = "ICA" + dev_id        # try as ICAOa
            if TID in lastfix:
               cntLF += 1		# increase the counter
               idtype=2 		# the ID Type is ICAO
               cntICAON += 1		# increase the counter
               cntICAOlf += 1		# increase the counter
               cntINT -=1		# address type is INTERNAL
      if checkfanet(dev_id,lastfix,prt): # count FANET devices
         cntFANET += 1
         if prt:
            print ("FANET:", dev_acreg, dev_id, dev_type, dev_actype)
         devtyp = 8			# set as FANET device
         idtype=1 			# the ID Type is internal
      if checknaviter(dev_id,lastfix):		# count NAVITER devices
         cntNAVITER += 1
         if prt:
            print ("NAVITER Reg:", dev_acreg, "ID:", dev_id, "DevType:", dev_type, "AcType:", dev_actype)
         devtyp = 4			# set as NAVITER OUDIE device
         idtype=1 			# the ID Type is internal

      #print (dev_id, dev_type, dev_actype, dev_acreg, dev_accn, dev_userid, dev_notrack, dev_noident)
					# convert that data in two tables: devices and aircraft objects
      inscmd1 = "insert into trackedobjects values ("+str(cnt)+"," + str(dev_actype) + ",'" + str(dev_acreg) + "','" + str(dev_accn) + "'," + str(dev_userid) +",1,'','','')"
      inscmd2 = "insert into devices values ('" + str(dev_id) + "',0," + str(devtyp) + ","+str(cnt)+"," + str(dev_userid) + "," + str(dev_notrack) + "," + str(dev_noident) + ", 1,"+str(idtype)+",'0')"
      #print (inscmd1)
      curs1.execute(inscmd1)		# add data to the flying object table
      curs1.execute(inscmd2)		# add data to devices table
      cnt += 1				# increase the counter
      if IDID in lastfix:
            cntseen += 1
      rowg2 = curs2.fetchone()		# next device
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

#
# ------------- Generate the CSV and JSON files ---------------------
#
j=buildjson(conn1)			# build the json table
os.system('rm  '+"DEVICES.json")	# remove file just in case
jsonfile = open ("DEVICES.json", 'w')	
jsonfile.write(j)
jsonfile.close()                       	# close the JSON file 
c=buildcsv(conn1, "DEVICES.csv", prt=prt)

# Migrate TRKDEVICES table to the new OGN DDB 

if trk:
   DBname='APRSLOG'
   print("Migrating TRKDEVICES to OGN DDB\n")
   print("MySQL: Database:", DBname, DBnameDest)
   conn1 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBnameDest)
   conn2 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBname)
   curs1 = conn1.cursor()		# cursor for trkdevices origin
   curs2 = conn2.cursor()		# cursor for trkdevices destination			       
   curs1.execute("SELECT dvt_name, dvt_id FROM devtypes;")
   devtypesl=curs1.fetchall()  
   devtypes=[]
   for d in devtypesl:
       devtypes.append(d[0][0:4])
   #print (devtypes)
   curs1.execute("SELECT ac_type, ac_id FROM aircraftstypes ;")
   acfttypesl=curs1.fetchall()  
   acfttypes=[]
   for d in acfttypesl:
       acfttypes.append(d[0].upper().lstrip())
   #print (acfttypes)
   curs2.execute("SELECT * FROM TRKDEVICES ORDER BY devicetype;")	# get all the devices on the original table
   row = curs2.fetchone()		# one by one

   while row:				# go thru all the devices

      dev_id 		= row[2] 	# device ID
      dev_psw 		= row[3] 	# device password
      dev_accn 		= row[4] 	# competition ID
      model 		= row[5].upper().lstrip()# model 
      dev_acreg		= row[6] 	# registration 
      active 		= row[7] 	# active
      actype		= row[8] 	# device type
      flarmid 		= row[9] 	# Flarm ID
      idtype		= 1		# by default type internal
      if actype in devtypes:
         devtyp=devtypes.index(actype)
      else:
         devtyp=0
      if model in acfttypes:
         dev_actype=acfttypes.index(model)+1	# index +1 as zero do not exist
      else:
         dev_actype=362
         if model != '' and model != "NOMODEL":
            print("Val:", dev_id, dev_psw, dev_accn, model, dev_acreg, active, actype, devtyp, flarmid)

      if flarmid == '' or flarmid == None:			# if FlarmID is provided
         dev_userid=99999
         dev_notrack=0
         dev_noident=0
         #curs1.execute("SELECT air_id, air_userid FROM trackedobjects WHERE air_acreg = '"+dev_acreg+"' AND air_accn = '"+dev_accn+"';")
         curs1.execute("SELECT air_id, air_userid FROM trackedobjects WHERE air_acreg = '"+dev_acreg+"' ;")
         obj=curs1.fetchone()
         if obj == None:		# if no object with that registration and comp id
            inscmd1 = "insert into trackedobjects values ("+str(cnt)+"," + str(dev_actype) + ",'" + str(dev_acreg) + "','" + str(dev_accn) + "'," + str(dev_userid) +","+str(active)+",'','','')"
            curs1.execute(inscmd1)	# add data to the flying object table
            inscmd2 = "insert into devices values ('" + str(dev_id) + "','"+str(dev_psw)+"'," + str(devtyp) + ","+str(cnt)+"," + str(dev_userid) + "," + str(dev_notrack) + "," + str(dev_noident) + ", "+str(active)+","+str(idtype)+",'0')"
            curs1.execute(inscmd2)	# add data to devices table
            cnt += 1			# increase the counter
         else:				# just add the new device to the tracked object	
            airid=obj[0]
            dev_userid=obj[1]
            inscmd2 = "INSERT INTO devices VALUES ('" + str(dev_id) + "','"+str(dev_psw)+"'," + str(devtyp) + ","+str(airid)+"," + str(dev_userid) + "," + str(dev_notrack) + "," + str(dev_noident) + ", 1,"+str(idtype)+",'0')"
            #print("AirID1:", airid, dev_userid, dev_acreg, dev_accn, inscmd2)
            curs1.execute(inscmd2)	# add data to devices table
      else:
         if flarmid[0:3] == "ICA" and devtyp == 2:
            idtype=2
         else:
            idtype=1
         flarmid=flarmid[3:]
         
         selcmd1="SELECT dev_flyobj, dev_userid FROM devices where dev_id = '"+str(flarmid)+"';"
         curs1.execute(selcmd1)
         obj=curs1.fetchone()
         if obj == None:
            print ("Wrong Flarmid:", flarmid, selcmd1)
            print("Val:", dev_id, dev_psw, dev_accn, model, dev_acreg, active, actype, devtyp, flarmid)
            row = curs2.fetchone()	# one by one
            continue
         airid=obj[0]
         dev_userid=obj[1]
         
         inscmd2 = "INSERT INTO devices VALUES ('" + str(dev_id) + "','"+str(dev_psw)+"'," + str(devtyp) + ","+str(airid)+"," + str(dev_userid) + "," + str(dev_notrack) + "," + str(dev_noident) + ", "+str(active)+","+str(idtype)+",'0')"
         #print("AirID2:", airid, dev_userid, dev_acreg, dev_accn, inscmd2)
         curs1.execute(inscmd2)		# add data to devices table
         conn1.commit()			# commit the changes

      row = curs2.fetchone()		# one by one
# end of while

   conn1.commit()			# commit the changes
print ("Regs: ", cnt-1)
curs1.execute("SELECT count(*) FROM devices;")
rowg = curs1.fetchone() 	
print("\n\nOGNDDB devices", rowg[0])
curs1.execute("SELECT count(*) FROM trackedobjects;")
rowg = curs1.fetchone() 	
print("OGNDDB trackedobjects", rowg[0],"\n\n")
conn1.commit()				# close destination database
conn1.close()				# close destination database
conn2.commit()				# close origin database
conn2.close()				# close origin database
# report results
ICAOcnt = 0
for i in cnticao:
    ICAOcnt += i 
print ("Nerrs detected ... device assigned as Flarm but not in Flarm range: ", cnterr, "\nICAO IDs detected:", ICAOcnt, cnticao, "Wrong ICAO ID, OK ICAO ID, Unkown ICAOID\nFlarm that should be ICAO:", cntOK, "It matches the ICAO ID", cntICAO)
print ("\n\nNumber of ICAO devices:", cntICAO,"\nNumber of FLARM devices:", cntFLARM,"\nNumber of OGN trackers detected: ", cntOGNT, "\nTotal:", cntICAO+cntFLARM+cntOGNT)
print ("\n\nNumber of FANET devices:", cntFANET, "\nNumber of Naviter devices:", cntNAVITER, "\nFlarms found in LASTFIX corrected:", cntLF)
print ("\n\nNumber of devices seen registered: ", cntseen, "out of:", len(lastfix))
print ("\n\nNumber of devices seen ICAO: ", cntICAON, "Internal:", cntINT,"Total:", cntICAON+cntINT)
print ("\n\nNumber of ICAO orig:", cntICAO, "Flarms that should be ICAO", cntOK, "Corrected by lastfix:", cntICAOlf, "Total:", cntICAO+cntOK+cntICAOlf)

# end of program
