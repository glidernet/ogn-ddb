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
from\_id        | `^[A-F0-9]{6}$`
till\_id        | `^[A-F0-9]{6}$`
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
from\_id     | id     | n/a     | select list of device ID's starting from this provided ID
till\_id     | id     | n/a     | select list of device ID's until this provided ID
registration | csv    | n/a     | select a comma separated list of registrations
cn           | csv    | n/a     | select a comma separated list of callsigns


### /download/?j=1
This returns all devices of the DDB in JSON. The output validates against the [ogn-ddb-schema-1.0.0](ogn-ddb-schema-1.0.0.json).

### /download/download-fln.php
This returns the device database in a flarmnet-compatible format.

### /api/v1/devices (authenticated)

Authenticated REST API for managing your own devices. Requires a Bearer token (see **API Token** below) or an active login session cookie.

#### Authentication

Each account has one API token. It is shown once after generation (or on first login) in the **device list** page. To get a new token, click **Generate new token** — this immediately invalidates the previous one.

Use the token in API requests via the `Authorization` header:

```
Authorization: Bearer <your_token>
```

#### Endpoints

Method | Endpoint | Description
------ | -------- | -----------
GET | `/api/v1/devices` | List all your devices
GET | `/api/v1/devices/{id}` | Get a single device
POST | `/api/v1/devices` | Create a device
PUT | `/api/v1/devices/{id}` | Update a device
DELETE | `/api/v1/devices/{id}` | Delete a device

`{id}` is the 6-character hex device address (e.g. `DEADBE`).

#### Request / Response format

All requests and responses use JSON (`Content-Type: application/json`).

Device object fields:

Field | Type | Description
----- | ---- | -----------
`device_id` | string | 6-char hex address, e.g. `DEADBE`
`device_type` | string | `I` (ICAO), `F` (Flarm), `O` (OGN)
`aircraft_type_id` | integer | Aircraft type ID (from `aircrafts` table)
`registration` | string | Aircraft registration (max 7 chars)
`cn` | string | Competition number (max 3 chars)
`no_track` | boolean | Opt out of tracking
`no_ident` | boolean | Opt out of identification
`updated_at` | integer | Unix timestamp of last update

#### Examples

```bash
# List devices
curl -H "Authorization: Bearer <token>" https://ddb.glidernet.org/api/v1/devices

# Create a device
curl -X POST -H "Authorization: Bearer <token>" \
     -H "Content-Type: application/json" \
     -d '{"device_id":"DEADBE","device_type":"F","aircraft_type_id":1,"registration":"D-1234","cn":"AB","no_track":false,"no_ident":false}' \
     https://ddb.glidernet.org/api/v1/devices

# Update a device
curl -X PUT -H "Authorization: Bearer <token>" \
     -H "Content-Type: application/json" \
     -d '{"registration":"D-5678"}' \
     https://ddb.glidernet.org/api/v1/devices/DEADBE

# Delete a device
curl -X DELETE -H "Authorization: Bearer <token>" \
     https://ddb.glidernet.org/api/v1/devices/DEADBE
```

#### Error responses

HTTP Status | Meaning
----------- | -------
400 | Bad request
401 | Missing or invalid token / not logged in
404 | Device not found (or not owned by you)
405 | Method not allowed
409 | Device already registered by another user
422 | Validation error (see `error` field in response body)

Each error response body: `{"error": "description"}`

#### Device limit

Each account can manage up to **20 devices** by default. This limit can be raised by an administrator directly in the database.

## ToDo
- finish multi languages management
- document accurate meaning of `tracked` and `identified`
