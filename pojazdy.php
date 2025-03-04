<?php
// Połączenie z bazą danych
include('funkcje.php');
otworz_polaczenie();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_vehicle'])) {
        add_vehicle();
    }

    if (isset($_POST['edit_vehicle'])) {
        edit_vehicle();
    }
}

if (isset($_GET['delete'])) {
    delete_vehicle();
}
//pobieranie listy klientow
$query_klienci = "SELECT id, imie, nazwisko FROM klient";
$dane_klientow = mysqli_query($polaczenie, $query_klienci);

$filtr = $_POST['filtr'] ?? '';
if (!empty($filtr)) {
    $query = "SELECT pojazd.id, klient.imie, klient.nazwisko, pojazd.marka, pojazd.model, pojazd.rok_produkcji, 
               pojazd.vin, pojazd.pojemnosc_silnika, pojazd.rodz_paliwa 
        FROM pojazd 
        JOIN klient ON pojazd.idKlient = klient.id
        WHERE klient.imie LIKE '%$filtr%' 
           OR klient.nazwisko LIKE '%$filtr%'
           OR pojazd.marka LIKE '%$filtr%'
           OR pojazd.model LIKE '%$filtr%'
           OR pojazd.rok_produkcji LIKE '%$filtr%'
           OR pojazd.vin LIKE '%$filtr%'
           OR pojazd.pojemnosc_silnika LIKE '%$filtr%'
           OR pojazd.rodz_paliwa LIKE '%$filtr%'
    ";
} else {
    $query = "SELECT pojazd.id, klient.imie, klient.nazwisko, pojazd.marka, pojazd.model, pojazd.rok_produkcji, 
    pojazd.vin, pojazd.pojemnosc_silnika, pojazd.rodz_paliwa 
    FROM pojazd 
    JOIN klient ON pojazd.idKlient = klient.id";
}
// Pobieranie danych z bazy danych

$result = mysqli_query($polaczenie, $query);

$pojazd = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $pojazd = get_dane_pojazdu($id);
}

// Zakończenie połączenia z bazą
zamknij_polaczenie();

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pojazdy</title>
    <link rel="stylesheet" href="css/style_main.css">
  
</head>
<body >
    <center>
        <h1>Lista Pojazdów</h1>
        <br>
        <br>
        <form method="POST">
        <i>Pokaż tylko pojazdy zawierające frazę:</i>
        <input class="input_filtr" type="text" name="filtr" value="<?php echo $filtr; ?>">
        <button class="btn" type="submit" >Filtruj </button>
        </form>
        <br>
        <table>
            <tr>
                <th>Klient</th>
                <th>Marka</th>
                <th>Model</th>
                <th>Rok Produkcji</th>
                <th>VIN</th>
                <th>Pojemność Silnika</th>
                <th>Rodzaj Paliwa</th>
                <th>Opcje</th>
            </tr>
            
            <?php
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row['imie']." ".$row['nazwisko']."</td>
                    <td>".$row['marka']."</td>
                    <td>".$row['model']."</td>
                    <td>".$row['rok_produkcji']."</td>
                    <td>".$row['vin']."</td>
                    <td>".$row['pojemnosc_silnika']."</td>
                    <td>".$row['rodz_paliwa']."</td>
                    <td>
                        <a href='pojazdy.php?edit=".$row['id']."'>Edytuj</a> | 
                        <a href='pojazdy.php?delete=".$row['id']."'>Usuń</a>
                    </td>
                </tr>";
            }
            ?>
        </table>

       
        <?php if ($pojazd): // dwukropek rozpoczyna blok ?> 
            <table style="width: 400px;">
            <th colspan="2"> <h>Edytuj Pojazd</h></th>
            <form method="POST" action="pojazdy.php">
                <input type="hidden" name="id" value="<?php echo $pojazd['id']; ?>">
                <input type="hidden" name="edit_vehicle">
                <tr><td><label>Klient: </label></td>
                <td><select style="padding: 5px;" name="idKlient" required>
                    <option value="">-- Wybierz Klienta --</option>
                    <?php
                    $wybrany_klient = $pojazd['idKlient'];
                    select_klienta($dane_klientow, $wybrany_klient);
                    ?>
                </select><br></td></tr>
                <tr><td><label>Marka: </label></td>
                <td><input type="text" name="marka" value="<?php echo $pojazd['marka']; ?>" required><br></td></tr>
                <tr><td><label>Model: </label></td><td><input type="text" name="model" value="<?php echo $pojazd['model']; ?>" required><br></td></tr>
                <tr><td><label>Rok Produkcji: </label></td>
                <td><input type="number" name="rok_produkcji" value="<?php echo $pojazd['rok_produkcji']; ?>"><br></td></tr>
                <tr><td><label>VIN: </label></td><td><input type="text" name="vin" value="<?php echo $pojazd['vin']; ?>"><br></td></tr>
                <tr><td> <label>Pojemność Silnika: </label></td>
                <td><input type="number" name="pojemnosc_silnika" value="<?php echo $pojazd['pojemnosc_silnika']; ?>"><br></td></tr>
                <tr><td><label>Rodzaj Paliwa: </label></td>
                <td> <input type="text" name="rodz_paliwa" value="<?php echo $pojazd['rodz_paliwa']; ?>"><br></td></tr>
                <tr><td><button class="btn" type="submit" >Zapisz Zmiany</button></td>
                <td><button class="btn" onClick="window.location='pojazdy.php'">Anuluj</button></td></tr>
            </form>
            </table>
            <?php
        else:
        ?>
        <table style="width: 400px;">
        <th colspan="2"><h>Dodaj Nowy Pojazd</h></th>
        <form method="POST" action="pojazdy.php">
            <input type="hidden" name="add_vehicle">

            <tr><td><label>Klient: </label></td>
            <td> <select name="idKlient" required>
                    <option value="">-- Wybierz Klienta --</option>
                <?php
                
                       select_klienta($dane_klientow, $wybrany_klient);
                ?>
                </select><br>
            </td></tr>
            <tr><td><label>Marka: </label></td>
            <td><input type="text" name="marka" required><br></td></tr>
            <tr><td><label>Model: </label></td>
            <td><input type="text" name="model" required><br></td></tr>
            <tr><td><label>Rok Produkcji: </label></td>
            <td><input type="number" name="rok_produkcji"><br></td></tr>
            <tr><td><label>VIN: </label></td>
            <td><input type="text" name="vin"><br></td></tr>
            <tr><td><label>Pojemność Silnika: </label></td>
            <td><input type="number" name="pojemnosc_silnika"><br></td></tr>
            <tr><td><label>Rodzaj Paliwa: </label></td>
            <td><input type="text" name="rodz_paliwa"><br></td></tr>
            <tr><td colspan="2"><button class="btn" type="submit" >Dodaj Pojazd</button></td></tr>
        </form>
        </table>
        <?php
        endif; // endif konczy blok if 
        ?>
        <br><br>
        <button class="btn" onClick="window.location='strona_glowna.html'">Powrót do menu</button>
    </center>
</body>
</html>

