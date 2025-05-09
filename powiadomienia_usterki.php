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
		SELECT ptu.*, pu.login as creator, pu2.login as modificator
		FROM panel_tabela_usterki ptu
		JOIN panel_users pu ON pu.id = ptu.creator_id
		JOIN panel_users pu2 on pu2.id = ptu.modificator_id
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
		<tr><td id="naglowek" colspan="22" style="font-size:20px;">USTERKI</td></tr>
		<tr>	';
			if($row_header['header'] == 'n'){
		echo '
			<td id="naglowek" width="35px">Lfd.Nr.</td>
			<td id="naglowek">Bauvorhaben</td>
			<td id="naglowek">Mängel</td>			
			<td id="naglowek">Wo</td>			
			<td id="naglowek">Achse</td>			
			<td id="naglowek">Ansprechpartner /Telefon</td>			
			<td id="naglowek" width="150px">gemeldet am</td>			
			<td id="naglowek" width="150px">Beseitigungsfirst</td>			
			<td id="naglowek" width="150px">beseitigt am</td>
			<td id="naglowek">beseitigt von</td>
			<td id="naglowek">Anmerkungen</td>
			<td id="naglowek">benötigte Gerätschaften: Arbeitsbühne, Regal, Leiter</td>
			<td id="naglowek" width="85px">DATA UTW.</td>
			<td id="naglowek" width="60px">LOGIN UTW.</td>
			<td id="naglowek" width="85px">DATA MOD.</td>
			<td id="naglowek" width="60px">LOGIN MOD.</td>				
		';
	}else{
		echo '
			<td id="naglowek" width="25px">LP</td>
			<td id="naglowek">BUDOWA</td>
			<td id="naglowek">USTERKA</td>			
			<td id="naglowek">GDZIE</td>			
			<td id="naglowek">OŚ</td>			
			<td id="naglowek">KONTAKT</td>			
			<td id="naglowek" width="150px">DATA ZGŁOSZENIA</td>			
			<td id="naglowek" width="150px">TERMIN USUNIĘCIA</td>			
			<td id="naglowek" width="150px">DATA USUNIĘCIA</td>
			<td id="naglowek">USUNIĘTE PRZEZ</td>
			<td id="naglowek">UWAGI</td>
			<td id="naglowek">POTRZEBNY SPRZĘT</td>
			<td id="naglowek" width="85px">DATA UTW.</td>
			<td id="naglowek" width="60px">LOGIN UTW.</td>
			<td id="naglowek" width="85px">DATA MOD.</td>
			<td id="naglowek" width="60px">LOGIN MOD.</td>		
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
				<td>'.nl2br($row['usterka']).'</td>						
				<td>'.nl2br($row['gdzie']).'</td>						
				<td>'.nl2br($row['os']).'</td>						
				<td>'.nl2br($row['kontakt']).'</td>						
				<td>'.zerowaDataNaPusta($row['data_zgloszenia']).'</td>				
				<td>'.zerowaDataNaPusta($row['termin_usuniecia']).'</td>									
				<td>'.zerowaDataNaPusta($row['data_usuniecia']).'</td>									
				<td>'.nl2br($row['usunal']).'</td>
				<td>'.nl2br($row['uwagi']).'</td>
				<td>'.nl2br($row['sprzet']).'</td>
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