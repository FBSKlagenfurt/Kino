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
<?php BuildPageHead(4) ?>
         <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Vorstellungen<button onclick="location.href='/editPerformance.php'">Hinzufügen</button></h1>
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
                         $x = $sqlcon->prepare("SELECT * FROM v_filmauffuerung");
                         $x->execute();
                         $x->bind_result($Kinoname, $Saalname, $Filmname, $Dauer, $Filmbeginndat, $Filmbeginn, $VorstellungsID);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Kinoname</td><td>$Saalname</td><td>$Filmname</td><td>$Dauer</td><td>$Filmbeginndat</td><td>$Filmbeginn</td><td><button onclick=\"location.href='/editPerformance.php?vid=".$VorstellungsID."'\">Bearbeiten</button><button onclick=\"location.href='/editPerformance.php'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>
<?php BuildPageFoot() ?>