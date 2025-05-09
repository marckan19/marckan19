<?php
echo '
<form action="tabela_wyjazdy.php" method="post">
	<table>';
		if($row_header['header'] == 'n'){
			echo '<tr id="naglowek_filtrow">
			<td>Mitarbeiter</td>
			<td>Baustelle</td>
			<td>Auf Stand<br />setzen</td>								
			<td>freimelden</td>								
			<td>bestellen</td>		
			<!--<td>Dienstwagen</td>	-->		
			<td>Bauvorhaben<br />bis</td>			
			<td>Bauvorhaben<br />zum</td>
			<td>Bemerkungen</td>								
			<td>Tag der<br />Eintragung</td>			
			<td>LOGIN</td>					
			</tr>';
		}else{
			 echo '<tr id="naglowek_filtrow">
			<td>PRACOWNIK</td>
			<td>BUDOWA</td>
			<td>ZAWIESIĆ</td>								
			<td>ZDAĆ</td>								
			<td>ZAMÓWIĆ</td>		
			<!--<td>SAMOCHÓD</td>	-->		
			<td>BUDOWA OD</td>			
			<td>BUDOWA DO</td>		
			<td>UWAGI</td>								
			<td>DATA <br />WPISU</td>			
			<td>LOGIN</td>					
		</tr>';
		}
		echo '<tr>
			<td><textarea name="filtry_pracownik" cols="13" rows="1"></textarea></td>
			<td><textarea name="filtry_budowa" cols="10" rows="1"></textarea></td>
			<td><textarea name="filtry_zawiesic" cols="8" rows="1"></textarea></td>						
			<td><textarea name="filtry_zdac" cols="8" rows="1"></textarea></td>						
			<td><textarea name="filtry_zamowic" cols="8" rows="1"></textarea></td>			
			<!--<td><textarea name="filtry_samochod" cols="15" rows="1"></textarea></td>	-->					
			<td><textarea name="filtry_budowa_od" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_budowa_do" cols="15" rows="1"></textarea></td>		
			<td><textarea name="filtry_uwagi" cols="9" rows="1"></textarea></td>						
			<td><textarea name="filtry_data_wpisu" cols="8" rows="1"></textarea></td>			
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