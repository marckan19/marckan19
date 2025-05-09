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
	
	//header("Content-Encoding: ISO-8859-2");
	header("Content-Type: application/xls; charset=ISO-8859-2");    
	header("Content-Disposition: attachment; filename=punkty_excel.xls");  
	header("Pragma: no-cache"); 
	header("Expires: 0");
	
	header( 'Content-Type: text/plain; charset=ISO-8859-2; name=' . urlencode( $t_filename ) );
	
	//header('Content-Encoding: UTF-8');
	//header('Content-type: text/csv; charset=UTF-8');
	//header('Content-Disposition: attachment; filename=Customers_Export.csv');
	//echo "\xEF\xBB\xBF"; // UTF-8 BOM

	$sep = "\t"; //tabbed character
	$date = date('Y-m-d');
	
	if ($ids != '') {
		$query = "SELECT * FROM panel_tabela_punkty WHERE id IN (".$ids.") ORDER BY realizacja_od;";
	} else {
		$query = "SELECT * FROM panel_tabela_punkty WHERE 1=0;";
	}
	
	$result = mysql_query($query) or die (mysql_error());
	
	$schema_insert = "";
		$schema_insert = 
			iconv("UTF-8", "ISO-8859-2", "BAUVORHABEN + ADRESSE / BUDOWA + ADRES (MICHAEL)") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "AUSFÜHRUNGSTERMIN VON/ TERMIN REALIZACJI OD (MACIEK B.)") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "AUSFÜHRUNGSTERMIN BIS/ TERMIN REALIZACJI DO (MACIEK B.)") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "PERSONAL / PERSONEL (MACIEK B.)") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "ERSTELLUNGSDATUM DER ABNAHMEDOKUMENTATION SEITENS BRASSCO / DATA SPORZĄDZENIA DOKUMENTACJI PRZEZ BRASSCO (MICHAEL)") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "DATUM DER RÜCKSENDUNG DER ABNAHMEDOKUMENTATION UNTERSCHRIEBEN DURCH AUFTRAGGEBER / DATA ODESŁANEJ DOKUMENTACJI, PODPISANEJ PRZEZ ZLECENIODAWCĘ (MICHAEL)") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "AUFTRAGSWERT / KWOTA KONTRAKTU (MICHAEL)") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "DATUM DES EINGANGS DES AVIS / DATA PRZESŁANIA AWIZA PŁATNOŚCI") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "DATUM DES ZAHLUNGSEINGANGS / DATA ZAPŁATY") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "VERMERKE / UWAGI") . $sep . 
			iconv("UTF-8", "ISO-8859-2", "DATUM DER EINTRAGUNG / DATA WPISU") . $sep;
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
		

		$schema_insert = mb_convert_encoding($schema_insert, 'ISO-8859-2');

        print(trim($schema_insert));
        print "\n";
    while($row = mysql_fetch_array($result))
    {
        $schema_insert = "";
		$schema_insert = 
			iconv("UTF-8", "ISO-8859-2", trim($row['budowa_adres'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['realizacja_od'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['realizacja_do'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['personel'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['data_sporzadzenia_dokumentacji'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['data_odeslanej_dokumentacji'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['kwota_kontraktu'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['data_przeslania_awiza'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['data_zaplaty'])) . $sep . 
			iconv("UTF-8", "ISO-8859-2", trim($row['uwagi'])) . $sep . 
			$row['creation_date'] . $sep;
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        //print(trim($schema_insert));
        print($schema_insert);
        print "\n";
    }   
}