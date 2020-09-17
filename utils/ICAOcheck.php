<?php
function checkreg($reg, $ICAOID)
{	// check if the registration matches the ICAO ID
        global $ranges;
        foreach  ($ranges as $r) {              		// check for the registration is in the ranges assigned by country
           //echo "R".$r["S"].":::".$r["E"].">>>".$r["R"]."\n";
           $l=strlen($r["R"]);

           $lr= substr($reg,0,$l);
           //echo $lr."\n";
           if ($lr == $r["R"]) {
              $idicao=intval($ICAOID, 16);
              if ($idicao > $r["S"] and $idicao < $r["E"]) 
                 {
                 //echo "OK\n";
                 return (1);	// return TRUE if OK
                 }
              else 
                 {
                 //echo "Not OK\n";
                 return (0);	// return FALSE if not in the range
 	         }          
           }
        }
        //echo "Unkown\n";
        return(2);	// return 2 if not found or UNKOWN 

}

exec ("python3 getICAOranges.py", $json);
//var_dump($json);
//echo $json[0];
global $ranges;
$ranges=json_decode( $json[0], $assoc = True, $depth = 512, $options = 0 );
//var_dump($ranges);
echo checkreg("D-2520", "123456");
echo "\n\n\n";
echo checkreg("HA-1501", "474A84");
echo "\n\n\n";
echo checkreg("D-IAPD", "3E18D1");
echo "\n\n\n";
?>
