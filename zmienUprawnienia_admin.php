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
    if ($_SESSION['uprawnienia'] < 3)
    {
        header('Location: index.php');
        exit();
    }
    require_once "connect.php";
    if (isset($_GET['id']))
    {
        $userId = $_GET['id'];
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        $polaczenie->query("SET NAMES 'utf8'");
        $rezultat = $polaczenie->query("SELECT uprawnienia FROM uzytkownicy where id=".$userId) or die($polaczenie->error);
        $wiersz = $rezultat->fetch_assoc();
        $aktualneUprawnienia = $wiersz['uprawnienia'];
        echo $aktualneUprawnienia;
    }
    
    
    
    
    
    
    
    
    
    
    if ((isset($_POST['uprawnienia'])) && (isset($_POST['userId'])))
    {
        $userId = $_POST['userId'];     
        $uprawnienia = $_POST['uprawnienia']; 
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        
        if ($polaczenie->connect_errno!=0)
        {
            echo "Error: ".$polaczenie->connect_errno;
        }
        else
        {
            $polaczenie->query("SET NAMES 'utf8'");
            $query = "update uzytkownicy set uprawnienia='".$uprawnienia."' where id=".$userId;
            $polaczenie->query($query);
            $_SESSION['uprawnienia_zmienione']=true;
            if($_SESSION['id'] == $userId)
            {
                $_SESSION['uprawnienia'] = $uprawnienia;
            }
            header('Location: index.php');


                
            
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
                if (isset($_SESSION['e_zmianaLogin'])) echo '<div class="blok" style="color: orange">'.$_SESSION['e_zmianaLogin'].'</div>';
                unset($_SESSION['e_zmianaLogin']);

            ?>
                
            <div class="blok">
                <div class="divm">
                    Aktualne uprawnienia : <?php echo $aktualneUprawnienia ?>
                    
                </div>
                <form action="zmienUprawnienia_admin.php" method="post">
                    <input type="hidden" name="userId" value="<?php echo $userId ?>">
                    <div class="divm">
                        Nowe uprawnienia (0,1,2,3) : 
                        <input class="textinput" type="number" name="uprawnienia">
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