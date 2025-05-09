<?php
if(isset($_SESSION['user_id'])){
	$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
}elseif(isset($_COOKIE['ciastko_user_id'])){
	$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
}
$result_header = mysql_query($query_header) or die (mysql_error());
$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);

echo '
<form action="tabela_konserwacje.php" method="post">
	<table width="100%">
		<tr id="naglowek_filtrow">';
			if($row_header['header'] == 'n'){
				echo '
					<td>Bauvorhaben</td>
					<td>Vertragspartner</td>								
					<td>Datum</td>
					<td>Bemerkung</td>												
					<td>Vermerk</td>			
					<td>Datum der Eintragung</td>			
					<td>LOGIN</td>		
				';
			}else{
				echo '
					<td>BUDOWA</td>
					<td>KONTRAHENT</td>								
					<td>DATA</td>
					<td>UWAGI</td>												
					<td>INFORMACJA</td>		
					<td>DATA WPISU</td>			
					<td>LOGIN</td>		
				';
			}	
		echo '</tr>
		<tr>
			<td><textarea name="filtry_budowa" cols="30" rows="1"></textarea></td>
			<td><textarea name="filtry_kontrahent" cols="30" rows="1"></textarea></td>						
			<td><textarea name="filtry_data" cols="30" rows="1"></textarea></td>
			<td><textarea name="filtry_uwagi" cols="30" rows="1"></textarea></td>
			<td><textarea name="filtry_informacja" cols="30" rows="1"></textarea></td>					
			<td><textarea name="filtry_data_wpisu" cols="30" rows="1"></textarea></td>			
			<td><textarea name="filtry_login" cols="30" rows="1"></textarea></td>						
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