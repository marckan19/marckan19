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
				echo '<td><h2>Baustellen – neuen Dienstwagen erstellen</h2></td><td width="20px" />';		
			}else{
				echo '<td><h2>Wyjazdy – dodawanie samochodu</h2></td><td width="20px" />';		
			}
			//require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
		$query = "SELECT * FROM panel_tabela_wyjazdy_samochody WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());	
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '
			<table>';
			if($row_header['header'] == 'n'){
				echo '<tr>
					<td id="naglowek">Dienstwagen</td>
					<td id="naglowek">amtl. Kennzeichen</td>
					<td id="naglowek">Tag der<br />Eintragung</td>			
					<td id="naglowek">LOGIN</td>			
					<td id="naglowek">E</td>
					<!--<td id="naglowek">U</td>-->
				</tr>';
				}else{
					echo '<tr>
					<td id="naglowek">SAMOCHÓD</td>
					<td id="naglowek">REJESTRACJA</td>					
					<td id="naglowek">DATA WPISU</td>
					<td id="naglowek">LOGIN</td>
					<td id="naglowek">E</td>
					<!--<td id="naglowek">U</td>-->
				</tr>';
				}				
				echo '<tr '.kolor_wiersza_wyjazdy_samochody().'>
					<td>'.nl2br($row['samochod']).'</td>
					<td>'.nl2br($row['rejestracja']).'</td>															
					<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row_login['login'].'</td>
					<td id="komorka_edycja"><a href="tabela_wyjazdy_samochody_edytuj.php?id='.$row['id'].'"><button>E</button></a></td>					
					<!--<td id="komorka_usun"><a href="tabela_wyjazdy_samochody_usun.php?id='.$row['id'].'"><button>X</button></a></td>-->
				</tr>
			</table>';
			if($row_header['header'] == 'n'){
				echo '<div id="success">Dienstwagen wurde gespeichert.</div><br />';
			}else{
				echo '<div id="success">Samochód został dodany.</div><br />';
			}	
		
	if($row_header['header'] == 'n'){
		echo '<br /><a href="tabela_wyjazdy_samochody.php'.$filters_path.'"><button>zurück</button></a>';
	}else{
		echo '<br /><a href="tabela_wyjazdy_samochody.php'.$filters_path.'"><button>Powrót</button></a>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');