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
				echo '<td><h2>Festpunkte - den Eintrag bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Punkty stałe - edytowanie wpisu</h2></td><td width="20px" />';			
			}
			require_once('legenda_punkty.php');
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		header('Location: tabela_punkty.php'.$filters_path);
	}elseif(isset($_POST['edytuj'])){		
		$budowa_adres = $_POST['budowa_adres'];
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
		$personel = $_POST['personel'];
		$data_sporzadzenia_dokumentacji = $_POST['data_sporzadzenia_dokumentacji'];
		$data_odeslanej_dokumentacji = $_POST['data_odeslanej_dokumentacji'];
		$kwota_kontraktu = $_POST['kwota_kontraktu'];
		$data_przeslania_awiza = $_POST['data_przeslania_awiza'];
		$data_zaplaty = $_POST['data_zaplaty'];
		$uwagi = $_POST['uwagi'];
		$modification_date = date('Y-m-d G:i:s');

		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
				
		$query_update = "UPDATE panel_tabela_punkty
						SET
							budowa_adres = '".$budowa_adres."',  
							realizacja_od = '".$realizacja_od."',
							realizacja_do = '".$realizacja_do."', 
							personel = '".$personel."', 
							data_sporzadzenia_dokumentacji = '".$data_sporzadzenia_dokumentacji."', 
							data_odeslanej_dokumentacji = '".$data_odeslanej_dokumentacji."', 							
							kwota_kontraktu = '".$kwota_kontraktu."',
							data_przeslania_awiza = '".$data_przeslania_awiza."',
							data_zaplaty = '".$data_zaplaty."',
							uwagi = '".$uwagi."',
							modificator_id = ".$modificator_id.",
							modification_date = '".$modification_date."'
						WHERE id = ".$id."						
						;";
		mysql_query($query_update);
		
		header('Location: tabela_punkty_edytowano.php?id='.$id.$filters_path);
		
	}else{
		echo '<form action="tabela_punkty_edytuj.php?id='.$id.$filters_path.'" method="post">	
		<table width="100%">
			<tr>
				<td id="naglowek" rowspan="2">BAUVORHABEN + ADRESSE /<br />BUDOWA + ADRES (MICHAEL)</td>
				<td id="naglowek" colspan="2">AUSFÜHRUNGSTERMIN/<br />TERMIN REALIZACJI (MACIEK B.)</td>
				<td id="naglowek" rowspan="2">PERSONAL /<br />PERSONEL (MACIEK B.)</td>
				<td id="naglowek" rowspan="2">ERSTELLUNGSDATUM DER ABNAHMEDOKUMENTATION SEITENS BRASSCO /<br />DATA SPORZĄDZENIA DOKUMENTACJI PRZEZ BRASSCO (MICHAEL)</td>
				<td id="naglowek" rowspan="2">DATUM DER RÜCKSENDUNG DER ABNAHMEDOKUMENTATION UNTERSCHRIEBEN DURCH AUFTRAGGEBER /<br />DATA ODESŁANEJ DOKUMENTACJI, PODPISANEJ PRZEZ ZLECENIODAWCĘ (MICHAEL)</td>			
				<td id="naglowek" rowspan="2">AUFTRAGSWERT /<br />KWOTA KONTRAKTU (MICHAEL)</td>
				<td id="naglowek" rowspan="2">DATUM DES EINGANGS DES AVIS /<br />DATA PRZESŁANIA AWIZA PŁATNOŚCI</td>
				<td id="naglowek" rowspan="2">DATUM DES ZAHLUNGSEINGANGS /<br />DATA ZAPŁATY</td>
				<td id="naglowek" rowspan="2">VERMERKE/<br />UWAGI</td>
				<td id="naglowek" rowspan="2" width="75px">DATA WPISU</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
			</tr>
			<tr>
				<td id="naglowek" width="75px">VON/OD<input type="checkbox" name="czy_realizacja_od" value="1" /></td>
				<td id="naglowek" width="75px">BIS/DO<input type="checkbox" name="czy_realizacja_do" value="1" /></td>
			</tr>
		';
		
		$query = "SELECT * FROM panel_tabela_punkty WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		echo '<tr '.kolor_wiersza_punkty($row['realizacja_od'], $row['realizacja_do']).'>
				<td id="komorka_edytowanie"><textarea name="budowa_adres" cols="15" rows="2">'.$row['budowa_adres'].'</textarea></td>
				<td id="komorka_edytowanie"><input type="text" size="18" name="realizacja_od_stara" value="'.$row['realizacja_od'].'" /><br /><script>DateInput(\'realizacja_od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><input type="text" size="18" name="realizacja_do_stara" value="'.$row['realizacja_do'].'" /><br /><script>DateInput(\'realizacja_do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><textarea name="personel" cols="15" rows="2">'.$row['personel'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="data_sporzadzenia_dokumentacji" cols="40" rows="2">'.$row['data_sporzadzenia_dokumentacji'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="data_odeslanej_dokumentacji" cols="50" rows="2">'.$row['data_odeslanej_dokumentacji'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="kwota_kontraktu" cols="15" rows="2">'.$row['kwota_kontraktu'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="data_przeslania_awiza" cols="10" rows="2">'.$row['data_przeslania_awiza'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="data_zaplaty" cols="18" rows="2">'.$row['data_zaplaty'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="uwagi" cols="13" rows="2">'.$row['uwagi'].'</textarea></td>
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