<?php

include 'sql.php';

//
// This program handles all the aspects of the OGN Device DataBase (OGNDDB)
// Website: http://ddb.glidernet.orga
//
// Partially Rewritten by: Angel Casadoa Date: August 2020
//

require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array('cache' => false));

$dbh = Database::connect();
$req = $dbh->query('SELECT count(dev_id) FROM devices ');
$nbdevices = $req->fetchColumn();
$twig->addGlobal('nbdevices',$nbdevices);
$req = $dbh->query('SELECT count(air_id) FROM trackedobjects ');
$nbobjects = $req->fetchColumn();
$twig->addGlobal('nbobjects',$nbobjects);

require_once 'language/english.php';

$url = 'https://ddb.glidernet.org/';
if (isset($_SERVER['HTTP_HOST'])) {
     $url='https://'.$_SERVER['HTTP_HOST'];
}
$sender = 'contact@glidernet.org';

function get_CCname($cc) {
    $countrynames = json_decode(file_get_contents("http://country.io/names.json"), true);
    $r = $countrynames[$cc];
    return ($r);
}

function send_email($to, $subject, $message, $from = '')
{
    $headers = array();
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = 'Content-Transfer-Encoding: quoted-printable';
    $headers[] = "From: {$from}";

    $email_message = quoted_printable_encode($message);

    return mail($to, $subject, $email_message, implode("\n", $headers), '-f'.$from);
}

function home()
{
    global $lang,$error,$user,$url,$twig;

    $template_vars = array(
        'lang' => $lang,
        'error' => $error,
        'url' => $url,
        'user' => $user,
    );
    echo $twig->render('home.html.twig', $template_vars);
}


function fromhome()
{
    if (isset($_SESSION['home'])) {        // test if user comes from home page
        if ($_SESSION['home'] == 'yes') {
            return;
        }
    }
    $_SESSION['home'] = 'yes';
    home();
    exit();
}

function devicetypes()
{
    $dbh = Database::connect();
    $result = $dbh->query('SELECT * FROM devtypes');
    foreach ($result as $row) {

        $devtypes[$row['dvt_id']] = $row['dvt_name'];
    }
    return $devtypes;
}


function devicetypeslen()
{
    $dbh = Database::connect();
    $result = $dbh->query('SELECT * FROM devtypes');
    foreach ($result as $row) {

        $devtypeslen[$row['dvt_id']] = (int) $row['dvt_idlen'];
    }
    return $devtypeslen;
}

function getflyobj($user)
{
    $user=$_SESSION['user'];
    $flyobj = array();
    $dbh = Database::connect();
    $cmd ='SELECT air_id FROM trackedobjects where air_userid = '.$user.';';
    $fly = $dbh->query($cmd) ;    // get data
    
    foreach ($fly as $fo){
        array_push($flyobj, $fo['air_id']); 
    }
    return $flyobj;
}

function fillinuser()
{
    global $lang,$error,$user,$twig;

    $v1 = rand(5, 9);
    $v2 = rand(5, 9);
    $_SESSION['verif'] = $v1 * $v2;

    $template_vars = array(
        'lang' => $lang,
        'error' => $error,
        'user' => $user,
        'v1' => $v1,
        'v2' => $v2,
    );
    echo $twig->render('fillinuser.html.twig', $template_vars);
}

function fillinuserforgot()
{
    global $lang,$error,$user,$twig;

    $v1 = rand(5, 9);
    $v2 = rand(5, 9);
    $_SESSION['verif'] = $v1 * $v2;

    $template_vars = array(
        'lang' => $lang,
        'error' => $error,
        'user' => $user,
        'v1' => $v1,
        'v2' => $v2,
    );
    echo $twig->render('fillinuserforgot.html.twig', $template_vars);
}

function fillindevice()
{
    global $lang,$error,$devid,$airid, $devtype,$acreg,$accn,$actype,$notrack,$noident,$active,$user,$idtype,$twig;
array('', '', '');

    $dtypc = devicetypes();
    $dtypc[$devtype] = 'selected';

    $aircraft = array();
    $flyobjs = array();
    $dbh = Database::connect();
    $result = $dbh->query('SELECT * FROM aircraftstypes ORDER BY ac_cat,ac_type');
    foreach ($result as $row) {
        $selected = ($row['ac_id'] == $actype) ? 'selected' : '';

        $aircraft[$row['ac_cat']][] = array(
            'id' => $row['ac_id'],
            'type' => $row['ac_type'],
            'selected' => $selected,
        );
    }

    Database::disconnect();
    $flyobjs=getflyobj($user);
    $template_vars = array(
        'aircrafts' => $aircraft,
        'flyobjs' => $flyobjs,
        'lang' => $lang,
        'error' => $error,
        'dtypc' => $dtypc,
        'cnotrack' => ($notrack) ? 'checked' : '',
        'cnoident' => ($noident) ? 'checked' : '',
        'devid' => $devid,
        'airid' => $airid,
        'active' => $active,
        'devtype' => $devtype,
        'idtype' => $idtype,

    );
    echo $twig->render('fillindevice.html.twig', $template_vars);
    aircraftlist();				// display the aircrafts
}


function fillinaircraft()
{
    global $lang,$error,$airid,$active,$acreg,$accn,$actype,$phone, $club, $country, $active, $twig;

    $catarray = array(
        1 => 'Gliders/motoGliders',
        2 => 'Planes',
        3 => 'Ultralights',
        4 => 'Helicoters',
        5 => 'Drones/UAV',
        6 => 'Others',
    );

    $aircraft = array();
    $dbh = Database::connect();
    $result = $dbh->query('SELECT * FROM aircraftstypes ORDER BY ac_cat,ac_type');
    foreach ($result as $row) {
        $selected = ($row['ac_id'] == $actype) ? 'selected' : '';

        $aircraft[$row['ac_cat']][] = array(
            'id' => $row['ac_id'],
            'type' => $row['ac_type'],
            'selected' => $selected,
        );
    }

    Database::disconnect();

    $active = (int)$active;
    $template_vars = array(
        'aircrafts' => $aircraft,
        'lang' => $lang,
        'error' => $error,
        'airid' => $airid,
        'acreg' => $acreg,
        'accn' => $accn,
        'active' => $active,
        'catarray' => $catarray,
        'phone' => $phone,
        'club' => $club,
        'country' => $country,

    );
    echo $twig->render('fillinaircraft.html.twig', $template_vars);
    aircraftlist();				// display the aircrafts
}
function changepassword()
{
    global $lang,$error,$twig;

    $template_vars = array(
        'lang' => $lang,
    );
    echo $twig->render('changepassword.html.twig', $template_vars);
}

function claimownership()
{
    global $lang,$error,$twig;

    $template_vars = array(
        'lang' => $lang,
    );
    echo $twig->render('claimownership.html.twig', $template_vars);
}

function devicelist()
{
    global $dbh,$lang,$error,$url,$twig;
    $req2 = $dbh->prepare('SELECT * FROM devices, trackedobjects where dev_userid=:us and dev_flyobj = air_id ORDER BY dev_id ASC');
    $req2->bindParam(':us', $_SESSION['user']);
    $req2->execute();
    $devtypes = devicetypes();
    $idtypes = array( '1' => 'INTERNAL', '2' => 'ICAO');
    $template_vars = array(
        'devicelist' => $req2->fetchAll(),
        'url' => $url,
        'lang' => $lang,
        'devicetypes' => $devtypes,
        'idtypes' => $idtypes,

    );
    echo $twig->render('devicelist.html.twig', $template_vars);
}

function aircraftlist()
{
    global $dbh,$lang,$error,$url,$twig;
    $req2 = $dbh->prepare('SELECT * FROM trackedobjects, aircraftstypes where air_userid=:us and air_actype = ac_id ORDER BY air_id ASC');
    $req2->bindParam(':us', $_SESSION['user']);
    $req2->execute();
    $template_vars = array(
        'aircraftlist' => $req2->fetchAll(),
        'url' => $url,
        'lang' => $lang,

    );
    echo $twig->render('aircraftlist.html.twig', $template_vars);
}

// --------------------------------------------------------------------------------------- //

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
} else {
    $action = '';
}

if (isset($_GET['a'])) {
    $action = $_GET['a'];
}

if (isset($_GET['v'])) {
    $action = 'validuser';
    $validcode = $_GET['v'];
}

if (isset($_GET['f'])) {			// the case of forgot password ...
    $action = 'validpasswd';
    $validcode = $_GET['f'];
}
session_start();

require_once 'language/english.php';

$lang = $languages['english'];

if (isset($_GET['l'])) {
    include_once 'language/'.$_GET['l'].'.php';

    if (isset($languages[$_GET['l']])) {
        $lang = array_merge($lang, $languages[$_GET['l']]);
        $_SESSION['lang'] = $_GET['l'];
    }
} elseif (isset($_SESSION['lang'])) {
    include_once 'language/'.$_SESSION['lang'].'.php';
    $lang = array_merge($lang, $languages[$_SESSION['lang']]);
}

$error = $user = '';

switch (strtolower($action)) {
case 'login':					// login
{
    fromhome();

    $dbh = Database::connect();
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
    }
    if (isset($_POST['pw'])) {
        $password = $_POST['pw'];
    } else {
        $password = '';
    }
    $password = crypt($password, 'GliderNetdotOrg');
    $req = $dbh->prepare('SELECT * FROM users where usr_adress=:us AND usr_pw=:pw');
    $req->bindParam(':us', $user);
    $req->bindParam(':pw', $password);
    $req->execute();
    if ($req->rowCount() == 1) {
        $result = $req->fetch();
        $req->closeCursor();
        $_SESSION['user'] = $result['usr_id'];
        $_SESSION['login'] = 'yes';

        devicelist();
        aircraftlist();
    } else {
        $error = $lang['error_login'];
        home();
    }
    Database::disconnect();
    break;
}

case 'd':        				// disconnect
{
    session_destroy();
    session_start();
    $_SESSION['home'] = 'yes';
    home();
    break;
}

case 'u':        				// fill in create user
{
    fromhome();
    fillinuser();
    break;
}

case 'forgot':    				// forgot the password
{
    fromhome();
    fillinuserforgot();
    break;
}

case 'deviceslist':        			// display device list
{
    fromhome();
    $dbh = Database::connect();
    devicelist();
    aircraftlist();
    Database::disconnect();
    break;
}

case 'aircraftlist':        			// display device list
{
    fromhome();
    $dbh = Database::connect();
    devicelist();
    aircraftlist();
    Database::disconnect();
    break;
}

case 'n':        				// fill in create device
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } // test if user come from login page
    $_SESSION['dev'] = 'yes';
    $devtype = 2;        			// default type is Flarm
    fillindevice();
    break;
}

case 'a':        				// fill in create aircraft
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    $_SESSION['acft'] = 'yes';
    $airid=0;					// create the aircraft
    fillinaircraft();
    break;
}

case 'o':        				// claimownership
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    claimownership();
    break;
}

case 'p':        				// fill in change password
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    changepassword();
    break;
}

case 'updatedev':        			// update/create device
{

    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    $_SESSION['dev'] = 'yes';
    if (isset($_REQUEST['devid'])) {
        $devid = $_REQUEST['devid'];
    }
    $dbh = Database::connect();

    $req = $dbh->prepare('select * from devices where dev_id=:de AND dev_userid=:us');
    $req->bindParam(':de', $devid);
    $req->bindParam(':us', $_SESSION['user']);
    $req->execute();
    if ($req->rowCount() == 1) {
        $result = $req->fetch();
        $req->closeCursor();
        $devtype = $result['dev_type'];
        $notrack = $result['dev_notrack'];
        $noident = $result['dev_noident'];
        $airid   = $result['dev_flyobj'];
        $active  = $result['dev_active'];
        $idtype  = $result['dev_idtype'];
        fillindevice();
    } else {
        $error = $lang['error_devid'];
        devicelist();
        aircraftlist();
    }

    Database::disconnect();
    break;
}

case 'deletedev':        // delete device
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } // test if user come from login page
    $_SESSION['dev'] = 'yes';
    if (isset($_REQUEST['devid'])) {
        $devid = $_REQUEST['devid'];
    }
    $dbh = Database::connect();
    $req = $dbh->prepare('select * from devices where dev_id=:de AND dev_userid=:us');
    $req->bindParam(':de', $devid);
    $req->bindParam(':us', $_SESSION['user']);
    $req->execute();

    if ($req->rowCount() == 1) {
        $req->closeCursor();
        $del = $dbh->prepare('DELETE FROM devices where dev_id=:de AND dev_userid=:us');
        $del->bindParam(':de', $devid);
        $del->bindParam(':us', $_SESSION['user']);
        $del->execute();

        $error = $lang['device_deleted'];
        devicelist();
        aircraftlist();
    } else {
        $error = $lang['error_devid'];
        fillindevice();
    }
    Database::disconnect();
    break;
}


case 'updateacft':        			// update/create tracked object
{

    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    $_SESSION['acft'] = 'yes';
    if (isset($_REQUEST['airid'])) {
        $airid = $_REQUEST['airid'];
    }
    $dbh = Database::connect();

    $req = $dbh->prepare('select * from trackedobjects where air_id=:de AND air_userid=:us');
    $req->bindParam(':de', $airid);
    $req->bindParam(':us', $_SESSION['user']);
    $req->execute();
    if ($req->rowCount() == 1 or air_id == 0) {
        $result = $req->fetch();
        $req->closeCursor();
        $actype = $result['air_actype'];
        $acreg = $result['air_acreg'];
        $accn = $result['air_accn'];
        $active = $result['air_active'];
        $phone = $result['air_SARphone'];
        $club = $result['air_SARclub'];
        $country = $result['air_Country'];
        fillinaircraft();
    } else {
        $error = $lang['error_airid'];
        devicelist();
        aircraftlist();
    }

    Database::disconnect();
    break;
}

case 'deleteacft':        			// delete tracked object
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    $_SESSION['acft'] = 'yes';
    if (isset($_REQUEST['airid'])) {
        $airid = $_REQUEST['airid'];
    }
    $dbh = Database::connect();
    $req = $dbh->prepare('select * from trackedobjects where air_id=:id AND air_userid=:us');
    $req->bindParam(':id', $airid);
    $req->bindParam(':us', $_SESSION['user']);
    $req->execute();

    if ($req->rowCount() == 1) {
        $req->closeCursor();
        $del = $dbh->prepare('DELETE FROM trackedobjects where air_id=:id AND air_userid=:us');
        $del->bindParam(':id', $airid);
        $del->bindParam(':us', $_SESSION['user']);
        $del->execute();

        $error = $lang['flyobj_deleted'];
        $req->closeCursor();			// now delete all the devices associated to this aircraft !!!
        $del = $dbh->prepare('DELETE FROM devices where dev_flyobj=:id AND dev_userid=:us');
        $del->bindParam(':id', $airid);
        $del->bindParam(':us', $_SESSION['user']);
        $del->execute();

        devicelist();
        aircraftlist();
    } else {
        $error = $lang['error_airid'];
        fillinaircraft();
    }
    Database::disconnect();
    break;
}

case 'createuser':        			// create user
{
    fromhome();
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
    }
    if (isset($_POST['pw1'])) {
        $pw1 = $_POST['pw1'];
    } else {
        $pw1 = '';
    }
    if (isset($_POST['pw2'])) {
        $pw2 = $_POST['pw2'];
    } else {
        $pw2 = '';
    }
    if (isset($_POST['verif'])) {
        $verif = $_POST['verif'];
    } else {
        $verif = '';
    }

    if ($verif == '' or $verif * 1 != $_SESSION['verif'] * 1) {
        $error = $lang['error_verif'];
    }

    if (strlen($pw1) < 4) {
        $error = $lang['error_pwtooshort'];
    }

    if ($pw1 != $pw2) {
        $error = $lang['error_pwdontmatch'];
    }
    if (filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
        $error = $lang['error_emailformat'];
    }

    $dbh = Database::connect();
    $req = $dbh->prepare('select usr_adress from users where usr_adress=:us UNION ALL select tusr_adress from tmpusers where tusr_adress=:us');
    $req->bindParam(':us', $user);
    $req->execute();

    if ($req->rowCount() > 0) {
        $error = $lang['error_userexists'];
    }
    $req->closeCursor();

    if ($error != '') {
        fillinuser();
    } else {
        $pass = crypt($pw1, 'GliderNetdotOrg');
        $valid = md5(date('dYmsHi').$user);
        $ttime = time();

        $ins = $dbh->prepare('INSERT INTO tmpusers (tusr_adress, tusr_pw, tusr_validation, tusr_time) VALUES (:us, :pw, :va, :ti)');
        $ins->bindParam(':us', $user);
        $ins->bindParam(':pw', $pass);
        $ins->bindParam(':va', $valid);
        $ins->bindParam(':ti', $ttime);

        if ($ins->execute()) {   // insert ok, sent email
            $validation_link = $url.'?v='.$valid;
            $msg = $twig->render('email-validation-request.html.twig', array('lang' => $lang, 'validation_link' => $validation_link));
            if (send_email($user, $lang['email_subject'], $msg, $sender)) {
                // email sent
                echo $twig->render('emailsent.html.twig', array('lang' => $lang));
            } else {
                $error = $lang['email_not_sent'];
                fillinuser();
            }
        } else {
            $error = $lang['error_insert_tmpusers'];
            fillinuser();
        }
        $ins->closeCursor();
    }

    Database::disconnect();
    break;
}

case 'claimownership':			// claim the ownership of a device
{
    fromhome();
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
    }
    if (isset($_POST['devid'])) {
        $devid = $_POST['devid'];
    } else {
        $devid = '';
    }
    if (isset($_POST['acreg'])) {
        $acreg = $_POST['acreg'];
    } else {
        $acreg = '';
    }
    if (isset($_POST['accn'])) {
        $accn = $_POST['accn'];
    } else {
        $accn = '';
    }

    $devid = strtoupper($devid);
    $acreg = strtoupper($acreg);
    $accn  = strtoupper($accn);

    $dbh = Database::connect();		// it has to match the DEVICE ID + REGISTRATION + CN
    $req = $dbh->prepare('select usr_adress from users, devices, trackedobjects  where dev_id=:di and dev_flyobj = air_id and air_userid = usr_id and air_acreg=:rg and air_accn =:cn ; ');
    $req->bindParam(':di', $devid);
    $req->bindParam(':rg', $acreg);
    $req->bindParam(':cn', $accn);
    $req->execute();

    if ($req->rowCount() == 0) {
        $error = $lang['error_devicedoesnotexists'];
        claimownership();
        Database::disconnect();
        break;
    } else {
        $touser=$req->fetchColumn();
        $msg = $twig->render('email-claimownership-request.html.twig', 
               array('lang' => $lang, 
                     'devid' => $devid,
                     'acreg' => $acreg,
                     'accn' => $accn,
                     'touser' => $touser,
               ));
        if (send_email($touser, $lang['email_claimsubject'], $msg, $sender)) {
                // email sent
                echo $twig->render('emailsent.html.twig', array('lang' => $lang));
        } else {
                $error = $lang['email_not_sent'];
            }
    }
    $req->closeCursor();

    Database::disconnect();
    devicelist();
    break;
}
case 'forgotpasswd':        			// forgot password
{
    fromhome();
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
    }
    if (isset($_POST['pw1'])) {
        $pw1 = $_POST['pw1'];
    } else {
        $pw1 = '';
    }
    if (isset($_POST['pw2'])) {
        $pw2 = $_POST['pw2'];
    } else {
        $pw2 = '';
    }
    if (isset($_POST['verif'])) {
        $verif = $_POST['verif'];
    } else {
        $verif = '';
    }

    if ($verif == '' or $verif * 1 != $_SESSION['verif'] * 1) {
        $error = $lang['error_verif'];
    }

    if (strlen($pw1) < 4) {
        $error = $lang['error_pwtooshort'];
    }

    if ($pw1 != $pw2) {
        $error = $lang['error_pwdontmatch'];
    }
    if (filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
        $error = $lang['error_emailformat'];
    }

    $dbh = Database::connect();
    $req = $dbh->prepare('select usr_adress from users where usr_adress=:us UNION ALL select tusr_adress from tmpusers where tusr_adress=:us');
    $req->bindParam(':us', $user);
    $req->execute();

    if ($req->rowCount() == 0) {
        $error = $lang['error_userdoesnotexists'];
    }
    $req->closeCursor();

    if ($error != '') {
        fillinuser();
    } else {
        $pass = crypt($pw1, 'GliderNetdotOrg');
        $valid = md5(date('dYmsHi').$user);
        $ttime = time();

        $ins = $dbh->prepare('INSERT INTO tmpusers (tusr_adress, tusr_pw, tusr_validation, tusr_time) VALUES (:us, :pw, :va, :ti)');
        $ins->bindParam(':us', $user);
        $ins->bindParam(':pw', $pass);
        $ins->bindParam(':va', $valid);
        $ins->bindParam(':ti', $ttime);

        if ($ins->execute()) {   // insert ok, sent email
            $validation_link = $url.'?f='.$valid;
            $msg = $twig->render('email-validation-request.html.twig', array('lang' => $lang, 'validation_link' => $validation_link));
            if (send_email($user, $lang['email_subject'], $msg, $sender)) {
                // email sent
                echo $twig->render('emailsent.html.twig', array('lang' => $lang));
            } else {
                $error = $lang['email_not_sent'];
                fillinuser();
            }
        } else {
            $error = $lang['error_insert_tmpusers'];
            fillinuser();
        }
        $ins->closeCursor();
    }

    Database::disconnect();
    break;
}

case 'changepass':        			// change pass
{
    fromhome();
    if (!isset($_SESSION['user'])) {
        exit();
    } // test if user id defined
    if (isset($_POST['pw1'])) {
        $pw1 = $_POST['pw1'];
    } else {
        $pw1 = '';
    }
    if (isset($_POST['pw2'])) {
        $pw2 = $_POST['pw2'];
    } else {
        $pw2 = '';
    }

    if (strlen($pw1) < 4) {
        $error = $lang['error_pwtooshort'];
    }

    if ($pw1 != $pw2) {
        $error = $lang['error_pwdontmatch'];
    }

    $dbh = Database::connect();
    $user_id = $_SESSION['user'];
    $pass = crypt($pw1, 'GliderNetdotOrg');

    $ins = $dbh->prepare('UPDATE users SET usr_pw = :pw WHERE usr_id = :us');
    $ins->bindParam(':us', $user_id);
    $ins->bindParam(':pw', $pass);

    if ($ins->execute()) {
        $ins->closeCursor();
    }

    devicelist();
    aircraftlist();
    Database::disconnect();
    break;
}

case 'validuser':        			// user validation from email
{
    $dbh = Database::connect();
    $req = $dbh->prepare('select * from tmpusers where tusr_validation=:vl');
    $req->bindParam(':vl', $validcode);
    $req->execute();
    if ($req->rowCount() == 1) {        	// tmpuser user found
        $result = $req->fetch();
        $req->closeCursor();
        $ins = $dbh->prepare('INSERT INTO users (usr_adress, usr_pw) VALUES (:us, :pw)');
        $ins->bindParam(':us', $result['tusr_adress']);
        $ins->bindParam(':pw', $result['tusr_pw']);

        if ($ins->execute()) {    		// insert ok, delete tmpuser
            $ins->closeCursor();
            $del = $dbh->prepare('DELETE FROM tmpusers where tusr_validation=:vl');
            $del->bindParam(':vl', $validcode);
            $del->execute();
            $user = $result['tusr_adress'];
            $error = $lang['email_validated'];
        } else {
            $error = $lang['error_validation'];
        }
    } else {
        $error = $lang['error_validation'];
    }
    $_SESSION['home'] = 'yes';
    home();
    break;
}

case 'validpasswd':        			// password validation from email
{
    $dbh = Database::connect();
    $req = $dbh->prepare('select * from tmpusers where tusr_validation=:vl');
    $req->bindParam(':vl', $validcode);
    $req->execute();
    if ($req->rowCount() == 1) {        	// tmpuser user found
        $result = $req->fetch();
        $req->closeCursor();

        $ins = $dbh->prepare('UPDATE users SET usr_pw = :pw WHERE usr_adress = :us');
        $ins->bindParam(':us', $result['tusr_adress']);
        $ins->bindParam(':pw', $result['tusr_pw']);

        if ($ins->execute()) {    		// insert ok, delete tmpuser
            $ins->closeCursor();
            $del = $dbh->prepare('DELETE FROM tmpusers where tusr_validation=:vl');
            $del->bindParam(':vl', $validcode);
            $del->execute();
            $user = $result['tusr_adress'];
            $error = $lang['email_validated'];
        } else {
            $error = $lang['error_validation'];
        }
    } else {
        $error = $lang['error_validation'];
    }
    $_SESSION['home'] = 'yes';
    home();
    break;
}

case 'createdev':        			// create device
{
    fromhome();
    $notrack = $noident = 0;
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    if (!isset($_SESSION['dev'])) {
        exit();
    } 						// test if user come from fill in device page
    if (!isset($_SESSION['user'])) {
        exit();
    } 						// test if user id defined

    if (isset($_REQUEST['devid'])) {
        $devid = $_REQUEST['devid'];
    } else {
        $error = $lang['error_devid'];
    }
    if (isset($_REQUEST['devtype'])) {
        $devtype = $_REQUEST['devtype'];
    } else {
        $error = $lang['error_devtype'];
    }
    if (isset($_REQUEST['idtype'])) {
        $idtype = $_REQUEST['idtype'];
    } else {
        $error = $lang['error_idtype'];
    }
    if (isset($_REQUEST['flyobj'])) {
        $flyobj = $_REQUEST['flyobj'];
    } else {
        $error = $lang['error_flyobj'];
    }
    if (isset($_REQUEST['active'])) {
        $active = $_REQUEST['active'];
    } else {
        $error = $lang['error_active'];
    }
    if (isset($_REQUEST['notrack'])) {
        if ($_REQUEST['notrack'] == 'yes') {
            $notrack = 1;
        }
    }
    if (isset($_REQUEST['noident'])) {
        if ($_REQUEST['noident'] == 'yes') {
            $noident = 1;
        }
    }

    if (isset($_REQUEST['owner'])) {
        if ($_REQUEST['owner'] != 'yes') {
            $error = $lang['error_owner'];
        }
    } else {
        $error = $lang['error_owner'];
    }

    if ($flyobj == ''){
        $error = $lang['error_flyobj'];
    }
    $devid = strtoupper($devid);
    if (preg_match(' /[A-F0-9]/ ', $devid)) {
    } // ok
    else {
        $error = $lang['error_devid'];
    }


    $devtypesl=devicetypeslen();			// get the length of the ID for that kind of device type
    if (strlen($devid) > $devtypesl[(int)$devtype]) { 	// the length has to be lower or equal
        $error = $lang['error_devidlen'];
    }

    $dbh = Database::connect();
    $req = $dbh->prepare('select dev_id, dev_type, dev_idtype, dev_userid from devices where dev_id=:de and dev_type =:dt and dev_idtype=:it');    // test if device is owned by another account
    $req->bindParam(':de', $devid);
    $req->bindParam(':dt', $devtype);
    $req->bindParam(':it', $idtype);
    $req->execute();

    $upd = false;
    if ($req->rowCount() == 1) {        // if device already registred
        $result = $req->fetch();
        if ($result['dev_userid'] == $_SESSION['user']) {
            $upd = true;
        }        			// if owned by the user then update
        else {
            $error = $lang['error_devexists'];
        }
    }
    $req->closeCursor();

    if ($error != '') {
        fillindevice();
    } else {
        if ($upd) {
            $ins = $dbh->prepare('UPDATE devices SET  dev_idtype=:it, dev_notrack=:nt, dev_noident=:ni, dev_flyobj=:fo, dev_active=:ac WHERE dev_id=:de AND dev_type=:dt AND dev_userid=:us');
        } else {
            $ins = $dbh->prepare('INSERT INTO devices (dev_id, dev_type, dev_userid, dev_notrack, dev_noident, dev_flyobj, dev_active, dev_idtype ) VALUES (:de, :dt,  :us, :nt, :ni, :fo, :ac, :it)');
        }
        $act = (int)$active;
        $fly = (int)$flyobj;
        $ins->bindParam(':de', $devid);
        $ins->bindParam(':dt', $devtype);
        $ins->bindParam(':it', $idtype);
        $ins->bindParam(':nt', $notrack);
        $ins->bindParam(':ni', $noident);
        $ins->bindParam(':ac', $act);
        $ins->bindParam(':fo', $fly);
        $ins->bindParam(':us', $_SESSION['user']);
        if ($ins->execute()) {    	// insert ok, send email
            if ($upd) {
                $error = $lang['device_updated'];
            } else {
                $error = $lang['device_inserted'];
            }
            devicelist();
            aircraftlist();
        } else {
            $error = $lang['error_insert_device'];
            fillindevice();
        }
        $ins->closeCursor();
    }

    Database::disconnect();
    break;
}


case 'createacft':        			// create tracked object
{
    fromhome();
    $airid = 0;
    if (!isset($_SESSION['login'])) {
        exit();
    } 						// test if user come from login page
    if (!isset($_SESSION['acft'])) {
        exit();
    } 						// test if user come from fill in device page
    if (!isset($_SESSION['user'])) {
        exit();
    } 						// test if user id defined

    if (isset($_REQUEST['airid'])) {
        $airid = $_REQUEST['airid'];
    } else {
        $error = $lang['error_noairid'];
    }
    if (isset($_REQUEST['actype'])) {
        $actype = $_REQUEST['actype'];
    } else {
        $error = $lang['error_actype'];
    }
    if (isset($_REQUEST['acreg'])) {
        $acreg = $_REQUEST['acreg'];
    } else {
        $error = $lang['error_acreg'];
    }
    if (isset($_REQUEST['accn'])) {
        $accn = $_REQUEST['accn'];
    } else {
        $error = $lang['error_accn'];
    }
    if (isset($_REQUEST['phone'])) {
        $phone = $_REQUEST['phone'];
    } else {
        $error = $lang['error_phone'];
    }
    if (isset($_REQUEST['club'])) {
        $club = $_REQUEST['club'];
    } else {
        $error = $lang['error_club'];
    }
    if (isset($_REQUEST['country'])) {
        $country = $_REQUEST['country'];
        if (strlen($country) == 2) {
           $r=get_CCname(strtoupper($country));
           if ($r=='')
            {
              $error = $lang['error_country'];
            }
           }
    } else {
        $error = $lang['error_country'];
    }
    if (isset($_REQUEST['active'])) {
        $active = $_REQUEST['active'];
    } else {
        $error = $lang['error_active'];
    }

    if (isset($_REQUEST['owner'])) {
        if ($_REQUEST['owner'] != 'yes') {
            $error = $lang['error_owner'];
        }
    } else {
        $error = $lang['error_owner'];
    }


    $dbh = Database::connect();
    $req = $dbh->prepare('select air_id,air_userid from trackedobjects where air_id=:de');    // test if aircraft  is owned by another account
    $req->bindParam(':de', $airid);
    $req->execute();

    $upd = false;
    if ($req->rowCount() == 1) {        // if device already registred
        $result = $req->fetch();
        if ($result['air_userid'] == $_SESSION['user']) {
            $upd = true;
        }        			// if owned by the user then update
        else {
            $error = $lang['error_airexists'];
        }
    }
    $req->closeCursor();

    if ($error != '') {
        fillinaircraft();
    } else {
        if ($acreg ==''){
            $acreg="R-".(string)$airid;
        }
        if ($upd) {
            if ($airid == 0){
                $error = $lang['error_airexists'];
                fillinaircraft();
            }
            $ins = $dbh->prepare('UPDATE trackedobjects SET air_actype=:dt,  air_acreg=:re, air_accn=:cn, air_active=:ac, air_SARphone=:ph, air_SARclub=:cl, air_Country=:co WHERE air_id=:de AND air_userid=:us');
        } else {
            $airid=0;
            $ins = $dbh->prepare('INSERT INTO trackedobjects (air_id, air_actype, air_acreg, air_accn, air_userid, air_active, air_SARphone, air_SARclub, air_Country  ) VALUES (:de, :dt,  :re, :cn, :us, :ac, :ph, :cl, :co )');
            if ($airid == ''){
               $airid='0';
            }
        }



        $act=(int) $active;
        $ins->bindParam(':de', $airid);
        $ins->bindParam(':dt', $actype);
        $ins->bindParam(':re', $acreg);
        $ins->bindParam(':cn', $accn);
        $ins->bindParam(':ac', $act);
        $ins->bindParam(':ph', $phone);
        $ins->bindParam(':cl', $club);
        $ins->bindParam(':co', $country);
        $ins->bindParam(':us', $_SESSION['user']);

        if ($ins->execute()) {    	// insert ok, send email
            if ($upd) {
                $error = $lang['flyobj_updated'];
            } else {
                $error = $lang['flyobj_inserted'];
                $req = $dbh->query('SELECT LAST_INSERT_ID(); ');
                $airid = $req->fetchColumn();
            }
            devicelist();
            aircraftlist();
        } else {
            $error = $lang['error_insert_flyobj'];
            fillinaircraft();
        }
        $ins->closeCursor();
    }

    Database::disconnect();
    break;
}

default:
{
    $_SESSION['home'] = 'yes';
    home();
}
}
