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
		SELECT ptb.*, pu.login as creator, pu2.login as modificator
		FROM panel_tabela_budowy ptb
		JOIN panel_users pu ON pu.id = ptb.creator_id
		JOIN panel_users pu2 on pu2.id = ptb.modificator_id
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
		<tr><td id="naglowek" colspan="22" style="font-size:20px;">BUDOWY</td></tr>
		<tr>	';
			if($row_header['header'] == 'n'){
				echo '
					<td id="naglowek" rowspan="2">LP</td>
					<td id="naglowek" rowspan="2" width="230px">BAUVORHABEN - AUFTRAGGEBER / BUDOWA - ZLECENIODAWCA</td>
					<td id="naglowek" rowspan="2" width="100px">Höhe der Halle / WYS. HALI</td>
					<td id="naglowek" colspan="2">AUSFÜHRUNGSZEIT /<br /> TERMIN REALIZACJI</td>
					<td id="naglowek" rowspan="2">BAULEITER /<br /> KIEROWNIK</td>
					<td id="naglowek" rowspan="2">UNTERLAGEN /<br /> DOKUMENTY</td>			
					<td id="naglowek" rowspan="2">BAU-TAGESBERICHTE KW-</td>
					<td id="naglowek" rowspan="2">ZOLLANMELDUNG <br />ZGŁOSZENIE DO ZOLL</td>
					<td id="naglowek" rowspan="2">MB</td>			
					<td id="naglowek" rowspan="2" width="100px">REVI / REWIZJA</td>
					<td id="naglowek" rowspan="2" width="75px">DATA WPISU</td>
					<td id="naglowek" rowspan="2" width="100px">UWAGI</td>
					<td id="naglowek" rowspan="2" width="100px">Nachträge</td>
					<td id="naglowek" rowspan="2" width="100px">VERMERK</td>
					<td id="naglowek" rowspan="2" width="85px">DATA UTW.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN UTW.</td>
					<td id="naglowek" rowspan="2" width="85px">DATA MOD.</td>
					<td id="naglowek" rowspan="2" width="60px">LOGIN MOD.</td>
				';
			}else{
				echo '
					<td id="naglowek" rowspan="2">LP</td>
					<td id="naglowek" rowspan="2" width="230px">BAUVORHABEN - AUFTRAGGEBER / BUDOWA - ZLECENIODAWCA</td>
					<td id="naglowek" rowspan="2" width="100px">Höhe der Halle / WYS. HALI</td>
					<td id="naglowek" colspan="2">AUSFÜHRUNGSZEIT /<br /> TERMIN REALIZACJI</td>
					<td id="naglowek" rowspan="2">BAULEITER /<br /> KIEROWNIK</td>
					<td id="naglowek" rowspan="2">UNTERLAGEN /<br /> DOKUMENTY</td>			
					<td id="naglowek" rowspan="2">BAU-TAGESBERICHTE KW-</td>
					<td id="naglowek" rowspan="2">ZOLLANMELDUNG <br />ZGŁOSZENIE DO ZOLL</td>
					<td id="naglowek" rowspan="2">MB</td>			
					<td id="naglowek" rowspan="2" width="100px">REVI / REWIZJA</td>
					<td id="naglowek" rowspan="2" width="75px">DATA WPISU</td>
					<td id="naglowek" rowspan="2" width="100px">UWAGI</td>
					<td id="naglowek" rowspan="2" width="100px">Nachträge</td>
					<td id="naglowek" rowspan="2" width="100px">VERMERK</td>
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
					<td id="naglowek" width="150px">OD</td>
					<td id="naglowek" width="150px">DO</td>
				';
			}			
		echo '</tr>';
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){		
		echo '<tr '.kolor_wiersza_budowy($row['realizacja_od'], $row['realizacja_do'], $row['zaznaczony_wiersz']).'>';
		if($row['creation_date'] == $row['modification_date']){
			echo '<td style="background-color:green;">';
		}else{
			echo '<td style="background-color:red;">';
		}
		
				echo $lp.'</td>
				<td>'.nl2br($row['zleceniodawca']).'</td>
				<td>'.nl2br($row['wys_hali']).'</td>
				<td>'.nl2br($row['realizacja_od']).'</td>
				<td>'.nl2br($row['realizacja_do']).'</td>					
				<td>'.nl2br($row['kierownik']).'</td>				
				<td>'.nl2br($row['dokumenty']).'</td>				
				<td>'.nl2br($row['tydzien']).'</td>				
				<td>'.nl2br($row['zgloszeni']).'</td>
				<td>'.nl2br($row['tekst']).'</td>
				<td>'.nl2br($row['rewizja']).'</td>			
				<td>'.substr($row['creation_date'], 8, 2).'-'.substr($row['creation_date'], 5, 2).'-'.substr($row['creation_date'], 0, 4).'</td>
				<td>'.nl2br($row['uwagi']).'</td>
				<td>'.nl2br($row['suplementy']).'</td>
				<td>'.nl2br($row['adnotacje']).'</td>
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