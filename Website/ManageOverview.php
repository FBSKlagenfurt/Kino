<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php");
    require_once("base.php");
    $IsLoggedID = isManagerLoggedIn();
    if(!$IsLoggedID)
    {
        session_start();
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
?>
<?php BuildPageHead(4,'',0) ?>
            <button onclick="location.href='/Kinouebersicht.php'">Kinos</button>
            <button onclick="location.href='/Mitarbeiter.php'">Mitarbeiter</button>
            <button onclick="location.href='/Filmuebersicht.php'">Filme</button>
<?php BuildPageFoot() ?>