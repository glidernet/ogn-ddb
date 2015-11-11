<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:22
 */


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

$languages['german'] = array (
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
);