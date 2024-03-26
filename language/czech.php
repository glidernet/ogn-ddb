<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:19.
 */

$languages['czech'] = array (
    "input_welcome" => "Vítejte v databázi zařízení sítě OGN",
    "input_login" => "Email:",
    "input_password" => "Heslo:",
    "input_newpassword" => "Nové heslo:",
    "input_confpassword" => "Potvrďte heslo:",
    "input_submit" => "Odeslat",
    "input_cancel" => "Zrušit",
    "input_nouser" => "Nemáte ještě svůj účet v databázi? - ",
    "input_forgot" => "Zapomněli jste heslo ??? - ",
    "input_forgotproc" => "Obnovit heslo  ",
    "input_help" => "Potřebovat pomoc? ",
    "input_create" => "Vytvořit účet",
    "input_createdev" => "Registrace zařízení",
    "input_owner" => "Potvrzuji, že jsem vlastníkem tohoto zařízení",
    "input_verif" => "Abychom se ujistili že jste člověk, vypočítejte prosím tento příklad:",
    "input_nbdevices" => "registrovaných zařízení",
    "input_notrack" => "Nepřeji si, aby toto zařízení bylo sledováno (tracking)",
    "input_noident" => "Nepřeji si, aby toto zařízení bylo identifikováno",
    "error_login" => "Neplatné heslo nebo adresa",
    "error_pwdontmatch" => "Hesla si neodpovídají",
    "error_pwtooshort" => "Heslo je příliš krátké (min 4 znaky)",
    "error_emailformat" => "Neplatný formát adresy",
    "error_userexists" => "Tento uživatel již existuje",
    "error_userdoesnotexists" => "This user does not exists",
    "error_insert_tmpusers" => "Chyba databáze, opakujte prosím později",
    "error_validation" => "Chyba validace vaší adresy, byla již validována?",
    "error_nodevice" => "Tento účet nemá zatím registrované žádné zařízení",
    "error_devid" => "Neplatný formát ID zařízení",
    "error_devtype" => "Neplatný formát typu zařízení",
    "error_actype" => "Neplatný formát typu letadla",
    "error_acreg" => "Neplatný formát registrace letadla",
    "error_accn" => "Neplatný formát závodního znaku letadla",
    "error_devexists" => "Toto zařízení je již registrováno",
    "error_owner" => "Musíte být vlastníkem zařízení",
    "error_verif" => "Chybný výsledek :-(",
    "device_deleted" => "Zařízení vymazáno",
    "device_updated" => "Zařízení aktualizováno",
    "device_inserted" => "Zařízení vloženo",
    "email_subject" => "[OGN] Validace účtu",
    "email_content" => "Ahoj,<BR>děkujeme za připojení k OGN.<BR>K potvrzení této vaší emailové adresy přejděte prosím na tento odkaz:<BR>",
    "email_sent" => "Na vaší adresu byl odeslán email s postupem ověření vašeho účtu.",
    "email_not_sent" => "Chyba při odesílání emailu, ověřte že zadaná adresa je správná.",
    "email_validated" => "Váš účet byl ověřen, nyní se můžete přihlásit.",
    "table_devid" => "ID zařízení",
    "table_devtype" => "Typ zařízení",
    "table_actype" => "Typ letadla",
    "table_acreg" => "Regist.",
    "table_acreg2" => "Registrace",
    "table_accn" => "CN",
    "table_accn2" => "Soutěžní znak",
    "table_notrack" => "Tracking",
    "table_noident" => "Ident.",
    "table_expiration" => "Vypršení",
    "table_update" => "Aktualizace",
    "table_delete" => "Výmaz",
    "table_new" => "Přidat nové",
    "table_tt_expiration" => "Platnost registrace tohoto zařízení vyprší:",
    "expiry_disclaimer" => "Zařízení s vypršenou registrací si mohou ostatní uživatelé zdarma zaregistrovat, obnovit zařízení s prošlou platností na další rok, upravit a ověřit své zařízení, zkontrolovat data, zkontrolovat certifikaci vlastnictví zařízení a stisknout Odeslat",
    "expiry_warning" => "Upozornění: Platnost registrace některých vašich zařízení vypršela, zkontrolujte je",
    "disconnect" => "Odhlášení",
    //new
    "home" => "Domů",
    "login" => "Přihlášení",
    "create_account" => "Vytvoření účtu",
    "my_devices" => "Moje zařízení",
    "add_device" => "Přidat zařízení",
    "change_password" => "Změna hesla",
    "full_participation" => "Plná účast", //no checkboxes checked
    "full_participation_description" => "<ul>
        <li>Mapové aplikace které využívají databázi OGN označí pozici identifikací letadla</li>
        <li>Registrace letadla a soutěžní znak (CN) jsou zveřejněné v databázi zařízení OGN</li></ul>",
    "anonymous_participation" => "Anonymní účast", //only noident checkbox checked
    "anonymous_participation_description" => "<ul>
        <li>Mapové aplikace které využívají databázi OGN označí pozici <em>anonymní</em> značkou</li>
        <li>Registrace letadla a soutěžní znak (CN) <em>nejsou</em> zvěřejněné v databázi zařízení OGN</li></ul>",
    "no_participation" => "Bez účasti", //notrack or both checkboxes checked
    "no_participation_description" => "<ul>
        <li>Mapové aplikace které využívají databázi OGN vaši pozici <em>vůbec neoznačí</em></li>
        <li>Registrace letadla a soutěžní znak (CN) <em>nejsou</em> zvěřejněné v databázi zařízení OGN</li>
        <li>Informace užitečné pro SAR <em>nemusí být</em> proto toto zařízení v případě potřeby k dispozici</li>
        <li>Toto zařízení <em>nebude</em> přispívat k přehledu letového provozu prostřednictvím OGN</li></ul>",
    "welcome_text" => "<p>
        Na tomto místě můžete registrovat váš kluzák, vlečnou nebo jiné letadlo vybavené zařízením FLARM/OGN
        do Open Glider Network (OGN). Registrace má řadu výhod:
        <ul>
            <li>Můžete ovlivnit jak bude vaše letadlo zobrazeno na
                <a href='http://live.glidernet.org'>live.glidernet.org</a> a v jiných sledovacích aplikacích a webech</li>
            <li>V případě nouze - SAR, zvýšíte šanci na rychlé nalezení vašeho letadla</li>
            <li>Přispějete k přehledu letového provozu mezi ostatními piloty a ATC</li>
        </ul>
        Data jsou volně k dispozici po licencí <a href='http://opendatacommons.org/licenses/by/summary/'>ODC-BY</a>.
        </p>",
);
