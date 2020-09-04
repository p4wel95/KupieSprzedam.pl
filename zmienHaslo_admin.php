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
    
    
    if ((isset($_POST['haslo'])) && (isset($_POST['userId'])))
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
            $haslo = $_POST['haslo'];
            if ((strlen($haslo)<8) || (strlen($haslo)>20))
            {
                $_SESSION['e_zmianaHasla']="Nowe Hasło musi posiadać od 8 do 20 znaków!";
            }
            else
            {
                $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);
                $query = "update uzytkownicy set haslo='".$haslo_hash."' where id=".$userId;
                $polaczenie->query($query);
                $_SESSION['haslo_zmienione']=true;
                header('Location: index.php');                                                                 
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
                if (isset($_SESSION['e_zmianaHasla'])) echo '<div class="blok" style="color: orange">'.$_SESSION['e_zmianaHasla'].'</div>';
                unset($_SESSION['e_zmianaHasla']);

            ?>
                
            <div class="blok">
                 <form action="zmienHaslo_admin.php" method="post">
                    <input type="hidden" name="userId" value="<?php echo $userId ?>">
                    <div class="divm">
                        Nowe Haslo : 
                        <input class="textinput" type="password" name="haslo">
                    </div>
                    <div class="divm">
                        <input class="submit" type="submit" value="Zmień">
                    </div>
                </form>
                
            </div>


		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>