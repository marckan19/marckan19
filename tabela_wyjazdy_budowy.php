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
				echo '<td><h2>Baustellen - Baustellen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - BUDOWY</h2></td><td width="20px" />';			
			}
			//require_once('legenda_wyjazdy.php');
		echo '</tr>
	</table>';
	
	//require 'tabela_wyjazdy_filtry.php';
	if (isset($_POST['filtry_szukaj'])){
	
		$query = "SELECT u.login AS login, ptn.* FROM panel_tabela_wyjazdy_budowy ptn
					JOIN panel_users u ON u.id = ptn.creator_id
			WHERE 
			ptn.budowa like '%".$_POST['filtry_budowa']."%' &&			
			ptn.od like '%".$_POST['filtry_od']."%' &&			
			ptn.do like '%".$_POST['filtry_do']."%' &&			
			ptn.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			u.login like '%".$_POST['filtry_login']."%'
			&& ptn.is_deleted = 0
			ORDER BY ptn.budowa
		;";
		
		$filters_path = '&budowa='.$_POST['filtry_budowa'].'
						&od='.$_POST['filtry_od'].'						
						&do='.$_POST['filtry_do'].'																			
						&data_wpisu='.$_POST['filtry_data_wpisu'].'												
						&login='.$_POST['filtry_login'];
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_wyjazdy_budowy ptz
					JOIN panel_users u ON u.id = ptz.creator_id
			WHERE 
			ptz.budowa like '%".$_GET['budowa']."%' &&
			ptz.od like '%".$_GET['od']."%' &&			
			ptz.do like '%".$_GET['do']."%' &&			
			ptz.creation_date like '%".$_GET['data_wpisu']."%' &&
			u.login like '%".$_GET['login']."%'
			&& ptz.is_deleted = 0
			ORDER BY ptz.budowa
		;";
		
		$filters_path = '&budowa='.$_GET['budowa'].'
						&od='.$_GET['od'].'						
						&do='.$_GET['do'].'									
						&creation_date='.$_GET['data_wpisu'].'						
						&login='.$_GET['login'];
	}else{
		$query = "
			SELECT u.login AS login, ptwb.* FROM panel_tabela_wyjazdy_budowy ptwb
			JOIN panel_users u ON u.id = ptwb.creator_id
			&& ptwb.is_deleted = 0
			ORDER BY ptwb.budowa;";
	}
		
	$result = mysql_query($query) or die (mysql_error());
	
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_wyjazdy_budowy_dodaj.php'.$filters_path2.'"><button>neue Baustelle</button></a><br /><br />';
		}else{
			echo '<a href="tabela_wyjazdy_budowy_dodaj.php'.$filters_path2.'"><button>Dodaj budowę</button></a><br /><br />';
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	require_once('menu_wyjazdy.php');
	echo '<div>
	<table><tr>	';
		if($row_header['header'] == 'n'){
			echo '		
			<td id="naglowek">Lfd.<br />Nr.</td>
			<td id="naglowek">Baustelle</td>
			<td id="naglowek">bis</td>
			<td id="naglowek">zum</td>			
			<td id="naglowek">Tag der<br />Eintragung</td>			
			<td id="naglowek">LOGIN</td>			
			';
		}else{
			echo '		
			<td id="naglowek">LP</td>
			<td id="naglowek">BUDOWA</td>
			<td id="naglowek">OD</td>
			<td id="naglowek">DO</td>			
			<td id="naglowek">DATA WPISU</td>			
			<td id="naglowek">LOGIN</td>			
			';
		}
			if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
				echo'					
					<td id="naglowek">E</td>					
					<td id="naglowek">U</td>';
			} 
		echo '</tr>';
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
		
		echo '<tr '.kolor_wiersza_wyjazdy_budowy($row['kolor']).'>';
		echo '			
				<td>'.$lp.'</td>
				<td>'.nl2br($row['budowa']).'</td>
				<td>'.nl2br($row['od']).'</td>
				<td>'.nl2br($row['do']).'</td>								
				<td>'.substr($row['creation_date'], 0, 10).'</td>				
				<td>'.$row['login'].'</td>
				';
				if($_SESSION['user_type'] == 'a'){
					echo '<td id="komorka_edycja"><a href="tabela_wyjazdy_budowy_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						  <td id="komorka_usun"><a href="tabela_wyjazdy_budowy_ukryj.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';
	if($_SESSION['user_type'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_wyjazdy_budowy_dodaj.php'.$filters_path2.'"><button>neue Baustelle</button></a>';
		}else{
			echo '<br /><a href="tabela_wyjazdy_budowy_dodaj.php'.$filters_path2.'"><button>Dodaj budowę</button></a>';
		}
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');