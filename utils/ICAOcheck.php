<?php
function checkreg($reg, $ICAOID)
{	// check if the registration matches the ICAO ID
   

	$ranges [] = array( "S"=> 0x008011, "E"=> 0x008fff, "R"=> "ZS-" );
        $ranges [] = array( "S"=> 0x390000, "E"=> 0x398000, "R"=> "F-G" );
        $ranges [] = array( "S"=> 0x398000, "E"=> 0x38FFFF, "R"=> "F-H" );
        $ranges [] = array( "S"=> 0x3C4421, "E"=> 0x3C8421, "R"=> "D-A" );
        $ranges [] = array( "S"=> 0x3C0001, "E"=> 0x3C8421, "R"=> "D-A" );
        $ranges [] = array( "S"=> 0x3C8421, "E"=> 0x3CC000, "R"=> "D-B" );
        $ranges [] = array( "S"=> 0x3C2001, "E"=> 0x3CC000, "R"=> "D-B" );
        $ranges [] = array( "S"=> 0x3CC000, "E"=> 0x3D04A8, "R"=> "D-C" );
        $ranges [] = array( "S"=> 0x3D04A8, "E"=> 0x3D4950, "R"=> "D-E" );
        $ranges [] = array( "S"=> 0x3D4950, "E"=> 0x3D8DF8, "R"=> "D-F" );
        $ranges [] = array( "S"=> 0x3D8DF8, "E"=> 0x3DD2A0, "R"=> "D-G" );
        $ranges [] = array( "S"=> 0x3DD2A0, "E"=> 0x3E1748, "R"=> "D-H" );
        $ranges [] = array( "S"=> 0x3E1748, "E"=> 0x3EFFFF, "R"=> "D-I" );
        $ranges [] = array( "S"=> 0x448421, "E"=> 0x44FFFF, "R"=> "OO-" );
        $ranges [] = array( "S"=> 0x458421, "E"=> 0x45FFFF, "R"=> "OY-" );
        $ranges [] = array( "S"=> 0x460000, "E"=> 0x468421, "R"=> "OH-" );
        $ranges [] = array( "S"=> 0x468421, "E"=> 0x46FFFF, "R"=> "SX-" );
        $ranges [] = array( "S"=> 0x490421, "E"=> 0x49FFFF, "R"=> "CS-" );
        $ranges [] = array( "S"=> 0x4A0421, "E"=> 0x4AFFFF, "R"=> "YR-" );
        $ranges [] = array( "S"=> 0x4B8421, "E"=> 0x4BFFFF, "R"=> "TC-" );
        $ranges [] = array( "S"=> 0x740421, "E"=> 0x74FFFF, "R"=> "JY-" );
        $ranges [] = array( "S"=> 0x760421, "E"=> 0x768421, "R"=> "AP-" );
        $ranges [] = array( "S"=> 0x768421, "E"=> 0x76FFFF, "R"=> "9V-" );
        $ranges [] = array( "S"=> 0x778421, "E"=> 0x77FFFF, "R"=> "YK-" );
        $ranges [] = array( "S"=> 0xC00001, "E"=> 0xC044A9, "R"=> "C-F" );
        $ranges [] = array( "S"=> 0xC044A9, "E"=> 0xC0FFFF, "R"=> "C-G" );
        $ranges [] = array( "S"=> 0xE01041, "E"=> 0xE0FFFF, "R"=> "LV-" );
        $ranges [] = array( "S"=> 0x3E0000, "E"=> 0x3EFFFF, "R"=> "D-" );
	// var_dump($ranges);
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
echo checkreg("D-2520", "123456");
echo "\n\n\n";
echo checkreg("HA-1501", "474A84");
echo "\n\n\n";
echo checkreg("D-IAPD", "3E18D1");
echo "\n\n\n";
?>
