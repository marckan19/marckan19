<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	
	require_once('menu.php');
	
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		$id = 0;
	}	

	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
	}else{
		$filters_path = '';
	}	
	
	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
			
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Festpunkte - den Eintrag löschen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Punkty stałe - usuwanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_punkty.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['usun'])){
		
		$query_delete = "DELETE FROM panel_tabela_punkty WHERE id = ".$id.";";
		mysql_query($query_delete);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_punkty_usunieto.php'.$filters_path);
		
	}elseif(isset($_POST['nie_usuwaj'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
	
		header('Location: tabela_punkty.php'.$filters_path);
	}else{
		echo '<form action="tabela_punkty_usun.php?id='.$id.$filters_path.'" method="post">	
		<table>
			<tr>
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
			</tr>
			<tr>
				<td id="naglowek" width="75px">VON/OD</td>
				<td id="naglowek" width="75px">BIS/DO</td>
			</tr>
		';
		
		$query = "SELECT * FROM panel_tabela_punkty WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '
				<tr '.kolor_wiersza_punkty($row['realizacja_od'], $row['realizacja_do']).'>
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
					<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row_login['login'].'</td>
				</tr>
		';
		
		if($row_header['header'] == 'n'){
			echo '</table><br />Möchten Sie den Eintrag löschen?<br /><br /><input type="submit"  value="Ja" name="usun" /> <input type="submit"  value="Nein" name="nie_usuwaj" /></form>';	
		}else{
			echo '</table><br />Czy na pewno chcesz usunąć ten wpis?<br /><br /><input type="submit"  value="Tak" name="usun" /> <input type="submit"  value="Nie" name="nie_usuwaj" /></form>';	
		}
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');