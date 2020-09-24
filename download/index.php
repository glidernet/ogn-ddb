<?php
//
// this program handles the API download for the OGNDDB
// Website: http://ddb.glidernet.org/download
//

include '../sql.php';
$dbh = Database::connect();
//$devtype = array(1 => 'I', 2 => 'F', 3 => 'O', 4 => 'N');
$idtypes = array('0' => 'NoDef', '1' => 'Internal', '2' => 'ICAO');
$sql0 = 'SELECT dvt_id, dvt_name, dvt_code, dvt_3ltcode, dvt_idlen FROM devtypes;';
$stmt= $dbh->query($sql0);
$devtype = array();		// single dimendional array with just the devtypes and names
$devtyp  = array();		// two dimensional array with the devtypes table
$devt = array(array());		// two dimensional array with the results from the query for the JSON file
$devt=$stmt->fetchAll();
foreach ($devt as $dt){
   $devtype[$dt['dvt_id']]=$dt['dvt_code'];
   $i=$dt['dvt_id'];
   $devtyp [$i]['dvt_id']      = $dt['dvt_id'];
   $devtyp [$i]['dvt_name']    = $dt['dvt_name'];
   $devtyp [$i]['dvt_code']    = $dt['dvt_code'];
   $devtyp [$i]['dvt_3ltcode'] = $dt['dvt_3ltcode'];
   $devtyp [$i]['dvt_idlen']   = $dt['dvt_idlen'];
}


$t = !empty($_GET['t']);
$actype = $t ? ', ac_cat AS aircraft_type ' : '';

$params = array();
$filter = array();

if (!empty($_GET['device_id'])) {
    $regs = explode(',', $_GET['device_id']);
    $qm = implode(',', array_fill(0, count($regs), '?'));
    $filter[] = 'dev_id IN ('.$qm.')';
    $params = array_merge($params, $regs);
}
if (!empty($_GET['from_id'])) {
    $fromid = $_GET['from_id'];
    $filter[] = 'dev_id >="'.$fromid.'"';
}
if (!empty($_GET['till_id'])) {
    $tillid = $_GET['till_id'];
    if (!empty($_GET['from_id'])) {
      $filter[count($filter)-1] .= ' AND dev_id <="'.$tillid.'"';
    } else {
      $filter[] = 'dev_id <="'.$tillid.'"';
    }
}
if (!empty($_GET['registration'])) {
    $regs = explode(',', $_GET['registration']);
    $qm = implode(',', array_fill(0, count($regs), '?'));
    $filter[] = ' ( air_acreg IN ('.$qm.') AND dev_notrack = 0 AND dev_noident = 0 ) ';
    $params = array_merge($params, $regs);
}
if (!empty($_GET['cn'])) {
    $regs = explode(',', $_GET['cn']);
    $qm = implode(',', array_fill(0, count($regs), '?'));
    $filter[] = ' ( air_accn IN ('.$qm.') AND dev_notrack = 0 AND dev_noident = 0 ) ';
    $params = array_merge($params, $regs);
}
if (count($filter)) {
    $filterstring = ' AND '.implode(' OR ', $filter);
} else {
    $filterstring = '';
}

$sql1 = 'SELECT 
        dev_type AS device_type,
        dev_id AS device_id,
        IF(!dev_notrack AND !dev_noident,ac_type,"" ) AS aircraft_model,
        IF(!dev_notrack AND !dev_noident,air_acreg,"") AS registration,
        IF(!dev_notrack AND !dev_noident,air_accn,"") AS cn,
        IF(!dev_notrack,"Y","N") AS tracked, 
        IF(!dev_noident,"Y","N") AS identified,
        dev_idtype AS device_idtype,
        IF(!dev_active,"N","Y") AS device_active,
        IF(!air_active,"N","Y") AS aircraft_active
        '.$actype.'
        , dev_uniqueid AS uniqueid
        FROM devices, aircraftstypes, trackedobjects 
        WHERE air_actype = ac_id and dev_flyobj = air_id 
        '.$filterstring.'
        ORDER BY dev_id ASC;';
$stmt = $dbh->prepare($sql1);
$stmt->execute($params);

$output['devices'] = array();

$j=0;
if (!empty($_GET['j']))  {
    $j=$_GET['j'];
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dt   = $row['device_type'];
    $dtt  = $devtype[$row['device_type']];
    $row['device_type'] = $dtt;
    $row['device_idtype'] = $idtypes[$row['device_idtype']];
    if ($j>1)
    {
        if ($dtt == 'S' or $dtt == "P" or $dtt == "R" or $dtt == "L")
           {
           $idx=$row['uniqueid'];
           $row['device_aprsid'] =  $devtyp[$dt]['dvt_3ltcode'].sprintf("%06d",$idx);
           }
        else
           {
           if ($row['device_idtype'] == 'ICAO')
              {
              $row['device_aprsid'] =  "ICA".$row['device_id'];
              }
           else
              {
              $row['device_aprsid'] =  $devtyp[$dt]['dvt_3ltcode'].$row['device_id'];
              }
           }
    }
    $output['devices'][] = $row;
}


if ($j == 2){
   $sql0 = 'SELECT ac_id, ac_type, ac_cat  FROM aircraftstypes ORDER BY ac_id;';
   $stmt= $dbh->query($sql0);
   $airtyp=$stmt->fetchAll();
   $output['aircrafttypes']=$airtyp;
   $output['devtypes']=$devtyp;
   $output['idtypes']=$idtypes;
   $sql0 = 'SELECT cat_id, cat_name  FROM aircraftcat ORDER BY cat_id;';
   $stmt= $dbh->query($sql0);
   $aircat=$stmt->fetchAll();
   $output['aircraftcat']=$aircat;
}
if (!empty($_GET['j']) || !empty($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] == 'application/json') {
    						// the case of output JSON
    						// Allow from any origin
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    if ($j != 1)
    {
        $json = json_encode($output,JSON_PRETTY_PRINT); 
    }
    else
    {
        $json = json_encode($output); 
    }

    if ($json) 
	echo $json; 
    else 
	{
        header('HTTP/1.0 501 Not Implemented');
	echo '{"errormsg":"'.json_last_error_msg().'"}';
	exit;
	}
} else {
    						// the case of output CSV
    header('Content-Type: text/plain; charset="UTF-8"');
    echo '#DEVICE_TYPE,DEVICE_ID,AIRCRAFT_MODEL,REGISTRATION,CN,TRACKED,IDENTIFIED,IDTYPE,DEVACTIVE,ACFTACTIVE';
    if ($t) {
        echo ',AIRCRAFT_TYPE';
    }
    echo "\r\n";
    foreach ($output['devices'] as $row) {
        echo "'";
        echo implode("','", $row);
        echo "'\r\n";
    }
}
