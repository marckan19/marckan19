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
				echo '<td><h2>Festpunkte</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Punkty stałe</h2></td><td width="20px" />';			
			}
			require_once('legenda_punkty.php');
		echo '</tr>
	</table>';
	
	require 'tabela_punkty_filtry.php';
	
	$query = "SELECT * FROM panel_tabela_punkty WHERE '".date('Y-m-d')."' >= SUBSTRING(realizacja_od,1,10) AND '".date('Y-m-d')."' <= SUBSTRING(realizacja_do,1,10) ORDER BY realizacja_od;";
	$result = mysql_query($query) or die (mysql_error());
	
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a><br /><br />';
		}else{
			echo '<a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a><br /><br />';
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	echo '<div>
	<table width="100%">
		<tr>			
			<td id="naglowek" rowspan="2">LP</td>
			<td id="naglowek" rowspan="2">BAUVORHABEN + ADRESSE /<br />BUDOWA + ADRES (MICHAEL)</td>
			<td id="naglowek" colspan="2">AUSFÜHRUNGSTERMIN/<br />TERMIN REALIZACJI (MACIEK B.)</td>
			<td id="naglowek" rowspan="2">PERSONAL /<br />PERSONEL (MACIEK B.)</td>
			<td id="naglowek" rowspan="2">ERSTELLUNGSDATUM DER ABNAHMEDOKUMENTATION SEITENS BRASSCO /<br />DATA SPORZĄDZENIA DOKUMENTACJI PRZEZ BRASSCO (MICHAEL)</td>
			<td id="naglowek" rowspan="2">DATUM DER RÜCKSENDUNG DER ABNAHMEDOKUMENTATION UNTERSCHRIEBEN DURCH AUFTRAGGEBER /<br />DATA ODESŁANEJ DOKUMENTACJI, PODPISANEJ PRZEZ ZLECENIODAWCĘ (MICHAEL)</td>			
			<td id="naglowek" rowspan="2">AUFTRAGSWERT /<br />KWOTA KONTRAKTU (MICHAEL)</td>
			<td id="naglowek" rowspan="2">DATUM DES EINGANGS DES AVIS /<br />DATA PRZESŁANIA AWIZA PŁATNOŚCI </td>
			<td id="naglowek" rowspan="2">DATUM DES ZAHLUNGSEINGANGS /<br />DATA ZAPŁATY</td>
			<td id="naglowek" rowspan="2">VERMERKE/<br />UWAGI</td>
			<td id="naglowek" rowspan="2">DATUM DER EINTRAGUNG/<br />DATA WPISU</td>
			<td id="naglowek" rowspan="2">LOGIN</td>		
			';

			if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
				echo'
					<td id="naglowek" rowspan="2">E</td>
					<td id="naglowek" rowspan="2">K</td>
					<td id="naglowek" rowspan="2">U</td>';
			} 
		echo '</tr>
				<tr>
					<td id="naglowek" width="75px">VOM</td>
					<td id="naglowek" width="75px">BISZUM</td>
					
				</tr>
	';
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_punkty($row['realizacja_od'], $row['realizacja_do'], $row['zaznaczony_wiersz']).'>';
		echo '			
				<td>'.$lp.'</td>
				<td>'.nl2br($row['budowa_adres']).'</td>
				<td>'.nl2br($row['realizacja_od']).'</td>
				<td>'.nl2br($row['realizacja_do']).'</td>
				<td>'.nl2br($row['personel']).'</td>					
				<td>'.nl2br($row['data_sporzadzenia_dokumentacji']).'</td>				
				<td>'.nl2br($row['data_odeslanej_dokumentacji']).'</td>				
				<td>'.nl2br($row['kwota_kontraktu']).'</td>				
				<td>'.nl2br($row['data_przeslania_awiza']).'</td>			
				<td>'.nl2br($row['data_zaplaty']).'</td>			
				<td>'.nl2br($row['uwagi']).'</td>			
				<td>'.substr($row['creation_date'], 8, 2).'-'.substr($row['creation_date'], 5, 2).'-'.substr($row['creation_date'], 0, 4).'</td>
				<td>'.$row_login['login'].'</td>';

				if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
					echo '<td id="komorka_edycja"><a href="tabela_punkty_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						<td id="komorka_kopiuj"><a href="tabela_punkty_kopiuj.php?id='.$row['id'].$filters_path.'"><button>K</button></a></td>
						<td id="komorka_usun"><a href="tabela_punkty_usun.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';

	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a>';
		}else{
			echo '<br /><a href="tabela_punkty_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a>';
		}
	}

}else{
	require_once('logout.php');
}


require_once('footer.php');