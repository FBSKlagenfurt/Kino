<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    $IsLoggedID = isManagerLoggedIn();
    if(isset($_GET["cid"]))
    {
        $cid = $_GET["cid"];
    }
    if(!$IsLoggedID)
    {
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
    else if(isset($_POST["Name"]) && isset($_POST["Rows"]) && isset($_POST["Seats"]))
    {
        require_once("getSqlConnection.php");
        $myval = 0;
        if(isset($_POST["HallID"]))
            $myval = $_POST["HallID"];
        if(isset($_POST["CineID"]))
        {
            $myval4 = $_POST["CineID"];
        }
        if(isset($myval) && $myval > 0 || isset($myval4) &&  $myval4 > 0)
        {
            $myval1 = $_POST["Name"];
            $myval2 = $_POST["Rows"];
            $myval3 = $_POST["Seats"];
            $sqlcon = getSqlCon();
            $x = $sqlcon->prepare("CALL p_ManipulateHall (?, ?, ?, ?, ?)");
            $x->bind_param("isiii", $myval, $myval1, $myval2, $myval3, $myval4);
            $result = $x->execute();
            $sqlcon->close();
        }
        redirect('/EditCinema.php?id='. $myval4);
    }
    else if(!isset($cid) && isset($_GET["delid"]))
    {    
        redirect('/ManageOverview.php');
    }
    else if(isset($_GET["id"]))
    {
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT * FROM t_Saal WHERE ID = ?");
        $mypar = $_GET["id"];
        $x->bind_param("i", $mypar);
        $x->execute();
        $x->bind_result($ID, $cid, $Name, $Rows, $Seats);
        $x->fetch();
        $sqlcon->close();
    }
    else if(isset($_GET["delid"]) && isset($cid))
    {
        require_once("getSqlConnection.php");
        $delId = $_GET["delid"];
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_DeleteHall( ? )");
        $x->bind_param("i", $delId);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/EditCinema.php?id='. $cid);
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
            <input type="hidden" name="HallID" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <input type="hidden" name="CineID" value="<?PHP echo $cid; ?>">
            <table>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h1 style="margin-left:auto;margin-right:auto;">Saal Bearbeiten</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td>
                            <input name="Name" type="text" value="<?php if(isset($_GET['id'])) echo $Name; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Reihe:</td>
                        <td>
                            <input name="Rows" type="text" value="<?php if(isset($_GET['id'])) echo $Rows; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Sitz:</td>
                        <td>
                            <input name="Seats" type="text" value="<?php if(isset($_GET['id'])) echo $Seats; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width:120px;">
                            <input class="submitbutton" type="submit" value="Speichern"/>
                        </td>
                        <td>
                            <input class="submitbutton" type="button" value="Abbrechen" onclick="location.href='/EditCinema.php?id=<?php echo $cid ?>'" />
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
            <a href="tel:+43424212345">+43 4242 12345</a> <a href="fax:+4342421234599">Fax: DW-99</a><br />
            <a href="mailto:office@starmovies.test">office@starmovies.test</a><br />
        </div>
      </div>
    </div>
    </form>
</body>
</html>