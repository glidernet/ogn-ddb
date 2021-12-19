#!/usr/bin/python3
import urllib.request
import urllib.error
import urllib.parse
import json

ranges= [
                {  "S": 0x008011, "E": 0x008fff, "R": "ZS-" },
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
                {  "S": 0x4B0000, "E": 0x4BFFFF, "R": "HB-" },
                {  "S": 0x480000, "E": 0x487FFF, "R": "PH-" },
                {  "S": 0x840000, "E": 0x87FFFF, "R": "JA-" },
                {  "S": 0x340000, "E": 0x34FFFF, "R": "EC-" },
                {  "S": 0x380000, "E": 0x3BFFFF, "R": "F-" },
                {  "S": 0x300000, "E": 0x33FFFF, "R": "I-" },
                {  "S": 0x400000, "E": 0x43FFFF, "R": "G-" },
                {  "S": 0xA00000, "E": 0xAFFFFF, "R": "N-" },
                {  "S": 0x3C0000, "E": 0x3FFFFF, "R": "D-" }
    ]
def dumpICAOranges():
    return(ranges)
def checkreg(reg, ICAOID):	# check if the registration matches the ICAO ID
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

def checkflarmint(flarmid):	# check if the flarm ID is in the ranges of the assigned by Flarm 
    if flarmid[0] == 'D' or flarmid[0] == '1' or flarmid[0] == '2':
       return(True)		# OK
    else:
       return(False)		# not OK

def checkflarmlf(flarmid, lastfix): # check if the flarm ID seen in lastfix is OK

    IDID = "FLR" + flarmid	# try as Flarm device
    if IDID in lastfix:		# check if that ID has been seen
       return(True)		# OK
    else:
       return(False)		# not OK

def checkogntlf(ogntid, lastfix): # check if the flarm ID seen in lastfix is OK

    IDID = "OGN" + ogntid	# try as OGNT device
    if IDID in lastfix:		# check if that ID has been seen
       return(True)		# OK
    else:
       return(False)		# not OK

def checkfanet(fanetid,lastfix,prt=False): # check if the fanetid ID is in the ranges of the assigned by fanet
    if fanetid[0] == 'E' and fanetid[1] == '0' :
       return(True)		# OK
    IDID = "FNB" + fanetid	# try as FANET device
    if IDID in lastfix:		# check if that ID has been seen
       if prt:
          print("FANET FNB:", fanetid)
       return(True)		# OK
    IDID = "FLR" + fanetid	# try as FANET/FALRM device
    if IDID in lastfix:		# check if that ID has been seen
       lf=lastfix[IDID]
       if lf['station'][0:3] == "FNB":
            if prt:
               print("FANET FNB:", fanetid, lf['station'])
            return(True)
          
    return(False)		# not OK

def checknaviter(navid,lastfix):

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
    results=lastfixgetapidata(url+"/lastfix.php")
    lastfix=results["lastfix"]
    if prt:
       print(json.dumps(lastfix,indent=4))
    for lfix in lastfix:
        if not "flarmId" in lfix:
           break

        lf[lfix["flarmId"]]=lfix
        lfixseen=lfix
    return lf,lfixseen

def checkwithlastfix(lastfix, devid, devtyp, devidtype):
    if devtyp == 1 and devidtype == 2:
       IDID = 'ICA'+devid
    elif devtyp == 1 and devidtype == 1:
       IDID = 'FLR'+devid
    elif devtyp == 2:
       IDID = 'FLR'+devid
    elif devtyp == 3 and devidtype == 1:
       IDID = 'OGN'+devid
    elif devtyp == 4 and devidtype == 1:
       IDID = 'NAV'+devid
    elif devtyp == 8 and devidtype == 1:
       IDID = 'FAN'+devid
    else:
       IDID = 'RDN'+devid
    if IDID in lastfix:
            return(True)
    else:
            return(False)
#
# --------------------------------------------------------------------------------------------------- #
#

