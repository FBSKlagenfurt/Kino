<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    $IsLoggedID = isLoggedIn();
    if(!$IsLoggedID)
    {
        session_start();
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
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
                        <a href="/">Filme</a>
                    </div>
                    <div class="menuentry">
                        <a href="/">Kinos</a>
                    </div>
                    <div class="menuentry">
                        <a href="/">Kontakt</a>
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
            <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Kinos<button onclick="location.href='/editCinema.php'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Kinoname
                    </th>
                    <th>
                        Tel Nr.
                    </th>
                    <th>
                        Straße
                    </th>
                    <th>
                        PLZ
                    </th>
                    <th>
                        Ort
                    </th>
                    <th>
                    </th>
                </thead>
                <tbody>
                    <?php
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM v_Kino");
                         $x->execute();
                         $x->bind_result($ID, $Kinoname, $TelNr, $Strasse, $PLZ, $Ort);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Kinoname</td><td>$TelNr</td><td>$Strasse</td><td>$PLZ</td><td>$Ort</td><td><button onclick=\"location.href='/editCinema.php?id=$ID'\">Bearbeiten</button><button onclick=\"location.href='/editCinema.php?delid=$ID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>
            <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Filme<button onclick="location.href='/editMovie.php?id=$ID'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Filmtitel
                    </th>
                    <th>
                        Dauer in Minuten
                    </th>
                    <th>
                        Preis in €
                    </th>
                    <th>
                        Kurzbeschreibung
                    </th>
                    <th>
                    </th>
                </thead>
                <tbody>
                    <?php
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM t_Film");
                         $x->execute();
                         $x->bind_result($ID, $Titel, $Beschreibung, $Dauer, $Preis);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Titel</td><td>$Dauer</td><td>$Preis</td><td>$Beschreibung</td><td><button onclick=\"location.href='/editMovie.php?id=$ID'\">Bearbeiten</button><button onclick=\"location.href='/editMovie.php?delid=$ID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>
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