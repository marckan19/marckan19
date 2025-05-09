<?php
echo '
<form action="tabela_budowy.php" method="post">
	<table>
		<tr id="naglowek_filtrow">
			<td rowspan="2">BAUVORHABEN-AUFTRAGGEBER/<br /> BUDOWA - ZLECENIODAWCA</td>
			<td rowspan="2">Höhe der Halle/<br /> WYS. HALI</td>
			<td rowspan="2">AUSFÜHRUNGSZEIT VOM/<br /> TERMIN REALIZACJI OD</td>			
			<td rowspan="2">AUSFÜHRUNGSZEIT BISZUM/<br /> TERMIN REALIZACJI DO</td>			
			<td rowspan="2">BAULEITER/<br /> KIEROWNIK</td>		
			<td rowspan="2">UNTERLAGEN/<br /> DOKUMENTY</td>
			<td rowspan="2">BAU-TAGESBERICHTE<br /> KW-</td>
			
			<td rowspan="2">REVI /<br /> REWIZJA</td>
			<td rowspan="2">Aktueller<br />Stand</td>
			<td rowspan="2">UWAGI</td>			
			<td rowspan="2">Nachträge</td>			
			<td rowspan="2">VERMERK</td>			
			<!--<td>DATA <br />WPISU</td>-->		
			<td colspan="2">Użyj daty <input type="checkbox" name="czy_data_wpisu" value="1" /></td>			
			<td rowspan="2">LOGIN</td>					
		</tr>		
		<tr id="naglowek_filtrow">
			<td>DATA <br />WPISU OD</td>
			<td>DATA <br />WPISU DO</td>
		</tr>
		<tr>
			<td><textarea name="filtry_zleceniodawca" cols="13" rows="1"></textarea></td>
			<td><textarea name="filtry_wys_hali" cols="10" rows="1"></textarea></td>
			<td><textarea name="filtry_realizacja_od" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_realizacja_do" cols="15" rows="1"></textarea></td>						
			<td><textarea name="filtry_kierownik" cols="8" rows="1"></textarea></td>			
			<td><textarea name="filtry_dokumenty" cols="9" rows="1"></textarea></td>
			<td><textarea name="filtry_tydzien" cols="13" rows="1"></textarea></td>
			
			<td><textarea name="filtry_rewizja" cols="7" rows="1"></textarea></td>			
			<td><textarea name="filtry_stan" cols="7" rows="1"></textarea></td>			
			<td><textarea name="filtry_uwagi" cols="5" rows="1"></textarea></td>			
			<td><textarea name="filtry_suplementy" cols="5" rows="1"></textarea></td>			
			<td><textarea name="filtry_adnotacje" cols="5" rows="1"></textarea></td>			
			<!--<td><textarea name="filtry_data_wpisu" cols="5" rows="1"></textarea></td>		-->
			
			<td><script>DateInput(\'filtry_data_wpisu_od\', true, \'YYYY-MM-DD\')</script></td>		
			<td><script>DateInput(\'filtry_data_wpisu_do\', true, \'YYYY-MM-DD\')</script></td>		
				
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