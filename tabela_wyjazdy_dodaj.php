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
				echo '<td><h2>Baustellen – neuen Eintrag erstellen</h2></td><td width="20px" />';		
			}else{
				echo '<td><h2>Wyjazdy – dodawanie wpisu</h2></td><td width="20px" />';		
			}
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_wyjazdy.php'.$filters_path);
	}elseif(isset($_POST['dodaj'])){
		
		$dlugosc_pracownicy = count($_POST['pracownicy_id']);		
		$dlugosc_samochody = count($_POST['samochody_id']);
		$is_main = 1;
		$budowa_id = $_POST['budowa_id'];
		//$budowa_od = $_POST['budowa_od'];
		//$budowa_do = $_POST['budowa_do'];	
		if($_POST['czy_budowa_od'] == 1){
			$budowa_od = $_POST['budowa_od'];
		}else{
			$budowa_od = '-';
		}		
		if($_POST['czy_budowa_do'] == 1){
			$budowa_do = $_POST['budowa_do'];
		}else{
			$budowa_do = '-';
		}				
		$zawiesic_1 = wyjazdy_przecinek_na_kropke($_POST['zawiesic_1']);		
		$zawiesic_2 = wyjazdy_przecinek_na_kropke($_POST['zawiesic_2']);		
		$zawiesic_3 = wyjazdy_przecinek_na_kropke($_POST['zawiesic_3']);		
		$zawiesic_4 = wyjazdy_przecinek_na_kropke($_POST['zawiesic_4']);		
		$zawiesic_5 = wyjazdy_przecinek_na_kropke($_POST['zawiesic_5']);		
		$zawiesic_6 = wyjazdy_przecinek_na_kropke($_POST['zawiesic_6']);		
		$zawiesic_7 = wyjazdy_przecinek_na_kropke($_POST['zawiesic_7']);		
		$zdac_1 = wyjazdy_przecinek_na_kropke($_POST['zdac_1']);		
		$zdac_2 = wyjazdy_przecinek_na_kropke($_POST['zdac_2']);		
		$zdac_3 = wyjazdy_przecinek_na_kropke($_POST['zdac_3']);		
		$zdac_4 = wyjazdy_przecinek_na_kropke($_POST['zdac_4']);		
		$zdac_5 = wyjazdy_przecinek_na_kropke($_POST['zdac_5']);		
		$zdac_6 = wyjazdy_przecinek_na_kropke($_POST['zdac_6']);		
		$zdac_7 = wyjazdy_przecinek_na_kropke($_POST['zdac_7']);		
		$zamowic_1 = wyjazdy_przecinek_na_kropke($_POST['zamowic_1']);		
		$zamowic_2 = wyjazdy_przecinek_na_kropke($_POST['zamowic_2']);		
		$zamowic_3 = wyjazdy_przecinek_na_kropke($_POST['zamowic_3']);		
		$zamowic_4 = wyjazdy_przecinek_na_kropke($_POST['zamowic_4']);		
		$zamowic_5 = wyjazdy_przecinek_na_kropke($_POST['zamowic_5']);		
		$zamowic_6 = wyjazdy_przecinek_na_kropke($_POST['zamowic_6']);		
		$zamowic_7 = wyjazdy_przecinek_na_kropke($_POST['zamowic_7']);		
		$uwagi = $_POST['uwagi'];		
		$creation_date = date('Y-m-d G:i:s');		
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}		
		
		//DODANIE GLOWNEGO WPISU
		$query_insert_wpis = "INSERT INTO panel_tabela_wyjazdy_wpisy (
						creator_id, creation_date, modificator_id, modification_date)
						VALUES (													
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."'							
						);";
						
		mysql_query($query_insert_wpis);				
		$id_wpisu = mysql_insert_id();
		//echo $id_wpisu;
		
		//DODANIE DO TABELI PRACOWNICY_POWIAZANIA
		if($dlugosc_pracownicy > 0){
			foreach($_POST['pracownicy_id'] as $pracownik_powiazanie){
				$pracownik_id = $pracownik_powiazanie;
				
				$query_insert_pracownik_powiazanie = "INSERT INTO panel_tabela_wyjazdy_pracownicy_powiazania (pracownik_id, wpis_id, creator_id, creation_date, modificator_id, modification_date)
						VALUES (						
							".$pracownik_id.", 						
							".$id_wpisu.", 													 							
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."'							
						);";
				
				mysql_query($query_insert_pracownik_powiazanie);				
			}		
		}
		
		//DODANIE DO TABELI BUDOWY_POWIAZANIA				
				$query_insert_budowa_powiazanie = "INSERT INTO panel_tabela_wyjazdy_budowy_powiazania (
					budowa_id, 
					wpis_id, 
					is_main,
					zawiesic_1,
					zawiesic_2,
					zawiesic_3,
					zawiesic_4,
					zawiesic_5,
					zawiesic_6,
					zawiesic_7,
					zdac_1,
					zdac_2,
					zdac_3,
					zdac_4,
					zdac_5,
					zdac_6,
					zdac_7,
					zamowic_1,
					zamowic_2,
					zamowic_3,
					zamowic_4,
					zamowic_5,
					zamowic_6,
					zamowic_7,
					budowa_od,
					budowa_do,
					uwagi,					
					creator_id, 
					creation_date, 
					modificator_id, 
					modification_date)
						VALUES (						
							".$budowa_id.", 						
							".$id_wpisu.", 													 							
							".$is_main.", 													 							
							'".$zawiesic_1."',
							'".$zawiesic_2."',
							'".$zawiesic_3."',
							'".$zawiesic_4."',
							'".$zawiesic_5."',
							'".$zawiesic_6."',
							'".$zawiesic_7."',
							'".$zdac_1."',
							'".$zdac_2."',
							'".$zdac_3."',
							'".$zdac_4."',
							'".$zdac_5."',
							'".$zdac_6."',
							'".$zdac_7."',
							'".$zamowic_1."',
							'".$zamowic_2."',
							'".$zamowic_3."',
							'".$zamowic_4."',
							'".$zamowic_5."',
							'".$zamowic_6."',
							'".$zamowic_7."',
							'".$budowa_od."',
							'".$budowa_do."',
							'".$uwagi."',
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."'							
						);";
				
				mysql_query($query_insert_budowa_powiazanie);				
	
		
		//DODANIE DO TABELI SAMOCHODY_POWIAZANIA
		if($dlugosc_samochody > 0){
			foreach($_POST['samochody_id'] as $samochod_powiazanie){
				$samochod_id = $samochod_powiazanie;
				
				$query_insert_samochod_powiazanie = "INSERT INTO panel_tabela_wyjazdy_samochody_powiazania (samochod_id, wpis_id, creator_id, creation_date, modificator_id, modification_date)
						VALUES (						
							".$samochod_id.", 						
							".$id_wpisu.", 													 							
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."'							
						);";
				
				mysql_query($query_insert_samochod_powiazanie);				
			}		
		}
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
			$filters_path = '&'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		//header('Location: tabela_wyjazdy_dodano.php?ids='.$string.$filters_path);
		header('Location: tabela_wyjazdy_dodano.php?id='.$id_wpisu);
		
	}else{
		require_once('menu_wyjazdy.php');
		echo '<form action="tabela_wyjazdy_dodaj.php'.$filters_path.'" method="post">	
		<table>';
			if($row_header['header'] == 'n'){
				echo '<tr>
				<td id="naglowek">Mitarbeiter</td>
				<td id="naglowek">Baustelle</td>
				<td id="naglowek">Auf Stand<br />setzen</td>
				<td id="naglowek">freimelden</td>
				<td id="naglowek">bestellen</td>
				<td id="naglowek">Dienstwagen</td>
				<td id="naglowek">Bauvorhaben bis</td>
				<td id="naglowek">Bauvorhaben zum</td>
				
				<td id="naglowek">Bemerkungen</td>
				</tr>';
			}else{
				echo '<tr>
				<td id="naglowek">PRACOWNIK</td>
				<td id="naglowek">BUDOWA</td>
				<td id="naglowek">ZAWIESIĆ</td>
				<td id="naglowek">ZDAĆ</td>
				<td id="naglowek">ZAMÓWIĆ</td>
				<td id="naglowek">SAMOCHÓD</td>
				<td id="naglowek">BUDOWA OD<input type="checkbox" name="czy_budowa_od" value="1" /></td>
				<td id="naglowek">BUDOWA DO<input type="checkbox" name="czy_budowa_do" value="1" /></td>
				
				<td id="naglowek">UWAGI</td>
			</tr>';
			}
						
		echo '<tr style="background-color: lightgreen;">';
		
		echo '	<td id="komorka_dodawanie_grupowe" SCROLLING=yes height="200">
			<div style="height: 100%; width: 100%; overflow-y: auto;">';
					$query_pracownik = "SELECT * FROM panel_tabela_wyjazdy_pracownicy WHERE is_deleted = 0 ORDER BY nazwisko, imie;";
					$result_pracownik = mysql_query($query_pracownik);
					while($row_pracownik = mysql_fetch_array($result_pracownik,MYSQL_ASSOC)){						
						echo '<input type="checkbox" name="pracownicy_id[]" value="'.$row_pracownik['id'].'" />'.$row_pracownik['nazwisko'].' '.$row_pracownik['imie'].'<br />';
					}	
		echo '  </div> </td>';
		echo '	<td id="komorka_dodawanie_grupowe">
					<select name="budowa_id">';
						$query_budowa = "SELECT * FROM panel_tabela_wyjazdy_budowy WHERE is_deleted = 0 ORDER BY budowa;";
						$result_budowa = mysql_query($query_budowa);
						while($row_budowa = mysql_fetch_array($result_budowa,MYSQL_ASSOC)){
							echo '<option value="'.$row_budowa['id'].'">'.$row_budowa['budowa'].'</option>';
						}						
				echo '</select>
				</td>';
				echo '<td id="komorka_dodawanie_grupowe">
					1. <input name="zawiesic_1" size="3" /><br />
					2. <input name="zawiesic_2" size="3" /><br />
					3. <input name="zawiesic_3" size="3" /><br />
					4. <input name="zawiesic_4" size="3" /><br />
					5. <input name="zawiesic_5" size="3" /><br />
					6. <input name="zawiesic_6" size="3" /><br />
					7. <input name="zawiesic_7" size="3" /><br />
				</td>
				<td id="komorka_dodawanie_grupowe">
					1. <input name="zdac_1" size="4" /><br />
					2. <input name="zdac_2" size="4" /><br />
					3. <input name="zdac_3" size="4" /><br />
					4. <input name="zdac_4" size="4" /><br />
					5. <input name="zdac_5" size="4" /><br />
					6. <input name="zdac_6" size="4" /><br />
					7. <input name="zdac_7" size="4" /><br />
				</td>
				<td id="komorka_dodawanie_grupowe">
					1. <input name="zamowic_1" size="4" /><br />
					2. <input name="zamowic_2" size="4" /><br />
					3. <input name="zamowic_3" size="4" /><br />
					4. <input name="zamowic_4" size="4" /><br />
					5. <input name="zamowic_5" size="4" /><br />
					6. <input name="zamowic_6" size="4" /><br />
					7. <input name="zamowic_7" size="4" /><br />
				</td>';
		echo '	<td id="komorka_dodawanie_grupowe" SCROLLING=yes height="200">
					<div style="height: 100%; width: 100%; overflow-y: auto;">';	
						$query_samochod = "SELECT * FROM panel_tabela_wyjazdy_samochody WHERE is_deleted = 0 ORDER BY rejestracja;";
						$result_samochod = mysql_query($query_samochod);
						while($row_samochod = mysql_fetch_array($result_samochod,MYSQL_ASSOC)){
							//echo '<option value="'.$row_samochod['id'].'">'.$row_samochod['rejestracja'].'</option>';
							echo '<input type="checkbox" name="samochody_id[]" value="'.$row_samochod['id'].'" />'.$row_samochod['rejestracja'].'<br />';
						}						
				echo '
				</div></td>';
		echo '					
				<td id="komorka_dodawanie_grupowe"><script>DateInput(\'budowa_od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_dodawanie_grupowe"><script>DateInput(\'budowa_do\', true, \'YYYY-MM-DD\')</script></td>
				
				<td id="komorka_dodawanie_grupowe"><textarea name="uwagi" cols="25" rows="9"></textarea></td>
			';
		echo '</tr>';

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