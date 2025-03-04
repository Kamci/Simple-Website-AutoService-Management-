
<?php 
function otworz_polaczenie(){
	global $polaczenie;
	$serwer = "127.0.0.1";
	$uzytkownik = "root";
	$haslo = "";
	$baza = "autoserwis";

	//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // zgłasza błędy lub wyjątki
	mysqli_report(MYSQLI_REPORT_OFF); // wyłącza zgłaszanie, wtedy poniższy kod działa
	
	$polaczenie = mysqli_connect($serwer, $uzytkownik, $haslo) or exit("Nieudane połączenie z serwerem");	
	//mysqli_select_db($polaczenie, $baza) or exit("Nieudane połączenie z bazą $baza");
	if(!mysqli_select_db($polaczenie, $baza)) {
		// 1049 oznacza że baza nie istnieje
		if(mysqli_errno($polaczenie) == 1049) {
			mysqli_select_db($polaczenie, $baza);
		}
		else echo("Połączenie z bazą danych $baza nieudane<br>");
	}
	mysqli_set_charset($polaczenie, "utf8");
}

function zamknij_polaczenie(){
	global $polaczenie;
	mysqli_close($polaczenie);
}



// Funkcje dla klient.php

//--------------------------------------------------------------------
function add_klient()
{
	global $polaczenie;
	// Funkcja dodawania klienta
if(isset($_POST['add_client'])) {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $adres = $_POST['adres'];

    $insertQuery = "INSERT INTO klient (imie, nazwisko, email, telefon, adres) 
                    VALUES ('$imie', '$nazwisko', '$email', '$telefon', '$adres')";
    mysqli_query($polaczenie, $insertQuery);
    header("Location: klienci.php"); // Przeładowanie strony po dodaniu klienta
}
}

function delete_klient()
{
	global $polaczenie;
// Funkcja usuwania klienta
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM klient WHERE id = $id";
    mysqli_query($polaczenie, $deleteQuery);
    header("Location: klienci.php"); // Przeładowanie strony po usunięciu klienta
}
}

function get_dane_klienta($id){
	global $polaczenie;
	$query = "SELECT * FROM klient WHERE id = $id";
	$result = mysqli_query($polaczenie, $query);
	if ($result)
	{
		return mysqli_fetch_assoc($result);
	} 
	else 
	{
		return null;
	}
}


function edit_klient()
{
	global $polaczenie;
	// Funkcja edytowania klienta
	if(isset($_POST['edit_client'])) {
    $id = $_POST['id'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $adres = $_POST['adres'];

    $updateQuery = "UPDATE klient SET imie='$imie', nazwisko='$nazwisko', email='$email', telefon='$telefon', 
                    adres='$adres' WHERE id=$id";
    mysqli_query($polaczenie, $updateQuery);
    header("Location: klienci.php"); // Przeładowanie strony po edytowaniu klienta
}

}
//--------------------------------------------------------------------------------------------------


// funkcje dla pojazdy.php
//-----------------------------------------------------------------------------------------------

function get_dane_pojazdu($id){
	global $polaczenie;
	$query = "SELECT * FROM pojazd WHERE id = $id";
	$result = mysqli_query($polaczenie, $query);
	if ($result)
	{
		return mysqli_fetch_assoc($result);
	} 
	else 
	{
		return null;
	}
}


function add_vehicle()
{
	global $polaczenie;
	// Funkcja dodawania pojazdu
if(isset($_POST['add_vehicle'])) {
    $idKlient = $_POST['idKlient'];
    $marka = $_POST['marka'];
    $model = $_POST['model'];
    $rok_produkcji = $_POST['rok_produkcji'];
    $vin = $_POST['vin'];
    $pojemnosc_silnika = $_POST['pojemnosc_silnika'];
    $rodz_paliwa = $_POST['rodz_paliwa'];

    $insertQuery = "INSERT INTO pojazd (idKlient, marka, model, rok_produkcji, vin, pojemnosc_silnika, rodz_paliwa) 
                    VALUES ('$idKlient', '$marka', '$model', '$rok_produkcji', '$vin', '$pojemnosc_silnika', '$rodz_paliwa')";
    mysqli_query($polaczenie, $insertQuery);
    header("Location: pojazdy.php"); // Przeładowanie strony po dodaniu pojazdu
}
}
function delete_vehicle(){
	global $polaczenie;
// Funkcja usuwania pojazdu
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM pojazd WHERE id = $id";
    mysqli_query($polaczenie, $deleteQuery);
    header("Location: pojazdy.php"); // Przeładowanie strony po usunięciu pojazdu
}
}

function edit_vehicle(){
	global $polaczenie;
// Funkcja edytowania pojazdu
if(isset($_POST['edit_vehicle'])) {
    $id = $_POST['id'];
    $idKlient = $_POST['idKlient'];
    $marka = $_POST['marka'];
    $model = $_POST['model'];
    $rok_produkcji = $_POST['rok_produkcji'];
    $vin = $_POST['vin'];
    $pojemnosc_silnika = $_POST['pojemnosc_silnika'];
    $rodz_paliwa = $_POST['rodz_paliwa'];

    $updateQuery = "UPDATE pojazd SET idKlient='$idKlient', marka='$marka', model='$model', rok_produkcji='$rok_produkcji', vin='$vin', 
                    pojemnosc_silnika='$pojemnosc_silnika', rodz_paliwa='$rodz_paliwa' WHERE id=$id";
    mysqli_query($polaczenie, $updateQuery);
    header("Location: pojazdy.php"); // Przeładowanie strony po edytowaniu pojazdu
}
}

function select_klienta($dane_klientow, $wybrany_klient){
	
	while ($klient = mysqli_fetch_assoc($dane_klientow)) {
		if ($klient['id'] == $wybrany_klient) {
			$selected = 'selected';
		} else {
			$selected = '';
		}
		echo "<option value='
		" .$klient['id'] . "' $selected>
		". $klient['imie'] . " " . $klient['nazwisko'] ."
		</option>";
}

}



//--------------------------------------------------------------------------------------------------


// funkcje dla uslugi.php
//-----------------------------------------------------------------------------------------------


function add_service()
{
	global $polaczenie;
	// Funkcja dodawania uslugi
if(isset($_POST['add_service'])) {
    $nazwa = $_POST['nazwa'];
    $opis = $_POST['opis'];
    $cena = $_POST['cena'];

    $insertQuery = "INSERT INTO usluga (nazwa, opis, cena) 
                    VALUES ('$nazwa', '$opis', '$cena')";
    mysqli_query($polaczenie, $insertQuery);
    header("Location: uslugi.php"); // Przeładowanie strony po dodaniu klienta
}
}

function delete_service()
{
	global $polaczenie;
// Funkcja usuwania uslugi
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM usluga WHERE id = $id";
    mysqli_query($polaczenie, $deleteQuery);
    header("Location: uslugi.php"); // Przeładowanie strony po usunięciu klienta
}
}

function get_dane_uslugi($id){
	global $polaczenie;
	$query = "SELECT * FROM usluga WHERE id = $id";
	$result = mysqli_query($polaczenie, $query);
	if ($result)
	{
		return mysqli_fetch_assoc($result);
	} 
	else 
	{
		return null;
	}
}


function edit_service()
{
	global $polaczenie;
	// Funkcja edytowania klienta
	if(isset($_POST['edit_service'])) {
    $id = $_POST['id'];
    $nazwa = $_POST['nazwa'];
    $opis = $_POST['opis'];
    $cena = $_POST['cena'];

    $updateQuery = "UPDATE usluga SET nazwa='$nazwa', opis='$opis', cena='$cena'
					 WHERE id=$id";
    mysqli_query($polaczenie, $updateQuery);
    header("Location: uslugi.php"); // Przeładowanie strony po edytowaniu klienta
}

}

//--------------------------------------------------------------------------------------------------


// funkcje dla wizyty.php
//-----------------------------------------------------------------------------------------------


function select_pojazd($dane_pojazdow, $wybrany_pojazd ){
	
	while ($pojazd = mysqli_fetch_assoc($dane_pojazdow)) {
		
			if ($pojazd['id'] == $wybrany_pojazd) 
			{
                $selected = 'selected';
            } 
			else 
			{
                $selected = '';
            }
		echo "<option value='
		" .$pojazd['id'] . "' $selected>
		". $pojazd['marka'] . " " . $pojazd['model'] ."
		</option>";
}
}

function get_dane_wizyty($id){
	global $polaczenie;
	$query = "SELECT * FROM wizyta WHERE id = $id";
	$result = mysqli_query($polaczenie, $query);
	if ($result)
	{
		return mysqli_fetch_assoc($result);
	} 
	else 
	{
		return null;
	}
}

function add_visit()
{
	global $polaczenie;
	// Funkcja dodawania pojazdu
if(isset($_POST['add_visit'])) {
    $idKlient = $_POST['idKlient'];
    $idPojazd = $_POST['idPojazd'];
    $dataWizyty = $_POST['dataWizyty'];
    $statusWizyty = $_POST['statusWizyty'];
    $opis = $_POST['opis'];

    $insertQuery = "INSERT INTO wizyta (idKlient, idPojazd, dataWizyty, statusWizyty, opis) 
                    VALUES ('$idKlient', '$idPojazd', '$dataWizyty', '$statusWizyty', '$opis')";
    mysqli_query($polaczenie, $insertQuery);
    header("Location: wizyty.php"); // Przeładowanie strony po dodaniu wizyty
}
}


function edit_visit()
{
	global $polaczenie;
	// Funkcja dodawania pojazdu
if(isset($_POST['edit_visit'])) {
	$id = $_POST['id'];
    $idKlient = $_POST['idKlient'];
    $idPojazd = $_POST['idPojazd'];
    $dataWizyty = $_POST['dataWizyty'];
    $statusWizyty = $_POST['statusWizyty'];
    $opis = $_POST['opis'];

    $updateQuery = "UPDATE wizyta SET idKlient='$idKlient', idPojazd='$idPojazd', dataWizyty='$dataWizyty',
					statusWizyty='$statusWizyty', opis='$opis'
					 WHERE id=$id";
    mysqli_query($polaczenie, $updateQuery);
    header("Location: wizyty.php"); // Przeładowanie strony po edytowaniu klienta
}
}

function delete_visit()
{
	global $polaczenie;
// Funkcja usuwania uslugi
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM wizyta WHERE id = $id";
    mysqli_query($polaczenie, $deleteQuery);
    header("Location: wizyty.php"); // Przeładowanie strony po usunięciu klienta
}
}

//--------------------------------------------------------------------------------------------------


// funkcje dla dodaj_fakture.php
//-----------------------------------------------------------------------------------------------

function add_invoice($numerFaktury, $dataWystawienia, $terminPlatnosci, $statusFaktury, $kwotaNetto, $kwotaVat, $kwotaBrutto)
{
	global $polaczenie;
	
	$errors = []; // Błędy dla poszczególnych pól
	  // Walidacja numeru faktury
	  if (!preg_match('/^FV-\d{4}-\d{3}$/', $numerFaktury)) {
        $errors['numerFaktury'] = "Niepoprawny format numeru faktury. Wymagany format: FV-YYYY-XXX";
    }

    // Walidacja daty wystawienia
    if (empty($dataWystawienia)) {
        $errors['dataWystawienia'] = "Data wystawienia jest wymagana.";
    }

    // Walidacja terminu płatności
    if (empty($terminPlatnosci)) {
        $errors['terminPlatnosci'] = "Termin płatności jest wymagany.";
    }

    // Walidacja kwot
    if (empty($kwotaNetto) || !is_numeric($kwotaNetto)) {
        $errors['kwotaNetto'] = "Kwota netto jest wymagana i musi być liczbą.";
    }

    if (empty($kwotaVat) || !is_numeric($kwotaVat)) {
        $errors['kwotaVat'] = "Kwota VAT jest wymagana i musi być liczbą.";
    }

    if (empty($kwotaBrutto) || !is_numeric($kwotaBrutto)) {
        $errors['kwotaBrutto'] = "Kwota brutto jest wymagana i musi być liczbą.";
    }

    // Jeśli brak błędów, dodaj fakturę do bazy
    if (empty($errors)) {
		$query = "INSERT INTO faktura (numerFaktury, dataWystawienia, terminPlatnosci, statusFaktury, kwotaNetto, kwotaVat, kwotaBrutto)
		VALUES ('$numerFaktury', '$dataWystawienia', '$terminPlatnosci', '$statusFaktury', $kwotaNetto, $kwotaVat, $kwotaBrutto)";
		mysqli_query($polaczenie, $query);

		header('Location: faktury.php');
    }

    return $errors;

}
function delete_invoice()
{
	global $polaczenie;
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM faktura WHERE id = $id";
    mysqli_query($polaczenie, $deleteQuery);
    header("Location: faktury.php"); // Przeładowanie strony po usunięciu 
}
}

function edit_invoice()
{
	global $polaczenie;
	$id = $_GET['id'];
	$numerFaktury = $_POST['numerFaktury'];
    $dataWystawienia = $_POST['dataWystawienia'];
    $terminPlatnosci = $_POST['terminPlatnosci'];
    $statusFaktury = $_POST['statusFaktury'];
    $kwotaNetto = $_POST['kwotaNetto'];
    $kwotaVat = $_POST['kwotaVat'];
    $kwotaBrutto = $_POST['kwotaBrutto'];

    $query = "UPDATE faktura SET numerFaktury='$numerFaktury', dataWystawienia='$dataWystawienia', 
	terminPlatnosci='$terminPlatnosci', statusFaktury='$statusFaktury', kwotaNetto=$kwotaNetto, 
	kwotaVat=$kwotaVat, kwotaBrutto=$kwotaBrutto 
	WHERE id=$id";
    mysqli_query($polaczenie, $query);

    header('Location: faktury.php');
}
?>
