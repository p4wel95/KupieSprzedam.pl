<?php
    session_start();
    if (isset($_SESSION['niepotwierdzony'])) session_unset();
    
    if (isset($_GET['dodaj']))
    {
        require_once 'connect.php';
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        $polaczenie->query("SET NAMES 'utf8'");       
        
        $tytul = $_SESSION['tytul'];
        $opis = $_SESSION['opis'];
        $cena = $_SESSION['cena'];
        $kontakt = $_SESSION['kontakt'];
        $kategoria = $_SESSION['kategoria'];
        $podkategoria = $_SESSION['podkategoria'];
        $wojewodztwo = $_SESSION['wojewodztwo'];
        $miejscowosc = $_SESSION['miejscowosc'];
        $img[0] = $_SESSION['img'][0];
        $img[1] = $_SESSION['img'][1];
        $img[2] = $_SESSION['img'][2];
        $img[3] = $_SESSION['img'][3];
        $img[4] = $_SESSION['img'][4];
        $img[5] = $_SESSION['img'][5];
        
        
        for ($i=0;$i<6;$i++)
            {
                    if(strlen($img[$i]) > 5)
                    {
                        rename('temp/'.$img[$i], 'img/'.$img[$i]);
                    }
                   
            }
            
        $query = "INSERT INTO ogloszenia VALUES ('','".$tytul."','".$opis."','".$cena."','".$_SESSION['id']."',"
                            . "'".$kontakt."', '".$kategoria."', '".$podkategoria."', '".$wojewodztwo."', '".$miejscowosc."',"
                            . "'".$img[0]."','".$img[1]."','".$img[2]."','".$img[3]."','".$img[4]."','".$img[5]."',NOW())";
        
        $polaczenie->query($query);
        $_SESSION['dodano_ogloszenie']=true;
        header('Location: index.php?start');
        
        
        
    }
    
    if (isset($_POST['tytul']))
	{
            $tytul = $_POST['tytul'];
            $opis = $_POST['opis'];
            $cena = $_POST['cena'];
            $kontakt = $_POST['kontakt'];
            $kategoria = $_POST['kategoria'];
            $podkategoria = $_POST['podkategoria'];
            $wojewodztwo = $_POST['wojewodztwo'];
            $miejscowosc = $_POST['miejscowosc'];
            $img[0] = "";
            $img[1] = "";
            $img[2] = "";
            $img[3] = "";
            $img[4] = "";
            $img[5] = "";
            for ($i=0;$i<6;$i++)
            {
                if ($_FILES['file']['error'][$i] == 0)
                {
                   if ($_FILES['file']['type'][$i] == "image/jpeg" || $_FILES['file']['type'][$i] == "image/png")
                    {
                       if ($_FILES['file']['type'][$i] == "image/jpeg") { $img[$i] = rand(10000000,99999999).rand(10000000,99999999).".jpg"; }
                       else { $img[$i] = rand(10000000,99999999).rand(10000000,99999999).".png";  }
                       move_uploaded_file($_FILES['file']['tmp_name'][$i], 'temp/'.$img[$i]);

                    } 
                }
                else
                {
                    $a = $i+1;
                    $str = 'usun'.$a;
                    if (!isset($_POST[$str]))
                    {
                        @$img[$i] = $_SESSION['img'][$i]; 
                    } 
                }

            }
            
            require_once 'connect.php';
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            $polaczenie->query("SET NAMES 'utf8'");
            $rezultat = $polaczenie->query("select NOW()");
            $wiersz = $rezultat->fetch_assoc();
            $data_dodania = $wiersz['NOW()'];
            
            $_SESSION['tytul'] = $tytul;
            $_SESSION['opis'] = $opis;
            $_SESSION['cena'] = $cena;
            $_SESSION['kontakt'] = $kontakt;
            $_SESSION['kategoria'] = $kategoria;
            $_SESSION['podkategoria'] = $podkategoria;
            $_SESSION['wojewodztwo'] = $wojewodztwo;
            $_SESSION['miejscowosc'] = $miejscowosc;
            $_SESSION['img'][0] = $img[0];
            $_SESSION['img'][1] = $img[1];
            $_SESSION['img'][2] = $img[2];
            $_SESSION['img'][3] = $img[3];
            $_SESSION['img'][4] = $img[4];
            $_SESSION['img'][5] = $img[5];
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
                            echo '<a href="dodaj_ogloszenie.php"><div class="option">Dodaj Ogloszenie</div></a>';
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
                <a href="dodaj_ogloszenie.php" style="color:yellow">Popraw ogloszenie</a>
                <a href="?dodaj=true" style="color:yellow">Dodaj ogloszenie</a>
            </div>

            <div class="blok">
                <div class="tytul">
                    <?php
                    echo $tytul;
                    
                    ?>
                </div>
            </div>
             <div class="blok">
                 
                 <div class="divm" style="font-size: 40px;">
                    <?php  
                    echo $cena;
                    echo " zł ";
                    ?>
                </div>
                
                 
                 

                  <div class="divm">
                    <?php 
                    echo $_SESSION['login'];
                    echo " / ";
                    echo $wojewodztwo;
                    echo " / ";
                    echo $miejscowosc;
                    ?>
                </div>
                 
                 <div class="divm">
                    <?php 
                    echo "Kontakt : ";
                    echo $kontakt;
                    ?>
                </div>
                 
                <div class="divm" style="float: left;">  
                    <?php                  
                    echo $kategoria;
                    echo " / ";
                    echo $podkategoria;

                    ?>
                </div>
                 <div class="divm" style="float: right;">
                    <?php 
                    echo "Dodano dnia : ";
                    echo $data_dodania;
                    ?>
                </div>
                 

                 <div style="clear:both;"></div>
                 
             </div>
            <div class="blok">
                <div class = "opis"><?php if (isset($opis)) echo $opis; ?></div>
            </div>
            <div class="blok">               
                <?php
                for ($i=0; $i<6; $i++)
                {
                    if(strlen($img[$i])>3)
                    {
                        echo '<div class="zdjecie">'
                        . ' <img src="temp/'.$img[$i].'"/>'
                        . '</div>';
                    }
                }               
                
                ?>
                
            </div>
            
            
           <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div> 
            
	</div>
	
</body>
</html>