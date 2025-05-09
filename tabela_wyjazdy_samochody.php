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
				echo '<td><h2>Baustellen - Dienstwagen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - SAMOCHODY</h2></td><td width="20px" />';			
			}
			//require_once('legenda_wyjazdy.php');
		echo '</tr>
	</table>';
	
	//require 'tabela_wyjazdy_filtry.php';
	if (isset($_POST['filtry_szukaj'])){
	
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptn.* FROM panel_tabela_wyjazdy_samochody ptn
					JOIN panel_users u ON u.id = ptn.creator_id
			WHERE 
			ptn.samochod like '%".$_POST['filtry_samochod']."%' &&			
			ptn.rejestracja like '%".$_POST['filtry_rejestracja']."%' &&			
			ptn.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			u.login like '%".$_POST['filtry_login']."%'
			&& ptn.is_deleted = 0
			ORDER BY ptn.samochod
		;";
		
		$filters_path = '&samochod='.$_POST['filtry_samochod'].'
						&rejestracja='.$_POST['filtry_rejestracja'].'						
						&data_wpisu='.$_POST['filtry_data_wpisu'].'												
						&login='.$_POST['filtry_login'];
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		//WYCIAGANIE ID USERA
		$query_login_id = "SELECT * FROM panel_users WHERE login = '".$_POST['filtry_login']."';";
		$result_login_id = mysql_query($query_login_id) or die (mysql_error());
		$row_login_id = mysql_fetch_array($result_login_id,MYSQL_ASSOC); 
		
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_wyjazdy_samochody ptz
					JOIN panel_users u ON u.id = ptz.creator_id
			WHERE 
			ptz.samochod like '%".$_GET['samochod']."%' &&
			ptz.rejestracja like '%".$_GET['rejestracja']."%' &&			
			ptz.creation_date like '%".$_GET['data_wpisu']."%' &&
			u.login like '%".$_GET['login']."%'
			&& ptz.is_deleted = 0
			ORDER BY ptz.samochod
		;";
		
		$filters_path = '&samochod='.$_GET['samochod'].'
						&rejestracja='.$_GET['rejestracja'].'				
						&creation_date='.$_GET['data_wpisu'].'						
						&login='.$_GET['login'];
	}else{
		$query = "SELECT * FROM panel_tabela_wyjazdy_samochody WHERE is_deleted = 0 ORDER BY samochod;";
	}
		
	$result = mysql_query($query) or die (mysql_error());
	
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_wyjazdy_samochody_dodaj.php'.$filters_path2.'"><button>neuer Dienstwagen</button></a><br /><br />';
		}else{
			echo '<a href="tabela_wyjazdy_samochody_dodaj.php'.$filters_path2.'"><button>Dodaj samochód</button></a><br /><br />';
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	require_once('menu_wyjazdy.php');
	echo '<div>
	<table><tr>';
	if($row_header['header'] == 'n'){
		echo '			
			<td id="naglowek">Lfd.<br />Nr.</td>
			<td id="naglowek">Dienstwagen</td>
			<td id="naglowek">amtl. Kennzeichen</td>
			<td id="naglowek">Tag der<br />Eintragung</td>			
			<td id="naglowek">LOGIN</td>			
			';
	}else{
		echo '			
			<td id="naglowek">LP</td>
			<td id="naglowek">SAMOCHÓD</td>
			<td id="naglowek">REJESTRACJA</td>
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
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_wyjazdy_samochody().'>';
		echo '			
				<td>'.$lp.'</td>
				<td>'.nl2br($row['samochod']).'</td>
				<td>'.nl2br($row['rejestracja']).'</td>						
				<td>'.substr($row['creation_date'], 0, 10).'</td>				
				<td>'.$row_login['login'].'</td>
				';
				if($_SESSION['user_type'] == 'a'){
					echo '<td id="komorka_edycja"><a href="tabela_wyjazdy_samochody_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						  <td id="komorka_usun"><a href="tabela_wyjazdy_samochody_ukryj.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';
	if($_SESSION['user_type'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_wyjazdy_samochody_dodaj.php'.$filters_path2.'"><button>neuer Dienstwagen</button></a>';
		}else{
			echo '<br /><a href="tabela_wyjazdy_samochody_dodaj.php'.$filters_path2.'"><button>Dodaj samochód</button></a>';
		}
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');