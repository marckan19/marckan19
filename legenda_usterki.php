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
			<td><a href="tabela_usterki_nowe.php"><div id="zwyzki_legenda_zolty"></div></a></td><td><a href="tabela_usterki_nowe.php">-neu</a></td><td width="20px"></td>
			<td><a href="tabela_usterki_usuniete.php"><div id="zwyzki_legenda_szary"></div></a></td><td><a href="tabela_usterki_usuniete.php">-beseitigt</a></td><td width="20px"></td>
			<td><a href="tabela_usterki_w_trakcie.php"><div id="zwyzki_legenda_zielony"></div></a></td><td><a href="tabela_usterki_w_trakcie.php">-läuft</a></td><td width="20px"></td>
		';
	}else{
		echo '
			<td><a href="tabela_usterki_nowe.php"><div id="zwyzki_legenda_zolty"></div></a></td><td><a href="tabela_usterki_nowe.php">-NOWE</a></td><td width="20px"></td>
			<td><a href="tabela_usterki_usuniete.php"><div id="zwyzki_legenda_szary"></div></a></td><td><a href="tabela_usterki_usuniete.php">-USUNIĘTE</a></td><td width="20px"></td>
			<td><a href="tabela_usterki_w_trakcie.php"><div id="zwyzki_legenda_zielony"></div></a></td><td><a href="tabela_usterki_w_trakcie.php">-W TRAKCIE</a></td><td width="20px"></td>
			';
	}
