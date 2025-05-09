<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "?"));
	}else{
		$filters_path = '';
	}
	
	require_once('menu.php');	
	
	if($row_header['header'] == 'n'){
		echo '<h2>Baustellen - Dienstwagen löschen</h2>';
	}else{
		echo '<h2>Wyjazdy - usuwanie samochodu</h2>';
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
		
		if($row_header['header'] == 'n'){
			echo '<div id="success">Dienstwagen wurde gelöscht</div><br />';
		}else{
			echo '<div id="success">Samochód został usunięty.</div><br />';
		}

	if(isset($_SESSION['user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_SESSION['user_id'].";";
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$query_header = "SELECT * FROM panel_users WHERE id = ".$_COOKIE['ciastko_user_id'].";";
	}
	$result_header = mysql_query($query_header) or die (mysql_error());
	$row_header = mysql_fetch_array($result_header,MYSQL_ASSOC);
		
	if($row_header['header'] == 'n'){
		echo '<br /><a href="tabela_wyjazdy_samochody.php'.$filters_path.'"><button>zurück</button></a>';
	}else{
		echo '<br /><a href="tabela_wyjazdy_samochody.php'.$filters_path.'"><button>Powrót</button></a>';
	}

}else{
	require_once('logout.php');
}

require_once('footer.php');