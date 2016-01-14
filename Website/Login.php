<?php
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    if(isset($_POST["Username"]) && isset($_POST["Passwort"]))
    {
        require_once("general.php");
        if(($typ = CheckPassword($_POST["Username"], $_POST["Passwort"])) !== "")
        {
            $_SESSION["LOGINUSER"] = $_POST["Username"];
            $_SESSION["LOGINTYP"] = $typ;
            $_SESSION["LOGINTIME"] = strtotime("now");
            redirect("/");
        }
        else 
        {
            echo '<p style="color:red;">Bitte überprüfen Sie Ihren eingaben!</p>';
        }
    }
    else
    {
        session_destroy();
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
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Insert title here</title>
    </head>
    <body>
        <form action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <table>
                <tbody>
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
                        <td>
                            <input type="submit" />  
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>