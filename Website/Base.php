<?php
    session_start();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Star Movies</title>
    </head>
    <body>
    	<?php
            set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary"); 
            require_once("general.php");
            /*
            require_once("hash.php");
            require_once("getSqlConnection.php");
            $sqlcon = getSqlCon();
            $x = $sqlcon->prepare("INSERT INTO `t_Typ` (`Typ` ) VALUES (?)"); 
            $myval1 = 'Manager';
            $x->bind_param("s", $myval1);
            $x->execute();
            $x = $sqlcon->prepare("INSERT INTO `t_Land` (`Land` ) VALUES (?)"); 
            $myval1 = 'Österreich';
            $x->bind_param("s", $myval1);
            $x->execute();
            $x = $sqlcon->prepare("INSERT INTO `t_Land` (`Land` ) VALUES (?)"); 
            $myval1 = 'Österreich';
            $x->bind_param("s", $myval1);
            $x->execute();
            $x = $sqlcon->prepare("INSERT INTO `t_Stadt` (`PLZ`, `Ort`, `LandID` ) VALUES (?, ?, ?)"); 
            $myval1 = '9500';
            $myval2 = 'Villach';
            $myval3 = 1;
            $x->bind_param("ssi", $myval1, $myval2, $myval3);
            $x->execute();
            $x = $sqlcon->prepare("INSERT INTO `t_User` (`Benutzername`, `Passwort`, `Vorname`, `Nachname`, `MailAdresse`, `Strasse`, `StadtID`, `TypID` ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $myval1 = 'Admin';
            $myval2 = HashPassword('UseTheForce2016!');
            $myval3 = 'Max';
            $myval4 = 'Muster';
            $myval5 = 'user@test.at';
            $myval6 = 'Testweg 1';
            $myval7 = 1;
            $myval8 = 1;
            $x->bind_param("ssssssii", $myval1, $myval2, $myval3, $myval4, $myval5, $myval6, $myval7, $myval8);
            $x->execute();
            $sqlcon->close();
            */
            echo "<h1>Willkommen zu Star Movies</h1>";
            if(!isLoggedIn())
            {
                echo "<p>Klicken Sie hier um sich anzumelden</p>";
                echo '<button onclick="location.href='. "'/Login.php'" . '">Login Here</button>';
            }
            else
            {
                echo "Sie sind als " . $_SESSION['LOGINTYP'] . " angemeldet.<br />";
                echo "<p>Klicken Sie hier um sich abzumelden</p>";
                echo '<button onclick="location.href='. "'/Login.php'" . '">Logout Here</button>';
            }
    	?>
    </body>
</html>