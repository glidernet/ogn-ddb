# Endpoints

## List devices
```
GET /devices
```

### Parameters

Name         | Type    | Description
-------------|---------|------------
tracked      | boolean | Indicates the tracked state of the devices to return. Default: `null`
identified   | boolean | Indicates the identified state of the devices to return. Default: `null`
ids          | string  | A comma-separated list of device ids.

### Response
```
Status: 200 OK
```
```json
[
{
  "id": "0123BC",
  "type": "F",
  "aircraft_model_id": 209,
  "aircraft_model_name": "LS-4",
  "aircraft_model_category": "glider",
  "aircraft_registration": "X-0123",
  "aircraft_competition_id": "X01",
  "tracked": true,
  "identified": true
},
{
  "id": "DEADBE",
  "type": "F",
  "aircraft_model_id": 112,
  "aircraft_model_name": "DR-400",
  "aircraft_model_category": "plane",
  "aircraft_registration": "X-EABC",
  "aircraft_competition_id": null,
  "tracked": false,
  "identified": false
}
]
```

## Get a single device
```
GET /devices/:id
```

### Response
```
Status: 200 OK
```
```json
{
  "id": "DEADBE",
  "type": "F",
  "aircraft_model_id": 112,
  "aircraft_model_name": "DR-400",
  "aircraft_model_category": "plane",
  "aircraft_registration": "X-EABC",
  "aircraft_competition_id": "LOL",
  "tracked": true,
  "identified": true
}
```

## Create a device
```
POST /devices
```

### Parameters
Name                      | Type    | Description (possible values)
--------------------------|---------|------------------------------
id                        | string  | Required. The device id of the device, also known as FLARM-Radio-ID or OGN address (`^[A-F0-9]{6}$`).
type                      | string  | Required. The type of the device (`F`: FLARM, `O`: OGN Tracker, `I`: FLARM with ICAO code as device id).
aircraft\_model\_id       | integer | The model id of the aircraft which is equipped with the device, see also [List aircraft models](#list-aircraft-models)
aircraft\_registration    | string  | The registration of the aircraft which is equipped with the device (utf-8, maximum length is 7 characters).
aircraft\_competition\_id | string  | The competition id of the aircraft which is equipped with the device (utf-8, maximum length is 3 characters).
tracked                   | boolean | The tracked state of the device.
identified                | boolean | The identified state of the device.

### Example
```json
{
  "id": "DEADBE",
  "type": "F",
  "aircraft_model_id": 112,
  "aircraft_registration": "X-EABC",
  "aircraft_competition_id": "XXL",
  "tracked": true,
  "identified": true
}
```

### Response
```
Status: 201 Created
Location: https://ddb.glidernet.org/api/v1/devices/DEADBE
```
```json
{
  "id": "DEADBE",
  "type": "F",
  "aircraft_model_id": 112,
  "aircraft_model_name": "DR-400",
  "aircraft_model_category": "plane",
  "aircraft_registration": "X-EABC",
  "aircraft_competition_id": "XXL",
  "tracked": false,
  "identified": false
}
```


## Edit a device
```
PATCH /devices/:id
```

### Parameters
Name                      | Type    | Description (possible values)
--------------------------|---------|------------------------------
type                      | string  | Required. The type of the device (`F`: FLARM, `O`: OGN Tracker, `I`: FLARM with ICAO code as device id).
aircraft\_model\_id       | integer | The model id of the aircraft which is equipped with the device, see also [List aircraft models](#list-aircraft-models)
aircraft\_registration    | string  | The registration of the aircraft which is equipped with the device (utf-8, maximum length is 7 characters).
aircraft\_competition\_id | string  | The competition id of the aircraft which is equipped with the device (utf-8, maximum length is 3 characters).
tracked                   | boolean | The tracked state of the device.
identified                | boolean | The identified state of the device.

### Example
```json
{
  "type": "F",
  "aircraft_model_id": 209,
  "aircraft_registration": "X-0123",
  "aircraft_competition_id": "X01",
  "tracked": true,
  "identified": true
}
```

### Response
```
Status: 200 OK
```
```json
{
  "id": "DEADBE",
  "type": "F",
  "aircraft_model_id": 209,
  "aircraft_model_name": "LS-4",
  "aircraft_model_category": "glider",
  "aircraft_registration": "X-0123",
  "aircraft_competition_id": "X01",
  "tracked": true,
  "identified": true
}
```

## Remove a device
```
DELETE /devices/:id
```

### Response
```
Status: 204 No Content
```

## List aircraft models
```
GET /aircraft_models
```

### Response
```
Status: 200 OK
```
```json
[
{
  "id": 112,
  "name": "DR-400",
  "category": "glider"
},
{
  "id": 209,
  "name": "LS-4",
  "category": "plane"
}
]
```

## Get a single aircraft model
```
GET /aircraft_models/:id
```

### Response
```
Status: 200 OK
```
```json
{
  "id": 112,
  "name": "DR-400",
  "category": "glider"
}
```
