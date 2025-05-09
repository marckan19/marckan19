<?php
ob_start();
session_start();
require_once('header.php');

// Sprawdzenie, czy u¿ytkownik jest zalogowany (sesja lub ciasteczko)
if (isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])) {
    require_once('menu.php');

    echo '
    <table>
        <tr>';
        if ($row_header['header'] == 'n') {
            echo '<td><h2>Bauvorhaben</h2></td><td width="20px" />';
        } else {
            echo '<td><h2>Budowy</h2></td><td width="20px" />';
        }
        require_once('legenda_budowy.php');
    echo '</tr>
    </table>';

    require 'tabela_budowy_filtry.php';

    // Obs³uga formularza filtrów
    if (isset($_POST['filtry_szukaj'])) {
        // Zabezpieczenie danych wejœciowych
        $filtry_login = mysqli_real_escape_string($conn, $_POST['filtry_login']);
        $filtry_zleceniodawca = mysqli_real_escape_string($conn, $_POST['filtry_zleceniodawca']);
        $filtry_wys_hali = mysqli_real_escape_string($conn, $_POST['filtry_wys_hali']);
        $filtry_realizacja_od = mysqli_real_escape_string($conn, $_POST['filtry_realizacja_od']);
        $filtry_realizacja_do = mysqli_real_escape_string($conn, $_POST['filtry_realizacja_do']);
        $filtry_kierownik = mysqli_real_escape_string($conn, $_POST['filtry_kierownik']);
        $filtry_dokumenty = mysqli_real_escape_string($conn, $_POST['filtry_dokumenty']);
        $filtry_tydzien = mysqli_real_escape_string($conn, $_POST['filtry_tydzien']);
        $filtry_rewizja = mysqli_real_escape_string($conn, $_POST['filtry_rewizja']);
        $filtry_stan = mysqli_real_escape_string($conn, $_POST['filtry_stan']);
        $filtry_uwagi = mysqli_real_escape_string($conn, $_POST['filtry_uwagi']);
        $filtry_suplementy = mysqli_real_escape_string($conn, $_POST['filtry_suplementy']);
        $filtry_adnotacje = mysqli_real_escape_string($conn, $_POST['filtry_adnotacje']);

        // Zabezpieczenie przed pustymi datami
        if ($_POST['czy_data_wpisu'] == 1) {
            $data_wpisu_od = mysqli_real_escape_string($conn, $_POST['filtry_data_wpisu_od']);
            $data_wpisu_do = mysqli_real_escape_string($conn, $_POST['filtry_data_wpisu_do']);
        } else {
            $data_wpisu_od = '1900-01-01';
            $data_wpisu_do = '2900-01-01';
        }

        // Pobranie ID u¿ytkownika z loginu
        $query_login_id = "SELECT id FROM panel_users WHERE login = '$filtry_login'";
        $result_login_id = mysqli_query($conn, $query_login_id);
        if ($result_login_id) {
            $row_login_id = mysqli_fetch_assoc($result_login_id);
            $user_id = $row_login_id['id'];
        } else {
            $user_id = null;
        }

        // Zapytanie do bazy z filtrami
        $query = "SELECT u.login AS login, ptn.* 
                  FROM panel_tabela_budowy ptn
                  JOIN panel_users u ON u.id = ptn.creator_id
                  WHERE ptn.zleceniodawca LIKE '%$filtry_zleceniodawca%'
                    AND ptn.wys_hali LIKE '%$filtry_wys_hali%'
                    AND ptn.realizacja_od LIKE '%$filtry_realizacja_od%'
                    AND ptn.realizacja_do LIKE '%$filtry_realizacja_do%'
                    AND ptn.kierownik LIKE '%$filtry_kierownik%'
                    AND ptn.dokumenty LIKE '%$filtry_dokumenty%'
                    AND ptn.tydzien LIKE '%$filtry_tydzien%'
                    AND ptn.rewizja LIKE '%$filtry_rewizja%'
                    AND ptn.stan LIKE '%$filtry_stan%'
                    AND ptn.creation_date BETWEEN '$data_wpisu_od' AND '$data_wpisu_do'
                    AND ptn.uwagi LIKE '%$filtry_uwagi%'
                    AND ptn.suplementy LIKE '%$filtry_suplementy%'
                    AND ptn.adnotacje LIKE '%$filtry_adnotacje%'
                    AND u.login LIKE '%$filtry_login%'
                  ORDER BY ptn.realizacja_od";

        // Œcie¿ka do filtrów w URL
        $filters_path = '&zleceniodawca=' . urlencode($filtry_zleceniodawca) .
                        '&wys_hali=' . urlencode($filtry_wys_hali) .
                        '&realizacja_od=' . urlencode($filtry_realizacja_od) .
                        '&realizacja_do=' . urlencode($filtry_realizacja_do) .
                        '&kierownik=' . urlencode($filtry_kierownik) .
                        '&dokumenty=' . urlencode($filtry_dokumenty) .
                        '&tydzien=' . urlencode($filtry_tydzien) .
                        '&rewizja=' . urlencode($filtry_rewizja) .
                        '&stan=' . urlencode($filtry_stan) .
                        '&data_wpisu_od=' . urlencode($filtry_data_wpisu_od) .
                        '&data_wpisu_do=' . urlencode($filtry_data_wpisu_do) .
                        '&uwagi=' . urlencode($filtry_uwagi) .
                        '&suplementy=' . urlencode($filtry_suplementy) .
                        '&adnotacje=' . urlencode($filtry_adnotacje) .
                        '&login=' . urlencode($filtry_login);
    } elseif (strpos($_SERVER['REQUEST_URI'], "&") > 0) {

        // Analogiczne dzia³anie w przypadku filtrów w URL
        $zleceniodawca = mysqli_real_escape_string($conn, $_GET['zleceniodawca']);
        $wys_hali = mysqli_real_escape_string($conn, $_GET['wys_hali']);
        $realizacja_od = mysqli_real_escape_string($conn, $_GET['realizacja_od']);
        $realizacja_do = mysqli_real_escape_string($conn, $_GET['realizacja_do']);
        $kierownik = mysqli_real_escape_string($conn, $_GET['kierownik']);
        $dokumenty = mysqli_real_escape_string($conn, $_GET['dokumenty']);
        $tydzien = mysqli_real_escape_string($conn, $_GET['tydzien']);
        $rewizja = mysqli_real_escape_string($conn, $_GET['rewizja']);
        $stan = mysqli_real_escape_string($conn, $_GET['stan']);
        $data_wpisu_od = mysqli_real_escape_string($conn, $_GET['data_wpisu_od']);
        $data_wpisu_do = mysqli_real_escape_string($conn, $_GET['data_wpisu_do']);
        $uwagi = mysqli_real_escape_string($conn, $_GET['uwagi']);
        $suplementy = mysqli_real_escape_string($conn, $_GET['suplementy']);
        $adnotacje = mysqli_real_escape_string($conn, $_GET['adnotacje']);
        $login = mysqli_real_escape_string($conn, $_GET['login']);

        $query = "SELECT u.login AS login, ptz.* 
                  FROM panel_tabela_budowy ptz
                  JOIN panel_users u ON u.id = ptz.creator_id
                  WHERE ptz.zleceniodawca LIKE '%$zleceniodawca%'
                    AND ptz.wys_hali LIKE '%$wys_hali%'
                    AND ptz.realizacja_od LIKE '%$realizacja_od%'
                    AND ptz.realizacja_do LIKE '%$realizacja_do%'
                    AND ptz.kierownik LIKE '%$kierownik%'
                    AND ptz.dokumenty LIKE '%$dokumenty%'
                    AND ptz.tydzien LIKE '%$tydzien%'
                    AND ptz.rewizja LIKE '%$rewizja%'
                    AND ptz.stan LIKE '%$stan%'
                    AND ptz.creation_date BETWEEN '$data_wpisu_od' AND '$data_wpisu_do'
                    AND ptz.uwagi LIKE '%$uwagi%'
                    AND ptz.suplementy LIKE '%$suplementy%'
                    AND ptz.adnotacje LIKE '%$adnotacje%'
                    AND u.login LIKE '%$login%'
                  ORDER BY ptz.realizacja_od";

        // Œcie¿ka do filtrów w URL
        $filters_path = '&zleceniodawca=' . urlencode($zleceniodawca) .
                        '&wys_hali=' . urlencode($wys_hali) .
                        '&realizacja_od=' . urlencode($realizacja_od) .
                        '&realizacja_do=' . urlencode($realizacja_do) .
                        '&kierownik=' . urlencode($kierownik) .
                        '&dokumenty=' . urlencode($dokumenty) .
                        '&tydzien=' . urlencode($tydzien) .
                        '&rewizja=' . urlencode($rewizja) .
                        '&stan=' . urlencode($stan) .
                        '&data_wpisu_od=' . urlencode($data_wpisu_od) .
                        '&data_wpisu_do=' . urlencode($data_wpisu_do) .
                        '&uwagi=' . urlencode($uwagi) .
                        '&suplementy=' . urlencode($suplementy) .
                        '&adnotacje=' . urlencode($adnotacje) .
                        '&login=' . urlencode($login);
    }

    // Wykonanie zapytania i wyœwietlenie wyników
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Wyœwietlenie wyników
            echo '<p>' . htmlspecialchars($row['login']) . '</p>';
        }
    }
}
?>