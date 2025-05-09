<?php
echo '
<form action="index.php" method="post" id="logowanie">
    <table>
        <tr>
            <td id="logowanie_tekst">Login: </td>
            <td><input type="text" name="login" id="logowanie_pole" required></td>
            <td id="logowanie_tekst" rowspan="2">
                <input type="submit" value="Zaloguj" name="post_login" id="logowanie_button" />
            </td>
        </tr>
        <tr>
            <td id="logowanie_tekst">Hasło/<br />Passwort: </td>
            <td><input type="password" name="pass" id="logowanie_pole" required></td>
        </tr>
    </table>
    <br />
</form>
';
?>