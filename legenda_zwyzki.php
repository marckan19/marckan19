<?php

	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);

	if($row_header['header'] == 'n'){
		echo '
			<td><a href="tabela_zwyzki_zawieszone.php"><div id="zwyzki_legenda_zolty"></div></a></td><td><a href="tabela_zwyzki_zawieszone.php">-auf Stand gesetzt</a></td><td width="20px"></td>
			<td><a href="tabela_zwyzki_zdane.php"><div id="zwyzki_legenda_szary"></div></a></td><td><a href="tabela_zwyzki_zdane.php" >-freigemeldet</a></td><td width="20px"></td>
			<td><a href="tabela_zwyzki_zaznaczone.php"><div id="zwyzki_legenda_pomaranczowy"></div></a></td><td><a href="tabela_zwyzki_zaznaczone.php">-geplant</a></td><td width="20px"></td>
			<td><a href="tabela_zwyzki_nowe.php"><div id="zwyzki_legenda_zielony"></div></a></td><td><a href="tabela_zwyzki_nowe.php">- im Einsatz</a></td><td width="20px"></td>
			<td><div id="zwyzki_legenda_czerwony"></div></td><td>-Alarm</td><td width="20px"></td>
			<td><a href="tabela_zwyzki_oferty.php"><div id="zwyzki_legenda_niebieski"></div></a></td><td><a href="tabela_zwyzki_oferty.php">-Angebot</a></td><td width="20px"></td>
		';
	}else{
		echo '
			<td><a href="tabela_zwyzki_zawieszone.php"><div id="zwyzki_legenda_zolty"></div></a></td><td><a href="tabela_zwyzki_zawieszone.php">-ZAWIESZONY</a></td><td width="20px"></td>
			<td><a href="tabela_zwyzki_zdane.php"><div id="zwyzki_legenda_szary"></div></a></td><td><a href="tabela_zwyzki_zdane.php" >-ZDANY</a></td><td width="20px"></td>
			<td><a href="tabela_zwyzki_zaznaczone.php"><div id="zwyzki_legenda_pomaranczowy"></div></a></td><td><a href="tabela_zwyzki_zaznaczone.php">-PLANOWANY</a></td><td width="20px"></td>
			<td><a href="tabela_zwyzki_nowe.php"><div id="zwyzki_legenda_zielony"></div></a></td><td><a href="tabela_zwyzki_nowe.php">-NOWY</a></td><td width="20px"></td>
			<td><div id="zwyzki_legenda_czerwony"></div></td><td>-ALARM</td><td width="20px"></td>
			<td><a href="tabela_zwyzki_oferty.php"><div id="zwyzki_legenda_niebieski"></div></a></td><td><a href="tabela_zwyzki_oferty.php">-OFERTA</a></td><td width="20px"></td>
		';
	}
	
	$queryResultsLimit = "SELECT zwyzki_limit FROM panel_users WHERE id = ".$_SESSION['user_id']." LIMIT 1;";
	$resultResultsLimit = mysql_query($queryResultsLimit) or die (mysql_error());
	$rowResultsLimit = mysql_fetch_array($resultResultsLimit,MYSQL_ASSOC); 
	if ($rowResultsLimit['zwyzki_limit'] == 1) {
		$limit = ' LIMIT 50 ';
		echo '
			<td><form action="tabela_zwyzki.php" method="post">
				<input type="submit" value="Pokaż wszystkie" name="zwyzki_zmien_limit" />
				<input type="hidden" name="limit" value="0">
			</form></td>';
	} else {
		$limit = '';
		echo '
			<td><form action="tabela_zwyzki.php" method="post">
				<input type="submit" value="Pokaż 50" name="zwyzki_zmien_limit" />
				<input type="hidden" name="limit" value="1">
			</form></td>';
	}
	
	//echo $rowResultsLimit;
	//echo '<td>'.var_dump($rowResultsLimit).'</td>';
	//echo '<td><button></button></td>';
