<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    require_once("base.php");
    if(isset($_GET["cid"]))
    {
        $cid = $_GET["cid"];
    }
    //check loginstate
    $IsLoggedID = isManagerLoggedIn();
    if(!$IsLoggedID)
    {
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
    //handle postback cinema hall
    else if(isset($_POST["Name"]) && isset($_POST["Rows"]) && isset($_POST["Seats"]))
    {
        require_once("getSqlConnection.php");
        $myval = 0;
        if(isset($_POST["HallID"]))
            $myval = $_POST["HallID"];
        if(isset($_POST["CineID"]))
        {
            $myval4 = $_POST["CineID"];
        }
        if(isset($myval) && $myval > 0 || isset($myval4) &&  $myval4 > 0)
        {
            $myval1 = $_POST["Name"];
            $myval2 = $_POST["Rows"];
            $myval3 = $_POST["Seats"];
            $sqlcon = getSqlCon();
            $x = $sqlcon->prepare("CALL p_ManipulateHall (?, ?, ?, ?, ?)");
            $x->bind_param("isiii", $myval, $myval1, $myval2, $myval3, $myval4);
            $result = $x->execute();
            $sqlcon->close();
        }
        redirect('/EditCinema.php?id='. $myval4);
    }
    //parameter validation
    else if(!isset($cid) && isset($_GET["delid"]))
    {    
        redirect('/ManageOverview.php');
    }
    //load data from id -> edit exist element
    else if(isset($_GET["id"]))
    {
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT * FROM t_Saal WHERE ID = ?");
        $mypar = $_GET["id"];
        $x->bind_param("i", $mypar);
        $x->execute();
        $x->bind_result($ID, $cid, $Name, $Rows, $Seats);
        $x->fetch();
        $sqlcon->close();
    }
    //delete cinema hall
    else if(isset($_GET["delid"]) && isset($cid))
    {
        require_once("getSqlConnection.php");
        $delId = $_GET["delid"];
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("CALL p_DeleteHall( ? )");
        $x->bind_param("i", $delId);
        $result = $x->execute();
        $sqlcon->close();
        redirect('/EditCinema.php?id='. $cid);
    }
?>
<?php 
    //load HTML head
    BuildPageHead(4,'
    <script>
        function validateForm() {
            var x = document.forms["HallForm"]["Name"].value;
            var isValid = true;
            var myReg = new RegExp(/^[A-Za-z1-9\-_. öäüßÖÄÜ]+$/);
            if (x == null || x == "" || !myReg.test(x)) {
                isValid = false;
            }
            myReg = new RegExp(/^[0-9]{1,3}$/);
            x = document.forms["HallForm"]["Rows"].value;
            if (isValid && (x == null || x == "" || !myReg.test(x))) {
                isValid = false;
            }
            myReg = new RegExp(/^[0-9]{1,4}$/);
            x = document.forms["HallForm"]["Seats"].value;
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
    //form for edit data + fill data for cinema hall and performace
?>
            <form id="HallForm" onsubmit="return validateForm()" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="HallID" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <input type="hidden" name="CineID" value="<?PHP echo $cid; ?>">
            <table>
                <tbody>
                    <tr id="validateResult" style="display:none"><td colspan="2"><p style="color:red;">Bitte überprüfen Sie Ihren eingaben!</p></td></tr>
                    <tr>
                        <td colspan="2">
                            <h1 style="margin-left:auto;margin-right:auto;">Saal Bearbeiten</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td>
                            <input name="Name" type="text" value="<?php if(isset($_GET['id'])) echo $Name; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Reihe:</td>
                        <td>
                            <input name="Rows" type="text" value="<?php if(isset($_GET['id'])) echo $Rows; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Sitz:</td>
                        <td>
                            <input name="Seats" type="text" value="<?php if(isset($_GET['id'])) echo $Seats; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="min-width:120px;">
                            <input class="submitbutton" type="submit" value="Speichern"/>
                        </td>
                        <td>
                            <input class="submitbutton" type="button" value="Abbrechen" onclick="location.href='/EditCinema.php?id=<?php echo $cid ?>'" />
                        </td>
                    </tr>
                </tbody>
            </table>


            <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Vorstellungen<button type="button" onclick="location.href='/editPerformance.php?hid=<?php echo $_GET['id'] ."&cid=". $cid ?>'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Kinoname
                    </th>
                    <th>
                        Saal
                    </th>
                    <th>
                        Film
                    </th>
                    <th>
                        Dauer
                    </th>
                    <th>
                        Vorstellungsdatum
                    </th>
                    <th>
                        Filmbeginn
                    </th>
                    <th>

                    </th>
                </thead>
                <tbody>
                    <?php
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM v_filmauffuerung where Saalname = ?" );
                         $x->bind_param("s",$Name);

                         $x->execute();
                         $x->bind_result($Kinoname, $Saalname, $Filmname, $Dauer, $Filmbeginndat, $Filmbeginn, $VorstellungsID);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Kinoname</td><td>$Saalname</td><td>$Filmname</td><td>$Dauer</td><td>$Filmbeginndat</td><td>$Filmbeginn</td><td><button type=\"button\" onclick=\"location.href='/editPerformance.php?hid=". $_GET["id"] . "&cid=" . $cid ."&id=$VorstellungsID'\">Bearbeiten</button><button type=\"button\" onclick=\"location.href='/editPerformance.php?hid=". $_GET["id"]. "&cid=" . $cid ."&delid=$VorstellungsID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>
<?php 
    //load footer
    BuildPageFoot(); 
?>