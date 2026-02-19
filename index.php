<?php

include 'sql.php';
require_once 'lib/device_service.php';

//
// Localisation TODO:
// - aircraft types (see $catarray)
//

require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array('cache' => false));

$dbh = Database::connect();
$req = $dbh->query('SELECT count(dev_id) FROM devices ');
$nbdevices = $req->fetchColumn();
$twig->addGlobal('nbdevices',$nbdevices);

require_once 'language/english.php';

$url = 'https://ddb.glidernet.org/';
$sender = 'contact@glidernet.org';

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
    global $lang,$error,$devid,$devtype,$acreg,$accn,$actype,$notrack,$noident,$twig;

    $catarray = array(
        1 => 'Gliders/motoGliders',
        2 => 'Planes',
        3 => 'Ultralights',
        4 => 'Helicoters',
        5 => 'Drones/UAV',
        6 => 'Others',
    );

    $dtypc = array('', '', '');
    $dtypc[$devtype] = 'selected';

    $aircraft = array();
    $dbh = Database::connect();
    $result = $dbh->query('SELECT * FROM aircrafts ORDER BY ac_cat,ac_type');
    foreach ($result as $row) {
        $selected = ($row['ac_id'] == $actype) ? 'selected' : '';

        $aircraft[$row['ac_cat']][] = array(
            'id' => $row['ac_id'],
            'type' => $row['ac_type'],
            'selected' => $selected,
        );
    }

    Database::disconnect();

    $template_vars = array(
        'aircrafts' => $aircraft,
        'lang' => $lang,
        'error' => $error,
        'dtypc' => $dtypc,
        'catarray' => $catarray,
        'cnotrack' => ($notrack) ? 'checked' : '',
        'cnoident' => ($noident) ? 'checked' : '',
        'devid' => $devid,
        'acreg' => $acreg,
        'accn' => $accn,

    );
    echo $twig->render('fillindevice.html.twig', $template_vars);
}

function changepassword()
{
    global $lang,$error,$twig;

    $template_vars = array(
        'lang' => $lang,
    );
    echo $twig->render('changepassword.html.twig', $template_vars);
}

function devicelist($new_token = null)
{
    global $dbh,$lang,$error,$url,$twig;

    // Auto-generate a token if user has none yet
    $user_id = $_SESSION['user'];
    $tok_req = $dbh->prepare('SELECT usr_token_hash FROM users WHERE usr_id = :us');
    $tok_req->bindParam(':us', $user_id);
    $tok_req->execute();
    $tok_row = $tok_req->fetch();
    if ($tok_row && $tok_row['usr_token_hash'] === null && $new_token === null) {
        $new_token = token_generate($dbh, $user_id);
    }
    $has_token = ($tok_row && $tok_row['usr_token_hash'] !== null) || $new_token !== null;

    $template_vars = array(
        'devicelist' => device_list($dbh, $user_id),
        'url' => $url,
        'lang' => $lang,
        'devicetypes' => array(1 => 'ICAO', 2 => 'Flarm', 3 => 'OGN'),
        'expirationdelta' => DEVICE_EXPIRATION_DELTA,
        'new_token' => $new_token,
        'has_token' => $has_token,
        'error' => $error,
    );
    echo $twig->render('devicelist.html.twig', $template_vars);
}

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
case 'login':
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
    } else {
        $error = $lang['error_login'];
        home();
    }
    Database::disconnect();
    break;
}

case 'd':        // disconnect
{
    session_destroy();
    session_start();
    $_SESSION['home'] = 'yes';
    home();
    break;
}

case 'u':        // fill in create user
{
    fromhome();
    fillinuser();
    break;
}

case 'forgot':    // forgot the password
{
    fromhome();
    fillinuserforgot();
    break;
}

case 'deviceslist':        // display device list
{
    fromhome();
    $dbh = Database::connect();
    devicelist();
    Database::disconnect();
    break;
}

case 'n':        // fill in create device
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } // test if user come from login page
    $_SESSION['dev'] = 'yes';
    $devtype = 2;        // default type is Flarm
    fillindevice();
    break;
}

case 'p':        // fill in change password
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    } // test if user come from login page
    changepassword();
    break;
}

case 'updatedev':        // update/create device
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
        $result = $req->fetch();
        $req->closeCursor();
        $devtype = $result['dev_type'];
        $actype = $result['dev_actype'];
        $acreg = $result['dev_acreg'];
        $accn = $result['dev_accn'];
        $notrack = $result['dev_notrack'];
        $noident = $result['dev_noident'];
        fillindevice();
    } else {
        $error = $lang['error_devid'];
        devicelist();
    }

    Database::disconnect();
    break;
}

case 'deletedev':        // delete device
{
    fromhome();
    if (!isset($_SESSION['login'])) {
        exit();
    }
    $_SESSION['dev'] = 'yes';
    if (isset($_REQUEST['devid'])) {
        $devid = $_REQUEST['devid'];
    }
    $dbh = Database::connect();

    if (device_delete($dbh, $devid, $_SESSION['user'])) {
        $error = $lang['device_deleted'];
        devicelist();
    } else {
        $error = $lang['error_devid'];
        fillindevice();
    }
    Database::disconnect();
    break;
}

case 'createuser':        // create user
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

case 'forgotpasswd':        // forgot password
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

case 'changepass':        // change pass
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

    Database::disconnect();
    devicelist();
    break;
}

case 'validuser':        // user validation from email
{
    $dbh = Database::connect();
    $req = $dbh->prepare('select * from tmpusers where tusr_validation=:vl');
    $req->bindParam(':vl', $validcode);
    $req->execute();
    if ($req->rowCount() == 1) {        // tmpuser user found
        $result = $req->fetch();
        $req->closeCursor();
        $ins = $dbh->prepare('INSERT INTO users (usr_adress, usr_pw) VALUES (:us, :pw)');
        $ins->bindParam(':us', $result['tusr_adress']);
        $ins->bindParam(':pw', $result['tusr_pw']);

        if ($ins->execute()) {    // insert ok, delete tmpuser
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

case 'validpasswd':        // password validation from email
{
    $dbh = Database::connect();
    $req = $dbh->prepare('select * from tmpusers where tusr_validation=:vl');
    $req->bindParam(':vl', $validcode);
    $req->execute();
    if ($req->rowCount() == 1) {        // tmpuser user found
        $result = $req->fetch();
        $req->closeCursor();

        $ins = $dbh->prepare('UPDATE users SET usr_pw = :pw WHERE usr_adress = :us');
        $ins->bindParam(':us', $result['tusr_adress']);
        $ins->bindParam(':pw', $result['tusr_pw']);

        if ($ins->execute()) {    // insert ok, delete tmpuser
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

case 'createdev':        // create device
{
    fromhome();
    $notrack = $noident = 0;
    if (!isset($_SESSION['login'])) {
        exit();
    }
    if (!isset($_SESSION['dev'])) {
        exit();
    }
    if (!isset($_SESSION['user'])) {
        exit();
    }

    $devid  = isset($_REQUEST['devid'])   ? $_REQUEST['devid']   : '';
    $devtype = isset($_REQUEST['devtype']) ? (int)$_REQUEST['devtype'] : 0;
    $actype  = isset($_REQUEST['actype'])  ? (int)$_REQUEST['actype']  : 0;
    $acreg   = isset($_REQUEST['acreg'])   ? $_REQUEST['acreg']   : '';
    $accn    = isset($_REQUEST['accn'])    ? $_REQUEST['accn']    : '';

    if (isset($_REQUEST['notrack']) && $_REQUEST['notrack'] == 'yes') {
        $notrack = 1;
    }
    if (isset($_REQUEST['noident']) && $_REQUEST['noident'] == 'yes') {
        $noident = 1;
    }

    $owner_confirmed = isset($_REQUEST['owner']) && $_REQUEST['owner'] == 'yes';

    $san    = device_sanitise($devid, $acreg, $accn);
    $errors = device_validate($san['devid'], $devtype, $actype, $san['acreg'], $san['accn'], $owner_confirmed);

    if (!empty($errors)) {
        $error = $lang[$errors[0]];
        fillindevice();
    } else {
        $dbh   = Database::connect();
        $result = device_save($dbh, $san['devid'], $devtype, $actype, $san['acreg'], $san['accn'], $notrack, $noident, $_SESSION['user']);

        if ($result['ok']) {
            $error = $lang[$result['action'] === 'updated' ? 'device_updated' : 'device_inserted'];
            devicelist();
        } else {
            $error = isset($lang[$result['error']]) ? $lang[$result['error']] : $result['error'];
            fillindevice();
        }
        Database::disconnect();
    }
    break;
}

case 'regentoken':        // regenerate API token
{
    fromhome();
    if (!isset($_SESSION['login']) || !isset($_SESSION['user'])) {
        exit();
    }
    $dbh       = Database::connect();
    $new_token = token_generate($dbh, $_SESSION['user']);
    devicelist($new_token);
    Database::disconnect();
    break;
}

default:
{
    $_SESSION['home'] = 'yes';
    home();
}
}
