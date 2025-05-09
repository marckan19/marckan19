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
				echo '<td><h2>Bauvorhaben</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>WYKRES</h2></td><td width="20px" />';			
			}
		echo '</tr>
	</table>';

	$date = date('Y-m-d');

	$query = "SELECT * FROM panel_tabela_budowy WHERE realizacja_od >= '".$date."' OR realizacja_do >= '".$date."' ORDER BY realizacja_od;";
	$result = mysql_query($query) or die (mysql_error());
	
	echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	echo '<div>';
	
	$budowy;
	$minRealizacjaOd = $date;
	$maxRealizacjaDo = $date;
	
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
		$budowa['realizacja_od'] = $row['realizacja_od'];
		$budowa['realizacja_do'] = $row['realizacja_do'];
		$budowa['zleceniodawca'] = $row['zleceniodawca'];
		$budowa['kraj'] = $row['kraj'];
		$budowy[] = $budowa;
			
		if ($row['realizacja_od'] < $minRealizacjaOd) {
			$minRealizacjaOd = $row['realizacja_od'];
		}	
		
		if ($row['realizacja_do'] > $maxRealizacjaDo) {
			$maxRealizacjaDo = $row['realizacja_do'];
		}	
	}
	echo '</div>';

	$liczbaDni = round((strtotime($maxRealizacjaDo)-strtotime($minRealizacjaOd))/86400); 
	$dataZaDwaLata = date('Y-m-d', strtotime($minRealizacjaOd . ' + 2 year'));
	$liczbaDniDwaLata = round((strtotime($dataZaDwaLata)-strtotime($minRealizacjaOd))/86400); 

	/*echo 'test: ' . md5('test') . '<br />';
	echo 'admin1: ' . md5('admin1') . '<br />';
	echo 'user1: ' . md5('user1') . '<br />';
	echo 'maciek: ' . md5('maciek') . '<br />';
	echo 'michael: ' . md5('michael') . '<br />';
	echo 'bartek: ' . md5('bartek') . '<br />';
	echo 'marta: ' . md5('marta') . '<br />';
	echo 'martyna: ' . md5('martyna') . '<br />';
	echo 'adrian: ' . md5('adrian') . '<br />';
	echo 'sebastian: ' . md5('sebastian') . '<br />';
	echo 'maciek1: ' . md5('maciek1') . '<br />';
	echo 'bode: ' . md5('bode') . '<br />';
	echo 'mora: ' . md5('mora') . '<br />';
	echo 'schnetter: ' . md5('schnetter') . '<br />';
	echo 'norbert: ' . md5('norbert') . '<br />';
	echo 'pdg: ' . md5('pdg') . '<br />';	
	echo 'mike: ' . md5('mike') . '<br />';	
	echo 'mateusz: ' . md5('mateusz') . '<br />';	
	echo 'pascal: ' . md5('pascal') . '<br />';	
	echo 'danny: ' . md5('danny') . '<br />';	
	echo 'henia: ' . md5('henia') . '<br />';	
	echo 'ania: ' . md5('ania') . '<br />';	*/
	
	echo '<br />';
	if ($liczbaDni > 0) {
		//Ogolne ramy tabeli
		echo '<div>';
		echo '<table style="border-spacing: 0px;">';
		echo '<tr>';
		echo '<td style="border-right:0.5px solid black;">Budowa</td>';
		echo '<td style="border-right:0.5px solid black;">Początek</td>';
		echo '<td style="border-right:0.5px solid black;">Koniec</td>';
		
		//Wygenerowanie kolumn
		//for ($i=1; $i<=$liczbaDni + 1; $i++) {
		for ($i=1; $i<=$liczbaDniDwaLata + 1; $i++) {
			$j = $i - 1;
			$data = date('Y-m-d', strtotime($minRealizacjaOd . ' + '. $j .' day'));
			
				if (date('d',strtotime($data)) == 1) {
					echo '<td style="border-left: 0.5px solid black; position: fixed; top: 140px;">';
					echo date('m',strtotime($data));
					echo '</td>';
				} elseif (date('Y-m-d', strtotime($data)) == $minRealizacjaOd) {
					echo '<td style=" position: fixed; top: 140px;">';
					echo date('m',strtotime($data));
					echo '</td>';
				} else {
				
					echo '<td style=""></td>';
				}
			
		}
		echo '</tr>';
		
		//Gerenowanie wierszy. 1 wiersz = 1 budowa
		foreach ($budowy as $wiersz) {
			$dzienRozpoczecia = round((strtotime($wiersz['realizacja_od'])-strtotime($minRealizacjaOd))/86400) + 1; 
			$dzienZakonczenia = round((strtotime($wiersz['realizacja_do'])-strtotime($minRealizacjaOd))/86400) + 1; 

			echo '<tr>';
			echo '<td style="border-top:0.5px solid black; border-right:0.5px solid black;">' . substr($wiersz['zleceniodawca'], 0, 100) . '</td>';
			echo '<td style="border-top:0.5px solid black; border-right:0.5px solid black;">' . $wiersz['realizacja_od'] . '</td>';
			echo '<td style="border-top:0.5px solid black; border-right:0.5px solid black;">' . $wiersz['realizacja_do'] . '</td>';
			//for ($i=1; $i<=$liczbaDni + 1; $i++) {
			
			if ($wiersz['kraj'] == 'h') {
				$kolor = 'green';
			} elseif ($wiersz['kraj'] == 'n') {
				$kolor = 'red';
			} else {
				$kolor = 'lightblue';
			}
			
			for ($i=1; $i<=$liczbaDniDwaLata + 1; $i++) {
				
				if ($i > $dzienRozpoczecia && $i < $dzienZakonczenia) {
					echo '<td style="border-top:0.5px solid black; background-color: ' . $kolor . ';">';
				} elseif ($i == $dzienRozpoczecia || $i == $dzienZakonczenia) {
					echo '<td style="border-top:0.5px solid black; background-color: ' . $kolor . ';">';
				} else {
					echo '<td style="border-top:0.5px solid black;">';
				}
				
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}
	
	echo '<div>';
}else{
	require_once('logout.php');
}

require_once('footer.php');