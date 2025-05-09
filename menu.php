<?php
session_start();
require_once('mysql_connecting.php'); // Plik, który zawiera funkcję getDbConnection()

// Uzyskaj połączenie PDO
$pdo = getDbConnection();

// Ustal ID użytkownika z sesji lub ciasteczek
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} elseif (isset($_COOKIE['ciastko_user_id'])) {
    $userId = $_COOKIE['ciastko_user_id'];
} else {
    // Jeśli nie znaleziono ID użytkownika, przekieruj lub wyświetl błąd
    die("Brak danych użytkownika.");
}

// Przygotuj zapytanie do pobrania danych o użytkowniku
$stmt = $pdo->prepare("SELECT * FROM panel_users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $userId]);
$row_header = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div id="menu">
    <?php
    if ($row_header['header'] == 'n') {
        echo 'eingeloggt als: <b>' . htmlspecialchars($row_header['login']) . '</b><br /><br />';
        echo '<a href="tabela_zwyzki.php"><button>Arbeitsbühnen</button></a> ';
        echo '<a href="tabela_noclegi.php"><button>Unterkunft</button></a> ';
        echo '<a href="tabela_budowy.php"><button>Bauvorhaben</button></a> ';
        echo '<a href="tabela_wyjazdy.php"><button>Baustellen</button></a> ';
        echo '<a href="tabela_oferty.php"><button>Angebote</button></a> ';
        echo '<a href="tabela_usterki.php"><button>Mängel</button></a> ';
        echo '<a href="tabela_konserwacje.php"><button>Wartungsverträge</button></a> ';
        echo '<a href="logout.php"><button>ausloggen</button></a> ';
        echo '<a href="pass_change_form.php"><button>Benachrichtigungen</button></a> ';
        echo '<a href="powiadomienia.php"><button>Benachrichtigungen</button></a> ';
        echo '<a href="tabela_punkty.php"><button>Festpunkte</button></a> ';
    } else {
        echo 'Zalogowany jako: <b>' . htmlspecialchars($row_header['login']) . '</b><br /><br />';
        echo '<a href="tabela_zwyzki.php"><button>Zwyżki</button></a> ';
        echo '<a href="tabela_noclegi.php"><button>Noclegi</button></a> ';
        if ($row_header['id'] != 16) {
            echo '<a href="tabela_budowy.php"><button>Budowy</button></a> ';
        }
        echo '<a href="tabela_wyjazdy.php"><button>Wyjazdy</button></a> ';
        if ($row_header['id'] != 16) {
            echo '<a href="tabela_oferty.php"><button>Oferty</button></a> ';
        }
        if ($row_header['id'] != 16) {
            echo '<a href="tabela_usterki.php"><button>Usterki</button></a> ';
        }
        if ($row_header['id'] != 16) {
            echo '<a href="tabela_konserwacje.php"><button>Konserwacje</button></a> ';
        }
        if ($row_header['id'] != 16) {
            echo '<a href="powiadomienia.php"><button>Powiadomienia</button></a> ';
        }
        if ($row_header['id'] != 16) {
            echo '<a href="tabela_punkty.php"><button>Punkty stałe</button></a> ';
        }
        echo '<a href="pass_change_form.php"><button>Zmień hasło</button></a> ';
        echo '<a href="ticket.php"><button>TICKET</button></a> ';
        echo '<a href="logout.php"><button>Wyloguj</button></a> ';
    }
    
    if ($row_header['id'] != 16) {
        echo '<a href="ftp://budowy:brassco123@brassco.home.pl" target="_blank"><button>FTP</button></a>';
    }
    ?>
</div>
<div style="float:right; margin-top:10px">
    <img src="brassco_logo.jpg" width="200px" />
</div>