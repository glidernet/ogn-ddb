<?php
include 'sql.php';

//
// Localisation TODO:
// - aircraft types (see $catarray)
//

require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array('cache' => false));


require_once "language/english.php";


$url = "https://ddb.glidernet.org/";
$sender = "contact@glidernet.org";



function email($dest, $subject, $message, $from="")  {
  $email_subject = $subject; // The Subject of the email 
  $email_txt = $message;
  $email_to = "$dest"; // Who the email is to 
  
  $headers = 	"From: {$from}\n".
		"MIME-Version: 1.0\n". 
		"Content-Type: text/html; charset=\"UTF-8\"\n";

  $email_message = "$email_txt \n\n";
  
  return mail($email_to, $email_subject, $email_message, $headers, "-f".$from);
}

function home() {
	global $lang,$error,$user,$url,$twig;

	$dbh = Database::connect();
	$req = $dbh->query("SELECT count(dev_id) as nb FROM devices ");
	$result = $req->fetch();
	$req->closeCursor();
	$nbdevices=$result['nb'];
	Database::disconnect();

	$template_vars = array(
		'lang'=>$lang,
		'error'=>$error,
		'url'=>$url,
		'nbdevices' => $nbdevices,
		'user' => $user,
	);
	echo $twig->render('home.html.twig',$template_vars);
}

function fromhome () {
 	if (isset($_SESSION['home'])) {		// test if user comes from home page
		if ($_SESSION['home'] == 'yes') {
			return;
		}
	} 
	$_SESSION['home'] = 'yes';
	home();
	exit();
}

function fillinuser() {
	global $lang,$error,$user,$twig;

	$v1=rand(5, 9);
	$v2=rand(5, 9);
	$_SESSION['verif']=$v1*$v2;

	$template_vars = array(
			'lang'=>$lang,
			'error'=>$error,
			'user' => $user,
			'v1' => $v1,
			'v2' => $v2,
	);
	echo $twig->render('fillinuser.html.twig',$template_vars);
}

function fillindevice() {
	global $lang,$error,$devid,$devtype,$acreg,$accn,$actype,$notrack,$noident,$twig;

	$catarray = array (
        1=>"Gliders/motoGliders",
		2=>"Planes",
		3=>"Ultralights",
		4=>"Helicoters",
		5=>"Drones/UAV",
		6=>"Others"
    );

    $dtypc = array ('','','');
	$dtypc[$devtype] = 'checked';

    $aircraft = array();
	$dbh = Database::connect();
    $result = $dbh->query('SELECT * FROM aircrafts ORDER BY ac_cat,ac_type');

    foreach ($result as $row)
	{
		$selected = ($row['ac_id'] == $actype) ? 'selected' :'';

		$aircraft[$row['ac_cat']][] = array(
            'id'=>$row['ac_id'],
			'type'=>$row['ac_type'],
			'selected' => $selected,
		);
	}

    Database::disconnect();

	$template_vars = array(
		'aircrafts'=>$aircraft,
        'lang' =>$lang,
        'error' => $error,
        'dtypc' => $dtypc,
        'catarray' => $catarray,
        'cnotrack' => ($notrack) ? 'checked' : '',
        'cnoident' => ($noident) ? 'checked' : '',
        'devid' => $devid,
        'acreg' => $acreg,
        'accn' => $accn,


	);
    echo $twig->render('fillindevice.html.twig',$template_vars);

}

function devicelist() {
	global $dbh,$lang,$error,$url,$twig;
	$req2 = $dbh->prepare("SELECT * FROM devices,aircrafts where dev_userid=:us AND dev_actype=ac_id ORDER BY dev_id ASC");
	$req2->bindParam(":us", $_SESSION['user']);
	$req2->execute();
	$template_vars = array(
        'devicelist' => $req2->fetchAll(),
        'url' => $url,
        'lang' => $lang,
        'devicetypes' => array(1 => "ICAO", 2=> "Flarm", 3=> "OGN")

	);
	echo $twig->render('devicelist.html.twig',$template_vars);
}


if (isset($_POST['action'])) $action=$_POST['action'];
else $action="";

if (isset($_GET['a'])) $action=$_GET['a'];

if (isset($_GET['v'])) { 
	$action = 'validuser';
	$validcode = $_GET['v'];
}
session_start();

require_once "language/english.php";

$lang = $languages['english'];

if (isset($_GET['l']))
{
    include_once 'language/' . $_GET['l'] . '.php';

    if (isset($languages[$_GET['l']]))
    {
        $lang = array_merge($lang, $languages[$_GET['l']]);
        $_SESSION['lang'] = $_GET['l'];
    }
}
elseif (isset($_SESSION['lang']))
{
    include_once 'language/' . $_SESSION['lang'] . '.php';
    $lang = array_merge($lang, $languages[$_SESSION['lang']]);
}

$error=$user="";



switch(strtolower($action))
{
	case "login":
	{
		fromhome();

		$dbh = Database::connect();
		if (isset($_POST['user'])) $user=$_POST['user'];
		if (isset($_POST['pw'])) $password=$_POST['pw']; else $password="";
		$password = crypt($password, 'GliderNetdotOrg');
		$req = $dbh->prepare("SELECT * FROM users where usr_adress=:us AND usr_pw=:pw");
		$req->bindParam(":us", $user);
		$req->bindParam(":pw", $password);
		$req->execute();
		if ($req->rowCount()==1)
		{
			$result = $req->fetch();
			$req->closeCursor();
			$_SESSION['user'] = $result['usr_id'];
			$_SESSION['login'] = 'yes';

			devicelist();



		}
		else
		{
			$error = $lang['error_login'];
			home();
		}
		Database::disconnect();
		break;
	}

	case "d":		// disconnect
	{
		session_destroy();
		session_start();
		$_SESSION['home'] = 'yes';
		home();
		break;
	}

	case "u":		// fill in create user
	{
		fromhome();
		fillinuser();
		break;
	}

	case "deviceslist":		// display device list
	{
		fromhome();
		$dbh = Database::connect();
		devicelist();
		Database::disconnect();
		break;
	}

	case "n":		// fill in create device
	{
		fromhome();
		if (!isset($_SESSION['login'])) exit(); // test if user come from login page
		$_SESSION['dev'] = 'yes';
		$devtype=2;		// default type is Flarm
		fillindevice();
		break;
	}

	case "updatedev":		// update/create device
	{
		fromhome();
		if (!isset($_SESSION['login'])) exit(); // test if user come from login page
		$_SESSION['dev'] = 'yes';
		if (isset($_POST['devid'])) $devid=$_POST['devid'];
		$dbh = Database::connect();

		$req = $dbh->prepare("select * from devices where dev_id=:de AND dev_userid=:us");
		$req->bindParam(":de", $devid);
		$req->bindParam(":us", $_SESSION['user']);
		$req->execute();
		if ($req->rowCount()==1) {
			$result = $req->fetch();
			$req->closeCursor();
			$devtype = $result['dev_type'];
			$actype = $result['dev_actype'];
			$acreg = $result['dev_acreg'];
			$accn = $result['dev_accn'];
			$notrack = $result['dev_notrack'];
			$noident = $result['dev_noident'];
			fillindevice();
		}
		else
		{
			$error = $lang['error_devid'];
			devicelist();
		}

		Database::disconnect();
		break;
	}

	case "deletedev":		// delete device
		{
		fromhome();
		if (!isset($_SESSION['login'])) exit(); // test if user come from login page
		$_SESSION['dev'] = 'yes';
		if (isset($_POST['devid'])) $devid=$_POST['devid'];
		$dbh = Database::connect();
		$req = $dbh->prepare("select * from devices where dev_id=:de AND dev_userid=:us");
		$req->bindParam(":de", $devid);
		$req->bindParam(":us", $_SESSION['user']);
		$req->execute();

		if ($req->rowCount()==1) {
			$req->closeCursor();
			$del = $dbh->prepare("DELETE FROM devices where dev_id=:de AND dev_userid=:us");
			$del->bindParam(":de", $devid);
			$del->bindParam(":us", $_SESSION['user']);
			$del->execute();

			$error = $lang['device_deleted'];
			devicelist();
		}
		else {
			$error = $lang['error_devid'];
		fillindevice();
		}
		Database::disconnect();
		break;
	}

    case "createuser":		// create user
    {
        fromhome();
    if (isset($_POST['user']))  $user=$_POST['user'];
    if (isset($_POST['pw1']))   $pw1=$_POST['pw1'];     else $pw1="";
    if (isset($_POST['pw2']))   $pw2=$_POST['pw2'];     else $pw2="";
    if (isset($_POST['verif'])) $verif=$_POST['verif']; else $verif="";

    if ($verif=="" OR $verif*1!=$_SESSION['verif']*1)	$error = $lang['error_verif'];

    if (strlen($pw1)<4) $error=$lang['error_pwtooshort'];

    if ($pw1!=$pw2) $error=$lang['error_pwdontmatch'];
    if (filter_var($user, FILTER_VALIDATE_EMAIL) === false) $error=$lang['error_emailformat'];


    $dbh = Database::connect();
        $req = $dbh->prepare("select usr_adress from users where usr_adress=:us UNION ALL select tusr_adress from tmpusers where tusr_adress=:us");
        $req->bindParam(":us", $user);
        $req->execute();

        if ($req->rowCount()>0) $error=$lang['error_userexists'];
        $req->closeCursor();

    if ($error!="") fillinuser();
    else {
        $pass = crypt($pw1, 'GliderNetdotOrg');
        $valid = md5(date("dYmsHi").$user);
        $ttime=time();

        $ins = $dbh->prepare("INSERT INTO tmpusers (tusr_adress, tusr_pw, tusr_validation, tusr_time) VALUES (:us, :pw, :va, :ti)");
            $ins->bindParam(":us", $user);
            $ins->bindParam(":pw", $pass);
            $ins->bindParam(":va", $valid);
            $ins->bindParam(":ti", $ttime);

            if ( $ins->execute() ) {	// insert ok, sent email
                $msglink=$url."?v=".$valid;
                $msg="<HTML><HEAD><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><TITLE>".$lang['email_object']."</TITLE></HEAD><BODY>".$lang['email_content']."<A HREF=\"$msglink\">$msglink</A></BODY></HTML>";
                if (email($user,$lang['email_object'],$msg,$sender))
                {	// email sent
                    echo $twig->render('emailsent.html.twig',array('lang'=>$lang));
                }
                else {
                    $error = $lang['email_not_sent'];
                    fillinuser();
                }
            } else {
                $error=$lang['error_insert_tmpusers'];
                fillinuser();
            }
            $ins->closeCursor();
    }

    Database::disconnect();
    break;
    }

    case "validuser":		// user validation from email
    {
        $dbh = Database::connect();
        $req = $dbh->prepare("select * from tmpusers where tusr_validation=:vl");
        $req->bindParam(":vl", $validcode);
        $req->execute();
        if ($req->rowCount()==1)
        {		// tmpuser user found
            $result = $req->fetch();
            $req->closeCursor();
            $ins = $dbh->prepare("INSERT INTO users (usr_adress, usr_pw) VALUES (:us, :pw)");
            $ins->bindParam(":us", $result['tusr_adress']);
            $ins->bindParam(":pw", $result['tusr_pw']);

            if ( $ins->execute() )
            {	// insert ok, delete tmpuser
                $ins->closeCursor();
                $del = $dbh->prepare("DELETE FROM tmpusers where tusr_validation=:vl");
                $del->bindParam(":vl", $validcode);
                $del->execute();
                $user = $result['tusr_adress'];
                $error = $lang['email_validated'];
            }
            else {
                $error = $lang['error_validation'];
            }
        }
        else
        {
            $error = $lang['error_validation'];
        }
        $_SESSION['home'] = 'yes';
        home();
    break;
    }

    case "createdev":		// create device
    {
        fromhome();
        $notrack=$noident=0;
        if (!isset($_SESSION['login'])) exit(); // test if user come from login page
        if (!isset($_SESSION['dev'])) exit(); // test if user come from fill in device page
        if (!isset($_SESSION['user'])) exit(); // test if user id defined

        if (isset($_POST['devid'])) $devid=$_POST['devid']; else $error = $lang['error_devid'];
        if (isset($_POST['devtype'])) $devtype=$_POST['devtype']; else $error = $lang['error_devtype'];
        if (isset($_POST['actype'])) $actype=$_POST['actype']; else $error = $lang['error_actype'];
        if (isset($_POST['acreg'])) $acreg=$_POST['acreg']; else $error = $lang['error_acreg'];
        if (isset($_POST['accn'])) $accn=$_POST['accn']; else $error = $lang['error_accn'];
        if (isset($_POST['notrack'])) {
            if ($_POST['notrack']=="yes") $notrack=1;
        }
            if (isset($_POST['noident'])) {
            if ($_POST['noident']=="yes") $noident=1;
        }

        if (isset($_POST['owner'])) {
            if ($_POST['owner']!="yes") $error = $lang['error_owner'];
        } else $error = $lang['error_owner'];

        $devid = strtoupper($devid);
        if ( preg_match ( " /[A-F0-9]{6}/ " , $devid )) {} // ok
        else $error = $lang['error_devid'];

            // replace all TAB by space
            $acreg = trim(preg_replace('/\t+/', ' ', $acreg));
            $accn = trim(preg_replace('/\t+/', ' ', $accn));

            // replace all " ' by -
            $repstr   = array("'", "\"", ",");
            $acreg = str_replace($repstr, '-', $acreg);
            $accn = str_replace($repstr, '-', $accn);

        if (strlen($devid)!=6) $error = $lang['error_devid'];
        if (strlen($acreg)>7) $error = $lang['error_acreg'];
        if (strlen($accn)>3) $error = $lang['error_accn'];
        if ($devtype<1 OR $devtype>3) $error = $lang['error_devtype'];

        $dbh = Database::connect();
            $req = $dbh->prepare("select dev_id,dev_userid from devices where dev_id=:de");	// test if device is owned by another account
            $req->bindParam(":de", $devid);
            $req->execute();

            $upd=false;
            if ($req->rowCount()==1) {		// if device already registred
                $result = $req->fetch();
                if ($result['dev_userid']==$_SESSION['user']) $upd=true;		// if owned by the user then update
                else $error = $lang['error_devexists'];
            }
            $req->closeCursor();

        if ($error!="") fillindevice();
        else {
            if ($upd) $ins = $dbh->prepare("UPDATE devices SET dev_type=:dt, dev_actype=:ty, dev_acreg=:re, dev_accn=:cn, dev_notrack=:nt, dev_noident=:ni WHERE dev_id=:de AND dev_userid=:us");
            else $ins = $dbh->prepare("INSERT INTO devices (dev_id, dev_type, dev_actype, dev_acreg, dev_accn, dev_userid, dev_notrack, dev_noident) VALUES (:de, :dt, :ty, :re, :cn, :us, :nt, :ni)");
            $ins->bindParam(":de", $devid);
            $ins->bindParam(":dt", $devtype);
            $ins->bindParam(":ty", $actype);
            $ins->bindParam(":re", $acreg);
            $ins->bindParam(":cn", $accn);
            $ins->bindParam(":nt", $notrack);
            $ins->bindParam(":ni", $noident);
                $ins->bindParam(":us", $_SESSION['user']);

                if ( $ins->execute() ) {	// insert ok, send email
                    if ($upd) $error = $lang['device_updated'];
                    else $error = $lang['device_inserted'];
                    devicelist();
                } else {
                    $error=$lang['error_insert_device'];
                    fillindevice();
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
