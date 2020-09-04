<?php
    session_start();
    if (isset($_SESSION['niepotwierdzony']))
    {
        session_unset();
        header('Location: index.php');
        exit();
    }
    if (isset($_SESSION['zablokowany']) && $_SESSION['zablokowany'] == 1)
    {
        header('Location: ban.php');
        exit();
    }
    require_once 'connect.php';
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

    
    $podkategoria = "";
    if (isset($_SESSION['podkategoria'])) $podkategoria = $_SESSION['podkategoria'];
    ?>


    
<script> 
  
// Access the array elements 
var podkategoria = <?php echo json_encode($podkategoria); ?>; 
var podkategorie =  <?php echo json_encode($podkategorie); ?>; 
var ilePodkategori =  <?php echo json_encode($ilePodkategori); ?>;

</script> 
    
    
<?php
    if(isset($_GET['id']))
    {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        $polaczenie->query("SET NAMES 'utf8'");
        $query = "select * from ogloszenia where id=".$_GET['id'];
        $rezultat = $polaczenie->query($query);
        $wiersz = $rezultat->fetch_assoc();
        if ($wiersz['id_wlasciciela'] != $_SESSION['id'] && $_SESSION['uprawnienia'] < 2)
        {

                header('Location: index.php');
                exit();

            
        }
        else
        {
            $_SESSION['id_ogloszenia'] = $wiersz['id'];
            $_SESSION['tytul'] = $wiersz['tytul'];
            $_SESSION['opis'] = $wiersz['opis'];
            $_SESSION['cena'] = $wiersz['cena'];
            $_SESSION['id_wlasciciela'] = $wiersz['id_wlasciciela'];
            $_SESSION['kontakt'] = $wiersz['kontakt'];
            $_SESSION['kategoria'] = $wiersz['kategoria'];
            $_SESSION['podkategoria'] = $wiersz['podkategoria'];
            $_SESSION['wojewodztwo'] = $wiersz['wojewodztwo'];
            $_SESSION['miejscowosc'] = $wiersz['miejscowosc'];
            $_SESSION['img'][0] = $wiersz['img1'];
            $_SESSION['img'][1] = $wiersz['img2'];
            $_SESSION['img'][2] = $wiersz['img3'];
            $_SESSION['img'][3] = $wiersz['img4'];
            $_SESSION['img'][4] = $wiersz['img5'];
            $_SESSION['img'][5] = $wiersz['img6'];
        }

    }          
?>

<script type="text/javascript">
    function myFunction()
    {
        var katValue = document.getElementById("selectKategoria").value;
        var a=0;
        for (i=0; i<ilePodkategori; i++)
        {
           var podzielone = podkategorie[i].split("/");
           if (podzielone[0] === katValue) a++;
        }
        var podkategorie2 = new Array(a);
        a=0;
        for (i=0; i<ilePodkategori; i++)
        {
           var podzielone = podkategorie[i].split("/");
           if (podzielone[0] === katValue)
           {
            podkategorie2[a] = podzielone[1];
            a++;
           }
        }
        document.getElementById("selectPodkategoria").innerHTML = '';
        for (i=0; i<a; i++)
        {
            if (podkategoria === podkategorie2[i])
            {
                document.getElementById("selectPodkategoria").innerHTML += '<option value="'+podkategorie2[i]+'" selected>'+podkategorie2[i]+'</option>'; 
            }
            else
            {
              document.getElementById("selectPodkategoria").innerHTML += '<option value="'+podkategorie2[i]+'">'+podkategorie2[i]+'</option>';  
            }
            
        }                
    }
    
    
    function usunZdj(a)
    {
        var str = "img"+a;
        var str2="inputImg"+a;
        document.getElementById(str).innerHTML = "";
        document.getElementById(str2).style.display = "block";
        var usun = "usun"+a;
        document.getElementById(str).innerHTML = '<input type="hidden" name="'+usun+'" value="true">';
        
        
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
                <div class="divm" style="color:red" id="div">
                    <?php
                        if (isset($_SESSION['e_blad'])) echo $_SESSION['e_blad'];
                    ?>
                </div>
                <form action="podglad_edycja.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_ogloszenia" value="<?php echo $_SESSION['id_ogloszenia'] ?>">
                    <input type="hidden" name="id_wlasciciela" value="<?php echo $_SESSION['id_wlasciciela'] ?>">
                    <div class="divm">
                        Tytul : </br>
                        <input class="tytulinput" type="text" name="tytul" value ="<?php if (isset($_SESSION['tytul'])) echo $_SESSION['tytul']; ?>" style="width:80%;"/>
                        <?php
			if (isset($_SESSION['e_tytul']))
			{
				echo '<div class="error">'.$_SESSION['e_tytul'].'</div>';
				unset($_SESSION['e_tytul']);
			}
                        ?>
                    </div>
                    
                    <div class="divm">
                        Opis : </br>
                        <textarea style="resize: none;width:80%" name="opis" rows="20"><?php if (isset($_SESSION['opis'])) echo $_SESSION['opis']; ?></textarea>
                    </div>
                    
                    <div class="divm">
                        Cena : </br><input class="cena" type="number" name="cena" value="<?php if (isset($_SESSION['cena'])) echo $_SESSION['cena']; ?>"/>
                    </div>
                    
                    <div class="divm">
                        Dane Kontaktowe : </br>
                        <textarea style="resize: none" name="kontakt" rows="5" cols="50"><?php if (isset($_SESSION['kontakt'])) echo $_SESSION['kontakt']; ?></textarea>
                    </div>
                    
                    <div class="divm">
                        Kategoria : </br>
                        <select  class="selectSort" name="kategoria" id="selectKategoria" onchange="myFunction()" style="width: 200px">
                                <?php 
                                    $sel_kategoria="Motoryzacja"; if (isset($_SESSION['kategoria'])) { $sel_kategoria = $_SESSION['kategoria']; }
                                    for ($i=0; $i<$ileKategori; $i++)
                                    {
                                        echo '<option value="'.$kategorie[$i].'"'; if ($sel_kategoria == $kategorie[$i]) { echo "selected";  } echo '>'.$kategorie[$i].'</option>';
                                    }
                                ?>                                   
                            </select>
                        
                    </div>
                    <div class="divm">
                        Podkategoria : </br>
                        <select  class="selectSort" name="podkategoria" id="selectPodkategoria" style="width: 200px">
                                                                   
                        </select>
                    </div>
                    <div class="divm">
                        Wojewodztwo : </br>
                        <select class="selectSort" name="wojewodztwo" style="width: 200px">
                            <?php $sel_wojewodztwo="dolnośląskie"; if (isset($_SESSION['wojewodztwo'])) { $sel_wojewodztwo = $_SESSION['wojewodztwo']; }  ?>
                            <option value="dolnośląskie" <?php if ($sel_wojewodztwo == "dolnośląskie") { echo "selected";  } ?> >dolnośląskie</option>
                            <option value="kujawsko-pomorskie" <?php if ($sel_wojewodztwo == "kujawsko-pomorskie") { echo "selected";  } ?> >kujawsko-pomorskie</option>
                            <option value="lubelskie" <?php if ($sel_wojewodztwo == "lubelskie") { echo "selected";  } ?> >lubelskie</option>
                            <option value="lubuskie" <?php if ($sel_wojewodztwo == "lubuskie") { echo "selected";  } ?> >lubuskie</option>
                            <option value="łódzkie" <?php if ($sel_wojewodztwo == "łódzkie") { echo "selected";  } ?> >łódzkie</option>
                            <option value="małopolskie" <?php if ($sel_wojewodztwo == "małopolskie") { echo "selected";  } ?> >małopolskie</option>
                            <option value="mazowieckie" <?php if ($sel_wojewodztwo == "mazowieckie") { echo "selected";  } ?> >mazowieckie</option>
                            <option value="opolskie" <?php if ($sel_wojewodztwo == "opolskie") { echo "selected";  } ?> >opolskie</option>
                            <option value="podkarpackie" <?php if ($sel_wojewodztwo == "podkarpackie") { echo "selected";  } ?> >podkarpackie</option>
                            <option value="podlaskie" <?php if ($sel_wojewodztwo == "podlaskie") { echo "selected";  } ?> >podlaskie</option>
                            <option value="pomorskie" <?php if ($sel_wojewodztwo == "pomorskie") { echo "selected";  } ?> >dolnośląskie</option>
                            <option value="śląskie" <?php if ($sel_wojewodztwo == "śląskie") { echo "selected";  } ?> >śląskie</option>
                            <option value="świętokrzyskie" <?php if ($sel_wojewodztwo == "świętokrzyskie") { echo "selected";  } ?> >świętokrzyskie</option>
                            <option value="warmińsko-mazurskie" <?php if ($sel_wojewodztwo == "warmińsko-mazurskie") { echo "selected";  } ?> >warmińsko-mazurskie</option>
                            <option value="wielkopolskie" <?php if ($sel_wojewodztwo == "wielkopolskie") { echo "selected";  } ?> >wielkopolskie</option>
                            <option value="zachodniopomorskie" <?php if ($sel_wojewodztwo == "zachodniopomorskie") { echo "selected";  } ?> >zachodniopomorskie</option>
                            
              

                        </select>
                    </div>
                    <div class="divm">
                        Miejscowosc : </br><input class="textinput" type="text" name="miejscowosc" value ="<?php if (isset($_SESSION['miejscowosc'])) echo $_SESSION['miejscowosc']; ?>" style="width:200px;"/>
                    </div>
                    
                    <div class="divm">
                        Zdjecie 1 - Miniatura :
                        
                        <?php
                            if (isset($_SESSION['img'][0]) && $_SESSION['img'][0] > 0)
                            {
                                echo '<div class="divm" id="img1"><img src="img/'.$_SESSION['img'][0].'" style="max-height: 200px">'
                                    . '</br><input type="button" onclick="usunZdj(1)" value="Usuń"/></div>'
                                        . '<div class="divm" id="inputImg1" style="display:none"><input type="file"  name="file[]"/></div>';
                            }
                            else
                            {
                                echo '</br><input type="file" name="file[]">';
                            }
                        ?>
                        

                    </div>
                    <div class="divm">
                        Zdjecie 2 : 
                        <?php
                            if (isset($_SESSION['img'][1]) && $_SESSION['img'][1] > 0)
                            {
                                echo '<div class="divm" id="img2"><img src="img/'.$_SESSION['img'][1].'" style="max-height: 200px">'
                                    . '</br><input type="button" onclick="usunZdj(2)" value="Usuń"/></div>'
                                        . '<div class="divm" id="inputImg2" style="display:none"><input type="file"  name="file[]"/></div>';
                            }
                            else
                            {
                                echo '</br><input type="file" name="file[]">';
                            }
                        ?>

                    </div>
                    <div class="divm">
                        Zdjecie 3 : 
                        <?php
                            if (isset($_SESSION['img'][2]) && $_SESSION['img'][2] > 0)
                            {
                                echo '<div class="divm" id="img3"><img src="img/'.$_SESSION['img'][2].'" style="max-height: 200px">'
                                    . '</br><input type="button" onclick="usunZdj(3)" value="Usuń"/></div>'
                                        . '<div class="divm" id="inputImg3" style="display:none"><input type="file"  name="file[]"/></div>';
                            }
                            else
                            {
                                echo '</br><input type="file" name="file[]">';
                            }
                        ?>

                    </div>
                    <div class="divm">
                        Zdjecie 4 : 
                        <?php
                            if (isset($_SESSION['img'][3]) && $_SESSION['img'][3] > 0)
                            {
                                echo '<div class="divm" id="img4"><img src="img/'.$_SESSION['img'][3].'" style="max-height: 200px">'
                                    . '</br><input type="button" onclick="usunZdj(4)" value="Usuń"/></div>'
                                        . '<div class="divm" id="inputImg4" style="display:none"><input type="file"  name="file[]"/></div>';
                            }
                            else
                            {
                                echo '</br><input type="file" name="file[]">';
                            }
                        ?>

                    </div>
                    <div class="divm">
                        Zdjecie 5 : 
                        <?php
                            if (isset($_SESSION['img'][4]) && $_SESSION['img'][4] > 0)
                            {
                                echo '<div class="divm" id="img5"><img src="img/'.$_SESSION['img'][4].'" style="max-height: 200px">'
                                    . '</br><input type="button" onclick="usunZdj(5)" value="Usuń"/></div>'
                                        . '<div class="divm" id="inputImg5" style="display:none"><input type="file"  name="file[]"/></div>';
                            }
                            else
                            {
                                echo '</br><input type="file" name="file[]">';
                            }
                        ?>

                    </div>
                    <div class="divm">
                        Zdjecie 6 : 
                        <?php
                            if (isset($_SESSION['img'][5]) && $_SESSION['img'][5] > 0)
                            {
                                echo '<div class="divm" id="img6"><img src="img/'.$_SESSION['img'][5].'" style="max-height: 200px">'
                                    . '</br><input type="button" onclick="usunZdj(6)" value="Usuń"/></div>'
                                        . '<div class="divm" id="inputImg6" style="display:none"><input type="file"  name="file[]"/></div>';
                            }
                            else
                            {
                                echo '</br><input type="file" name="file[]">';
                            }
                        ?>

                    </div>
                    
                    <div class="divm">
                        <input class="submit" type="submit"  name="submit" value="Podglad" >
                    </div>
                    
                </form> 
                
                
                
            </div>  
            
            <div class="blok" id="podglad">
                
            </div>
            		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
			
	
	</div>
	
	<?php echo '<script type="text/javascript">',
     'myFunction();',
     '</script>'; 
	 ?>
	
</body>
</html>
