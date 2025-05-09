<?php
ob_start();
session_start();
require_once('header.php');

//if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a'){
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
		
	echo '
	<table>
		<tr>';
			//<td><h2>Noclegi - edytowanie wpisu</h2></td><td width="20px" />';			
			if($row_header['header'] == 'n'){
				echo '<td><h2>Unterkunft - den Eintrag bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Noclegi - edytowanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_noclegi.php'.$filters_path);
	}elseif(isset($_POST['edytuj'])){		
		$budowa = $_POST['budowa'];
		$firma = $_POST['firma'];					
		$ilosc_osob_mieszka = $_POST['ilosc_osob_mieszka'];		
		$cena = $_POST['cena'];
		$placone = $_POST['placone'];
		if($_POST['czy_okres_wynajmu_od'] == 1){
			$okres_wynajmu_od = $_POST['okres_wynajmu_od'];
		}else{
			$okres_wynajmu_od = $_POST['okres_wynajmu_od_stara'];
		}
		if($_POST['czy_okres_wynajmu_do'] == 1){
			$okres_wynajmu_do = $_POST['okres_wynajmu_do'];
		}else{
			$okres_wynajmu_do = $_POST['okres_wynajmu_do_stara'];
		}
		$uwagi = $_POST['uwagi'];
		$os_kontaktowa = $_POST['os_kontaktowa'];
		$modification_date = date('Y-m-d G:i:s');
		//$modificator_id = $_SESSION['user_id'];
		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
		if($_POST['zaznaczony_wiersz'] == 1){
			$zaznaczony_wiersz = 1;
		}else{
			$zaznaczony_wiersz = 0;
		}
		
		$query_update = "UPDATE panel_tabela_noclegi 
						SET
							budowa = '".$budowa."', 
							firma = '".$firma."',
							ilosc_osob_mieszka = '".$ilosc_osob_mieszka."', 
							okres_wynajmu_od = '".$okres_wynajmu_od."', 
							okres_wynajmu_do = '".$okres_wynajmu_do."', 
							cena = '".$cena."', 
							placone = '".$placone."', 
							uwagi = '".$uwagi."', 
							os_kontaktowa = '".$os_kontaktowa."',
							modificator_id = ".$modificator_id.",
							modification_date = '".$modification_date."',
							zaznaczony_wiersz = '".$zaznaczony_wiersz."'
						WHERE id = ".$id."						
						;";
		mysql_query($query_update);
		
		header('Location: tabela_noclegi_edytowano.php?id='.$id.$filters_path);
		
	}else{
		echo '<form action="tabela_noclegi_edytuj.php?id='.$id.$filters_path.'" method="post">	
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
				<td id="naglowek" rowspan="2">Datum der Eintragung</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
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
				<td id="naglowek" rowspan="2">DATA WPISU</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
				<td id="naglowek" rowspan="2">Z</td>
			</tr>
			<tr>
				<td id="naglowek">OD <input type="checkbox" name="czy_okres_wynajmu_od" value="1" /></td>
				<td id="naglowek">DO <input type="checkbox" name="czy_okres_wynajmu_do" value="1" /></td>
			</tr>
			';
		}
		
		$query = "SELECT * FROM panel_tabela_noclegi WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		//SPRAWDZANIE ZAZNACZONEGO WIERSZA
		if ($row['zaznaczony_wiersz'] == 1){
			$zw = 'checked="checked"';
		}else{
			$zw = '';
		}
		
		echo '<tr '.kolor_wiersza_noclegi($row['okres_wynajmu_do'], $row['zaznaczony_wiersz']).'>
				<td id="komorka_edytowanie"><textarea name="budowa" cols="15" rows="2">'.$row['budowa'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="firma" cols="15" rows="2">'.$row['firma'].'</textarea></td>				
				<td id="komorka_edytowanie"><textarea name="ilosc_osob_mieszka" cols="3" rows="2">'.$row['ilosc_osob_mieszka'].'</textarea></td>
				<td id="komorka_edytowanie"><input type="text" name="okres_wynajmu_od_stara" value="'.$row['okres_wynajmu_od'].'" /><br /><script>DateInput(\'okres_wynajmu_od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><input type="text" name="okres_wynajmu_do_stara" value="'.$row['okres_wynajmu_do'].'" /><br /><script>DateInput(\'okres_wynajmu_do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><textarea name="cena" cols="3" rows="2">'.$row['cena'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="placone" cols="3" rows="2">'.$row['placone'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="uwagi" cols="10" rows="2">'.$row['uwagi'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="os_kontaktowa" cols="10" rows="2">'.$row['os_kontaktowa'].'</textarea></td>
				<td id="komorka_edytowanie">'.substr($row['creation_date'], 0, 10).'</td>
				<td id="komorka_edytowanie">'.$row_login['login'].'</td>				
				<td id="komorka_edytowanie"><input type="checkbox" name="zaznaczony_wiersz" value="1" '.$zw.' /></td>				
			</tr>
		';

		echo '</table><br />';
		if($row_header['header'] == 'n'){
			echo '<input type="submit"  value="ändern" name="edytuj" />';
			echo '<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Edytuj" name="edytuj" />';
			echo '<input type="submit"  value="Powrót" name="powrot" />';
		}
			
		echo '</form>';	
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');