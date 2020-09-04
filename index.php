<?php
    session_start();
    if (isset($_SESSION['niepotwierdzony'])) session_unset();
    if (isset($_POST['wojewodztwo'])) $_SESSION['wojewodztwo'] = $_POST['wojewodztwo'];
    
    if (isset($_GET['start']))
    {
    
            
            unset($_SESSION['id_ogloszenia']);
            unset($_SESSION['tytul']);
            unset($_SESSION['opis']);
            unset($_SESSION['cena']);
            unset($_SESSION['id_wlasciciela']);
            unset($_SESSION['kontakt']);
            unset($_SESSION['kategoria']);
            unset($_SESSION['podkategoria']);
            unset($_SESSION['wojewodztwo']);
            unset($_SESSION['miejscowosc']);
            unset($_SESSION['img']);
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
    

    
    if (isset($_POST['szukane']))
    {
        $czySzukane = 0;
        $where = "";
        if (strlen($_POST['szukane'])>0)
        {
            $czySzukane = 1;
            $szukane = explode(" ",$_POST['szukane']);
            $ileWyrazow = count($szukane);


            for ($i=0; $i<$ileWyrazow; $i++)
            {
                if (strlen($szukane[$i])>0)
                {
                    $szukane2[]=$szukane[$i];
                }
            }
            $ileWyrazow2 = count($szukane2);


            for ($i=0; $i<$ileWyrazow2; $i++)
            {
                if (isset($_POST['szukaj_w_opisach']))
                {
                    $where .= " (tytul like '%".$szukane2[$i]."%' or opis like'%".$szukane2[$i]."%') ";
                }
                else
                {                
                    $where .= "tytul like '%".$szukane2[$i]."%'";
                }
                if ($i < $ileWyrazow2-1) $where.= " and ";
            }
        }
        if ($czySzukane == 0) $where.= " 1 ";
        
        if (isset($_POST['wojewodztwo']))
        {
            if ($_POST['wojewodztwo'] != "Wszystkie") $where .= " and wojewodztwo = '".$_POST['wojewodztwo']."'";
        }
        
        if (isset($_POST['miasto']))
        {
            if (strlen($_POST['miasto']) > 0) $where .= " and miejscowosc = '".$_POST['miasto']."'";
        }
        
        if (isset($_POST['cena_od']))
        {
            if ($_POST['cena_od'] > 0) $where .= " and cena > ".$_POST['cena_od'];
        }
        if (isset($_POST['cena_do']))
        {
            if ($_POST['cena_do'] > 0) $where .= " and cena < ".$_POST['cena_do'];
        }
        
        if (isset($_POST['cena_do']))
        {
            if ($_POST['cena_do'] > 0) $where .= " and cena < ".$_POST['cena_do'];
        }
        
        if (isset($_POST['kategoria']))
        {
            $where .= " and kategoria = '".$_POST['kategoria']."'";
        }
        if (isset($_POST['podkategoria']))
        {
            $where .= " and podkategoria = '".$_POST['podkategoria']."'";
        }
        

        
        if ($_POST['sortuj'] == "najnowsze") $orderby = " order by data_dodania desc";
        if ($_POST['sortuj'] == "najtansze") $orderby = " order by cena asc";
        if ($_POST['sortuj'] == "najdrozsze") $orderby = " order by cena desc";
        
        
        
        //   (select * from (select * from (select * from ogloszenia where tytul like %a%)as a where tytul like %b%) as b where tytul like %c%) as c
        
        $query = "SELECT id, tytul,cena,wojewodztwo,miejscowosc,img1,data_dodania FROM `ogloszenia` where ".$where.$orderby;

        $rezultat = $polaczenie->query($query);
        $ileOgloszen = $rezultat->num_rows;
        for ($i=0; $i<$ileOgloszen; $i++)
        {
            $wiersz = $rezultat->fetch_assoc();
            $ogloszenia[$i] = $wiersz;

        } 
    }
    else
    {
        $where=" where 1 ";
        if (isset($_GET['kategoria']))
        {
            $where .= " and kategoria = '".$_GET['kategoria']."'";
        }
        if (isset($_GET['podkategoria']))
        {
            $where .= " and podkategoria = '".$_GET['podkategoria']."'";
        }
        
        $rezultat = $polaczenie->query("SELECT id, tytul,cena,wojewodztwo,miejscowosc,img1,data_dodania FROM `ogloszenia` ".$where." order by data_dodania desc");
        $ileOgloszen = $rezultat->num_rows;
        for ($i=0; $i<$ileOgloszen; $i++)
        {
            $wiersz = $rezultat->fetch_assoc();
            $ogloszenia[$i] = $wiersz;

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

<body onload="podkatFind()">
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
                if (isset($_SESSION['dodano_ogloszenie'])) echo '<div class="blok" style="color: orange">Dodano ogloszenie!</div>';
                unset($_SESSION['dodano_ogloszenie']);
                if (isset($_SESSION['usunieto_ogloszenie'])) echo '<div class="blok" style="color: orange">Usunieto ogloszenie!</div>';
                unset($_SESSION['usunieto_ogloszenie']);
                if (isset($_SESSION['zmieniono_ogloszenie'])) echo '<div class="blok" style="color: orange">Zmieniono ogloszenie!</div>';
                unset($_SESSION['zmieniono_ogloszenie']);
                if (isset($_SESSION['haslo_zmienione'])) echo '<div class="blok" style="color: orange">haslo_zmienione!</div>';
                unset($_SESSION['haslo_zmienione']);
                if (isset($_SESSION['email_zmieniony'])) echo '<div class="blok" style="color: orange">email_zmieniony!</div>';
                unset($_SESSION['email_zmieniony']);
                 if (isset($_SESSION['login_zmieniony'])) echo '<div class="blok" style="color: orange">login_zmieniony!</div>';
                unset($_SESSION['login_zmieniony']);
                 if (isset($_SESSION['wyslano_haslo'])) echo '<div class="blok" style="color: orange">'.$_SESSION['wyslano_haslo'].'!</div>';
                unset($_SESSION['wyslano_haslo']);
                 if (isset($_SESSION['zbanowany'])) echo '<div class="blok" style="color: orange">zbanowany!</div>';
                unset($_SESSION['zbanowany']);
                if (isset($_SESSION['uprawnienia_zmienione'])) echo '<div class="blok" style="color: orange">uprawnienia_zmienione!</div>';
                unset($_SESSION['uprawnienia_zmienione']);
                if (isset($_SESSION['odbanowany'])) echo '<div class="blok" style="color: orange">odbanowany!</div>';
                unset($_SESSION['odbanowany']);
            ?>

            <div class="blok" style="text-align: left">
                <form action="index.php" method="post">
                    <div class="divm">
                        
                        <div >
                            <input type="search" placeholder="Szukaj" class="pole_wyszukiwarki" name="szukane" value="<?php if (isset($_POST['szukane'])) echo $_POST['szukane'];  ?>">
                            <input class="searchSumbit" type="submit" value="Szukaj">
                             
                            
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                    <div class="divm" style="margin-left: 20px;">
                        
                            Cena Od: <input class="cena" type="number" name="cena_od" value="<?php if (isset($_POST['cena_od'])) echo $_POST['cena_od'];  ?>">
                            Cena Do: <input class="cena" type="number" name="cena_do" value="<?php if (isset($_POST['cena_do'])) echo $_POST['cena_do'];  ?>" >
                            Sortuj : 
                            <select style="width: 150px" class="selectSort" name="sortuj">
                                <?php $sortuj="najnowsze"; if (isset($_POST['sortuj'])) { $sortuj = $_POST['sortuj']; }  ?>
                                <option value="najnowsze" <?php if ($sortuj == "najnowsze") { echo "selected";  } ?> >Najnowsze</option>
                                <option value="najtansze" <?php if ($sortuj == "najtansze") { echo "selected";  }   ?> >Najtansze</option>
                                <option value="najdrozsze" <?php if ($sortuj == "najdrozsze") { echo "selected"; }  ?> >Najdrozsze</option>
                            </select>
                            <label> <input class="czekbox" type="checkbox" <?php if (isset($_POST['szukaj_w_opisach'])) echo "checked";  ?> name="szukaj_w_opisach"> Szukaj rownież w opisach</label>
                            <?php $kat=0; $podkat=0;
                             if (isset($_GET['kategoria'])) { echo '<input type="hidden" name="kategoria" value="'.$_GET['kategoria'].'">'; $kat = 1; }  
                             if (isset($_GET['podkategoria'])) { echo '<input type="hidden" name="podkategoria" value="'.$_GET['podkategoria'].'">'; $podkat=1; } 
                             if (isset($_POST['kategoria']) && $kat == 0) echo '<input type="hidden" name="kategoria" value="'.$_POST['kategoria'].'">';  
                             if (isset($_POST['podkategoria']) && $podkat == 0) echo '<input type="hidden" name="podkategoria" value="'.$_POST['podkategoria'].'">';  
                             ?>
                            
                            </div>
                    <div class="divm" style="margin-left: 20px;">
                        
                
                      
                            <div class="divm">
                            Wojewodztwo: 
                            <select class="selectSort" name="wojewodztwo" style="width: 200px">
                                <?php $sel_wojewodztwo="Wszystkie"; if (isset($_SESSION['wojewodztwo'])) { $sel_wojewodztwo = $_SESSION['wojewodztwo']; }  ?>
                                <option value="Wszystkie" <?php if ($sel_wojewodztwo == "Wszystkie") { echo "selected";  } ?> >Wszystkie</option>
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
                                <option value="pomorskie" <?php if ($sel_wojewodztwo == "pomorskie") { echo "selected";  } ?> >pomorskie</option>
                                <option value="śląskie" <?php if ($sel_wojewodztwo == "śląskie") { echo "selected";  } ?> >śląskie</option>
                                <option value="świętokrzyskie" <?php if ($sel_wojewodztwo == "świętokrzyskie") { echo "selected";  } ?> >świętokrzyskie</option>
                                <option value="warmińsko-mazurskie" <?php if ($sel_wojewodztwo == "warmińsko-mazurskie") { echo "selected";  } ?> >warmińsko-mazurskie</option>
                                <option value="wielkopolskie" <?php if ($sel_wojewodztwo == "wielkopolskie") { echo "selected";  } ?> >wielkopolskie</option>
                                <option value="zachodniopomorskie" <?php if ($sel_wojewodztwo == "zachodniopomorskie") { echo "selected";  } ?> >zachodniopomorskie</option>                                     
                            </select>
                            Miasto: <input type="text" class="selectSort" name="miasto" value="<?php if (isset($_POST['miasto'])) echo $_POST['miasto'];  ?>" >
                        </div>
                            
                        

                    </div>
                </form>
                <div style="clear:both;"></div>
                <!--</form>-->
            </div>
            
            
            <div class="blok">
                <?php
                    for ($i=0; $i<$ileKategori; $i++)
                    {
                        echo '<div class="kategoria">'
                        . '<a href="?kategoria='.$kategorie[$i].'">'   .$kategorie[$i].   '</a>'
                        . '</div>';                       
                    }
                ?>

                <div style="clear:both;"></div>

            </div>
               
                    

                <?php
                $kategoriaWyswietlona = 0;
                    if (isset($_POST['kategoria']))
                        {
                            echo '<div class="blok" style="color:orange">';
                        echo $_POST['kategoria'];
                        if (isset($_POST['podkategoria']))
                        {
                            echo " / ".$_POST['podkategoria'];
                        }
                        echo '</div>';
                        $kategoriaWyswietlona = 1;
                        }
                    if (isset($_GET['kategoria']))
                    {
                        if ($kategoriaWyswietlona == 0)
                        {
                            echo '<div class="blok" style="color:orange">';
                            echo $_GET['kategoria'];
                            if (isset($_GET['podkategoria']))
                            {
                                echo " / ".$_GET['podkategoria'];
                            }
                            echo '</div>';
                        }                       
                        

                        $a=0;
                        for ($i=0; $i<$ilePodkategori; $i++)
                        {                           
                            $str2 = explode("/",$podkategorie[$i]);
                            if ($str2[0] == $_GET['kategoria'])
                            {
                                $a++;
                                $podkategorie2[]=$str2[1];
                            }
                        
                        }
                        if (isset($podkategorie2))
                        {
                            echo '<div class="blok">';
                            for ($i=0; $i<$a; $i++)
                            {
                                echo '<div class="kategoria"><a href="?kategoria='.$_GET['kategoria'].'&podkategoria='.$podkategorie2[$i].'">'.$podkategorie2[$i].'</a></div>';       
                            }
                            echo '<div style="clear:both;"></div></div>';
                        }
                        
                    }
                ?>
            
               <?php
			   if ($ileOgloszen <1)
					{
							echo '<div class="blok">Brak Wyników</div>';
					}
					else
					{
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
					}
               ?>
            
		
		
		
            <div class="footer">
                    KupieSprzedam.pl - najlepszy darmowy serwis ogłoszeniowy. Strona w sieci od 2019r. &copy; Wszelkie prawa zastrzeżone
            </div>
	
	</div>
	
</body>
</html>