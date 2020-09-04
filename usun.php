<?php
    session_start();
    if (isset($_SESSION['niepotwierdzony']))
    {
        session_unset();
        header('Location: index.php');
        exit();
    }
    require_once 'connect.php';
    ?>
    
    
<?php
    if(isset($_POST['id_ogloszenia']))
        {
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            $polaczenie->query("SET NAMES 'utf8'");
            $query = "select * from ogloszenia where id=".$_POST['id_ogloszenia'];
            $rezultat = $polaczenie->query($query);
            $wiersz = $rezultat->fetch_assoc();
            if ($wiersz['id_wlasciciela'] != $_SESSION['id'] && $_SESSION['uprawnienia'] < 2)
            {
                header('Location: index.php');
                exit();
            }
            else
            {
                $query = "delete from ogloszenia where id=".$_POST['id_ogloszenia'];
                $polaczenie->query($query);

                $_SESSION['usunieto_ogloszenie']=true;
                header('Location: index.php');
            }

        }
    if(isset($_GET['id']))
    {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        $polaczenie->query("SET NAMES 'utf8'");
        $query = "select * from ogloszenia where id=".$_GET['id'];
        $rezultat = $polaczenie->query($query);
        $wiersz = $rezultat->fetch_assoc();
        if ($wiersz['id_wlasciciela'] != $_SESSION['id'] && $_SESSION['uprawnienia'] < 2)
        {
            header('Location: index.php');
            exit();
        }
        else
        {
            $_SESSION['id_ogloszenia'] = $wiersz['id'];
            $_SESSION['tytul'] = $wiersz['tytul'];
        }

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
                <div class="divm" style="color:red" id="div">
                    <?php
                        if (isset($_SESSION['e_blad'])) echo $_SESSION['e_blad'];
                    ?>
                </div>
                <form action="usun.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_ogloszenia" value="<?php echo $_SESSION['id_ogloszenia'] ?>">
                    Czy na pewno chcesz usunać ogłoszenie : <?php echo $_SESSION['tytul']; ?> ?
                    <input class="submit" type="submit"  name="submit" value="Usuń" >
                </form>
            		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>
