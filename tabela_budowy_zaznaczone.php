<?php
ob_start();
session_start();
require_once('header.php');

if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){
	require_once('menu.php');
			
	echo '
	<table>
		<tr>';
			//<td><h2>Budowy</h2></td><td width="20px" />';			
			if($row_header['header'] == 'n'){
				echo '<td><h2>Bauvorhaben</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Budowy</h2></td><td width="20px" />';			
			}
			require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	require 'tabela_budowy_filtry.php';
	if (isset($_POST['filtry_szukaj'])){
	
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptn.* FROM panel_tabela_budowy ptn
					JOIN panel_users u ON u.id = ptn.creator_id
			WHERE 
			ptn.zleceniodawca like '%".$_POST['filtry_zleceniodawca']."%' &&			
			ptn.wys_hali like '%".$_POST['filtry_wys_hali']."%' &&			
			ptn.realizacja_od like '%".$_POST['filtry_realizacja_od']."%' &&			
			ptn.realizacja_do like '%".$_POST['filtry_realizacja_do']."%' &&			
			ptn.kierownik like '%".$_POST['filtry_kierownik']."%' &&
			ptn.dokumenty like '%".$_POST['filtry_dokumenty']."%' &&
			ptn.tydzien like '%".$_POST['filtry_tydzien']."%' &&
			
			ptn.rewizja like '%".$_POST['filtry_rewizja']."%' &&
			ptn.stan like '%".$_POST['filtry_stan']."%' &&
			ptn.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			ptn.uwagi like '%".$_POST['filtry_uwagi']."%' &&
			ptn.suplementy like '%".$_POST['filtry_suplementy']."%' &&
			u.login like '%".$_POST['filtry_login']."%'
			ORDER BY ptn.realizacja_od
		;";
		
		$filters_path = '&zleceniodawca='.$_POST['filtry_zleceniodawca'].'
						&wys_hali='.$_POST['filtry_wys_hali'].'
						&realizacja_od='.$_POST['realizacja_od'].'						
						&realizacja_do='.$_POST['realizacja_do'].'									
						&kierownik='.$_POST['filtry_kierownik'].'
						&dokumenty='.$_POST['filtry_dokumenty'].'
						&tydzien='.$_POST['filtry_tydzien'].'
										
						&rewizja='.$_POST['filtry_rewizja'].'						
						&stan='.$_POST['filtry_stan'].'						
						&data_wpisu='.$_POST['filtry_data_wpisu'].'						
						&uwagi='.$_POST['filtry_uwagi'].'						
						&suplementy='.$_POST['filtry_suplementy'].'						
						&login='.$_POST['filtry_login'];
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_budowy ptz
					JOIN panel_users u ON u.id = ptz.creator_id
			WHERE 
			ptz.zleceniodawca like '%".$_GET['zleceniodawca']."%' &&
			ptz.wys_hali like '%".$_GET['wys_hali']."%' &&
			ptz.realizacja_od like '%".$_GET['realizacja_od']."%' &&			
			ptz.realizacja_do like '%".$_GET['realizacja_do']."%' &&			
			ptz.kierownik like '%".$_GET['kierownik']."%' &&
			ptz.dokumenty like '%".$_GET['dokumenty']."%' &&
			ptz.tydzien like '%".$_GET['tydzien']."%' &&
			
			ptz.rewizja like '%".$_GET['rewizja']."%' &&			
			ptz.stan like '%".$_GET['stan']."%' &&			
			ptz.creation_date like '%".$_GET['data_wpisu']."%' &&
			ptz.uwagi like '%".$_GET['uwagi']."%' &&
			ptz.suplementy like '%".$_GET['suplementy']."%' &&
			u.login like '%".$_GET['login']."%'
			ORDER BY ptz.realizacja_od
		;";
		
		$filters_path = '&zleceniodawca='.$_GET['zleceniodawca'].'
						&wys_hali='.$_GET['wys_hali'].'
						&realizacja_od='.$_GET['realizacja_od'].'						
						&realizacja_do='.$_GET['realizacja_do'].'									
						&kierownik='.$_GET['kierownik'].'
						&dokumenty='.$_GET['dokumenty'].'
						&tydzien='.$_GET['tydzien'].'
						
						&rewizja='.$_GET['rewizja'].'
						&stan='.$_GET['stan'].'
						&creation_date='.$_GET['data_wpisu'].'						
						&uwagi='.$_GET['uwagi'].'						
						&suplementy='.$_GET['suplementy'].'						
						&login='.$_GET['login'];
	}else{
		$query = "SELECT * FROM panel_tabela_budowy WHERE zaznaczony_wiersz = 1 ORDER BY realizacja_od;";
	}
	
	$result = mysql_query($query) or die (mysql_error());
	
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_budowy_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a><br /><br />';
		}else{
			echo '<a href="tabela_budowy_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a><br /><br />';
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	echo '<div>
	<table width="100%">
		<tr>			
			<td id="naglowek" rowspan="2">LP</td>
			<td id="naglowek" rowspan="2" width="230px">BAUVORHABEN - AUFTRAGGEBER / BUDOWA - ZLECENIODAWCA</td>
			<td id="naglowek" rowspan="2">Höhe der Halle /<br /> WYS. HALI</td>
			<td id="naglowek" colspan="2">AUSFÜHRUNGSZEIT /<br /> TERMIN REALIZACJI</td>
			<td id="naglowek" rowspan="2">BAULEITER /<br /> KIEROWNIK</td>
			<td id="naglowek" rowspan="2">UNTERLAGEN /<br /> DOKUMENTY</td>			
			<td id="naglowek" rowspan="2">BAU-TAGESBERICHTE KW-</td>
			
			<td id="naglowek" rowspan="2" width="100px">REVI / REWIZJA</td>
			<td id="naglowek" rowspan="2" width="100px">Aktueller Stand</td>
			<td id="naglowek" rowspan="2" width="75px">DATA WPISU</td>
			<td id="naglowek" rowspan="2" width="100px">UWAGI</td>
			<td id="naglowek" rowspan="2" width="100px">Nachträge</td>
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
		
		echo '<tr '.kolor_wiersza_budowy($row['realizacja_od'], $row['realizacja_do'], $row['zaznaczony_wiersz']).'>';
		echo '			
				<td>'.$lp.'</td>
				<td>'.nl2br($row['zleceniodawca']).'</td>
				<td>'.nl2br($row['wys_hali']).'</td>
				<td>'.nl2br($row['realizacja_od']).'</td>
				<td>'.nl2br($row['realizacja_do']).'</td>					
				<td>'.nl2br($row['kierownik']).'</td>				
				<td>'.nl2br($row['dokumenty']).'</td>				
				<td>'.nl2br($row['tydzien']).'</td>		
				
				<td>'.nl2br($row['rewizja']).'</td>			
				<td>'.nl2br($row['stan']).'</td>			
				<td>'.substr($row['creation_date'], 0, 10).'</td>
				<td>'.nl2br($row['uwagi']).'</td>
				<td>'.nl2br($row['suplementy']).'</td>
				<td>'.$row_login['login'].'</td>
				';

				if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
					echo '<td id="komorka_edycja"><a href="tabela_budowy_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						<td id="komorka_kopiuj"><a href="tabela_budowy_kopiuj.php?id='.$row['id'].$filters_path.'"><button>K</button></a></td>
						<td id="komorka_usun"><a href="tabela_budowy_usun.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';

	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_budowy_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a>';
		}else{
			echo '<br /><a href="tabela_budowy_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a>';
		}
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');