<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	require_once('menu.php');
	
	if(isset($_GET['id_pow'])){
		$id_pow = $_GET['id_pow'];
	}else{
		$id_pow = 0;
	}
	
	$query_id_wpisu= "SELECT * FROM panel_tabela_wyjazdy_budowy_powiazania WHERE id = ".$id_pow." LIMIT 1;";
		$result_id_wpisu = mysql_query($query_id_wpisu) or die (mysql_error());
		$row_id_wpisu = mysql_fetch_array($result_id_wpisu,MYSQL_ASSOC);
		echo $id_wpisu = $row_id_wpisu['wpis_id'];
		
		$creation_date = date('Y-m-d G:i:s');		
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}		
		$query_update = "UPDATE panel_tabela_wyjazdy_wpisy SET modificator_id = ".$creator_id.", modification_date = '".$creation_date."' WHERE id = ".$id_wpisu.";";
		mysql_query($query_update);	
	
	$query_delete = "DELETE FROM panel_tabela_wyjazdy_budowy_powiazania WHERE id = ".$id_pow.";";
	mysql_query($query_delete);
	
	
	
	header('Location: tabela_wyjazdy.php');
	
}else{
	require_once('logout.php');
}

require_once('footer.php');