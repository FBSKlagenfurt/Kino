<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    $IsLoggedID = isManagerLoggedIn();
    if(!$IsLoggedID)
    {
        session_start();
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
    else if(isset($_POST["Kinoname"]) && isset($_POST["Str"]) && isset($_POST["PLZ"]) && isset($_POST["Ort"]))
    {
        require_once("getSqlConnection.php");
        if(isset($_POST["cineid"]))
            $myval = $_POST["cineid"];
        else 
            $myval = 0;
        $myval1 = $_POST["Kinoname"];
        $myval2 = $_POST["Str"];
        $myval3 = $_POST["PLZ"];
        $myval4 = $_POST["Ort"];
        $myval5 = $_POST["Tel"];
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_ManipulateCinema (?, ?, ?, ?, ?, ?)");
        $x->bind_param("isssss", $myval, $myval1, $myval2, $myval3, $myval4, $myval5);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/ManageOverview.php');
    }
    else if(isset($_GET["id"]))
    {
        $cid = $_GET["id"];
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT * FROM v_Kino WHERE ID = ?");
        $mypar = $_GET["id"];
        $x->bind_param("i", $mypar);
        $x->execute();
        $x->bind_result($ID, $Kinoname, $TelNr, $Strasse, $PLZ, $Ort);
        $x->fetch();
        $sqlcon->close();
    }
    else if(isset($_GET["delid"]))
    {
        require_once("getSqlConnection.php");
        $delId = $_GET["delid"];
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_DeleteCinema( ? )");
        $x->bind_param("i", $delId);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/ManageOverview.php');
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
            <form id="cinemaForm" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="cineid" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <table>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h1 style="margin-left:auto;margin-right:auto;">Kino Bearbeiten</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>Kinoname:</td>
                        <td>
                            <input name="Kinoname" type="text" value="<?php if(isset($_GET['id'])) echo $Kinoname ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Tel:</td>
                        <td>
                            <input name="Tel" type="text" value="<?php if(isset($_GET['id'])) echo $TelNr ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Straße:</td>
                        <td>
                            <input name="Str" type="text" value="<?php if(isset($_GET['id'])) echo $Strasse ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>PLZ:</td>
                        <td>
                            <input name="PLZ" type="text" value="<?php if(isset($_GET['id'])) echo $PLZ ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Ort:</td>
                        <td>
                            <input name="Ort" type="text" value="<?php if(isset($_GET['id'])) echo $Ort ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width:120px;">
                            <input class="submitbutton" type="submit" value="Speichern"/>
                        </td>
                        <td>
                            <input class="submitbutton" type="button" value="Zurück" onclick="location.href='/ManageOverview.php'" />
                        </td>
                    </tr>
                </tbody>
            </table>
             <?php if(isset($_GET["id"])){ echo '
             <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Säle in '. $Kinoname .'<button type="button"; onclick="location.href=\'/EditCinemahall.php?cid='. $cid .'\'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Saalname
                    </th>
                    <th>
                        Reihen
                    </th>
                    <th>
                        Sitze/Reihe
                    </th>
                    <th>
                    </th>
                </thead>
                <tbody>';
                    
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT ID, Saalname, Reihe, Sitze FROM t_Saal WHERE KinoID = ?");
                         $x->bind_param("i", $_GET['id']);
                         $x->execute();
                         $x->bind_result($ID, $Saalname, $Row, $Seats);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Saalname</td><td>$Row</td><td>$Seats</td><td><button type=\"button\" onclick=\"location.href='/editCinemahall.php?cid=$cid&id=$ID'\">Bearbeiten</button><button type=\"button\" onclick=\"location.href='/editCinemahall.php?cid=$cid&delid=$ID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                   
                echo '</tbody>
            </table>';} ?>
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
            <b><a href="/AGB.pdf" target="_blank">AGB</a></b><br />
        </div>
        <div class="col3">
            <b>KONTAKT</b><br /><br />
            Star Movies GmbH<br />
            <a href="maps:address=Hauptplatz 1, A-9500 Villach, Austira">Justastreet 1<br />
            A-9500 Villach<br />
            Austria<br />
            </a>
            <a href="tel:+43424212345">+43 4242 12345</a> <a href="fax:+4342421234599">Fax: DW-99</a><br />
            <a href="mailto:office@starmovies.test">office@starmovies.test</a><br />
        </div>
      </div>
    </div>
    </form>
</body>
</html>