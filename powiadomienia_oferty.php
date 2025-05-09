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
		SELECT pto.*, pu.login as creator, pu2.login as modificator
		FROM panel_tabela_oferty pto
		JOIN panel_users pu ON pu.id = pto.creator_id
		JOIN panel_users pu2 on pu2.id = pto.modificator_id
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
		<tr><td id="naglowek" colspan="22" style="font-size:20px;">OFERTY</td></tr>
		<tr>	';
			if($row_header['header'] == 'n'){
				echo '
					<td id="naglowek" rowspan="2">lfd. Nr.</td>
					<td id="naglowek" rowspan="2">Vermieter</td>
					<td id="naglowek" rowspan="2">Maschinentyp</td>
					<td id="naglowek" rowspan="2">Opis</td>
					<td id="naglowek" rowspan="2">Arbeitshöhe</td>			
					<td id="naglowek" rowspan="1" colspan="4">Mietdauer (pro Tag)</td>					
					<td id="naglowek" rowspan="2">Bemerkungen</td>
					<td id="naglowek" rowspan="2" width="85px">DATA UTW.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN UTW.</td>
					<td id="naglowek" rowspan="2" width="85px">DATA MOD.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN MOD.</td>		
				';
			}else{
				echo '
					<td id="naglowek" rowspan="2">LP</td>
					<td id="naglowek" rowspan="2">WYNAJMUJĄCY</td>
					<td id="naglowek" rowspan="2">MASZYNA</td>
					<td id="naglowek" rowspan="2">OPIS</td>
					<td id="naglowek" rowspan="2">WYSOKOŚĆ</td>			
					<td id="naglowek" rowspan="1" colspan="4">WYPOŻYCZALNIA (DZIENNIE)</td>					
					<td id="naglowek" rowspan="2">UWAGI</td>
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
				<td id="naglowek">1-2 Tage</td>
				<td id="naglowek">3-4 Tage</td>
				<td id="naglowek">5-10 Tage</td>
				<td id="naglowek">ab 11 Tage</td>
			';
		}else{
			echo '
				<td id="naglowek">1-2 dni</td>
				<td id="naglowek">3-4 dni</td>
				<td id="naglowek">5-10 dni</td>
				<td id="naglowek">od 11 dni</td>
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
				<td>'.nl2br($row['wynajmujacy']).'</td>
				<td>'.nl2br($row['maszyna']).'</td>
				<td>'.nl2br($row['opis']).'</td>
				<td>'.nl2br($row['wysokosc_od']).' - '.nl2br($row['wysokosc_do']).'</td>						
				<td>'.nl2br($row['wyp_1_2']).'</td>				
				<td>'.nl2br($row['wyp_3_4']).'</td>				
				<td>'.nl2br($row['wyp_5_10']).'</td>				
				<td>'.nl2br($row['wyp_od_11']).'</td>				
				<td>'.nl2br($row['uwagi']).'</td>				
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