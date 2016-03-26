# ogn-ddb
OGN Devices DataBAse

Offical server at <http://ddb.glidernet.org>.

## API-Endpoints
### /download
This returns a CSVish-File. Each value is quoted with `'`,
Each line starting with `#` is a comment.

Field           | Possible Values
--------------- | -------------------------------
device\_type    | `^[A-F0-9]{6}$`
device\_id      | any string
aircraft\_model | `^[FIO]$`
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

### /download/download-fln.php
This returns the device database in a flarmnet-compatible format.

## ToDo
- finish multi languages management
- document accurate meaning of `tracked` and `identified`
