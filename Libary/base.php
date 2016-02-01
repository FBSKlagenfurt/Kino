<?php
    
    function isLoggedIn($ds = false){
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
    //Baue HTML Kopf
    function BuildPageHead($menu, $headstrings = '', $submenu = 0){
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://fonts.googleapis.com/css?family=Signika:300,600&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css" /><link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,800italic,800" rel="stylesheet" type="text/css" />
    <link href="Style/base.css" rel="stylesheet" type="text/css" />
    <script src="/Scripts/jquery.js" language="javascript"></script>
    <title>Star Movies</title>
    '. $headstrings .'
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
                    <a href="/Login.php">
                        <img src="/Images/login.png" style="vertical-align:middle"; width="24px" height="24px" />
                        <span style="line-height:24px; vertical-align:middle; font-size:15px;">' . (isLoggedIn() ? "Abmelden" : "Anmelden") . '</span>
                    </a>
'                    . (isLoggedIn() ? '' :
                    '<a href="/">
                        <img src="/Images/register.png" style="vertical-align:middle"; width="24px" height="24px" />
                        <span style="line-height:24px; vertical-align:middle; font-size:15px;">Registrieren</span>
                    </a>' 
) .
                   
'                </div>
                <div class="clear"></div>
                ' . BuildMenu($menu) .'
         </div>
      </div>
    </div>
    '
    .(($menu == 4 && isManagerLoggedIn()) ?
    '<div class="submenu">
      <div class="page">
          <div class="submenuentry'.(($submenu == 3) ? ' selsubmenuentry' : '').'">
           <a href="/Filmuebersicht.php" >Filme</a>
          </div>      
          <div class="submenuentry'.(($submenu == 2) ? ' selsubmenuentry' : '').'">
           <a href="/Mitarbeiter.php" >Mitarbeiter</a>
          </div>      
          <div class="submenuentry'.(($submenu == 1) ? ' selsubmenuentry' : '').'">
           <a href="/Kinouebersicht.php" >Kinos</a>
          </div>      
      </div>
    </div>
' : '').
'    <div class="page">
        <div class="main">';
    }
    
    //Baue Menu
    function BuildMenu($active = 1){
        $isLogin = isLoggedIn(true);
        if($isLogin)
            $loginType = $_SESSION["LOGINTYP"];
        
        return '<div class="mainmenu">
                    <div class="' . (($active === 1) ? 'sel' : '') .'menuentry">
                        <a href="/">Start</a>
                    </div>
                    <div class="' . (($active === 2) ? 'sel' : '') .'menuentry">
                        <a href="MovieOverview.php">Filme</a>
                    </div>
                    <div class="' . (($active === 3) ? 'sel' : '') .'menuentry">
                        <a href="CinemaOverview.php">Kinos</a>
                    </div>' .
                    (($isLogin) ?  (
'                   <div class="' . (($active === 4) ? 'sel' : '') .'menuentry">'
                        .
                        (($loginType == 1) ? //Wenn Manager
                        '<a href="/ManageOverview.php">Verwaltung</a>'
                        : (($loginType == 2) ?  //Wenn kassier
                        '<a href="/">Kassabereich</a>' 
                        : '<a href="/">Kontoverwaltung</a>' )) //Wenn Kunde
                        .
'                    </div>') : '') . '
        </div>';
    }
    
    //Baue Fuss
    function BuildPageFoot(){
        echo '</div>
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
</html>';
    }
?>