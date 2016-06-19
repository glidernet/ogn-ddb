<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:22.
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
    "email_subject" => "[OGN] Benutzerkonto-Validierung",
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
    //new:
    "home" => "Startseite",
    "login" => "Einloggen",
    "create_account" => "Konto erstellen",
    "my_devices" => "Mein Geräten",
    "add_device" => "Gerät eintragen",
    "full_participation" => "Full participation", //no checkboxes checked
    "full_participation_description" => "<ul>
        <li>Tracking applications that use the OGN DDB will mark the position with aircraft identification</li>
        <li>Aircraft registration and CN are published in the OGN Devices Database</li></ul>",
    "anonymous_participation" => "Anonymous participation", //only noident checkbox checked
    "anonymous_participation_description" => "<ul>
        <li>Tracking applications that use the OGN DDB will mark the position with an <em>anonymous</em> marker</li>
        <li>Aircraft registration and CN are <em>not</em> made public</li></ul>",
    "no_participation" => "No participation", //notrack or both checkboxes checked
    "no_participation_description" => "<ul>
        <li>Tracking applications that use the OGN DDB will <em>not</em> mark your position</li>
        <li>Aircraft registration and CN are <em>not</em> made public.</li>
        <li>SAR-functions <em>may not</em> be available for this device</li>
        <li>This device <em>may not</em> contribute to traffic awareness through OGN</li></ul>",
    "welcome_text" => "<p>
        This is the place to register your glider, towplane or other FLARM/OGN-equipped aircraft
        to the Open Glider Network. Registering has several advantages:
        <ul>
            <li>You can influence how your glider is displayed on
                <a href='http://live.glidernet.org'>live.glidernet.org</a> and other tracking sites</li>
            <li>In case of SAR, your glider may be easier to find</li>
            <li>You contribute to traffic-awareness among other pilots and ATC</li>
        </ul>
        The data is freely available under the <a href='http://opendatacommons.org/licenses/by/summary/'>ODC-BY</a> license.
        </p>",

);
