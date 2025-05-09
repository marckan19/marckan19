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
				echo '<td><h2>Wartungsverträge - neuen Eintrag erstellen</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Umowy konserwacyjne - dodawanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_noclegi.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_konserwacje.php'.$filters_path);
	}elseif(isset($_POST['dodaj'])){
		
		$budowa = $_POST['budowa'];
		$kontrahent = $_POST['kontrahent'];	
		$data = $_POST['data'];
		$uwagi = $_POST['uwagi'];
		$informacja = $_POST['informacja'];
		$creation_date = date('Y-m-d G:i:s');
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
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
		
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
			$filters_path = '&'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_konserwacje_dodano.php?id='.mysql_insert_id().$filters_path);
		
	}else{
		echo '<form action="tabela_konserwacje_dodaj.php'.$filters_path.'" method="post">	
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
					</tr>
				';
			}else{
				echo '
					<tr>
						<td id="naglowek">BUDOWA</td>
						<td id="naglowek">KONTRAHENT</td>								
						<td id="naglowek">DATA</td>
						<td id="naglowek">UWAGI</td>												
						<td id="naglowek">INFORMACJA</td>
					</tr>					
				';
			}
		//echo '<tr '.kolor_wiersza_noclegi($row['okres_wynajmu_do'], $row['zaznaczony_wiersz']).'>
			echo '<tr '.kolor_wiersza_konserwacje().'>
				<td id="komorka_dodawanie"><textarea name="budowa" cols="48" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="kontrahent" cols="48" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><script>DateInput(\'data\', true, \'YYYY-MM-DD\')</script></td>								
				<td id="komorka_dodawanie"><textarea name="uwagi" cols="48" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="informacja" cols="48" rows="2"></textarea></td>	
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
			<input type="submit"  value="Powrót" name="powrot" /><br /><br />';
		}
		echo '</form>';	
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');