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
    else if(isset($_POST["Titel"]) && isset($_POST["Dauer"]) && isset($_POST["Preis"]) && isset($_POST["Beschreibung"]))
    {
        require_once("getSqlConnection.php");
        if(isset($_POST["filmid"]))
            $myval = $_POST["filmid"];
        else 
            $myval = 0;
        $myval1 = $_POST["Titel"];
        $myval2 = $_POST["Dauer"];
        $myval3 = $_POST["Preis"];
        $myval4 = $_POST["Beschreibung"];
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_ManipulateMovie (?, ?, ?, ?, ?)");
        $x->bind_param("isids", $myval, $myval1, $myval2, $myval3, $myval4);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/Filmuebersicht.php');
    }
    //load data from id -> edit exist element
    else if(isset($_GET["id"]))
    {
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT * FROM t_Film WHERE ID = ?");
        $mypar = $_GET["id"];
        $x->bind_param("i", $mypar);
        $x->execute();
        $x->bind_result($ID, $Titel, $Beschreibung, $Dauer, $Preis);
        $x->fetch();
        $sqlcon->close();
    }
    //delete movie
    else if(isset($_GET["delid"]))
    {
        require_once("getSqlConnection.php");
        $delId = $_GET["delid"];
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_DeleteMovie( ? )");
        $x->bind_param("i", $delId);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/Filmuebersicht.php');
    }
?>
<?php 
    //load HTML head
    //Validators for dauer -> number with 1 -4 digits
    //               Titel -> not null or empty
    //               Preis -> float 1-3.1-2 digits
    BuildPageHead(4,'
    <script>
        function validateForm() {
            var x = document.forms["MovieForm"]["Titel"].value;
            var isValid = true;
            if (x == null || x == "") {
                isValid = false;
            }
            var myReg = new RegExp(/^\d{1,4}$/);
            x = document.forms["MovieForm"]["Dauer"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^\d{1,3}(\.\d{1,2})?$/);
            x = document.forms["MovieForm"]["Preis"].value;
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
    ',3);
    //Form for edit data + fill data for movie
?>
            <form id="MovieForm" onsubmit="return validateForm()" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="filmid" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <table>
                <tbody>
                    <tr id="validateResult" style="display:none"><td colspan="2"><p style="color:red;">Bitte überprüfen Sie Ihren eingaben!</p></td></tr>
                    <tr>
                        <td colspan="2">
                            <h1 style="margin-left:auto;margin-right:auto;">Film Bearbeiten</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>Titel:</td>
                        <td>
                            <input name="Titel" type="text" value="<?php if(isset($_GET['id'])&&isset($Titel)) echo $Titel ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Dauer:</td>
                        <td>
                            <input name="Dauer" type="text" value="<?php if(isset($_GET['id'])&&isset($Dauer)) echo $Dauer ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Preis:</td>
                        <td>
                            <input name="Preis" type="text" value="<?php if(isset($_GET['id'])&&isset($Preis)) echo $Preis ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Beschreibung:</td>
                        <td>
                            <textarea name="Beschreibung"><?php if(isset($_GET['id'])&&isset($Beschreibung)) echo $Beschreibung ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width:120px;">
                            <input class="submitbutton" type="submit" value="Speichern"/>
                        </td>
                        <td>
                            <input class="submitbutton" type="button" value="Abbrechen" onclick="location.href='/Filmuebersicht.php'" />
                        </td>
                    </tr>
                </tbody>
            </table>
<?php 
    //load footer
    BuildPageFoot(); 
?>