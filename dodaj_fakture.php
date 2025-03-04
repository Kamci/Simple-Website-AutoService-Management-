<?php

include('funkcje.php');
otworz_polaczenie();
$error = ''; // Ogólny błąd
$errors = []; // Błędy dla poszczególnych pól
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
     $numerFaktury = $_POST['numerFaktury'];
     $dataWystawienia = $_POST['dataWystawienia'];
     $terminPlatnosci = $_POST['terminPlatnosci'];
     $statusFaktury = $_POST['statusFaktury'];
 
     // Ustawienie cookies przed jakimkolwiek wyjściem
     setcookie('ostatni_numerFaktury', $numerFaktury, time() + 86400, '/'); // 1 dzien 
     setcookie('ostatni_status', $statusFaktury, time() + 86400, '/');
     setcookie('ostatni_termin', $terminPlatnosci, time() + 86400, '/');
     setcookie('ostatnia_data_wystawienia', $dataWystawienia, time() + 86400, '/');

     $errors = add_invoice($numerFaktury, $dataWystawienia, $terminPlatnosci, $statusFaktury, $_POST['kwotaNetto'], $_POST['kwotaVat'], $_POST['kwotaBrutto']);

     if (empty($errors)) {
        header('Location: faktury.php');
     }


}
zamknij_polaczenie();
// step oznacza z jaka dokladonoscia mozna ustawic numer po przecinku 
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Fakturę</title>
    <link rel="stylesheet" href="css/style_main.css">
</head>
<body>
    <center>
    <h1>Dodaj Nową Fakturę</h1>
    <form method="POST" action="">
    <input type="hidden" name="add_invoice">
    <table style="width: 70%;">
        <tr>
        <td> 
            <label>Numer Faktury: </label>
        </td>
        <td>   
            <input type="text" name="numerFaktury" 
                value="<?php echo $_POST['numerFaktury'] ?? ''; ?>" 
                class="<?php echo isset($errors['numerFaktury']) ? 'error' : ''; ?>" required>
                <?php if (isset($errors['numerFaktury'])): ?>
                <span class="error-message"><?php echo $errors['numerFaktury']; ?></span>
                <?php endif; ?>
        </td>
        </tr>
        <tr> 
            <td> 
                <label>Data Wystawienia: </label>

            </td>
            <td>
            <input type="date" name="dataWystawienia" 
            value="<?php echo $_POST['dataWystawienia'] ?? ''; ?>" 
            class="<?php echo isset($errors['dataWystawienia']) ? 'error' : ''; ?>" required>
            <?php if (isset($errors['dataWystawienia'])): ?>
            <span class="error-message"><?php echo $errors['dataWystawienia']; ?></span>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
            <label>Termin Płatności: </label>
            </td>
            <td>
            <input type="date" name="terminPlatnosci"  
                    value="<?php echo $_POST['terminPlatnosci'] ?? ''; ?>" 
                    class="<?php echo isset($errors['terminPlatnosci']) ? 'error' : ''; ?>" required>
                    <?php if (isset($errors['terminPlatnosci'])): ?>
                    <span class="error-message"><?php echo $errors['terminPlatnosci']; ?></span>
                <?php endif; ?>
            </td>
        </tr>   
        <tr>
            <td>
                <label>Status: </label>
            </td>
            <td >
             <select name="statusFaktury" required>
            <option value="">-- Wybierz Status --</option>
            <option value="Opłacona" <?php echo (isset($_COOKIE['ostatni_status']) && $_COOKIE['ostatni_status'] == 'Opłacona') ? 'selected' : ''; ?>>Opłacona</option>
            <option value="Nieopłacona" <?php echo (isset($_COOKIE['ostatni_status']) && $_COOKIE['ostatni_status'] == 'Nieopłacona') ? 'selected' : ''; ?>>Nieopłacona</option>
            <option value="Zaplanowana" <?php echo (isset($_COOKIE['ostatni_status']) && $_COOKIE['ostatni_status'] == 'Zaplanowana') ? 'selected' : ''; ?>>Zaplanowana</option>
            </select>
            </td>
        </tr>
        <tr>
            <td>
        <label>Kwota Netto: </label>
            </td>
            <td>
        <input type="number" step="0.01" name="kwotaNetto" 
                value="<?php echo $_POST['kwotaNetto'] ?? ''; ?>" 
                class="<?php echo isset($errors['kwotaNetto']) ? 'error' : ''; ?>" required>
                <?php if (isset($errors['kwotaNetto'])): ?>
                <span class="error-message"><?php echo $errors['kwotaNetto']; ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
        <label>Kwota VAT: </label>
            </td>
            <td>
            <input type="number" step="0.01" name="kwotaVat" 
                    value="<?php echo $_POST['kwotaVat'] ?? ''; ?>" 
                    class="<?php echo isset($errors['kwotaVat']) ? 'error' : ''; ?>" required>
                    <?php if (isset($errors['kwotaVat'])): ?>
                    <span class="error-message"><?php echo $errors['kwotaVat']; ?></span>
                    <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
        <label>Kwota Brutto: </label>
            </td>
            <td>
            <input type="number" step="0.01" name="kwotaBrutto" 
                    value="<?php echo $_POST['kwotaBrutto'] ?? ''; ?>" 
                    class="<?php echo isset($errors['kwotaBrutto']) ? 'error' : ''; ?>" required>
                    <?php if (isset($errors['kwotaBrutto'])): ?>
                    <span class="error-message"><?php echo $errors['kwotaBrutto']; ?></span>
                    <?php endif; ?>
            </td>
        </tr>
    </table>
   
        <button style="margin: 20px;" class="btn" type="submit">Dodaj Fakturę</button>
        <button class="btn" onClick="window.location='faktury.php'"> Powrót do Listy Faktur </button>
    </form>
    </center>
</body>
</html>

