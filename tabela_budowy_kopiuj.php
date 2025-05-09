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
				echo '<td><h2>Bauvorhaben - den Eintrag kopieren</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Budowy - kopiowanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_budowy.php'.$filters_path);
	}elseif(isset($_POST['kopiuj'])){		
		$zleceniodawca = $_POST['zleceniodawca'];
		$wys_hali = $_POST['wys_hali'];
		if($_POST['czy_realizacja_od'] == 1){
			$realizacja_od = $_POST['realizacja_od'];
		}else{
			$realizacja_od = $_POST['realizacja_od_stara'];
		}		
		if($_POST['czy_realizacja_do'] == 1){
			$realizacja_do = $_POST['realizacja_do'];
		}else{
			$realizacja_do = $_POST['realizacja_do_stara'];
		}		
		$kierownik = $_POST['kierownik'];		
		$dokumenty = $_POST['dokumenty'];
		$tydzien = $_POST['tydzien'];
		
		$rewizja = $_POST['rewizja'];
		$stan = $_POST['stan'];
		$uwagi = $_POST['uwagi'];
		$suplementy = $_POST['suplementy'];
		$adnotacje = $_POST['adnotacje'];
		$creation_date = date('Y-m-d G:i:s');

		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}
		if($_POST['zaznaczony_wiersz'] == 1){
			$zaznaczony_wiersz = 1;
		}else{
			$zaznaczony_wiersz = 0;
		}
		
		$query_insert = "INSERT INTO panel_tabela_budowy (zleceniodawca, wys_hali, realizacja_od, realizacja_do, kierownik, dokumenty, tydzien, rewizja, stan, creator_id, creation_date, modificator_id, modification_date, zaznaczony_wiersz, uwagi, suplementy, adnotacje)
						VALUES (
							'".$zleceniodawca."', 
							'".$wys_hali."', 
							'".$realizacja_od."', 						
							'".$realizacja_do."', 							
							'".$kierownik."', 							 							
							'".$dokumenty."', 							 							
							'".$tydzien."', 					

							'".$rewizja."',
							'".$stan."',
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."',
							".$zaznaczony_wiersz.",
							'".$uwagi."',
							'".$suplementy."',
							'".$adnotacje."'
						);";
		mysql_query($query_insert);
		
		header('Location: tabela_budowy_skopiowano.php?id='.mysql_insert_id().$filters_path);
		
	}else{
		echo '<form action="tabela_budowy_kopiuj.php?id='.$id.$filters_path.'" method="post">	
		<table width="100%">
			<tr>
				<td id="naglowek" rowspan="2" width="250px">BAUVORHABEN - AUFTRAGGEBER / BUDOWA - ZLECENIODAWCA</td>
				<td id="naglowek" rowspan="2">Höhe der Halle /<br />WYS. HALI</td>
				<td id="naglowek" colspan="2">AUSFÜHRUNGSZEIT / TERMIN REALIZACJI</td>
				<td id="naglowek" rowspan="2">BAULEITER / KIEROWNIK</td>
				<td id="naglowek" rowspan="2">UNTERLAGEN / DOKUMENTY</td>			
				<td id="naglowek" rowspan="2">BAU-TAGESBERICHTE KW-</td>
				<td id="naglowek" rowspan="2" width="100px">REVI / REWIZJA</td>
				<td id="naglowek" rowspan="2" width="100px">Aktueller Stand</td>
				<td id="naglowek" rowspan="2" width="100px">UWAGI</td>
				<td id="naglowek" rowspan="2" width="100px">Nachträge</td>
				<td id="naglowek" rowspan="2" width="100px">VERMERK</td>
				<td id="naglowek" rowspan="2">DATA WPISU</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
				<td id="naglowek" rowspan="2">Z</td>
			</tr>
			<tr>
				<td id="naglowek" width="75px">VOM<input type="checkbox" name="czy_realizacja_od" value="1" /></td>
				<td id="naglowek" width="75px">BISZUM<input type="checkbox" name="czy_realizacja_do" value="1" /></td>

			</tr>
		';
		
		$query = "SELECT * FROM panel_tabela_budowy WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		//SPRAWDZANIE ZAZNACZONEGO WIERSZA
		if ($row['zaznaczony_wiersz'] == 1){
			$zw = 'checked="checked"';
		}else{
			$zw = '';
		}
		
		echo '<tr '.kolor_wiersza_budowy($row['realizacja_od'], $row['realizacja_do'], $row['zaznaczony_wiersz']).'>
				<td id="komorka_edytowanie"><textarea name="zleceniodawca" cols="15" rows="2">'.$row['zleceniodawca'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="wys_hali" cols="10" rows="2">'.$row['wys_hali'].'</textarea></td>
				<td id="komorka_edytowanie"><input type="text" size="18" name="realizacja_od_stara" value="'.$row['realizacja_od'].'" /><br /><script>DateInput(\'realizacja_od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><input type="text" size="18" name="realizacja_do_stara" value="'.$row['realizacja_do'].'" /><br /><script>DateInput(\'realizacja_do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><textarea name="kierownik" cols="3" rows="2">'.$row['kierownik'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="dokumenty" cols="13" rows="3">'.$row['dokumenty'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="tydzien" cols="3" rows="2">'.$row['tydzien'].'</textarea></td>
				
				<td id="komorka_edytowanie"><textarea name="rewizja" cols="10" rows="2">'.$row['rewizja'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="stan" cols="10" rows="2">'.$row['stan'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="uwagi" cols="13" rows="2">'.$row['uwagi'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="suplementy" cols="13" rows="2">'.$row['suplementy'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="adnotacje" cols="13" rows="2">'.$row['adnotacje'].'</textarea></td>
				<td id="komorka_edytowanie">'.substr($row['creation_date'], 0, 10).'</td>
				<td id="komorka_edytowanie">'.$row_login['login'].'</td>				
				<td id="komorka_edytowanie"><input type="checkbox" name="zaznaczony_wiersz" value="1" '.$zw.' /></td>		
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
			echo '<input type="submit"  value="Kopieren" name="kopiuj" />
			<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Kopiuj" name="kopiuj" />
			<input type="submit"  value="Powrót" name="powrot" />';
		}
		echo '</form>';	
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');