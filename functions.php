<?php

function kolor_wiersza_zwyzki($data_zdania, $data_zawiesz, $zaznaczony, $oferta){
	if($oferta == 1){
		$kolor = 'style="background-color: blue;"'; //Niebieski
	}elseif($zaznaczony == 1){
		$kolor = 'style="background-color: orange;"'; //POMARAŃCZOWY
	}elseif($data_zdania != '' && $data_zdania != ' '){
		$kolor = 'style="background-color: #CCCCCC;"'; //SZARY
	}elseif($data_zawiesz != '' && $data_zawiesz != ' ' && date('Y-m-d') < substr($data_zawiesz, 0, 10)){
		$kolor = 'style="background-color: yellow;"'; //ZOLTY	
	}else{
		$kolor = 'style="background-color:#00FF00;"'; //ZIELONY
	}
	
	return $kolor;
}

function kolor_wiersza_noclegi($wynajem_do, $czerwony){
	if($czerwony == 1){
		$kolor = 'style="background-color: yellow;"'; //ZOLTY	
	}elseif($wynajem_do != '' && $wynajem_do != ' ' && date('Y-m-d') > substr($wynajem_do, 0, 10)){
		$kolor = 'style="background-color: #CCCCCC;"'; //SZARY
	}else{
		$kolor = 'style="background-color:#00FF00;"'; //ZIELONY
	}
	
	return $kolor;
}

function kolor_wiersza_budowy($od, $do, $zaznaczony){
	if($zaznaczony == 1){
		$kolor = 'style="background-color: red;"'; //CZERWONY	
	}elseif($do < date('Y-m-d')){
		$kolor = 'style="background-color:#CCCCCC;"'; //SZARY
	}elseif($od <= date('Y-m-d') && date('Y-m-d') <= $do){
		$kolor = 'style="background-color: #00FF00;"'; //ZIELONY
	}elseif(date('Y-m-d') >= date('Y-m-d',strtotime($od.' -7 day')) && date('Y-m-d') < $od){
		$kolor = 'style="background-color: #FFE010;"'; //POMARAŃCZOWY
	}elseif(date('Y-m-d') < $od){		
		$kolor = 'style="background-color:yellow;"'; //ZOLTY
	}
	
	return $kolor;
}

function kolor_wiersza_wyjazdy_pracownicy(){
	$kolor = 'style="background-color: #00FF00;"'; //ZIELONY
	
	return $kolor;
}

function kolor_wiersza_wyjazdy_budowy($color){
	$kolor = 'style="background-color: '.$color.';"';
	
	return $kolor;
}

function kolor_wiersza_wyjazdy_samochody(){
	$kolor = 'style="background-color: #00FF00;"'; //ZIELONY
	
	return $kolor;
}

function kolor_wiersza_wyjazdy($color){
	$kolor = 'style="background-color: '.$color.';"';
	
	return $kolor;
}

function kolor_wiersza_wyjazdy_historia($color){
	$kolor = 'style="background-color: '.$color.';"';
	
	return $kolor;
}

function kolor_wiersza_oferty(){
	$kolor = 'style="background-color: #00FF00;"'; //ZIELONY
	
	return $kolor;
}

function kolor_wiersza_konserwacje(){
	$kolor = 'style="background-color: #00FF00;"'; //ZIELONY
	
	return $kolor;
}

function kolor_wiersza_usterki($data_usuniecia, $termin_usuniecia){
	if($data_usuniecia != '' && $data_usuniecia != '0000-00-00' && $data_usuniecia <= date('Y-m-d')){
		$kolor = 'style="background-color: #CCCCCC;"'; //SZARY	
	}elseif($termin_usuniecia != '' && $termin_usuniecia != '0000-00-00'){
		$kolor = 'style="background-color: #00FF00;"'; //ZIELONY
	}else{
		$kolor = 'style="background-color:yellow;"'; //ZIELONY
	}
	
	return $kolor;
}

function kolor_wiersza_punkty($od, $do){
	if($do < date('Y-m-d')){
		$kolor = 'style="background-color:#CCCCCC;"'; //SZARY
	}elseif($od <= date('Y-m-d') && date('Y-m-d') <= $do){
		$kolor = 'style="background-color: #00FF00;"'; //ZIELONY
	}elseif(date('Y-m-d') < $od){		
		$kolor = 'style="background-color:yellow;"'; //ZOLTY
	}
	
	return $kolor;
}

function oferty_przecinek_na_kropke_wysokosc_od($liczba){
	$liczba = str_replace(',','.',trim($liczba));
	
	if(!is_numeric($liczba)){
		$liczba = 0;
	}	
	
	return $liczba;
}

function oferty_przecinek_na_kropke_wysokosc_do($liczba){
	$liczba = str_replace(',','.',trim($liczba));
	
	if(!is_numeric($liczba)){
		$liczba = 999999;
	}	
	
	return $liczba;
}

function oferty_przecinek_na_kropke($liczba){
	$liczba = str_replace(',','.',trim($liczba));
	
	if(!is_numeric($liczba)){
		$liczba = 0;
	}	
	
	return $liczba;
}

function wyjazdy_przecinek_na_kropke($liczba){
		$liczba = str_replace(',','.',trim($liczba));
	
	if(!is_numeric($liczba)){
		$liczba = 0;
	}	
	
	return $liczba;
}

function zerowaDataNaPusta($data){
	if($data == '0000-00-00'){
		return null;
	}else{
		return $data;
	}
}

