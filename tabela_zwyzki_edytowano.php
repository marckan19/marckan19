<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')) {
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    } else {
        $id = 0;
    }
    
    // ZAPAMIETANA SCIEZKA Z FILTRAMI
    if(strpos($_SERVER['REQUEST_URI'], "&") > 0){
        $filters_path = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "&"));
        $filters_path = '?' . substr($filters_path, 1);
    } else {
        $filters_path = '';
    }
    
    require_once('menu.php');
    
    echo '
    <table>
        <tr>';
            if($row_header['header'] == 'n'){
                echo '<td><h2>Arbeitsbühnen - den Eintrag bearbeiten</h2></td><td width="20px" />';
            } else {
                echo '<td><h2>Zwyżki - edytowanie wpisu</h2></td><td width="20px" />';
            }

            require_once('legenda_zwyzki.php');
    echo '</tr>
    </table>';
    
    // Użycie MySQLi z przygotowanymi zapytaniami
    $conn = new mysqli("localhost", "username", "password", "database_name"); // Połączenie z bazą danych
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM panel_tabela_zwyzki WHERE id = ?");
    $stmt->bind_param("i", $id);  // "i" oznacza, że $id jest typu integer
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // WYCIĄGANIE ID USERA
    $stmt_login = $conn->prepare("SELECT * FROM panel_users WHERE id = ?");
    $stmt_login->bind_param("i", $row['creator_id']);
    $stmt_login->execute();
    $result_login = $stmt_login->get_result();
    $row_login = $result_login->fetch_assoc();
    
    echo '
    <table width="100%">';
    
    if(isset($_SESSION['user_id'])){
        $stmt_header = $conn->prepare("SELECT * FROM panel_users WHERE id = ?");
        $stmt_header->bind_param("i", $_SESSION['user_id']);
    } elseif(isset($_COOKIE['ciastko_user_id'])){
        $stmt_header = $conn->prepare("SELECT * FROM panel_users WHERE id = ?");
        $stmt_header->bind_param("i", $_COOKIE['ciastko_user_id']);
    }
    $stmt_header->execute();
    $result_header = $stmt_header->get_result();
    $row_header = $result_header->fetch_assoc();

    if($row_header['header'] == 'n'){
        echo '
            <tr>
                <td id="naglowek" rowspan="2">Bauvorhaben</td>
                <td id="naglowek" rowspan="2">Vermieter</td>
                <td id="naglowek" rowspan="2">Bühnentyp</td>
                <td id="naglowek" rowspan="2">Arbeitshöhe</td>		
                <td id="naglowek" rowspan="2">Mietpreis</td>
                <td id="naglowek" rowspan="2">MaschinenNr.</td>
                <td id="naglowek" colspan="2">Mietzeitraum</td>
                <td id="naglowek" rowspan="2">auf Stand gesetzt bis zum</td>
                <td id="naglowek" rowspan="2">Standtage</td>
                <td id="naglowek" rowspan="2">Freimeldung</td>
                <td id="naglowek" rowspan="2">Bemerkungen</td>
                <td id="naglowek" rowspan="2">Ansprechpartner</td>
                <td id="naglowek" rowspan="2">Datum der Eintragung</td>
                <td id="naglowek" rowspan="2">LOGIN</td>	
                <td id="naglowek" colspan="4">Rechnung I</td>
                <td id="naglowek" colspan="2">Gutschrift</td>
                <td id="naglowek" rowspan="2">E</td>
                <td id="naglowek" rowspan="2">K</td>
                <td id="naglowek" rowspan="2">U</td>
            </tr>
            <tr>
                <td id="naglowek">vom</td>
                <td id="naglowek">bis zum</td>
                <td id="naglowek">Nr</td>
                <td id="naglowek">Abrechnungs Zeitraum</td>
                <td id="naglowek">Betrag</td>
                <td id="naglowek">Bemerkungen</td>
                <td id="naglowek">Nr</td>
                <td id="naglowek">Betrag</td>
                <td id="naglowek">Nr</td>
            </tr>';
    } else {
        echo '
            <tr>
                <td id="naglowek" rowspan="2">BUDOWA</td>
                <td id="naglowek" rowspan="2">FIRMA WYNAJMUJĄCA</td>
                <td id="naglowek" rowspan="2">RODZAJ ZWYŻKI</td>
                <td id="naglowek" rowspan="2">WYSOKOŚĆ</td>		
                <td id="naglowek" rowspan="2">CENA</td>
                <td id="naglowek" rowspan="2">NR MASZYNY</td>
                <td id="naglowek" colspan="2">DATA WYNAJMU</td>
                <td id="naglowek" rowspan="2">ZAWIESZONE DO</td>
                <td id="naglowek" rowspan="2">DATA ZAWIESZENIA</td>
                <td id="naglowek" rowspan="2">DATA ZDANIA</td>
                <td id="naglowek" rowspan="2">UWAGI</td>
                <td id="naglowek" rowspan="2">OSOBA KONTAKTOWA</td>
                <td id="naglowek" rowspan="2">DATA WPISU</td>
                <td id="naglowek" rowspan="2">LOGIN</td>
                <td id="naglowek" colspan="4">RACHUNKI I</td>
                <td id="naglowek" colspan="2">KOREKTY</td>					
                <td id="naglowek" rowspan="2">E</td>
                <td id="naglowek" rowspan="2">K</td>
                <td id="naglowek" rowspan="2">U</td>
            </tr>
            <tr>
                <td id="naglowek">OD</td>
                <td id="naglowek">DO</td>
                <td id="naglowek">NR</td>
                <td id="naglowek">OKR. ROZ.</td>
                <td id="naglowek">KWOTA</td>
                <td id="naglowek">UWAGI</td>
                <td id="naglowek">NR</td>
                <td id="naglowek">KWOTA</td>
                <td id="naglowek">NR</td>
            </tr>';
    }
    
    echo '<tr '.kolor_wiersza_zwyzki($row['data_zdania'], $row['zawieszone_do'], $row['zaznaczony_wiersz'], $row['oferta']).'>
        <td>'.nl2br($row['budowa']).'</td>
        <td>'.nl2br($row['firma_wynajmujaca']).'</td>
        <td>'.nl2br($row['rodzaj_zwyzki']).'</td>
        <td>'.nl2br($row['wysokosc']).'</td>			
        <td>'.nl2br($row['cena']).'</td>
        <td>'.nl2br($row['nr_maszyny']).'</td>
        <td>'.nl2br($row['data_wynajmu_od']).'</td>
        <td>'.nl2br($row['data_wynajmu_do']).'</td>
        <td>'.nl2br($row['zawieszone_do']).'</td>
        <td>'.nl2br($row['data_zawiesz']).'</td>
        <td>'.nl2br($row['data_zdania']).'</td>				
        <td>'.nl2br($row['uwagi']).'</td>
        <td>'.nl2br($row['os_kontaktowa']).'</td>
        <td>'.substr($row['creation_date'], 0, 10).'</td>
        <td>'.$row_login['login'].'</td>
        <td>'.nl2br($row['rach_1_nr']).'</td>
        <td>'.nl2br($row['rach_1_okres']).'</td>
        <td>'.nl2br($row['rach_1_kwota']).'</td>
        <td>'.nl2br($row['rach_1_uwagi']).'</td>
        <td>'.nl2br($row['korekta_nr']).'</td>
        <td>'.nl2br($row['korekta_kwota']).'</td>
        <td id="komorka_edycja"><a href="tabela_zwyzki_edytuj.php?id='.$row['id'].'"><button>E</button></a></td>
        <td id="komorka_kopiuj"><a href="tabela_zwyzki_kopiuj.php?id='.$row['id'].'"><button>K</button></a></td>
        <td id="komorka_usun"><a href="tabela_zwyzki_usun.php?id='.$row['id'].'"><button>X</button></a></td>
    </tr>
    </table>';
    if($row_header['header'] == 'n'){
        echo '<div id="success">Der Eintrag wurde geändert</div><br />';
    } else {
        echo '<div id="success">Wpis został edytowany.</div><br />';
    }

    if(isset($_SESSION['user_id'])){
        $stmt_header = $conn->prepare("SELECT * FROM panel_users WHERE id = ?");
        $stmt_header->bind_param("i", $_SESSION['user_id']);
    } elseif(isset($_COOKIE['ciastko_user_id'])){
        $stmt_header = $conn->prepare("SELECT * FROM panel_users WHERE id = ?");
        $stmt_header->bind_param("i", $_COOKIE['ciastko_user_id']);
    }
    $stmt_header->execute();
    $result_header = $stmt_header->get_result();
    $row_header = $result_header->fetch_assoc();
    
    if($row_header['header'] == 'n'){
        echo '<br /><a href="tabela_zwyzki.php'.$filters_path.'"><button>zurück</button></a>';
    } else {
        echo '<br /><a href="tabela_zwyzki.php'.$filters_path.'"><button>Powrót</button></a>';
    }
} else {
    require_once('logout.php');
}

require_once('footer.php');
?>
