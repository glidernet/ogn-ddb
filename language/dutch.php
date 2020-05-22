<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:19.
 */

$languages['dutch'] = array (
    "input_welcome" => "Welkom bij de OGN Devices Database",
    "input_login" => "Email adres:",
    "input_password" => "Wachtwoord:",
    "input_newpassword" => "New password:",
    "input_confpassword" => "Bevestig wachtwoord:",
    "input_submit" => "Verstuur",
    "input_cancel" => "Annuleer",
    "input_nouser" => "Bent u nog niet geregistreerd? ",
    "input_forgot" => "Forgot your password ???, ",
    "input_forgotproc" => "Reset password  ",
    "input_help" => "Hulp nodig? ",
    "input_create" => "Maak een account aan!",
    "input_createdev" => "Registreer een apparaat",
    "input_owner" => "Ik verklaar dat ik de eigenaar ben van dit apparaat",
    "input_verif" => "Los de rekensom op, om te controleren dat u geen robot bent:",
    "input_nbdevices" => "Geregistreerde devices",
    "input_notrack" => "Dit apparaat mag NIET getracked worden",
    "input_noident" => "Dit apparaat mag NIET geidentificeerd worden",
    "error_login" => "Gebruikersnaam of wachtwoord incorrect",
    "error_pwdontmatch" => "Wachtwoorden komen niet overeen",
    "error_pwtooshort" => "Wachtwoord is te kort (minimaal 4 karakters)",
    "error_emailformat" => "E-mail adres is ongeldig",
    "error_userexists" => "Deze gebruikersnaam bestaat al",
    "error_userdoesnotexists" => "This user does not exists",
    "error_insert_tmpusers" => "Database fout, probeer het later nog eens",
    "error_validation" => "Fout bij het valideren van uw e-mail adres. Bent u misschien al gevalideerd?",
    "error_nodevice" => "Geen apparaten gevonden voor dit account",
    "error_devid" => "Het format van Apparaat ID is incorrect",
    "error_devtype" => "Het format van  Apparaat Type is incorrect",
    "error_actype" => "Vliegtuigtype is incorrect",
    "error_acreg" => "Vliegtuigregistratie is incorrect",
    "error_accn" => "Vliegtuig callsign is incorrect",
    "error_devexists" => "Dit apparaat is al geregistreerd!",
    "error_owner" => "U moet de eigenaar zijn van het apparaat wat u registreert",
    "error_verif" => "Uitkomst rekensom is incorrect :-(",
    "device_deleted" => "Apparaat verwijderd",
    "device_updated" => "Apparaat geupdate",
    "device_inserted" => "Apparaat toegevoegd",
    "email_subject" => "[OGN] Account Validatie",
    "email_content" => "Hallo,<BR>Bedankt voor uw bijdrage aan OGN. U heeft zich aangemeld voor de Devices Database.<BR>Klik op de link hieronder om uw e-mail adres te valideren:<BR>",
    "email_sent" => "Er is een e-mail naar u verstuurd met instructies om uw account te valideren.",
    "email_not_sent" => "E-mail kan niet verzonden worden. Controleer of uw adres correct is.",
    "email_validated" => "Uw account is gevalideerd. U kunt nu inloggen!",
    "table_devid" => "Apparaat ID",
    "table_devtype" => "Apparaat type",
    "table_actype" => "Vliegtuigtype",
    "table_acreg" => "Registr.",
    "table_acreg2" => "Regristratie",
    "table_accn" => "CN",
    "table_accn2" => "Callsign",
    "table_notrack" => "Tracking",
    "table_noident" => "Ident.",
    "table_update" => "Update",
    "table_delete" => "Verwijder",
    "table_new" => "Toevoegen",
    "disconnect" => "Uitloggen",
    //new
    "home" => "Home",
    "login" => "Inloggen",
    "create_account" => "Maak een account aan",
    "my_devices" => "Mijn devices",
    "add_device" => "Device toevoegen",
    "change_password" => "Change password",
    "full_participation" => "Volledig deelnemen",
    "full_participation_description" => "<ul>
        <li>Tracking applicaties die de OGN DDB gebruiken, geven de positie weer met vliegtuig identificatie</li>
        <li>De vliegtuiggevens zijn te vinden in de openbare Devices Database</li></ul>",
    "anonymous_participation" => "Annoniem deelnemen",
    "anonymous_participation_description" => "<ul>
        <li>Tracking applicaties die de OGN DDB gebruiken, geven de positie weer met een <em>anonieme</em> marker</li>
        <li>De registratie en callsign worden <em>niet</em> openbaar gemaakt</li></ul>",
    "no_participation" => "Niet deelnemen",
    "no_participation_description" => "<ul>
        <li>Tracking applicaties die de OGN DDB gebruiken, geven de positie <em>niet</em> weer</li>
        <li>De registratie en callsign worden <em>niet</em> openbaar gemaakt</li>
        <li>Hulp bij SAR-activiteiten kan mogelijk niet plaatsvinden</li>
        <li>Deze device draagt niet bij aan traffic-awareness m.b.v. OGN</li></ul>",
    "welcome_text" => "<p>
        Hier kunt u met FLARM of OGN-tracker uitgeruiste (zweef)vliegtuigen registreren in de OGN Devices Database.
        Registreren heeft verschillende voordelen:
        <ul>
            <li>U kunt bepalen hoe uw (zweef)vliegtuig wordt weergegeven op
                <a href='https://live.glidernet.org'>live.glidernet.org</a> en andere tracking websites</li>
            <li>Bij Search and Rescue operaties is uw vliegtuig sneller vindbaar</li>
            <li>U draagt bij aan traffic-awareness bij medepiloten en luchtverkeersleiding</li>
        </ul>
        De data wordt gepubliceerd onder de <a href='https://opendatacommons.org/licenses/by/summary/'>ODC-BY</a> licentie.
        </p>",
);
