<?php
include('funkcje.php');
otworz_polaczenie();
if (isset($_GET['delete'])) {
    delete_invoice();
}
// Pobranie faktur z bazy danych
$filtr = $_POST['filtr'] ?? '';
if (!empty($filtr)) {
    $query = "
        SELECT * 
        FROM faktura 
        WHERE numerFaktury LIKE '%$filtr%' 
           OR dataWystawienia LIKE '%$filtr%'
           OR terminPlatnosci LIKE '%$filtr%'
           OR statusFaktury LIKE '%$filtr%'
           OR kwotaNetto LIKE '%$filtr%'
           OR kwotaVat LIKE '%$filtr%'
           OR kwotaBrutto LIKE '%$filtr%'
    ";
} else {
    $query = "SELECT * FROM faktura";
}
$result = mysqli_query($polaczenie, $query);



zamknij_polaczenie();

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista Faktur</title>
    <link rel="stylesheet" href="css/style_main.css">
   <!--  <style>
       
    </style> -->
</head>
<body>
    <center>
    <h1>LISTA FAKTUR</h1>
   
    <br>
    <br>
    <form method="POST">
        <i>Pokaż tylko faktury zawierające frazę:</i>
        <input class="input_filtr" type="text" name="filtr" value="<?php echo $filtr; ?>">
        <button class="btn" type="submit" >Filtruj </button>
    </form>
    <br>
    <table>
        <tr>
            <th>ID</th>
            <th>Numer Faktury</th>
            <th>Data Wystawienia</th>
            <th>Termin Płatności</th>
            <th>Status</th>
            <th>Kwota Netto</th>
            <th>Kwota VAT</th>
            <th>Kwota Brutto</th>
            <th>Opcje</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['numerFaktury']; ?></td>
            <td><?php echo $row['dataWystawienia']; ?></td>
            <td><?php echo $row['terminPlatnosci']; ?></td>
            <td><?php echo $row['statusFaktury']; ?></td>
            <td><?php echo number_format($row['kwotaNetto'], 2); ?></td>
            <td><?php echo number_format($row['kwotaVat'], 2); ?></td>
            <td><?php echo number_format($row['kwotaBrutto'], 2); ?></td>
            <td>
                <a href="edytuj_fakture.php?id=<?php echo $row['id']; ?>">Edytuj</a> |
                <a href="faktury.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Czy na pewno usunąć fakturę?')">Usuń</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
  
    <br>
    <button class="btn" style="margin: 20px;" onclick="window.location.href='dodaj_fakture.php'">Dodaj Fakturę</button>
    
    <button class="btn" onClick="window.location='strona_glowna.html'"> Powrót do menu </button>
        
        </center>
</body>
</html>