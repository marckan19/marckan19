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
				echo '<td><h2>Wartungsverträge - den Eintrag kopieren</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Umowy konserwacyjne - kopiowanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['powrot'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_konserwacje.php'.$filters_path);
	}elseif(isset($_POST['kopiuj'])){		
		$budowa = $_POST['budowa'];
		$kontrahent = $_POST['kontrahent'];				

		$uwagi = $_POST['uwagi'];
		$informacja = $_POST['informacja'];
		$creation_date = date('Y-m-d G:i:s');

		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}
		if($_POST['data'] == 1){
			$data = $_POST['data'];
		}else{
			$data = $_POST['data_stara'];
		}
		
		$modification_date = date('Y-m-d G:i:s');

		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
		
		$query_insert = "INSERT INTO panel_tabela_konserwacje (budowa, kontrahent, data, uwagi, informacja, creator_id, creation_date, modificator_id, modification_date)
						VALUES (
							'".$budowa."', 
							'".$kontrahent."', 							
							'".$data."', 	
							'".$uwagi."', 
							'".$informacja."',
							".$creator_id.",
							'".$creation_date."',
							".$creator_id.",
							'".$creation_date."'
						);";
		mysql_query($query_insert);
		
		header('Location: tabela_konserwacje_skopiowano.php?id='.mysql_insert_id().$filters_path);
		
	}else{
		echo '<form action="tabela_konserwacje_kopiuj.php?id='.$id.$filters_path.'" method="post">	
		<table width="100%">';
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
				<td id="naglowek">Vertragspartner</td>								
				<td id="naglowek">Datum</td>
				<td id="naglowek">Bemerkung</td>												
				<td id="naglowek">Vermerk</td>
				<td id="naglowek">Datum der Eintragung</td>
				<td id="naglowek">LOGIN</td>
			</tr>';
		}else{
			echo '
			<tr>
				<td id="naglowek">BUDOWA</td>
				<td id="naglowek">KONTRAHENT</td>								
				<td id="naglowek">DATA</td>
				<td id="naglowek">UWAGI</td>												
				<td id="naglowek">INFORMACJA</td>
				<td id="naglowek">DATA WPISU</td>
				<td id="naglowek">LOGIN</td>
			</tr>';
		}
		
		$query = "SELECT * FROM panel_tabela_konserwacje WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_konserwacje().'>
				<td id="komorka_edytowanie"><textarea name="budowa" cols="50" rows="2">'.$row['budowa'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="kontrahent" cols="50" rows="2">'.$row['kontrahent'].'</textarea></td>
				<td id="komorka_edytowanie"><input type="text" name="data_stara" value="'.$row['data'].'" /><br /><script>DateInput(\'data\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><textarea name="uwagi" cols="50" rows="2">'.$row['uwagi'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="informacja" cols="50" rows="2">'.$row['informacja'].'</textarea></td>
				<td id="komorka_edytowanie">'.substr($row['creation_date'], 0, 10).'</td>
				<td id="komorka_edytowanie">'.$row_login['login'].'</td>								
			</tr>';

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