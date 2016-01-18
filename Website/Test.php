<?php
     require_once("../Libary/getSqlConnection.php");
     $sqlcon = getSqlCon();
     $x = $sqlcon->prepare("SELECT Ort FROM t_stadt");
     $x->execute();
     $x->bind_result($Ort);
     while($x->fetch())
     {
         echo $Ort;
     }
     $sqlcon->close();
?>