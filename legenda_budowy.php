<?php
include('mysql_connecting.php'); // zakładam, że ten plik tworzy połączenie jako $conn

$user_id = null;

// Pobieranie ID użytkownika z sesji lub ciasteczka
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_COOKIE['ciastko_user_id'])) {
    $user_id = $_COOKIE['ciastko_user_id'];
}

$row_header = null;

// Bezpieczne wykonanie zapytania, jeśli user_id jest dostępne
if ($user_id !== null) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM panel_users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result_header = mysqli_stmt_get_result($stmt);
    $row_header = mysqli_fetch_array($result_header, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}

// Wyświetlanie elementów w zależności od wartości 'header'
if ($row_header && $row_header['header'] === 'n') {
    echo '
        <td><a href="tabela_budowy_nowe.php"><div id="zwyzki_legenda_zolty"></div></a></td><td><a href="tabela_budowy_nowe.php">-neu</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_zrealizowane.php"><div id="zwyzki_legenda_szary"></div></a></td><td><a href="tabela_budowy_zrealizowane.php">-abgewickelt</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_w_trakcie.php"><div id="zwyzki_legenda_zielony"></div></a></td><td><a href="tabela_budowy_w_trakcie.php">-läuft</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_zaznaczone.php"><div id="zwyzki_legenda_czerwony"></div></a></td><td><a href="tabela_budowy_zaznaczone.php">-markiert</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_tydzien_przed.php"><div id="zwyzki_legenda_pomaranczowy"></div></a></td><td><a href="tabela_budowy_tydzien_przed.php">-eine Woche vor der Ausführungsfrist</a></td><td width="20px"></td>
    ';
} else {
    echo '
        <td><a href="tabela_budowy_nowe.php"><div id="zwyzki_legenda_zolty"></div></a></td><td><a href="tabela_budowy_nowe.php">-NOWE</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_zrealizowane.php"><div id="zwyzki_legenda_szary"></div></a></td><td><a href="tabela_budowy_zrealizowane.php">-ZREALIZOWANE</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_w_trakcie.php"><div id="zwyzki_legenda_zielony"></div></a></td><td><a href="tabela_budowy_w_trakcie.php">-W TRAKCIE</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_zaznaczone.php"><div id="zwyzki_legenda_czerwony"></div></a></td><td><a href="tabela_budowy_zaznaczone.php">-ZAZNACZONE</a></td><td width="20px"></td>
        <td><a href="tabela_budowy_tydzien_przed.php"><div id="zwyzki_legenda_pomaranczowy"></div></a></td><td><a href="tabela_budowy_tydzien_przed.php">-TYDZIEŃ PRZED</a></td><td width="20px"></td>
    ';
}
?>
