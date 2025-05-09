<?php
ob_start();
session_start();
require_once('header.php');

header('refresh: 7;');

// Za³aduj plik z po³¹czeniem do bazy danych
require_once('mysql_connecting.php');

if (isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])) {
    require_once('menu.php');
    
    echo '
    <table>
        <tr>';
        if ($row_header['header'] == 'n') {
            echo '<td><h2>Benachrichtigungen</h2></td><td width="20px" />';
        } else {
            echo '<td><h2>Powiadomienia</h2></td><td width="20px" />';
        }            
    echo '</tr>
    </table>';

    // Po³¹czenie z baz¹ danych przez funkcjê z mysql_connecting.php
    $pdo = getDbConnection(); // Funkcja z mysql_connecting.php do po³¹czenia z baz¹ danych

    // Zapytanie o powiadomienia
    $query = "
        SELECT ptz.*, pu.login as creator, pu2.login as modificator
        FROM panel_tabela_zwyzki ptz
        JOIN panel_users pu ON pu.id = ptz.creator_id
        JOIN panel_users pu2 ON pu2.id = ptz.modificator_id
        WHERE modification_date >= NOW() - INTERVAL 48 HOUR
        ORDER BY SUBSTRING(modification_date, 1, 10) DESC, SUBSTRING(modification_date, 11) DESC
    ";

    // U¿ycie PDO w celu wykonania zapytania
    $stmt = $pdo->query($query);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sprawdzanie sesji lub ciasteczka dla user_id
    if (isset($_SESSION['user_id'])) {
        $query_header = "SELECT * FROM panel_users WHERE id = :user_id";
        $stmt_header = $pdo->prepare($query_header);
        $stmt_header->execute(['user_id' => $_SESSION['user_id']]);
    } elseif (isset($_COOKIE['ciastko_user_id'])) {
        $query_header = "SELECT * FROM panel_users WHERE id = :user_id";
        $stmt_header = $pdo->prepare($query_header);
        $stmt_header->execute(['user_id' => $_COOKIE['ciastko_user_id']]);
    }

    $row_header = $stmt_header->fetch(PDO::FETCH_ASSOC);

    echo '</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

    if ($row_header['id'] != 16) { // Nie widzi user PDG
        require_once('menu_powiadomienia.php');
    }

    echo '<div>';

} else {
    require_once('logout.php');
}

require_once('footer.php');
?>
