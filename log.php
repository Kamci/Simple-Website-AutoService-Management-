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

if(!empty($login)) {

    otworz_polaczenie();
    global $polaczenie;
    $uzytkownik = " SELECT * FROM uzytkownicy WHERE login = '$login' ";
    $wynik = mysqli_fetch_assoc(mysqli_query($polaczenie, $uzytkownik));
    if ($wynik){
        
        $salt =  $wynik['salt'];
        $passwordDB = $wynik['haslo'];
        $saltedPassword = $salt.$password;
        $hashedPassword = hash('sha256',$saltedPassword,true);
        if ($passwordDB == $hashedPassword ){
            $wpis = [$login, date("Y-m-d H:i:s")];
            $plik = fopen("dziennik_log.csv","a");
	        fputcsv ($plik,$wpis,";",eol:"\n");
	        fclose($plik);

            header("Location: strona_glowna.html");
             
        }else 
        { 
            echo "hasło nieprawidłowe <br><br>"; 
            echo '<input type="button" value="Powrót" onClick="window.location=\'log.php\'">';
        }
    }
     else 
     { 
        echo "login nieprawidłowy <br><br>";
        echo '<input type="button" value="Powrót" onClick="window.location=\'log.php\'">';
    }

    zamknij_polaczenie();
}

else { // formularz generuje tylko gdy dane jeszcze nie były wysyłane
?>
<a href='rejestracja.php'> Jeśli nie masz dostępu ZAREJESTRUJ SIĘ </a>
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
<td colspan=2>
<input type=submit value='Zaloguj się' style='width:100%'></td>
</tr>
</table>
</form>
<?php } // koniec else ?>
</center>
</body>
</html>