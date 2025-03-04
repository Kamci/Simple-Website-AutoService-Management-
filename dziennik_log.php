
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klienci</title>
    <link rel="stylesheet" href="css/style_main.css">
  
</head>
<body >
<?php
$plik = @fopen("dziennik_log.csv","r")
or exit("Problem z otwarciem pliku.");
echo "<center>
        <table style='border: 1px solid black; width: 50%;'>
      </center>";
$naglowki=["Login","Data logowania"];   
	
	echo "<tr>";
	foreach($naglowki as $naglowek) echo "<td align=center ><b> $naglowek </b></td>";
	echo "</tr>";
    
    while ($wpis= fgetcsv($plik, 0, ";")) { // jezeli warunek zwraca tablice która jest przypisana do 
        //wartosci $towar to warunek jest true i pętla wykonuje sie tak długo aż zwróci wszystkie rekordy 
        // jesli osiagnie koniec pliku zwraca false i petla sie kończy
      echo "<tr>";
      foreach($wpis as  $pole){
      
        echo "<td align=center> $pole  </td>";
      }
    

      echo "</tr>";
      
    }		

   
    echo "</table>";
    fclose($plik);
?>

<br><br>
<button class="btn" onClick="window.location='strona_glowna.html'">Powrót do menu</button>
</body>
</html>