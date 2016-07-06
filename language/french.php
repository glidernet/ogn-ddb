<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:21.
 */

$languages['french'] = array (
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
    "email_subject" => "[OGN] Validation du Compte",
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
    //new:
    "home" => "Accueil",
    "login" => "Connectez-vous",
    "create_account" => "créez un compte",
    "my_devices" => "Mon appareil",
    "add_device" => "Ajouter appareil",
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
        C'est ici qu'on enregistre les planeurs, remorqueurs ou tout autre aéronefs équipés de FLARM dans la base de données Open Glider Network.
        L'enregistrement à plusieurs avantages:
        <ul>
            <li>Vous pouvez gérer l'affichage de votre aéronefs sur
                <a href='http://live.glidernet.org'>live.glidernet.org</a> et sur les autres sites de localisation.</li>
            <li>En cas de sauvetage, votre aéronef est plus facile à localiser</li>
            <li>Vous participez à la sensibilisation du traffic aérien pour les pilotes et les contrôleurs</li>
        </ul>
        Les données sont disponibles gratuitement sous licence <a href='http://opendatacommons.org/licenses/by/summary/'>ODC-BY</a>.
        </p>",

);

