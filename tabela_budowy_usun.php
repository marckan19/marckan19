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
	
	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
			
	
	
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Bauvorhaben - den Eintrag löschen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Budowy - usuwanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['usun'])){
		
		$query_delete = "DELETE FROM panel_tabela_budowy WHERE id = ".$id.";";
		mysql_query($query_delete);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_budowy_usunieto.php'.$filters_path);
		
	}elseif(isset($_POST['nie_usuwaj'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
	
		header('Location: tabela_budowy.php'.$filters_path);
	}else{
		echo '<form action="tabela_budowy_usun.php?id='.$id.$filters_path.'" method="post">	
		<table>
			<tr>
				<td id="naglowek" rowspan="2" width="250px">BAUVORHABEN - AUFTRAGGEBER / BUDOWA - ZLECENIODAWCA</td>
				<td id="naglowek" rowspan="2">Höhe der Halle /<br /> WYS. HALI</td>
				<td id="naglowek" colspan="2">AUSFÜHRUNGSZEIT /<br /> TERMIN REALIZACJI</td>
				<td id="naglowek" rowspan="2">BAULEITER /<br /> KIEROWNIK</td>
				<td id="naglowek" rowspan="2">UNTERLAGEN /<br /> DOKUMENTY</td>			
				<td id="naglowek" rowspan="2">BAU-TAGESBERICHTE KW-</td>
				<td id="naglowek" colspan="3" width="100px">MÄNGEL / USTERKI</td>
				<td id="naglowek" rowspan="2" width="100px">REVI / REWIZJA</td>
				<td id="naglowek" rowspan="2" width="100px">Aktueller Stand</td>
				<td id="naglowek" rowspan="2" width="100px">UWAGI</td>
				<td id="naglowek" rowspan="2" width="100px">Nachträge</td>
				<td id="naglowek" rowspan="2" width="100px">VERMERK</td>
				<td id="naglowek" rowspan="2" width="75px">DATA WPISU</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
			</tr>
			<tr>
				<td id="naglowek" width="75px">VOM</td>
				<td id="naglowek" width="75px">BISZUM</td>
				<td id="naglowek" width="75px">ZGŁOSZONO</td>
				<td id="naglowek" width="75px">USUNIĘTO</td>
				<td id="naglowek" width="75px">OSOBA</td>		
			</tr>
		';
		
		$query = "SELECT * FROM panel_tabela_budowy WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '
				<tr '.kolor_wiersza_budowy($row['realizacja_od'], $row['realizacja_do'], $row['zaznaczony_wiersz']).'>
					<td>'.nl2br($row['zleceniodawca']).'</td>
					<td>'.nl2br($row['wys_hali']).'</td>
					<td>'.nl2br($row['realizacja_od']).'</td>
					<td>'.nl2br($row['realizacja_do']).'</td>					
					<td>'.nl2br($row['kierownik']).'</td>				
					<td>'.nl2br($row['dokumenty']).'</td>				
					<td>'.nl2br($row['tydzien']).'</td>			
					<td>'.nl2br($row['usterka_zgloszenie']).'</td>
					<td>'.nl2br($row['usterka_usuniecie']).'</td>
					<td>'.nl2br($row['usterka_osoba']).'</td>
					<td>'.nl2br($row['rewizja']).'</td>			
					<td>'.nl2br($row['stan']).'</td>			
					<td>'.nl2br($row['uwagi']).'</td>			
					<td>'.nl2br($row['suplementy']).'</td>			
					<td>'.nl2br($row['adnotacje']).'</td>			
					<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row_login['login'].'</td>
				</tr>
		';
		
		if($row_header['header'] == 'n'){
			echo '</table><br />Möchten Sie den Eintrag löschen?<br /><br /><input type="submit"  value="Ja" name="usun" /> <input type="submit"  value="Nein" name="nie_usuwaj" /></form>';	
		}else{
			echo '</table><br />Czy na pewno chcesz usunąć ten wpis?<br /><br /><input type="submit"  value="Tak" name="usun" /> <input type="submit"  value="Nie" name="nie_usuwaj" /></form>';	
		}
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');