<?php
// Połączenie z bazą danych
$mysqli = new mysqli("host", "user", "password", "database");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo '
	<div style="overflow: scroll;">
	<form action="tabela_zwyzki_dodaj.php'.$filters_path.'" method="post">	
		<table>';
		
		// Pobieranie danych użytkownika
		if(isset($_SESSION['user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
		}elseif(isset($_COOKIE['ciastko_user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
		}

		$result_header = $mysqli->query($query_header);

		if($result_header === false){
			die("Error in query: " . $mysqli->error);
		}

		$row_header = $result_header->fetch_array(MYSQLI_ASSOC);
		
		if($row_header['header'] == 'n'){
			echo '
			<tr>
				<td id="naglowek" rowspan="2">Bauvorhaben</td>				
				<td id="naglowek" rowspan="2">Vermieter</td>
				<td id="naglowek" rowspan="2">Bühnentyp</td>
				<td id="naglowek" rowspan="2">Arbeitshöhe</td>							
				<td id="naglowek" rowspan="2">Mietpreis</td>
				<td id="naglowek" rowspan="2">MaschinenNr.</td>
				<td id="naglowek" colspan="2">Mietzeitraum</td>
				<td id="naglowek" rowspan="2">auf Stand gesetzt bis zum<input type="checkbox" name="czy_zawieszone_do" value="1" /></td>
				<td id="naglowek" rowspan="2">Standtage</td>
				<td id="naglowek" rowspan="2">Freimeldung <input type="checkbox" name="czy_data_zdania" value="1" /></td>
				<td id="naglowek" rowspan="2">Bemerkungen</td>
				<td id="naglowek" rowspan="2">Ansprechpartner</td>
				<td id="naglowek" rowspan="2">Z</td>
				<td id="naglowek" rowspan="2">Angebot</td>				
				<td id="naglowek" colspan="4">Rechnung I</td>
				<td id="naglowek" colspan="2">Gutschrift</td>
				</tr>
			<tr>
				<td id="naglowek">vom <input type="checkbox" name="czy_data_wynajmu_od" value="1" /></td>
				<td id="naglowek">bis zum <input type="checkbox" name="czy_data_wynajmu_do" value="1" /></td>				
				<td id="naglowek">Nr</td>
				<td id="naglowek">Abrechnungs Zeitraum</td>
				<td id="naglowek">Betrag</td>
				<td id="naglowek">Bemerkungen</td>
				<td id="naglowek">Nr</td>
				<td id="naglowek">Betrag</td>
				<td id="naglowek">Nr</td>
			</tr>
			';
		}else{
			echo '
			<tr>
				<td id="naglowek" rowspan="2">BUDOWA</td>				
				<td id="naglowek" rowspan="2">FIRMA <br />WYNAJMUJĄCA</td>
				<td id="naglowek" rowspan="2">RODZAJ ZWYŻKI</td>
				<td id="naglowek" rowspan="2">WYSOKOŚĆ</td>							
				<td id="naglowek" rowspan="2">CENA</td>
				<td id="naglowek" rowspan="2">NR MASZYNY</td>
				<td id="naglowek" colspan="2">DATA WYNAJMU</td>
				<td id="naglowek" rowspan="2">ZAWIESZONE DO<input type="checkbox" name="czy_zawieszone_do" value="1" /></td>
				<td id="naglowek" rowspan="2">DATA ZAW.</td>
				<td id="naglowek" rowspan="2">DATA ZDANIA <input type="checkbox" name="czy_data_zdania" value="1" /></td>
				<td id="naglowek" rowspan="2">UWAGI</td>
				<td id="naglowek" rowspan="2">OSOBA KONTAKTOWA</td>
				<td id="naglowek" rowspan="2">Z</td>
				<td id="naglowek" rowspan="2">OFERTA</td>				
				<td id="naglowek" colspan="4">RACHUNKI I</td>
				<td id="naglowek" colspan="2">KOREKTY</td>
			</tr>
			<tr>
				<td id="naglowek">OD <input type="checkbox" name="czy_data_wynajmu_od" value="1" /></td>
				<td id="naglowek">DO <input type="checkbox" name="czy_data_wynajmu_do" value="1" /></td>
				<td id="naglowek">NR</td>
				<td id="naglowek">OKR. ROZ.</td>
				<td id="naglowek">KWOTA</td>
				<td id="naglowek">UWAGI</td>
				<td id="naglowek">NR</td>
				<td id="naglowek">KWOTA</td>
			</tr>
			';
		}
		echo '<tr '.kolor_wiersza_zwyzki($row['data_zdania'], $row['zawieszone_do'], $row['zaznaczony_wiersz'], $row['oferta']).'>';
		echo '		
				<td id="komorka_dodawanie"><textarea name="budowa" cols="15" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="firma_wynajmujaca" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="rodzaj_zwyzki" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="wysokosc" cols="8" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="cena" cols="8" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="nr_maszyny" cols="5" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><script>DateInput(\'data_wynajmu_od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_dodawanie"><script>DateInput(\'data_wynajmu_do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_dodawanie"><script>DateInput(\'zawieszone_do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_dodawanie"><textarea name="data_zawiesz" cols="5" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><script>DateInput(\'data_zdania\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_dodawanie"><textarea name="uwagi" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="os_kontaktowa" cols="10" rows="2"></textarea></td>	
				<td id="komorka_dodawanie"><input type="checkbox" name="zaznaczony_wiersz" value="1" /></td>	
				<td id="komorka_dodawanie"><input type="checkbox" name="oferta" value="1" /></td>	
				
				<td id="komorka_dodawanie"><textarea name="rach_1_nr" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="rach_1_okres" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="rach_1_kwota" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="rach_1_uwagi" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="korekta_nr" cols="10" rows="2"></textarea></td>
				<td id="komorka_dodawanie"><textarea name="korekta_kwota" cols="10" rows="2"></textarea></td>
			</tr>
		';

		echo '</table><br />';
		if(isset($_SESSION['user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
		}elseif(isset($_COOKIE['ciastko_user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
		}
		$result_header = $mysqli->query($query_header);
	
		if($result_header === false){
			die("Error in query: " . $mysqli->error);
		}
		$row_header = $result_header->fetch_array(MYSQLI_ASSOC);
	
		if($row_header['header'] == 'n'){
			echo '<input type="submit"  value="neuer Eintrag" name="dodaj" />
			<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Dodaj" name="dodaj" />
			<input type="submit"  value="Powrót" name="powrot" />';
		}
		echo '</form>
		</div>';	

// Zamknięcie połączenia z bazą
$mysqli->close();
?>
