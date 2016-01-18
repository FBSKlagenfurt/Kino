<?php
      set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary"); 
      require_once("general.php");
      
      require_once("getSqlConnection.php");
      $sqlcon = getSqlCon();
      $x = $sqlcon->prepare("INSERT INTO `t_kino` (`Kinoname`, `Strasse`, `StadtID`, `TelNr` ) VALUES (?, ?, ?, ?)"); 
      $myval1 = 'KinoEins';
      $myval2 = 'Teststrasse 21';
      $myval3 = 1;
      $myval4 = '06761234567';
      $x->bind_param("ssis", $myval1, $myval2, $myval3, $myval4);
      $x->execute();
      $sqlcon->close();
?>