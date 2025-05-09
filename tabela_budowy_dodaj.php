<?php
session_start();
require_once('mysql_connecting.php');

// Po³¹czenie z baz¹ danych PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    // Ustawienie trybu b³êdów na wyj¹tki
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

if(isset($_POST['dodaj'])){
    // Zbieranie danych z formularza
    $zleceniodawca = $_POST['zleceniodawca'];
    $wys_hali = $_POST['wys_hali'];
    $realizacja_od = $_POST['realizacja_od'];
    $realizacja_do = $_POST['realizacja_do'];
    $kierownik = $_POST['kierownik'];
    $dokumenty = $_POST['dokumenty'];
    $tydzien = $_POST['tydzien'];
    $zgloszeni = $_POST['zgloszeni'];
    $tekst = $_POST['tekst'];

    $rewizja = $_POST['rewizja'];
    $stan = $_POST['stan'];
    $uwagi = $_POST['uwagi'];
    $suplementy = $_POST['suplementy'];
    $adnotacje = $_POST['adnotacje'];
    $creation_date = date('Y-m-d G:i:s');

    if(isset($_SESSION['user_id'])){
        $creator_id = $_SESSION['user_id'];
    }else{
        $creator_id = $_COOKIE['ciastko_user_id'];
    }

    if($_POST['zaznaczony_wiersz'] == 1){
        $zaznaczony_wiersz = 1;
    }else{
        $zaznaczony_wiersz = 0;
    }

    $kraj = $_POST['kraj'];

    // Przygotowane zapytanie PDO
    $query_insert = "INSERT INTO panel_tabela_budowy (zleceniodawca, wys_hali, realizacja_od, realizacja_do, kierownik, dokumenty, tydzien, rewizja, stan, creator_id, creation_date, modificator_id, modification_date, zaznaczony_wiersz, uwagi, suplementy, adnotacje, kraj)
                     VALUES (:zleceniodawca, :wys_hali, :realizacja_od, :realizacja_do, :kierownik, :dokumenty, :tydzien, :rewizja, :stan, :creator_id, :creation_date, :modificator_id, :modification_date, :zaznaczony_wiersz, :uwagi, :suplementy, :adnotacje, :kraj)";

    $stmt = $pdo->prepare($query_insert);

    // Bindowanie parametrów
    $stmt->bindParam(':zleceniodawca', $zleceniodawca);
    $stmt->bindParam(':wys_hali', $wys_hali);
    $stmt->bindParam(':realizacja_od', $realizacja_od);
    $stmt->bindParam(':realizacja_do', $realizacja_do);
    $stmt->bindParam(':kierownik', $kierownik);
    $stmt->bindParam(':dokumenty', $dokumenty);
    $stmt->bindParam(':tydzien', $tydzien);
    $stmt->bindParam(':rewizja', $rewizja);
    $stmt->bindParam(':stan', $stan);
    $stmt->bindParam(':creator_id', $creator_id);
    $stmt->bindParam(':creation_date', $creation_date);
    $stmt->bindParam(':modificator_id', $creator_id);
    $stmt->bindParam(':modification_date', $creation_date);
    $stmt->bindParam(':zaznaczony_wiersz', $zaznaczony_wiersz);
    $stmt->bindParam(':uwagi', $uwagi);
    $stmt->bindParam(':suplementy', $suplementy);
    $stmt->bindParam(':adnotacje', $adnotacje);
    $stmt->bindParam(':kraj', $kraj);

    // Wykonanie zapytania
    $stmt->execute();

    // Przekierowanie po dodaniu
    header('Location: tabela_budowy_dodano.php?id=' . $pdo->lastInsertId() . $filters_path);
}

if(isset($_SESSION['user_id'])){
    $query_header = "SELECT * FROM panel_users WHERE id = :user_id";
    $stmt = $pdo->prepare($query_header);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
}elseif(isset($_COOKIE['ciastko_user_id'])){
    $query_header = "SELECT * FROM panel_users WHERE id = :user_id";
    $stmt = $pdo->prepare($query_header);
    $stmt->bindParam(':user_id', $_COOKIE['ciastko_user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $row_header = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Wyœwietlanie tabeli
$query_select = "SELECT * FROM panel_tabela_budowy WHERE 1";
$stmt = $pdo->prepare($query_select);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Tabela Budowy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Panel Tabela Budowy</h1>
    
    <form action="" method="POST">
        <label for="zleceniodawca">Zleceniodawca:</label>
        <input type="text" name="zleceniodawca" id="zleceniodawca" required><br><br>

        <label for="wys_hali">Wysokoœæ hali:</label>
        <input type="text" name="wys_hali" id="wys_hali" required><br><br>

        <label for="realizacja_od">Realizacja od:</label>
        <input type="date" name="realizacja_od" id="realizacja_od" required><br><br>

        <label for="realizacja_do">Realizacja do:</label>
        <input type="date" name="realizacja_do" id="realizacja_do" required><br><br>

        <label for="kierownik">Kierownik:</label>
        <input type="text" name="kierownik" id="kierownik" required><br><br>

        <label for="dokumenty">Dokumenty:</label>
        <input type="text" name="dokumenty" id="dokumenty"><br><br>

        <label for="tydzien">Tydzieñ:</label>
        <input type="text" name="tydzien" id="tydzien"><br><br>

        <label for="zgloszeni">Zg³oszeni:</label>
        <input type="text" name="zgloszeni" id="zgloszeni"><br><br>

        <label for="tekst">Tekst:</label>
        <textarea name="tekst" id="tekst"></textarea><br><br>

        <label for="rewizja">Rewizja:</label>
        <input type="text" name="rewizja" id="rewizja"><br><br>

        <label for="stan">Stan:</label>
        <input type="text" name="stan" id="stan"><br><br>

        <label for="uwagi">Uwagi:</label>
        <textarea name="uwagi" id="uwagi"></textarea><br><br>

        <label for="suplementy">Suplementy:</label>
        <input type="text" name="suplementy" id="suplementy"><br><br>

        <label for="adnotacje">Adnotacje:</label>
        <textarea name="adnotacje" id="adnotacje"></textarea><br><br>

        <label for="kraj">Kraj:</label>
        <input type="text" name="kraj" id="kraj"><br><br>

        <input type="submit" name="dodaj" value="Dodaj">
    </form>

    <h2>Lista Budowy</h2>
    <table>
        <tr>
            <th>Zleceniodawca</th>
            <th>Wysokoœæ Hali</th>
            <th>Realizacja Od</th>
            <th>Realizacja Do</th>
            <th>Kierownik</th>
            <th>Dokumenty</th>
        </tr>
        <?php foreach($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['zleceniodawca']); ?></td>
                <td><?php echo htmlspecialchars($row['wys_hali']); ?></td>
                <td><?php echo htmlspecialchars($row['realizacja_od']); ?></td>
                <td><?php echo htmlspecialchars($row['realizacja_do']); ?></td>
                <td><?php echo htmlspecialchars($row['kierownik']); ?></td>
                <td><?php echo htmlspecialchars($row['dokumenty']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>