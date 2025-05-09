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
	
	$query_delete = "DELETE FROM panel_tabela_wyjazdy_pracownicy_powiazania WHERE id = ".$id_pow.";";
	mysql_query($query_delete);
	
	header('Location: tabela_wyjazdy.php');
	
}else{
	require_once('logout.php');
}

require_once('footer.php');