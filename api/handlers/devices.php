<?php

/**
 * GET /api/devices
 * List all devices belonging to the authenticated user.
 */
function handle_devices_list($dbh)
{
    $user_id = api_authenticate($dbh);
    $devices = device_list($dbh, $user_id);

    $devtype_map = array(1 => 'I', 2 => 'F', 3 => 'O');
    $out = array();
    foreach ($devices as $d) {
        $out[] = device_to_api($d, $devtype_map);
    }

    api_response(200, array('devices' => $out));
}

/**
 * GET /api/devices/{id}
 * Get a single device belonging to the authenticated user.
 */
function handle_device_get($dbh, $devid)
{
    $user_id = api_authenticate($dbh);
    $device  = device_get($dbh, $devid, $user_id);

    if ($device === null) {
        api_error(404, 'Device not found');
    }

    $devtype_map = array(1 => 'I', 2 => 'F', 3 => 'O');
    api_response(200, device_to_api($device, $devtype_map));
}

/**
 * POST /api/devices
 * Create a new device.
 *
 * Body (JSON): { "device_id", "device_type" (I/F/O), "aircraft_type_id",
 *               "registration", "cn", "no_track", "no_ident" }
 */
function handle_device_post($dbh)
{
    $user_id = api_authenticate($dbh);
    $body    = api_parse_body();

    $devid   = isset($body['device_id'])       ? $body['device_id']       : '';
    $devtype = isset($body['device_type'])      ? api_devtype_to_int($body['device_type']) : 0;
    $actype  = isset($body['aircraft_type_id']) ? (int)$body['aircraft_type_id'] : 0;
    $acreg   = isset($body['registration'])     ? $body['registration']    : '';
    $accn    = isset($body['cn'])               ? $body['cn']              : '';
    $notrack = !empty($body['no_track'])  ? 1 : 0;
    $noident = !empty($body['no_ident'])  ? 1 : 0;

    $san    = device_sanitise($devid, $acreg, $accn);
    $errors = device_validate($san['devid'], $devtype, $actype, $san['acreg'], $san['accn'], true);

    if (!empty($errors)) {
        api_error(422, implode(', ', $errors));
    }

    $result = device_save($dbh, $san['devid'], $devtype, $actype, $san['acreg'], $san['accn'], $notrack, $noident, $user_id);

    if (!$result['ok']) {
        $status = ($result['error'] === 'error_devexists') ? 409 : 422;
        api_error($status, $result['error']);
    }

    $device = device_get($dbh, $san['devid'], $user_id);
    $devtype_map = array(1 => 'I', 2 => 'F', 3 => 'O');
    api_response(201, device_to_api($device, $devtype_map));
}

/**
 * PUT /api/devices/{id}
 * Update an existing device owned by the authenticated user.
 *
 * Body (JSON): { "device_type", "aircraft_type_id", "registration", "cn", "no_track", "no_ident" }
 */
function handle_device_put($dbh, $devid)
{
    $user_id = api_authenticate($dbh);

    $existing = device_get($dbh, $devid, $user_id);
    if ($existing === null) {
        api_error(404, 'Device not found');
    }

    $body = api_parse_body();

    $devtype = isset($body['device_type'])      ? api_devtype_to_int($body['device_type']) : (int)$existing['dev_type'];
    $actype  = isset($body['aircraft_type_id']) ? (int)$body['aircraft_type_id']           : (int)$existing['dev_actype'];
    $acreg   = isset($body['registration'])     ? $body['registration']                    : $existing['dev_acreg'];
    $accn    = isset($body['cn'])               ? $body['cn']                              : $existing['dev_accn'];
    $notrack = isset($body['no_track'])         ? ($body['no_track'] ? 1 : 0)             : (int)$existing['dev_notrack'];
    $noident = isset($body['no_ident'])         ? ($body['no_ident'] ? 1 : 0)             : (int)$existing['dev_noident'];

    $san    = device_sanitise($devid, $acreg, $accn);
    $errors = device_validate($san['devid'], $devtype, $actype, $san['acreg'], $san['accn'], true);

    if (!empty($errors)) {
        api_error(422, implode(', ', $errors));
    }

    $result = device_save($dbh, $san['devid'], $devtype, $actype, $san['acreg'], $san['accn'], $notrack, $noident, $user_id);

    if (!$result['ok']) {
        api_error(422, $result['error']);
    }

    $device = device_get($dbh, $san['devid'], $user_id);
    $devtype_map = array(1 => 'I', 2 => 'F', 3 => 'O');
    api_response(200, device_to_api($device, $devtype_map));
}

/**
 * DELETE /api/devices/{id}
 * Delete a device owned by the authenticated user.
 */
function handle_device_delete($dbh, $devid)
{
    $user_id = api_authenticate($dbh);

    if (!device_delete($dbh, $devid, $user_id)) {
        api_error(404, 'Device not found');
    }

    api_response(200, array('deleted' => $devid));
}

// --- Helpers ---

function api_parse_body()
{
    $raw = file_get_contents('php://input');
    if (!empty($raw)) {
        $data = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }
    }
    // Fall back to POST fields
    return $_POST;
}

function api_devtype_to_int($type)
{
    $map = array('I' => 1, 'F' => 2, 'O' => 3);
    $type = strtoupper(trim($type));
    return isset($map[$type]) ? $map[$type] : 0;
}

function device_to_api($device, $devtype_map)
{
    return array(
        'device_id'      => $device['dev_id'],
        'device_type'    => isset($devtype_map[$device['dev_type']]) ? $devtype_map[$device['dev_type']] : $device['dev_type'],
        'aircraft_type_id' => (int)$device['dev_actype'],
        'registration'   => $device['dev_acreg'],
        'cn'             => $device['dev_accn'],
        'no_track'       => (bool)$device['dev_notrack'],
        'no_ident'       => (bool)$device['dev_noident'],
        'updated_at'     => (int)$device['dev_updatetime'],
    );
}
