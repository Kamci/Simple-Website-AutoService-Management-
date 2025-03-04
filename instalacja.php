<?php 

$serwer = mysqli_connect("localhost", "root", "")
                or exit("Nie mozna połączyć się z serwerem bazy danych");

$createDataBase = "CREATE DATABASE Autoserwis";
mysqli_query($serwer, $createDataBase);
$baza = mysqli_select_db($serwer, "Autoserwis") 
                or exit ("Nie mozna połączyć się z bazą Autoserwis");
                mysqli_set_charset($serwer, "utf8");

$createTable = "CREATE TABLE uzytkownicy(
                        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        login VARCHAR(30) NOT NULL,
                        imie VARCHAR(30) NOT NULL,
                        nazwisko VARCHAR(30) NOT NULL,
                        haslo VARBINARY(255) NOT NULL,
                        salt VARBINARY(255) NOT NULL);";
mysqli_query($serwer, $createTable);

$createTable = "CREATE TABLE klient( 
                 id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                 imie VARCHAR(30) NOT NULL,
                 nazwisko VARCHAR(30) NOT NULL,
                 telefon VARCHAR(15) NULL,
                 email VARCHAR(100) NULL,
                 adres NVARCHAR(100) NULL);";
mysqli_query($serwer, $createTable);

$createTable ="CREATE TABLE pojazd( 
                 id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                 idKlient INT NOT NULL,
                 marka VARCHAR(30) NOT NULL,
                 model VARCHAR(30) NOT NULL,
                 rok_produkcji INT NULL,
                 vin NVARCHAR(100) NULL,
                 pojemnosc_silnika INT NULL,
                 rodz_paliwa VARCHAR(30) NULL);";
mysqli_query($serwer, $createTable);

$createTable ="CREATE TABLE wizyta( 
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                idKlient INT NOT NULL,
                idPojazd INT NOT NULL,
                dataWizyty DATETIME NOT NULL,
                statusWizyty NVARCHAR(20) NOT NULL,
                opis NVARCHAR(255) NOT NULL)";
mysqli_query($serwer, $createTable);

$createTable ="CREATE TABLE usluga( 
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                nazwa NVARCHAR(50) NOT NULL,
                opis NVARCHAR(255) NOT NULL,
                cena DECIMAL(10,2) NOT NULL)";
mysqli_query($serwer, $createTable);


$createTable ="CREATE TABLE faktura( 
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                numerFaktury VARCHAR(50) NOT NULL UNIQUE,
                idWizyta INT NOT NULL,
                dataWystawienia DATE NOT NULL,
                terminPlatnosci DATE NOT NULL,
                statusFaktury NVARCHAR(20) NOT NULL,
                idUsluga INT NOT NULL,
                kwotaNetto DECIMAL(10,2) NOT NULL,
                kwotaVat DECIMAL(10,2) NOT NULL,
                kwotaBrutto DECIMAL(10,2) NOT NULL)";
mysqli_query($serwer, $createTable);

$salt=random_bytes(16);
$password="Haslo";
$saltedPassword = $salt.$password;
$hashedPassword = hash('sha256',$saltedPassword,true);
$insertIntoTable ="INSERT INTO uzytkownicy (login, imie, nazwisko, haslo, salt) 
                    VALUES ('Kami','Kamila','Wichowska', '$hashedPassword', '$salt')";
mysqli_query($serwer, $insertIntoTable);
$insertIntoTable = "INSERT INTO klient (imie, nazwisko, telefon, email, adres) VALUES 
    ('Jan', 'Kowalski', '123456789', 'jan.kowalski@example.com', 'Warszawa, ul. Kwiatowa 1'),
    ('Anna', 'Nowak', '987654321', 'anna.nowak@example.com', 'Kraków, ul. Długa 12'),
    ('Piotr', 'Wiśniewski', '456123789', 'piotr.wisniewski@example.com', 'Poznań, ul. Krótka 7')";
mysqli_query($serwer, $insertIntoTable);
$insertIntoTable = "INSERT INTO pojazd (idKlient, marka, model, rok_produkcji, vin, pojemnosc_silnika, rodz_paliwa) VALUES 
    (1, 'Toyota', 'Corolla', 2018, 'JT12345ABC67890', 1600, 'Benzyna'),
    (2, 'Volkswagen', 'Golf', 2020, 'WV98765XYZ43210', 2000, 'Diesel'),
    (3, 'BMW', 'X5', 2022, 'BM65432QWE12345', 3000, 'Hybrid')";
mysqli_query($serwer, $insertIntoTable);
$insertIntoTable = "INSERT INTO wizyta (idKlient, idPojazd, dataWizyty, statusWizyty, opis) VALUES 
    (1, 1, '2025-01-15 10:00:00', 'Zrealizowana', 'Przegląd techniczny'),
    (2, 2, '2025-01-20 14:00:00', 'W trakcie', 'Naprawa silnika'),
    (3, 3, '2025-02-01 09:30:00', 'Zaplanowana', 'Wymiana opon')";
mysqli_query($serwer, $insertIntoTable);
$insertIntoTable = "INSERT INTO usluga (nazwa, opis, cena) VALUES 
    ('Przegląd techniczny', 'Kompleksowy przegląd techniczny pojazdu', 300.00),
    ('Naprawa silnika', 'Diagnostyka i naprawa silnika', 1200.00),
    ('Wymiana opon', 'Wymiana i wyważenie opon', 200.00)";
mysqli_query($serwer, $insertIntoTable);
$insertIntoTable = "INSERT INTO faktura (numerFaktury, idWizyta, dataWystawienia, terminPlatnosci, statusFaktury, idUsluga, kwotaNetto, kwotaVat, kwotaBrutto) VALUES 
    ('FV-2025-001', 1, '2025-01-15', '2025-02-15', 'Opłacona', 1, 300.00, 69.00, 369.00),
    ('FV-2025-002', 2, '2025-01-20', '2025-02-20', 'Nieopłacona', 2, 1200.00, 276.00, 1476.00),
    ('FV-2025-003', 3, '2025-02-01', '2025-03-01', 'Zaplanowana', 3, 200.00, 46.00, 246.00)";
mysqli_query($serwer, $insertIntoTable);
mysqli_close($serwer);
echo "Baza danych 'Autoserwis' została pomyślnie utworzona.";
?>
