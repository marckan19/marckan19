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
				echo '<td><h2>Mängel - den Eintrag bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Usterki - edytowanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_usterki.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_usterki.php'.$filters_path);
	}elseif(isset($_POST['edytuj'])){		
		$budowa = $_POST['budowa'];
		$usterka = $_POST['usterka'];							
		$gdzie = $_POST['gdzie'];							
		$os = $_POST['os'];							
		$kontakt = $_POST['kontakt'];							
		if($_POST['czy_data_zgloszenia'] == 1){
			$data_zgloszenia = $_POST['data_zgloszenia'];
		}else{
			$data_zgloszenia = $_POST['data_zgloszenia_stara'];
		}
		if($_POST['czy_termin_usuniecia'] == 1){
			$termin_usuniecia = $_POST['termin_usuniecia'];
		}else{
			$termin_usuniecia = $_POST['termin_usuniecia_stara'];
		}
		if($_POST['czy_data_usuniecia'] == 1){
			$data_usuniecia = $_POST['data_usuniecia'];
		}else{
			$data_usuniecia = $_POST['data_usuniecia_stara'];
		}
		$usunal = $_POST['usunal'];		
		$uwagi = $_POST['uwagi'];		
		$sprzet = $_POST['sprzet'];		
		$modification_date = date('Y-m-d G:i:s');
		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
		
		$query_update = "UPDATE panel_tabela_usterki 
						SET
							budowa = '".$budowa."', 
							usterka = '".$usterka."',							
							gdzie = '".$gdzie."',							
							os = '".$os."',							
							kontakt = '".$kontakt."',							
							data_zgloszenia = '".$data_zgloszenia."', 
							termin_usuniecia = '".$termin_usuniecia."', 							
							data_usuniecia = '".$data_usuniecia."', 							
							usunal = '".$usunal."', 							
							uwagi = '".$uwagi."', 							
							sprzet = '".$sprzet."', 							
							modificator_id = ".$modificator_id.",
							modification_date = '".$modification_date."'						
						WHERE id = ".$id."						
						;";
		mysql_query($query_update);
		
		header('Location: tabela_usterki_edytowano.php?id='.$id.$filters_path);
		
	}else{
		echo '<form action="tabela_usterki_edytuj.php?id='.$id.$filters_path.'" method="post">	
		<table>';
		
		if($row_header['header'] == 'n'){
			echo '
			<tr>
				<td id="naglowek">Bauvorhaben</td>
				<td id="naglowek">Mängel</td>								
				<td id="naglowek">Wo</td>								
				<td id="naglowek">Achse</td>								
				<td id="naglowek">Ansprechpartner /Telefon</td>								
				<td id="naglowek">gemeldet am <input type="checkbox" name="czy_data_zgloszenia" value="1" /></td>
				<td id="naglowek">Beseitigungsfrist<input type="checkbox" name="czy_termin_usuniecia" value="1" /></td>
				<td id="naglowek">beseitigt am <input type="checkbox" name="czy_data_usuniecia" value="1" /></td>																		
				<td id="naglowek">beseitigt von</td>
				<td id="naglowek">Anmerkungen</td>				
				<td id="naglowek">benötigte Gerätschaften: Arbeitsbühne, Regal, Leiter</td>				
				<td id="naglowek">eingetragen am</td>
				<td id="naglowek">LOGIN</td>				
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
				<td id="naglowek" width="155px">DATA ZGŁOSZENIA <input type="checkbox" name="czy_data_zgloszenia" value="1" /></td>
				<td id="naglowek" width="155px">TERMIN USUNIECIA <input type="checkbox" name="czy_termin_usuniecia" value="1" /></td>																		
				<td id="naglowek" width="155px">DATA USUNIECIA <input type="checkbox" name="czy_data_usuniecia" value="1" /></td>																		
				<td id="naglowek">USUNIĘTE PRZEZ</td>
				<td id="naglowek">UWAGI</td>				
				<td id="naglowek">POTRZEBNY SPRZĘT</td>				
				<td id="naglowek">DATA WPISU</td>
				<td id="naglowek">LOGIN</td>				
			</tr>			
			';
		}
		
		$query = "
			SELECT u.login AS login, ptu.* FROM panel_tabela_usterki ptu
			JOIN panel_users u ON u.id = ptu.creator_id
			WHERE ptu.id = ".$id." LIMIT 1;
		";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		echo '<tr '.kolor_wiersza_usterki($row['data_usuniecia'], $row['termin_usuniecia']).'>
				<td id="komorka_edytowanie"><textarea name="budowa" cols="15" rows="2">'.$row['budowa'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="usterka" cols="15" rows="2">'.$row['usterka'].'</textarea></td>								
				<td id="komorka_edytowanie"><textarea name="gdzie" cols="15" rows="2">'.$row['gdzie'].'</textarea></td>								
				<td id="komorka_edytowanie"><textarea name="os" cols="15" rows="2">'.$row['os'].'</textarea></td>								
				<td id="komorka_edytowanie"><textarea name="kontakt" cols="15" rows="2">'.$row['kontakt'].'</textarea></td>								
				<td id="komorka_edytowanie"><input type="text" name="data_zgloszenia_stara" value="'.$row['data_zgloszenia'].'" /><br /><script>DateInput(\'data_zgloszenia\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><input type="text" name="termin_usuniecia_stara" value="'.$row['termin_usuniecia'].'" /><br /><script>DateInput(\'termin_usuniecia\', true, \'YYYY-MM-DD\')</script></td>				
				<td id="komorka_edytowanie"><input type="text" name="data_usuniecia_stara" value="'.$row['data_usuniecia'].'" /><br /><script>DateInput(\'data_usuniecia\', true, \'YYYY-MM-DD\')</script></td>				
				
				<td id="komorka_edytowanie"><textarea name="usunal" cols="10" rows="2">'.$row['usunal'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="uwagi" cols="10" rows="2">'.$row['uwagi'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="sprzet" cols="10" rows="2">'.$row['sprzet'].'</textarea></td>
				<td id="komorka_edytowanie">'.substr($row['creation_date'], 0, 10).'</td>
				<td id="komorka_edytowanie">'.$row['login'].'</td>								
			</tr>
		';

		echo '</table><br />';
		if($row_header['header'] == 'n'){
			echo '<input type="submit"  value="ändern" name="edytuj" />';
			echo '<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Edytuj" name="edytuj" />';
			echo '<input type="submit"  value="Powrót" name="powrot" />';
		}
			
		echo '</form>';	
		echo '<br /><br />';
		
		$query_img = "SELECT * FROM panel_tabela_usterki_images WHERE id_usterki = ".$id." ORDER BY id;";
		$result_img = mysql_query($query_img) or die (mysql_error());		
		while($row_img = mysql_fetch_array($result_img,MYSQL_ASSOC)){
			echo '<img src="'.$row_img['path'].'" width="200px" /> <a href="tabela_usterki_img_del.php?id_img='.$row_img['id'].'"><button>-</button></a><br /><br />';
		
		}
		echo '<br /><br /><a href="tabela_usterki_img.php?id_wpisu='.$id.'"><button>Dodaj obraz</button></a>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');