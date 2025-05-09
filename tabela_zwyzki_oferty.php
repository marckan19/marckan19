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
				echo '<td><h2>Arbeitsbühnen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Zwyżki</h2></td><td width="20px" />';			
			}

			require_once('legenda_zwyzki.php');
		echo '</tr>
	</table>';
	
	require 'tabela_zwyzki_filtry.php';
	if (isset($_POST['filtry_szukaj'])){
	
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_zwyzki ptz
					JOIN panel_users u ON u.id = ptz.creator_id
			WHERE 
			ptz.budowa like '%".$_POST['filtry_budowa']."%' &&
			ptz.firma_wynajmujaca like '%".$_POST['filtry_firma_wynajmujaca']."%' &&
			ptz.rodzaj_zwyzki like '%".$_POST['filtry_rodzaj_zwyzki']."%' &&
			ptz.wysokosc like '%".$_POST['filtry_wysokosc']."%' &&
			ptz.cena like '%".$_POST['filtry_cena']."%' &&
			ptz.nr_maszyny like '%".$_POST['filtry_nr_maszyny']."%' &&
			ptz.data_wynajmu_od like '%".$_POST['filtry_data_wynajmu_od']."%' &&
			ptz.data_wynajmu_do like '%".$_POST['filtry_data_wynajmu_do']."%' &&
			ptz.zawieszone_do like '%".$_POST['filtry_zawieszone_do']."%' &&
			ptz.data_zawiesz like '%".$_POST['filtry_data_zawiesz']."%' &&
			ptz.data_zdania like '%".$_POST['filtry_data_zdania']."%' &&
			ptz.uwagi like '%".$_POST['filtry_uwagi']."%' &&
			ptz.os_kontaktowa like '%".$_POST['filtry_os_kontaktowa']."%' &&
			ptz.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			u.login like '%".$_POST['filtry_login']."%' &&			
			ptz.rach_1_nr like '%".$_POST['filtry_rach_1_nr']."%' &&
			ptz.rach_1_okres like '%".$_POST['filtry_rach_1_okres']."%' &&
			ptz.rach_1_kwota like '%".$_POST['filtry_rach_1_kwota']."%' &&
			ptz.rach_1_uwagi like '%".$_POST['filtry_rach_1_uwagi']."%' &&
			ptz.korekta_nr like '%".$_POST['filtry_korekta_nr']."%' &&
			ptz.korekta_kwota like '%".$_POST['filtry_korekta_kwota']."%'
		;";
		
		$filters_path = '&budowa='.$_POST['filtry_budowa'].'
						&firma_wynajmujaca='.$_POST['firma_wynajmujaca'].'
						&rodzaj_zwyzki='.$_POST['rodzaj_zwyzki'].'
						&wysokosc='.$_POST['wysokosc'].'
						&cena='.$_POST['cena'].'
						&nr_maszyny='.$_POST['nr_maszyny'].'
						&data_wynajmu_od='.$_POST['data_wynajmu_od'].'
						&data_wynajmu_do='.$_POST['data_wynajmu_do'].'
						&zawieszone_do='.$_POST['zawieszone_do'].'
						&data_zawiesz='.$_POST['data_zawiesz'].'
						&data_zdania='.$_POST['data_zdania'].'
						&uwagi='.$_POST['uwagi'].'
						&os_kontaktowa='.$_POST['os_kontaktowa'].'
						&creation_date='.$_POST['creation_date'].'
						&login='.$_POST['login'].'
						&rach_1_nr='.$_POST['rach_1_nr'].'
						&rach_1_okres='.$_POST['rach_1_okres'].'
						&rach_1_kwota='.$_POST['rach_1_kwota'].'
						&rach_1_uwagi='.$_POST['rach_1_uwagi'].'
						&korekta_nr='.$_POST['korekta_nr'].'
						&korekta_kwota='.$_POST['korekta_kwota'];
		
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_zwyzki ptz
					JOIN panel_users u ON u.id = ptz.creator_id
			WHERE 
			ptz.budowa like '%".$_GET['budowa']."%' &&
			ptz.firma_wynajmujaca like '%".$_GET['firma_wynajmujaca']."%' &&
			ptz.rodzaj_zwyzki like '%".$_GET['rodzaj_zwyzki']."%' &&
			ptz.wysokosc like '%".$_GET['wysokosc']."%' &&
			ptz.cena like '%".$_GET['cena']."%' &&
			ptz.nr_maszyny like '%".$_GET['nr_maszyny']."%' &&
			ptz.data_wynajmu_od like '%".$_GET['data_wynajmu_od']."%' &&
			ptz.data_wynajmu_do like '%".$_GET['data_wynajmu_do']."%' &&
			ptz.zawieszone_do like '%".$_GET['zawieszone_do']."%' &&
			ptz.data_zawiesz like '%".$_GET['data_zawiesz']."%' &&
			ptz.data_zdania like '%".$_GET['data_zdania']."%' &&
			ptz.uwagi like '%".$_GET['uwagi']."%' &&
			ptz.os_kontaktowa like '%".$_GET['os_kontaktowa']."%' &&
			ptz.creation_date like '%".$_GET['data_wpisu']."%' &&
			u.login like '%".$_GET['flogin']."%' &&			
			ptz.rach_1_nr like '%".$_GET['rach_1_nr']."%' &&
			ptz.rach_1_okres like '%".$_GET['rach_1_okres']."%' &&
			ptz.rach_1_kwota like '%".$_GET['rach_1_kwota']."%' &&
			ptz.rach_1_uwagi like '%".$_GET['rach_1_uwagi']."%' &&
			ptz.korekta_nr like '%".$_GET['korekta_nr']."%' &&
			ptz.korekta_kwota like '%".$_GET['korekta_kwota']."%'
			;";
		
		$filters_path = '&budowa='.$_GET['budowa'].'
						&firma_wynajmujaca='.$_GET['firma_wynajmujaca'].'
						&rodzaj_zwyzki='.$_GET['rodzaj_zwyzki'].'
						&wysokosc='.$_GET['wysokosc'].'
						&cena='.$_GET['cena'].'
						&nr_maszyny='.$_GET['nr_maszyny'].'
						&data_wynajmu_od='.$_GET['data_wynajmu_od'].'
						&data_wynajmu_do='.$_GET['data_wynajmu_do'].'
						&zawieszone_do='.$_GET['zawieszone_do'].'
						&data_zawiesz='.$_GET['data_zawiesz'].'
						&data_zdania='.$_GET['data_zdania'].'
						&uwagi='.$_GET['uwagi'].'
						&os_kontaktowa='.$_GET['os_kontaktowa'].'
						&creation_date='.$_GET['creation_date'].'
						&login='.$_GET['login'].'						
						&rach_1_nr='.$_GET['rach_1_nr'].'
						&rach_1_okres='.$_GET['rach_1_okres'].'
						&rach_1_kwota='.$_GET['rach_1_kwota'].'
						&rach_1_uwagi='.$_GET['rach_1_uwagi'].'
						&korekta_nr='.$_GET['korekta_nr'].'
						&korekta_kwota='.$_GET['korekta_kwota'];
	}else{
		$query = "SELECT * FROM panel_tabela_zwyzki WHERE oferta = 1;";
	}
		
	$result = mysql_query($query) or die (mysql_error());
	
	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
	
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		if($row_header['id'] != 16){ //Nie widzi user PDG
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_zwyzki_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a><br /><br />';
		}else{
			echo '<a href="tabela_zwyzki_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a><br /><br />';
		}
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	echo '<div>
	<table>
		<tr>	';	
	
	if($row_header['header'] == 'n'){
		echo '
			<td id="naglowek" rowspan="2" width="10px">lfd. Nr.</td>
			<td id="naglowek" rowspan="2" width="90px">Bauvorhaben</td>
			<td id="naglowek" rowspan="2" width="70px">Vermieter</td>
			<td id="naglowek" rowspan="2" width="70px">Bühnentyp</td>
			<td id="naglowek" rowspan="2" width="70px">Arbeitshöhe</td>		
			<td id="naglowek" rowspan="2" width="85px">Mietpreis</td>
			<td id="naglowek" rowspan="2" width="70px">MaschinenNr.</td>
			<td id="naglowek" colspan="2">Mietzeitraum</td>
			<td id="naglowek" rowspan="2" width="85px">auf Stand gesetzt bis zum</td>
			<td id="naglowek" rowspan="2" width="85px">Standtage</td>
			<td id="naglowek" rowspan="2" width="85px">Freimeldung</td>
			<td id="naglowek" rowspan="2" width="150px">Bemerkungen</td>
			<td id="naglowek" rowspan="2" width="100px">Ansprechpartner</td>
			<td id="naglowek" rowspan="2" width="85px">Datum der Eintragung</td>
			<td id="naglowek" rowspan="2" width="60px">LOGIN</td>		
			<td id="naglowek" colspan="4" width="60px">Rechnung I</td>
			<td id="naglowek" colspan="2" width="60px">Gutschrift</td>
		';
	}else{
		echo '
			<td id="naglowek" rowspan="2" width="10px">LP</td>
			<td id="naglowek" rowspan="2" width="90px">BUDOWA</td>
			<td id="naglowek" rowspan="2" width="70px">FIRMA WYNAJM.</td>
			<td id="naglowek" rowspan="2" width="70px">RODZAJ ZWYŻKI</td>
			<td id="naglowek" rowspan="2" width="70px">WYSOK.</td>		
			<td id="naglowek" rowspan="2" width="85px">CENA</td>
			<td id="naglowek" rowspan="2" width="70px">NR MASZYNY</td>
			<td id="naglowek" colspan="2">DATA WYNAJMU</td>
			<td id="naglowek" rowspan="2" width="85px">ZAWIESZ. DO</td>
			<td id="naglowek" rowspan="2" width="85px">DATA ZAWIESZ.</td>
			<td id="naglowek" rowspan="2" width="85px">DATA ZDANIA</td>
			<td id="naglowek" rowspan="2" width="150px">UWAGI</td>
			<td id="naglowek" rowspan="2" width="100px">OSOBA KONTAKTOWA</td>
			<td id="naglowek" rowspan="2" width="85px">DATA WPISU</td>
			<td id="naglowek" rowspan="2" width="60px">LOGIN</td>	
			<td id="naglowek" colspan="4" width="60px">RACHUNKI I</td>
			<td id="naglowek" colspan="2" width="60px">KOREKTY</td>
		';
	}
		if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		if($row_header['id'] != 16){ //Nie widzi user PDG
				echo'
					<td id="naglowek" rowspan="2" width="20px">E</td>
					<td id="naglowek" rowspan="2" width="20px">K</td>
					<td id="naglowek" rowspan="2" width="20px">U</td>';
			} 
			} 
		if($row_header['header'] == 'n'){
			echo '</tr>
			<tr>
				<td id="naglowek" width="95px">vom</td>
				<td id="naglowek" width="95px">bis zum</td>
				<td id="naglowek" width="110px">Nr</td>
				<td id="naglowek" width="110px">Abrechnungs Zeitraum</td>
				<td id="naglowek" width="110px">Betrag</td>
				<td id="naglowek" width="110px">Bemerkungen</td>
				<td id="naglowek" width="110px">Nr</td>
				<td id="naglowek" width="110px">Betrag</td>
				<td id="naglowek" width="110px">Nr</td>
			</tr>
			';
		}else{
			echo '</tr>
			<tr>
				<td id="naglowek" width="95px">OD</td>
				<td id="naglowek" width="95px">DO</td>
				<td id="naglowek" width="95px">NR</td>
				<td id="naglowek" width="95px">OKR. ROZ.</td>
				<td id="naglowek" width="95px">KWOTA</td>
				<td id="naglowek" width="95px">UWAGI</td>
				<td id="naglowek" width="95px">NR</td>
				<td id="naglowek" width="95px">KWOTA</td>
				<td id="naglowek" width="95px">NR</td>
			</tr>
			';
		}
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		
		
		echo '<tr '.kolor_wiersza_zwyzki($row['data_zdania'], $row['zawieszone_do'], $row['zaznaczony_wiersz'], $row['oferta']).'>';
		echo '			
				<td>'.$lp.'</td>
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
				<td>'.nl2br($row['rach_1_nr']).'</td>
				<td>'.nl2br($row['rach_1_okres']).'</td>
				<td>'.nl2br($row['rach_1_kwota']).'</td>
				<td>'.nl2br($row['rach_1_uwagi']).'</td>
				<td>'.nl2br($row['korekta_nr']).'</td>
				<td>'.nl2br($row['korekta_kwota']).'</td>
				';

				if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
				if($row_header['id'] != 16){ //Nie widzi user PDG
					echo '<td id="komorka_edycja"><a href="tabela_zwyzki_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						<td id="komorka_kopiuj"><a href="tabela_zwyzki_kopiuj.php?id='.$row['id'].$filters_path.'"><button>K</button></a></td>
						<td id="komorka_usun"><a href="tabela_zwyzki_usun.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
				}
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';
	

	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		if($row_header['id'] != 16){ //Nie widzi user PDG
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_zwyzki_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a>';
		}else{
			echo '<br /><a href="tabela_zwyzki_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a>';
		}
		}
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');