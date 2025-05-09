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
				echo '<td><h2>Baustellen – neuen Dienstwagen erstellen</h2></td><td width="20px" />';		
			}else{
				echo '<td><h2>Wyjazdy – dodawanie samochodu</h2></td><td width="20px" />';		
			}
			//require_once('legenda_samochody.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_wyjazdy_samochody.php'.$filters_path);
	}elseif(isset($_POST['dodaj'])){
		
		$samochod = $_POST['samochod'];
		$rejestracja = $_POST['rejestracja'];
		$creation_date = date('Y-m-d G:i:s');		
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}
		
		$query_insert = "INSERT INTO panel_tabela_wyjazdy_samochody (samochod, rejestracja, creator_id, creation_date, modificator_id, modification_date)
						VALUES (
							'".$samochod."', 
							'".$rejestracja."', 													
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
		
		header('Location: tabela_wyjazdy_samochody_dodano.php?id='.mysql_insert_id().$filters_path);
		
	}else{
		echo '<form action="tabela_wyjazdy_samochody_dodaj.php'.$filters_path.'" method="post">	
		<table>';
		if($row_header['header'] == 'n'){
			echo '<tr>
				<td id="naglowek">Dienstwagen</td>
				<td id="naglowek">amtl. Kennzeichen</td>
			</tr>';
		}else{
			echo '<tr>
				<td id="naglowek">SAMOCHÓD</td>
				<td id="naglowek">REJESTRACJA</td>
			</tr>';
		}
		echo '<tr style="background-color: #00FF00;">';
		echo '		
				<td id="komorka_dodawanie"><input name="samochod" /></td>
				<td id="komorka_dodawanie"><input name="rejestracja" /></td>				
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