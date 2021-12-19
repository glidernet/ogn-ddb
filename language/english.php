<?php 		
$languages['english'] = array ( 						// 9
    "input_welcome" => "Welcome to the OGN Devices database", 			// 10
    "input_login" => "Email address:", 						// 11
    "input_password" => "Password:", 						// 12
    "input_newpassword" => "New password:", 					// 13
    "input_confpassword" => "Confirm password:", 				// 14
    "input_submit" => "Submit", 						// 15
    "input_cancel" => "Cancel", 						// 16
    "input_nouser" => "If you're not already a member, ", 			// 17
    "input_forgot" => "Forgot your password ???, ", 				// 18
    "input_forgotproc" => "Reset password  ", 					// 19
    "input_create" => "Create an account", 					// 20
    "input_createdevair" => "Register a device and aircraft", 			// 21
    "input_createdev" => "Register a device, to be linked with an aircaft",	// 22
    "input_createacft" => "Register an aircraft to have multiples devices",	// 23
    "input_owner" => "I certify to be the owner of this device", 		// 24
    "input_ownerflyobj" => "I certify to be the owner of this aircraft", 	// 25
    "input_verif" => "To be sure you're a human being, please resolve this:", 	// 26
    "input_nbdevices" => "registered aircraft", 				// 27
    "input_nbobjects" => "registered aircraft", 				// 28
    "input_notrack" => "I don't want this device to be tracked", 		// 29
    "input_noident" => "I don't want this device to be identified", 		// 30
    "input_active" => "I want this device to be active", 			// 31
    "input_activeobj" => "I want this aircraft to be active", 			// 32
    "error_login" => "Address or password incorrect", 				// 33
    "error_pwdontmatch" => "Passwords don't match", 				// 34
    "error_pwtooshort" => "Password is too short (4 char mini)", 		// 35
    "error_emailformat" => "Invalid email address format", 			// 36
    "error_emailbadisp" => "This ISP do not accept OGN emails", 		// 37
    "error_userexists" => "This user already exists", 				// 38
    "error_userdoesnotexists" => "This user does not exists", 			// 39
    "error_devicedoesnotexists" => "This device does not exists", 		// 40
    "error_insert_tmpusers" => "Database error, please retry later", 		// 41
    "error_validation" => "Error validating your address, already validated?",	// 42
    "error_nodevice" => "No device recorded for this account", 			// 43
    "error_notrackedobject" => "No aircrafts recorded for this account", 	// 44
    "error_devid" => "Device ID format incorrect", 				// 45
    "error_idtype" => "Device ID type incorrect", 				// 46
    "error_devidlen" => "Device ID lenght incorrect", 				// 47
    "error_invalid_ICAO_ID" => "Device ICAO ID do not match registration", 	// 48
    "error_airid" => "Aircraft ID format incorrect", 				// 49
    "error_active" => "Error active", 						// 50
    "error_noairid" => "Missing aircraft ID", 					// 51
    "error_devtype" => "Device Type format incorrect", 				// 52
    "error_flyobj" => "Aircraft is incorrect", 					// 53
    "error_actype" => "Aircraft type format incorrect", 			// 54
    "error_acreg" => "Aircraft registration format incorrect", 			// 55
    "error_accn" => "Aircraft Competition Number format incorrect", 		// 56
    "error_phone" => "Phone Number format incorrect", 				// 57
    "error_club" => "Club name format incorrect", 				// 58
    "error_country" => "Country name format incorrect", 			// 59
    "error_devexists" => "This device is already registered", 			// 60
    "error_airexists" => "This aircraft is already registered", 		// 61
    "error_owner" => "You must be the owner of the device", 			// 62
    "error_verif" => "Incorrect equation :-(", 					// 63
    "error_insert_device" => "Database error inserting device ", 		// 64
    "error_insert_flyobj" => "Database error inserting aircraft ", 		// 65
    "device_deleted"  => "Device deleted", 					// 66
    "device_updated"  => "Device updated", 					// 67
    "device_inserted" => "Device inserted", 					// 68
    "flyobj_deleted" => "Aircraft  deleted", 					// 69
    "flyobj_updated" => "Aircraft  updated", 					// 70
    "flyobj_inserted" => "Aircraft  inserted", 					// 71
    "email_subject" => "[OGN] Account Validation", 				// 72
    "email_claimsubject" => "[OGN] Request Devices Deregistration", 		// 73
    "email_content" => "Hello,<BR>Thank you for contributing to OGN.<BR>To confirm this email address you have to click on the link below:<BR>",// 74
    "email_claim"   => "Hello,<BR>Thank you for contributing to OGN.<BR><BR>", 	// 75
    "email_sent"    => "An email has just been sent to you, you'll find instructions on how to validate your account.", 			// 76
    "email_claimsent" => "An email has just been sent to the person who registered that device asking to de register.", 			// 77
    "email_not_sent" => "Error sending email, check your address is correct.", 	// 78
    "email_validated" => "Your account is validated, now you can login.", 	// 79
    "table_devid" => "Device ID", 						// 80
    "table_devtype" => "Device type", 						// 81
    "table_idtype" => "ID type", 						// 82
    "table_flyobj" => "Aircraft ID", 						// 83
    "table_flyobjzero" => "Aircraft ID (Zero for new aircraft)", 		// 84
    "table_actype" => "Aircraft type", 						// 85
    "table_acreg" => "Link to Regist.", 					// 86
    "table_acreg2" => "Registration", 						// 87
    "table_accn" => "Link to CN", 						// 88
    "table_accn2" => "Competition Number", 					// 89
    "table_phone" => "Telephone (Optional)", 					// 90
    "table_club" => "Club name (Optional)", 					// 91
    "table_country" => "Country (Optional)", 					// 92
    "table_notrack" => "Tracking", 						// 93
    "table_noident" => "Ident.", 						// 94
    "table_active" => "Active", 						// 95
    "table_update" => "Update", 						// 96
    "table_delete" => "Delete", 						// 97
    "table_new" => "Add new", 							// 98
    "disconnect" => "Disconnect", 						// 99
    //new 									// 100
    "home" => "Home", 								// 101
    "login" => "Log in", 							// 102
    "create_account" => "Create an account", 					// 103
    "my_devices" => "My devices and aircrafts", 				// 104
    "add_device" => "Add device", 						// 105
    "add_aircraft" => "Add aircraft ", 						// 106
    "add_devair" => "Add device and aircraft", 					// 107
    "change_password" => "Change password", 					// 108
    "claim_ownership" => "Claim Ownership", 					// 109
    "full_participation" => "Full participation", 				//no checkboxes checked 			// 110
    "full_participation_description" => "<ul> 
        <li>Tracking applications that use the OGN DDB will mark the position with aircraft identification</li> 
        <li>Aircraft registration and CN are published in the OGN Devices Database</li> 
        <li>You need to create first a <b>Aircraft</b> and later on link it with the <b>Tracked Device</b> </li></ul>", 	// 114
    "anonymous_participation" => "Anonymous participation", 			//only noident checkbox checked 		// 115
    "anonymous_participation_description" => "<ul> 			
        <li>Tracking applications that use the OGN DDB will mark the position with an <em>anonymous</em> marker</li> 
        <li>Aircraft registration and CN are <em>not</em> made public</li></ul>", 						// 118
    "no_participation" => "No participation", 					//notrack or both checkboxes checked 		// 119
    "no_participation_description" => "<ul> 
        <li>Tracking applications that use the OGN DDB will <em>not</em> mark your position</li>
        <li>Aircraft registration and CN are <em>not</em> made public.</li>
        <li>SAR-functions <em>may not</em> be available for this device</li>
        <li>This device <em>may not</em> contribute to traffic awareness through OGN</li></ul>",	 			// 124
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
        </p>", 															// 135
 																// 136
); 																// 137
