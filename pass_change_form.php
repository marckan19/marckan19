<?php
ob_start();
session_start();
require_once('header.php');
require_once('db_config.php'); // Zakładając, że masz plik konfiguracyjny do połączenia z bazą

if (isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])) {
    if (isset($_POST['change_submit'])) {
        // Połączenie z bazą danych
        $pdo = getDbConnection();
        $user_id = $_SESSION['user_id'];
        
        // Przygotowanie zapytania
        $stmt = $pdo->prepare("SELECT * FROM panel_users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (isset($_POST['nowe_haslo']) && ($_POST['nowe_haslo'] == '' || $_POST['nowe_haslo'] == ' ')) {
                echo ($row['header'] == 'n') ? '<br />Das Passwort darf nicht leer sein!<br /><br />' : '<br />Hasło nie może być puste!<br /><br />';
                echo '
                    <form action="pass_change_form.php" method="post" id="zmiana_hasla">
                        Nowe hasło / neues Passwort: <br  /><br />
                        <input type="password" name="nowe_haslo"><br /><br />
                        <input type="submit"  value="Zmień" name="change_submit" />
                    </form>
                ';
            } elseif (isset($_POST['nowe_haslo']) && md5($_POST['nowe_haslo']) == $row['password']) {
                echo ($row['header'] == 'n') ? '<br />Das neue Passwort muss sich von dem vorherigen unterscheiden!<br /><br />' : '<br />Nowe hasło musi być inne niż poprzednie!<br /><br />';
                echo '
                    <form action="pass_change_form.php" method="post" id="zmiana_hasla">
                        Nowe hasło / neues Passwort: <br  /><br />
                        <input type="password" name="nowe_haslo"><br /><br />
                        <input type="submit"  value="Zmień" name="change_submit" />
                    </form>
                ';
            } else {
                // Zaktualizowanie hasła
                $new_password = md5($_POST['nowe_haslo']);
                $update = $pdo->prepare("UPDATE panel_users SET password = :password, pass_changed = 1 WHERE id = :id");
                $update->execute(['password' => $new_password, 'id' => $user_id]);

                echo 'Hasło zostało zmienione. Możesz zalogować się do systemu.<br />Das Passwort wurde geändert. Sie können sich beim System anmelden.<br /><br />';
                echo '<a href="logout.php"><button>Login</button></a>';
            }
        }
    } else {
        echo '<br />Zmiana hasła.<br /><br />';
        echo '
            <form action="pass_change_form.php" method="post" id="zmiana_hasla">
                Nowe hasło / neues Passwort: <br  /><br />
                <input type="password" name="nowe_haslo"><br /><br />
                <input type="submit"  value="Zmień" name="change_submit" />
            </form>
        ';
    }
} else {
    require_once('logout.php');
}

require_once('footer.php');