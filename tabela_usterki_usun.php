<?php
ob_start();
session_start();
require_once('header.php');

//if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a'){
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
	
	//TLUMACZENIE
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
				echo '<td><h2>den Eintrag löschen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>usuwanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['usun'])){
	
		$query_img = "SELECT * FROM panel_tabela_usterki_images WHERE id_usterki = ".$id.";";	
		$result_img = mysql_query($query_img) or die (mysql_error());
		while($row_img = mysql_fetch_array($result_img,MYSQL_ASSOC)){
			unlink($row_img['path']);
			//echo '/'.$row_img['path'];
		}
		
		$query_delete_img = "DELETE FROM panel_tabela_usterki_images WHERE id_usterki = ".$id.";";
		mysql_query($query_delete_img);
		
		$query_delete = "DELETE FROM panel_tabela_usterki WHERE id = ".$id.";";
		mysql_query($query_delete);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_usterki_usunieto.php'.$filters_path);
		
	}elseif(isset($_POST['nie_usuwaj'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
	
		header('Location: tabela_usterki.php'.$filters_path);
	}else{
		echo '<form action="tabela_usterki_usun.php?id='.$id.$filters_path.'" method="post">	
		<table>';
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
					<td id="naglowek" width="150px">gemeldet am</td>			
					<td id="naglowek" width="150px">Beseitigungsfirst</td>			
					<td id="naglowek" width="150px">beseitigt am</td>
					<td id="naglowek">beseitigt von</td>
					<td id="naglowek">Anmerkungen</td>
					<td id="naglowek">benötigte Gerätschaften: Arbeitsbühne, Regal, Leiter</td>
					<td id="naglowek" width="150px">eingetragen am</td>
					<td id="naglowek" width="150px">LOGIN</td>		
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
			';	
		}
		
		echo '</tr>';
		
		$query = "SELECT u.login AS login, ptn.* FROM panel_tabela_usterki ptn
			JOIN panel_users u ON u.id = ptn.creator_id
			WHERE ptn.id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_usterki($row['data_usuniecia'], $row['termin_usuniecia']).'>';
		echo '			
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
				';
				
			echo '</tr>
		';

		//echo '</table><br />Czy na pewno chcesz usunąć ten wpis?<br /><br /><input type="submit"  value="Tak" name="usun" /> <input type="submit"  value="Nie" name="nie_usuwaj" /></form>';	
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