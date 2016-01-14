<?php
    function CheckPassword($username, $password){
        require_once("getSqlConnection.php");
        $dbh = getSqlCon();
        $sth = $dbh->prepare('SELECT Password, Typ FROM v_Account WHERE Username = ? LIMIT 1');
        $sth->bind_param("s", $username);
        $sth->execute();
        $sth->bind_result($hash, $Typ);
        $correct = false;
        if($sth->fetch())
        {
            if ( hash_equals($hash, crypt($password, $hash)) ) {
                $correct = true;
            }
        }
        $dbh->close();
        if($correct)
            return $Typ;
        else
            return "";
    }
    function redirect($url, $statusCode = 303)
    {
       header('Location: ' . $url, true, $statusCode);
       die();
    }
    function isLoggedIn(){
        if(((!(!isset($_SESSION["LOGINUSER"]) || $_SESSION["LOGINUSER"] == NULL || !isset($_SESSION["LOGINTYP"]) || $_SESSION["LOGINTYP"] == NULL || !isset($_SESSION["LOGINTIME"]) || $_SESSION["LOGINTIME"] == NULL)) && ($_SESSION["LOGINTIME"] > strtotime("- 30 minutes"))))
        {
            return true;
        }
        else
        {
            session_destroy();
            return false;   
        }
    } 
?>