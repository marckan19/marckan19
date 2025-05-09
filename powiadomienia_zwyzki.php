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
		SELECT ptz.*, pu.login as creator, pu2.login as modificator
		FROM panel_tabela_zwyzki ptz
		JOIN panel_users pu ON pu.id = ptz.creator_id
		JOIN panel_users pu2 on pu2.id = ptz.modificator_id
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
		<tr><td id="naglowek" colspan="22" style="font-size:20px;">ZWYŻKI</td></tr>
		<tr>	';
			if($row_header['header'] == 'n'){
				echo '
					<td id="naglowek" rowspan="2" width="10px">lfd. Nr.</td>			
					<td id="naglowek" rowspan="2" width="90px">Bauvorhaben</td>
					<td id="naglowek" rowspan="2" width="70px">Vermieter</td>
					<td id="naglowek" rowspan="2" width="70px">Bühnentyp</td>
					<td id="naglowek" rowspan="2" width="70px">Arbeitshöhe</td>		
					<td id="naglowek" rowspan="2" width="85px">Mietpreis</td>
					<td id="naglowek" rowspan="2" width="70px">MaschinenNr.</td>
					<td id="naglowek" colspan="2">Mietzeitraum</td>
					<td id="naglowek" rowspan="2" width="85px">auf Stand gesetzt bis zum</td>
					<td id="naglowek" rowspan="2" width="85px">zusätzliche Einsatztage</td>
					<td id="naglowek" rowspan="2" width="85px">Standtage</td>
					<td id="naglowek" rowspan="2" width="85px">Freimeldung</td>
					<td id="naglowek" rowspan="2" width="150px">Bemerkungen</td>
					<td id="naglowek" rowspan="2" width="100px">Ansprechpartner</td>
					<td id="naglowek" rowspan="2" width="75px">Rechnung</td>
					<td id="naglowek" rowspan="2" width="100px">Tankservice</td>
					<td id="naglowek" rowspan="2" width="70px">Müll</td>
					<td id="naglowek" rowspan="2" width="85px">DATA UTW.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN UTW.</td>
					<td id="naglowek" rowspan="2" width="85px">DATA MOD.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN MOD.</td>
				';
			}else{
				echo '
					<td id="naglowek" rowspan="2" width="10px">LP</td>			
					<td id="naglowek" rowspan="2" width="90px">BUDOWA</td>
					<td id="naglowek" rowspan="2" width="70px">FIRMA WYNAJM.</td>
					<td id="naglowek" rowspan="2" width="70px">RODZAJ ZWYŻKI</td>
					<td id="naglowek" rowspan="2" width="70px">WYSOK.</td>		
					<td id="naglowek" rowspan="2" width="85px">CENA</td>
					<td id="naglowek" rowspan="2" width="70px">NR MASZYNY</td>
					<td id="naglowek" colspan="2" >DATA WYNAJMU</td>
					<td id="naglowek" rowspan="2" width="85px">ZAWIESZ. DO</td>
					<td id="naglowek" rowspan="2" width="85px">DODATKOWE DNI ZWYŻKI</td>
					<td id="naglowek" rowspan="2" width="85px">DATA ZAWIESZ.</td>
					<td id="naglowek" rowspan="2" width="85px">DATA ZDANIA</td>
					<td id="naglowek" rowspan="2" width="150px">UWAGI</td>
					<td id="naglowek" rowspan="2" width="100px">OSOBA KONTAKTOWA</td>
					<td id="naglowek" rowspan="2" width="75px">RACHUNEK</td>
					<td id="naglowek" rowspan="2" width="100px">TANKSERVICE</td>
					<td id="naglowek" rowspan="2" width="70px">ŚMIECI</td>
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
					<td id="naglowek" width="110px">vom</td>
					<td id="naglowek" width="110px">bis zum</td>
				';
			}else{
					echo '
					<td id="naglowek" width="95px">OD</td>
					<td id="naglowek" width="95px">DO</td>
				';
			}			
		echo '</tr>';
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){		
		echo '<tr '.kolor_wiersza_zwyzki($row['data_zdania'], $row['zawieszone_do'], $row['zaznaczony_wiersz'], $row['oferta']).' >';
		if($row['creation_date'] == $row['modification_date']){
			echo '<td style="background-color:green;">';
		}else{
			echo '<td style="background-color:red;">';
		}
		
				echo $lp.'</td>
				<td>'.nl2br($row['budowa']).'</td>
				<td>'.nl2br($row['firma_wynajmujaca']).'</td>
				<td>'.nl2br($row['rodzaj_zwyzki']).'</td>
				<td>'.nl2br($row['wysokosc']).'</td>			
				<td>'.nl2br($row['cena']).'</td>
				<td>'.nl2br($row['nr_maszyny']).'</td>
				<td>'.nl2br($row['data_wynajmu_od']).'</td>
				<td>'.nl2br($row['data_wynajmu_do']).'</td>
				<td>'.nl2br($row['zawieszone_do']).'</td>
				<td>'.nl2br($row['dodatkowe_dni']).'</td>
				<td>'.nl2br($row['data_zawiesz']).'</td>
				<td>'.nl2br($row['data_zdania']).'</td>				
				<td>'.nl2br($row['uwagi']).'</td>
				<td>'.nl2br($row['os_kontaktowa']).'</td>
				<td>'.nl2br($row['rachunek']).'</td>
				<td>'.nl2br($row['tankservice']).'</td>
				<td>'.nl2br($row['smieci']).'</td>
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