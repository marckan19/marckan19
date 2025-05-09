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
				echo '<td><h2>Baustellen - den Eintrag bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - edytowanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_wyjazdy.php?'.$filters_path);
	}elseif(isset($_POST['edytuj'])){		
		
		$pracownik_id = $_POST['pracownik_id'];		
		$budowa_id = $_POST['budowa_id'];			
		$samochod_id = $_POST['samochod_id'];		
		$budowa_od = $_POST['budowa_od'];		
		$budowa_do = $_POST['budowa_do'];				
		$zawiesic = wyjazdy_przecinek_na_kropke($_POST['zawiesic']);				
		$zdac = wyjazdy_przecinek_na_kropke($_POST['zdac']);				
		$zamowic = wyjazdy_przecinek_na_kropke($_POST['zamowic']);				
		$uwagi = $_POST['uwagi'];				
		$modification_date = date('Y-m-d G:i:s');
		
		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
				
		$query_update = "UPDATE panel_tabela_wyjazdy
						SET							
							pracownik_id = ".$pracownik_id.", 
							budowa_id = ".$budowa_id.", 
							samochod_id = ".$samochod_id.", 
							budowa_od = '".$budowa_od."', 
							budowa_do = '".$budowa_do."', 							
							zawiesic = ".$zawiesic.", 							
							zdac = ".$zdac.", 							
							zamowic = ".$zamowic.", 							
							uwagi = '".$uwagi."', 							
							modificator_id = ".$modificator_id.",
							modification_date = '".$modification_date."'							
						WHERE id = ".$id."						
						;";
		mysql_query($query_update);
		
		header('Location: tabela_wyjazdy_edytowano.php?id='.$id.$filters_path);
		
	}else{
		require_once('menu_wyjazdy.php');
		echo '<form action="tabela_wyjazdy_edytuj.php?id='.$id.$filters_path.'" method="post">	
		<table>
			';
			if($row_header['header'] == 'n'){
				echo '<tr><td id="naglowek">Mitarbeiter</td>
				<td id="naglowek">Baustelle</td>
				<td id="naglowek">Dienstwagen</td>
				<td id="naglowek">Bauvorhaben bis</td>
				<td id="naglowek">Bauvorhaben zum</td>
				<td id="naglowek">Auf Stand<br />setzen</td>
				<td id="naglowek">freimelden</td>
				<td id="naglowek">bestellen</td>
				<td id="naglowek">Bemerkungen</td>
				<td id="naglowek">Tag der Eintragung</td>
				<td id="naglowek">LOGIN</td>				
			</tr>';
			}else{
				echo '<tr><td id="naglowek">PRACOWNIK</td>
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
			</tr>';
			}
		
		$query = "SELECT * FROM panel_tabela_wyjazdy WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		//WYCIAGNIECIE KOLORU BUDOWY
		$query_kolor = "SELECT kolor FROM panel_tabela_wyjazdy_budowy WHERE id = '".$row['budowa_id']."' LIMIT 1;";
		$result_kolor = mysql_query($query_kolor) or die (mysql_error());
		$row_kolor = mysql_fetch_array($result_kolor,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_wyjazdy($row_kolor['kolor']).'>
				<td id="komorka_edytowanie">
					<select name="pracownik_id">';
						$query_pracownik = "SELECT * FROM panel_tabela_wyjazdy_pracownicy ORDER BY nazwisko, imie;";
						$result_pracownik = mysql_query($query_pracownik);
						while($row_pracownik = mysql_fetch_array($result_pracownik,MYSQL_ASSOC)){
							echo '<option'; if($row_pracownik['id'] == $row['pracownik_id']){echo ' selected="selected" ';} echo ' value="'.$row_pracownik['id'].'">'.$row_pracownik['nazwisko'].' '.$row_pracownik['imie'].'</option>';
						}						
				echo '</select></td>
				<td id="komorka_edytowanie">
					<select name="budowa_id">';
						$query_budowa = "SELECT * FROM panel_tabela_wyjazdy_budowy ORDER BY budowa;";
						$result_budowa = mysql_query($query_budowa);
						while($row_budowa = mysql_fetch_array($result_budowa,MYSQL_ASSOC)){
							echo '<option'; if($row_budowa['id'] == $row['budowa_id']){echo ' selected="selected" ';} echo ' value="'.$row_budowa['id'].'" >'.$row_budowa['budowa'].'</option>';
						}						
				echo '</select>
				</td>
				<td id="komorka_edytowanie">
				<select name="samochod_id">';
						$query_samochod = "SELECT * FROM panel_tabela_wyjazdy_samochody ORDER BY rejestracja;";
						$result_samochod = mysql_query($query_samochod);
						while($row_samochod = mysql_fetch_array($result_samochod,MYSQL_ASSOC)){
							echo '<option'; if($row_samochod['id'] == $row['samochod_id']){echo ' selected="selected" ';} echo ' value="'.$row_samochod['id'].'" >'.$row_samochod['rejestracja'].'</option>';
						}	
				echo '</select>
				</td>
				<td id="komorka_edytowanie"><input name="budowa_od" value="'.$row['budowa_od'].'" /></td>
				<td id="komorka_edytowanie"><input name="budowa_do" value="'.$row['budowa_do'].'" /></td>
				<td id="komorka_edytowanie"><input name="zawiesic" size="6" value="'.$row['zawiesic'].'" /></td>
				<td id="komorka_edytowanie"><input name="zdac" size="6" value="'.$row['zdac'].'" /></td>
				<td id="komorka_edytowanie"><input name="zamowic" size="6" value="'.$row['zamowic'].'" /></td>
				<td id="komorka_edytowanie"><input name="uwagi" value="'.$row['uwagi'].'" /></td>
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