<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    $IsLoggedID = isManagerLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://fonts.googleapis.com/css?family=Signika:300,600&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css" /><link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,800italic,800" rel="stylesheet" type="text/css" />
    <link href="Style/base.css" rel="stylesheet" type="text/css" />
    <script src="/Scripts/jquery.js" language="javascript"></script>
  <title>Star Movies</title>
</head>
<body>
</div>
    <div class="header">
      <div class="page">
         <div class="title">
            <a href="/"><img src="/Images/title.png" alt="Star Movies Logo" width="200px" height="100px" /></a>
         </div>
         <div class="menu">
                <div class="topmenu">
                    <a href="/Login.php"><img src="/Images/login.png" style="vertical-align:middle"; width="24px" height="24px" /><span style="line-height:24px; vertical-align:middle; font-size:15px;"><?php if($IsLoggedID)echo "Abmelden"; else echo "Anmelden";?></span></a>
                </div>
                <div class="clear"></div>
                <div class="mainmenu">
                    <div class="menuentry">
                        <a href="/">Start</a>
                    </div>
                    <div class="menuentry">
                        <a href="MovieOverview.php">Filme</a>
                    </div>
                    <div class="menuentry">
                        <a href="CinemaOverview.php">Kinos</a>
                    </div>
                    <?php if($IsLoggedID) echo ' 
                    <div class="selmenuentry">
                        <a href="/ManageOverview.php">Verwaltung</a>
                    </div>' ?>
                </div>
         </div>
      </div>
    </div>
    

    <div class="page">
        <div class="main">
         <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Vorstellungen<button onclick="location.href='/editPerformance.php'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Kinoname
                    </th>
                    <th>
                        Saal
                    </th>
                    <th>
                        Film
                    </th>
                    <th>
                        Dauer
                    </th>
                    <th>
                        Vorstellungsdatum
                    </th>
                    <th>
                        Filmbeginn
                    </th>
                    <th>

                    </th>
                </thead>
                <tbody>
                    <?php
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM v_filmauffuerung");
                         $x->execute();
                         $x->bind_result($Kinoname, $Saalname, $Filmname, $Dauer, $Filmbeginndat, $Filmbeginn, $VorstellungsID);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Kinoname</td><td>$Saalname</td><td>$Filmname</td><td>$Dauer</td><td>$Filmbeginndat</td><td>$Filmbeginn</td><td><button onclick=\"location.href='/editPerformance.php?vid=".$VorstellungsID."'\">Bearbeiten</button><button onclick=\"location.href='/editPerformance.php'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>

            
  <div class="Teaser">
   <div class="col1">
       </div>
   <div class="col2">
     <br /><br />
   </div>
  </div>   
  <div class="clear"></div>
  <br />
        </div>
        <div class="clear">
        </div>
    </div>
    <div class="clear"></div>
    <br /><br /><br />
    <div class="footer">
      <div class="page">
        <div class="col1">
            <b>Ticket Reservieren</b><br /><br />
            <a href="/login.php">Login</a><br />
            <a href="/">FAQ</a>
        </div>
        <div class="col2">
            <b>FILME</b><br /><br />
            <a href="/">Jetzt im Kino</a><br />
            <a href="/">Bald im Kino</a><br />
            <a href="/">IMAX</a><br />
            <a href="/">Trailer</a><br />
            <br />
            <b><a href="/Impressum.aspx">IMPRESSUM</a></b><br /><br />
            <b><a href="../Images/AGB.pdf" target="_blank">AGB</a></b><br />
        </div>
        <div class="col3">
            <b>KONTAKT</b><br /><br />
            Star Movies GmbH<br />
            Justastreet 1<br />
            A-9500 Villach<br />
            +43 4242 12345 Fax: DW-99<br />
            office@starmovies.test<br />
        </div>
      </div>
    </div>
    </form>
</body>
</html>