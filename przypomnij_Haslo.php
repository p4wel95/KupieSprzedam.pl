<?php
    session_start();
    
    
    if (isset($_POST['email']))
    {
        require_once "connect.php";
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        
        if ($polaczenie->connect_errno!=0)
        {
            echo "Error: ".$polaczenie->connect_errno;
        }
        else
        {
            $polaczenie->query("SET NAMES 'utf8'");
            $email = $_POST['email'];
            $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

            if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
            {
                $_SESSION['e_przypomnij']="Podaj poprawny adres e-mail!";
            }
            else
            {

                    $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
                    $ile_takich_maili = $rezultat->num_rows;
                    if($ile_takich_maili>0)
                    {
                        $haslo = rand(10000000,99999999);
                        echo $haslo;
                        $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);
                        $query = "update uzytkownicy set haslo='".$haslo_hash."' where email='".$email."'";
                        $polaczenie->query($query);
                        $subject = "Nowe haslo";
                        $messages= "Nowe Haslo : ".$haslo;
                        if( mail($email, $subject, $messages) ) {
                          $_SESSION['e_sendemail'] = "Wiadomosc wyslana";
                            $_SESSION['wyslano_haslo']="Wyslano nowe haslo na email";
                            header('Location: index.php');
                        } else {
                            $_SESSION['wyslano_haslo']="Blad wysylania nowego hasla";
                            header('Location: index.php');
                        }                        

                        
                    }
                    else
                    {
                        
                        $_SESSION['e_przypomnij']="brak adresu email w bazie";
                    }
                    
                                                                   
            }

            $rezultat->free_result();
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
            
            <?php
                if (isset($_SESSION['e_przypomnij'])) echo '<div class="blok" style="color: orange">'.$_SESSION['e_przypomnij'].'</div>';
                unset($_SESSION['e_przypomnij']);

            ?>
                
            <div class="blok">
                <form action="przypomnij_Haslo.php" method="post">
                    <div class="divm">
                        Podaj email : 
                        <input class="textinput" type="email" name="email">
                    </div>
                    <div class="divm">
                        <input class="submit" type="submit" value="Wyślij nowe hasło" style="width: 200px">
                    </div>
                </form>
                
            </div>


		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>