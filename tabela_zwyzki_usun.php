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
	
	//TLUMACZENIE
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
				echo '<td><h2>Arbeitsbühnen - den Eintrag löschen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Zwyżki - usuwanie wpisu</h2></td><td width="20px" />';			
			}
			
			require_once('legenda_zwyzki.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['usun'])){
		
		$query_delete = "DELETE FROM panel_tabela_zwyzki WHERE id = ".$id.";";
		mysql_query($query_delete);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_zwyzki_usunieto.php'.$filters_path);
		
	}elseif(isset($_POST['nie_usuwaj'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
	
		header('Location: tabela_zwyzki.php'.$filters_path);
	}else{
		echo '<form action="tabela_zwyzki_usun.php?id='.$id.$filters_path.'" method="post">	
		<table>';
		if(isset($_SESSION['user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
		}elseif(isset($_COOKIE['ciastko_user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
		}
		$result_header = mysql_query($query_header) or die (mysql_error());
		$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
		if($row_header['header'] == 'n'){
			echo '
			<tr>
				<td id="naglowek" rowspan="2">Bauvorhaben</td>
				<td id="naglowek" rowspan="2">Vermieter</td>
				<td id="naglowek" rowspan="2">Bühnentyp</td>
				<td id="naglowek" rowspan="2">Arbeitshöhe</td>		
				<td id="naglowek" rowspan="2">Mietpreis</td>
				<td id="naglowek" rowspan="2">MaschinenNr.</td>
				<td id="naglowek" colspan="2">Mietzeitraum</td>
				<td id="naglowek" rowspan="2">auf Stand gesetzt bis zum</td>
				<td id="naglowek" rowspan="2">Standtage</td>
				<td id="naglowek" rowspan="2">Freimeldung</td>
				<td id="naglowek" rowspan="2">Bemerkungen</td>
				<td id="naglowek" rowspan="2">Ansprechpartner</td>
				<td id="naglowek" rowspan="2">Datum der Eintragung</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
				<td id="naglowek" colspan="4">Rechnung I</td>
				<td id="naglowek" colspan="2">Gutschrift</td>				
			</tr>
			<tr>
				<td id="naglowek">vom</td>
				<td id="naglowek">bis zum</td>
				<td id="naglowek">Nr</td>
				<td id="naglowek">Abrechnungs Zeitraum</td>
				<td id="naglowek">Betrag</td>
				<td id="naglowek">Bemerkungen</td>
				<td id="naglowek">Nr</td>
				<td id="naglowek">Betrag</td>
				<td id="naglowek">Nr</td>
			</tr>
			';
		}else{
			echo '
			<tr>
				<td id="naglowek" rowspan="2">BUDOWA</td>
				<td id="naglowek" rowspan="2">FIRMA WYNAJMUJĄCA</td>
				<td id="naglowek" rowspan="2">RODZAJ ZWYŻKI</td>
				<td id="naglowek" rowspan="2">WYSOKOŚĆ</td>		
				<td id="naglowek" rowspan="2">CENA</td>
				<td id="naglowek" rowspan="2">NR MASZYNY</td>
				<td id="naglowek" colspan="2">DATA WYNAJMU</td>
				<td id="naglowek" rowspan="2">ZAWIESZONE DO</td>
				<td id="naglowek" rowspan="2">DATA ZAWIESZENIA</td>
				<td id="naglowek" rowspan="2">DATA ZDANIA</td>
				<td id="naglowek" rowspan="2">UWAGI</td>
				<td id="naglowek" rowspan="2">OSOBA KONTAKTOWA</td>
				<td id="naglowek" rowspan="2">DATA WPISU</td>
				<td id="naglowek" rowspan="2">LOGIN</td>	
				<td id="naglowek" colspan="4">RACHUNKI I</td>
				<td id="naglowek" colspan="2">KOREKTY</td>
			</tr>
			<tr>
				<td id="naglowek">OD</td>
				<td id="naglowek">DO</td>
				<td id="naglowek">NR</td>
				<td id="naglowek">OKR. ROZ.</td>
				<td id="naglowek">KWOTA</td>
				<td id="naglowek">UWAGI</td>
				<td id="naglowek">NR</td>
				<td id="naglowek">KWOTA</td>
				<td id="naglowek">NR</td>
			</tr>
			';
		}
		
		$query = "SELECT * FROM panel_tabela_zwyzki WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '
				<tr '.kolor_wiersza_zwyzki($row['data_zdania'], $row['zawieszone_do'], $row['zaznaczony_wiersz'], $row['oferta']).'>
					<td>'.nl2br($row['budowa']).'</td>
					<td>'.nl2br($row['firma_wynajmujaca']).'</td>					
					<td>'.nl2br($row['rodzaj_zwyzki']).'</td>
					<td>'.nl2br($row['wysokosc']).'</td>
					<td>'.nl2br($row['cena']).'</td>
					<td>'.nl2br($row['nr_maszyny']).'</td>
					<td>'.nl2br($row['data_wynajmu_od']).'</td>
					<td>'.nl2br($row['data_wynajmu_do']).'</td>
					<td>'.nl2br($row['zawieszone_do']).'</td>
					<td>'.nl2br($row['data_zawiesz']).'</td>
					<td>'.nl2br($row['data_zdania']).'</td>				
					<td>'.nl2br($row['uwagi']).'</td>
					<td>'.nl2br($row['os_kontaktowa']).'</td>
					<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row_login['login'].'</td>
					<td>'.$row['rach_1_nr'].'</td>				
					<td>'.$row['rach_1_okres'].'</td>				
					<td>'.$row['rach_1_kwota'].'</td>				
					<td>'.$row['rach_1_uwagi'].'</td>				
					<td>'.$row['korekta_nr'].'</td>				
					<td>'.$row['korekta_kwota'].'</td>			
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