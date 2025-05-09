<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	require_once('menu.php');
	
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		$id = 0;
	}	

	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
	}else{
		$filters_path = '';
	}	
		
	echo '
	<table>
		<tr>';		
			if($row_header['header'] == 'n'){
				echo '<td><h2>Baustellen - Mitarbeiter bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - edytowanie pracownika</h2></td><td width="20px" />';			
			}
			//require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_wyjazdy_pracownicy.php'.$filters_path);
	}elseif(isset($_POST['edytuj'])){		
		$imie = $_POST['imie'];		
		$nazwisko = $_POST['nazwisko'];		
		$stanowisko = $_POST['stanowisko'];
		$kontakt = $_POST['kontakt'];
		$modification_date = date('Y-m-d G:i:s');
		
		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
				
		$query_update = "UPDATE panel_tabela_wyjazdy_pracownicy
						SET
							imie = '".$imie."', 
							nazwisko = '".$nazwisko."',
							stanowisko = '".$stanowisko."', 							
							kontakt = '".$kontakt."', 							
							modificator_id = ".$modificator_id.",
							modification_date = '".$modification_date."'							
						WHERE id = ".$id."						
						;";
		mysql_query($query_update);
		
		header('Location: tabela_wyjazdy_pracownicy_edytowano.php?id='.$id.$filters_path);
		
	}else{
		echo '<form action="tabela_wyjazdy_pracownicy_edytuj.php?id='.$id.$filters_path.'" method="post">	
		<table>';
		if($row_header['header'] == 'n'){
			echo '<tr>
				<td id="naglowek">Vorname</td>
				<td id="naglowek">Nachname</td>
				<td id="naglowek">Funktion</td>				
				<td id="naglowek">Kontakt</td>			
				<td id="naglowek">Tag der<br />Eintragung</td>
				<td id="naglowek">LOGIN</td>			
			</tr>';
		}else{
			echo '<tr>
				<td id="naglowek">IMIĘ</td>
				<td id="naglowek">NAZWISKO</td>
				<td id="naglowek">STANOWISKO</td>				
				<td id="naglowek">KONTAKT</td>				
				<td id="naglowek">DATA WPISU</td>
				<td id="naglowek">LOGIN</td>				
			</tr>';
		}
		
		$query = "SELECT * FROM panel_tabela_wyjazdy_pracownicy WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_wyjazdy_pracownicy().'>
				<td id="komorka_edytowanie"><input name="imie" value="'.$row['imie'].'" /></td>
				<td id="komorka_edytowanie"><input name="nazwisko" value="'.$row['nazwisko'].'" /></td>
				<td id="komorka_edytowanie"><input name="stanowisko" value="'.$row['stanowisko'].'" /></td>
				<td id="komorka_edytowanie"><input name="kontakt" value="'.$row['kontakt'].'" /></td>
				<td id="komorka_edytowanie">'.substr($row['creation_date'], 0, 10).'</td>
				<td id="komorka_edytowanie">'.$row_login['login'].'</td>				
			</tr>
		';

		echo '</table><br />';
		if(isset($_SESSION['user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
		}elseif(isset($_COOKIE['ciastko_user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
		}
		$result_header = mysql_query($query_header) or die (mysql_error());
		$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
	
		if($row_header['header'] == 'n'){
			echo '<input type="submit"  value="ändern" name="edytuj" />';
			echo '<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Edytuj" name="edytuj" />';
			echo '<input type="submit"  value="Powrót" name="powrot" />';
		}
			
		echo '</form>';	
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');