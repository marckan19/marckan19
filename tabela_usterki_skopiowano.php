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
				echo '<td><h2>Mängel - den Eintrag kopieren</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Usterki - kopiowanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_usterki.php');
		echo '</tr>
	</table>';
	
		$query = "
			SELECT u.login AS login, ptu.* FROM panel_tabela_usterki ptu
			JOIN panel_users u ON u.id = ptu.creator_id
			WHERE ptu.id = ".$id." LIMIT 1;";
		$result = mysql_query($query) or die (mysql_error());	
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		
		echo '
			<table>';
			
		if($row_header['header'] == 'n'){
			echo '
			<tr>
				<td id="naglowek">Bauvorhaben</td>
					<td id="naglowek">Mängel</td>			
					<td id="naglowek">Wo</td>			
					<td id="naglowek">Achse</td>			
					<td id="naglowek">Ansprechpartner /Telefon</td>			
					<td id="naglowek" width="150px">gemeldet am</td>			
					<td id="naglowek" width="150px">Beseitigungsfirst</td>			
					<td id="naglowek" width="150px">beseitigt am</td>
					<td id="naglowek">beseitigt von</td>
					<td id="naglowek">Anmerkungen</td>
					<td id="naglowek">benötigte Gerätschaften: Arbeitsbühne, Regal, Leiter</td>
					<td id="naglowek" width="150px">eingetragen am</td>
					<td id="naglowek" width="150px">LOGIN</td>		
					<td id="naglowek">E</td>
					<td id="naglowek">K</td>
					<td id="naglowek">U</td>
			</tr>
			';
		}else{
			echo '
				<tr>
						<td id="naglowek">BUDOWA</td>
					<td id="naglowek">USTERKA</td>			
					<td id="naglowek">GDZIE</td>			
					<td id="naglowek">OŚ</td>			
					<td id="naglowek">KONTAKT</td>			
					<td id="naglowek" width="150px">DATA ZGŁOSZENIA</td>			
					<td id="naglowek" width="150px">TERMIN USUNIĘCIA</td>			
					<td id="naglowek" width="150px">DATA USUNIĘCIA</td>
					<td id="naglowek">USUNIĘTE PRZEZ</td>
					<td id="naglowek">UWAGI</td>
					<td id="naglowek">POTRZEBNY SPRZĘT</td>
					<td id="naglowek" width="150px">DATA WPISU</td>
					<td id="naglowek" width="150px">LOGIN</td>		
					<td id="naglowek">E</td>
					<td id="naglowek">K</td>
					<td id="naglowek">U</td>
				</tr>
			';
		}
			echo '
				<tr '.kolor_wiersza_usterki($row['data_usuniecia'], $row['termin_usuniecia']).'>
					<td>'.nl2br($row['budowa']).'</td>
					<td>'.nl2br($row['usterka']).'</td>	
					<td>'.nl2br($row['gdzie']).'</td>	
					<td>'.nl2br($row['os']).'</td>	
					<td>'.nl2br($row['kontakt']).'</td>	
					<td>'.zerowaDataNaPusta($row['data_zgloszenia']).'</td>				
					<td>'.zerowaDataNaPusta($row['termin_usuniecia']).'</td>		
					<td>'.zerowaDataNaPusta($row['data_usuniecia']).'</td>		
					<td>'.nl2br($row['usunal']).'</td>
					<td>'.nl2br($row['uwagi']).'</td>
					<td>'.nl2br($row['sprzet']).'</td>
					<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row['login'].'</td>
					<td id="komorka_edycja"><a href="tabela_usterki_edytuj.php?id='.$row['id'].'"><button>E</button></a></td>
					<td id="komorka_kopiuj"><a href="tabela_usterki_kopiuj.php?id='.$row['id'].'"><button>K</button></a></td>
					<td id="komorka_usun"><a href="tabela_usterki_usun.php?id='.$row['id'].'"><button>X</button></a></td>
				</tr>
			</table>';
			
			if($row_header['header'] == 'n'){
				echo '<div id="success">Der Eintrag wurde kopiert</div><br />';
			}else{
				echo '<div id="success">Wpis został skopiowany.</div><br />';
			}
	
	if($row_header['header'] == 'n'){
		echo '<br /><a href="tabela_usterki.php'.$filters_path.'"><button>zurück</button></a>';
	}else{
		echo '<br /><a href="tabela_usterki.php'.$filters_path.'"><button>Powrót</button></a>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');