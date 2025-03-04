<?php
// Połączenie z bazą danych
include('funkcje.php');
otworz_polaczenie();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_visit'])) {
        add_visit();
    }

    if (isset($_POST['edit_visit'])) {
        edit_visit();
    }
}

if (isset($_GET['delete'])) {
    delete_visit();
}
//pobieranie listy klientow
$query_klienci = "SELECT id, imie, nazwisko FROM klient";
$dane_klientow = mysqli_query($polaczenie, $query_klienci);
$query_pojazdy = "SELECT id, idKlient, model, marka FROM pojazd ";
$dane_pojazdow = mysqli_query($polaczenie, $query_pojazdy);


// Pobieranie danych z bazy danych
$filtr = $_POST['filtr'] ?? '';
if (!empty($filtr)) {
$query = "SELECT wizyta.id, klient.imie, klient.nazwisko, pojazd.marka, pojazd.model, wizyta.dataWizyty,
                wizyta.statusWizyty, wizyta.opis
          FROM wizyta 
          JOIN klient ON wizyta.idKlient = klient.id 
          JOIN pojazd ON wizyta.idPojazd = pojazd.id
          WHERE klient.imie LIKE '%$filtr%' 
           OR klient.nazwisko LIKE '%$filtr%'
           OR pojazd.marka LIKE '%$filtr%'
           OR pojazd.model LIKE '%$filtr%'
           OR wizyta.dataWizyty LIKE '%$filtr%'
           OR wizyta.statusWizyty LIKE '%$filtr%'
           OR wizyta.opis LIKE '%$filtr%'"; 
           } else {
            $query="SELECT wizyta.id, klient.imie, klient.nazwisko, pojazd.marka, pojazd.model, wizyta.dataWizyty,
                wizyta.statusWizyty, wizyta.opis
          FROM wizyta 
          JOIN klient ON wizyta.idKlient = klient.id 
          JOIN pojazd ON wizyta.idPojazd = pojazd.id"; 
          }

$result = mysqli_query($polaczenie, $query);

$wizyta = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $wizyta = get_dane_wizyty($id);
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
    <title>Wizyty</title>
</head>
<body >
    <center>
        <h1>Lista Wizyt</h1>
        <br>
        <br>
        <form method="POST">
        <i>Pokaż tylko wizyty zawierające frazę:</i>
        <input class="input_filtr" type="text" name="filtr" value="<?php echo $filtr; ?>">
        <button class="btn" type="submit" >Filtruj </button>
        </form>
        <br>
        
        <table>
            <tr>
                <th>Klient</th>
                <th>Pojazd</th>
                <th>Data Wizyty</th>
                <th>Status Wizyty</th>
                <th>Opis</th>
                <th>Opcje</th>
            </tr>
            
            <?php
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row['imie']." ".$row['nazwisko']."</td>
                    <td>".$row['marka']." ".$row['model']."</td>
                    <td>".$row['dataWizyty']."</td>
                    <td>".$row['statusWizyty']."</td>
                    <td>".$row['opis']."</td>
                    <td>
                        <a href='wizyty.php?edit=".$row['id']."'>Edytuj</a> | 
                        <a href='wizyty.php?delete=".$row['id']."'>Usuń</a>
                    </td>
                </tr>";
            }
            ?>
        </table>



        <?php if ($wizyta): // dwukropek rozpoczyna blok ?> 
            <table style="width: 50%;">
            <th colspan="2"><h>Edytuj Wizytę</h></th>
            <form method="POST" action="wizyty.php">
                <input type="hidden" name="id" value="<?php echo $wizyta['id']; ?>">
                <input type="hidden" name="edit_visit">
                <tr><td><label>Klient: </label></td>
                <td><select name="idKlient" required>
                    <option value="">-- Wybierz Klienta --</option>
                    <?php
                    $wybrany_klient = $wizyta['idKlient'];
                    select_klienta($dane_klientow, $wybrany_klient);
                    ?>
                </select><br></td></tr>
                <tr><td> <label>Pojazd: </label></td>
                <td><select name="idPojazd" required>
                    <option value="">-- Wybierz Pojazd --</option>
                    <?php
                    $wybrany_pojazd = $wizyta['idPojazd'];
                    select_pojazd($dane_pojazdow, $wybrany_pojazd);
                    ?>
                </select><br></td></tr>
                <tr><td><label>Data: </label></td><td>
                <input type="datetime-local" name="dataWizyty" value="<?php echo $wizyta['dataWizyty']; ?>" required><br>
                </td></tr>
                <tr><td><label>Status: </label></td>
                <td> <select name="statusWizyty" required>
                <option value="">-- Wybierz Status --</option>
                <option value="Zaplanowana" <?php echo $wizyta['statusWizyty'] == 'Zaplanowana' ? 'selected' : ''; ?>>Zaplanowana</option>
                <option value="W trakcie" <?php echo $wizyta['statusWizyty'] == 'W trakcie' ? 'selected' : ''; ?>>W trakcie</option>
                <option value="Zrealizowana" <?php echo $wizyta['statusWizyty'] == 'Zrealizowana' ? 'selected' : ''; ?>>Zrealizowana</option>
                </select><br></td></tr>
                <tr><td><label>Opis: </label></td>
                <td><textarea name="opis" rows="5" wrap="soft"required style="width: 100%; height: 100%; box-sizing: border-box;"><?php echo $wizyta['opis']; ?></textarea><br></td></tr>
                <tr><td> <button class="btn" type="submit" >Zapisz Zmiany</button></td>
                <td><button class="btn" onClick="window.location='wizyty.php'">Anuluj</button></td></tr>
               
                
            </form>
            </table>
            <?php
        else:
        ?>
        <table style="width: 50%;">
          <th colspan="2"><h>Dodaj Nową Wizytę</h></th>
          <form method="POST" action="wizyty.php">
          <input type="hidden" name="add_visit">
          <tr><td><label>Klient: </label>
          </td><td><select name="idKlient" required>
                    <option value="">-- Wybierz Klienta --</option>
                    <?php
                   $wybrany_klient = $wizyta['idKlient'];
                   select_klienta($dane_klientow, $wybrany_klient);
                    ?>
                </select><br></td></tr>
          <tr><td><label>Pojazd: </label></td>
        <td><select name="idPojazd" required>
                    <option value="">-- Wybierz Pojazd --</option>
                    <?php
                     select_pojazd($dane_pojazdow, $wybrany_pojazd);
                    ?>
                </select><br></td></tr>
                <tr><td><label>Data: </label></td><td><input type="datetime-local" name="dataWizyty" value="" required><br></td></tr>
                <tr><Td><label>Status: </label></Td>
            <td><select name="statusWizyty" required>
                <option value="">-- Wybierz Status --</option>
                <option value="Zaplanowana">Zaplanowana</option>
                <option value="W trakcie">W trakcie</option>
                <option value="Zrealizowana">Zrealizowana</option>
                </select><br></td></tr>
                <tr><td><label>Opis: </label></td><td><textarea name="opis" rows="5" wrap="soft" required style="width: 100%; height: 100%; box-sizing: border-box;"></textarea><br></td></tr>
                <tr><td colspan="2"><button class="btn" type="submit" >Dodaj Wizytę</button></td></tr>
                
        </form>
        </table>
        <?php
        endif; // endif konczy blok if 
        ?>
        <br><br>
        <button class="btn"  onClick="window.location='strona_glowna.html'">Powrót do menu</button>
    </center>
</body>
</html>