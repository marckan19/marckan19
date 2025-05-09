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
				echo '<td><h2>Baustellen – neuen Baustelle erstellen</h2></td><td width="20px" />';		
			}else{
				echo '<td><h2>Wyjazdy – dodawanie budowy</h2></td><td width="20px" />';		
			}
			//require_once('legenda_budowy.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_wyjazdy_budowy.php'.$filters_path);
	}elseif(isset($_POST['dodaj'])){
		
		$budowa = $_POST['budowa'];
		if($_POST['czy_od'] == 1){
			$od = $_POST['od'];
		}else{
			$od = '';
		}	

		if($_POST['czy_do'] == 1){
			$do = $_POST['do'];
		}else{
			$do = '';
		}	
		$kolor = $_POST['kolor'];		
		$creation_date = date('Y-m-d G:i:s');		
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}
		
		$query_insert = "INSERT INTO panel_tabela_wyjazdy_budowy (budowa, od, do, kolor, creator_id, creation_date, modificator_id, modification_date)
						VALUES (
							'".$budowa."', 
							'".$od."', 						
							'".$do."', 							
							'".$kolor."', 							
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
		
		header('Location: tabela_wyjazdy_budowy_dodano.php?id='.mysql_insert_id().$filters_path);
		
	}else{
		echo '<form action="tabela_wyjazdy_budowy_dodaj.php'.$filters_path.'" method="post">	
		<table>';
		if($row_header['header'] == 'n'){
			echo '<tr>
				<td id="naglowek">Baustelle</td>
				<td id="naglowek">bis<input type="checkbox" name="czy_od" value="1" /></td>
				<td id="naglowek">zum<input type="checkbox" name="czy_do" value="1" /></td>				
				<td id="naglowek">Farbe</td>				
			</tr>';
		}else{
			echo '<tr>
				<td id="naglowek">BUDOWA</td>
				<td id="naglowek">OD<input type="checkbox" name="czy_od" value="1" /></td>
				<td id="naglowek">DO<input type="checkbox" name="czy_do" value="1" /></td>				
				<td id="naglowek">KOLOR</td>				
			</tr>';
		}
		echo '<tr style="background-color: #00FF00;">';
		echo '		
				<td id="komorka_dodawanie"><input name="budowa" /></td>
				<td id="komorka_dodawanie"><script>DateInput(\'od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_dodawanie"><script>DateInput(\'do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_dodawanie">';
					require_once('tabela_wyjazdy_budowy_kolory.php');
			echo '</td>
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