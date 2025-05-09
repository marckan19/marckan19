<?php
ob_start();
session_start();
require_once('header.php');

if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){
	require_once('menu.php');

$adres_odbiorcy="admin@brassco.pl";
	$adres_kopii="admin@brassco.pl";
	$adres_nadawcy=$_POST['email'];
	$naglowek_listu="From: $adres_nadawcy\r\nCc: $adres_kopii";
	$temat_listu="TICKET";
	$tresc_listu=$_POST['imie'] . " napisał(a):" . $_POST['zapytanie'];
	if(mail($adres_odbiorcy, $temat_listu, $tresc_listu, $naglowek_listu))
		{
		echo("GDZIE KLIKASZ!! '$temat_listu' jeszcze jest niegotowy!");
		}
		else
		{
		echo("Błąd podczas wysyłania listu: '$temat_listu'.");
		}
require_once('footer.php');	
