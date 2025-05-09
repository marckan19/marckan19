<?php
ob_start();
session_start();
require_once('header.php');
require_once('mysql_connecting.php'); // Załaduj połączenie z bazą danych

if ((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')) {
    require_once('menu.php');

    // ZAPAMIETANA SCIEZKA Z FILTRAMI
    if (strpos($_SERVER['REQUEST_URI'], "&") > 0) {
        $filters_path = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "?"));
    } else {
        $filters_path = '';
    }

    echo '
    <table>
        <tr>';
    if ($row_header['header'] == 'n') {
        echo '<td><h2>Arbeitsbühnen - neuen Eintrag erstellen</h2></td><td width="20px" />';
    } else {
        echo '<td><h2>Zwyżki - dodawanie wpisu</h2></td><td width="20px" />';
    }

    require_once('legenda_zwyzki.php');
    echo '</tr>
    </table>';

    if (isset($_POST['powrot'])) {
        header('Location: tabela_zwyzki.php' . $filters_path);
    } elseif (isset($_POST['dodaj'])) {

        $budowa = $_POST['budowa'];
        $firma_wynajmujaca = $_POST['firma_wynajmujaca'];
        $rodzaj_zwyzki = $_POST['rodzaj_zwyzki'];
        $wysokosc = $_POST['wysokosc'];
        $cena = $_POST['cena'];
        $nr_maszyny = $_POST['nr_maszyny'];
        if ($_POST['czy_data_wynajmu_od'] == 1) {
            $data_wynajmu_od = $_POST['data_wynajmu_od'];
        }
        if ($_POST['czy_data_wynajmu_do'] == 1) {
            $data_wynajmu_do = $_POST['data_wynajmu_do'];
        }
        if ($_POST['czy_zawieszone_do'] == 1) {
            $zawieszone_do = $_POST['zawieszone_do'];
        }

        $data_zawiesz = $_POST['data_zawiesz'];
        if ($_POST['czy_data_zdania'] == 1) {
            $data_zdania = $_POST['data_zdania'];
        }
        $uwagi = $_POST['uwagi'];
        $os_kontaktowa = $_POST['os_kontaktowa'];

        $creation_date = date('Y-m-d G:i:s');
        if (isset($_SESSION['user_id'])) {
            $creator_id = $_SESSION['user_id'];
        } else {
            $creator_id = $_COOKIE['ciastko_user_id'];
        }
        if ($_POST['zaznaczony_wiersz'] == 1) {
            $zaznaczony_wiersz = 1;
        } else {
            $zaznaczony_wiersz = 0;
        }

        if ($_POST['oferta'] == 1) {
            $oferta = 1;
        } else {
            $oferta = 0;
        }

        $rach_1_nr = $_POST['rach_1_nr'];
        $rach_1_okres = $_POST['rach_1_okres'];
        $rach_1_kwota = $_POST['rach_1_kwota'];
        $rach_1_uwagi = $_POST['rach_1_uwagi'];
        $korekta_nr = $_POST['korekta_nr'];
        $korekta_kwota = $_POST['korekta_kwota'];

        // Przygotowanie zapytania SQL z wykorzystaniem PDO
        $query_insert = "
            INSERT INTO panel_tabela_zwyzki (
                budowa, firma_wynajmujaca, rodzaj_zwyzki, wysokosc, cena, nr_maszyny, 
                data_wynajmu_od, data_wynajmu_do, zawieszone_do, data_zawiesz, data_zdania, 
                uwagi, os_kontaktowa, creator_id, creation_date, modificator_id, 
                modification_date, zaznaczony_wiersz, oferta, rach_1_nr, rach_1_okres, 
                rach_1_kwota, rach_1_uwagi, korekta_nr, korekta_kwota
            ) VALUES (
                :budowa, :firma_wynajmujaca, :rodzaj_zwyzki, :wysokosc, :cena, :nr_maszyny, 
                :data_wynajmu_od, :data_wynajmu_do, :zawieszone_do, :data_zawiesz, :data_zdania, 
                :uwagi, :os_kontaktowa, :creator_id, :creation_date, :creator_id, 
                :creation_date, :zaznaczony_wiersz, :oferta, :rach_1_nr, :rach_1_okres, 
                :rach_1_kwota, :rach_1_uwagi, :korekta_nr, :korekta_kwota
            )";

        // Przygotowanie zapytania PDO
        $stmt = $pdo->prepare($query_insert);

        // Wiązanie zmiennych z zapytaniem
        $stmt->bindParam(':budowa', $budowa);
        $stmt->bindParam(':firma_wynajmujaca', $firma_wynajmujaca);
        $stmt->bindParam(':rodzaj_zwyzki', $rodzaj_zwyzki);
        $stmt->bindParam(':wysokosc', $wysokosc);
        $stmt->bindParam(':cena', $cena);
        $stmt->bindParam(':nr_maszyny', $nr_maszyny);
        $stmt->bindParam(':data_wynajmu_od', $data_wynajmu_od);
        $stmt->bindParam(':data_wynajmu_do', $data_wynajmu_do);
        $stmt->bindParam(':zawieszone_do', $zawieszone_do);
        $stmt->bindParam(':data_zawiesz', $data_zawiesz);
        $stmt->bindParam(':data_zdania', $data_zdania);
        $stmt->bindParam(':uwagi', $uwagi);
        $stmt->bindParam(':os_kontaktowa', $os_kontaktowa);
        $stmt->bindParam(':creator_id', $creator_id);
        $stmt->bindParam(':creation_date', $creation_date);
        $stmt->bindParam(':zaznaczony_wiersz', $zaznaczony_wiersz);
        $stmt->bindParam(':oferta', $oferta);
        $stmt->bindParam(':rach_1_nr', $rach_1_nr);
        $stmt->bindParam(':rach_1_okres', $rach_1_okres);
        $stmt->bindParam(':rach_1_kwota', $rach_1_kwota);
        $stmt->bindParam(':rach_1_uwagi', $rach_1_uwagi);
        $stmt->bindParam(':korekta_nr', $korekta_nr);
        $stmt->bindParam(':korekta_kwota', $korekta_kwota);

        // Wykonanie zapytania
        $stmt->execute();

        // Przekierowanie na stronę z dodanym wpisem
        header('Location: tabela_zwyzki_dodano.php?id=' . $pdo->lastInsertId() . $filters_path);
    } else {
        require_once('tabela_zwyzki_dodaj_form.php');
    }
} else {
    require_once('logout.php');
}

require_once('footer.php');
