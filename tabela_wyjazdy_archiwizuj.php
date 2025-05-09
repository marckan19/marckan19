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
				echo '<td><h2>Baustellen - Archiwizacja</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - Archiwizacja</h2></td><td width="20px" />';			
			}
			require_once('legenda_wyjazdy.php');
		echo '</tr>
	</table></div>
	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
		
	if(isset($_POST['tak'])){
		$rekordy = $_POST['rekordy'];
		$query_archiwizuj = "UPDATE panel_tabela_wyjazdy_wpisy SET archiwizacja = 1 WHERE id in (".$rekordy.");";
		mysql_query($query_archiwizuj);
		echo 'Wpisy zostały zarchiwizowane.';
	}elseif(isset($_POST['nie'])){
		header('Location: tabela_wyjazdy.php');
	}else{
		foreach($_GET as $get){
			$id_wpisu = $id_wpisu . $get . ',';
		}
		
		$id_wpisu = substr($id_wpisu,0,-1);
		
		echo '<form action="prints/archiwizacja.php" method="post" target="_blank">
			<input type="hidden" name="rekordy" value="'.$id_wpisu.'" />
			<input type="submit" value="Drukuj" name="wyslij" />
		</form><br />';		
		
		echo '<form action="tabela_wyjazdy_archiwizuj.php" method="post">
			Czy na pewno chcesz zarchiwizować tą tabelę?
			<input type="hidden" name="rekordy" value="'.$id_wpisu.'" />
			<input type="submit" value="tak" name="tak" />
			<input type="submit" value="nie" name="nie" />
		</form>';		
			
		$query = "
			SELECT ptww.*, u.login as login
			FROM panel_tabela_wyjazdy_wpisy ptww 
			JOIN panel_users u ON u.id = ptww.creator_id
			WHERE ptww.id in (".$id_wpisu.")		
		";	
		
		$result = mysql_query($query) or die (mysql_error());
		echo '
	<table width="100%">
		<tr>';
		if ($row_zalogowany['header'] == 'n'){
				echo '<td id="naglowek" rowspan="1">Lfd.<br />Nr.</td>
			<td id="naglowek" rowspan="1">Mitarbeiter</td>
			<td id="naglowek" rowspan="1">Baustelle</td>
			<td id="naglowek" rowspan="1">Auf Stand<br />setzen</td>			
			<td id="naglowek" rowspan="1">freimelden</td>			
			<td id="naglowek" rowspan="1">bestellen</td>	
			<td id="naglowek" rowspan="1">Dienstwagen</td>
			<td id="naglowek" rowspan="1">Bauvorhaben<br />bis</td>			
			<td id="naglowek" rowspan="1">Bauvorhaben<br />zum</td>	
			<td id="naglowek" rowspan="1">Bemerkungen</td>			
			<td id="naglowek" rowspan="1" width="75px">Tag der<br />Eintragung</td>			
			<td id="naglowek" rowspan="1">LOGIN</td>			
				';
		}else{
				echo '<td id="naglowek" rowspan="1">LP</td>
			<td id="naglowek" rowspan="1">PRACOWNIK</td>
			<td id="naglowek" rowspan="1">BUDOWA</td>
			<td id="naglowek" rowspan="1">ZAWIESIĆ</td>			
			<td id="naglowek" rowspan="1">ZDAĆ</td>			
			<td id="naglowek" rowspan="1">ZAMÓWIĆ</td>		
			<td id="naglowek" rowspan="1">SAMOCHÓD</td>
			<td id="naglowek" rowspan="1">BUDOWA OD</td>			
			<td id="naglowek" rowspan="1">BUDOWA DO</td>						
			<td id="naglowek" rowspan="1">UWAGI</td>			
			<td id="naglowek" rowspan="1" width="75px">DATA WPISU</td>			
			<td id="naglowek" rowspan="1">LOGIN</td>			
			';
			}

		echo '</tr>';
	$lp = 1;
	$wyniki = array();
	
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
			SELECT ptwp.*
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
		echo '			
				<td>'.$lp.'</td>
				<td>';				
				while($row_lista_pracownikow = mysql_fetch_array($result_lista_pracownikow,MYSQL_ASSOC)){
					echo $lp_prac++.'. <b>'.nl2br($row_lista_pracownikow['nazwisko']).' '.nl2br($row_lista_pracownikow['imie']).'</b> ('.$row_lista_pracownikow['kontakt'].')<br />';
				}		
				echo '<br />';	
				echo '</td>
				<td valign="top"><br /><table width="100%"><tr><td> </td></tr>';						
				while($row_lista_budow = mysql_fetch_array($result_lista_budow,MYSQL_ASSOC)){
					echo '<tr><td style="background-color:'.$row_lista_budow['kolor'].';">'.$lp_budow++.'. '.nl2br($row_lista_budow['budowa']).'</td>';
					echo '</tr>';
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
				<td>'.substr($row['creation_date'], 0, 10).'</td>				
				<td>'.$row['login'].'</td>
				';				
			echo '</tr>
		';
		$lp++;
		
		array_push($wyniki,$row['id']);
	}
	echo '</table>';
	}
		
	
	if($_SESSION['user_type'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_wyjazdy.php'.$filters_path2.'"><button>Powrót</button></a><br /><br />';
		}else{
			echo '<a href="tabela_wyjazdy.php?'.$wyniki.'"><button>Powrót</button></a><br /><br />';
		}
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');