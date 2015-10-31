<?php
include '../sql.php';

function encodeString($string,$length) {
  $string=str_pad($string,$length);
  $string=substr($string,0,$length);
  $encoded = ""; 
  for ($n=0;$n<strlen($string);$n++){ 
     $encoded .= dechex(ord($string[$n])); 
  }
  return $encoded; 
}

$dbh = Database::connect();
$sql = 'SELECT * FROM devices LEFT JOIN aircrafts ON dev_actype = ac_id WHERE dev_noident = 0 AND dev_notrack = 0 ORDER BY dev_id ASC';
echo "002c38\n"; // Version
foreach ($dbh->query($sql) as $row) {
	  echo encodeString($row['dev_id'],6); // Id
	  echo encodeString("",21); // Owner
	  echo encodeString("",21); // Airport
	  echo encodeString($row['ac_type'],21); // Aircraft
	  echo encodeString($row['dev_acreg'],7); // Raignum
	  echo encodeString($row['dev_accn'],3); // CN
	  echo encodeString("",7)."\n"; // Freq
}
Database::disconnect();
?>
