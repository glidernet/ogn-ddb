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
device\_id      | `^[A-F0-9]{6}$`
aircraft\_model | any string
registration    | any string
cn              | any string
tracked         | `^[YN]$`
identified      | `^[YN]$`
aircraft_type   | `^[1-6]` (optional, with t flag)

Example:
```
#DEVICE_TYPE,DEVICE_ID,AIRCRAFT_MODEL,REGISTRATION,CN,TRACKED,IDENTIFIED
'F','0123BC','LS-4','X-0123','23','Y','Y'
'F','DEADBE','DR-400','X-EABC','','N','N'
```

#### URL parameters
parameter    | values | default | effect
------------ | -------|---------|---------------------------------------------------------------
t            | 0/1    | 0       | show field `aircraft_type` if set to 1
j            | 0/1    | 0       | forces JSON output when set to 1 (regardless of accept header)
device\_id   | csv    | n/a     | select a comma separated list of device ID's              
registration | csv    | n/a     | select a comma separated list of registrations
cn           | csv    | n/a     | select a comma separated list of callsigns


### /download/?j=1
This returns all devices of the DDB in JSON. The output validates against the [ogn-ddb-schema-1.0.0](ogn-ddb-schema-1.0.0.json).

### /download/download-fln.php
This returns the device database in a flarmnet-compatible format.

## ToDo
- finish multi languages management
- document accurate meaning of `tracked` and `identified`
