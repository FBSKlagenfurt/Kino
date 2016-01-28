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
    <script language="javascript" src="/Scripts/app.js"></script>
  <title>Star Movies</title>
</head>
<body onload="init()">
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
            <table>
    <tbody>
        <tr>
            <td colspan="2">
                <h1 style="margin-left:auto;margin-right:auto;">Vorstellungen</h1>
            </td>
        </tr>
        <tr>
            <td>Kino:</td>
            <td>
                <?php
                    require_once("getSqlConnection.php");
                    $sqlcon = getSqlCon();
                    $x = $sqlcon->prepare("SELECT id, Kinoname FROM t_Kino;");
                    $x->execute();
                    $x->bind_result($id,$Kinoname);
                    echo "<select name='Kinoname' id='KiNa'>";
                    while($x->fetch())
                    {
                     
                        echo "<option value='".$id."'>".$Kinoname."</option>";   

                    }
                    echo "</select>";
                    $sqlcon->close();  
                ?>
            </td>
        </tr>
        <tr>
            <td>Saal:</td>
            <td>                
                   <?php
                        echo "<select name='Saalname' id='Saalname'>";            
                        echo "</select>";                                    
                    ?>
            </td>
        </tr>
        <tr>
            <td>Film:</td>
            <td>
                <?php
                    require_once("getSqlConnection.php");
                    $sqlcon = getSqlCon();
                    $x = $sqlcon->prepare("SELECT id, Titel FROM t_film;");
                    $x->execute();
                    $x->bind_result($id,$Titel);
                    echo "<select name='Titel' id='FiNa'>";
                    while($x->fetch())
                    {
                     
                        echo "<option value='".$id."'>".$Titel."</option>";   

                    }
                    echo "</select>";
                    $sqlcon->close();  
                ?>
            </td>
        </tr>
        <tr>
            <td>Dauer:</td>
            <td>                
                    <?php
                        echo "<output name='Dauer' id='Dauer'>";            
                        echo "</output>";                                    
                    ?>
            </td>
        </tr>

         <tr>
            <td>Beginnt um: </td>
            <td><input type="text" id="beginnZeit" /></td>
        </tr>


        <tr>
            <td>Zeit von/bis</td>
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