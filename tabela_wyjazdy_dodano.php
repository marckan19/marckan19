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
			
			$query = "
			SELECT ptww.*, ptwbp.*, ptwbp.id as id_powiazania
			FROM panel_tabela_wyjazdy_wpisy  ptww
			JOIN panel_tabela_wyjazdy_budowy_powiazania ptwbp ON ptwbp.wpis_id = ptww.id
			WHERE ptww.id = ".$id." AND ptwbp.is_main = '1';";
		
		$result = mysql_query($query) or die (mysql_error());	
		//$row = mysql_fetch_array($result,MYSQL_ASSOC);
		
		//WYCIAGANIE ID USERA
		$query_login = "SELECT * FROM panel_users WHERE id = ".$creator_id.";";
		$result_login = mysql_query($query_login) or die (mysql_error());
		$row_login = mysql_fetch_array($result_login,MYSQL_ASSOC);	
		
		require_once('menu_wyjazdy.php');
		echo '
			<table>
				';
				if($row_header['header'] == 'n'){
					echo '<tr><td id="naglowek">Mitarbeiter</td>
					<td id="naglowek">Baustelle</td>					
					<td id="naglowek">Auf Stand<br />setzen</td>
					<td id="naglowek">freimelden</td>
					<td id="naglowek">bestellen</td>
					<td id="naglowek">Dienstwagen</td>
					<td id="naglowek">Bauvorhaben<br />bis</td>
					<td id="naglowek">Bauvorhaben<br />zum</td>					
					<td id="naglowek">Bemerkungen</td>
					<!--<td id="naglowek">Tag der<br />Eintragung</td>
					<td id="naglowek">LOGIN</td>-->
					<!--<td id="naglowek">E</td>-->
					<td id="naglowek">U</td>
					</tr>';
				}else{
					echo '<tr><td id="naglowek">PRACOWNIK</td>
					<td id="naglowek">BUDOWA</td>
					
					<td id="naglowek">ZAWIESIĆ</td>
					<td id="naglowek">ZDAĆ</td>
					<td id="naglowek">ZAMÓWIĆ</td>
					<td id="naglowek">SAMOCHOD</td>
					<td id="naglowek">BUDOWA OD</td>
					<td id="naglowek">BUDOWA DO</td>					
					<td id="naglowek">UWAGI</td>
					<!--<td id="naglowek">DATA WPISU</td>
					<td id="naglowek">LOGIN</td>-->
					<!--<td id="naglowek">E</td>-->
					<td id="naglowek">U</td>
					</tr>';
				}				
								
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
				//WYCIAGNIECIE KOLORU BUDOWY
				$query_kolor = "
						SELECT ptwb.*
						FROM panel_tabela_wyjazdy_budowy  ptwb			
						JOIN panel_tabela_wyjazdy_budowy_powiazania ptwbp ON ptwbp.budowa_id = ptwb.id		
						WHERE ptwbp.wpis_id = ".$id." AND ptwbp.is_main = '1'						
						LIMIT 1;";
				$result_kolor = mysql_query($query_kolor) or die (mysql_error());
				$row_kolor = mysql_fetch_array($result_kolor,MYSQL_ASSOC);
				
				$query_pracownicy = "
						SELECT ptwp.*
						FROM panel_tabela_wyjazdy_pracownicy  ptwp			
						JOIN panel_tabela_wyjazdy_pracownicy_powiazania ptwpp ON ptwpp.pracownik_id = ptwp.id		
						WHERE ptwpp.wpis_id = ".$id."
						ORDER BY ptwp.nazwisko, ptwp.imie;";
		
				$result_pracownicy = mysql_query($query_pracownicy) or die (mysql_error());	
				
				$query_budowy = "
						SELECT ptwb.*
						FROM panel_tabela_wyjazdy_budowy  ptwb			
						JOIN panel_tabela_wyjazdy_budowy_powiazania ptwbp ON ptwbp.budowa_id = ptwb.id		
						WHERE ptwbp.wpis_id = ".$id."
						ORDER BY ptwb.budowa;";
		
				$result_budowy = mysql_query($query_budowy) or die (mysql_error());	
				
				$query_samochody = "
						SELECT ptws.*
						FROM panel_tabela_wyjazdy_samochody ptws			
						JOIN panel_tabela_wyjazdy_samochody_powiazania ptwsp ON ptwsp.samochod_id = ptws.id		
						WHERE ptwsp.wpis_id = ".$id."
						ORDER BY ptws.rejestracja;";		
				$result_samochody = mysql_query($query_samochody) or die (mysql_error());
				
				$lp_pracownik = 0;
				$lp_samochod = 0;
				$lp_budowa = 0;
				
				echo '<tr '.kolor_wiersza_wyjazdy($row_kolor['kolor']).'>
					<td>';
					while($row_pracownicy = mysql_fetch_array($result_pracownicy,MYSQL_ASSOC)){
						echo ++$lp_pracownik.'. '.$row_pracownicy['nazwisko'].' '.$row_pracownicy['imie'].'<br />';
					}							
					echo '</td>
					<td>';					
					while($row_budowy = mysql_fetch_array($result_budowy,MYSQL_ASSOC)){
						echo '<div style="background-color:'.$row_budowy['kolor'].';">'.++$lp_budowa.'. '.$row_budowy['budowa'].'</div>';
					}
					echo '</td>
												
					<td>
						<table>
							<tr>
								<td>Pn.</td>
								<td>Wt.</td>
								<td>Śr.</td>
								<td>Cz.</td>
								<td>Pt.</td>
								<td>So.</td>
								<td>Nd.</td>
							</tr>
							<tr>
								<td style="border:1px solid black;">'.nl2br($row['zawiesic_1']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zawiesic_2']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zawiesic_3']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zawiesic_4']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zawiesic_5']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zawiesic_6']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zawiesic_7']).'</td>
							</tr>
						</table>
					</td>					
					<td>
						<table>
							<tr>
								<td>Pn.</td>
								<td>Wt.</td>
								<td>Śr.</td>
								<td>Cz.</td>
								<td>Pt.</td>
								<td>So.</td>
								<td>Nd.</td>
							</tr>
							<tr>
								<td style="border:1px solid black;">'.nl2br($row['zdac_1']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zdac_2']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zdac_3']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zdac_4']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zdac_5']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zdac_6']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zdac_7']).'</td>
							</tr>
						</table>
					</td>						
					<td>
						<table>
							<tr>
								<td>Pn.</td>
								<td>Wt.</td>
								<td>Śr.</td>
								<td>Cz.</td>
								<td>Pt.</td>
								<td>So.</td>
								<td>Nd.</td>
							</tr>
							<tr>
								<td style="border:1px solid black;">'.nl2br($row['zamowic_1']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zamowic_2']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zamowic_3']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zamowic_4']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zamowic_5']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zamowic_6']).'</td>
								<td style="border:1px solid black;">'.nl2br($row['zamowic_7']).'</td>
							</tr>
						</table>
					</td>
					<td>';						
					while($row_samochody = mysql_fetch_array($result_samochody,MYSQL_ASSOC)){
						echo ++$lp_samochod.'. '.$row_samochody['rejestracja'].'<br />';
					}
					echo '</td>						
					<td>'.nl2br($row['budowa_od']).'</td>
					<td>'.nl2br($row['budowa_do']).'</td>		
					<td>'.nl2br($row['uwagi']).'</td>					
					<!--<td>'.substr($row['creation_date'], 0, 10).'</td>
					<td>'.$row_login['login'].'</td>-->
					<!--<td id="komorka_edycja"><a href="tabela_wyjazdy_edytuj.php?id='.$row['id_powiazania'].'"><button>E</button></a></td>					-->
					<td id="komorka_usun"><a href="tabela_wyjazdy_usun.php?id='.$id.'"><button>X</button></a></td>
				</tr>';
			}
				echo '			</table>';
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