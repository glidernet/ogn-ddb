<?php

const DEVICE_EXPIRATION_DELTA = 450 * 24 * 60 * 60;

/**
 * Validate device fields. Returns an array of error keys (empty = valid).
 */
function device_validate($devid, $devtype, $actype, $acreg, $accn, $owner_confirmed)
{
    $errors = array();

    if (!$owner_confirmed) {
        $errors[] = 'error_owner';
    }

    $devid = strtoupper($devid);
    if (strlen($devid) !== 6 || !preg_match('/^[A-F0-9]{6}$/', $devid)) {
        $errors[] = 'error_devid';
    }

    if ($devtype < 1 || $devtype > 3) {
        $errors[] = 'error_devtype';
    }

    if (empty($actype) || !is_numeric($actype)) {
        $errors[] = 'error_actype';
    }

    $acreg = trim(preg_replace('/[^A-Za-z0-9._ -]/', '', $acreg));
    if (strlen($acreg) > 7) {
        $errors[] = 'error_acreg';
    }

    $accn = trim(preg_replace('/[^A-Za-z0-9._ -]/', '', $accn));
    if (strlen($accn) > 3) {
        $errors[] = 'error_accn';
    }

    return $errors;
}

/**
 * Sanitise and normalise device input fields.
 * Returns array: ['devid', 'acreg', 'accn']
 */
function device_sanitise($devid, $acreg, $accn)
{
    return array(
        'devid' => strtoupper(trim($devid)),
        'acreg' => trim(preg_replace('/[^A-Za-z0-9._ -]/', '', $acreg)),
        'accn'  => trim(preg_replace('/[^A-Za-z0-9._ -]/', '', $accn)),
    );
}

/**
 * Save (create or update) a device.
 *
 * Returns array:
 *   ['ok' => true,  'action' => 'inserted'|'updated']
 *   ['ok' => false, 'error'  => 'error_key']
 */
function device_save($dbh, $devid, $devtype, $actype, $acreg, $accn, $notrack, $noident, $user_id)
{
    // Check device limit for this user
    $limit_req = $dbh->prepare('SELECT usr_device_limit FROM users WHERE usr_id = :us');
    $limit_req->bindParam(':us', $user_id);
    $limit_req->execute();
    $user_row = $limit_req->fetch();
    $device_limit = $user_row ? (int)$user_row['usr_device_limit'] : 20;

    // Check if device already exists
    $req = $dbh->prepare('SELECT dev_id, dev_userid, dev_updatetime FROM devices WHERE dev_id = :de');
    $req->bindParam(':de', $devid);
    $req->execute();

    $upd = false;
    $trf = false;

    if ($req->rowCount() === 1) {
        $result = $req->fetch();
        $req->closeCursor();

        if ($result['dev_userid'] == $user_id) {
            $upd = true;
        } else {
            $ttime = time() - DEVICE_EXPIRATION_DELTA;
            if ($ttime >= $result['dev_updatetime']) {
                $upd = true;
                $trf = true;
            } else {
                return array('ok' => false, 'error' => 'error_devexists');
            }
        }
    } else {
        $req->closeCursor();

        // New device: check limit
        $count_req = $dbh->prepare('SELECT COUNT(dev_id) FROM devices WHERE dev_userid = :us');
        $count_req->bindParam(':us', $user_id);
        $count_req->execute();
        $count = (int)$count_req->fetchColumn();
        if ($count >= $device_limit) {
            return array('ok' => false, 'error' => 'error_device_limit');
        }
    }

    $ttime = time();

    if ($upd) {
        if ($trf) {
            $ins = $dbh->prepare('UPDATE devices SET dev_type=:dt, dev_actype=:ty, dev_acreg=:re, dev_accn=:cn, dev_notrack=:nt, dev_noident=:ni, dev_updatetime=:ti, dev_userid=:us WHERE dev_id=:de');
        } else {
            $ins = $dbh->prepare('UPDATE devices SET dev_type=:dt, dev_actype=:ty, dev_acreg=:re, dev_accn=:cn, dev_notrack=:nt, dev_noident=:ni, dev_updatetime=:ti WHERE dev_id=:de AND dev_userid=:us');
        }
    } else {
        $ins = $dbh->prepare('INSERT INTO devices (dev_id, dev_type, dev_actype, dev_acreg, dev_accn, dev_userid, dev_notrack, dev_noident, dev_updatetime) VALUES (:de, :dt, :ty, :re, :cn, :us, :nt, :ni, :ti)');
    }

    $ins->bindParam(':de', $devid);
    $ins->bindParam(':dt', $devtype);
    $ins->bindParam(':ty', $actype);
    $ins->bindParam(':re', $acreg);
    $ins->bindParam(':cn', $accn);
    $ins->bindParam(':nt', $notrack);
    $ins->bindParam(':ni', $noident);
    $ins->bindParam(':ti', $ttime);
    $ins->bindParam(':us', $user_id);

    if ($ins->execute()) {
        $ins->closeCursor();
        return array('ok' => true, 'action' => $upd ? 'updated' : 'inserted');
    }

    $ins->closeCursor();
    return array('ok' => false, 'error' => 'error_insert_device');
}

/**
 * Delete a device owned by the given user.
 * Returns true on success, false if device not found / not owned.
 */
function device_delete($dbh, $devid, $user_id)
{
    $req = $dbh->prepare('SELECT dev_id FROM devices WHERE dev_id = :de AND dev_userid = :us');
    $req->bindParam(':de', $devid);
    $req->bindParam(':us', $user_id);
    $req->execute();

    if ($req->rowCount() !== 1) {
        $req->closeCursor();
        return false;
    }

    $req->closeCursor();
    $del = $dbh->prepare('DELETE FROM devices WHERE dev_id = :de AND dev_userid = :us');
    $del->bindParam(':de', $devid);
    $del->bindParam(':us', $user_id);
    $del->execute();
    return true;
}

/**
 * Fetch all devices for a user, with expiration flag and aircraft type.
 */
function device_list($dbh, $user_id)
{
    $ttime = time() - DEVICE_EXPIRATION_DELTA;
    $req = $dbh->prepare(
        'SELECT *, (:ti >= dev_updatetime) AS dev_expired
         FROM devices, aircrafts
         WHERE dev_userid = :us AND dev_actype = ac_id
         ORDER BY dev_id ASC'
    );
    $req->bindParam(':us', $user_id);
    $req->bindParam(':ti', $ttime);
    $req->execute();
    return $req->fetchAll();
}

/**
 * Fetch a single device for a user.
 */
function device_get($dbh, $devid, $user_id)
{
    $req = $dbh->prepare('SELECT * FROM devices WHERE dev_id = :de AND dev_userid = :us');
    $req->bindParam(':de', $devid);
    $req->bindParam(':us', $user_id);
    $req->execute();
    if ($req->rowCount() === 1) {
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

/**
 * Generate a new API token for a user. Stores the SHA-256 hash in users table.
 * Returns the plaintext token (shown once).
 */
function token_generate($dbh, $user_id)
{
    $plaintext = bin2hex(random_bytes(24)); // 48-char hex token
    $hash      = hash('sha256', $plaintext);
    $hint      = substr($plaintext, 0, 6) . '****' . substr($plaintext, -6);

    $upd = $dbh->prepare('UPDATE users SET usr_token_hash = :th, usr_token_hint = :hi WHERE usr_id = :us');
    $upd->bindParam(':th', $hash);
    $upd->bindParam(':hi', $hint);
    $upd->bindParam(':us', $user_id);
    $upd->execute();

    return $plaintext;
}
