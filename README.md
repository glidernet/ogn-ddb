# ogn-ddb
OGN Devices DataBase

### This fork implements:
* Twig Template Engine (HTML moved to templates directory)
* Language in separate language files 
* Filters in the download section

### On my todo list are:
* Fancy landing page that explains what the DDB is for
* Fancy fillindevice page that explains (better) what the options are for
* Some documentation of the download functions

### To install [Twig](http://twig.sensiolabs.org)
* install package manager [Composer](http://getcomposer.org) (if it's not installed already)
* run `composer update` in the DDB directory. This will automatically install twig, as defined in the composer.json file

## API-Endpoints
### /download
This returns a CSVish or JSON File. In the CSV file, each value is quoted with `'`,
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

####Filters:
filter       | values | default  | effect
------------ | -------|----------|----------------------------------------------------------------------------
t            | 0/1    | 0        | adds the aircraft_type field to output when set to 1
j            | 0/1    | 0        | forces JSON output when set to 1 (regardless of accept header) 
device\_id   | csv    | n/a      | select a comma separated list of device ID's              
registration | csv    | n/a      | select a comma separated list of registrations
cn           | csv    | n/a      | select a comma separated list of callsigns

### /download/download-fln.php
returns the Devices Database in a flarmnet-compatible format

# ToDo
- finish multi languages management
- document accurate meaning of `tracked` and `identified`
