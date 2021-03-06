<?php 
   session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    require_once("base.php");
    
    //check for login
    $IsLoggedID = isManagerLoggedIn();
    if(!$IsLoggedID)
    {
        session_start();
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
    //handle postback
    else if(isset($_POST["Vorname"]) && isset($_POST["Nachname"]) && isset($_POST["Benutzername"]) && isset($_POST["EMail"]) && isset($_POST["Strasse"]) && isset($_POST["PLZ"]) && isset($_POST["Ort"]) && isset($_POST["Typ"]))
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
        $myval4 = $_POST["EMail"];
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
        redirect('/Mitarbeiter.php');
    }
    //load data from id -> edit exist element
    else if(isset($_GET["id"]))
    {
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT * FROM v_mitarbeiter WHERE ID = ?");
        $mypar = $_GET["id"];
        $x->bind_param("i", $mypar);
        $x->execute();
        $x->bind_result($ID, $Benutzername, $Vorname, $EMail,$Nachname, $Strasse, $PLZ, $Ort, $Typ);
        $x->fetch();
        $sqlcon->close();
    }
    //delete process
    else if(isset($_GET["delid"]))
    {
        require_once("getSqlConnection.php");
        $delId = $_GET["delid"];
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_DeleteUser( ? )");
        $x->bind_param("i", $delId);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/Mitarbeiter.php');
    }
?>

<?php
    //load HTML head 
    //load HTML head
    //Validators for Benutzername -> alphanumeric + '-' + '_'
    //               Passwort -> empty will leave old password 
    //                        -> valid when legth > 8 and <32 
    //                               a least 1 uppercase 1 lowercase 1 number and 1 of !{}@#$%^&+= character 
    //                               only those are valid characters
    //               Streasse  -> Alphanueric with German characters and '-', '_', ' ', '.'
    //               PLZ  -> NUMERIC 4 DIGITS
    //               Ort  -> ASCI letter + German letter + ' ' + '-'
    //               Nachname  -> ASCI letter + German letter + ' '
    //               Vorname  -> ASCI letter + German letter + ' '
    BuildPageHead(4,'
    <script>
        function validateForm() {
            var myReg = new RegExp(/^[A-Za-z1-9\-_]+$/);
            var x = document.forms["empForm"]["Benutzername"].value;
            var isValid = true;
            if (x == null || x == "" || !myReg.test(x)) {
                isValid = false;
            }
            myReg = new RegExp(/^(?=.{8,32})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!\{\}@#$%^&+=]).*$/);
            x = document.forms["empForm"]["Passwort"].value;
            if (isValid && ((x != null && x != "" && !myReg.test(x)) || x != document.forms["empForm"]["PWRep"].value)) {
                isValid = false;
            }
            myReg = new RegExp(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i);
            x = document.forms["empForm"]["EMail"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[A-Za-z0-9\.\- öäüßÖÄÜ]+$/);
            x = document.forms["empForm"]["Strasse"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[A-Za-z öäüßÖÄÜ]+$/);
            x = document.forms["empForm"]["Vorname"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[A-Za-z öäüßÖÄÜ]+$/);
            x = document.forms["empForm"]["Nachname"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[0-9]{4}$/);
            x = document.forms["empForm"]["PLZ"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[A-Za-z \-öäüßÖÄÜ]+$/);
            x = document.forms["empForm"]["Ort"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            if(!isValid)
                $("#validateResult").show();
            else
                $("#validateResult").hide();
            return isValid;
        }
    </script>
    ',2);
    //form for edit data + fill data for user
?>
            <form id="empForm" onsubmit="return validateForm()" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="mid" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <table>
                <tbody>
                    <tr id="validateResult" style="display:none"><td colspan="2"><p style="color:red;">Bitte überprüfen Sie Ihren eingaben!</p></td></tr>
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
                            <input name="EMail" type="text" value="<?php if(isset($_GET['id'])&&isset($EMail)) echo $EMail ?>"/>
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
                            <input name="Ort" type="text" value="<?php if(isset($_GET['id'])&&isset($Ort)) echo $Ort ?>"></input>
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
                            <input class="submitbutton" type="button" value="Abbrechen" onclick="location.href='/Mitarbeiter.php'" />
                        </td>
                    </tr>
                </tbody>
            </table>
<?php 
    //load footer
    BuildPageFoot();  
?>