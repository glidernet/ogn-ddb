<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:22.
 */

/**
 * Updated
 * User: mrandt
 * Date: 2016-06-20
 */

// German terminology:
// Datenbank
// Gerät
// Benutzer
// Benutzerkonto
// Verfolgen (Tracking)
// E-Mail (with dash)
// (Wettbewerbs-)Kennzeichen
// identifizieren
// eintragen
// hinzufügen
// aktualisieren
// an/abmelden (log in/out)

$languages['german'] = array (
    "input_welcome" => "Willkommen zur OGN-Gerätedatenbank (OGN DDB)",
    "input_login" => "E-Mail-Adresse:",
    "input_password" => "Passwort:",
    "input_newpassword" => "Neues Passwort:",
    "input_confpassword" => "Passwort (Bestätigung):",
    "input_submit" => "OK",
    "input_cancel" => "Abbrechen",
    "input_nouser" => "Wenn Sie noch kein Benutzerkonto haben, ",
    "input_forgot" => "Passwort vergessen ???, ",
    "input_forgotproc" => "Passwort zurücksetzen  ",
    "input_help" => "Brauche Hilfe? ",
    "input_create" => "erstellen Sie ein neues Konto!",
    "input_createdev" => "Gerät eintragen",
    "input_owner" => "Ich bestätige, der Besitzer dieses Gerätes zu sein.",
    "input_verif" => "Beweisen Sie, dass Sie ein Mensch sind:",
    "input_nbdevices" => "eingetragene Geräte",
    "input_notrack" => "Dieses Gerät nicht verfolgen (No-Track)",
    "input_noident" => "Dieses Gerät nicht identifizieren (No-Ident)",
    "error_login" => "Falsche E-Mail-Adresse oder Passwort",
    "error_pwdontmatch" => "Passwörter stimmen nicht überein",
    "error_pwtooshort" => "Das Passwort muss mindestens vier Zeichen haben",
    "error_emailformat" => "Falsche E-Mail-Adresse",
    "error_userexists" => "Benutzerkonto existiert bereits",
    "error_userdoesnotexists" => "Benutzerkonto existiert nicht",
    "error_insert_tmpusers" => "Datenbank-Fehler, bitte versuchen Sie es später nochmals.",
    "error_validation" => "Ihre E-Mail-Adresse konnte nicht validiert werden. Wurde diese bereits validiert?",
    "error_nodevice" => "Zu diesem Benutzerkonto ist kein Gerät registriert.",
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
    "table_notrack" => "Verfolgen (Tracking)",
    "table_noident" => "Identifizieren (Ident)",
    "table_update" => "Aktualisieren",
    "table_delete" => "Löschen",
    "table_new" => "Hinzufügen",
    "disconnect" => "Abmelden",
    //new:
    "home" => "Startseite",
    "login" => "Anmelden",
    "create_account" => "Konto erstellen",
    "my_devices" => "Meine Geräte",
    "add_device" => "Neues Gerät eintragen",
    "change_password" => "Passwort ändern",
    "full_participation" => "Teilnahme an allen Diensten (Opt-In)", //no checkboxes checked
    "full_participation_description" => "<ul>
        <li>Anwendungen und Dienste, welche die OGN Devices Database (OGN DDB) verwenden, zeigen die Position Ihres Luftfahrzeuges inklusive der Kennung und des Musters an.</li>
        <li>Luftfahrzeugkennung, Muster und die hexadezimale FLARM bzw. OGN ID werden in der OGN DDB veröffentlicht.</li></ul>",
    "anonymous_participation" => "Anonyme Teilnahme", //only noident checkbox checked
    "anonymous_participation_description" => "<ul>
        <li>Anwendungen und Dienste, welche die OGN DDB verwenden, zeigen die Position Ihres Luftfahrzeuges <em>anonym</em> an; das Luftfahrzeug kann <em>nicht</em> identifiziert werden.</li>
        <li>Luftfahrzeugkennung und Muster werden <em>nicht</em> veröffentlicht</li>.</ul>",
    "no_participation" => "Keine Teilnahme (Opt-Out)", //notrack or both checkboxes checked
    "no_participation_description" => "<ul>
        <li>Anwendungen und Dienste, welche die OGN DDB verwenden, zeigen die Position Ihres Luftfahrzeuges <em>nicht</em> an.</li>
        <li>Luftfahrzeugkennung und Muster werden <em>nicht</em> veröffentlicht.</li>
        <li>Im Notfall kann Ihr Luftfahrzeug <em>nicht</em> mit Hilfe des OGN geortet werden.</li>
        <li>Sie tragen <em>nicht</em> zu einer Verbesserung des Verkehrslagebildes für Piloten, Flugleiter am Boden und die offiziellen Lotsen (ATC) bei.</li>
        <li>Die von Ihrem FLARM ausgestrahlten Funksignale werden von OGN-Bodenstationen <em>nicht</em> verarbeitet, sofern diese den sogenannten &quot;Whitelist-Filter&quot; verwenden.</li>        
        </ul>",
    "welcome_text" => "<p>
        Hier können Sie Ihr Segelflugzeug, Schleppflugzeug oder ein anderes mit FLARM bzw. OGN-Tracker ausgestattetes Luftfahrzeug 
        registrieren und die Nutzung der Positionsdaten durch das Open Glider Network konfigurieren.<br><br>
        Die Registrierung bietet Ihnen folgende Vorteile:
        <ul>
            <li>Sie können beeinflussen, ob und wie Ihr Luftfahrzeug auf <a href='http://live.glidernet.org'>live.glidernet.org</a> und in anderen OGN Anwendungen und Diensten erscheint.</li>
            <li>Ihr Luftfahrzeug kann im Notfall schnell mit Hilfe des OGN geortet und gefunden werden (SAR).</li>
            <li>Sie tragen zu einer Verbesserung des Verkehrslagebildes für Piloten, Flugleiter am Boden und die offiziellen Lotsen (ATC) bei.</li>
        </ul>
        Alle Daten des OGN sind frei und öffentlich unter der <a href='http://opendatacommons.org/licenses/by/summary/'>ODC-BY</a> Lizenz verfügbar.
        </p>",
);
