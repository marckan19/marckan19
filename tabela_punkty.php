<?php
ob_start();
session_start();
require_once('header.php');

if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){
	require_once('menu.php');
			
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Festpunkte</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Punkty stałe</h2></td><td width="20px" />';			
			}
			require_once('legenda_punkty.php');
		echo '</tr>
	</table>';
	
	require 'tabela_punkty_filtry.php';
	if (isset($_POST['filtry_szukaj'])){

		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptn.* FROM panel_tabela_punkty ptn
					JOIN panel_users u ON u.id = ptn.creator_id
			WHERE 
			ptn.budowa_adres like '%".$_POST['filtry_budowa_adres']."%' &&			
			ptn.realizacja_od like '%".$_POST['filtry_realizacja_od']."%' &&			
			ptn.realizacja_do like '%".$_POST['filtry_realizacja_do']."%' &&			
			ptn.personel like '%".$_POST['filtry_personel']."%' &&
			ptn.data_sporzadzenia_dokumentacji like '%".$_POST['filtry_data_sporzadzenia_dokumentacji']."%' &&
			ptn.data_odeslanej_dokumentacji like '%".$_POST['filtry_data_odeslanej_dokumentacji']."%' &&
			ptn.kwota_kontraktu like '%".$_POST['filtry_kwota_kontraktu']."%' &&
			ptn.data_przeslania_awiza like '%".$_POST['filtry_data_przeslania_awiza']."%' &&
			ptn.data_zaplaty like '%".$_POST['filtry_data_zaplaty']."%' &&
			ptn.uwagi like '%".$_POST['filtry_uwagi']."%' &&
			ptn.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			u.login like '%".$_POST['filtry_login']."%'
			ORDER BY ptn.realizacja_od
		;";
		
		$filters_path = '&budowa_adres='.$_POST['filtry_budowa_adres'].'
						&realizacja_od='.$_POST['filtry_realizacja_od'].'						
						&realizacja_do='.$_POST['filtry_realizacja_do'].'									
						&personel='.$_POST['filtry_personel'].'
						&data_sporzadzenia_dokumentacji='.$_POST['filtry_data_sporzadzenia_dokumentacji'].'
						&data_odeslanej_dokumentacji='.$_POST['filtry_data_odeslanej_dokumentacji'].'
						&kwota_kontraktu='.$_POST['filtry_kwota_kontraktu'].'						
						&data_przeslania_awiza='.$_POST['filtry_data_przeslania_awiza'].'						
						&data_zaplaty='.$_POST['filtry_data_zaplaty'].'						
						&uwagi='.$_POST['filtry_uwagi'].'								
						&data_wpisu='.$_POST['filtry_data_wpisu'].'						
						&login='.$_POST['filtry_login'];
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_punkty ptz
					JOIN panel_users u ON u.id = ptz.creator_id
			WHERE 			
				ptz.budowa_adres like '%".$_GET['filtry_budowa_adres']."%' &&			
				ptz.realizacja_od like '%".$_GET['filtry_realizacja_od']."%' &&			
				ptz.realizacja_do like '%".$_GET['filtry_realizacja_do']."%' &&			
				ptz.personel like '%".$_GET['filtry_personel']."%' &&
				ptz.data_sporzadzenia_dokumentacji like '%".$_GET['filtry_data_sporzadzenia_dokumentacji']."%' &&
				ptz.data_odeslanej_dokumentacji like '%".$_GET['filtry_data_odeslanej_dokumentacji']."%' &&
				ptz.kwota_kontraktu like '%".$_GET['filtry_kwota_kontraktu']."%' &&
				ptz.data_przeslania_awiza like '%".$_GET['filtry_data_przeslania_awiza']."%' &&
				ptz.data_zaplaty like '%".$_GET['filtry_data_zaplaty']."%' &&
				ptz.uwagi like '%".$_GET['filtry_uwagi']."%' &&
				ptz.creation_date like '%".$_GET['filtry_data_wpisu']."%' &&
				u.login like '%".$_GET['filtry_login']."%'			
			ORDER BY ptz.realizacja_od
		;";
		
		$filters_path = '&budowa_adres='.$_GET['filtry_budowa_adres'].'
						&realizacja_od='.$_GET['filtry_realizacja_od'].'						
						&realizacja_do='.$_GET['filtry_realizacja_do'].'									
						&personel='.$_GET['filtry_personel'].'
						&data_sporzadzenia_dokumentacji='.$_GET['filtry_data_sporzadzenia_dokumentacji'].'
						&data_odeslanej_dokumentacji='.$_GET['filtry_data_odeslanej_dokumentacji'].'
						&kwota_kontraktu='.$_GET['filtry_kwota_kontraktu'].'						
						&data_przeslania_awiza='.$_GET['filtry_data_przeslania_awiza'].'						
						&data_zaplaty='.$_GET['filtry_data_zaplaty'].'						
						&uwagi='.$_GET['filtry_uwagi'].'								
						&data_wpisu='.$_GET['filtry_data_wpisu'].'						
						&login='.$_GET['filtry_login'];
	}else{
		$query = "SELECT * FROM panel_tabela_punkty ORDER BY realizacja_od;";
	}
		
	$result = mysql_query($query) or die (mysql_error());
	
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a><br /><br />';
		}else{
			echo '<a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a><br /><br />';
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	echo '<div>';
	
	// Formularz stworzony na potrzeby exportu danych do excel.
	echo '<form action="tabela_punkty_excel.php" method="post">	';
	
	if($row_header['header'] == 'n'){
		echo '<input type="submit" value="Excel" name="punkty_excel" /><br /><br />';
	} else {
		echo '<input type="submit" value="Excel" name="punkty_excel" /><br /><br />';
	}
	
	echo '
	<table width="100%">
		<tr>		
			<td id="naglowek" rowspan="2">*</td>	
			<td id="naglowek" rowspan="2">LP</td>
			<td id="naglowek" rowspan="2">BAUVORHABEN + ADRESSE /<br />BUDOWA + ADRES (MICHAEL)</td>
			<td id="naglowek" colspan="2">AUSFÜHRUNGSTERMIN/<br />TERMIN REALIZACJI (MACIEK B.)</td>
			<td id="naglowek" rowspan="2">PERSONAL /<br />PERSONEL (MACIEK B.)</td>
			<td id="naglowek" rowspan="2">ERSTELLUNGSDATUM DER ABNAHMEDOKUMENTATION SEITENS BRASSCO /<br />DATA SPORZĄDZENIA DOKUMENTACJI PRZEZ BRASSCO (MICHAEL)</td>
			<td id="naglowek" rowspan="2">DATUM DER RÜCKSENDUNG DER ABNAHMEDOKUMENTATION UNTERSCHRIEBEN DURCH AUFTRAGGEBER /<br />DATA ODESŁANEJ DOKUMENTACJI, PODPISANEJ PRZEZ ZLECENIODAWCĘ (MICHAEL)</td>			
			<td id="naglowek" rowspan="2">AUFTRAGSWERT /<br />KWOTA KONTRAKTU (MICHAEL)</td>
			<td id="naglowek" rowspan="2">DATUM DES EINGANGS DES AVIS /<br />DATA PRZESŁANIA AWIZA PŁATNOŚCI</td>
			<td id="naglowek" rowspan="2">DATUM DES ZAHLUNGSEINGANGS /<br />DATA ZAPŁATY</td>
			<td id="naglowek" rowspan="2">VERMERKE/<br />UWAGI</td>
			<td id="naglowek" rowspan="2">DATUM DER EINTRAGUNG/<br />DATA WPISU</td>
			<td id="naglowek" rowspan="2">LOGIN</td>			
			';

			if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
				echo'
					
					<td id="naglowek" rowspan="2">E</td>
					<td id="naglowek" rowspan="2">K</td>
					<td id="naglowek" rowspan="2">U</td>';
			} 
		echo '</tr>
				<tr>
					<td id="naglowek" width="75px">VON/OD</td>
					<td id="naglowek" width="75px">BIS/DO</td>							
				</tr>
	';
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_punkty($row['realizacja_od'], $row['realizacja_do']).'>';
		echo '		
				<td><input type="checkbox" name="export_excel_'.$row['id'].'" value="1" /></td>
				<td>'.$lp.'</td>
				<td>'.nl2br($row['budowa_adres']).'</td>
				<td>'.nl2br($row['realizacja_od']).'</td>
				<td>'.nl2br($row['realizacja_do']).'</td>
				<td>'.nl2br($row['personel']).'</td>					
				<td>'.nl2br($row['data_sporzadzenia_dokumentacji']).'</td>				
				<td>'.nl2br($row['data_odeslanej_dokumentacji']).'</td>				
				<td>'.nl2br($row['kwota_kontraktu']).'</td>				
				<td>'.nl2br($row['data_przeslania_awiza']).'</td>			
				<td>'.nl2br($row['data_zaplaty']).'</td>			
				<td>'.nl2br($row['uwagi']).'</td>			
				<td>'.substr($row['creation_date'], 8, 2).'-'.substr($row['creation_date'], 5, 2).'-'.substr($row['creation_date'], 0, 4).'</td>
				<td>'.$row_login['login'].'</td>';

				if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
					if($row_header['id'] != 16){ //Nie widzi user PDG
					echo '<td id="komorka_edycja"><a href="tabela_punkty_edytuj.php?id='.$row['id'].$filters_path.'"><button type="button">E</button></a></td>
						<td id="komorka_kopiuj"><a href="tabela_punkty_kopiuj.php?id='.$row['id'].$filters_path.'"><button type="button">K</button></a></td>
						<td id="komorka_usun"><a href="tabela_punkty_usun.php?id='.$row['id'].$filters_path.'"><button type="button">X</button></a></td>';
					}	
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';
	echo '</form>';
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a>';
		}else{
			echo '<br /><a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a>';
		}
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');