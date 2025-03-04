<?php
include('funkcje.php');
otworz_polaczenie();

$id = $_GET['id'];
$query = "SELECT * FROM faktura WHERE id = $id";
$result = mysqli_query($polaczenie, $query);
$faktura = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    edit_invoice();
}
zamknij_polaczenie();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj Fakturę</title>
    <link rel="stylesheet" href="css/style_main.css">
</head>
<body><center>
    <table style="width: 70%;">
        <th colspan="2">  <h1>Edytuj Fakturę</h1></th>
   
    <form method="POST" action="">
    <input type="hidden" name="edit_invoice">
    <tr><td><label>Numer Faktury: </label></td>
    <td><input type="text" name="numerFaktury" value="<?php echo $faktura['numerFaktury']; ?>" required><br></td></tr>
    <tr><td><label>Data Wystawienia: </label></td>
    <td><input type="date" name="dataWystawienia" value="<?php echo $faktura['dataWystawienia']; ?>" required><br></td></tr>
    <tr><td><label>Termin Płatności: </label></td>
    <td><input type="date" name="terminPlatnosci" value="<?php echo $faktura['terminPlatnosci']; ?>" required><br></td></tr>
    <tr><td> <label>Status: </label></td>
    <td><select name="statusFaktury" required>
            <option value="Opłacona" <?php echo $faktura['statusFaktury'] == 'Opłacona' ? 'selected' : ''; ?>>Opłacona</option>
            <option value="Nieopłacona" <?php echo $faktura['statusFaktury'] == 'Nieopłacona' ? 'selected' : ''; ?>>Nieopłacona</option>
            <option value="Zaplanowana" <?php echo $faktura['statusFaktury'] == 'Zaplanowana' ? 'selected' : ''; ?>>Zaplanowana</option>
        </select><br></td></tr>
        <tr><td><label>Kwota Netto: </label></td>
        <td><input type="number" step="0.01" name="kwotaNetto" value="<?php echo $faktura['kwotaNetto']; ?>" required><br></td></tr>
       <tr><td><label>Kwota VAT: </label></td>
        <td><input type="number" step="0.01" name="kwotaVat" value="<?php echo $faktura['kwotaVat']; ?>" required><br></td></tr>
        <tr><td><label>Kwota Brutto: </label></td>
    <td><input type="number" step="0.01" name="kwotaBrutto" value="<?php echo $faktura['kwotaBrutto']; ?>" required><br></td></tr>
        <tr><td colspan="2"><button class="btn" type="submit" style="margin: 20px;">Zapisz Zmiany</button>
        <button class="btn" onClick="window.location='faktury.php'"> Powrót do Listy Faktur </button></td></tr>
        
        
        
    </form>
    </table>
    </center>
</body>
</html>