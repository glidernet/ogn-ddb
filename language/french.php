<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:21.
 */

$languages['french'] = array (
    "input_welcome" => "Bienvenue sur la database Emetteurs OGN",
    "input_login" => "Utilisateur (adresse courriel) :",
    "input_password" => "Mot de passe :",
    "input_newpassword" => "Nouveau mot de passe :",
    "input_confpassword" => "Confirmez le mot de passe :",
    "input_submit" => "Valider",
    "input_cancel" => "Annuler",
    "input_nouser" => "Si vous n'est pas encore membre, ",
    "input_forgot" => "Mot de passe oublié ? ",
    "input_forgotproc" => "Réinitialiser le mot de passe",
    "input_help" => "Besoin d'aide pour? ",
    "input_create" => "créez un compte",
    "input_createdev" => "Enregistrer un appareil",
    "input_owner" => "Je certifie être le possesseur de cet appareil",
    "input_verif" => "Pour être sûr que vous soyez un humain, merci de résoudre ceci :",
    "input_nbdevices" => "émetteurs enregistrés",
    "input_notrack" => "Je ne veux pas que cet émetteur soit pisté",
    "input_noident" => "Je ne veux pas que cet émetteur soit identifié",
    "error_login" => "Adresse ou mot de passe incorrect",
    "error_pwdontmatch" => "Les mots de passe ne correspondent pas",
    "error_pwtooshort" => "Mot de passe trop court (4 car mini)",
    "error_emailformat" => "Format d'adresse courriel invalide",
    "error_userexists" => "Utilisateur déjà existant",
    "error_userdoesnotexists" => "This user does not exists", 
    "error_insert_tmpusers" => "Erreur Base de données, ré-essayez plus tard",
    "error_validation" => "Erreur de validation, déjà validé ?",
    "error_nodevice" => "Il n'y a pas d'appareil enregistré pour ce compte",
    "error_devid" => "Format d'adresse d'appareil incorrect",
    "error_devtype" => "Format du type d'adresse d'appareil incorrect",
    "error_actype" => "Format type de machine incorrect",
    "error_acreg" => "Format immatriculation incorrect",
    "error_accn" => "Format N° concours incorrect",
    "error_devexists" => "Cet appareil est déjà enregistré",
    "error_owner" => "Vous devez être le possesseur de l'appareil",
    "error_device_limit" => "Vous avez atteint le nombre maximum d'appareils autorisés pour votre compte",
    "error_insert_device" => "Erreur base de données lors de l'enregistrement de l'appareil, ré-essayez plus tard",
    "api_token_section" => "Accès API",
    "api_token_label" => "Votre token API :",
    "api_token_new" => "Votre nouveau token (copiez-le maintenant, il ne sera plus affiché) :",
    "api_token_regen" => "Générer un nouveau token",
    "api_token_has_token" => "Un token est défini. Générez-en un nouveau pour le remplacer.",
    "api_token_hint_notice" => "Ceci est uniquement un aperçu partiel — pour voir le token complet, générez-en un nouveau.",
    "api_token_none" => "Aucun token API pour l'instant. Cliquez sur le bouton ci-dessous pour en générer un.",
    "api_token_doc_notice" => "Pour plus d'informations sur l'API, consultez :",
    "error_verif" => "L'équation est fausse :-(",
    "device_deleted" => "Appareil supprimé",
    "device_updated" => "Appareil mis à jour",
    "device_inserted" => "Appareil enregistré",
    "email_subject" => "[OGN] Validation du Compte",
    "email_content" => "Bonjour,<BR>Merci de contribuer à OGN.<BR>Pour confirmer cette adresse de courriel, cliquez sur le lien ci-dessous :<BR>",
    "email_sent" => "Un courriel vous a été envoyé, vous trouverez les instruction pour valider votre compte.",
    "email_not_sent" => "Erreur d'envoi de courriel, vérifiez que votre adresse est correcte.",
    "email_validated" => "Votre compte est validé, vous pouvez vous connecter.",
    "table_devid" => "Adresse d'appareil",
    "table_devtype" => "Type d'adresse d'appareil",
    "table_actype" => "Type aéronef",
    "table_acreg" => "Immat.",
    "table_acreg2" => "Immatriculation",
    "table_accn" => "N°",
    "table_accn2" => "N° de concours",
    "table_notrack" => "Tracking",
    "table_noident" => "Ident.",
    "table_expiration" => "Expiration",
    "table_update" => "Modifier",
    "table_delete" => "Supprimer",
    "table_new" => "Ajouter",
    "table_tt_expiration" => "L'enregistrement de cet appareil va expirer :",
    "expiry_disclaimer" => "Les appareils dont l'enregistrement a expiré peuvent être enregistrés librement par d'autres utilisateurs, renouveler pour une année supplémentaire un appareil expiré, modifier et valider votre appareil, examiner les données, vérifier la certification de propriété de l'appareil et appuyer sur Soumettre.",
    "expiry_warning" => "Avertissement : L'enregistrement de certains de vos appareils est EXPIRÉ, veuillez les vérifier",
    "disconnect" => "Déconnexion",
    //new:
    "home" => "Accueil",
    "login" => "Connectez-vous",
    "create_account" => "créez un compte",
    "my_devices" => "Mon appareil",
    "add_device" => "Ajouter appareil",
    "change_password" => "Modifier mot de passe",
    "full_participation" => "Participation complète", //no checkboxes checked
    "full_participation_description" => "<ul>
        <li>Les applications de pistage utilisant OGN DDB afficheront la position avec l'identification de l'aéronef</li>
        <li>L'immatriculation de l'aéronef et son n° de concours sont publié dans la base de donnée des apareils OGN (DDB)</li></ul>",
    "anonymous_participation" => "Participation anonyme", //only noident checkbox checked
    "anonymous_participation_description" => "<ul>
        <li>Les applications de pistage utilisant OGN DDB afficheront la position avec un marqueur <em>anonyme</em></li>
        <li>L'immatriculation de l'aéronef et son n° de concours <em>ne</em> seront <em>pas</em> publiés</li></ul>",
    "no_participation" => "Pas de participation", //notrack or both checkboxes checked
    "no_participation_description" => "<ul>
        <li>Les applications de pistage utilisant OGN DDB <em>n'</em>afficheront <em>pas</em> votre position</li>
        <li>L'immatriculation de l'aéronef et son n° de concours <em>ne</em> sont <em>pas</em> publiés</li>
        <li>Les fonctions de <abbr title=\"recherche et sauvetage (Search And Rescue)\">SAR</abbr> <em>ne sont éventuellement pas</em> disponible pour cet appareil</li>
        <li>Cet appareil <em>ne</em> contribura <em>éventuellement pas</em>à montrer la réalité du traffic via OGN</li></ul>",
    "welcome_text" => "<p>
        C'est ici qu'on enregistre les planeurs, remorqueurs ou tout autre aéronef équipé de FLARM dans la base de données Open Glider Network.
        L'enregistrement a plusieurs avantages :
        <ul>
            <li>Vous pouvez gérer l'affichage de votre aéronef sur
                <a href='http://live.glidernet.org'>live.glidernet.org</a> et sur les autres sites de localisation.</li>
            <li>En cas de sauvetage, votre aéronef est plus facile à localiser</li>
            <li>Vous participez à la sensibilisation du traffic aérien pour les pilotes et les contrôleurs</li>
        </ul>
        Les données sont disponibles gratuitement sous licence <a href='http://opendatacommons.org/licenses/by/summary/'>ODC-BY</a>.
        </p>",

);

