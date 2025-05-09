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
				echo '<td><h2>Wartungsverträge</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Umowy konserwacyjne</h2></td><td width="20px" />';			
			}
			//require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
	require 'tabela_konserwacje_filtry.php';
	if (isset($_POST['filtry_szukaj'])){
	
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptk.* FROM panel_tabela_konserwacje ptk
					JOIN panel_users u ON u.id = ptk.creator_id
			WHERE 
			ptk.budowa like '%".$_POST['filtry_budowa']."%' &&			
			ptk.kontrahent like '%".$_POST['filtry_kontrahent']."%' &&						
			ptk.data like '%".$_POST['filtry_data']."%' &&
			ptk.uwagi like '%".$_POST['filtry_uwagi']."%' &&
			ptk.informacja like '%".$_POST['filtry_informacja']."%' &&			
			ptk.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			u.login like '%".$_POST['filtry_login']."%'
		;";
		
		$filters_path = '&budowa='.$_POST['filtry_budowa'].'
						&kontrahent='.$_POST['filtry_kontrahent'].'						
						&data='.$_POST['filtry_data'].'
						&uwagi='.$_POST['filtry_uwagi'].'
						&informacje='.$_POST['filtry_informacje'].'						
						&creation_date='.$_POST['filtry_data_wpisu'].'						
						&login='.$_POST['filtry_login'];
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptk.* FROM panel_tabela_konserwacje ptk
					JOIN panel_users u ON u.id = ptk.creator_id
			WHERE 
			ptk.budowa like '%".$_GET['budowa']."%' &&
			ptk.kontrahent like '%".$_GET['kontrahent']."%' &&						
			ptk.data '%".$_GET['data']."%' &&
			ptk.uwagi like '%".$_GET['uwagi']."%' &&
			ptk.informacja like '%".$_GET['informacja']."%' &&						
			ptk.creation_date like '%".$_GET['data_wpisu']."%' &&
			u.login like '%".$_GET['flogin']."%'
		;";
		
		$filters_path = '&budowa='.$_GET['budowa'].'
						&kontrahent='.$_GET['kontrahent'].'						
						&data='.$_GET['data'].'
						&uwagi='.$_GET['uwagi'].'
						&informacja='.$_GET['informacja'].'
						&creation_date='.$_GET['data_wpisu'].'						
						&login='.$_GET['login'];
	}else{
		$query = "SELECT * FROM panel_tabela_konserwacje;";
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
			echo '<a href="tabela_konserwacje_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a><br /><br />';
		}else{
			echo '<a href="tabela_konserwacje_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a><br /><br />';
		}
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	echo '<div>
	<table width="100%">
		<tr>';	
	
	
	if($row_header['header'] == 'n'){
		echo '
			<td id="naglowek">lfd. Nr.</td>
			<td id="naglowek">Bauvorhaben</td>
			<td id="naglowek">Vertragspartner</td>								
			<td id="naglowek">Datum</td>
			<td id="naglowek">Bemerkung</td>												
			<td id="naglowek">Vermerk</td>
			<td id="naglowek">Datum der Eintragung</td>
			<td id="naglowek">LOGIN</td>			
		';
	}else{
		echo '
			<td id="naglowek">LP</td>
			<td id="naglowek">BUDOWA</td>
			<td id="naglowek">KONTRAHENT</td>			
			<td id="naglowek">DATA</td>			
			<td id="naglowek">UWAGI</td>
			<td id="naglowek">INFORMACJE</td>
			<td id="naglowek">DATA WPISU</td>
			<td id="naglowek">LOGIN</td>			
		';
	}
			if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
			if($row_header['id'] != 16){ //Nie widzi user PDG
				echo'
					<td id="naglowek">E</td>
					<td id="naglowek">K</td>
					<td id="naglowek">U</td>';
			} 
			} 
		echo '</tr>';
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
				<td>'.nl2br($row['kontrahent']).'</td>						
				<td>'.nl2br($row['data']).'</td>				
				<td>'.nl2br($row['uwagi']).'</td>				
				<td>'.nl2br($row['informacja']).'</td>		
				<td>'.substr($row['creation_date'], 0, 10).'</td>
				<td>'.$row_login['login'].'</td>
				';
				if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
				if($row_header['id'] != 16){ //Nie widzi user PDG
					echo '<td id="komorka_edycja"><a href="tabela_konserwacje_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						<td id="komorka_kopiuj"><a href="tabela_konserwacje_kopiuj.php?id='.$row['id'].$filters_path.'"><button>K</button></a></td>
						<td id="komorka_usun"><a href="tabela_konserwacje_usun.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
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
			echo '<br /><a href="tabela_konserwacje_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a>';
		}else{
			echo '<br /><a href="tabela_konserwacje_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a>';
		}
		}
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');