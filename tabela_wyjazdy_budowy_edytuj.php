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
				echo '<td><h2>Baustellen – Baustelle bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Wyjazdy - edytowanie budowy</h2></td><td width="20px" />';			
			}
			//require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_wyjazdy_budowy.php'.$filters_path);
	}elseif(isset($_POST['edytuj'])){		
		$budowa = $_POST['budowa'];		
		if($_POST['czy_od'] == 1){
			$od = $_POST['od'];
		}else{
			$od = $_POST['od_stara'];
		}		
		if($_POST['czy_do'] == 1){
			$do = $_POST['do'];
		}else{
			$do = $_POST['do_stara'];
		}		
		$kolor = $_POST['kolor'];
		$modification_date = date('Y-m-d G:i:s');
		
		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
				
		$query_update = "UPDATE panel_tabela_wyjazdy_budowy
						SET
							budowa = '".$budowa."', 
							od = '".$od."',
							do = '".$do."', 							
							kolor = '".$kolor."', 							
							modificator_id = ".$modificator_id.",
							modification_date = '".$modification_date."'							
						WHERE id = ".$id."						
						;";
		mysql_query($query_update);
		
		header('Location: tabela_wyjazdy_budowy_edytowano.php?id='.$id.$filters_path);
		
	}else{
		echo '<form action="tabela_wyjazdy_budowy_edytuj.php?id='.$id.$filters_path.'" method="post">	
		<table>';
		if($row_header['header'] == 'n'){
			echo '<tr>
				<td id="naglowek">Baustelle</td>
				<td id="naglowek">bis<input type="checkbox" name="czy_od" value="1" /></td>
				<td id="naglowek">zum<input type="checkbox" name="czy_do" value="1" /></td>				
				<td id="naglowek">Farbe</td>				
				<td id="naglowek">Tag der<br />Eintragung</td>
				<td id="naglowek">LOGIN</td>				
			</tr>';
		}else{
			echo '<tr>
				<td id="naglowek">BUDOWA</td>
				<td id="naglowek">OD<input type="checkbox" name="czy_od" value="1" /></td>
				<td id="naglowek">DO<input type="checkbox" name="czy_do" value="1" /></td>				
				<td id="naglowek">KOLOR</td>				
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
		
		echo '<tr '.kolor_wiersza_wyjazdy_budowy($row['kolor']).'>
				<td id="komorka_edytowanie"><input name="budowa" value="'.$row['budowa'].'" /></td>
				<td id="komorka_edytowanie"><input type="text" size="18" name="od_stara" value="'.$row['od'].'" /><br /><script>DateInput(\'od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><input type="text" size="18" name="do_stara" value="'.$row['do'].'" /><br /><script>DateInput(\'do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie">';
					require_once('tabela_wyjazdy_budowy_kolory.php');
			echo '</td>
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