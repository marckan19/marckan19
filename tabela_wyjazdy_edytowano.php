<?php
ob_start();
session_start();
require_once('header.php');

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
			if($row_header['header'] == 'n'){
				echo '<td><h2>Baustellen - den Eintrag bearbeiten</h2></td><td width="20px" />';		
			}else{
				echo '<td><h2>Wyjazdy – edytowanie wpisu</h2></td><td width="20px" />';		
			}
			//require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
		$query = "
			SELECT ptw.*, ptws.rejestracja as rejestracja, ptwc.imie as imie, ptwc.nazwisko as nazwisko, ptwb.budowa as budowa
			FROM panel_tabela_wyjazdy  ptw
			JOIN panel_tabela_wyjazdy_samochody ptws ON ptws.id = ptw.samochod_id
			JOIN panel_tabela_wyjazdy_pracownicy ptwc ON ptwc.id = ptw.pracownik_id
			JOIN panel_tabela_wyjazdy_budowy ptwb ON ptwb.id = ptw.budowa_id
			WHERE ptw.id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());	
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		require_once('menu_wyjazdy.php');
		echo '
			<table>';
			if($row_header['header'] == 'n'){
				echo '<tr><td id="naglowek">Mitarbeiter</td>
					<td id="naglowek">Baustelle</td>
					<td id="naglowek">Dienstwagen</td>
					<td id="naglowek">Bauvorhaben<br />bis</td>
					<td id="naglowek">Bauvorhaben<br />zum</td>
					<td id="naglowek">Auf Stand<br />setzen</td>
					<td id="naglowek">freimelden</td>
					<td id="naglowek">bestellen</td>
					<td id="naglowek">Bemerkungen</td>
					<td id="naglowek">Tag der<br />Eintragung</td>
					<td id="naglowek">LOGIN</td>
					<td id="naglowek">E</td>
					<td id="naglowek">U</td>
					</tr>';
			}else{
				echo '<tr>
					<td id="naglowek">PRACOWNIK</td>
					<td id="naglowek">BUDOWA</td>
					<td id="naglowek">SAMOCHÓD</td>
					<td id="naglowek">BUDOWA OD</td>
					<td id="naglowek">BUDOWA DO</td>
					<td id="naglowek">ZAWIESIĆ</td>
					<td id="naglowek">ZDAĆ</td>
					<td id="naglowek">ZAMÓWIĆ</td>
					<td id="naglowek">UWAGI</td>
					<td id="naglowek">DATA WPISU</td>
					<td id="naglowek">LOGIN</td>
					<td id="naglowek">E</td>
					<td id="naglowek">U</td>
				</tr>';
			}				
			
				//WYCIAGNIECIE KOLORU BUDOWY
				$query_kolor = "SELECT kolor FROM panel_tabela_wyjazdy_budowy WHERE id = '".$row['budowa_id']."' LIMIT 1;";
				$result_kolor = mysql_query($query_kolor) or die (mysql_error());
				$row_kolor = mysql_fetch_array($result_kolor,MYSQL_ASSOC);
				
				echo '<tr '.kolor_wiersza_wyjazdy($row_kolor['kolor']).'>
					<td>'.nl2br($row['nazwisko']).' '.nl2br($row['imie']).'</td>
					<td>'.nl2br($row['budowa']).'</td>
					<td>'.nl2br($row['rejestracja']).'</td>
					<td>'.nl2br($row['budowa_od']).'</td>
					<td>'.nl2br($row['budowa_do']).'</td>					
					<td>'.nl2br($row['zawiesic']).'</td>					
					<td>'.nl2br($row['zdac']).'</td>					
					<td>'.nl2br($row['zamowic']).'</td>					
					<td>'.nl2br($row['uwagi']).'</td>					
					<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row_login['login'].'</td>
					<td id="komorka_edycja"><a href="tabela_wyjazdy_edytuj.php?id='.$row['id'].'"><button>E</button></a></td>					
					<td id="komorka_usun"><a href="tabela_wyjazdy_usun.php?id='.$row['id'].'"><button>X</button></a></td>
				</tr>
			</table>';
			if($row_header['header'] == 'n'){
				echo '<div id="success">Der Eintrag wurde geändert</div><br />';
			}else{
				echo '<div id="success">Wpis został edytowany.</div><br />';
			}	
		
	if($row_header['header'] == 'n'){
		echo '<br /><a href="tabela_wyjazdy.php'.$filters_path.'"><button>zurück</button></a>';
	}else{
		echo '<br /><a href="tabela_wyjazdy.php'.$filters_path.'"><button>Powrót</button></a>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');