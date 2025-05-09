<?php
ob_start();
session_start();
require_once('header.php');
require_once('mysql_connecting.php'); // Załaduj połączenie z bazą

// Połączenie z bazą danych
$pdo = getDbConnection();

if (isset($_POST['post_login'])) {		
    $login = trim($_POST['login']);
    $pass = trim($_POST['pass']);
    
    // Walidacja pustych pól
    if ($login == '' || $pass == '') {
        echo '<br /><br /><br /><br /><br /><br /><center><img src="brassco_logo.jpg" /></center>';
        echo '<center>';
        require_once('login_form.php');
        echo 'Podaj login i hasło! (Geben Sie bitte Login und Passwort ein!)';
        echo '</center>';
    } else {
        // Przygotowanie zapytania z zabezpieczeniem przed SQL injection
        $query = "SELECT * FROM panel_users WHERE login = :login AND password = :password";
        
        // Przygotowanie zapytania PDO
        $stmt = $pdo->prepare($query);
        
        // Bindowanie parametrów
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':password', md5($pass), PDO::PARAM_STR);
        
        // Wykonanie zapytania
        $stmt->execute();
        
        // Sprawdzenie, czy są wyniki
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $_SESSION['zalogowany'] = true;
            $_SESSION['user_login'] = $login;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['pass_changed'] = $row['pass_changed'];
            
            // Wymuszenie zmiany hasła
            if ($row['pass_changed'] == 0) {
                header('Location: pass_change_form.php');
                exit;
            }
            
            // Ustawienie ciasteczek
            setcookie('ciastko_zalogowany', $row['user_type'], time() + 3600 * 24);
            setcookie('ciastko_user_login', $login, time() + 3600 * 24);
            setcookie('ciastko_user_id', $row['id'], time() + 3600 * 24);
            
            // Załadowanie menu
            require_once('menu.php');
        } else {
            echo '<br /><br /><br /><br /><br /><br /><center><img src="brassco_logo.jpg" /></center>';
            echo '<center>';
            require_once('login_form.php');
            echo 'Błędny login lub hasło! (Login oder Passwort ist falsch!)';
            echo '</center>';
        }
    }
} else {
    echo '<br /><br /><br /><br /><br /><br /><center><img src="brassco_logo.jpg" /></center>';
    echo '<center>';
    require_once('login_form.php');
    echo '</center>';
}

echo '</div>';

require_once('footer.php');
?>
