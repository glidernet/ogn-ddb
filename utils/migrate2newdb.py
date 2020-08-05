#!/usr/bin/python3
######################################################################
# This program migrate the current OGN DDB to the new format
######################################################################
import MySQLdb                  # the SQL data base routines
import json
import os
import csv
import urllib.request
import urllib.error
import urllib.parse

def checkreg(reg, ICAOID):	# check if the registration matches the ICAO ID
    ranges= [
                {  "S": 0x008011, "E": 0x008fff, "R": "ZS-" },
                {  "S": 0x390000, "E": 0x398000, "R": "F-G" },
                {  "S": 0x398000, "E": 0x38FFFF, "R": "F-H" },
                {  "S": 0x3C4421, "E": 0x3C8421, "R": "D-A" },
                {  "S": 0x3C0001, "E": 0x3C8421, "R": "D-A" },
                {  "S": 0x3C8421, "E": 0x3CC000, "R": "D-B" },
                {  "S": 0x3C2001, "E": 0x3CC000, "R": "D-B" },
                {  "S": 0x3CC000, "E": 0x3D04A8, "R": "D-C" },
                {  "S": 0x3D04A8, "E": 0x3D4950, "R": "D-E" },
                {  "S": 0x3D4950, "E": 0x3D8DF8, "R": "D-F" },
                {  "S": 0x3D8DF8, "E": 0x3DD2A0, "R": "D-G" },
                {  "S": 0x3DD2A0, "E": 0x3E1748, "R": "D-H" },
                {  "S": 0x3E1748, "E": 0x3EFFFF, "R": "D-I" },
                {  "S": 0x448421, "E": 0x44FFFF, "R": "OO-" },
                {  "S": 0x458421, "E": 0x45FFFF, "R": "OY-" },
                {  "S": 0x460000, "E": 0x468421, "R": "OH-" },
                {  "S": 0x468421, "E": 0x46FFFF, "R": "SX-" },
                {  "S": 0x490421, "E": 0x49FFFF, "R": "CS-" },
                {  "S": 0x4A0421, "E": 0x4AFFFF, "R": "YR-" },
                {  "S": 0x4B8421, "E": 0x4BFFFF, "R": "TC-" },
                {  "S": 0x740421, "E": 0x74FFFF, "R": "JY-" },
                {  "S": 0x760421, "E": 0x768421, "R": "AP-" },
                {  "S": 0x768421, "E": 0x76FFFF, "R": "9V-" },
                {  "S": 0x778421, "E": 0x77FFFF, "R": "YK-" },
                {  "S": 0xC00001, "E": 0xC044A9, "R": "C-F" },
                {  "S": 0xC044A9, "E": 0xC0FFFF, "R": "C-G" },
                {  "S": 0xE01041, "E": 0xE0FFFF, "R": "LV-" },
                {  "S": 0x3E0000, "E": 0x3EFFFF, "R": "D-" }
    ]
    for r in ranges:		# check for the registration is in the ranges assigned by country
        #print ("R",r, r["S"], r["E"])
        l=len(r["R"])
        if reg[0:l] == r["R"]:
           idicao=int('0x'+ICAOID, 16)
           if idicao > r["S"] and idicao < r["E"]:
              return (1)	# return TRUE if OK
           else:
              return (0)	# return FALSE if not in the range
    return(2)			# return 2 if not found or UNKOWN 

def checkflarm(flarmid):	# check if the flarm ID is in the ranges of the assigned by Flarm 
    if flarmid[0] == 'D' or flarmid[0] == '1' or flarmid[0] == '2':
       return(True)		# OK
    else:
       return(False)		# not OK

def checkfanet(fanetid):	# check if the fanetid ID is in the ranges of the assigned by fanet
    if fanetid[0] == 'E' and fanetid[1] == '0' :
       return(True)		# OK
    else:
       return(False)		# not OK

def checknaviter(navid):

    IDID = "OGN" + navid	# try as OGN tracker
    if IDID in lastfix:		# check if that ID has been seen
       lf=lastfix[IDID]
       if lf['station'].upper() == "NAVITER":
            return(True)
    IDID = "ICA" + navid	# try as ICAO id
    if IDID in lastfix:		# check if that ID has been seen
       lf=lastfix[IDID]
       if lf['station'].upper() == "NAVITER":
            return(True)
    IDID = "FNT" + navid	# try as FANET device
    if IDID in lastfix:		# check if that ID has been seen
       lf=lastfix[IDID]
       if lf['station'].upper() == "NAVITER":
            return(True)
    IDID = "FLR" + navid	# try as FLARM device
    if IDID in lastfix:		# check if that ID has been seen
       lf=lastfix[IDID]
       if lf['station'].upper() == "NAVITER":
            return(True)
    return (False)
       
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
           "device_idtype":   idtype  
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
           "device_idtype":   1  
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

def lastfixgetapidata(url):             # get the data from the API server

    req = urllib.request.Request(url)   # build the request
    req.add_header("Content-Type", "application/json")
    req.add_header("Content-type", "application/x-www-form-urlencoded")
    r = urllib.request.urlopen(req)     # open the url resource
    js=r.read().decode('UTF-8')
    j_obj = json.loads(js)              # convert from JSON
    return j_obj      

def buildlastfix(lf,url,prt=False):	# build the lastfix table
    #fp=open("LASTFIX.json", 'r')
    #results=json.load(fp)
    #fp.close()
    results=lastfixgetapidata(url+"/lastfix")
    lastfix=results["lastfix"]
    if prt:
       print(json.dumps(lastfix,indent=4))
    for lfix in lastfix:
        if not "flarmId" in lfix:
           break

        lf[lfix["flarmId"]]=lfix
        lfixseen=lfix
    return lf,lfixseen
#
# --------------------------------------------------------------------------------------------------- #
#
print ("\n\nOGN DDB migration utility:\n" )
print (    "=========================:\n" )
sqlcopyat="truncate `glidernet_devicesdb`.`aircraftstypes` ; \
INSERT INTO `glidernet_devicesdb`.`aircraftstypes`(`ac_id`, `ac_type`, `ac_cat`) SELECT `ac_id`, `ac_type`, `ac_cat` FROM `glidernet_devicesdb_original`.`aircrafts`;"

sqlcopyusers="truncate `glidernet_devicesdb`.`users` ; \
INSERT INTO `glidernet_devicesdb`.`users`(`usr_id`, `usr_adress`, `usr_pw`) SELECT `usr_id`, `usr_adress`, `usr_pw` FROM `glidernet_devicesdb_original`.`users`;"

sqlcopytmpu="truncate `glidernet_devicesdb`.`tmpusers` ; \
INSERT INTO `glidernet_devicesdb`.`tmpusers`(`tusr_adress`, `tusr_pw`, `tusr_validation`, `tusr_time`) SELECT `tusr_adress`, `tusr_pw`, `tusr_validation`, `tusr_time` FROM `glidernet_devicesdb_original`.`tmpusers`;"

prt=False
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
print("MySQL: Database:", DBnameOrig, DBnameDest)
conn1 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBnameDest)
conn2 = MySQLdb.connect(user=DBuser, passwd=DBpasswd, db=DBnameOrig)

curs1 = conn1.cursor()			# cursor for devices destination			         
curs2 = conn2.cursor()			# cursor devices origin         
curs1.execute(sqlcopyat)		# copy aircraftstypes
curs1.execute(sqlcopyusers)		# copy users table
curs1.execute(sqlcopytmpu)		# copy temp users table
curs1.execute("truncate devices;")	# delete all records just in case
curs1.execute("truncate trackedobjects;")
conn1.commit()				# commit the changes
oldestdeviceseen= oldestdeviceseen["lastFixTx"]
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
      dev_actype 	= rowg2[2]	# aricraft type  
      dev_acreg 	= repl(rowg2[3],cnt)# aircraft registration like EC-xxx
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
         cr=checkflarm(dev_id)		# check if it is in a flarm addr range
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

         if IDID not in lastfix and idtype != 2:	# try to see if in last fix
            TID = "ICA" + dev_id        # try as ICAOa
            if TID in lastfix:
               cntLF += 1		# increase the counter
               idtype=2 		# the ID Type is ICAO
               cntICAON += 1		# increase the counter
               cntICAOlf += 1		# increase the counter
               cntINT -=1			# address type is INTERNAL
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
               cntINT -=1			# address type is INTERNAL
      if checkfanet(dev_id):		# count FANET devices
         cntFANET += 1
         if prt:
            print ("FANET:", dev_acreg, dev_id, dev_type, dev_actype)
         devtyp = 8			# set as FANET device
         idtype=1 			# the ID Type is internal
      if checknaviter(dev_id):		# count NAVITER devices
         cntNAVITER += 1
         if prt:
            print ("NAVITER Reg:", dev_acreg, "ID:", dev_id, "DevType:", dev_type, "AcType:", dev_actype)
         devtyp = 4			# set as NAVITER OUDIE device
         idtype=1 			# the ID Type is internal

      dev_accn 		= rowg2[4]	# competition ID 
      dev_userid 	= rowg2[5]	# Id of person registering the device 
      dev_notrack 	= rowg2[6]	# no tract requested 
      dev_noident 	= rowg2[7] 	# no identofication requested
      #print (dev_id, dev_type, dev_actype, dev_acreg, dev_accn, dev_userid, dev_notrack, dev_noident)
					# convert that data in two tables: devices and aircraft objects
      inscmd1 = "insert into trackedobjects values ("+str(cnt)+"," + str(dev_actype) + ",'" + str(dev_acreg) + "','" + str(dev_accn) + "'," + str(dev_userid) +",1,'','','')"
      inscmd2 = "insert into devices values ('" + str(dev_id) + "',0," + str(devtyp) + ","+str(cnt)+"," + str(dev_userid) + "," + str(dev_notrack) + "," + str(dev_noident) + ", 1,"+str(idtype)+")"
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
