<?php
ob_start();
session_start();
require_once('header.php');

//if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a'){
if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		$id = 0;
	}
	
	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
		$filters_path = '?'.substr($filters_path,1);
	}else{
		$filters_path = '';
	}
	
	require_once('menu.php');
	
	echo '
	<table>
		<tr>';
			//<td><h2>Noclegi - kopiowanie wpisu</h2></td><td width="20px" />';			
			if($row_header['header'] == 'n'){
				echo '<td><h2>Unterkunft - den Eintrag kopieren</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Noclegi - kopiowanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
		$query = "SELECT * FROM panel_tabela_noclegi WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());	
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '
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
				<td id="naglowek" rowspan="2">E</td>
				<td id="naglowek" rowspan="2">K</td>
				<td id="naglowek" rowspan="2">U</td>
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
				<td id="naglowek" rowspan="2">E</td>
				<td id="naglowek" rowspan="2">K</td>
				<td id="naglowek" rowspan="2">U</td>
			</tr>
			<tr>
				<td id="naglowek">OD <input type="checkbox" name="czy_okres_wynajmu_od" value="1" /></td>
				<td id="naglowek">DO <input type="checkbox" name="czy_okres_wynajmu_do" value="1" /></td>
			</tr>
			';
		}
			echo '
				<tr '.kolor_wiersza_noclegi($row['okres_wynajmu_do'], $row['zaznaczony_wiersz']).'>
					<td>'.nl2br($row['budowa']).'</td>
					<td>'.nl2br($row['firma']).'</td>					
					<td>'.nl2br($row['ilosc_osob_mieszka']).'</td>				
					<td>'.nl2br($row['okres_wynajmu_od']).'</td>				
					<td>'.nl2br($row['okres_wynajmu_od']).'</td>				
					<td>'.nl2br($row['cena']).'</td>
					<td>'.nl2br($row['placone']).'</td>
					<td>'.nl2br($row['uwagi']).'</td>
					<td>'.nl2br($row['os_kontaktowa']).'</td>
					<td>'.substr($row['creation_date'],0 ,10).'</td>
					<td>'.$row_login['login'].'</td>
					<td id="komorka_edycja"><a href="tabela_noclegi_edytuj.php?id='.$row['id'].'"><button>E</button></a></td>
					<td id="komorka_kopiuj"><a href="tabela_noclegi_kopiuj.php?id='.$row['id'].'"><button>K</button></a></td>
					<td id="komorka_usun"><a href="tabela_noclegi_usun.php?id='.$row['id'].'"><button>X</button></a></td>
				</tr>
			</table>';
			
			if($row_header['header'] == 'n'){
				echo '<div id="success">Der Eintrag wurde kopiert</div><br />';
			}else{
				echo '<div id="success">Wpis został skopiowany.</div><br />';
			}
	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
	
	if($row_header['header'] == 'n'){
		echo '<br /><a href="tabela_noclegi.php'.$filters_path.'"><button>zurück</button></a>';
	}else{
		echo '<br /><a href="tabela_noclegi.php'.$filters_path.'"><button>Powrót</button></a>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');