<?php
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    function CheckPassword($username, $password){
        require_once("getSqlConnection.php");
        $dbh = getSqlCon();
        $sth = $dbh->prepare('SELECT Password, Typ FROM v_Account WHERE Username = ? LIMIT 1');
        $sth->bind_param("s", $username);
        $sth->execute();
        $sth->bind_result($hash, $Typ);
        $correct = false;
        if($sth->fetch())
        {
            if ( hash_equals($hash, crypt($password, $hash)) ) {
                $correct = true;
            }
        }
        $dbh->close();
        if($correct)
            return $Typ;
        else
            return "";
    }
    require_once("base.php");
    $mytext = "";
    $returnurl = "/";
    if(isset($_POST["Username"]) && isset($_POST["Passwort"]))
    {
        if(($typ = CheckPassword($_POST["Username"], $_POST["Passwort"])) !== "")
        {
            $_SESSION["LOGINUSER"] = $_POST["Username"];
            $_SESSION["LOGINTYP"] = $typ;
            $_SESSION["LOGINTIME"] = strtotime("now");
            if(isset($_SESSION["ReturnUrl"]) && $_SESSION["ReturnUrl"] != NULL)
                $returnurl = $_SESSION["ReturnUrl"];
            $_SESSION["ReturnUrl"] = NULL;  
            require_once("general.php");              
            redirect($returnurl);
        }
        else 
        {
            $mytext = '<tr><td colspan="2"><p style="color:red;">Bitte überprüfen Sie Ihren eingaben!</p></td></tr>';
        }
    }
    else
    {
        if(isset($_SESSION["ReturnUrl"]) && $_SESSION["ReturnUrl"] != NULL)
            $returnurl = $_SESSION["ReturnUrl"];
        session_destroy();
        session_start();
        $_SESSION["ReturnUrl"] = $returnurl;
    }
    /*
    require_once("getSqlConnection.php");
    $sqlcon = getSqlCon(); 
    $x = $sqlcon->prepare("SELECT * From t_Land WHERE ID < ?");
    $myval = 10;
    $x->bind_param("i", $myval);
    $x->execute();
    $x->bind_result($res1, $res2);
    while($x->fetch())
    {
        echo "$res1 - $res2 <br />";
    }*/   
?>
<?php BuildPageHead(0) ?>            
  <div class="Teaser">
   <div class="col1">
     <br /><br /><br />
     <div style="font-weight:800;color: #1acdc9;font-size: 2.8em;padding-bottom:20px;">STAR MOVIES</div>
     <div style="font-weight:300;color:#68696b;font-size:2.3em;">Watch the star, watch us.</div>
     <br />
     <div style="font-family: Signika;font-style:normal;font-size: 1.25em;line-height:1.7em;">
       <form id="LoginForm" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <table>
                <tbody>
                    <?php echo $mytext ?> 
                    <tr>
                        <td>Benutzername:</td>
                        <td>
                            <input name="Username" type="text" />
                        </td>
                    </tr>
                    <tr>
                        <td>Passwort:</td>
                        <td>
                            <input name="Passwort" type="Password" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a style='color:#000;height:30px;padding-left:28px; display: block;background-repeat: no-repeat; background-size:100px 30px; background-image:url("/images/button_bg.png");background-repeat: no-repeat;' href="javascript:document.getElementById('LoginForm').submit()">
                                Login
                            </a> 
                            <input type="submit" style="visibility:hidden;width:0;height:0;"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
     </div>
   </div>
   <div class="col2">
     <br /><br />
     <img src="/Images/movie.png" width="400px" height="260px" alt="Movie Image"  />
   </div>
  </div>   
  <div class="clear"></div>
  <br />
  </div>
        <div class="clear">
        </div>
<?php BuildPageFoot() ?>