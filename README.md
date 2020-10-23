# ogn-ddb: OGN Devices DataBase

Offical server at <http://ddb.glidernet.org>.

## Installation
This project uses the PHP template engine [Twig](http://twig.sensiolabs.org), available via [Composer](https://getcomposer.org/).

1. Download and install [Composer](https://getcomposer.org/download/)

2. Clone repository
   ```
   git clone https://github.com/glidernet/ogn-ddb
   ```

3. Install all dependencies defined in [composer.json](composer.json) (this will install Twig)
   ```
   cd ogn-ddb
   composer update
   ```

There is an [installation guide](INSTALL.md) to run a local development instance of the DDB with [Vagrant](https://www.vagrantup.com/).

## API-Endpoints
### /download
This returns a CSVish-File. Each value is quoted with `'`,
Each line starting with `#` is a comment.

Field           | Possible Values
--------------- | -------------------------------
device\_type    | `^[FIO]$`
device\_id      | `^[A-F0-9]{36}$`
from\_id        | `^[A-F0-9]{36}$`
till\_id        | `^[A-F0-9]{36}$`
aircraft\_model | any string
registration    | any string
cn              | any string
tracked         | `^[YN]$`
identified      | `^[YN]$`
aircraft_type   | `^[1-6]` (optional, with t flag)

Example:
```
#DEVICE_TYPE,DEVICE_ID,AIRCRAFT_MODEL,REGISTRATION,CN,TRACKED,IDENTIFIED,IDTYPE,DEVACTIVE,ACFTACTIVE
'F','000000','HPH 304CZ-17','OK-7777','KN','Y','Y','Internal','Y','Y'
'F','000002','LS-6 18','OY-XRG','G2','Y','Y','Internal','Y','Y'
'F','00000D','Ka-8','D-1749','W5','Y','Y','ICAO','Y','Y'
'F','000010','Unknown','D-EEAC','AC','Y','Y','Internal','Y','Y'
```

#### URL parameters
parameter    | values | default | effect
------------ | -------|---------|---------------------------------------------------------------
t            | 0/1    | 0       | show field `aircraft_type` if set to 1
j            | 0/1    | 0       | forces JSON output when set to 1 (regardless of accept header)
device\_id   | csv    | n/a     | select a comma separated list of device ID's              
from\_id     | id     | n/a     | select list of device ID's starting from this provided ID
till\_id     | id     | n/a     | select list of device ID's until this provided ID
registration | csv    | n/a     | select a comma separated list of registrations
cn           | csv    | n/a     | select a comma separated list of callsigns


### /download/?j=1
This returns all devices of the DDB in JSON. The output validates against the [ogn-ddb-schema-2.0](docs/ogn-ddb-schema-2.0.json).
### /download/?j=2
Adds the aircraft table as well to the JSON output.
### /download/?j=3
Print the JSON on a pretty-print format
Example:
```
{"devices":[
{"device_type":"O","device_id":"000000","aircraft_model":"HPH 304CZ-17","registration":"OK-7777","cn":"KN","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"1","device_aprsid":"OGN000000"},
{"device_type":"F","device_id":"000002","aircraft_model":"LS-6 18","registration":"OY-XRG","cn":"G2","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"2","device_aprsid":"FLR000002"},
{"device_type":"F","device_id":"000010","aircraft_model":"Unknown","registration":"D-EEAC","cn":"AC","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"3","device_aprsid":"FLR000010"},
{"device_type":"F","device_id":"000011","aircraft_model":"Skylane Airlony","registration":"D-MTEW","cn":"EW","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"4","device_aprsid":"FLR000011"},
{"device_type":"F","device_id":"000013","aircraft_model":"PA-28","registration":"D-EZIP","cn":"IP","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"5","device_aprsid":"FLR000013"},
{"device_type":"O","device_id":"000015","aircraft_model":"Paraglider","registration":"36445","cn":"","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"6","device_aprsid":"OGN000015"},
{"device_type":"F","device_id":"0000FD","aircraft_model":"Taurus","registration":"F-JRDN","cn":"DN","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"7","device_aprsid":"FLR0000FD"},
{"device_type":"F","device_id":"000114","aircraft_model":"","registration":"","cn":"","tracked":"N","identified":"N","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"8","device_aprsid":"FLR000114"},
{"device_type":"O","device_id":"000146","aircraft_model":"Paraglider","registration":"000146","cn":"","tracked":"Y","identified":"Y","device_idtype":"Internal","device_active":"Y","aircraft_active":"Y","uniqueid":"9","device_aprsid":"OGN000146"},
....
]}

```
### /download/download-fln.php
This returns the device database in a flarmnet-compatible format.

### /API/V1/?action=
Invoke the OGN DDB thru an API
Example:
```
URL request:
http://DDB.glidernet.org/API/V1/?action=add&login=john@acme.org&password=psw123456&device_id=757899&device_type=O&registration=EC-ACA&cn=AC&acftype=Discus

Reply:
{ 'NumberObjects':'18556', 'NumberDevices':'18545' ,'ValidUser' : 'john@acme.org' ,'Action': 'add' ,'DeviceId' : '757899', 'DeviceType' : '3', 'DeviceIdType' : '1' , 'Registration' : 'EC-ACA', 'cn' : 'AC','AircraftType' : '105' ,'FlyobjMsg' :' flyobj_inserted ', 'FlyobjID' : '22394' ,'DeviceID' :' 757899', 'DeviceMsg': 'device_inserted'}
{
    "NumberObjects": "18556",
    "NumberDevices": "18545",
    "ValidUser": "john@acme.org",
    "Action": "add",
    "DeviceId": "757899",
    "DeviceType": "3",
    "DeviceIdType": "1",
    "Registration": "EC-ACA",
    "cn": "AC",
    "AircraftType": "105",
    "FlyobjMsg": " flyobj_inserted ",
    "FlyobjID": "22394",
    "DeviceID": "757899",
    "DeviceMsg": "device_inserted"
}

```
Parameters:
action=add		Add an object and a devicea
acction=object		Add just an registered flying object
action=delobject	Delete an object
action=device		Add a registered device
action=deldevice	Delete a device

login=			The email ID of the user
password=		The user password
device_id		The device id 
device_type		The device type (F for Flarm, O for OGNtractker, etc, ...)
deviceidtype		The device ID type (1 for internal, 2 for ICAO)
registration		The country registration of the registered object (like EC-ACA)
cn			The competition ID (like AC)
acftype			The aircarft type of the registered object (Like Janus ... you can obtain all the types from the JSON output ?j=3)

## ToDo
- finish multi languages management
- document accurate meaning of `tracked` and `identified`
- document the API as described on the docs directory
