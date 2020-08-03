<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10-11-15
 * Time: 16:19.
 */

$languages['english'] = array (
    "input_welcome" => "Welcome to the OGN Devices database",
    "input_login" => "Email address:",
    "input_password" => "Password:",
    "input_newpassword" => "New password:",
    "input_confpassword" => "Confirm password:",
    "input_submit" => "Submit",
    "input_cancel" => "Cancel",
    "input_nouser" => "If you're not already a member, ",
    "input_forgot" => "Forgot your password ???, ",
    "input_forgotproc" => "Reset password  ",
    "input_create" => "Create an account",
    "input_createdev" => "Register a device",
    "input_createacft" => "Register a tracked object",
    "input_owner" => "I certify to be the owner of this device",
    "input_verif" => "To be sure you're a human being, please resolve this:",
    "input_nbdevices" => "registered devices",
    "input_nbobjects" => "registered tracked objects",
    "input_notrack" => "I don't want this device to be tracked",
    "input_noident" => "I don't want this device to be identified",
    "input_active" => "I want this device to be active",
    "error_login" => "Address or password incorrect",
    "error_pwdontmatch" => "Passwords don't match",
    "error_pwtooshort" => "Password is too short (4 char mini)",
    "error_emailformat" => "Invalid email address format",
    "error_userexists" => "This user already exists",
    "error_userdoesnotexists" => "This user does not exists",
    "error_devicedoesnotexists" => "This device does not exists",
    "error_insert_tmpusers" => "Database error, please retry later",
    "error_validation" => "Error validating your address, already validated?",
    "error_nodevice" => "No device recorded for this account",
    "error_devid" => "Device ID format incorrect",
    "error_devidlen" => "Device ID lenght incorrect",
    "error_airid" => "Tracked object ID format incorrect",
    "error_noairid" => "Missing tracked object ID",
    "error_devtype" => "Device Type format incorrect",
    "error_flyobj" => "Tracked Object is incorrect",
    "error_actype" => "Aircraft type format incorrect",
    "error_acreg" => "Aircraft registration format incorrect",
    "error_accn" => "Aircraft Competition Number format incorrect",
    "error_phone" => "Phone Number format incorrect",
    "error_club" => "Club name format incorrect",
    "error_country" => "Country name format incorrect",
    "error_devexists" => "This device is already registered",
    "error_airexists" => "This tracked object is already registered",
    "error_owner" => "You must be the owner of the device",
    "error_verif" => "Incorrect equation :-(",
    "error_insert_device" => "Database error inserting device ",
    "error_insert_flyobj" => "Database error inserting tracked object ",
    "device_deleted"  => "Device deleted",
    "device_updated"  => "Device updated",
    "device_inserted" => "Device inserted",
    "flyobj_deleted" => "Tracked object  deleted",
    "flyobj_updated" => "Tracked object  updated",
    "flyobj_inserted" => "Tracked object  inserted",
    "email_subject" => "[OGN] Account Validation",
    "email_claimsubject" => "[OGN] Request Devices Deregistration",
    "email_content" => "Hello,<BR>Thank you for contributing to OGN.<BR>To confirm this email address you have to click on the link below:<BR>",
    "email_claim"   => "Hello,<BR>Thank you for contributing to OGN.<BR><BR>",
    "email_sent"    => "An email has just been sent to you, you'll find instructions on how to validate your account.",
    "email_claimsent" => "An email has just been sent to the person who registered that device asking to de register.",
    "email_not_sent" => "Error sending email, check your address is correct.",
    "email_validated" => "Your account is validated, now you can login.",
    "table_devid" => "Device ID",
    "table_devtype" => "Device type",
    "table_flyobj" => "Tracked Object",
    "table_actype" => "Aircraft type",
    "table_acreg" => "Link to Regist.",
    "table_acreg2" => "Registration",
    "table_accn" => "Link to CN",
    "table_accn2" => "Competition Number",
    "table_phone" => "Telephone",
    "table_club" => "Club name",
    "table_country" => "Country",
    "table_notrack" => "Tracking",
    "table_noident" => "Ident.",
    "table_active" => "Active",
    "table_update" => "Update",
    "table_delete" => "Delete",
    "table_new" => "Add new",
    "disconnect" => "Disconnect",
    //new
    "home" => "Home",
    "login" => "Log in",
    "create_account" => "Create an account",
    "my_devices" => "My devices",
    "add_device" => "Add device",
    "add_aircraft" => "Add Tracked Object",
    "change_password" => "Change password",
    "claim_ownership" => "Claim Ownership",
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
