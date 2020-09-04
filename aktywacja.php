<?php

	session_start();
	if (isset($_GET['login']))
	{
            require_once 'connect.php';
            mysqli_report(MYSQLI_REPORT_STRICT);
            try 
            {
                $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
                if ($polaczenie->connect_errno!=0)
                {
                    throw new Exception(mysqli_connect_errno());
                }
                else
                {
                    $polaczenie->query("SET NAMES 'utf8'");
                        //Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
                    $query = "update uzytkownicy set potwierdzony=1 where login='".$_GET['login']."'";
                    if ($polaczenie->query($query))
                    {
                        $_SESSION['e_aktywacja']="Aktywacja udana";
                    }
                    else
                    {
                        $_SESSION['e_aktywacja']="Blad aktywacji";
                        throw new Exception($polaczenie->error);
                    }               

                    $polaczenie->close();
                }

            }
            catch(Exception $e)
            {
                    echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
                    echo '<br />Informacja developerska: '.$e;
            }   
	}
	else
	{
            header('Location: index.php');
            exit();
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
                <div class="divm">
                <?php
                    if (isset($_SESSION['e_aktywacja'])) echo $_SESSION['e_aktywacja'];
                ?>
                </div>
            </div>

</body>
</html>