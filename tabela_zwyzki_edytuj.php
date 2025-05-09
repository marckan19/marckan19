<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	require_once('menu.php');
	
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		$id = 0;
	}			
	
	//ZAPAMIETANA SCIEZKA Z FILTRAMI
	if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
		$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
	}else{
		$filters_path = '';
	}
		
	echo '
	<table>
		<tr>';
			if($row_header['header'] == 'n'){
				echo '<td><h2>Arbeitsbühnen - den Eintrag bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Zwyżki - edytowanie wpisu</h2></td><td width="20px" />';			
			}
		
			require_once('legenda_zwyzki.php');
		echo '</tr>
	</table>
	</div>
	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
	
	if(isset($_POST['powrot'])){
		//ZAPAMIETANA SCIEZKA Z FILTRAMI
		if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
			$filters_path = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'], "&"));
			$filters_path = '?'.substr($filters_path,1);
		}else{
			$filters_path = '';
		}
		
		header('Location: tabela_zwyzki.php'.$filters_path);
	}elseif(isset($_POST['edytuj'])){		
		$budowa = $_POST['budowa'];
		$firma_wynajmujaca = $_POST['firma_wynajmujaca'];		
		$rodzaj_zwyzki = $_POST['rodzaj_zwyzki'];		
		$wysokosc = $_POST['wysokosc'];
		$cena = $_POST['cena'];
		$nr_maszyny = $_POST['nr_maszyny'];
		if($_POST['czy_data_wynajmu_od'] ==1){
			$data_wynajmu_od= $_POST['data_wynajmu_od'];
		}else{
			$data_wynajmu_od= $_POST['data_wynajmu_od_stara'];
		}
		if($_POST['czy_data_wynajmu_do'] ==1){
			$data_wynajmu_do= $_POST['data_wynajmu_do'];
		}else{
			$data_wynajmu_do= $_POST['data_wynajmu_do_stara'];
		}
		if($_POST['czy_zawieszone_do'] == 1){
			$zawieszone_do = $_POST['zawieszone_do'];
		}else{
			$zawieszone_do = $_POST['zawieszone_do_stara'];
		}
		
		$data_zawiesz = $_POST['data_zawiesz'];
		
		if($_POST['czy_data_zdania'] == 1){
			$data_zdania = $_POST['data_zdania'];
		}else{
			$data_zdania = $_POST['data_zdania_stara'];
		}
		$uwagi = $_POST['uwagi'];
		$os_kontaktowa = $_POST['os_kontaktowa'];
		$kredyt = $_POST['kredyt'];
		$modification_date = date('Y-m-d G:i:s');
		if(isset($_SESSION['user_id'])){
			$modificator_id = $_SESSION['user_id'];
		}else{
			$modificator_id = $_COOKIE['ciastko_user_id'];
		}
		if($_POST['zaznaczony_wiersz'] == 1){
			$zaznaczony_wiersz = 1;
		}else{
			$zaznaczony_wiersz = 0;
		}
		
		if($_POST['oferta'] == 1){
			$oferta = 1;
		}else{
			$oferta = 0;
		}
		
		$rach_1_nr = $_POST['rach_1_nr'];
		$rach_1_okres = $_POST['rach_1_okres'];
		$rach_1_kwota = $_POST['rach_1_kwota'];
		$rach_1_uwagi = $_POST['rach_1_uwagi'];
		$korekta_nr = $_POST['korekta_nr'];
		$korekta_kwota = $_POST['korekta_kwota'];
		
		$query_update = "UPDATE panel_tabela_zwyzki 
						SET
							budowa = '".$budowa."', 
							firma_wynajmujaca = '".$firma_wynajmujaca."', 
							rodzaj_zwyzki = '".$rodzaj_zwyzki."', 
							wysokosc = '".$wysokosc."', 
							cena = '".$cena."', 
							nr_maszyny = '".$nr_maszyny."', 
							data_wynajmu_od = '".$data_wynajmu_od."', 
							data_wynajmu_do = '".$data_wynajmu_do."', 
							zawieszone_do = '".$zawieszone_do."', 
							data_zawiesz = '".$data_zawiesz."', 
							data_zdania = '".$data_zdania."', 
							uwagi = '".$uwagi."', 
							os_kontaktowa = '".$os_kontaktowa."',
							smieci = '".$smieci."',
							modificator_id = ".$modificator_id.",
							modification_date = '".$modification_date."',
							zaznaczony_wiersz = '".$zaznaczony_wiersz."',
							oferta = '".$oferta."',
							rach_1_nr = '".$rach_1_nr."',
							rach_1_okres = '".$rach_1_okres."',
							rach_1_kwota = '".$rach_1_kwota."',
							rach_1_uwagi = '".$rach_1_uwagi."',
							korekta_nr = '".$korekta_nr."',
							korekta_kwota = '".$korekta_kwota."'
						WHERE id = ".$id."						
						;";
						
			mysql_query($query_update);
			header('Location: tabela_zwyzki_edytowano.php?id='.$id.$filters_path);
	
	}else{
		require_once('tabela_zwyzki_edytuj_form.php');
	}
}else{
	require_once('logout.php');
}


require_once('footer.php');