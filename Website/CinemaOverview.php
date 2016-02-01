<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php");
    require_once("base.php");
?>
<?php BuildPageHead(3) ?>  
           <h1 style="margin-left:auto;margin-right:auto;text-align:center;">Kinos</h1> 
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
                
                </thead>
                <tbody>
                    <?php 
                    
					     require_once("getSqlConnection.php");
					     $sqlcon = getSqlCon();
                         $x = $sqlcon->prepare("SELECT * FROM v_Kino");
                         $x->execute();
                         $x->bind_result($ID, $Kinoname, $TelNr, $Strasse, $PLZ, $Ort);
                         while($x->fetch())
                         {
                             echo "<tr><td>$Kinoname</td><td>$TelNr</td><td>$Strasse</td><td>$PLZ</td><td>$Ort</td></tr>";
                         }
                         $sqlcon->close();
					?>
                </tbody>
            </table>
<?php BuildPageFoot() ?>