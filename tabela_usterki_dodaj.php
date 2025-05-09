<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	require_once('menu.php');
	
	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
	}else{
		$filters_path = '';
	}
	
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Mängel - neuen Eintrag erstellen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Usterki - dodawanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_usterki.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_usterki.php'.$filters_path);
	}elseif(isset($_POST['dodaj'])){
		//$budowa_id = $_POST['budowa_id'];
		$budowa = $_POST['budowa'];
		$usterka = $_POST['usterka'];							
		$gdzie = $_POST['gdzie'];							
		$os = $_POST['os'];							
		$kontakt = $_POST['kontakt'];							
		if($_POST['czy_data_zgloszenia'] == 1){
			$data_zgloszenia = $_POST['data_zgloszenia'];
		}else{
			$data_zgloszenia = null;
		}
		if($_POST['czy_termin_usuniecia'] == 1){
			$termin_usuniecia = $_POST['termin_usuniecia'];
		}else{
			$termin_usuniecia = null;
		}
		if($_POST['czy_data_usuniecia'] == 1){
			$data_usuniecia = $_POST['data_usuniecia'];
		}else{
			$data_usuniecia = null;
		}
		$modyfikator = $_POST['modyfikator'];
		$usunal = $_POST['usunal'];
		$uwagi = $_POST['uwagi'];
		$sprzet = $_POST['sprzet'];
		
		$creation_date = date('Y-m-d G:i:s');
		
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}
				
		$query_insert = "INSERT INTO panel_tabela_usterki (budowa, usterka, gdzie, os, kontakt, data_zgloszenia, termin_usuniecia, data_usuniecia, usunal, uwagi, sprzet, creator_id, creation_date, modificator_id, modification_date)
						VALUES (
							'".$budowa."', 
							'".$usterka."', 													
							'".$gdzie."', 													
							'".$os."', 													
							'".$kontakt."', 													
							'".$data_zgloszenia."', 							 							
							'".$termin_usuniecia."',	
							'".$data_usuniecia."',	
							'".$usunal."', 
							'".$uwagi."', 
							'".$sprzet."', 
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."'
						);";
		mysql_query($query_insert);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
			$filters_path = '&'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_usterki_dodano.php?id='.mysql_insert_id().$filters_path);
		
	}else{
		echo '<form action="tabela_usterki_dodaj.php'.$filters_path.'" method="post">	
		<table >';
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
						<td id="naglowek">Mängel</td>								
						<td id="naglowek">Wo</td>								
						<td id="naglowek">Achse</td>								
						<td id="naglowek">Ansprechpartner /Telefon</td>								
						<td id="naglowek" width="150px">gemeldet am <input type="checkbox" name="czy_data_zgloszenia" value="1" /></td>
						<td id="naglowek" width="150px">Beseitigungsfirst <input type="checkbox" name="czy_termin_usuniecia" value="1" /></td>																		
						<td id="naglowek" width="150px">beseitigt am <input type="checkbox" name="czy_data_usuniecia" value="1" /></td>																		
						<td id="naglowek">beseitigt von</td>
						<td id="naglowek">Anmerkungen</td>
						<td id="naglowek">benötigte Gerätschaften: Arbeitsbühne, Regal, Leiter</td>
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
						<td id="naglowek" width="150px">DATA ZGŁOSZENIA <input type="checkbox" name="czy_data_zgloszenia" value="1" /></td>
						<td id="naglowek" width="150px">TERMIN USUNIECIA <input type="checkbox" name="czy_termin_usuniecia" value="1" /></td>																		
						<td id="naglowek" width="150px">DATA USUNIECIA <input type="checkbox" name="czy_data_usuniecia" value="1" /></td>																		
						<td id="naglowek">USUNIĘTE PRZEZ</td>
						<td id="naglowek">UWAGI</td>
						<td id="naglowek">SPRZĘT</td>
					</tr>					
				';
			}
		echo '<tr '.kolor_wiersza_usterki($row['data_usuniecia'], $row['termin_usuniecia']).'>';
		echo '		
				<td id="komorka_dodawanie"><textarea name="budowa" cols="15" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="usterka" cols="15" rows="2"></textarea></td>				
				<td id="komorka_dodawanie"><textarea name="gdzie" cols="15" rows="2"></textarea></td>				
				<td id="komorka_dodawanie"><textarea name="os" cols="15" rows="2"></textarea></td>				
				<td id="komorka_dodawanie"><textarea name="kontakt" cols="15" rows="2"></textarea></td>				
				<td id="komorka_dodawanie"><script>DateInput(\'data_zgloszenia\', true, \'YYYY-MM-DD\')</script></td>				
				<td id="komorka_dodawanie"><script>DateInput(\'termin_usuniecia\', true, \'YYYY-MM-DD\')</script></td>				
				<td id="komorka_dodawanie"><script>DateInput(\'data_usuniecia\', true, \'YYYY-MM-DD\')</script></td>				
				<td id="komorka_dodawanie"><textarea name="usunal" cols="15" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="uwagi" cols="15" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="sprzet" cols="15" rows="2"></textarea></td>
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
			echo '<input type="submit"  value="neuer Eintrag" name="dodaj" />
			<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Dodaj" name="dodaj" />
			<input type="submit"  value="Powrót" name="powrot" />';
		}
		echo '</form>';	
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');