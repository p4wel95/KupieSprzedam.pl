<?php
    session_start();
    if (isset($_SESSION['niepotwierdzony']))
    {
        session_unset();
        header('Location: index.php');
    }
    
    if (!isset($_SESSION['id']))
    {
        header('Location: index.php');
        exit();
    }
    
    if (isset($_SESSION['uprawnienia']))
        {
            if ($_SESSION['uprawnienia'] > 1)
            {
               header('Location: moje_konto_admin.php');
                exit(); 
            }
            
            
            
        }
    
    
    require_once 'connect.php';
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    $polaczenie->query("SET NAMES 'utf8'");
    $rezultat = $polaczenie->query("SELECT id, tytul,cena,wojewodztwo,miejscowosc,img1,data_dodania FROM `ogloszenia` where id_wlasciciela = ".$_SESSION['id']." order by data_dodania desc");
    $ileOgloszen = $rezultat->num_rows;
    for ($i=0; $i<$ileOgloszen; $i++)
    {
        $wiersz = $rezultat->fetch_assoc();
        $ogloszenia[$i] = $wiersz;

    }
     
?>


<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>kupieSprzedam</title>
	<meta name="description" content="Serwis ogloszeniowy" />
	<meta name="keywords" content="kupie, sprzedam" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Lato:400,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

</head>

<body>
	<div class="container">

	
            <div class="blok">
                <div class="logo">
                    <a href="index.php?start">KupieSprzedam.pl</a>
                </div>

                <div class="menu">
                    <?php
                        if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
                        {
                            echo '<a href="moje_konto.php"><div class="option">'.$_SESSION['login'].'</div></a>';
                            echo '<a href="wyloguj.php"><div class="option">Wyloguj</div></a>';
                        }
                        else
                        {
                            echo '<a href="zaloguj.php"><div class="option">Zaloguj</div></a>';
                            echo '<a href="zarejestruj.php"><div class="option">Zarejstruj</div></a>';                                                                                   
                        }
                    ?>                                     

                </div>
                <div style="clear:both;"></div>
            </div>
                
            <div class="blok">
                <a href="zmienHaslo.php" style="color:#ad75c9">Zmien Haslo </a> |
                <a href="zmienEmail.php" style="color:#ad75c9">Zmien Email</a> |
                <a href="zmienLogin.php" style="color:#ad75c9">Zmien Login</a>
                
            </div>

            <?php
                for ($i=0; $i<$ileOgloszen; $i++)
                {
                    if (strlen($ogloszenia[$i]['img1']) < 1)
                    {
                        $img = "brak.jpg";
                    }
                    else
                    {
                        $img = $ogloszenia[$i]['img1'];
                    }
                    echo '<a href="ogloszenie.php?id='.$ogloszenia[$i]['id'].'" style="padding:0px">'
                    . '<div class="ogloszenie">'
                    . '<div class="zdj_ogloszenia">'
                    . '<img src="img/'.$img.'">'
                    . '</div>'
                    . '<div class="info_ogloszenia">'
                    . '<div class="tytul_ogloszenia">'
                    . $ogloszenia[$i]['tytul']
                    . '</div>'
                    . '<div class="cena_ogloszenia">'
                    . $ogloszenia[$i]['cena'].' zł'
                    . '</div>'
                    . '<div class="lokalizacja_ogloszenia">'
                    . $ogloszenia[$i]['wojewodztwo'].' / '.$ogloszenia[$i]['miejscowosc']
                    . '</div>'
                    . '<div class="data_ogloszenia">'
                    . 'dodano dnia : '.$ogloszenia[$i]['data_dodania']
                    . '</div>'
                    . '</div>'
                    . '<div style="clear:both;"></div>'
                    . '</div>'
                    . '</a>';
                }
               ?>
		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>