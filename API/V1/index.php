<?php
//
// this program handles the general API for the OGNDDB
// Website: http://ddb.glidernet.org/API/V1
//

//
// Wrtitten by: Angel Casado - August 2020
//

global $outputmsg;

// -------------------------------------------------------------------------------

// Functions:

function checkflyobj($flyobj, $user)		//check if the flyobj belongs to the user
{
    $dbh = Database::connect();
    $req = $dbh->query("SELECT air_userid FROM trackedobjects WHERE air_id = '".$flyobj."' ;");
    if ($req->rowCount() == 1) {        	// if flyobj exists ???
        $row=$req->fetch();
        if ($row[0] == $user) {
           Database::disconnect();
           return (True);
        }
    }
    Database::disconnect();
    return(False);
}


function getacftype($acftype)			// get the aircraft type ID
{
    $dbh = Database::connect();
    $req = $dbh->query("SELECT ac_id FROM aircraftstypes WHERE ac_type = '".$acftype."' ;");
    if ($req->rowCount() == 1) {        	// if we found it ??
        $row=$req->fetch();
        Database::disconnect();
        return($row[0]);
    }

    Database::disconnect();
    return(-1);
}

function devicetypeslen()			// get the device type length
{
    $dbh = Database::connect();
    $result = $dbh->query('SELECT * FROM devtypes');
    foreach ($result as $row) {

        $devtypeslen[$row['dvt_id']] = (int) $row['dvt_idlen'];
    }
    Database::disconnect();
    return $devtypeslen;
}


function createdevice($devid, $dvct, $deviceidtype, $flyobj, $user)
{

     global $outputmsg;
     $dbh = Database::connect();
     $req = $dbh->prepare('select dev_id, dev_type, dev_idtype, dev_userid from devices where dev_id=:de and dev_type =:dt and dev_idtype=:it');    // test if device is owned 
     $req->bindParam(':de', $devid);
     $req->bindParam(':dt', $dvct);
     $req->bindParam(':it', $deviceidtype);
     $req->execute();

     $upd = false;
     if ($req->rowCount() == 1) {        	// if device already registred
        $result = $req->fetch();
        if ($result['dev_userid'] == $user) {	// and belongs to this user
            $upd = true;
        }        				// if owned by the user then update
        else {
             echo 'Error device exists: '.$devid."\n\n";
             exit();
        }
     }
     $req->closeCursor();
     if (!checkflyobj($flyobj, $user))  {	// check that the flyobject belongs to this user
             echo 'Error object do not belong to this user';
             exit();
     }
     if ($upd) {
            $ins = $dbh->prepare('UPDATE devices SET  dev_idtype=:it, dev_notrack=:nt, dev_noident=:ni, dev_flyobj=:fo, dev_active=:ac WHERE dev_id=:de AND dev_type=:dt AND dev_userid=:us');
     } else {
            $ins = $dbh->prepare('INSERT INTO devices (dev_id, dev_type, dev_userid, dev_notrack, dev_noident, dev_flyobj, dev_active, dev_idtype ) VALUES (:de, :dt,  :us, :nt, :ni, :fo, :ac, :it)');
     }
     $act = 1;
     $fly = (int)$flyobj;
     $notrack=0;
     $noident=0;
     $ins->bindParam(':de', $devid);
     $ins->bindParam(':dt', $dvct);
     $ins->bindParam(':it', $deviceidtype);
     $ins->bindParam(':nt', $notrack);
     $ins->bindParam(':ni', $noident);
     $ins->bindParam(':ac', $act);
     $ins->bindParam(':fo', $fly);
     $ins->bindParam(':us', $user);
     if ($ins->execute()) {    				// insert ok
            if ($upd) {
                $msg = 'device_updated';
            } else {
                $msg = 'device_inserted';
            }
     } else {
            $msg = ' error_insert_device';
     }
     $ins->closeCursor();

     Database::disconnect();
     $outputmsg=$outputmsg.",'DeviceID' :' ".$devid."', 'DeviceMsg': '".$msg."' ";
     return ($devid);

}

function deldevice($devid, $dvct, $deviceidtype, $flyobj, $user)
{

     global $outputmsg;
     if ($devid == 0) {
        echo 'Error device invalid'.$devid;
        return (-1);
     }
     $dbh = Database::connect();
     $req = $dbh->prepare('select dev_id, dev_type, dev_idtype, dev_userid from devices where dev_id=:de and dev_type =:dt and dev_idtype=:it');    // test if device is owned 
     $req->bindParam(':de', $devid);
     $req->bindParam(':dt', $dvct);
     $req->bindParam(':it', $deviceidtype);
     $req->execute();

     $upd = false;
     if ($req->rowCount() == 1) {        	// if device already registred
        $result = $req->fetch();
        if ($result['dev_userid'] == $user) {	// and belongs to this user
            $del = true;
        }        				// if owned by the user then update
        else {
             echo 'Error device do not belong to this user';
             exit();
        }
     }
     else {
             echo 'Error device do not exists: '.$devid."\n\n";
             exit();
     }
     $req->closeCursor();
     if (!checkflyobj($flyobj, $user))  {	// check that the flyobject belongs to this user
             echo 'Error object do not belong to this user';
             exit();
     }
     $req = $dbh->prepare('DELETE FROM  devices WHERE dev_id=:de AND dev_type=:dt AND dev_userid=:us');
     $req->bindParam(':de', $devid);
     $req->bindParam(':dt', $dvct);
     $req->bindParam(':us', $user);
     if ($req->execute()) {    			// delete ok
            $msg = 'Device Deleted';
     } else {
            $msg = ' Error deleting device';
     }
     $req->closeCursor();

     Database::disconnect();
     $outputmsg=$outputmsg.",'DeviceID' :' ".$devid."', 'DeviceMsg': '".$msg."' ";
     return ($devid);

}

function createobj($airid, $acreg, $accn, $actype, $user)
{
    global $outputmsg;
    $dbh = Database::connect();
    $upd = false;
    if ($airid != 0){
        $req = $dbh->prepare('select air_id,air_userid from trackedobjects where air_id=:de');    // test if aircraft  is owned by another account
        $req->bindParam(':de', $airid);
        $req->execute();

        if ($req->rowCount() == 1) {        // if device already registred
            $result = $req->fetch();
            if ($result['air_userid'] == $user) {
                $upd = true;
            }        			// if owned by the user then update
            else {
                echo 'Error object exists'.$airid."\n\n";
                exit();
            }
        }
        $req->closeCursor();
    }
    $acreg=str_replace("_",'-',$acreg);
    if ($acreg == ' ' or $acreg == ''){
        $acreg = 'R-'.$airid;
    }
    if ($upd) {
            $ins = $dbh->prepare('UPDATE trackedobjects SET air_actype=:dt,  air_acreg=:re, air_accn=:cn, air_active=:ac  WHERE air_id=:de AND air_userid=:us');
    } else {
            $airid=0;
            $ins = $dbh->prepare('INSERT INTO trackedobjects (air_id, air_actype , air_acreg, air_accn, air_userid, air_active, air_SARphone, air_SARclub, air_Country  ) VALUES (:de, :dt,  :re, :cn, :us, :ac, :ph, :cl, :co )');
            $phone='';
            $club='';
            $country='';
            $ins->bindParam(':ph', $phone);
            $ins->bindParam(':cl', $club);
            $ins->bindParam(':co', $country);
    }

    $act=1;
    $ins->bindParam(':de', $airid);
    $ins->bindParam(':dt', $actype);
    $ins->bindParam(':re', $acreg);
    $ins->bindParam(':cn', $accn);
    $ins->bindParam(':ac', $act);
    $ins->bindParam(':us', $user);

    if ($ins->execute()) {    	// insert ok, send email
            if ($upd) {
                $msg = 'flyobj_updated ';
            } else {
                $msg = 'flyobj_inserted ';
                $req = $dbh->query('SELECT LAST_INSERT_ID(); ');
                $airid = $req->fetchColumn();
                }
    }
    $outputmsg=$outputmsg." ,'FlyobjMsg' :'".$msg."', 'FlyobjID' : '".$airid."' ";
    Database::disconnect();
    return ($airid);

}

function delobj($airid, $acreg, $accn, $actype, $user)
{
    global $outputmsg;
    if ($airid == 0) {
        echo 'Error object invalid'.$airid;
        return (-1);
    }
    $dbh = Database::connect();
    $req = $dbh->prepare('select air_id, air_userid from trackedobjects where air_id=:de');    // test if aircraft  is owned by another account
    $req->bindParam(':de', $airid);
    $req->execute();

    if ($req->rowCount() == 1) {        // if device already registred
            $result = $req->fetch();
            if ($result['air_userid'] == $user) {
                $del = true;
            }        			// if owned by the user then update
            else {
                echo 'Error object belongs to other user'.$airid;
                exit();
            }
    }
    $req->closeCursor();

    $req = $dbh->prepare('DELETE FROM  trackedobjects WHERE air_id=:de AND air_userid=:us');
    $req->bindParam(':de', $airid);
    $req->bindParam(':us', $user);

    if ($req->execute()) {    	// delete  ok
                $msg = 'flyobj_deleted ';
    }

    $req->closeCursor();			// now delete all the devices associated to this aircraft !!!
    $del = $dbh->prepare('DELETE FROM devices where dev_flyobj=:id AND dev_userid=:us');
    $del->bindParam(':id', $airid);
    $del->bindParam(':us', $user);
    $del->execute();
    $req->closeCursor();			

    $outputmsg=$outputmsg." ,'FlyobjMsg' :'".$msg."', 'FlyobjID' : '".$airid."' ";
    Database::disconnect();
    return ($airid);

}
// -------------------------------------------------------------------------------

// Variables:

$action='';			//action='add' or 'addobject' or 'adddevice'
$login='';			//login='ac@me.com'
$password='';			//password='123456789'
$deviceid='';			//device_id='123456'
$devicetype='';			//device_type='F'
$deviceidtype='';		//device_idtype='Internal'
$objectid='';			//object_id='12345'
$registration='';		//registration='EC-ACA'
$cn='';				//cn='AC'
$acftype='';			//acftype='ASW 20'

$outputmsg='';			// init the JSON output message

include '../../sql.php';	// get the MySQL credentials
$dbh = Database::connect();
$req = $dbh->query('SELECT count(dev_id) FROM devices ');
$nbdevices = $req->fetchColumn();
$req = $dbh->query('SELECT count(air_id) FROM trackedobjects ');
$nbobjects = $req->fetchColumn();
$outputmsg=$outputmsg."{'Numberobjects':'".$nbobjects."', 'NumberDevices':'".$nbdevices."' " ;

$idtypes = array('0' => 'NoDef', '1' => 'Internal', '2' => 'ICAO'); // local definition, it is not worth it to get it from the DB
				// build the device type arrays
$sql0 = 'SELECT dvt_id, dvt_name, dvt_code, dvt_3ltcode, dvt_idlen FROM devtypes;';
$stmt= $dbh->query($sql0);
$devtype = array();		// single dimendional array with just the devtypes and names
$devtyp  = array();		// two dimensional array with the devtypes table
$devt = array(array());		// two dimensional array with the results from the query for the JSON file
$devt=$stmt->fetchAll();
foreach ($devt as $dt){
   $devtype[$dt['dvt_id']]=$dt['dvt_code'];
   $i=$dt['dvt_id'];
   $devtyp [$i]['dvt_id']      = $dt['dvt_id'];
   $devtyp [$i]['dvt_name']    = $dt['dvt_name'];
   $devtyp [$i]['dvt_code']    = $dt['dvt_code'];
   $devtyp [$i]['dvt_3ltcode'] = $dt['dvt_3ltcode'];
   $devtyp [$i]['dvt_idlen']   = $dt['dvt_idlen'];
}

if (!empty($_GET['action'])) {	// check the action: add, object, device, delobject, deldevice
    $action =  $_GET['action'];
}
else {
    $action ='Error INVALID ACTION';
    echo $action;
    exit();
}
// -------------------------------------------
if (!empty($_GET['login'])) {	// check the credentials: login/password
    $login = $_GET['login'];
}
if (!empty($_GET['password'])) {
    $password = $_GET['password'];
}
$password = crypt($password, 'GliderNetdotOrg');
$dbh = Database::connect();
$req = $dbh->prepare('SELECT * FROM users where usr_adress=:us AND usr_pw=:pw');
$req->bindParam(':us', $login);
$req->bindParam(':pw', $password);
$req->execute();
if ($req->rowCount() == 1) {
        $result = $req->fetch();
        $req->closeCursor();
        $user =             $result['usr_id'];
        $outputmsg=$outputmsg.",'ValidUser' : '".$login."' ";
} else {
        echo 'Error Invalid user: '.$login.$password;
        Database::disconnect();
        exit();
}

// -------------------------------------------
if (!empty($_GET['device_id'])) {	// check the device related parameters
    $deviceid = $_GET['device_id'];
}
if (!empty($_GET['device_type'])) {
    $dvctype = strtoupper($_GET['device_type']);
}
if (!empty($_GET['device_idtype'])) {
    $devidtyp = $_GET['device_idtype'];
}
else {
    $devidtyp ='Internal';
}
// -------------------------------------------
if (!empty($_GET['object_id'])) {	// check the object related parameters
    $objectid= $_GET['object_id'];
}
else {
    $objectid =0;
}
if (!empty($_GET['registration'])) {
    $registration =  $_GET['registration'];
}
if (!empty($_GET['cn'])) {
    $cn =  $_GET['cn'];
}
if (!empty($_GET['acftype'])) {
    $acftype =  $_GET['acftype'];
}
// -------------------------------------------
//
//                MAIN LOGIC
//
// -------------------------------------------
$action=strtolower($action);
$outputmsg=$outputmsg.",'Action': '".$action."' ";

if ($action == 'add' or $action == 'device' or $action == 'deldevice') 
{								// validate arguments
     if ($deviceid == ''){
        echo "Error Missing device_id";
        exit();
     }
     $devid = strtoupper($deviceid);
     if (preg_match(' /[A-F0-9]/ ', $devid)) {			// only hex chars
     }                                                          // ok
     else {
        echo 'Error error_devid'.$devid;
        exit(1);
     }

     if ($dvctype == ''){
        echo "Error Missing device_type";
        exit();
     }
     else {
        $cnt=0;
        $dvct=0;
        foreach($devtype as $dt){
          if ($dt == $dvctype){
             $dvct=$cnt;
             break;
             }
          $cnt = $cnt + 1;
          }
          if ($dvct == 0){
             echo "Error Wrong device type: ".$dvctype;
             exit();
             }
        }
     if ($deviceidtype == 'ICAO'){
         $deviceidtype = 2;
         }
     else {
         $deviceidtype = 1;
         }
     $devtypesl=devicetypeslen();			// get the length of the ID for that kind of device type
     if (strlen($devid) > $devtypesl[(int)$devtype]) { 	// the length has to be lower or equal
        echo 'Error error_devidlen: '.strlen($devid)." for this kind of device";
        exit();
    }
    $outputmsg=$outputmsg.",'DeviceId' : '".$devid."', 'DeviceType' : '".$dvct."', 'DeviceIdType' : '".$deviceidtype."' ";
        
}

if ($action == 'add' or $action == 'object' or $action == 'delobject') 
{
     if ($registration == ''){
        echo "Error Missing registration";
        exit();
     }
     $registration = strtoupper($registration);
     if (preg_match(' /[A-F0-9]-/ ', $registration)) {
     }                                                          // ok
     else {
        echo 'Error error_registration'.$registration;
        exit(1);
     }
     if ($cn == ''){
        echo "Error Missing cn";
        exit();
     }
     if ($acftype == ''){
        echo "Error Missing acftype";
        exit();
     }
     else {
          $acft = getacftype($acftype);
          if ($acft <= 0){
              echo "Error Invalid AcfType: ".$acftype;
              exit();
             }
          }
    $outputmsg=$outputmsg.", 'Registration' : '".$registration."',  'cn' : '".$cn."','AircraftType' : '".$acft."' ";
}

if ($action == 'object' ) 	// create or update a tracked object
{
     $flyobj=createobj(0,$registration, $cn, $acft, $user);
     if ($flyobj < 0){
         echo "Error creating object: ".$flyobj;
          }

}

if ($action == 'delobject' ) 	// delete a tracked object
{
     $flyobj=delobj($objectid,$registration, $cn, $acft, $user);
     if ($flyobj < 0){
         echo "Error deleting object: ".$flyobj;
          }

}

if ($action == 'device' ) 	// create or update an updated device
{
     if ($objectid == ''){
        echo "Error Missing objectid";
        exit();
     }
     $rc=createdevice($devid, $dvct, $deviceidtype, $objectid, $user);
     if ($rc < 0){
         echo "Error creating device: ".$rc;
         }

}

if ($action == 'deldevice' ) 	// delete a device
{
     if ($objectid == ''){
        echo "Error Missing objectid";
        exit();
     }
     $rc=deldevice($devid, $dvct, $deviceidtype, $objectid, $user);
     if ($rc < 0){
         echo "Error deleing device: ".$rc;
         }

}

if ($action == 'add' ) 		// create an object and a linked device
{
     $flyobj=createobj(0,$registration, $cn, $acft, $user);
     if ($flyobj > 0){ 		// if object created
         $rc=createdevice($devid, $dvct, $deviceidtype, $flyobj, $user);
         if ($rc < 0){
            echo "Error creating device: ".$rc;
            }
          }
     else {
         echo "Error creating object: ".$rc;
          }

}
$outputmsg=$outputmsg."} ";
$outputmsg=str_replace("'",'"',$outputmsg);
echo $outputmsg."\n";
exit();
?>
