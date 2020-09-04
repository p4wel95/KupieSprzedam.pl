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
    if ($_SESSION['uprawnienia'] < 2)
    {
        header('Location: index.php');
        exit();
    }
    
    if (isset($_GET['id']))
    {
        $userId = $_GET['id'];
    }
    
    
    if ((isset($_POST['email'])) && (isset($_POST['userId'])))
    {
        $userId = $_POST['userId'];
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
                $_SESSION['e_zmianaEmail']="Podaj poprawny adres e-mail!";
            }
            else
            {

                    $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
                    $ile_takich_maili = $rezultat->num_rows;
                    if($ile_takich_maili>0)
                    {

                        $_SESSION['e_zmianaEmail']="Istnieje już konto przypisane do tego adresu e-mail!";
                    }
                    else
                    {
                        $query = "update uzytkownicy set email='".$email."' where id=".$userId;
                        $polaczenie->query($query);
                        $_SESSION['email_zmieniony']=true;
                        header('Location: index.php');
                    }

                $rezultat->free_result();
            }
         
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
                if (isset($_SESSION['e_zmianaEmail'])) echo '<div class="blok" style="color: orange">'.$_SESSION['e_zmianaEmail'].'</div>';
                unset($_SESSION['e_zmianaEmail']);

            ?>
                
            <div class="blok">
                 <form action="zmienEmail_admin.php" method="post">
                     <input type="hidden" name="userId" value="<?php echo $userId ?>">
                    <div class="divm">
                        Nowy email : 
                        <input class="textinput" class="textinput" type="email" name="email">
                    </div>
                    <div class="divm">
                        <input class="submit" class="submit" type="submit" value="Zmień">
                    </div>
                </form>
                
            </div>


		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>