<?php
ob_start();
session_start();

require_once('mysql_connecting.php');
if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){

	$ids = '';
	foreach ($_POST as $key => $item) {
		if (substr($key, 0, 13) === 'export_excel_') {
			$ids .= substr($key, 13) . ',';
		}	
	}

	$ids = substr($ids, 0, -1);
	
	header("Content-Type: application/xls; charset=utf-8");    
	header("Content-Disposition: attachment; filename=zwyzki_excel.xls");  
	header("Pragma: no-cache"); 
	header("Expires: 0");

	$sep = "\t"; //tabbed character
	$date = date('Y-m-d');
	$query = "SELECT * FROM panel_tabela_zwyzki WHERE id IN (".$ids.") ORDER BY id;";
	$result = mysql_query($query) or die (mysql_error());
	
	$schema_insert = "";

		$schema_insert = "LP" . $sep . "BUDOWA" . $sep . "DATA WYNAJMU" . $sep . "DATA WYNAJMU DO" . $sep;
        
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
		
		$lp = 1;
    while($row = mysql_fetch_array($result))
    {
        $schema_insert = "";

		$schema_insert = $lp . $sep . $row['budowa'] . $sep . $row['data_wynajmu_od'] . $sep . $row['data_wynajmu_do'] . $sep;
        
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
		
		$lp++;
    }   
}
