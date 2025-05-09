<?php
ob_start();
session_start();
require_once('header.php');

//if(isset($_SESSION['user_id'])){
if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){
	require_once('menu.php');
			
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Unterkunft</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Noclegi</h2></td><td width="20px" />';			
			}
	
			require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
	require 'tabela_noclegi_filtry.php';
	if (isset($_POST['filtry_szukaj'])){
	
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptn.* FROM panel_tabela_noclegi ptn
					JOIN panel_users u ON u.id = ptn.creator_id
			WHERE 
			ptn.budowa like '%".$_POST['filtry_budowa']."%' &&			
			ptn.firma like '%".$_POST['filtry_firma']."%' &&			
			ptn.ilosc_osob_mieszka like '%".$_POST['filtry_ilosc_osob_mieszka']."%' &&
			ptn.okres_wynajmu_od like '%".$_POST['filtry_okres_wynajmu_od']."%' &&
			ptn.okres_wynajmu_do like '%".$_POST['filtry_okres_wynajmu_do']."%' &&
			ptn.cena like '%".$_POST['filtry_cena']."%' &&
			ptn.placone like '%".$_POST['filtry_placone']."%' &&
			ptn.uwagi like '%".$_POST['filtry_uwagi']."%' &&
			ptn.os_kontaktowa like '%".$_POST['filtry_os_kontaktowa']."%' &&
			ptn.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			u.login like '%".$_POST['filtry_login']."%'
		;";
		
		$filters_path = '&budowa='.$_POST['filtry_budowa'].'
						&firma='.$_POST['filtry_firma'].'						
						&ilosc_osob_mieszka='.$_POST['filtry_ilosc_osob_mieszka'].'
						&okres_wynajmu_od='.$_POST['filtry_okres_wynajmu_od'].'
						&okres_wynajmu_do='.$_POST['filtry_okres_wynajmu_do'].'
						&cena='.$_POST['filtry_cena'].'
						&placone='.$_POST['filtry_placone'].'
						&uwagi='.$_POST['filtry_uwagi'].'
						&os_kontaktowa='.$_POST['filtry_os_kontaktowa'].'
						&creation_date='.$_POST['filtry_data_wpisu'].'						
						&login='.$_POST['filtry_login'];
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_noclegi ptz
					JOIN panel_users u ON u.id = ptz.creator_id
			WHERE 
			ptz.budowa like '%".$_GET['budowa']."%' &&
			ptz.firma like '%".$_GET['firma']."%' &&			
			ptz.ilosc_osob_mieszka like '%".$_GET['ilosc_osob_mieszka']."%' &&
			ptz.okres_wynajmu_od like '%".$_GET['okres_wynajmu_od']."%' &&
			ptz.okres_wynajmu_do like '%".$_GET['okres_wynajmu_od']."%' &&
			ptz.cena like '%".$_GET['cena']."%' &&
			ptz.placone like '%".$_GET['placone']."%' &&
			ptz.uwagi like '%".$_GET['uwagi']."%' &&
			ptz.os_kontaktowa like '%".$_GET['os_kontaktowa']."%' &&			
			ptz.creation_date like '%".$_GET['data_wpisu']."%' &&
			u.login like '%".$_GET['flogin']."%'
		;";
		
		$filters_path = '&budowa='.$_GET['budowa'].'
						&firma='.$_GET['firma'].'						
						&ilosc_osob_mieszka='.$_GET['ilosc_osob_mieszka'].'
						&okres_wynajmu_od='.$_GET['okres_wynajmu_od'].'
						&okres_wynajmu_do='.$_GET['okres_wynajmu_do'].'
						&cena='.$_GET['cena'].'
						&placone='.$_GET['placone'].'
						&uwagi='.$_GET['uwagi'].'
						&os_kontaktowa='.$_GET['os_kontaktowa'].'
						&creation_date='.$_GET['data_wpisu'].'						
						&login='.$_GET['login'];
	}else{
		$query = "SELECT * FROM panel_tabela_noclegi WHERE zaznaczony_wiersz = 1 ;";
	}
	
	$result = mysql_query($query) or die (mysql_error());
	
	//if($_SESSION['user_type'] == 'a'){
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		if($row_header['id'] != 16){ //Nie widzi user PDG
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_noclegi_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a><br /><br />';
		}else{
			echo '<a href="tabela_noclegi_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a><br /><br />';
		}
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	echo '<div>
	<table width="100%">
		<tr>		';
	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
	
	if($row_header['header'] == 'n'){
		echo '
			<td id="naglowek" rowspan="2">lfd. Nr.</td>
			<td id="naglowek" rowspan="2">Bauvorhaben</td>
			<td id="naglowek" rowspan="2">Vermieter</td>
			<td id="naglowek" rowspan="2">Anzhal der Personen</td>			
			<td id="naglowek" colspan="2">Mietzeitraum</td>
			<td id="naglowek" rowspan="2">Preis</td>
			<td id="naglowek" rowspan="2" width="75px">Info. zur Zahlung</td>
			<td id="naglowek" rowspan="2" width="100px">Bemerkungen</td>
			<td id="naglowek" rowspan="2" width="100px">Ansprechpartner</td>
			<td id="naglowek" rowspan="2" width="75px">Datum der Eintragung</td>
			<td id="naglowek" rowspan="2">LOGIN</td>
		';
	}else{
		echo '
			<td id="naglowek" rowspan="2">LP</td>
			<td id="naglowek" rowspan="2">BUDOWA</td>
			<td id="naglowek" rowspan="2">FIRMA</td>
			<td id="naglowek" rowspan="2">L. OS. <br />MIESZ.</td>			
			<td id="naglowek" colspan="2">OKRES WYNAJMU</td>
			<td id="naglowek" rowspan="2">CENA</td>
			<td id="naglowek" rowspan="2" width="75px">PŁACONE</td>
			<td id="naglowek" rowspan="2" width="100px">UWAGI</td>
			<td id="naglowek" rowspan="2" width="100px">OSOBA KONTAKTOWA</td>
			<td id="naglowek" rowspan="2" width="75px">DATA WPISU</td>
			<td id="naglowek" rowspan="2">LOGIN</td>
		';
	}
			//if($_SESSION['user_type'] == 'a'){
			if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
			if($row_header['id'] != 16){ //Nie widzi user PDG
				echo'
					<td id="naglowek" rowspan="2">E</td>
					<td id="naglowek" rowspan="2">K</td>
					<td id="naglowek" rowspan="2">U</td>';
			} 
			} 
		echo '</tr>';
		if($row_header['header'] == 'n'){
			echo '
				<tr>
					<td id="naglowek" width="75px">vom</td>
					<td id="naglowek" width="75px">bis zum</td>
				</tr>
			';
		}else{
			echo '
				<tr>
					<td id="naglowek" width="75px">OD</td>
					<td id="naglowek" width="75px">DO</td>
				</tr>
			';
		}
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_noclegi($row['okres_wynajmu_do'], $row['zaznaczony_wiersz']).'>';
		echo '			
				<td>'.$lp.'</td>
				<td>'.nl2br($row['budowa']).'</td>
				<td>'.nl2br($row['firma']).'</td>
				<td>'.nl2br($row['ilosc_osob_mieszka']).'</td>				
				<td>'.nl2br($row['okres_wynajmu_od']).'</td>				
				<td>'.nl2br($row['okres_wynajmu_do']).'</td>				
				<td>'.nl2br($row['cena']).'</td>
				<td>'.nl2br($row['placone']).'</td>				
				<td>'.nl2br($row['uwagi']).'</td>
				<td>'.nl2br($row['os_kontaktowa']).'</td>
				<td>'.substr($row['creation_date'], 0, 10).'</td>
				<td>'.$row_login['login'].'</td>
				';
				//if($_SESSION['user_type'] == 'a'){
				if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
				if($row_header['id'] != 16){ //Nie widzi user PDG
					echo '<td id="komorka_edycja"><a href="tabela_noclegi_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						<td id="komorka_kopiuj"><a href="tabela_noclegi_kopiuj.php?id='.$row['id'].$filters_path.'"><button>K</button></a></td>
						<td id="komorka_usun"><a href="tabela_noclegi_usun.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
				}
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';
	//if($_SESSION['user_type'] == 'a'){
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		if($row_header['id'] != 16){ //Nie widzi user PDG
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_noclegi_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a>';
		}else{
			echo '<br /><a href="tabela_noclegi_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a>';
		}
		}
	}
	//echo '</div>';
}else{
	require_once('logout.php');
}


require_once('footer.php');