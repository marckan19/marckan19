<?php
ob_start();
session_start();
require_once('header.php');

//if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a'){
if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	require_once('menu.php');
	
	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
		//$filters_path = '?'.substr($filters_path,1);
	}else{
		$filters_path = '';
	}
	
	echo '
	<table>
		<tr>';
			//<td><h2>Noclegi - dodawanie wpisu</h2></td><td width="20px" />';			
			if($row_header['header'] == 'n'){
				echo '<td><h2>Unterkunft - neuen Eintrag erstellen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Noclegi - dodawanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_noclegi.php'.$filters_path);
	}elseif(isset($_POST['dodaj'])){
		
		$budowa = $_POST['budowa'];
		$firma = $_POST['firma'];					
		$ilosc_osob_mieszka = $_POST['ilosc_osob_mieszka'];		
		$cena = $_POST['cena'];
		$placone = $_POST['placone'];
		if($_POST['czy_okres_wynajmu_od'] ==1){
			$okres_wynajmu_od = $_POST['okres_wynajmu_od'];
		}
		if($_POST['czy_okres_wynajmu_do'] == 1){
			$okres_wynajmu_do = $_POST['okres_wynajmu_do'];
		}
		$uwagi = $_POST['uwagi'];
		$os_kontaktowa = $_POST['os_kontaktowa'];
		$creation_date = date('Y-m-d G:i:s');
		//$creator_id = $_SESSION['user_id'];
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}
		if($_POST['zaznaczony_wiersz'] == 1){
			$zaznaczony_wiersz = 1;
		}else{
			$zaznaczony_wiersz = 0;
		}
		
		$query_insert = "INSERT INTO panel_tabela_noclegi (budowa, firma, ilosc_osob_mieszka, okres_wynajmu_od, okres_wynajmu_do, cena, placone, uwagi, os_kontaktowa, creator_id, creation_date, modificator_id, modification_date, zaznaczony_wiersz)
						VALUES (
							'".$budowa."', 
							'".$firma."', 						
							'".$ilosc_osob_mieszka."', 							 							
							'".$okres_wynajmu_od."', 							 							
							'".$okres_wynajmu_do."', 							 							
							'".$cena."', 
							'".$placone."', 							
							'".$uwagi."', 
							'".$os_kontaktowa."',
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."',
							".$zaznaczony_wiersz."
						);";
		mysql_query($query_insert);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
			$filters_path = '&'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_noclegi_dodano.php?id='.mysql_insert_id().$filters_path);
		
	}else{
		echo '<form action="tabela_noclegi_dodaj.php'.$filters_path.'" method="post">	
		<table width="100%">';
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
						<td id="naglowek" rowspan="2">Anzhal der Personen</td>
						<td id="naglowek" colspan="2">Mietzeitraum</td>												
						<td id="naglowek" rowspan="2">Preis</td>
						<td id="naglowek" rowspan="2">Info. zur Zahlung</td>			
						<td id="naglowek" rowspan="2">Bemerkungen</td>
						<td id="naglowek" rowspan="2">Ansprechpartner</td>
						<td id="naglowek" rowspan="2">Z</td>
					</tr>
					<tr>				
						<td id="naglowek">vom <input type="checkbox" name="czy_okres_wynajmu_od" value="1" /></td>
						<td id="naglowek">bis zum <input type="checkbox" name="czy_okres_wynajmu_do" value="1" /></td>
					</tr>
				';
			}else{
				echo '
					<tr>
						<td id="naglowek" rowspan="2">BUDOWA</td>
						<td id="naglowek" rowspan="2">FIRMA</td>								
						<td id="naglowek" rowspan="2">LICZBA OSÓB MIESZKA</td>
						<td id="naglowek" colspan="2">OKRES WYNAJMU</td>												
						<td id="naglowek" rowspan="2">CENA</td>
						<td id="naglowek" rowspan="2">PLACONE</td>			
						<td id="naglowek" rowspan="2">UWAGI</td>
						<td id="naglowek" rowspan="2">OSOBA KONTAKTOWA</td>
						<td id="naglowek" rowspan="2">Z</td>
					</tr>
					<tr>				
						<td id="naglowek">OD <input type="checkbox" name="czy_okres_wynajmu_od" value="1" /></td>
						<td id="naglowek">DO <input type="checkbox" name="czy_okres_wynajmu_do" value="1" /></td>
					</tr>
				';
			}
		echo '<tr '.kolor_wiersza_noclegi($row['okres_wynajmu_do'], $row['zaznaczony_wiersz']).'>';
		echo '		
				<td id="komorka_dodawanie"><textarea name="budowa" cols="18" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="firma" cols="15" rows="2"></textarea></td>				
				<td id="komorka_dodawanie"><textarea name="ilosc_osob_mieszka" cols="3" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><script>DateInput(\'okres_wynajmu_od\', true, \'YYYY-MM-DD\')</script></td>				
				<td id="komorka_dodawanie"><script>DateInput(\'okres_wynajmu_do\', true, \'YYYY-MM-DD\')</script></td>				
				<td id="komorka_dodawanie"><textarea name="cena" cols="3" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="placone" cols="3" rows="2"></textarea></td>				
				<td id="komorka_dodawanie"><textarea name="uwagi" cols="20" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="os_kontaktowa" cols="10" rows="2"></textarea></td>	
				<td id="komorka_dodawanie"><input type="checkbox" name="zaznaczony_wiersz" value="1" /></td>	
			</tr>
		';

		echo '</table><br />';
		
		if(isset($_SESSION['user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
		}elseif(isset($_COOKIE['ciastko_user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
		}
		$result_header = mysql_query($query_header) or die (mysql_error());
		$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
	
		if($row_header['header'] == 'n'){
			echo '<input type="submit"  value="neuer Eintrag" name="dodaj" />
			<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Dodaj" name="dodaj" />
			<input type="submit"  value="Powrót" name="powrot" />';
		}
		echo '</form>';	
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');