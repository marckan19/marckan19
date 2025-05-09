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
		SELECT ptww.*, pu.login as creator, pu2.login as modificator
		FROM panel_tabela_wyjazdy_wpisy ptww
		JOIN panel_users pu ON pu.id = ptww.creator_id
		JOIN panel_users pu2 on pu2.id = ptww.modificator_id
		WHERE ptww.modification_date >= NOW() - INTERVAL 48 hour
		ORDER BY SUBSTRING(modification_date, 1, 10) DESC, SUBSTRING(modification_date, 12) DESC
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
		<tr><td id="naglowek" colspan="22" style="font-size:20px;">WYJAZDY</td></tr>
		<tr>	';
			if($row_header['header'] == 'n'){
				echo '
					<td id="naglowek" rowspan="1">Lfd.<br />Nr.</td>
					<td id="naglowek" rowspan="1">Mitarbeiter</td>
					<td id="naglowek" rowspan="1">Baustelle</td>
					<td id="naglowek" rowspan="1">Auf Stand<br />setzen</td>			
					<td id="naglowek" rowspan="1">freimelden</td>			
					<td id="naglowek" rowspan="1">bestellen</td>			
					<td id="naglowek" rowspan="1">Dienstwagen</td>
					<td id="naglowek" rowspan="1" width="80px">Bauvorhaben<br />bis</td>			
					<td id="naglowek" rowspan="1" width="80px">Bauvorhaben<br />zum</td>						
					<td id="naglowek" rowspan="1">Bemerkungen</td>			
					<td id="naglowek" width="85px">DATA UTW.</td>
					<td id="naglowek" width="60px">LOGIN UTW.</td>
					<td id="naglowek" width="85px">DATA MOD.</td>
					<td id="naglowek" width="60px">LOGIN MOD.</td>
				';
			}else{
				echo '
					<td id="naglowek" rowspan="1">LP</td>
					<td id="naglowek" rowspan="1">PRACOWNIK</td>
					<td id="naglowek" rowspan="1">BUDOWA</td>			
					<td id="naglowek" rowspan="1">ZAWIESIĆ</td>			
					<td id="naglowek" rowspan="1">ZDAĆ</td>			
					<td id="naglowek" rowspan="1">ZAMÓWIĆ</td>
					<td id="naglowek" rowspan="1">SAMOCHÓD</td>
					<td id="naglowek" rowspan="1" width="80px">BUDOWA OD</td>			
					<td id="naglowek" rowspan="1" width="80px">BUDOWA DO</td>
					<td id="naglowek" rowspan="1">UWAGI</td>			
					<td id="naglowek" width="85px">DATA UTW.</td>
					<td id="naglowek" width="60px">LOGIN UTW.</td>
					<td id="naglowek" width="85px">DATA MOD.</td>
					<td id="naglowek" width="60px">LOGIN MOD.</td>	
				';
			}
						
		echo '</tr>';
		
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){	

		//WYCIAGNIECIE KOLORU BUDOWY
		$query_kolor = "
			SELECT ptwb.*
			FROM panel_tabela_wyjazdy_budowy  ptwb			
			JOIN panel_tabela_wyjazdy_budowy_powiazania ptwbp ON ptwbp.budowa_id = ptwb.id	
			WHERE ptwbp.wpis_id = ".$row['id']." AND ptwbp.is_main = 1
			ORDER BY ptwb.budowa
			LIMIT 1;";
		$result_kolor = mysql_query($query_kolor) or die (mysql_error());
		$row_kolor = mysql_fetch_array($result_kolor,MYSQL_ASSOC);
		
		$query_lista_pracownikow = "
			SELECT ptwp.*, ptwpp.id AS id_pow
			FROM panel_tabela_wyjazdy_pracownicy ptwp			
			JOIN panel_tabela_wyjazdy_pracownicy_powiazania ptwpp ON ptwpp.pracownik_id = ptwp.id		
			WHERE ptwpp.wpis_id = ".$row['id']."
			ORDER BY ptwp.nazwisko, ptwp.imie;";
		$result_lista_pracownikow = mysql_query($query_lista_pracownikow) or die (mysql_error());
				
		$query_lista_budow = "
			SELECT ptwb.*, ptwbp.*
			FROM panel_tabela_wyjazdy_budowy ptwb			
			JOIN panel_tabela_wyjazdy_budowy_powiazania ptwbp ON ptwbp.budowa_id = ptwb.id		
			WHERE ptwbp.wpis_id = ".$row['id']."
			ORDER BY ptwbp.is_main DESC, ptwb.budowa;";
		$result_lista_budow = mysql_query($query_lista_budow) or die (mysql_error());
		$result_lista_budow_zawiesic = mysql_query($query_lista_budow) or die (mysql_error());
		$result_lista_budow_zdac = mysql_query($query_lista_budow) or die (mysql_error());
		$result_lista_budow_zamowic = mysql_query($query_lista_budow) or die (mysql_error());
		$result_lista_budowa_od = mysql_query($query_lista_budow) or die (mysql_error());
		$result_lista_budowa_do = mysql_query($query_lista_budow) or die (mysql_error());
		$result_lista_budow_uwagi = mysql_query($query_lista_budow) or die (mysql_error());
		
		$query_lista_samochodow = "
			SELECT ptws.*
			FROM panel_tabela_wyjazdy_samochody ptws			
			JOIN panel_tabela_wyjazdy_samochody_powiazania ptwsp ON ptwsp.samochod_id = ptws.id		
			WHERE ptwsp.wpis_id = ".$row['id']."
			ORDER BY ptws.rejestracja;";
		$result_lista_samochodow = mysql_query($query_lista_samochodow) or die (mysql_error());
			
		$lp_prac = 1;
		$lp_budow = 1;
		$lp_budowa_od = 1;
		$lp_budowa_do = 1;
		$lp_uwagi = 1;
		$lp_zawiesic = 1;
		$lp_zdac = 1;
		$lp_zamowic = 1;
		$lp_samochodow = 1;
	
		echo '<tr '.kolor_wiersza_wyjazdy($row_kolor['kolor']).'>';
		if($row['creation_date'] == $row['modification_date']){
			echo '<td style="background-color:green;">';
		}else{
			echo '<td style="background-color:red;">';
		}
		
				echo $lp.'</td>
				<td><table width="100%">';				
				while($row_lista_pracownikow = mysql_fetch_array($result_lista_pracownikow,MYSQL_ASSOC)){
					echo '<tr><td>'.$lp_prac++.'. <b>'.nl2br($row_lista_pracownikow['nazwisko']).' '.nl2br($row_lista_pracownikow['imie']).'</b> ('.$row_lista_pracownikow['kontakt'].')</td>
					<td align="right"><a href="tabela_wyjazdy_pracownik_usun.php?id_pow='.$row_lista_pracownikow['id_pow'].'">X</a></td></tr>';
				}		
				//echo '';	
				echo '</table></td>
				<td valign="top"><br /><table width="100%"><tr><td> </td><td></td></tr>';						
				while($row_lista_budow = mysql_fetch_array($result_lista_budow,MYSQL_ASSOC)){
					echo '<tr><td style="background-color:'.$row_lista_budow['kolor'].';">'.$lp_budow++.'. '.nl2br($row_lista_budow['budowa']).'</td><td align="right" style="background-color:'.$row_lista_budow['kolor'].';">';					
					if($row_lista_budow['is_main'] == 0){
						echo '<a href="tabela_wyjazdy_dodatkowa_budowa_usun.php?id_pow='.$row_lista_budow['id'].'">X </a>';
					}
					echo '<a href="tabela_wyjazdy_dodatkowa_budowa_edytuj.php?id_pow='.$row_lista_budow['id'].'">E</a>';
					echo '</td></tr>';
				}					
				echo '</table></td>
				<td valign="top">
					<table width="100%">
						<tr>
							<td>Pn.</td>
							<td>Wt.</td>
							<td>Śr.</td>
							<td>Cz.</td>
							<td>Pt.</td>
							<td>So.</td>
							<td>Nd.</td>
						</tr>';						
				while($row_lista_budow_zawiesic = mysql_fetch_array($result_lista_budow_zawiesic,MYSQL_ASSOC)){
					echo '				
						<tr>
							<td style="background-color:'.$row_lista_budow_zawiesic['kolor'].';">'.nl2br($row_lista_budow_zawiesic['zawiesic_1']).'</td>
							<td style="background-color:'.$row_lista_budow_zawiesic['kolor'].';">'.nl2br($row_lista_budow_zawiesic['zawiesic_2']).'</td>
							<td style="background-color:'.$row_lista_budow_zawiesic['kolor'].';">'.nl2br($row_lista_budow_zawiesic['zawiesic_3']).'</td>
							<td style="background-color:'.$row_lista_budow_zawiesic['kolor'].';">'.nl2br($row_lista_budow_zawiesic['zawiesic_4']).'</td>
							<td style="background-color:'.$row_lista_budow_zawiesic['kolor'].';">'.nl2br($row_lista_budow_zawiesic['zawiesic_5']).'</td>
							<td style="background-color:'.$row_lista_budow_zawiesic['kolor'].';">'.nl2br($row_lista_budow_zawiesic['zawiesic_6']).'</td>
							<td style="background-color:'.$row_lista_budow_zawiesic['kolor'].';">'.nl2br($row_lista_budow_zawiesic['zawiesic_7']).'</td>
						</tr>
					';
				}				
				echo '</table></td>					
				<td valign="top">
					<table width="100%">
						<tr>
							<td>Pn.</td>
							<td>Wt.</td>
							<td>Śr.</td>
							<td>Cz.</td>
							<td>Pt.</td>
							<td>So.</td>
							<td>Nd.</td>
						</tr>';						
				while($row_lista_budow_zdac = mysql_fetch_array($result_lista_budow_zdac,MYSQL_ASSOC)){
					echo '				
						<tr>
							<td style="background-color:'.$row_lista_budow_zdac['kolor'].';">'.nl2br($row_lista_budow_zdac['zdac_1']).'</td>
							<td style="background-color:'.$row_lista_budow_zdac['kolor'].';">'.nl2br($row_lista_budow_zdac['zdac_2']).'</td>
							<td style="background-color:'.$row_lista_budow_zdac['kolor'].';">'.nl2br($row_lista_budow_zdac['zdac_3']).'</td>
							<td style="background-color:'.$row_lista_budow_zdac['kolor'].';">'.nl2br($row_lista_budow_zdac['zdac_4']).'</td>
							<td style="background-color:'.$row_lista_budow_zdac['kolor'].';">'.nl2br($row_lista_budow_zdac['zdac_5']).'</td>
							<td style="background-color:'.$row_lista_budow_zdac['kolor'].';">'.nl2br($row_lista_budow_zdac['zdac_6']).'</td>
							<td style="background-color:'.$row_lista_budow_zdac['kolor'].';">'.nl2br($row_lista_budow_zdac['zdac_7']).'</td>
						</tr>
					';
				}				
				echo '</table></td>								
				<td valign="top">
					<table width="100%">
						<tr>
							<td>Pn.</td>
							<td>Wt.</td>
							<td>Śr.</td>
							<td>Cz.</td>
							<td>Pt.</td>
							<td>So.</td>
							<td>Nd.</td>
						</tr>';						
				while($row_lista_budow_zamowic = mysql_fetch_array($result_lista_budow_zamowic,MYSQL_ASSOC)){
					echo '				
						<tr>
							<td style="background-color:'.$row_lista_budow_zamowic['kolor'].';">'.nl2br($row_lista_budow_zamowic['zamowic_1']).'</td>
							<td style="background-color:'.$row_lista_budow_zamowic['kolor'].';">'.nl2br($row_lista_budow_zamowic['zamowic_2']).'</td>
							<td style="background-color:'.$row_lista_budow_zamowic['kolor'].';">'.nl2br($row_lista_budow_zamowic['zamowic_3']).'</td>
							<td style="background-color:'.$row_lista_budow_zamowic['kolor'].';">'.nl2br($row_lista_budow_zamowic['zamowic_4']).'</td>
							<td style="background-color:'.$row_lista_budow_zamowic['kolor'].';">'.nl2br($row_lista_budow_zamowic['zamowic_5']).'</td>
							<td style="background-color:'.$row_lista_budow_zamowic['kolor'].';">'.nl2br($row_lista_budow_zamowic['zamowic_6']).'</td>
							<td style="background-color:'.$row_lista_budow_zamowic['kolor'].';">'.nl2br($row_lista_budow_zamowic['zamowic_7']).'</td>
						</tr>
					';
				}				
				echo '</table></td>	
				<td>';						
				while($row_lista_samochodow = mysql_fetch_array($result_lista_samochodow,MYSQL_ASSOC)){
					echo $lp_samochodow++.'. '.nl2br($row_lista_samochodow['rejestracja']).'<br />';
				}				
				echo '</td>
				<td valign="top"><br /><table width="100%"><tr><td> </td></tr>';		
				while($row_lista_budowa_od = mysql_fetch_array($result_lista_budowa_od,MYSQL_ASSOC)){
					echo '<tr><td style="background-color:'.$row_lista_budowa_od['kolor'].';">'.nl2br($row_lista_budowa_od['budowa_od']).'</td></tr>';
				}				
				echo '</table></td>
				<td valign="top"><br /><table width="100%"><tr><td> </td></tr>';					
				while($row_lista_budowa_do = mysql_fetch_array($result_lista_budowa_do,MYSQL_ASSOC)){
					echo '<tr><td style="background-color:'.$row_lista_budowa_do['kolor'].';">'.nl2br($row_lista_budowa_do['budowa_do']).'</td></tr>';
				}				
				echo '</table></td>					
								
				<td valign="top"><br /><table width="100%"><tr><td> </td></tr>';					
				while($row_lista_budow_uwagi = mysql_fetch_array($result_lista_budow_uwagi,MYSQL_ASSOC)){
					
					echo '<tr><td style="background-color:'.$row_lista_budow_uwagi['kolor'].';">';
					if(nl2br($row_lista_budow_uwagi['uwagi']) == ''){
						echo '-';
					}else{
						echo nl2br($row_lista_budow_uwagi['uwagi']);
					}
					echo '</td></tr>';
				}				
				echo '</table></td>								
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