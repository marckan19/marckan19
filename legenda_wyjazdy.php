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
			<td><a href="tabela_wyjazdy.php">AKTUALNE</a></td><td width="20px"></td>
			<td><a href="tabela_wyjazdy_historia.php">HISTORIA</a></td><td width="20px"></td>			
			<td><a href="tabela_wyjazdy_archiwum.php">ARCHIWUM</a></td><td width="20px"></td>			
		';
	}else{
		echo '
			<td><a href="tabela_wyjazdy.php">AKTUALNE</a></td><td width="20px"></td>
			<td><a href="tabela_wyjazdy_historia.php">HISTORIA</a></td><td width="20px"></td>
			<td><a href="tabela_wyjazdy_archiwum.php">ARCHIWUM</a></td><td width="20px"></td>			
		';
	}
