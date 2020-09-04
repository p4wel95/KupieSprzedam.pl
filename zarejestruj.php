<?php
    session_start();
    if (isset($_SESSION['login']))
    {
        header('Location: index.php');
        exit();
    }
    if (isset($_SESSION['niepotwierdzony'])) session_unset();
    if (isset($_POST['email']))
    {
        //Udana walidacja? Załóżmy, że tak!
        $wszystko_OK=true;

        //Sprawdź poprawność nickname'a
        $login = $_POST['login'];

        //Sprawdzenie długości nicka
        if ((strlen($login)<3) || (strlen($login)>20))
        {
            $wszystko_OK=false;
            $_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
        }

        if (ctype_alnum($login)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)";
        }

        // Sprawdź poprawność adresu email
        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
        {
            $wszystko_OK=false;
            $_SESSION['e_email']="Podaj poprawny adres e-mail!";
        }

        //Sprawdź poprawność hasła
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];

        if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
        {
            $wszystko_OK=false;
            $_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
        }

        if ($haslo1!=$haslo2)
        {
            $wszystko_OK=false;
            $_SESSION['e_haslo']="Podane hasła nie są identyczne!";
        }	

        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);					

        require_once "connect.php";
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
                //Czy email już istnieje?
                $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

                if (!$rezultat) throw new Exception($polaczenie->error);

                $ile_takich_maili = $rezultat->num_rows;
                if($ile_takich_maili>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
                }		

                //Czy nick jest już zarezerwowany?
                $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE login='$login'");

                if (!$rezultat) throw new Exception($polaczenie->error);

                $ile_takich_loginow = $rezultat->num_rows;
                if($ile_takich_loginow>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_login']="Istnieje już gracz o takim nicku! Wybierz inny.";
                }

                if ($wszystko_OK==true)
                {
                    //Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy

                    if ($polaczenie->query("INSERT INTO uzytkownicy VALUES ('', '$login', '$haslo_hash', '$email','0', '1', '0', '')"))
                    {
                        $subject = "Potwierdz konto";
						$messages= "http://kupiesprzedam.5v.pl/aktywacja.php?login=".$login;

                        if( mail($email, $subject, $messages) ) {
                          $_SESSION['e_sendemail'] = "Wiadomosc wyslana";
                        } else {
                          $_SESSION['e_sendemail'] = "Blad wysylania emaila, sprobuj puzniej lub pisz do admina";
                        }
                        $_SESSION['udanarejestracja']=true;
                        header('Location: witamy.php');
                    }
                    else
                    {
                            throw new Exception($polaczenie->error);
                    }

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
                    <a href="zaloguj.php"><div class="option">Zaloguj</div></a>
                    <a href="zarejestruj.php"><div class="option">Zarejstruj</div></a>


                </div>
                <div style="clear:both;"></div>
            </div>

            <div class="blok">
                <form action="zarejestruj.php" method="post">
                    <div class="divm">
                        Login : 
                        <input class="textinput" type="text" name="login">
                        <?php
			if (isset($_SESSION['e_login']))
			{
				echo '<div class="error">'.$_SESSION['e_login'].'</div>';
				unset($_SESSION['e_login']);
			}
                        ?>
                    </div>
                    <div class="divm">
                        Haslo : 
                        <input class="textinput" type="password" name="haslo1">
                        <?php
			if (isset($_SESSION['e_haslo']))
			{
				echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
				unset($_SESSION['e_haslo']);
			}
                        ?>
                    </div>
                    <div class="divm">
                        Powtorz Haslo : 
                        <input class="textinput" type="password" name="haslo2">
                    </div>
                    <div class="divm">
                        Email : 
                        <input class="textinput" type="email" name="email">
                        <?php
			if (isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
                        ?>
                    </div>
                    <div class="divm">
                        <input class="submit" type="submit" value="Zarejestruj">
                    </div>
                </form>
            </div>
		
		
		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>