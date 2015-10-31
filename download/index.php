<?php
include '../sql.php';
$dbh = Database::connect();
$yesno = array(0=>"Y",1=>"N");
$devtype = array(1=>"I", 2=>"F", 3=>"O");

// if parameter t=1 also display aircraft type (1=gliders 2=plane 3=ultralight 4=helicopter 5=Drones 6=Others)
if (isset($_GET['t'])) $t=$_GET['t'];
else $t=0;

// if parameter j=1 display JSon format
if (isset($_GET['j'])) $j=$_GET['j'];
else $j=0;


$sql = 'SELECT * FROM devices LEFT JOIN aircrafts ON dev_actype = ac_id ORDER BY dev_id ASC';



switch($j) {
	case 1:		// JSon format
		// Allow from any origin
    header('Access-Control-Allow-Headers: Content-Type');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
		
		$cpt=0;
		echo "{\r\n\"devices\": [";
		foreach ($dbh->query($sql) as $row) {
			$dt=$devtype[$row['dev_type']];
			$did=$row['dev_id'];
			$am=$row['ac_type'];
			$reg=$row['dev_acreg'];
			$cn=$row['dev_accn'];
			$tr=$yesno[$row['dev_notrack']];
			$id=$yesno[$row['dev_noident']];
			$at=$row['ac_cat'];
			
			if ($tr=="N" OR $id=="N") $am=$reg=$cn=''; 
				
			if (++$cpt!=1) echo ",";
			echo "\r\n	{\r\n	\"device_type\": \"$dt\",\r\n	\"device_id\": \"$did\",\r\n	\"aircraft_model\": \"$am\",\r\n	\"registration\": \"$reg\",\r\n	\"cn\": \"$cn\",\r\n	\"tracked\": \"$tr\",\r\n	\"identified\": \"$id\"";

			if ($t==1) echo ",\r\n	\"aircraft_type\": \"$at\"";
			
			echo "\r\n	}";
		}
		echo "\r\n	]\r\n}";

		break;
	default:		// txt cvs format
		header('Content-Type: text/plain; charset="UTF-8"');
		
		echo "#DEVICE_TYPE,DEVICE_ID,AIRCRAFT_MODEL,REGISTRATION,CN,TRACKED,IDENTIFIED";
		if ($t==1) echo ",AIRCRAFT_TYPE";
		echo "\r\n";

		foreach ($dbh->query($sql) as $row) {
			if ($row['dev_notrack']==1 OR $row['dev_noident']==1 ) echo "'".$devtype[$row['dev_type']]."','{$row['dev_id']}','','','','".$yesno[$row['dev_notrack']]."','".$yesno[$row['dev_noident']]."'";
			else echo "'".$devtype[$row['dev_type']]."','{$row['dev_id']}','{$row['ac_type']}','{$row['dev_acreg']}','{$row['dev_accn']}','Y','Y'";
			if ($t==1) echo ",'{$row['ac_cat']}'";
			echo "\r\n";
		}

	
	
}


Database::disconnect();
