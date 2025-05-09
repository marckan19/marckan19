<?php
ob_start();
session_start();
//require_once('header.php');
require_once('mysql_connecting.php');
if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){
	
	header("Content-Type: application/xls; charset=utf-8");    
	header("Content-Disposition: attachment; filename=wykres.xls");  
	header("Pragma: no-cache"); 
	header("Expires: 0");

	$sep = "\t"; //tabbed character
	$date = date('Y-m-d');
	$query = "SELECT * FROM panel_tabela_budowy WHERE realizacja_od >= '".$date."' OR realizacja_do >= '".$date."' ORDER BY realizacja_od;";
	//$query = "SELECT * FROM panel_tabela_budowy WHERE realizacja_od > '2017-12-31' AND realizacja_od < '2019-01-01' ORDER BY realizacja_od;";
	$result = mysql_query($query) or die (mysql_error());

	
	$schema_insert = "";
		//echo $row['zleceniodawca'];
		$schema_insert = "Budowa" . $sep . "Od" . $sep . "Do" . $sep . "Kraj" . $sep;
        
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    while($row = mysql_fetch_array($result))
    {
        $schema_insert = "";
		//echo $row['zleceniodawca'];
		$schema_insert = $row['zleceniodawca'] . $sep . $row['realizacja_od'] . $sep . $row['realizacja_do'] . $sep . $row['kraj'] . $sep;
        
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }   
}
//require_once('footer.php');