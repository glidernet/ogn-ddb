<?php
include 'sql.php';

//
// Localisation TODO:
// - aircraft types (see $catarray)
//

$url = "http://ddb.glidernet.org/";
$sender = "contact@glidernet.org";

$languages= array (
	"english" => array (
		"input_welcome" => "Welcome to the OGN Devices database",
		"input_login" => "User (email address):",
		"input_password" => "Password:",
		"input_confpassword" => "Confirm password:",
		"input_submit" => "Submit",
		"input_cancel" => "Cancel",
		"input_nouser" => "If you're not already a member, ",
		"input_create" => "create an account",
		"input_createdev" => "Register a device",
		"input_owner" => "I certify to be the owner of this device",
		"input_verif" => "To be sure you're a human being, please resolve this:",
		"input_nbdevices" => "registered devices",		
		"input_notrack" => "I don't want this device to be tracked",
		"input_noident" => "I don't want this device to be identified",		
		"error_login" => "Address or password incorrect",		
		"error_pwdontmatch" => "Passwords don't match",
		"error_pwtooshort" => "Password is too short (4 char mini)",
		"error_emailformat" => "Invalid email address format",
		"error_userexists" => "This user already exists",
		"error_insert_tmpusers" => "Database error, please retry later",
		"error_validation" => "Error validating your address, already validated?",
		"error_nodevice" => "No device recorded for this account",		
		"error_devid" => "Device ID format incorrect",
		"error_devtype" => "Device Type format incorrect",
		"error_actype" => "Aircraft type format incorrect",
		"error_acreg" => "Aircraft registration format incorrect",
		"error_accn" => "Aircraft Competition Number format incorrect",
		"error_devexists" => "This device is already registered",
		"error_owner" => "You must be the owner of the device",
		"error_verif" => "Incorrect equation :-(",
		"device_deleted" => "Device deleted",		
		"device_updated" => "Device updated",
		"device_inserted" => "Device inserted",
		"email_object" => "[OGN] Account Validation",
		"email_content" => "Hello,<BR>Thank you for contributing to OGN.<BR>To confirm this email address you have to click on the link below:<BR>",		
		"email_sent" => "An email has just been sent to you, you'll find instructions on how to validate your account.",
		"email_not_sent" => "Error sending email, check your address is correct.",		
		"email_validated" => "Your account is validated, now you can login.",
		"table_devid" => "Device ID",
		"table_devtype" => "Device type",
		"table_actype" => "Aircraft type",
		"table_acreg" => "Regist.",
		"table_acreg2" => "Registration",
		"table_accn" => "CN",
		"table_accn2" => "Competition Number",
		"table_notrack" => "Tracking",
		"table_noident" => "Ident.",		
		"table_update" => "Update",
		"table_delete" => "Delete",
		"table_new" => "Add new",
		"disconnect" => "Disconnect",
				
	),
	"french" => array (
		"input_welcome" => "Bienvenue sur la database Emetteurs OGN",
		"input_login" => "Utilisateur (adresse courriel):",
		"input_password" => "Mot de passe:",
		"input_confpassword" => "Confirmez le mot de passe:",		
		"input_submit" => "Valider",
		"input_cancel" => "Annuler",		
		"input_nouser" => "Si vous n'est pas encore membre, ",
		"input_create" => "créez un compte",
		"input_createdev" => "Enregistrer un appareil",
		"input_owner" => "Je certifie être le possesseur de cet appareil",
		"input_verif" => "Pour être sûr que vous soyez un humain, merci de résoudre ceci:",
		"input_nbdevices" => "émetteurs enregistrés",
		"input_notrack" => "Je ne veux pas que cet émetteur soit pisté",
		"input_noident" => "Je ne veux pas que cet émetteur soit identifié",		
		"error_login" => "Adresse ou mot de passe incorrect",		
		"error_pwdontmatch" => "Les mots de passe ne correspondent pas",
		"error_pwtooshort" => "Mot de passe trop court (4 car mini)",
		"error_emailformat" => "Format d'adresse courriel invalide",
		"error_userexists" => "Utilisateur déjà existant",
		"error_insert_tmpusers" => "Erreur Base de données, ré-essayez plus tard",
		"error_validation" => "Erreur de validation, déjà validé?",		
		"error_nodevice" => "Il n'y a pas d'appareil enregistré pour ce compte",
		"error_devid" => "Format ID appareil incorrect",
		"error_devtype" => "Format type d'appareil incorrect",
		"error_actype" => "Format type de machine incorrect",
		"error_acreg" => "Format immatriculation incorrect",
		"error_accn" => "Format N° concours incorrect",
		"error_devexists" => "Cet appareil est déjà enregistré",
		"error_owner" => "Vous devez être le possesseur de l'appareil",
		"error_verif" => "L'équation est fausse :-(",		
		"device_deleted" => "Appareil supprimé",
		"device_updated" => "Appareil mis à jour",
		"device_inserted" => "Appareil enregistré",
		"email_object" => "[OGN] Validation du Compte",
		"email_content" => "Bonjour,<BR>Merci de contribuer à OGN.<BR>Pour confirmer cette adresse de courriel, cliquez sur le lien ci-dessous:<BR>",
		"email_sent" => "Un courriel vous a été envoyé, vous trouverez les instruction pour valider votre compte.",
		"email_not_sent" => "Erreur d'envoi de courriel, vérifiez que votre adresse est correcte.",
		"email_validated" => "Votre compte est validé, vous pouvez vous connecter.",
		"table_devid" => "ID Appareil",
		"table_devtype" => "Type d'appareil",
		"table_actype" => "Type aéronef",
		"table_acreg" => "Immat.",
		"table_acreg2" => "Immatriculation",
		"table_accn" => "N°",
		"table_accn2" => "N° de concours",
		"table_notrack" => "Tracking",
		"table_noident" => "Ident.",		
		"table_update" => "Modifier",
		"table_delete" => "Supprimer",
		"table_new" => "Ajouter",
		"disconnect" => "Déconnexion",
		
	),
        // German terminology:
        // Datenbank
        // Gerät
        // Benutzer
        // Benutzerkonto
        // Tracking
        // E-Mail (with dash)
        // (Wettbewerbs-)Kennzeichen
        // identifizieren
        // eintragen
        // hinzufügen
        // aktualisieren
        // an/abmelden (log in/out)
	"german" => array (
		"input_welcome" => "Willkommen zur OGN-Gerätedatenbank",
		"input_login" => "E-Mail-Addresse:",
		"input_password" => "Passwort:",
		"input_confpassword" => "Passwort (Bestätigung):",
		"input_submit" => "OK",
		"input_cancel" => "Abbrechen",
                "input_nouser" => "Wenn Sie noch kein Benutzerkonto haben, ",
		"input_create" => "erzeugen Sie ein Benutzerkonto",
		"input_createdev" => "Gerät eintragen",
		"input_owner" => "Ich bestätige, der Besitzer dieses Gerätes zu sein.",
		"input_verif" => "Beweisen Sie, dass Sie ein Mensch sind:",
		"input_nbdevices" => "eingetragene Geräte",		
                "input_notrack" => "Dieses Gerät nicht tracken",
                "input_noident" => "Dieses Gerät nicht mit Kennzeichen identifizieren",
		"error_login" => "Falsche E-Mail-Adresse oder Passwort",		
                "error_pwdontmatch" => "Passwörter stimmen nicht überein",
                "error_pwtooshort" => "Das Passwort muss mindestens vier Zeichen haben",
                "error_emailformat" => "Falsche E-Mail-Adresse",
                "error_userexists" => "Benutzerkonto existiert bereits",
                "error_insert_tmpusers" => "Datenbank-Fehler, bitte versuchen Sie es später nochmals.",
                "error_validation" => "Ihre E-Mail-Adresse konnte nicht validiert werden. Wurde sie bereits validiert?",
		"error_nodevice" => "Kein Gerät unter diesem Benutzerkonto eingetragen",
		"error_devid" => "Format-Fehler (Geräte-ID)",
                "error_devtype" => "Format-Fehler (Gerätetyp)",
                "error_actype" => "Format-Fehler (Flugzeugtyp)",
                "error_acreg" => "Format-Fehler (Kennzeichen)",
                "error_accn" => "Format-Fehler (Wettbewerbskennzeichen)",
                "error_devexists" => "Dieses Gerät ist bereits eingetragen",
                "error_owner" => "Sie müssen der Besitzer dieses Gerätes sein",
		"error_verif" => "Falsches Resultat :-(",
		"device_deleted" => "Gerät gelöscht",
                "device_updated" => "Gerät aktualisiert",
		"device_inserted" => "Gerät eingetragen",
                "email_object" => "[OGN] Benutzerkonto-Validierung",
		"email_content" => "Hallo,<BR>Vielen Dank für Ihren Beitrag zu OGN!<BR>Um diese E-Mail-Adresse zu validieren, klicken Sie bitte auf folgenden Link:<BR>",		
		"email_sent" => "Sie erhalten in Kürze eine E-Mail.  Darin finden Sie Instruktionen, wie Sie Ihr Benutzerkonto validieren können.",
		"email_not_sent" => "Versand der E-Mail fehlgeschlagen.  Bitte überprüfen Sie Ihre E-Mail-Adresse!",
                "email_validated" => "Ihr Benutzerkonto wurde validiert, Sie können sich jetzt anmelden.",
		"table_devid" => "Geräte-ID",
                "table_devtype" => "Gerätetyp",
                "table_actype" => "Flugzeugtyp",
                "table_acreg" => "Kennz.",
		"table_acreg2" => "Kennzeichen",
		"table_accn" => "WK",
                "table_accn2" => "Wettbewerbskennzeichen",
		"table_notrack" => "Tracking",
		"table_noident" => "Kennzeichen",
		"table_update" => "Aktualisieren",
		"table_delete" => "Löschen",
		"table_new" => "Hinzufügen",
		"disconnect" => "Abmelden",
	)
	
);

function htmlheader() {
		echo "<HTML><HEAD>
<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\"/>
<meta name=\"robots\" content=\"noindex,nofollow\" />
<TITLE>OGN Database</TITLE>
<style>
form { margin-bottom: 0; }
.tab1 {border-width:1px; border-style:solid; border-color:black; border-collapse: collapse; border-spacing: 1px;}
.tab2 {border-width:1px; border-style:solid; border-color:grey; }
.p1l {min-width:70px; background-color: #EEEEFF; text-align: left; vertical-align:middle; }
.p2l {min-width:70px; background-color: #CCCCFF; text-align: left; vertical-align:middle; }
.inp {min-width:70px; text-align: right;}
</style>
</HEAD><BODY>";
}

function htmlfooter($dis="") {
	global $lang,$url;
		echo "<BR><BR><CENTER>";
		if ($dis!="no") echo "<A HREF=\"index.php?a=d\">".$lang['disconnect']."</A><BR>";
		
		echo "<IMG SRC=\"".$url."pict/ogn-logo-ani.gif\"></CENTER>
	</BODY></HTML>
	";
}

function email($dest, $subject, $message, $from="")  {
  $email_subject = $subject; // The Subject of the email 
  $email_txt = $message;
  $email_to = "$dest"; // Who the email is to 
  
  $headers = 	"From: {$from}\n".
		"MIME-Version: 1.0\n". 
		"Content-Type: text/html; charset=\"UTF-8\"\n";

  
  $email_message .= "$email_txt \n\n"; 
  
  return @mail($email_to, $email_subject, $email_message, $headers, "-f".$from); 
}



function home() {
	global $lang,$error,$user;
	htmlheader();
	
	$dbh = Database::connect();
  $req = $dbh->query("SELECT count(dev_id) as nb FROM devices ");
	$result = $req->fetch();
	$req->closeCursor();
	$nbdevices=$result['nb'];
	Database::disconnect();

	
	echo "<DIV style=\"margin-left: auto; margin-right: auto; width:450px\">
	<CENTER>
	<FORM action=\"index.php\" ENCTYPE=\"multipart/form-data\" method=\"post\">
	<INPUT type=\"hidden\" name=\"action\" value=\"login\">
	<H2>{$lang['input_welcome']}</H2>";
	if ($error!="") echo "<H3 style=\"color:red\">$error</H3>";
	echo "<TABLE>
		<TR><TD>{$lang['input_login']}</TD><TD><INPUT TYPE=\"text\" name=\"user\" VALUE=\"$user\" SIZE=25></TD></TR>
		<TR><TD>{$lang['input_password']}</TD><TD><INPUT TYPE=\"password\" name=\"pw\" VALUE=\"\" SIZE=25></TD></TR>
	</TABLE>
	<input type=\"submit\" value=\"{$lang['input_submit']}\"></FORM>
	<BR><BR>
	{$lang['input_nouser']}<A HREF=\"index.php?a=u\">{$lang['input_create']}</A><BR><BR>(&nbsp;<B>$nbdevices {$lang['input_nbdevices']}</B>&nbsp;) 
	
	</CENTER></DIV>";
	
	
	htmlfooter("no");
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
	global $lang,$error,$user;
	htmlheader();
	$v1=rand(5, 9);
	$v2=rand(5, 9);
	$_SESSION['verif']=$v1*$v2;
	echo "<DIV style=\"margin-left: auto; margin-right: auto; width:450px\">
	<CENTER>
	<FORM action=\"index.php\" ENCTYPE=\"multipart/form-data\" method=\"post\">
	<INPUT type=\"hidden\" name=\"action\" value=\"createuser\">
	<H2>{$lang['input_create']}</H2>";
	if ($error!="") echo "<H3 style=\"color:red\">$error</H3>";
	echo "<TABLE>
		<TR><TD>{$lang['input_login']}</TD><TD><INPUT TYPE=\"text\" name=\"user\" VALUE=\"$user\" SIZE=25></TD></TR>
		<TR><TD>{$lang['input_password']}</TD><TD><INPUT TYPE=\"password\" name=\"pw1\" VALUE=\"\" SIZE=25></TD></TR>
		<TR><TD>{$lang['input_confpassword']}</TD><TD><INPUT TYPE=\"password\" name=\"pw2\" VALUE=\"\" SIZE=25></TD></TR>
		<TR><TD colspan=\"2\">{$lang['input_verif']}<BR>$v1 x $v2 =<INPUT TYPE=\"text\" name=\"verif\" VALUE=\"\" SIZE=5></TD></TR>
	</TABLE>
	<input type=\"submit\" value=\"{$lang['input_submit']}\"></FORM>";
	htmlfooter();
}

function fillindevice() {
	global $lang,$error,$devid,$devtype,$acreg,$accn,$actype,$notrack,$noident;
	$catarray = array (1=>"Gliders/motoGliders",
								2=>"Planes",
								3=>"Ultralights",
								4=>"Helicoters",
								5=>"Drones/UAV",
								6=>"Others");	
	$cat = 0;
	htmlheader();
	$cnotrack=$cnoident="";
	if ($notrack==1) $cnotrack="checked"; 
	if ($noident==1) $cnoident="checked";
	echo "<DIV style=\"margin-left: auto; margin-right: auto; width:450px\">
	<CENTER>
	<FORM action=\"index.php\" ENCTYPE=\"multipart/form-data\" method=\"post\">
	<INPUT type=\"hidden\" name=\"action\" value=\"createdev\">
	<H2>{$lang['input_createdev']}</H2>";
	if ($error!="") echo "<H3 style=\"color:red\">$error</H3>";
	$dtypc1=$dtypc2=$dtypc3="";
	$var="dtypc".$devtype;
	$$var=" CHECKED";
	echo "<TABLE>
		<TR><TD class=\"inp\">{$lang['table_devtype']}</TD><TD><INPUT TYPE=\"radio\" NAME=\"devtype\" VALUE=\"1\" $dtypc1>ICAO <INPUT TYPE=\"radio\" NAME=\"devtype\" VALUE=\"2\" $dtypc2>Flarm <INPUT TYPE=\"radio\" NAME=\"devtype\" VALUE=\"3\" $dtypc3>OGN</TD></TR>
		<TR><TD class=\"inp\">{$lang['table_devid']}</TD><TD><INPUT TYPE=\"text\" name=\"devid\" VALUE=\"$devid\" size=\"25\" maxlength=\"6\"></TD></TR>
		<TR><TD class=\"inp\">{$lang['table_actype']}</TD><TD><SELECT name=\"actype\">";
		
	$dbh = Database::connect();
	foreach ($dbh->query('SELECT * FROM aircrafts ORDER BY ac_cat,ac_type') as $row) {
		if ($row['ac_cat'] != $cat) {
			++$cat;
			if ($cat!=1) echo "</optgroup>";
			echo "<optgroup label=\"{$catarray[$row['ac_cat']]}\">";
		}
		echo "<OPTION VALUE=\"{$row['ac_id']}\"";
		if ($row['ac_id']==$actype) echo " selected";
		echo ">{$row['ac_type']}</OPTION>";
	}
	Database::disconnect();
	
	echo "</optgroup></SELECT></TD></TR>
		<TR><TD class=\"inp\">{$lang['table_acreg2']}</TD><TD><INPUT TYPE=\"text\" name=\"acreg\" VALUE=\"$acreg\" size=\"25\" maxlength=\"7\"></TD></TR>
		<TR><TD class=\"inp\">{$lang['table_accn2']}</TD><TD><INPUT TYPE=\"text\" name=\"accn\" VALUE=\"$accn\" size=\"25\" maxlength=\"3\"></TD></TR>
		<TR><TD class=\"inp\"><input type=\"checkbox\" name=\"notrack\" value=\"yes\" $cnotrack></TD><TD>{$lang['input_notrack']}</TD></TR>
		<TR><TD class=\"inp\"><input type=\"checkbox\" name=\"noident\" value=\"yes\" $cnoident></TD><TD>{$lang['input_noident']}</TD></TR>
		<TR><TD class=\"inp\"><input type=\"checkbox\" name=\"owner\" value=\"yes\"></TD><TD>{$lang['input_owner']}</TD></TR>
		
	</TABLE>
	<input type=\"submit\" value=\"{$lang['input_submit']}\"></FORM>";

	echo "&nbsp;&nbsp;&nbsp;
	<FORM action=\"index.php\" ENCTYPE=\"multipart/form-data\" method=\"post\">
	<INPUT type=\"hidden\" name=\"action\" value=\"deviceslist\">
	<input type=\"submit\" value=\"{$lang['input_cancel']}\"></FORM>
	";
	htmlfooter();
}

function devicelist() {
	global $dbh,$lang,$error,$url;
	$arraytype = array(1 => "ICAO", 2=> "Flarm", 3=> "OGN");
	$req2 = $dbh->prepare("SELECT * FROM devices,aircrafts where dev_userid=:us AND dev_actype=ac_id ORDER BY dev_id ASC");
	$req2->bindParam(":us", $_SESSION['user']);
	$req2->execute();
	htmlheader();
	if ($req2->rowCount()==0) {
		echo "<CENTER>".$lang['error_nodevice'].", ".$lang['table_new'].": <A HREF=\"?a=n\"><IMG style=\"border: none\" SRC=\"".$url."pict/plu.png\" title=\"".$lang['table_new']."\"></A></CENTER>";
	} else {
		echo "<CENTER>";
		if ($error!="") echo "<H3 style=\"color:red\">$error</H3>";
		echo "<TABLE class=\"tab1\"><TR><TH colspan=\"2\"><A HREF=\"?a=n\"><IMG style=\"border: none\" SRC=\"".$url."pict/plu.png\" title=\"".$lang['table_new']."\"></A></TH><TH>".$lang['table_devtype']."</TH><TH>".$lang['table_devid']."</TH><TH>".$lang['table_actype']."</TH><TH>".$lang['table_acreg']."</TH><TH>".$lang['table_accn']."</TH><TH>".$lang['table_notrack']."</TH><TH>".$lang['table_noident']."</TH></TR>";
		//$alldev = $req2->fetchAll();
		$cl=1;
		while($ligne = $req2->fetch(PDO::FETCH_ASSOC)) {
			extract($ligne);
			echo "<TR class=\"p{$cl}l\">
				<TD class=\"tab2\">
						<FORM action=\"index.php\" ENCTYPE=\"multipart/form-data\" method=\"post\">
							<INPUT type=\"hidden\" name=\"action\" value=\"updatedev\">
							<INPUT type=\"hidden\" name=\"devid\" value=\"{$dev_id}\">
							<INPUT border=0 SRC=\"".$url."pict/mod.gif\" type=\"image\" Value=\"submit\" title=\"".$lang['table_update']."\"> 
						</FORM>
					</TD>
				<TD class=\"tab2\">
						<FORM action=\"index.php\" ENCTYPE=\"multipart/form-data\" method=\"post\">
							<INPUT type=\"hidden\" name=\"action\" value=\"deletedev\">
							<INPUT type=\"hidden\" name=\"devid\" value=\"{$dev_id}\">
							<INPUT border=0 SRC=\"".$url."pict/bin.gif\" type=\"image\" Value=\"submit\" title=\"".$lang['table_delete']."\"> 
						</FORM>
				</TD>
				<TD class=\"tab2\">".$arraytype[$dev_type]."</TD>
				<TD class=\"tab2\">{$dev_id}</TD><TD class=\"tab2\">{$ac_type}</TD>
				<TD class=\"tab2\">{$dev_acreg}</TD>
				<TD class=\"tab2\">{$dev_accn}</TD>
				<TD class=\"tab2\"><iMG SRC=\"".$url."pict/yn{$dev_notrack}.gif\"></TD>
				<TD class=\"tab2\"><iMG SRC=\"".$url."pict/yn{$dev_noident}.gif\"></TD>
				</TR>";
			if (++$cl==3) $cl=1;
		}
		echo "</TABLE></CENTER>";
		
	}
	htmlfooter(); 
}


if (isset($_POST['action'])) $action=$_POST['action'];
else $action="";

if (isset($_GET['a'])) $action=$_GET['a'];

if (isset($_GET['v'])) { 
	$action = 'validuser';
	$validcode = $_GET['v'];
}

$lang = $languages['english'];

$error=$user="";

session_start();


switch(strtolower($action))
{
  case "login":
  {
  	fromhome();
  	
  	$dbh = Database::connect();
  	if (isset($_POST['user'])) $user=$_POST['user'];
  	if (isset($_POST['pw'])) $password=$_POST['pw']; else $password="";
  	
		$req = $dbh->prepare("SELECT * FROM users where usr_adress=:us AND usr_pw=:pw");
		$req->bindParam(":us", $user);
		$req->bindParam(":pw", crypt($password, 'GliderNetdotOrg'));
		$req->execute();
		if ($req->rowCount()==1) {
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
  	} else {
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
		} else {
			$error = $lang['error_devid'];
	  	fillindevice();
	  }
	  Database::disconnect();
    break;
	}



  case "createuser":		// create user
  {
		fromhome();
  	if (isset($_POST['user'])) $user=$_POST['user'];
  	if (isset($_POST['pw1'])) $pw1=$_POST['pw1']; else $pw1="";
  	if (isset($_POST['pw2'])) $pw2=$_POST['pw2']; else $pw2="";
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
				if (email($user,$lang['email_object'],$msg,$sender)) {	// email sent
					htmlheader();
					echo "<CENTER>";
					echo $lang['email_sent'];
					echo "</CENTER>";
					htmlfooter();
				} else {
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
		if ($req->rowCount()==1) {		// tmpuser user found
			$result = $req->fetch();
			$req->closeCursor();
			$ins = $dbh->prepare("INSERT INTO users (usr_adress, usr_pw) VALUES (:us, :pw)");
			$ins->bindParam(":us", $result['tusr_adress']);
			$ins->bindParam(":pw", $result['tusr_pw']);
			
			if ( $ins->execute() ) {	// insert ok, delete tmpuser
				$ins->closeCursor();
				$del = $dbh->prepare("DELETE FROM tmpusers where tusr_validation=:vl");
				$del->bindParam(":vl", $validcode);
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
