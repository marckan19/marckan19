<?php

echo '
	<div style="overflow: scroll;">
	<form action="tabela_zwyzki_kopiuj.php?id='.$id.$filters_path.'" method="post">	
		<table width="100%">';
		
		if(isset($_SESSION['user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
		}elseif(isset($_COOKIE['ciastko_user_id'])){
			$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
		}
		$result_header = mysql_query($query_header) or die (mysql_error());
		$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
		if($row_header['header'] == 'n'){
			echo'
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
				<td id="naglowek" rowspan="2">Datum der Eintragung</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
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
			echo'
			<tr>
				<td id="naglowek" rowspan="2">BUDOWA</td>
				<td id="naglowek" rowspan="2">FIRMA WYNAJMUJĄCA</td>
				<td id="naglowek" rowspan="2">RODZAJ ZWYŻKI</td>
				<td id="naglowek" rowspan="2">WYSOKOŚĆ</td>							
				<td id="naglowek" rowspan="2">CENA</td>
				<td id="naglowek" rowspan="2">NR MASZYNY</td>
				<td id="naglowek" colspan="2">DATA WYNAJMU</td>
				<td id="naglowek" rowspan="2">ZAWIESZONE DO <input type="checkbox" name="czy_zawieszone_do" value="1" /></td>
				<td id="naglowek" rowspan="2">DATA ZAWIESZENIA</td>
				<td id="naglowek" rowspan="2">DATA ZDANIA <input type="checkbox" name="czy_data_zdania" value="1" /></td>
				<td id="naglowek" rowspan="2">UWAGI</td>
				<td id="naglowek" rowspan="2">OSOBA KONTAKTOWA</td>
				<td id="naglowek" rowspan="2">DATA WPISU</td>
				<td id="naglowek" rowspan="2">LOGIN</td>
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
				<td id="naglowek">NR</td>
			</tr>
			';
		}
		
		$query = "SELECT * FROM panel_tabela_zwyzki WHERE id = ".$id.";";
		$result = mysql_query($query) or die (mysql_error());		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$row['creator_id'].";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);
		
		//SPRAWDZANIE ZAZNACZONEGO WIERSZA
		if ($row['zaznaczony_wiersz'] == 1){
			$zw = 'checked="checked"';
		}else{
			$zw = '';
		}
		
		//SPRAWDZANIE OFERTY
		if ($row['oferta'] == 1){
			$of = 'checked="checked"';
		}else{
			$of = '';
		}
		
		echo '<tr '.kolor_wiersza_zwyzki($row['data_zdania'], $row['zawieszone_do'], $row['zaznaczony_wiersz'], $row['oferta']).'>
				<td id="komorka_edytowanie"><textarea name="budowa" cols="10" rows="2">'.$row['budowa'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="firma_wynajmujaca" cols="10" rows="2">'.$row['firma_wynajmujaca'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="rodzaj_zwyzki" cols="8" rows="2">'.$row['rodzaj_zwyzki'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="wysokosc" cols="8" rows="2">'.$row['wysokosc'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="cena" cols="8" rows="2">'.$row['cena'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="nr_maszyny" cols="5" rows="2">'.$row['nr_maszyny'].'</textarea></td>
				<td id="komorka_edytowanie"><input type="text" name="data_wynajmu_od_stara" value="'.$row['data_wynajmu_od'].'" /><br /><script>DateInput(\'data_wynajmu_od\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><input type="text" name="data_wynajmu_do_stara" value="'.$row['data_wynajmu_do'].'" /><br /><script>DateInput(\'data_wynajmu_do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><input type="text" name="zawieszone_do_stara" value="'.$row['zawieszone_do'].'" /><br /><script>DateInput(\'zawieszone_do\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><textarea name="data_zawiesz" cols="10" rows="2">'.$row['data_zawiesz'].'</textarea></td>
				<td id="komorka_edytowanie"><input type="text" name="data_zdania_stara" value="'.$row['data_zdania'].'" /><br /><script>DateInput(\'data_zdania\', true, \'YYYY-MM-DD\')</script></td>
				<td id="komorka_edytowanie"><textarea name="uwagi" cols="20" rows="2">'.$row['uwagi'].'</textarea></td>
				<td id="komorka_edytowanie"><textarea name="os_kontaktowa" cols="10" rows="2">'.$row['os_kontaktowa'].'</textarea></td>
				<td id="komorka_edytowanie">'.substr($row['creation_date'], 0, 10).'</td>
				<td id="komorka_edytowanie">'.$row_login['login'].'</td>				
				<td id="komorka_edytowanie"><input type="checkbox" name="zaznaczony_wiersz" value="1" '.$zw.' /></td>				
				<td id="komorka_edytowanie"><input type="checkbox" name="oferta" value="1" '.$of.' /></td>	
				<td id="komorka_edytowanie"><textarea name="rach_1_nr" cols="10" rows="2">'.$row['rach_1_nr'].'</textarea></td>				
				<td id="komorka_edytowanie"><textarea name="rach_1_okres" cols="10" rows="2">'.$row['rach_1_okres'].'</textarea></td>				
				<td id="komorka_edytowanie"><textarea name="rach_1_kwota" cols="10" rows="2">'.$row['rach_1_kwota'].'</textarea></td>				
				<td id="komorka_edytowanie"><textarea name="rach_1_uwagi" cols="10" rows="2">'.$row['rach_1_uwagi'].'</textarea></td>				
				<td id="komorka_edytowanie"><textarea name="korekta_nr" cols="10" rows="2">'.$row['korekta_nr'].'</textarea></td>				
				<td id="komorka_edytowanie"><textarea name="korekta_kwota" cols="10" rows="2">'.$row['korekta_kwota'].'</textarea></td>				
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
			echo '<input type="submit"  value="Kopieren" name="kopiuj" />
			<input type="submit"  value="zurück" name="powrot" />';
		}else{
			echo '<input type="submit"  value="Kopiuj" name="kopiuj" />
			<input type="submit"  value="Powrót" name="powrot" />';
		}
		echo '</form></div>';	