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
    
    function isLoggedIn($ds = true){
        if(((!(!isset($_SESSION["LOGINUSER"]) || $_SESSION["LOGINUSER"] == NULL || !isset($_SESSION["LOGINTYP"]) || $_SESSION["LOGINTYP"] == NULL || !isset($_SESSION["LOGINTIME"]) || $_SESSION["LOGINTIME"] == NULL)) && ($_SESSION["LOGINTIME"] > strtotime("- 30 minutes"))))
        {
            $_SESSION["LOGINTIME"] = strtotime("now");
            return true;
        }
        else
        {
            if($ds === true)
                session_destroy();
            return false;   
        }
    }
    function isManagerLoggedIn(){
        if(isLoggedIn() && $_SESSION["LOGINTYP"] === 1)
        {
            return true;
        }
        else
        {
            return false;   
        }
    }
    function BuildPageHead($menu){
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
                ' . $menu .'
         </div>
      </div>
    </div>
    

    <div class="page">
        <div class="main">';
    } 
    
    function BuildCashierMenu($active = 1, $CanShow = false){
        '<div class="mainmenu">
                    <div class="' . (($active === 1) ? 'sel' : '') .'menuentry">
                        <a href="/">Start</a>
                    </div>
                    <div class="' . (($active === 2) ? 'sel' : '') .'menuentry">
                        <a href="MovieOverview.php">Filme</a>
                    </div>
                    <div class="' . (($active === 3) ? 'sel' : '') .'menuentry">
                        <a href="CinemaOverview.php">Kinos</a>
                    </div>' .
                    ((!$isLogin) ?  (
'                   <div class="' . (($active === 4) ? 'sel' : '') .'menuentry">
                        <a href="/ManageOverview.php">Verwaltung</a>
                    </div>') : '') . '
        </div>';
    }
    
    function BuildManagerMenu($active = 1, $CanShow = false){
        '<div class="mainmenu">
                    <div class="' . (($active === 1) ? 'sel' : '') .'menuentry">
                        <a href="/">Start</a>
                    </div>
                    <div class="' . (($active === 2) ? 'sel' : '') .'menuentry">
                        <a href="MovieOverview.php">Filme</a>
                    </div>
                    <div class="' . (($active === 3) ? 'sel' : '') .'menuentry">
                        <a href="CinemaOverview.php">Kinos</a>
                    </div>' .
                    ((!$isLogin) ?  (
'                   <div class="' . (($active === 4) ? 'sel' : '') .'menuentry">
                        <a href="/ManageOverview.php">Verwaltung</a>
                    </div>') : '') . '
        </div>';
    }
?>