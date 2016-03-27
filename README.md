# ogn-ddb: OGN Devices DataBase

Offical server at <http://ddb.glidernet.org>.

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


Example:
```
#DEVICE_TYPE,DEVICE_ID,AIRCRAFT_MODEL,REGISTRATION,CN,TRACKED,IDENTIFIED
'F','0123BC','LS-4','X-0123','23','Y','Y'
'F','DEADBE','DR-400','X-EABC','','N','N'
```

### /download/?j=1
This returns all devices of the DDB in JSON. The output validates against the [ogn-ddb-schema-1.0.0](ogn-ddb-schema-1.0.0.json).

### /download/download-fln.php
This returns the device database in a flarmnet-compatible format.

## ToDo
- finish multi languages management
- document accurate meaning of `tracked` and `identified`
