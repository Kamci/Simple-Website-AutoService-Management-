<?php
// Połączenie z bazą danych
include('funkcje.php');
otworz_polaczenie();

// wywolanie odpowiednich funkcji w zaleznosci od akcji 
//$_SERVER to superglobalna tablica PHP, która zawiera różne 
//informacje o bieżącym żądaniu HTTP, serwerze, środowisku i użytkowniku.
//REQUEST_METHOD: Określa metodę żądania HTTP (np. GET, POST, PUT, DELETE).
// === porownoje wartosci po obu stronach a 3= porownouje typy po obu stronach
// w tym warunku potrzbujemy rowniez do porownania typu aby uniknac niezamierzonej konwersji zwracanej wartosci
// $_SERVER['REQUEST_METHOD']
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_client'])) {
        add_klient();
    }

    if (isset($_POST['edit_client'])) {
        edit_klient();
    }
}

if (isset($_GET['delete'])) {
    delete_klient();
}

$filtr = $_POST['filtr'] ?? '';
if (!empty($filtr)) {
    $query = "
        SELECT * 
        FROM klient 
        WHERE imie LIKE '%$filtr%' 
           OR nazwisko LIKE '%$filtr%'
           OR telefon LIKE '%$filtr%'
           OR adres LIKE '%$filtr%'
    ";
} else {
    $query = "SELECT * FROM klient";
}
// Pobieranie danych z bazy danych

$result = mysqli_query($polaczenie, $query);


// Pobieranie danych klienta 
$klient = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $klient = get_dane_klienta($id);
}


// Zakończenie połączenia z bazą
zamknij_polaczenie();

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klienci</title>
    <link rel="stylesheet" href="css/style_main.css">
  
</head>
<body >
    <center>
        <h1>Lista Klientów</h1>
        <br>
        <br>
        <form method="POST">
        <i>Pokaż tylko klientów zawierające frazę:</i>
        <input class="input_filtr" type="text" name="filtr" value="<?php echo $filtr; ?>">
        <button class="btn" type="submit" >Filtruj </button>
        </form>
    <br>
        <table>
            <tr>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Adres</th>
                <th>Opcje</th>
            </tr>
            
            <?php
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>".$row['imie']."</td>
                    <td>".$row['nazwisko']."</td>
                    <td>".$row['email']."</td>
                    <td>".$row['telefon']."</td>
                    <td>".$row['adres']."</td>
                    <td>
                        <a href='klienci.php?edit=".$row['id']."'>Edytuj</a> | 
                        <a href='klienci.php?delete=".$row['id']."'>Usuń</a>
                    </td>
                </tr>";
            }
            ?>
        </table>

        <?php if ($klient): // dwukropek rozpoczyna blok ?> 
            <table style="width: 400px; ">

            <th colspan="2"><h>Edytuj Klienta</h></th>
            <form method="POST" action="klienci.php">
                
                <input type="hidden" name="id" value="<?php echo $klient['id']; ?>">
                <input type="hidden" name="edit_client">
                <tr >
                    <td>
                <label>Imię: </label>
                    </td>
                    <td>
                    <input type="text" name="imie" value="<?php echo $klient['imie']; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>
                <label>Nazwisko: </label>
                    </td>
                    <td>
                    <input type="text" name="nazwisko" value="<?php echo $klient['nazwisko']; ?>" required><br>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label>Email: </label>
                    </td>
                    <td>
                        <input type="email" name="email" value="<?php echo $klient['email']; ?>" required><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Telefon: </label>
                    </td>
                    <td>
                        <input type="text" name="telefon" value="<?php echo $klient['telefon']; ?>"><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Adres: </label>
                    </td>
                    <td>
                        <input type="text" name="adres" value="<?php echo $klient['adres']; ?>"><br>
                    </td>
                </tr>
                <tr >
                    <td >
                        <button class="btn" type="submit" >Zapisz zmiany</button>
                    </td>
                    <td>
                    <button class="btn" onClick="window.location='klienci.php'">Anuluj</button>
                    </td>
                 </tr>
            </form>
            </table>
            <br>
        
            <?php
        else:  // dwukropek rozpoczyna blok 
          
        ?>

    <table style="width: 400px; ">
    <th colspan="2"><h>Dodaj Nowego Klienta</h> </th>
        <form method="POST" action="klienci.php">
            <input type="hidden" name="add_client">
            <tr>
                <td>
            <label>Imię: </label>
                </td>
                <td>
                <input type="text" name="imie" required><br>
                </td>
            </tr>
            <tr>
                <td>
                <label>Nazwisko: </label>
                </td>
                <td>
                    <input type="text" name="nazwisko" required><br>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Email: </label>
                </td>
                <td>
                    <input type="email" name="email" required><br>
                </td>
            </tr>
            <tr><td><label>Telefon: </label></td><td><input type="text" name="telefon"><br></td></tr>            
             <tr><td><label>Adres: </label></td> <td><input type="text" name="adres"><br></td></tr>       
           <tr><td colspan="2"><button class="btn" type="submit" >Dodaj Klienta </button></td></tr>
            
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