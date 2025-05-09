<?php
ob_start();
session_start();
require_once('header.php');

if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){
	require_once('menu.php');
			
	echo '
	<table>
		<tr>';	
			if($row_header['header'] == 'n'){
				echo '<td><h2>Mängel</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Usterki</h2></td><td width="20px" />';			
			}
			require_once('legenda_usterki.php');
		echo '</tr>
	</table>';
	
	require 'tabela_usterki_filtry.php';
	if (isset($_POST['filtry_szukaj'])){		
		$query = "SELECT u.login AS login, ptn.* FROM panel_tabela_usterki ptn
					JOIN panel_users u ON u.id = ptn.creator_id
						
			WHERE 
			ptn.budowa like '%".$_POST['filtry_budowa']."%' &&			
			ptn.usterka like '%".$_POST['filtry_usterka']."%' &&						
			ptn.gdzie like '%".$_POST['filtry_gdzie']."%' &&						
			ptn.os like '%".$_POST['filtry_os']."%' &&						
			ptn.kontakt like '%".$_POST['filtry_kontakt']."%' &&						
			ptn.data_zgloszenia like '%".$_POST['filtry_data_zgloszenia']."%' &&
			ptn.termin_usuniecia like '%".$_POST['filtry_termin_usuniecia']."%' &&			
			ptn.data_usuniecia like '%".$_POST['filtry_data_usuniecia']."%' &&			
			
			ptn.usunal like '%".$_POST['filtry_usunal']."%' &&
			ptn.uwagi like '%".$_POST['filtry_uwagi']."%' &&
			ptn.sprzet like '%".$_POST['filtry_sprzet']."%' &&
			ptn.creation_date like '%".$_POST['filtry_data_wpisu']."%' &&
			u.login like '%".$_POST['filtry_login']."%'
			ORDER BY ptn.data_zgloszenia
		;";
		
				
		$filters_path = '&budowa='.$_POST['filtry_budowa'].'
						&usterka='.$_POST['filtry_usterka'].'						
						&gdzie='.$_POST['filtry_gdzie'].'						
						&os='.$_POST['filtry_os'].'						
						&kontakt='.$_POST['filtry_kontakt'].'						
						&data_zgloszenia='.$_POST['filtry_data_zgloszenia'].'
						&termin_usuniecia='.$_POST['filtry_termin_usuniecia'].'					
						&data_usuniecia='.$_POST['filtry_data_usuniecia'].'					
						
						&usunal='.$_POST['filtry_usunal'].'
						&uwagi='.$_POST['filtry_uwagi'].'
						&sprzet='.$_POST['filtry_sprzet'].'
						&creation_date='.$_POST['filtry_data_wpisu'].'						
						&login='.$_POST['filtry_login'];
	}elseif(strpos($_SERVER['REQUEST_URI'], "&") > 0){
				
		$query = "SELECT u.login AS login, ptz.* FROM panel_tabela_usterki ptz
					JOIN panel_users u ON u.id = ptz.creator_id
					
			WHERE 
			ptz.budowa like '%".$_GET['budowa']."%' &&
			ptz.usterka like '%".$_GET['usterka']."%' &&						
			ptz.gdzie like '%".$_GET['gdzie']."%' &&						
			ptz.os like '%".$_GET['os']."%' &&						
			ptz.kontakt like '%".$_GET['kontakt']."%' &&						
			ptz.data_zgloszenia like '%".$_GET['data_zgloszenia']."%' &&
			ptz.termin_usuniecia like '%".$_GET['termin_usuniecia']."%' &&		
			ptz.data_usuniecia like '%".$_GET['data_usuniecia']."%' &&		
			
			ptz.usunal like '%".$_GET['usunal']."%' &&
			ptz.uwagi like '%".$_GET['uwagi']."%' &&
			ptz.sprzet like '%".$_GET['sprzet']."%' &&
			ptz.creation_date like '%".$_GET['data_wpisu']."%' &&
			u.login like '%".$_GET['login']."%'
			ORDER BY ptz.data_zgloszenia
		;";
		
		$filters_path = '&budowa='.$_GET['budowa'].'
						&usterka='.$_GET['usterka'].'						
						&gdzie='.$_GET['gdzie'].'						
						&os='.$_GET['os'].'						
						&kontakt='.$_GET['kontakt'].'						
						&data_zgloszenia='.$_GET['data_zgloszenia'].'
						&termin_usuniecia='.$_GET['termin_usuniecia'].'
						&data_usuniecia='.$_GET['data_usuniecia'].'
						
						&usunal='.$_GET['usunal'].'
						&uwagi='.$_GET['uwagi'].'
						&sprzet='.$_GET['sprzet'].'
						&creation_date='.$_GET['data_wpisu'].'						
						&login='.$_GET['login'];
	}else{
		$query = "
			SELECT u.login AS login, ptn.* FROM panel_tabela_usterki ptn
			JOIN panel_users u ON u.id = ptn.creator_id
			
			ORDER BY ptn.data_zgloszenia;";
	}
		
	$result = mysql_query($query) or die (mysql_error());
	
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<a href="tabela_usterki_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a><br /><br />';
		}else{
			echo '<a href="tabela_usterki_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a><br /><br />';
		}
	}
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	echo '<div>
	<table width="100%">
		<tr>';	
		
	if($row_header['header'] == 'n'){
		echo '
			<td id="naglowek" width="35px">Lfd.Nr.</td>
			<td id="naglowek">Bauvorhaben</td>
			<td id="naglowek">Mängel</td>			
			<td id="naglowek">Wo</td>			
			<td id="naglowek">Achse</td>			
			<td id="naglowek">Ansprechpartner /Telefon</td>			
			<td id="naglowek" width="150px">gemeldet am</td>			
			<td id="naglowek" width="150px">Beseitigungsfirst</td>			
			<td id="naglowek" width="150px">beseitigt am</td>
			<td id="naglowek">beseitigt von</td>
			<td id="naglowek">Anmerkungen</td>
			<td id="naglowek">benötigte Gerätschaften: Arbeitsbühne, Regal, Leiter</td>
			<td id="naglowek">FOTO</td>
			<td id="naglowek" width="150px">eingetragen am</td>
			<td id="naglowek" width="150px">LOGIN</td>					
		';
	}else{
		echo '
			<td id="naglowek" width="25px">LP</td>
			<td id="naglowek">BUDOWA</td>
			<td id="naglowek">USTERKA</td>			
			<td id="naglowek">GDZIE</td>			
			<td id="naglowek">OŚ</td>			
			<td id="naglowek">KONTAKT</td>			
			<td id="naglowek" width="150px">DATA ZGŁOSZENIA</td>			
			<td id="naglowek" width="150px">TERMIN USUNIĘCIA</td>			
			<td id="naglowek" width="150px">DATA USUNIĘCIA</td>
			<td id="naglowek">USUNIĘTE PRZEZ</td>
			<td id="naglowek">UWAGI</td>
			<td id="naglowek">POTRZEBNY SPRZĘT</td>
			<td id="naglowek">FOTO</td>
			<td id="naglowek" width="150px">DATA WPISU</td>
			<td id="naglowek" width="150px">LOGIN</td>			
		';
	}
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		echo'
			<td id="naglowek" width="25px">E</td>
			<td id="naglowek" width="25px">K</td>
			<td id="naglowek" width="25px">U</td>';
	} 
	echo '</tr>';
			
	$lp = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){	
		
		$query_img = "SELECT * FROM panel_tabela_usterki_images WHERE id_usterki = ".$row['id']." ORDER BY id;";
		$result_img = mysql_query($query_img) or die (mysql_error());		
		
		
		echo '<tr '.kolor_wiersza_usterki($row['data_usuniecia'], $row['termin_usuniecia']).'>';
		echo '			
				<td>'.$lp.'</td>
				<td>'.nl2br($row['budowa']).'</td>
				<td>'.nl2br($row['usterka']).'</td>						
				<td>'.nl2br($row['gdzie']).'</td>						
				<td>'.nl2br($row['os']).'</td>						
				<td>'.nl2br($row['kontakt']).'</td>						
				<td>'.zerowaDataNaPusta($row['data_zgloszenia']).'</td>				
				<td>'.zerowaDataNaPusta($row['termin_usuniecia']).'</td>									
				<td>'.zerowaDataNaPusta($row['data_usuniecia']).'</td>									
				<td>'.nl2br($row['usunal']).'</td>
				<td>'.nl2br($row['uwagi']).'</td>
				<td>'.nl2br($row['sprzet']).'</td>
				<td>';
				$l = 0;
				while($row_img = mysql_fetch_array($result_img,MYSQL_ASSOC)){
					$l++;
					echo '<a href="'.$row_img['path'].'" target="_blank">FOTO '.$l.'</a><br />';		
				}
				echo '</td>
				<td>'.substr($row['creation_date'], 0, 10).'</td>
				<td>'.$row['login'].'</td>
				';
				if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
					echo '<td id="komorka_edycja"><a href="tabela_usterki_edytuj.php?id='.$row['id'].$filters_path.'"><button>E</button></a></td>
						<td id="komorka_kopiuj"><a href="tabela_usterki_kopiuj.php?id='.$row['id'].$filters_path.'"><button>K</button></a></td>
						<td id="komorka_usun"><a href="tabela_usterki_usun.php?id='.$row['id'].$filters_path.'"><button>X</button></a></td>';
				}
			echo '</tr>
		';
		$lp++;
	}
	echo '</table>';
	if($_SESSION['user_type'] == 'a' || $_COOKIE['ciastko_zalogowany'] == 'a'){
		$filters_path2 = '?'.substr($filters_path,1);
		
		if($row_header['header'] == 'n'){
			echo '<br /><a href="tabela_usterki_dodaj.php'.$filters_path2.'"><button>neuer Eintrag</button></a>';
		}else{
			echo '<br /><a href="tabela_usterki_dodaj.php'.$filters_path2.'"><button>Dodaj wpis</button></a>';
		}
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');