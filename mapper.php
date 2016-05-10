<?php
class Mapper {
    public function __construct($_db) {
        $this->db = $_db;
    }
}

class DeviceMapper extends Mapper{
    private $device_types = array(1 => 'ICAO', 2 => 'FLARM', 3 => 'OGN');

    private function deviceFromData($data) {
        return array('id' => $data['dev_id'],
                     'type' => $this->device_types[$data['dev_type']],
                     'aircraft_model_id' => $data['dev_actype'],
                     'aircraft_registration' => $data['dev_acreg'],
                     'aircraft_competition_id' => $data['dev_accn'],
                     'tracked' => $data['dev_notrack'] == 0,
                     'identified' => $data['dev_noident'] == 0,
                     'owner_id' => $data['dev_userid']
                    );
    }

    public function getCounter() {
       $req = $this->db->query('SELECT count(dev_id) as nb FROM devices ');
       $result = $req->fetch();
       $req->closeCursor();
       return $result['nb'];
    }

    public function getDeviceById($device_id) {
        $cursor = $this->db->prepare('SELECT dev_id, dev_type, dev_actype, dev_acreg, dev_accn, dev_userid, dev_notrack, dev_noident
                                      FROM devices
                                      WHERE dev_id=:dev_id');
        $cursor->bindParam(':dev_id', $device_id);
        $result = $cursor->execute();
        $data = $cursor->fetch();
        $cursor->closeCursor();
        // print(!$data ? "Ja" : "nein");
        // print_r($data);
        if (!$data) {
            return null;
        }
        return $this->deviceFromData($data);
    }

    public function save($device) {
        $dev_notrack = ($device['tracked'] ? 0 : 1);
        $dev_noident = ($device['identified'] ? 0 : 1);
        $dev_type = array_flip($this->device_types)[$device['type']];
        $cursor = $this->db->prepare('INSERT INTO devices (dev_id, dev_type, dev_actype, dev_acreg, dev_accn, dev_userid, dev_notrack, dev_noident)
                                      VALUES (:dev_id, :dev_type, :dev_actype, :dev_acreg, :dev_accn, :dev_userid, :dev_notrack, :dev_noident)
                                      ON DUPLICATE KEY UPDATE
                                          dev_type=VALUES(dev_type),
                                          dev_actype=VALUES(dev_actype),
                                          dev_acreg=VALUES(dev_acreg),
                                          dev_accn=VALUES(dev_accn),
                                          dev_userid=VALUES(dev_userid),
                                          dev_notrack=VALUES(dev_notrack),
                                          dev_noident=VALUES(dev_noident)');
        $cursor->bindParam(':dev_id', $device['id']);
        $cursor->bindParam(':dev_type', $dev_type);
        $cursor->bindParam(':dev_actype', $device['aircraft_model_id']);
        $cursor->bindParam(':dev_acreg', $device['aircraft_registration']);
        $cursor->bindParam(':dev_accn', $device['aircraft_competition_id']);
        $cursor->bindParam(':dev_userid', $device['owner_id']);
        $cursor->bindParam(':dev_notrack', $dev_notrack);
        $cursor->bindParam(':dev_noident', $dev_noident);
        $result = $cursor->execute();
        $cursor->closeCursor();
        return $result;
    }

    public function deleteDeviceById($device_id) {
        $cursor = $this->db->prepare('SELECT dev_id FROM devices WHERE dev_id=:dev_id');
        $cursor->bindParam(':dev_id', $device_id);
        $cursor->execute();
        $count = $cursor->rowCount();
        $cursor->closeCursor();
        if ($count == 0) {
            // No device found
            return false;
        }

        $cursor = $this->db->prepare('DELETE FROM devices where dev_id=:dev_id');
        $cursor->bindParam(':dev_id', $device_id);
        $cursor->execute();
        $cursor->closeCursor();
        return true;
    }

    public function getDevices() {
        $cursor = $this->db->prepare('SELECT dev_id, dev_type, dev_actype, dev_acreg, dev_accn, dev_userid, dev_notrack, dev_noident
                                      FROM devices
                                      ORDER BY dev_id ASC');
        $result = $cursor->execute();
        $devices = [];
        while ($row = $cursor->fetch()) {
            $devices[] = $this->deviceFromData($row);
        }
        $cursor->closeCursor();
        return $devices;
    }
}

class AircraftModelMapper extends Mapper {
    private $aircraft_categories = array(1 => 'glider/motorglider',
                                         2 => 'plane',
                                         3 => 'ultralight',
                                         4 => 'helicoter',
                                         5 => 'drone',
                                         6 => 'other',
                                        );

    private function aircraftModelsFromData($data) {
        return array('id' => $data['ac_id'],
                     'name' => $data['ac_type'],
                     'category' => $this->aircraft_categories[$data['ac_cat']],
                    );
    }

    public function getAircraftModels() {
        $cursor = $this->db->prepare('SELECT ac_id, ac_type, ac_cat
                                      FROM aircrafts
                                      ORDER BY ac_cat, ac_type');
        $result = $cursor->execute();
        $aircraft_models = [];
        while ($row = $cursor->fetch()) {
            $aircraft_models[] = $this->aircraftModelsFromData($row);
        }
        $cursor->closeCursor();
        return $aircraft_models;
    }
}

class UserMapper extends Mapper {
    private function UserFromData($data) {
        return array('email_address' => $data['usr_adress'],
                    );
    }

    public function getUserByCredentials($address, $password) {
        $cursor = $this->db->prepare('SELECT usr_id, usr_adress, usr_pw
                                      FROM users
                                      WHERE usr_adress=:usr_adress');
        $cursor->bindParam(':usr_adress', $address);
        $result = $cursor->execute();

        if (!$cursor->rowCount() == 1) {
            return null;
        }
        $data = $cursor->fetch();
        $cursor->closeCursor();
        if (password_verify($password, $data['usr_pw'])) {
            return $this->UserFromData($data);
        } else {
            return null;
        }
    }
}
