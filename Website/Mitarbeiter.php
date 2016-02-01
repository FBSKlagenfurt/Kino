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
?>
<?php BuildPageHead(4,'',2) ?>
        <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Mitarbeiter<button onclick="location.href='/editEmployee.php'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Benutzername
                    </th>
                    <th>
                        Vorname
                    </th>
                    <th>
                        Nachname
                    </th>
                    <th>
                        E-Mail
                    </th>
                    <th>
                        Strasse
                    </th>
                    <th>
                        PLZ
                    </th>
                    <th>
                        Ort
                    </th>
                    <th>
                        Typ
                    </th>
                    <th>
                    </th>   
                </thead>
                <tbody>
                    <?php
                         //Load Employee entrys
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM v_mitarbeiter");
                         $x->execute();
                         $x->bind_result($ID, $Benutzername, $Vorname, $EMail, $Nachname, $Strasse, $PLZ, $Ort, $Typ);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Benutzername</td><td>$Vorname</td><td>$Nachname</td><td>$EMail</td><td>$Strasse</td><td>$PLZ</td><td>$Ort</td><td>$Typ</td><td><button onclick=\"location.href='/editEmployee.php?id=$ID'\">Bearbeiten</button><button onclick=\"location.href='/editEmployee.php?delid=$ID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>
<?php BuildPageFoot() ?>