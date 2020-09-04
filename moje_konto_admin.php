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
    require_once 'connect.php';
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    $polaczenie->query("SET NAMES 'utf8'");
    $userId = $_SESSION['id'];
    
    if(isset($_POST['user']))
    {
        $user = $_POST['user'];
            $query = "select id,login from uzytkownicy where login like '%".$user."%'";
            $rezultat = $polaczenie->query($query);
            $ileUzytkownikow = $rezultat->num_rows;
            for ($i=0; $i<$ileUzytkownikow; $i++)
            {
                $wiersz = $rezultat->fetch_assoc();
                $users[$i] = $wiersz;
            }
    }
    
    if(isset($_POST['kategoria']))
    {
        $kategoria = $_POST['kategoria'];
        if (strlen($kategoria) > 1)
        {
            echo $kategoria;
            $query = "INSERT INTO kategorie VALUES ('','".$kategoria."')";
            $polaczenie->query($query);
        }
    }
    
    if(isset($_POST['podkategoria']))
    {
        $podkategoria = $_POST['podkategoria'];
        $kategoria2 = $_POST['kategoria2'];
        if (strlen($podkategoria) > 1)
        {
            echo $kategoria2;
            echo $podkategoria;
            $query = "select id from kategorie where nazwa='".$kategoria2."'";
            $rezultat = $polaczenie->query($query);
            $wiersz = $rezultat->fetch_assoc();
            $idKat = $wiersz['id'];
            $query = "INSERT INTO podkategorie VALUES ('','".$podkategoria."','".$idKat."')";
            $polaczenie->query($query);
        }
    }
    
    if(isset($_GET['usunKategorie']))
    {
        $kategoria = $_GET['usunKategorie'];
        echo $kategoria;
        $query = "Delete from kategorie where nazwa='".$kategoria."'";
        $polaczenie->query($query);

    }
    
    if(isset($_GET['usunPodkategorie']))
    {
        $podkategoria = $_GET['usunPodkategorie'];
        echo $podkategoria;
        $str3 = explode("/",$podkategoria);
        $kat = $str3[0];
        $podKat = $str3[1];
        
        $query = "select id from kategorie where nazwa='".$kat."'";
        $rezultat = $polaczenie->query($query);
        $wiersz = $rezultat->fetch_assoc();
        $idKat = $wiersz['id'];
        $query = "Delete from podkategorie where nazwa2='".$podKat."' and id_kategori =".$idKat;
        $polaczenie->query($query);

    }
    
    
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    $polaczenie->query("SET NAMES 'utf8'");
    $rezultat = $polaczenie->query("select * from kategorie");
    $ileKategori = $rezultat->num_rows;
    for ($i=0; $i<$ileKategori; $i++)
    {
        $wiersz = $rezultat->fetch_assoc();
        $kategorie[$i] = $wiersz['nazwa'];
    }
    
    $rezultat = $polaczenie->query("select * from kategorie as k, podkategorie as p where k.id=p.id_kategori");
    $ilePodkategori = $rezultat->num_rows;
    for ($i=0; $i<$ilePodkategori; $i++)
    {
        $wiersz = $rezultat->fetch_assoc();
        $podkategorie[$i] = $wiersz['nazwa'].'/'.$wiersz['nazwa2'];
    }
    
    
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    $polaczenie->query("SET NAMES 'utf8'");
    $rezultat = $polaczenie->query("SELECT id, tytul,cena,wojewodztwo,miejscowosc,img1,data_dodania FROM `ogloszenia` where id_wlasciciela = ".$_SESSION['id']." order by data_dodania desc");
    $ileOgloszen = $rezultat->num_rows;
    for ($i=0; $i<$ileOgloszen; $i++)
    {
        $wiersz = $rezultat->fetch_assoc();
        $ogloszenia[$i] = $wiersz;

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
                <a href="zmienHaslo_admin.php?id=<?php echo $userId ?>" style="color:#ad75c9">Zmien Haslo </a> |
                <a href="zmienEmail_admin.php?id=<?php echo $userId ?>" style="color:#ad75c9">Zmien Email</a> | 
                <a href="zmienLogin_admin.php?id=<?php echo $userId ?>" style="color:#ad75c9">Zmien Login</a>
                
            </div>
            
            <div class="blok">
                <form action="moje_konto_admin.php" method="post">
                    <div class="divm">
                        Znajdz użytkownika : 
                        <input class="textinput" type="text" name="user" style="width: 200px"><input type="submit" value="Znajdz">
                    </div>
                </form>
                
                <?php
                    
                if (isset($users))
                {   
                    for ($i=0; $i<$ileUzytkownikow; $i++)
                    {
                        echo '<div class="divm">'
                        . '<a href="user.php?id='.$users[$i]['id'].'" style="color:yellow">'.$users[$i]['login'].'</a>'
                        . '</div>';
                    }
                    
                }
                
                ?>
                <div class="divm">
                        
                </div>
                
            </div>
            
            <div class="blok">
                <div class="divm">
                    <form action="moje_konto_admin.php" method="post">
                    <div class="divm">
                        Dodaj Kategorie : 
                        <input class="textinput" type="text" name="kategoria" style="width: 200px"><input type="submit" value="Dodaj">
                    </div>
                </form> 
                </div>
                
            </div>
            <?php
                for ($i=0; $i<$ileKategori; $i++)
                {
                    echo '<div class="blok">';
                    echo '<span style="font-size:20px">'.$kategorie[$i].'</span> : -  <a href="?usunKategorie='.$kategorie[$i].'" style="color:yellow">Usuń kategorie</a></br>';
                    $a=0;
                    for ($j=0; $j<$ilePodkategori; $j++)
                    {                                                  
                        $str2 = explode("/",$podkategorie[$j]);                        
                        if ($str2[0] == $kategorie[$i])
                        {
                            $a++;
                            echo $a.' : '.$str2[1].' -  <a href="?usunPodkategorie='.$podkategorie[$j].'" style="color:yellow">Usuń podkategorie</a></br>';
                        }
                    }
                    echo '<div class="divm">'
                    . '<form action="moje_konto_admin.php" method="post">'
                            . 'Dodaj Podkategorie : '
                            . '<input type="hidden" name="kategoria2" value="'.$kategorie[$i].'">'
                            . '<input class="textinput" type="text" name="podkategoria" style="width: 200px"><input type="submit" value="Dodaj">'
                            . ' </form>'
                            . '</div>';
                    
                    
                    echo '</div>';
                }
            ?>


            <?php
                for ($i=0; $i<$ileOgloszen; $i++)
                {
                    if (strlen($ogloszenia[$i]['img1']) < 1)
                    {
                        $img = "brak.jpg";
                    }
                    else
                    {
                        $img = $ogloszenia[$i]['img1'];
                    }
                    echo '<a href="ogloszenie.php?id='.$ogloszenia[$i]['id'].'" style="padding:0px">'
                    . '<div class="ogloszenie">'
                    . '<div class="zdj_ogloszenia">'
                    . '<img src="img/'.$img.'">'
                    . '</div>'
                    . '<div class="info_ogloszenia">'
                    . '<div class="tytul_ogloszenia">'
                    . $ogloszenia[$i]['tytul']
                    . '</div>'
                    . '<div class="cena_ogloszenia">'
                    . $ogloszenia[$i]['cena'].' zł'
                    . '</div>'
                    . '<div class="lokalizacja_ogloszenia">'
                    . $ogloszenia[$i]['wojewodztwo'].' / '.$ogloszenia[$i]['miejscowosc']
                    . '</div>'
                    . '<div class="data_ogloszenia">'
                    . 'dodano dnia : '.$ogloszenia[$i]['data_dodania']
                    . '</div>'
                    . '</div>'
                    . '<div style="clear:both;"></div>'
                    . '</div>'
                    . '</a>';
                }
               ?>
		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>