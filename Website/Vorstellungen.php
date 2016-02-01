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
            <table>
    <tbody>
        <tr>
            <td colspan="2">
                <h1 style="margin-left:auto;margin-right:auto;">Vorstellungen</h1>
            </td>
        </tr>
        <tr>
            <td>Kino:</td>
            <td>
                <?php
                    require_once("getSqlConnection.php");
                    $sqlcon = getSqlCon();
                    $x = $sqlcon->prepare("SELECT id, Kinoname FROM t_Kino;");
                    $x->execute();
                    $x->bind_result($id,$Kinoname);
                    echo "<select name='Kinoname' id='KiNa'>";
                    while($x->fetch())
                    {
                     
                        echo "<option value='".$id."'>".$Kinoname."</option>";   

                    }
                    echo "</select>";
                    $sqlcon->close();  
                ?>
            </td>
        </tr>
        <tr>
            <td>Saal:</td>
            <td>                
                   <?php
                        echo "<select name='Saalname' id='Saalname'>";            
                        echo "</select>";                                    
                    ?>
            </td>
        </tr>
        <tr>
            <td>Film:</td>
            <td>
                <?php
                    require_once("getSqlConnection.php");
                    $sqlcon = getSqlCon();
                    $x = $sqlcon->prepare("SELECT id, Titel FROM t_film;");
                    $x->execute();
                    $x->bind_result($id,$Titel);
                    echo "<select name='Titel' id='FiNa'>";
                    while($x->fetch())
                    {
                     
                        echo "<option value='".$id."'>".$Titel."</option>";   

                    }
                    echo "</select>";
                    $sqlcon->close();  
                ?>
            </td>
        </tr>
        <tr>
            <td>Dauer:</td>
            <td>                
                    <?php
                        echo "<output name='Dauer' id='Dauer'>";            
                        echo "</output>";                                    
                    ?>
            </td>
        </tr>

         <tr>
            <td>Beginnt um: </td>
            <td><input type="text" id="beginnZeit" /></td>
        </tr>


        <tr>
            <td>Zeit von/bis</td>
        </tr>
       
        <tr>
            <td style="min-width:120px;">
                <input class="submitbutton" type="submit" value="Speichern"/>
            </td>
            <td>
                <input class="submitbutton" type="button" value="Abbrechen" onclick="location.href='/ManageOverview.php'" />
            </td>
        </tr>
    </tbody>
</table>
<?php BuildPageFoot() ?>