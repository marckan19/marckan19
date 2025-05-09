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
				echo '<td><h2>Baustellen - den Eintrag löschen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - usuwanie wpisu</h2></td><td width="20px" />';			
			}
			
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['usun'])){
		
		$query_delete = "DELETE FROM panel_tabela_wyjazdy_budowy_powiazania WHERE wpis_id = ".$id.";";
		mysql_query($query_delete);
		$query_delete = "DELETE FROM panel_tabela_wyjazdy_samochody_powiazania WHERE wpis_id = ".$id.";";
		mysql_query($query_delete);
		$query_delete = "DELETE FROM panel_tabela_wyjazdy_pracownicy_powiazania WHERE wpis_id = ".$id.";";
		mysql_query($query_delete);
		$query_delete = "DELETE FROM panel_tabela_wyjazdy_wpisy WHERE id = ".$id.";";
		mysql_query($query_delete);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_wyjazdy_usunieto.php'.$filters_path);
		
	}elseif(isset($_POST['nie_usuwaj'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
	
		header('Location: tabela_wyjazdy.php'.$filters_path);
	}else{
		require_once('menu_wyjazdy.php');
		echo '<form action="tabela_wyjazdy_usun.php?id='.$id.$filters_path.'" method="post">	
		<table>';
		if($row_header['header'] == 'n'){
				echo '<tr><td id="naglowek">Mitarbeiter</td>
				<!--<td id="naglowek">Kontakt</td>-->
				<td id="naglowek">Baustelle</td>				
				<td id="naglowek">Auf Stand<br />setzen</td>
				<td id="naglowek">freimelden</td>
				<td id="naglowek">bestellen</td>
				<td id="naglowek">Dienstwagen</td>
				<td id="naglowek">Bauvorhaben bis</td>
				<td id="naglowek">Bauvorhaben zum</td>
				<td id="naglowek">Bemerkungen</td>
				<td id="naglowek">Tag der Eintragung</td>
				<td id="naglowek">LOGIN</td>				
			</tr>';
		}else{
			echo '<tr>
				<td id="naglowek">PRACOWNIK</td>
				<!--<td id="naglowek">KONTAKT</td>-->
				<td id="naglowek">BUDOWA</td>								
				<td id="naglowek">ZAWIESIĆ</td>				
				<td id="naglowek">ZDAĆ</td>				
				<td id="naglowek">ZAMÓWIĆ</td>		
				<td id="naglowek">SAMOCHÓD</td>
				<td id="naglowek">BUDOWA OD</td>
				<td id="naglowek">BUDOWA DO</td>				
				<td id="naglowek">UWAGI</td>				
				<td id="naglowek">DATA WPISU</td>
				<td id="naglowek">LOGIN</td>
			</tr>';
		}
		
		
			$query = "
			SELECT ptww.*, u.login
			FROM panel_tabela_wyjazdy_wpisy ptww 
			JOIN panel_users u ON u.id = ptww.creator_id
			WHERE ptww.id = ".$id."			
			LIMIT 1";

			
			
			
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		
		//echo $row['id'];
		
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
					ORDER BY ptwb.budowa;";
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
		
		//WYCIAGNIECIE KOLORU BUDOWY
				$query_kolor = "
						SELECT ptwb.*
						FROM panel_tabela_wyjazdy_budowy  ptwb			
						JOIN panel_tabela_wyjazdy_budowy_powiazania ptwbp ON ptwbp.budowa_id = ptwb.id		
						WHERE ptwbp.wpis_id = ".$id." AND ptwbp.is_main = 1						
						LIMIT 1;";
				$result_kolor = mysql_query($query_kolor) or die (mysql_error());
				$row_kolor = mysql_fetch_array($result_kolor,MYSQL_ASSOC);
			
		echo '
				<tr '.kolor_wiersza_wyjazdy($row_kolor['kolor']).'>
					<td>';						
				while($row_lista_pracownikow = mysql_fetch_array($result_lista_pracownikow,MYSQL_ASSOC)){
					echo $lp_prac++.'. <b>'.nl2br($row_lista_pracownikow['nazwisko']).' '.nl2br($row_lista_pracownikow['imie']).'</b> ('.$row_lista_pracownikow['kontakt'].')<br />';
				}				
				echo '</td>
				<td valign="top"><br /><table width="100%"><tr><td> </td></tr>';								
				while($row_lista_budow = mysql_fetch_array($result_lista_budow,MYSQL_ASSOC)){
					echo '<tr><td style="background-color:'.$row_lista_budow['kolor'].';">'.$lp_budow++.'. '.nl2br($row_lista_budow['budowa']).'</td></tr>';
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