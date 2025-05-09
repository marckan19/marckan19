<?php

echo '<!DOCTYPE html>
<html lang="pl">
<head>
	<title>PANEL</title>
	<meta charset="utf-8">
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="calendar.js"></script>
	<link rel="stylesheet" href="css/picker/datepicker.css">
</head>
<body>';

require_once('mysql_connecting.php'); // Plik zawierający funkcję getDbConnection()
require_once('functions.php');

$pdo = getDbConnection();
$row_header = null;

if (isset($_SESSION['user_id'])) {
    $query_header = "SELECT * FROM panel_users WHERE id = :id";
    $stmt = $pdo->prepare($query_header);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_COOKIE['ciastko_user_id'])) {
    $query_header = "SELECT * FROM panel_users WHERE id = :id";
    $stmt = $pdo->prepare($query_header);
    $stmt->execute([':id' => $_COOKIE['ciastko_user_id']]);
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>