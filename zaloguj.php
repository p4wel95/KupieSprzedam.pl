<?php
    session_start();
    if (isset($_POST['link']))
    {
        $email = $_SESSION['email'];
        $login = $_SESSION['login'];
        $subject = "Potwierdz konto";
        $messages= "http://kupiesprzedam.5v.pl/aktywacja.php?login=".$login;
        if( mail($email, $subject, $messages) ) {
        $_SESSION['e_sendemail'] = "Wiadomosc wyslana";
        } else {
          $_SESSION['e_sendemail'] = "Blad wysylania emaila, sprobuj puzniej lub pisz do admina";
        }
        $_SESSION['wyslano'] = true;
    }
    
    
    
    if ((isset($_POST['login'])) && (isset($_POST['haslo'])))
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
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];            

            $login = htmlentities($login, ENT_QUOTES, "UTF-8");

            if ($rezultat = @$polaczenie->query(
            sprintf("SELECT * FROM uzytkownicy WHERE login='%s'",
            mysqli_real_escape_string($polaczenie,$login))))
            {
                $ilu_userow = $rezultat->num_rows;
                if($ilu_userow>0)
                {
                    $wiersz = $rezultat->fetch_assoc();
                    if (password_verify($haslo, $wiersz['haslo']))
                    {
                        $_SESSION['id'] = $wiersz['id'];
                        $_SESSION['login'] = $wiersz['login'];
                        $_SESSION['email'] = $wiersz['email'];
                        $_SESSION['zablokowany'] = $wiersz['zablokowany'];
                        $_SESSION['powod_blokady'] = $wiersz['powod_blokady'];
                        $_SESSION['uprawnienia'] = $wiersz['uprawnienia'];
                        if ($wiersz['potwierdzony']==0)
                        {
                            $_SESSION['niepotwierdzony']=true;
                        }
                        else
                        {
                            if ($_SESSION['zablokowany']==1)
                            {
                                $_SESSION['zalogowany'] = true;
                                header('Location: ban.php');
                            }
                            else
                            {
                                $_SESSION['zalogowany'] = true;
                                header('Location: index.php');
                            }
                            
                        }
                        

                        unset($_SESSION['blad']);
                        $rezultat->free_result();                                               
                    }
                    else 
                        {
                            $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                        }
                } 
                else 
                {

                    $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';

                }

            }

            $polaczenie->close();
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
                <form action="zaloguj.php" method="post">
                    <div class="divm">
                        Login : 
                        <input class="textinput" type="text" name="login">
                    </div>
                    <div class="divm">
                        Haslo : 
                        <input class="textinput"  type="password" name="haslo">
                    </div>
                    <div class="divm">
                        <input class="submit" type="submit" value="Zaloguj">
                    </div>
                </form>
                <div class="divm">
                    <a href="przypomnij_Haslo.php">Przypomnij haslo</a>
                </div>
                
                <div>
                   <?php
                    if (isset($_SESSION['blad']))
                        {
                            echo $_SESSION['blad'];
                            unset($_SESSION['blad']);
                        }
                    if (isset($_SESSION['niepotwierdzony']) && !isset($_SESSION['wyslano']))
                        {
                            echo 'Niepotwierdzone konto! wyslij email ponownie : '
                        . '<form action="zaloguj.php" method="post">'
                        . '<input type="hidden" name="link">'
                        . '<input type="submit" value="Wyslij">';
                        }    
                    if (isset($_SESSION['e_sendemail']))
                    {
                        echo $_SESSION['e_sendemail'];
                        session_unset();
                    }
                   ?>
                    
                    
                </div>
            </div>
            
            
                
            
		
		
		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>