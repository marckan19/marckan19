<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	require_once('menu.php');
	
	if(isset($_GET['id_img'])){
		$id_img = $_GET['id_img'];
	}else{
		$id_img = 0;
	}	

	echo '
	<table>
		<tr>';	
			if($row_header['header'] == 'n'){
				echo '<td><h2>Mängel - den Eintrag bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Usterki - edytowanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_usterki.php');
		echo '</tr>
	</table>';
	
	$query_img = "SELECT * FROM panel_tabela_usterki_images WHERE id = ".$id_img." LIMIT 1;";
	$result_img = mysql_query($query_img) or die (mysql_error());
	$row_img = mysql_fetch_array($result_img,MYSQL_ASSOC);
	
	if(isset($_POST['back'])){
		header('Location: tabela_usterki_edytuj.php?id='.$row_img['id_usterki']);
	}elseif(isset($_POST['delete'])){	

		unlink($row_img['path']);
		
		$img = $row_img['id_usterki'];
		
		$query_delete = "DELETE FROM panel_tabela_usterki_images WHERE id = ".$row_img['id'].";";
		mysql_query($query_delete);		

		header('Location: tabela_usterki_edytuj.php?id='.$img);
		
	}else{
		echo '<form action="tabela_usterki_img_del.php?id_img='.$row_img['id'].'" method="post">
			Czy na pewno usunąć zdjęcie?<br /><br />';		
		echo '<input type="submit"  value="Tak" name="delete" />
		      <input type="submit"  value="Nie" name="back" />';
		echo '</form>';	
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');