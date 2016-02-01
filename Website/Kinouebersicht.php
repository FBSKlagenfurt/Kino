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
<?php BuildPageHead(4,'',1); ?>
         <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Kinos<button onclick="location.href='/editCinema.php'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Kinoname
                    </th>
                    <th>
                        Tel Nr.
                    </th>
                    <th>
                        Straße
                    </th>
                    <th>
                        PLZ
                    </th>
                    <th>
                        Ort
                    </th>
                    <th>
                    </th>
                </thead>
                <tbody>
                    <?php
                         //Load entrys
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM v_Kino");
                         $x->execute();
                         $x->bind_result($ID, $Kinoname, $TelNr, $Strasse, $PLZ, $Ort);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Kinoname</td><td>$TelNr</td><td>$Strasse</td><td>$PLZ</td><td>$Ort</td><td><button onclick=\"location.href='/editCinema.php?id=$ID'\">Bearbeiten</button><button onclick=\"location.href='/editCinema.php?delid=$ID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>
            
<?php BuildPageFoot(); ?>