<?php
    session_start();
    if (isset($_SESSION['niepotwierdzony'])) session_unset();
    
    if (isset($_GET['id']))
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
                    $query = "select * from ogloszenia where id=".$_GET['id'];
                    if ($rezultat = $polaczenie->query($query))
                    {
                        $wiersz = $rezultat->fetch_assoc();
                        $id = $wiersz['id'];
                        $tytul = $wiersz['tytul'];
                        $opis = $wiersz['opis'];
                        $cena = $wiersz['cena'];
                        $id_wlasciciela = $wiersz['id_wlasciciela'];
                        $kontakt = $wiersz['kontakt'];
                        $kategoria = $wiersz['kategoria'];
                        $podkategoria = $wiersz['podkategoria'];
                        $wojewodztwo = $wiersz['wojewodztwo'];
                        $miejscowosc = $wiersz['miejscowosc'];
                        $img[0] = $wiersz['img1'];
                        $img[1] = $wiersz['img2'];
                        $img[2] = $wiersz['img3'];
                        $img[3] = $wiersz['img4'];
                        $img[4] = $wiersz['img5'];
                        $img[5] = $wiersz['img6'];
                        $data_dodania = $wiersz['data_dodania'];
                        if ($rezultat = $polaczenie->query("select login from uzytkownicy where id=".$id_wlasciciela))
                        {
                           $wiersz = $rezultat->fetch_assoc();
                           $login_wlasciciela = $wiersz['login'];
                        }
                        else
                        {
                            throw new Exception($polaczenie->error);
                        } 
                        
                    }
                    else
                    {
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
<script>
    function myFunction(imgs) {
  // Get the expanded image
  var expandImg = document.getElementById("expandedImg");
  // Get the image text
  var imgText = document.getElementById("imgtext");
  // Use the same src in the expanded image as the image being clicked on from the grid
  expandImg.src = imgs.src;
  // Show the container element (hidden with CSS)
  expandImg.parentElement.style.display = "block";
}
</script>
    

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
            
            <?php
                if (isset($_SESSION['login']))
                {
                    if ($_SESSION['id'] == $id_wlasciciela || $_SESSION['uprawnienia'] > 1 )
                    {
                        echo '<div class="blok">'
                        . '<a href="edytuj.php?id='.$id.'" style="color:yellow">Edytuj ogloszenie</a>'
                                . '<a href="usun.php?id='.$id.'" style="color:yellow">Usuń ogloszenie</a>'
                                . ' </div>';
                    }
                }
            ?>

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
                    echo '<a style="color:#ad75c9" href="user.php?id='.$id_wlasciciela.'">'.$login_wlasciciela.'</a>';
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
                
                <div class="row">
                    <?php
                    for ($i=0; $i<6; $i++)
                        {
                            if(strlen($img[$i])>3)
                            {
                                echo '<div class="column">'
                                . '<img src="img/'.$img[$i].'"  onclick="myFunction(this);">'
                                . '</div>';
                            }
                        }
                    ?>
                   
                  </div>
                
                <div class="container2">
                    <!-- Close the image -->
                    <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span>

                    <!-- Expanded image -->
                    <img id="expandedImg" style="width:100%">

                    <!-- Image text -->
                    <div id="imgtext"></div>
                  </div>
                
                <!--<?php
                for ($i=0; $i<6; $i++)
                {
                    if(strlen($img[$i])>3)
                    {
                        echo '<div class="zdjecie">'
                        . ' <img src="img/'.$img[$i].'"/>'
                        . '</div>';
                    }
                }               
                
                ?> -->
                
            </div>
           <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div> 
            
	</div>
	
</body>
</html>