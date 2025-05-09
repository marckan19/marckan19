<?php
ob_start();
session_start();
require_once('header.php');
require_once('mysql_connecting.php');

if ((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || 
    (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')) {

    require_once('menu.php');

    $id = $_GET['id'] ?? 0;

    // Œcie¿ka z filtrami
    $filters_path = (strpos($_SERVER['REQUEST_URI'], "&") > 0)
        ? substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "&"))
        : '';

    echo '<table><tr>';
    echo '<td><h2>Zwy¿ki - kopiowanie wpisu</h2></td><td width="20px" />';
    require_once('legenda_zwyzki.php');
    echo '</tr></table>';

    if (isset($_POST['powrot'])) {
        $filters_path = (strpos($_SERVER['REQUEST_URI'], "&") > 0)
            ? '?' . substr(substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "&")), 1)
            : '';
        header('Location: tabela_zwyzki.php' . $filters_path);
        exit;
    } elseif (isset($_POST['kopiuj'])) {
        $conn = getDbConnection();

        $budowa = $_POST['budowa'];
        $firma = $_POST['firma_wynajmujaca'];
        $rodzaj = $_POST['rodzaj_zwyzki'];
        $wysokosc = $_POST['wysokosc'];
        $cena = $_POST['cena'];
        $nr_maszyny = $_POST['nr_maszyny'];

        $data_wynajmu_od = ($_POST['czy_data_wynajmu_od'] == 1) ? $_POST['data_wynajmu_od'] : $_POST['data_wynajmu_od_stara'];
        $data_wynajmu_do = ($_POST['czy_data_wynajmu_do'] == 1) ? $_POST['data_wynajmu_do'] : $_POST['data_wynajmu_do_stara'];
        $zawieszone_do = ($_POST['czy_zawieszone_do'] == 1) ? $_POST['zawieszone_do'] : $_POST['zawieszone_do_stara'];
        $data_zawiesz = $_POST['data_zawiesz'];
        $data_zdania = ($_POST['czy_data_zdania'] == 1) ? $_POST['data_zdania'] : $_POST['data_zdania_stara'];
        $uwagi = $_POST['uwagi'];
        $os_kontaktowa = $_POST['os_kontaktowa'];
        $zaznaczony_wiersz = ($_POST['zaznaczony_wiersz'] == 1) ? 1 : 0;
        $oferta = ($_POST['oferta'] == 1) ? 1 : 0;
        $rach_1_nr = $_POST['rach_1_nr'];
        $rach_1_okres = $_POST['rach_1_okres'];
        $rach_1_kwota = $_POST['rach_1_kwota'];
        $rach_1_uwagi = $_POST['rach_1_uwagi'];
        $korekta_nr = $_POST['korekta_nr'];
        $korekta_kwota = $_POST['korekta_kwota'];

        $creator_id = $_SESSION['user_id'] ?? $_COOKIE['ciastko_user_id'];
        $creation_date = date('Y-m-d G:i:s');

        $stmt = $conn->prepare("
            INSERT INTO panel_tabela_zwyzki 
            (budowa, firma_wynajmujaca, rodzaj_zwyzki, wysokosc, cena, nr_maszyny, data_wynajmu_od, data_wynajmu_do, zawieszone_do, data_zawiesz, data_zdania, uwagi, os_kontaktowa, creator_id, creation_date, modificator_id, modification_date, zaznaczony_wiersz, oferta, rach_1_nr, rach_1_okres, rach_1_kwota, rach_1_uwagi, korekta_nr, korekta_kwota)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $budowa,
            $firma,
            $rodzaj,
            $wysokosc,
            $cena,
            $nr_maszyny,
            $data_wynajmu_od,
            $data_wynajmu_do,
            $zawieszone_do,
            $data_zawiesz,
            $data_zdania,
            $uwagi,
            $os_kontaktowa,
            $creator_id,
            $creation_date,
            $creator_id,
            $creation_date,
            $zaznaczony_wiersz,
            $oferta,
            $rach_1_nr,
            $rach_1_okres,
            $rach_1_kwota,
            $rach_1_uwagi,
            $korekta_nr,
            $korekta_kwota
        ]);

        $new_id = $conn->lastInsertId();
        header('Location: tabela_zwyzki_skopiowano.php?id=' . $new_id . $filters_path);
        exit;

    } else {
        require_once('tabela_zwyzki_kopiuj_form.php');
    }
} else {
    require_once('logout.php');
}

require_once('footer.php');