<?php
echo '
<form action="tabela_wyjazdy_archiwum.php" method="post">
	<table>';
		if($row_header['header'] == 'n'){
			echo '<tr id="naglowek_filtrow">
				<td>Bauvorhaben</td>
			</tr>';
		}else{
			 echo '<tr id="naglowek_filtrow">			
				<td>BUDOWA</td>
			</tr>';
		}
		echo '<tr>			
			<td><select name="filtry_budowa_id">';
				$query_budowa = "SELECT * FROM panel_tabela_wyjazdy_budowy ORDER BY budowa;";
				$result_budowa = mysql_query($query_budowa);
				echo '<option value="0">Budowa</option>';
				while($row_budowa = mysql_fetch_array($result_budowa,MYSQL_ASSOC)){
					echo '<option value="'.$row_budowa['id'].'">'.$row_budowa['budowa'].'</option>';
				}	
			
			echo '</select></td>
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