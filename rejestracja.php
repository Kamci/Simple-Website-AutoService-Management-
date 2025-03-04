<?php
include('funkcje.php');
?>


<html>
<head>
<meta charset="utf-8">
<title>Logowanie</title>
</head>
<body style='background-color: teal; color: white'>
<br><center>
<?php
$login = $_POST['login'] ??'';
$password = $_POST['haslo']?? '' ;
$repeatedpassword = $_POST['powtorzhaslo']?? '' ;
$imie = $_POST['imie']?? '' ;
$nazwisko = $_POST['nazwisko']?? '' ;
if(!empty($login)) {

    otworz_polaczenie();
    global $polaczenie;
    $uzytkownik = " SELECT * FROM uzytkownicy WHERE login = '$login' ";
    $wynik = mysqli_fetch_assoc(mysqli_query($polaczenie, $uzytkownik));
    if (!$wynik)
        { 
            if ($password==$repeatedpassword){
                $salt=random_bytes(16);
                $saltedPassword = $salt.$password;
                $hashedPassword = hash('sha256',$saltedPassword,true);
                mysqli_query($polaczenie,"INSERT INTO uzytkownicy (login, imie, nazwisko, haslo, salt) 
                VALUES ('$login','$imie','$nazwisko','$hashedPassword','$salt')");
                echo "Rejestracja zakończona";
                ?><a href='log.php'> Zaloguj się </a> <?php
            }else {
                echo "Wpisz takie same hasła <br> <br>";
                echo '<input type="button" value="Powrót" onClick="window.location=\'rejestracja.php\'">';
            }

        
        }else {
            echo "Taki login już istnieje  <br> <br>";
            echo '<input type="button" value="Powrót" onClick="window.location=\'rejestracja.php\'">';

        } 
        zamknij_polaczenie();
    
}

else { // formularz generuje tylko gdy dane jeszcze nie były wysyłane
    ?>
    <form method=POST action=''>
    <table border=0>
    <tr>
    <td>Login</td><td colspan=2>
    <input type=text name='login' size=15 style='text-align: left'></td>
    </tr>
    <tr>
    <td>Hasło</td><td colspan=2>
    <input type=password name='haslo' size=15'></td>
    </tr>
    <tr>
    <td>Powtórz hasło</td><td colspan=2>
    <input type=password name='powtorzhaslo' size=15'></td>
    </tr>
    <tr>
    <td>Imie</td><td colspan=2>
    <input type=text name='imie' size=15'></td>
    </tr>
    <tr>
    <td>Nazwisko</td><td colspan=2>
    <input type=text name='nazwisko' size=15'></td>
    </tr>
    <tr>
    <tr>
    <td colspan=2>
    <input type=submit value='Zarejestruj się' style='width:100%'></td>
    </tr>
    </table>
    </form>
    <?php } // koniec else ?>
    </center>
    </body>
    </html>