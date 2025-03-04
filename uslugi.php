<?php
// Połączenie z bazą danych
include('funkcje.php');
otworz_polaczenie();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        add_service();
    }

    if (isset($_POST['edit_service'])) {
        edit_service();
    }
}

if (isset($_GET['delete'])) {
    delete_service();
}
$filtr = $_POST['filtr'] ?? '';
if (!empty($filtr)) {
    $query = "
        SELECT * 
        FROM usluga 
        WHERE nazwa LIKE '%$filtr%' 
           OR opis LIKE '%$filtr%'
           OR cena LIKE '%$filtr%'
    ";
} else {
    $query = "SELECT * FROM usluga";
}
// Pobieranie danych z bazy danych

$result = mysqli_query($polaczenie, $query);


// Pobieranie danych klienta 
$usluga = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $usluga = get_dane_uslugi($id);
}
// Zakończenie połączenia z bazą
zamknij_polaczenie();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_main.css">
    <title>Uslugi</title>
</head>
<body>
    <center>
        <h1>Lista Usług</h1>
        <br>
        <br>
        <form method="POST">
        <i>Pokaż tylko usługi zawierające frazę:</i>
        <input class="input_filtr" type="text" name="filtr" value="<?php echo $filtr; ?>">
        <button class="btn" type="submit" >Filtruj </button>
        </form>
    <br>
        <table>
            <tr>
                <th>Id</th>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Cena</th>
                <th>Opcje</th>
            </tr>
            
            <?php
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['nazwa']."</td>
                    <td>".$row['opis']."</td>
                    <td>".$row['cena']."</td>
                    <td>
                        <a href='uslugi.php?edit=".$row['id']."'>Edytuj</a> | 
                        <a href='uslugi.php?delete=".$row['id']."'>Usuń</a>
                    </td>
                </tr>";
            }
            ?>
        </table>

        <?php if ($usluga): // dwukropek rozpoczyna blok ?> 
            <table style="width: 50%;">
                <th colspan="2"> <h>Edytuj usługę</h></th>
           
            <form method="POST" action="uslugi.php">
                <input type="hidden" name="id" value="<?php echo $usluga['id']; ?>">
                <input type="hidden" name="edit_service">
                <tr><td><label>Nazwa: </label></td>
                <td><input type="text" name="nazwa" value="<?php echo $usluga['nazwa']; ?>" required><br></td></tr>
                <tr><td><label>Opis: </label></td>
                <td> <textarea name="opis" rows="5" wrap="soft" required><?php echo $usluga['opis']; ?></textarea><br></td></tr>
                <tr><td><label>Cena: </label></td><td><input type="text" name="cena" value="<?php echo $usluga['cena']; ?>"><br></td></tr>
                <tr><td><button class="btn" type="submit" >Zapisz Zmiany</button></td>
                <td><button class="btn" onClick="window.location='uslugi.php'">Anuluj</button></td></tr>
                
            </form>
            <br>
        
        </table>
            <?php
        else:  // dwukropek rozpoczyna blok 
          
        ?>
        <table style="width: 50%;">
            <th colspan="2"><h>Dodaj Nową usługę</h></th>
         
        <form method="POST" action="uslugi.php">
            <input type="hidden" name="add_service">
            <tr><td> <label>Nazwa: </label></td>
            <td><input type="text" name="nazwa" required><br></td></tr>
            <tr><td><label>Opis: </label></td><td><textarea name="opis" rows="5" wrap="soft" required> </textarea><br></td></tr>
            <tr><td><label>Cena: </label></td>
            <td> <input type="text" name="cena" required><br></td></tr>
           <tr><td colspan="2"> <button class="btn" type="submit" >Dodaj Usługę</button></td></tr>
           
        </form>
        </table>
        <?php
        endif; // endif konczy blok if 
        ?>

        <br><br>
        <button class="btn"  onClick="window.location='strona_glowna.html'"> Powrót do menu</button>
    </center>
</body>
</html>