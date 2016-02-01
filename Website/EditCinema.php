<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    require_once("base.php");
    
    //check login state
    $IsLoggedID = isManagerLoggedIn();
    if(!$IsLoggedID)
    {
        session_start();
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
    //handle postback cinema
    else if(isset($_POST["Kinoname"]) && isset($_POST["Str"]) && isset($_POST["PLZ"]) && isset($_POST["Ort"]))
    {
        require_once("getSqlConnection.php");
        if(isset($_POST["cineid"]))
            $myval = $_POST["cineid"];
        else 
            $myval = 0;
        $myval1 = $_POST["Kinoname"];
        $myval2 = $_POST["Str"];
        $myval3 = $_POST["PLZ"];
        $myval4 = $_POST["Ort"];
        $myval5 = $_POST["Tel"];
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_ManipulateCinema (?, ?, ?, ?, ?, ?)");
        $x->bind_param("isssss", $myval, $myval1, $myval2, $myval3, $myval4, $myval5);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/Kinouebersicht.php');
    }
    //load data from id -> edit exist element
    else if(isset($_GET["id"]))
    {
        $cid = $_GET["id"];
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT * FROM v_Kino WHERE ID = ?");
        $mypar = $_GET["id"];
        $x->bind_param("i", $mypar);
        $x->execute();
        $x->bind_result($ID, $Kinoname, $TelNr, $Strasse, $PLZ, $Ort);
        $x->fetch();
        $sqlcon->close();
    }
    //delete cinema
    else if(isset($_GET["delid"]))
    {
        require_once("getSqlConnection.php");
        $delId = $_GET["delid"];
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_DeleteCinema( ? )");
        $x->bind_param("i", $delId);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/Kinouebersicht.php');
    }
?>
<?php 
    //load HTML head
    BuildPageHead(4,'
    <script>
        function validateForm() {
            var x = document.forms["cinemaForm"]["Kinoname"].value;
            var isValid = true;
            var myReg = new RegExp(/^[A-Za-z1-9\-_. öäüßÖÄÜ]+$/);
            if (x == null || x == "" || !myReg.test(x)) {
                isValid = false;
            }
            myReg = new RegExp(/^[0-9\-\+]{9,15}$/);
            x = document.forms["cinemaForm"]["Tel"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[A-Za-z0-9\.\- öäüßÖÄÜ]+$/);
            x = document.forms["cinemaForm"]["Str"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[0-9]{4}$/);
            x = document.forms["cinemaForm"]["PLZ"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[A-Za-z \-öäüßÖÄÜ]+$/);
            x = document.forms["cinemaForm"]["Ort"].value;
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
    ',1); 
    //form for edit data + fill data for cinema
?> 
            <form id="cinemaForm" onsubmit="return validateForm()" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="cineid" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <table>
                <tbody>
                    <tr id="validateResult" style="display:none"><td colspan="2"><p style="color:red;">Bitte überprüfen Sie Ihren eingaben!</p></td></tr>
                    <tr>
                        <td colspan="2">
                            <h1 style="margin-left:auto;margin-right:auto;">Kino Bearbeiten</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>Kinoname:</td>
                        <td>
                            <input required name="Kinoname" type="text" value="<?php if(isset($_GET['id'])) echo $Kinoname ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Tel:</td>
                        <td>
                            <input required name="Tel" type="text" value="<?php if(isset($_GET['id'])) echo $TelNr ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Straße:</td>
                        <td>
                            <input required name="Str" type="text" value="<?php if(isset($_GET['id'])) echo $Strasse ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>PLZ:</td>
                        <td>
                            <input required name="PLZ" type="text" value="<?php if(isset($_GET['id'])) echo $PLZ ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Ort:</td>
                        <td>
                            <input required name="Ort" type="text" value="<?php if(isset($_GET['id'])) echo $Ort ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width:120px;">
                            <input class="submitbutton" type="submit" value="Speichern"/>
                        </td>
                        <td>
                            <input class="submitbutton" type="button" value="Zurück" onclick="location.href='/Kinouebersicht.php'" />
                        </td>
                    </tr>
                </tbody>
            </table>
             <?php if(isset($_GET["id"])){ echo '
             <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Säle in '. $Kinoname .'<button type="button"; onclick="location.href=\'/EditCinemahall.php?cid='. $cid .'\'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Saalname
                    </th>
                    <th>
                        Reihen
                    </th>
                    <th>
                        Sitze/Reihe
                    </th>
                    <th>
                    </th>
                </thead>
                <tbody>';
                    
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT ID, Saalname, Reihe, Sitze FROM t_Saal WHERE KinoID = ?");
                         $x->bind_param("i", $_GET['id']);
                         $x->execute();
                         $x->bind_result($ID, $Saalname, $Row, $Seats);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Saalname</td><td>$Row</td><td>$Seats</td><td><button type=\"button\" onclick=\"location.href='/editCinemahall.php?cid=$cid&id=$ID'\">Bearbeiten</button><button type=\"button\" onclick=\"location.href='/editCinemahall.php?cid=$cid&delid=$ID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                   
                echo '</tbody>
            </table>';} ?>
<?php 
    //load footer
    BuildPageFoot(); 
?>