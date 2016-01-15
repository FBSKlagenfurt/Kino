<?php
function getSqlCon($host = "", $port = "", $socket = "", $user = "", $password = "", $dbname = ""){
    if ($host === "" && $port === "" && $socket === "" && $user === "" && $password === "" && $dbname === "")
    {
        $host="127.0.0.1";
        $port=3306;
        $socket="";
        $user="root";
        $password="";
        $dbname="KinoDaten";
    }
    $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());
    if(!$con->set_charset("utf8")){
        echo "Fehler beim Laden von UTF-8" . $mysqli->error;
    }
    //$con->close();
    return $con;
}
?>