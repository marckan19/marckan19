<?php
if(isset($_SESSION['user_id'])){
	$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
}elseif(isset($_COOKIE['ciastko_user_id'])){
	$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
}
$result_header = mysql_query($query_header) or die (mysql_error());
$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);

echo '
<form action="tabela_noclegi.php" method="post">
	<table width="100%">
		<tr id="naglowek_filtrow">';
			if($row_header['header'] == 'n'){
				echo '
					<td>Bauvorhaben</td>
					<td>Vermieter</td>	
					<td>Anzhal der Personen</td>
					<td>Mietzeitraum vom</td>
					<td>Mietzeitraum bis zum</td>
					<td>Preis</td>
					<td>Info. zur Zahlung</td>
					<td>Bemerkungen</td>
					<td>Ansprechpartner</td>			
					<td>Datum der Eintragung</td>			
					<td>LOGIN</td>		
				';
			}else{
				echo '
					<td>BUDOWA</td>
					<td>FIRMA</td>	
					<td>L. O. MIE.</td>
					<td>OKRES W. OD</td>
					<td>OKRES W. DO</td>
					<td>CENA</td>
					<td>PŁACONE</td>
					<td>UWAGI</td>
					<td>OSOBA KONTAKTOWA</td>			
					<td>DATA WPISU</td>			
					<td>LOGIN</td>		
				';
			}	
		echo '</tr>
		<tr>
			<td><textarea name="filtry_budowa" cols="9" rows="1"></textarea></td>
			<td><textarea name="filtry_firma" cols="9" rows="1"></textarea></td>						
			<td><textarea name="filtry_ilosc_osob_mieszka" cols="3" rows="1"></textarea></td>
			<td><textarea name="filtry_okres_wynajmu_od" cols="6" rows="1"></textarea></td>
			<td><textarea name="filtry_okres_wynajmu_do" cols="6" rows="1"></textarea></td>
			<td><textarea name="filtry_cena" cols="3" rows="1"></textarea></td>
			<td><textarea name="filtry_placone" cols="5" rows="1"></textarea></td>
			<td><textarea name="filtry_uwagi" cols="10" rows="1"></textarea></td>
			<td><textarea name="filtry_os_kontaktowa" cols="7" rows="1"></textarea></td>			
			<td><textarea name="filtry_data_wpisu" cols="5" rows="1"></textarea></td>			
			<td><textarea name="filtry_login" cols="5" rows="1"></textarea></td>						
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