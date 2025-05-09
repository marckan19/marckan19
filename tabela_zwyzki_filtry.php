<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo '
<div style="overflow: scroll;">
<form action="tabela_zwyzki.php" method="post">
    <table width="100%">
        <tr id="naglowek_filtrow">
';

require_once('mysql_connecting.php'); // Plik z funkcją getDbConnection()
$pdo = getDbConnection();

// Pobieramy dane użytkownika dla nagłówka filtrów
$row_header = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM panel_users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_COOKIE['ciastko_user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM panel_users WHERE id = :id");
    $stmt->execute([':id' => $_COOKIE['ciastko_user_id']]);
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($row_header && $row_header['header'] == 'n') {
    echo '
        <td rowspan="2">Bauvorhaben</td>        
        <td rowspan="2">Vermieter</td>
        <td rowspan="2">Bühnentyp</td>
        <td rowspan="2">Arbeitshöhe</td>
        <td rowspan="2">Mietpreis</td>
        <td rowspan="2">MaschinenNr.</td>
        <td rowspan="2">Mietzeitraum vom</td>
        <td rowspan="2">Mietzeitraum bis zum</td>
        <td rowspan="2">auf Stand gesetzt bis zum</td>
        <td rowspan="2">Standtage</td>
        <td rowspan="2">Freimeldung</td>
        <td rowspan="2">Bemerkungen</td>
        <td rowspan="2">Ansprechpartner</td>            
        <td rowspan="2">Datum der Eintragung</td>            
        <td rowspan="2">LOGIN</td>                        
        <td colspan="4">Rechnung I</td>    
        <td colspan="2">Gutschrift</td>    
    ';
} else {
    echo '
        <td rowspan="2">BUDOWA</td>        
        <td rowspan="2">FIRMA WYNAJMUJĄCA</td>
        <td rowspan="2">RODZAJ ZWYŻKI</td>
        <td rowspan="2">WYSOKOŚĆ</td>
        <td rowspan="2">CENA</td>
        <td rowspan="2">NR MASZYNY</td>
        <td rowspan="2">DATA WYNAJMU OD</td>
        <td rowspan="2">DATA WYNAJMU DO</td>
        <td rowspan="2">ZAW. DO</td>
        <td rowspan="2">DATA ZAW.</td>
        <td rowspan="2">DATA ZDANIA</td>
        <td rowspan="2">UWAGI</td>
        <td rowspan="2">OSOBA KONTAKTOWA</td>            
        <td rowspan="2">DATA WPISU</td>            
        <td rowspan="2">LOGIN</td>                        
        <td colspan="4">RACHUNKI I</td>    
        <td colspan="2">KOREKTY</td>    
    ';
}
echo '
        </tr>
        <tr id="naglowek_filtrow">
';
if ($row_header && $row_header['header'] == 'n') {
    echo '
        <td rowspan="1">Nr</td>        
        <td rowspan="1">Abrechnungs Zeitraum</td>        
        <td rowspan="1">Betrag</td>        
        <td rowspan="1">Bemerkungen</td>        
        <td rowspan="1">Nr</td>        
        <td rowspan="1">Betrag</td>        
    ';
} else {
    echo '
        <td rowspan="1">NR</td>        
        <td rowspan="1">OKR. ROZ.</td>        
        <td rowspan="1">KWOTA</td>        
        <td rowspan="1">UWAGI</td>        
        <td rowspan="1">NR</td>        
        <td rowspan="1">KWOTA</td>    
    ';
}
echo '
        </tr>
        <tr>
            <td><textarea name="filtry_budowa" cols="10" rows="1"></textarea></td>
            <td><textarea name="filtry_firma_wynajmujaca" cols="10" rows="1"></textarea></td>            
            <td><textarea name="filtry_rodzaj_zwyzki" cols="7" rows="1"></textarea></td>
            <td><textarea name="filtry_wysokosc" cols="10" rows="1"></textarea></td>
            <td><textarea name="filtry_cena" cols="3" rows="1"></textarea></td>
            <td><textarea name="filtry_nr_maszyny" cols="6" rows="1"></textarea></td>
            <td><textarea name="filtry_data_wynajmu_od" cols="6" rows="1"></textarea></td>
            <td><textarea name="filtry_data_wynajmu_do" cols="6" rows="1"></textarea></td>
            <td><textarea name="filtry_zawieszone_do" cols="10" rows="1"></textarea></td>
            <td><textarea name="filtry_data_zawiesz" cols="10" rows="1"></textarea></td>
            <td><textarea name="filtry_data_zdania" cols="10" rows="1"></textarea></td>
            <td><textarea name="filtry_uwagi" cols="10" rows="1"></textarea></td>
            <td><textarea name="filtry_os_kontaktowa" cols="10" rows="1"></textarea></td>            
            <td><textarea name="filtry_data_wpisu" cols="7" rows="1"></textarea></td>            
            <td><textarea name="filtry_login" cols="5" rows="1"></textarea></td>    
            <td><textarea name="filtry_rach_1_nr" cols="5" rows="1"></textarea></td>                
            <td><textarea name="filtry_rach_1_okres" cols="5" rows="1"></textarea></td>                
            <td><textarea name="filtry_rach_1_kwota" cols="5" rows="1"></textarea></td>                
            <td><textarea name="filtry_rach_1_uwagi" cols="5" rows="1"></textarea></td>                
            <td><textarea name="filtry_korekta_nr" cols="5" rows="1"></textarea></td>                
            <td><textarea name="filtry_korekta_kwota" cols="5" rows="1"></textarea></td>            
        </tr>
    </table>
';

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM panel_users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_COOKIE['ciastko_user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM panel_users WHERE id = :id");
    $stmt->execute([':id' => $_COOKIE['ciastko_user_id']]);
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($row_header && $row_header['header'] == 'n') {
    echo '<input type="submit" value="Suchen" name="filtry_szukaj" />';
} else {
    echo '<input type="submit" value="Szukaj" name="filtry_szukaj" />';
}
echo '</form></div><br />';
?>