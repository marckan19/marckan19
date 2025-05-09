<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	if(isset($_SESSION['user_id'])){
		$creator_id = $_SESSION['user_id'];
	}elseif(isset($_COOKIE['ciastko_user_id'])){
		$creator_id = $_COOKIE['ciastko_user_id'];
	}else{
		$creator_id = 0;
	}
	
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		$id = 0;
	}
	
	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
		$filters_path = '?'.substr($filters_path,1);
	}else{
		$filters_path = '';
	}
	
	require_once('menu.php');
		
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Baustellen – neuen Eintrag erstellen</h2></td><td width="20px" />';		
			}else{
				echo '<td><h2>Wyjazdy – dodawanie wpisu</h2></td><td width="20px" />';		
			}
		echo '</tr>
	</table>';
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';		
						
			if($row_header['header'] == 'n'){
				echo '<div id="success">Der Eintrag wurde gespeichert.</div><br />';
			}else{
				echo '<div id="success">Wpis został dodany.</div><br />';
			}	
		
	if($row_header['header'] == 'n'){
		echo '<br /><a href="tabela_wyjazdy.php'.$filters_path.'"><button>zurück</button></a>';
	}else{
		echo '<br /><a href="tabela_wyjazdy.php'.$filters_path.'"><button>Powrót</button></a>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');