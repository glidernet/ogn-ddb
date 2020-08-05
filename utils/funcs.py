#!/usr/bin/python3

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
       
