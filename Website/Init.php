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
            //initialize Amdmin User
            set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary"); 
            require_once("hash.php");
            require_once("getSqlConnection.php");
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
    	?>
    </body>
</html>