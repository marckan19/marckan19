<?php
echo '
<form action="tabela_usterki.php" method="post">
	<table>
		<tr id="naglowek_filtrow">';
			if($row_header['header'] == 'n'){
				echo '
					<td>Bauvorhaben</td>
					<td>Mängel</td>	
					<td>Wo</td>			
					<td>Achse</td>			
					<td>Ansprechpartner /Telefon</td>		
					<td>gemeldet am</td>
					<td>Beseitigungsfrist</td>	
					<td>beseitigt am</td>
					<td>beseitigt von</td>			
					<td>Anmerkungen</td>			
					<td>benötigte Gerätschaften</td>			
					<td>eingetragen am</td>			
					<td>LOGIN</td>	
				';
			}else{
				echo '
					<td>BUDOWA</td>
					<td>USTERKA</td>
					<td>GDZIE</td>
					<td>OŚ</td>
					<td>KONTAKT</td>
					<td>DATA ZGŁOSZENIA</td>
					<td>TERMIN USUNIĘCIA</td>					
					<td>DATA USUNIĘCIA</td>					
					<td>USUNIĘTE PRZEZ</td>
					<td>UWAGI</td>
					<td>SPRZĘT</td>
					<td>DATA WPISU</td>			
					<td>LOGIN</td>		
				';
			}	
		echo '</tr>
		<tr>
			<td><textarea name="filtry_budowa" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_usterka" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_gdzie" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_os" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_kontakt" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_data_zgloszenia" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_termin_usuniecia" cols="15" rows="1"></textarea></td>		
			<td><textarea name="filtry_data_usuniecia" cols="15" rows="1"></textarea></td>		
			<td><textarea name="filtry_usunal" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_uwagi" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_sprzet" cols="15" rows="1"></textarea></td>
			<td><textarea name="filtry_data_wpisu" cols="15" rows="1"></textarea></td>			
			<td><textarea name="filtry_login" cols="15" rows="1"></textarea></td>						
		</tr>
	</table>';	
	
	if($row_header['header'] == 'n'){
		echo '<input type="submit"  value="Suchen" name="filtry_szukaj" />';
	}else{
		echo '<input type="submit"  value="Szukaj" name="filtry_szukaj" />';
	}
echo '</form><br />';