<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 
    require_once("base.php");
    $IsLoggedID = isManagerLoggedIn();
?>
<?php BuildPageHead(4,'',3) ?>
         <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Filme<button onclick="location.href='/editMovie.php?id=$ID'">Hinzufügen</button></h1>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <th>
                        Filmtitel
                    </th>
                    <th>
                        Dauer in Minuten
                    </th>
                    <th>
                        Preis in €
                    </th>
                    <th>
                        Kurzbeschreibung
                    </th>
                    <th>
                    </th>
                </thead>
                <tbody>
                    <?php
                         require_once("getSqlConnection.php");
                         $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM t_Film");
                         $x->execute();
                         $x->bind_result($ID, $Titel, $Beschreibung, $Dauer, $Preis);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Titel</td><td>$Dauer</td><td>$Preis</td><td>$Beschreibung</td><td><button onclick=\"location.href='/editMovie.php?id=$ID'\">Bearbeiten</button><button onclick=\"location.href='/editMovie.php?delid=$ID'\">Löschen</button></td></tr>";
                         }
                         $sqlcon->close();
                    ?>
                </tbody>
            </table>
            
<?php BuildPageFoot() ?>