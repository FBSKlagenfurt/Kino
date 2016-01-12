<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
	<?php
        require_once("getSqlConnection.php");
        $sqlcon = getSqlCon(); 
        $x = $sqlcon->prepare("SELECT * From t_Country WHERE ID < ?");
        $myval = 10;
        $x->bind_param("i", $myval);
        $x->execute();
        $x->bind_result($res1, $res2);
        while($x->fetch())
        {
            echo "$res1 - $res2 <br />";
        }
	   	$Start = "HelloWorld! This is the start of our Project <br />";
		echo "$Start Just made some thing so that we don't have a empty repository!"
	?>
</body>
</html>