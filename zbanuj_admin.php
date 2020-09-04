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
    
    require_once 'connect.php';
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    $polaczenie->query("SET NAMES 'utf8'");
    $query = "select zablokowany from uzytkownicy where id=".$userId;
    $rezultat = $polaczenie->query($query);
    $wiersz = $rezultat->fetch_assoc();
    $czyZablokowany = $wiersz['zablokowany'];
    
    if ($czyZablokowany == 1)
    {
        $query = "update uzytkownicy set zablokowany=0 where id=".$userId;
        $polaczenie->query($query);
        $query = "update uzytkownicy set powod_blokady=''";
        $polaczenie->query($query);
        $_SESSION['odbanowany']=true;
        header('Location: index.php');
    }
    
    
    if ((isset($_POST['powod'])) && (isset($_POST['userId'])))
    {
        $userId = $_POST['userId'];
        $powod = $_POST['powod'];
        require_once "connect.php";
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        
        if ($polaczenie->connect_errno!=0)
        {
            echo "Error: ".$polaczenie->connect_errno;
        }
        else
        {
            $polaczenie->query("SET NAMES 'utf8'");

            $query = "update uzytkownicy set zablokowany=1 where id=".$userId;
            $polaczenie->query($query);
            $query = "update uzytkownicy set powod_blokady='".$powod."' where id=".$userId;
            $polaczenie->query($query);
            $_SESSION['zbanowany']=true;
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
                <form action="zbanuj_admin.php" method="post">
                    <input type="hidden" name="userId" value="<?php echo $userId ?>">
                    <div class="divm">
                        Powod bana : </br>
                        <textarea style="resize: none;width:70%" name="powod" rows="10"></textarea>
                    </div>
                    <div class="divm">
                        <input class="submit" type="submit" value="Zbanuj">
                    </div>
                </form>
                
            </div>


		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>