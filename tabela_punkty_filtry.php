<?php
echo '
<form action="tabela_punkty.php" method="post">
	<table width="100%">
		<tr id="naglowek_filtrow">
			<td width="130px">BAUVORHABEN + ADRESSE /<br />BUDOWA + ADRES (MICHAEL)</td>
			<td width="130px">AUSFÜHRUNGSTERMIN VOM/<br />TERMIN REALIZACJI OD (MACIEK B.)</td>
			<td width="130px">AUSFÜHRUNGSTERMIN DO/<br />TERMIN REALIZACJI DO (MACIEK B.)</td>
			<td width="130px">PERSONAL /<br />PERSONEL (MACIEK B.)</td>
			<td width="130px">ERSTELLUNGSDATUM DER ABNAHMEDOKUMENTATION SEITENS BRASSCO /<br />DATA SPORZĄDZENIA DOKUMENTACJI PRZEZ BRASSCO (MICHAEL)</td>
			<td width="130px">DATUM DER RÜCKSENDUNG DER ABNAHMEDOKUMENTATION UNTERSCHRIEBEN DURCH AUFTRAGGEBER /<br />DATA ODESŁANEJ DOKUMENTACJI, PODPISANEJ PRZEZ ZLECENIODAWCĘ (MICHAEL)</td>
			<td width="130px">AUFTRAGSWERT /<br />KWOTA KONTRAKTU (MICHAEL)</td>
			<td width="130px">DATUM DES EINGANGS DES AVIS /<br />DATA PRZESŁANIA AWIZA PŁATNOŚCI</td>
			<td width="130px">DATUM DES ZAHLUNGSEINGANGS /<br />DATA ZAPŁATY</td>
			<td width="130px">VERMERKE/<br />UWAGI</td>
			<td width="130px">DATUM DER EINTRAGUNG/<br />DATA WPISU</td>
			<td width="130px">LOGIN</td>		
		</tr>	
		<tr>
			<td><textarea name="filtry_budowa_adres" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_realizacja_od" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_realizacja_do" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_personel" cols="15" rows="1"></textarea></td>			
			<td><textarea name="filtry_data_sporzadzenia_dokumentacji" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_data_odeslanej_dokumentacji" cols="15" rows="1"></textarea></td>			
			<td><textarea name="filtry_kwota_kontraktu" cols="15" rows="1"></textarea></td>			
			<td><textarea name="filtry_data_przeslania_awiza" cols="15" rows="1"></textarea></td>			
			<td><textarea name="filtry_data_zaplaty" cols="15" rows="1"></textarea></td>			
			<td><textarea name="filtry_uwagi" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_data_wpisu" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_login" cols="15" rows="1"></textarea></td>						
		</tr>
	</table>';
	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
	
	if($row_header['header'] == 'n'){
		echo '<input type="submit"  value="Suchen" name="filtry_szukaj" />';
	}else{
		echo '<input type="submit"  value="Szukaj" name="filtry_szukaj" />';
	}
echo '</form><br />';