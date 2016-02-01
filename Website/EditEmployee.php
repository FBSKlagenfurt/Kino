<?php 
   session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    require_once("base.php");
    
    //chekc for login
    $IsLoggedID = isManagerLoggedIn();
    if(!$IsLoggedID)
    {
        session_start();
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
    //Handle Postback
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
        if(isset($_POST["Passwort"]) && ($_POST["Passwort"] != '') && isset($_POST["PWRep"]) && ($_POST["PWRep"] === $_POST["Passwort"]) && $_POST["Passwort"] != '')
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
    //Load data from id -> Edit exist element
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
    //Delete process
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

<?php
    //Load HTML head 
    BuildPageHead(4,'',2);
    //Form for edit data + Fill data 
?>
            <form id="cinemaForm" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="mid" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <table>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h1 style="margin-left:auto;margin-right:auto;">Mitarbeiter Bearbeiten</h1>
                        </td>
                    </tr>
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
                            <input name="PWRep" type="password" value=""/>
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
                                 $x->bind_result($tid,$TypName);
                                 echo "<select name='Typ'>";
                                 while($x->fetch())
                                 {
                                     if($Typ == $TypName)
                                        $selected = 'selected';
                                     else 
                                        $selected = '';
                                     echo "<option " . $selected ." value='".$tid."'>".$TypName."</option>";   

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
<?php BuildPageFoot(); //Load Footer ?>