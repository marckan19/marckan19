<?php
ob_start();
session_start();
require_once('header.php');

header('refresh: 60;');

if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){

	require_once('menu.php');
			
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Benachrichtigungen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Powiadomienia</h2></td><td width="20px" />';			
			}			
		echo '</tr>
	</table>';
	
	$query = "
		SELECT ptn.*, pu.login as creator, pu2.login as modificator
		FROM panel_tabela_noclegi ptn
		JOIN panel_users pu ON pu.id = ptn.creator_id
		JOIN panel_users pu2 on pu2.id = ptn.modificator_id
		WHERE modification_date >= NOW() - INTERVAL 48 hour
		ORDER BY SUBSTRING(modification_date, 1, 10) DESC, SUBSTRING(modification_date, 11) DESC
	;";
				
	$result = mysql_query($query) or die (mysql_error());
	
	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if($row_header['id'] != 16){ //Nie widzi user PDG
		require_once('menu_powiadomienia.php');
	}
	
	echo '<div>
	<table>
		<tr><td id="naglowek" colspan="22" style="font-size:20px;">NOCLEGI</td></tr>
		<tr>	';
			if($row_header['header'] == 'n'){
				echo '
					<td id="naglowek" rowspan="2">lfd. Nr.</td>
					<td id="naglowek" rowspan="2">Bauvorhaben</td>
					<td id="naglowek" rowspan="2">Vermieter</td>			
					<td id="naglowek" rowspan="2">Anzhal der Personen</td>			
					<td id="naglowek" colspan="2">Mietzeitraum</td>
					<td id="naglowek" rowspan="2">Preis</td>
					<td id="naglowek" rowspan="2" width="75px">Info. zur Zahlung</td>
					<td id="naglowek" rowspan="2" width="100px">Bemerkungen</td>
					<td id="naglowek" rowspan="2" width="100px">Ansprechpartner</td>
					<td id="naglowek" rowspan="2" width="75px">Datum der Eintragung</td>
					<td id="naglowek" rowspan="2" width="85px">DATA UTW.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN UTW.</td>
					<td id="naglowek" rowspan="2" width="85px">DATA MOD.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN MOD.</td>	
				';
			}else{
				echo '
					<td id="naglowek" rowspan="2">LP</td>
					<td id="naglowek" rowspan="2">BUDOWA</td>
					<td id="naglowek" rowspan="2">FIRMA</td>			
					<td id="naglowek" rowspan="2">L. OS. <br />MIESZ.</td>			
					<td id="naglowek" colspan="2">OKRES WYNAJMU</td>
					<td id="naglowek" rowspan="2">CENA</td>
					<td id="naglowek" rowspan="2" width="75px">PŁACONE</td>
					<td id="naglowek" rowspan="2" width="100px">UWAGI</td>
					<td id="naglowek" rowspan="2" width="100px">OSOBA KONTAKTOWA</td>
					<td id="naglowek" rowspan="2" width="85px">DATA UTW.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN UTW.</td>
					<td id="naglowek" rowspan="2" width="85px">DATA MOD.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN MOD.</td>	
				';
			}
						
		echo '</tr>
				<tr>';
		if($row_header['header'] == 'n'){
			echo '
				<td id="naglowek" width="75px">vom</td>
				<td id="naglowek" width="75px">bis zum</td>
			';
		}else{
			echo '
				<td id="naglowek" width="75px">OD</td>
				<td id="naglowek" width="75px">DO</td>
			';
		}
		echo '</tr>	';
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){		
		echo '<tr '.kolor_wiersza_noclegi($row['okres_wynajmu_do'], $row['zaznaczony_wiersz']).'>';
		if($row['creation_date'] == $row['modification_date']){
			echo '<td style="background-color:green;">';
		}else{
			echo '<td style="background-color:red;">';
		}
		
				echo $lp.'</td>
				<td>'.nl2br($row['budowa']).'</td>
				<td>'.nl2br($row['firma']).'</td>						
				<td>'.nl2br($row['ilosc_osob_mieszka']).'</td>				
				<td>'.nl2br($row['okres_wynajmu_od']).'</td>				
				<td>'.nl2br($row['okres_wynajmu_do']).'</td>				
				<td>'.nl2br($row['cena']).'</td>
				<td>'.nl2br($row['placone']).'</td>				
				<td>'.nl2br($row['uwagi']).'</td>
				<td>'.nl2br($row['os_kontaktowa']).'</td>
				<td>'.substr($row['creation_date'], 0, 10).'<br />'.substr($row['creation_date'], 11, 8).'</td>
				<td>'.$row['creator'].'</td>
				<td>'.substr($row['modification_date'], 0, 10).'<br />'.substr($row['modification_date'], 11, 8).'</td>
				<td>'.$row['modificator'].'</td>
				';
				
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';	
	
}else{
	require_once('logout.php');
}

require_once('footer.php');