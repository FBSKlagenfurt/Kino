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
    else if(isset($_POST["Vorname"]) && isset($_POST["Nachname"]) && isset($_POST["Benutzername"]) && isset($_POST["E-Mail"]) && isset($_POST["Strasse"]) && isset($_POST["PLZ"]) && isset($_POST["Ort"]) && isset($_POST["Typ"]))
    {
        require_once("getSqlConnection.php");
        require_once("hash.php");
        if(isset($_POST["mid"]))
            $myval = $_POST["mid"];
        else 
            $myval = 0;
        $myval1 = $_POST["Vorname"];
        $myval2 = $_POST["Nachname"];
        $myval3 = $_POST["Benutzername"];
        $myval4 = $_POST["E-Mail"];
        $myval5 = $_POST["Strasse"];
        $myval6 = $_POST["PLZ"];
        $myval7 = $_POST["Ort"];
        $myval8 = $_POST["Typ"];
        if(isset($_POST["Passwort"]))
            $myval9 =  HashPassword($_POST["Passwort"]);
        else
            $myval9 = '';
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_ManipulateUser (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $x->bind_param("issssissss", $myval, $myval3, $myval5, $myval6, $myval7, $myval8, $myval4, $myval1, $myval2, $myval9);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/ManageOverview.php');
    }
    else if(isset($_GET["id"]))
    {
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT * FROM v_mitarbeiter WHERE ID = ?");
        $mypar = $_GET["id"];
        $x->bind_param("i", $mypar);
        $x->execute();
        $x->bind_result($ID, $Benutzername, $Vorname, $Nachname, $EMail, $Strasse, $PLZ, $Ort, $Typ);
        $x->fetch();
        $sqlcon->close();
    }
    else if(isset($_GET["delid"]))
    {
        require_once("getSqlConnection.php");
        $delId = $_GET["delid"];
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_DeleteUser( ? )");
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
            <form id="cinemaForm" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="mid" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <table>
                <tbody>
                    <tr>
                        <td>Benutzername:</td>
                        <td>
                            <input name="Benutzername" type="text" value="<?php if(isset($_GET['id'])&&isset($Benutzername)) echo $Benutzername ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Passwort:</td>
                        <td>
                            <input name="Passwort" type="password" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>Passwort wiederholen:</td>
                        <td>
                            <input name="Passwort" type="password" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>E-Mail:</td>
                        <td>
                            <input name="E-Mail" type="text" value="<?php if(isset($_GET['id'])&&isset($EMail)) echo $EMail ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Vorname:</td>
                        <td>
                            <input name="Vorname" type="text" value="<?php if(isset($_GET['id'])&&isset($Vorname)) echo $Vorname ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Nachname:</td>
                        <td>
                            <input name="Nachname" type="text" value="<?php if(isset($_GET['id'])&&isset($Nachname)) echo $Nachname ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Strasse:</td>
                        <td>
                            <input name="Strasse" type="text" value="<?php if(isset($_GET['id'])&&isset($Strasse)) echo $Strasse ?>"></input>
                        </td>
                    </tr>
                     <tr>
                        <td>PLZ:</td>
                        <td>
                            <input name="PLZ" type="text" value="<?php if(isset($_GET['id'])&&isset($PLZ)) echo $PLZ ?>"></input>
                        </td>
                    </tr>
                     <tr>
                        <td>Ort:</td>
                        <td>
                            <input name="Ort"type="text" value="<?php if(isset($_GET['id'])&&isset($Ort)) echo $Ort ?>"></input>
                        </td>
                    </tr>
                     <tr>
                        <td>Typ:</td>
                        <td>
                            <?php
                                 require_once("getSqlConnection.php");
                                 $sqlcon = getSqlCon();
                                 $x = $sqlcon->prepare("SELECT id, Typ FROM t_typ;");
                                 $x->execute();
                                 $x->bind_result($id,$Typ);
                                 echo "<select name='Typ'>";
                                 while($x->fetch())
                                 {
                                     if($_GET['id'])
                                        $selected = 'selected';
                                     else 
                                        $selected = '';
                                     echo "<option " . $selected ." value='".$id."'>".$Typ."</option>";   

                                }
                                echo "</select>";
                                $sqlcon->close();  
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width:120px;">
                            <input class="submitbutton" type="submit" value="Speichern"/>
                        </td>
                        <td>
                            <input class="submitbutton" type="button" value="Abbrechen" onclick="location.href='/ManageOverview.php'" />
                        </td>
                    </tr>
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
            <b><a href="/AGB.pdf" target="_blank">AGB</a></b><br />
        </div>
        <div class="col3">
            <b>KONTAKT</b><br /><br />
            Star Movies GmbH<br />
            <a href="maps:address=Hauptplatz 1, A-9500 Villach, Austria">Justastreet 1<br />
            A-9500 Villach<br />
            Austria<br />
            </a>
            <a href="tel:+43424212345">+43 4242 12345</a> Fax: <a href="fax:+4342421234599">DW-99</a><br />
            <a href="mailto:office@starmovies.test">office@starmovies.test</a><br />
        </div>
      </div>
    </div>
    </form>
</body>
</html>