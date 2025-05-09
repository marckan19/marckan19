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
				echo '<td><h2>Baustellen - Baustelle löschen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - usuwanie budowy</h2></td><td width="20px" />';			
			}
			//require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['usun'])){
		
		$query_delete = "DELETE FROM panel_tabela_wyjazdy_budowy WHERE id = ".$id.";";
		mysql_query($query_delete);
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_wyjazdy_budowy_usunieto.php'.$filters_path);
		
	}elseif(isset($_POST['nie_usuwaj'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
	
		header('Location: tabela_wyjazdy_budowy.php'.$filters_path);
	}else{
		echo '<form action="tabela_wyjazdy_budowy_usun.php?id='.$id.$filters_path.'" method="post">	
		<table>';
		if($row_header['header'] == 'n'){
			echo '		
			<td id="naglowek">Baustelle</td>
			<td id="naglowek">bis</td>
			<td id="naglowek">zum</td>			
			<td id="naglowek">Tag der<br />Eintragung</td>			
			<td id="naglowek">LOGIN</td>			
			';
		}else{
			echo '<tr>
				<td id="naglowek">BUDOWA</td>
				<td id="naglowek">OD</td>
				<td id="naglowek">DO</td>
				<td id="naglowek">DATA WPISU</td>
				<td id="naglowek">LOGIN</td>
			</tr>';
		}
		
		$query = "SELECT * FROM panel_tabela_wyjazdy_budowy WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '
				<tr '.kolor_wiersza_wyjazdy_budowy($row['kolor']).'>
					<td>'.nl2br($row['budowa']).'</td>
					<td>'.nl2br($row['od']).'</td>
					<td>'.nl2br($row['do']).'</td>												
					<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row_login['login'].'</td>
				</tr>
		';
		
		if($row_header['header'] == 'n'){
			echo '</table><br />Möchten Sie Baustelle löschen? <br /><br /><input type="submit"  value="Ja" name="usun" /> <input type="submit"  value="Nein" name="nie_usuwaj" /></form>';	
		}else{
			echo '</table><br />Czy na pewno chcesz usunąć tą budowę?<br /><br /><input type="submit"  value="Tak" name="usun" /> <input type="submit"  value="Nie" name="nie_usuwaj" /></form>';	
		}
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');