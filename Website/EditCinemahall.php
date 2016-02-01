<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    require_once("base.php");
    $IsLoggedID = isManagerLoggedIn();
    if(isset($_GET["cid"]))
    {
        $cid = $_GET["cid"];
    }
    if(!$IsLoggedID)
    {
        $_SESSION["ReturnUrl"] = "/ManageOverview.php";
        redirect("/login.php");
    }
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
    else if(!isset($cid) && isset($_GET["delid"]))
    {    
        redirect('/ManageOverview.php');
    }
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
<?php BuildPageHead(4,'',1) ?>
            <form id="cinemaForm" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="HallID" value="<?PHP if(isset($_GET['id'])) echo $_GET['id'] ?>">
            <input type="hidden" name="CineID" value="<?PHP echo $cid; ?>">
            <table>
                <tbody>
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
<?php BuildPageFoot() ?>