<?php
echo '
<form action="tabela_wyjazdy_pracownicy.php" method="post">
	<table>';
		if($row_header['header'] == 'n'){
			echo '<tr id="naglowek_filtrow">
				<td>Nachname</td>
				<td>Vorname</td>
				<td>Funktion</td>			
				<td>Kontakt</td>			
				<td>Tag der<br />Eintragung</td>									
				<td>LOGIN</td>													
			</tr>';
		}else{
			echo '<tr id="naglowek_filtrow">
				<td>NAZWISKO</td>
				<td>IMIĘ</td>
				<td>STANOWISKO</td>			
				<td>KONTAKT</td>			
				<td>DATA<br />WPISU</td>								
				<td>LOGIN</td>													
			</tr>';
		}
		echo '<tr>
			<td><textarea name="filtry_nazwisko" cols="13" rows="1"></textarea></td>
			<td><textarea name="filtry_imie" cols="10" rows="1"></textarea></td>
			<td><textarea name="filtry_stanowisko" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_kontakt" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_data_wpisu" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_login" cols="8" rows="1"></textarea></td>									
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