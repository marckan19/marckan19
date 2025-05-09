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
				echo '<td><h2>Wartungsverträge - den Eintrag kopieren</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Umowy konserwacyjne - kopiowanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
		$query = "SELECT * FROM panel_tabela_konserwacje WHERE id = ".$id.";";
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
				<td id="naglowek">Bauvorhaben</td>
				<td id="naglowek">Vertragspartner</td>								
				<td id="naglowek">Datum</td>
				<td id="naglowek">Bemerkung</td>												
				<td id="naglowek">Vermerk</td>
				<td id="naglowek">Datum der Eintragung</td>
				<td id="naglowek">LOGIN</td>
				<td id="naglowek">E</td>
				<td id="naglowek">K</td>
				<td id="naglowek">U</td>
			</tr>';
		}else{
			echo '
			<tr>
				<td id="naglowek">BUDOWA</td>
				<td id="naglowek">KONTRAHENT</td>								
				<td id="naglowek">DATA</td>
				<td id="naglowek">UWAGI</td>												
				<td id="naglowek">INFORMACJA</td>
				<td id="naglowek">DATA WPISU</td>
				<td id="naglowek">LOGIN</td>
				<td id="naglowek">E</td>
				<td id="naglowek">K</td>
				<td id="naglowek">U</td>
			</tr>';
		}
			echo '
				<tr '.kolor_wiersza_konserwacje().'>
					<td>'.nl2br($row['budowa']).'</td>
					<td>'.nl2br($row['kontrahent']).'</td>					
					<td>'.nl2br($row['data']).'</td>				
					<td>'.nl2br($row['uwagi']).'</td>				
					<td>'.nl2br($row['informacja']).'</td>			
					<td>'.substr($row['creation_date'],0 ,10).'</td>
					<td>'.$row_login['login'].'</td>
					<td id="komorka_edycja"><a href="tabela_konserwacje_edytuj.php?id='.$row['id'].'"><button>E</button></a></td>
					<td id="komorka_kopiuj"><a href="tabela_konserwacje_kopiuj.php?id='.$row['id'].'"><button>K</button></a></td>
					<td id="komorka_usun"><a href="tabela_konserwacje_usun.php?id='.$row['id'].'"><button>X</button></a></td>
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
		echo '<br /><a href="tabela_konserwacje.php'.$filters_path.'"><button>zurück</button></a>';
	}else{
		echo '<br /><a href="tabela_konserwacje.php'.$filters_path.'"><button>Powrót</button></a>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');