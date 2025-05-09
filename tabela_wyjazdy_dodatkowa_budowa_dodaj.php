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
	
	if(isset($_GET['id'])){
		$id_wpisu = $_GET['id'];
	}else{
		$id_wpisu = 0;
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
		
		//$is_main = 1;
		$budowa_id = $_POST['budowa_id'];
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
		
		//DODANIE DO TABELI BUDOWY_POWIAZANIA				
		$query_insert_budowa_powiazanie = "INSERT INTO panel_tabela_wyjazdy_budowy_powiazania (
			budowa_id, 
			wpis_id, 		
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
		
		$creation_date = date('Y-m-d G:i:s');		
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}		
		$query_update = "UPDATE panel_tabela_wyjazdy_wpisy SET modificator_id = ".$creator_id.", modification_date = '".$creation_date."' WHERE id = ".$id_wpisu.";";
		mysql_query($query_update);	
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
			$filters_path = '&'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_wyjazdy_dodatkowa_budowa_dodano.php?id='.$id_wpisu);
		
	}else{
		//require_once('menu_wyjazdy.php');
		echo '<form action="tabela_wyjazdy_dodatkowa_budowa_dodaj.php?id='.$id_wpisu.'" method="post">	
		<table>';
			if($row_header['header'] == 'n'){
				echo '<tr>
				<td id="naglowek">Baustelle</td>
				<td id="naglowek">Auf Stand<br />setzen</td>
				<td id="naglowek">freimelden</td>
				<td id="naglowek">bestellen</td>
				<td id="naglowek">Bauvorhaben bis</td>
				<td id="naglowek">Bauvorhaben zum</td>				
				<td id="naglowek">Bemerkungen</td>
				</tr>';
			}else{
				echo '<tr>				
				<td id="naglowek">BUDOWA</td>
				<td id="naglowek">ZAWIESIĆ</td>
				<td id="naglowek">ZDAĆ</td>
				<td id="naglowek">ZAMÓWIĆ</td>				
				<td id="naglowek">BUDOWA OD<input type="checkbox" name="czy_budowa_od" value="1" /></td>
				<td id="naglowek">BUDOWA DO<input type="checkbox" name="czy_budowa_do" value="1" /></td>				
				<td id="naglowek">UWAGI</td>
			</tr>';
			}
								
		//WYCIAGNIECIE KOLORU BUDOWY
				$query_kolor = "
						SELECT ptwb.*
						FROM panel_tabela_wyjazdy_budowy  ptwb			
						JOIN panel_tabela_wyjazdy_budowy_powiazania ptwbp ON ptwbp.budowa_id = ptwb.id		
						WHERE ptwbp.wpis_id = ".$id_wpisu." AND ptwbp.is_main = '1'		
												
						LIMIT 1;";
				$result_kolor = mysql_query($query_kolor) or die (mysql_error());
				$row_kolor = mysql_fetch_array($result_kolor,MYSQL_ASSOC);
				
		echo '<tr '.kolor_wiersza_wyjazdy($row_kolor['kolor']).'>';
		
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