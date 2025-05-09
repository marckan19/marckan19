<?php
require_once("mysql_connecting.php");
require_once("functions.php");
session_start();

// Sprawdzenie ID z GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("B³¹d: nieprawid³owe ID");
}
$id = (int) $_GET['id'];
$filters_path = isset($_GET['filters_path']) ? htmlspecialchars($_GET['filters_path']) : '';

// Pobierz dane u¿ytkownika (z sesji lub ciasteczka)
$db = getDbConnection();
$user_id = $_SESSION['user_id'] ?? $_COOKIE['ciastko_user_id'] ?? null;

if (!$user_id) {
    die("Brak zalogowanego u¿ytkownika.");
}

// Pobierz dane u¿ytkownika
$stmt = $db->prepare("SELECT * FROM panel_users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$row_header = $stmt->fetch(PDO::FETCH_ASSOC);

echo '<form action="tabela_zwyzki_edytuj.php?id=' . $id . $filters_path . '" method="post">
<table width="100%">';

if ($row_header['header'] == 'n') {
    echo '
    <tr>
        <td id="naglowek" rowspan="2">Bauvorhaben</td>
        <td id="naglowek" rowspan="2">Vermieter</td>
        <td id="naglowek" rowspan="2">Bühnentyp</td>
        <td id="naglowek" rowspan="2">Arbeitshöhe</td>							
        <td id="naglowek" rowspan="2">Mietpreis</td>
        <td id="naglowek" rowspan="2">MaschinenNr.</td>
        <td id="naglowek" colspan="2">Mietzeitraum</td>
        <td id="naglowek" rowspan="2">auf Stand gesetzt bis zum <input type="checkbox" name="czy_zawieszone_do" value="1" /></td>
        <td id="naglowek" rowspan="2">Standtage</td>
        <td id="naglowek" rowspan="2">Freimeldung <input type="checkbox" name="czy_data_zdania" value="1" /></td>
        <td id="naglowek" rowspan="2">Bemerkungen</td>
        <td id="naglowek" rowspan="2">Ansprechpartner</td>
        <td id="naglowek" rowspan="2">Datum der Eintragung</td>
        <td id="naglowek" rowspan="2">LOGIN</td>
        <td id="naglowek" rowspan="2">Z</td>
        <td id="naglowek" rowspan="2">Angebot</td>
        <td id="naglowek" colspan="4">Rechnung I</td>
        <td id="naglowek" colspan="2">Gutschrift</td>
    </tr>
    <tr>
        <td id="naglowek">vom <input type="checkbox" name="czy_data_wynajmu_od" value="1" /></td>
        <td id="naglowek">bis zum <input type="checkbox" name="czy_data_wynajmu_do" value="1" /></td>
        <td id="naglowek">Nr</td>
        <td id="naglowek">Abrechnungs Zeitraum</td>
        <td id="naglowek">Betrag</td>
        <td id="naglowek">Bemerkungen</td>
        <td id="naglowek">Nr</td>
        <td id="naglowek">Betrag</td>
    </tr>';
} else {
    echo '
    <tr>
        <td id="naglowek" rowspan="2">BUDOWA</td>
        <td id="naglowek" rowspan="2">FIRMA WYNAJMUJ¥CA</td>
        <td id="naglowek" rowspan="2">RODZAJ ZWY¯KI</td>
        <td id="naglowek" rowspan="2">WYSOKOŒÆ</td>							
        <td id="naglowek" rowspan="2">CENA</td>
        <td id="naglowek" rowspan="2">NR MASZYNY</td>
        <td id="naglowek" colspan="2">DATA WYNAJMU</td>
        <td id="naglowek" rowspan="2">ZAWIESZONE DO <input type="checkbox" name="czy_zawieszone_do" value="1" /></td>
        <td id="naglowek" rowspan="2">DATA ZAWIESZENIA</td>
        <td id="naglowek" rowspan="2">DATA ZDANIA <input type="checkbox" name="czy_data_zdania" value="1" /></td>
        <td id="naglowek" rowspan="2">UWAGI</td>
        <td id="naglowek" rowspan="2">OSOBA KONTAKTOWA</td>
        <td id="naglowek" rowspan="2">DATA WPISU</td>
        <td id="naglowek" rowspan="2">LOGIN</td>
        <td id="naglowek" rowspan="2">Z</td>
        <td id="naglowek" rowspan="2">OFERTA</td>
        <td id="naglowek" colspan="4">RACHUNKI I</td>
        <td id="naglowek" colspan="2">KOREKTY</td>
    </tr>
    <tr>
        <td id="naglowek">OD <input type="checkbox" name="czy_data_wynajmu_od" value="1" /></td>
        <td id="naglowek">DO <input type="checkbox" name="czy_data_wynajmu_do" value="1" /></td>
        <td id="naglowek">NR</td>
        <td id="naglowek">OKR. ROZ.</td>
        <td id="naglowek">KWOTA</td>
        <td id="naglowek">UWAGI</td>
        <td id="naglowek">NR</td>
        <td id="naglowek">KWOTA</td>
    </tr>';
}

// Pobranie rekordu do edycji
$stmt = $db->prepare("SELECT * FROM panel_tabela_zwyzki WHERE id = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Nie znaleziono rekordu o podanym ID.");
}

// Pobranie loginu twórcy wpisu
$stmt = $db->prepare("SELECT login FROM panel_users WHERE id = :id");
$stmt->execute([':id' => $row['creator_id']]);
$row_login = $stmt->fetch(PDO::FETCH_ASSOC);

$zw = $row['zaznaczony_wiersz'] == 1 ? 'checked' : '';
$of = $row['oferta'] == 1 ? 'checked' : '';

// Wiersz z polami edycyjnymi (fragment)
echo '<tr ' . kolor_wiersza_zwyzki($row['data_zdania'], $row['zawieszone_do'], $row['zaznaczony_wiersz'], $row['oferta']) . '>';
echo '<td id="komorka_edytowanie"><textarea name="budowa" cols="10" rows="2">' . htmlspecialchars($row['budowa']) . '</textarea></td>';
// ... analogiczne pola dla pozosta³ych kolumn ...

// Przycisk zapisu
echo '</table><br />';
if ($row_header['header'] == 'n') {
    echo '<input type="submit" value="Ändern" name="edytuj" />';
    echo '<input type="submit" value="Zurück" name="powrot" />';
} else {
    echo '<input type="submit" value="Edytuj" name="edytuj" />';
    echo '<input type="submit" value="Powrót" name="powrot" />';
}
echo '</form>';
?>