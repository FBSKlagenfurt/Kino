<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php"); 

init();


function init()
	{
		if (isset($_GET['KiNa'])) {
			getKinosaaele();
		}
		else{
			getFilmname();
		}
	}


function getKinosaaele(){
	$Data = array();
		
	require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT id, KinoID, Saalname FROM t_Saal where KinoID = ".$_GET['KiNa'].";");
        $x->execute();
        $x->bind_result($id, $KinoID, $Saalname);
        
        while($x->fetch())
        {
        	$obj = new stdClass();

        	$obj->id = $id;
        	$obj->KinoID = $KinoID;
        	$obj->Saalname = $Saalname;
         
            array_push($Data, $obj);
        }
        
        $sqlcon->close();

	createJsonOutput($Data);
}

function getFilmname(){
	$Data = array();
		
	require_once("getSqlConnection.php");
        $sqlcon = getSqlCon();
        $x = $sqlcon->prepare("SELECT id, Titel, Dauer FROM t_film where ID = ".$_GET['FiNa'].";");
        $x->execute();
        $x->bind_result($id, $Titel, $Dauer);
        
        while($x->fetch())
        {
        	$obj = new stdClass();

        	$obj->id = $id;
        	$obj->Titel = $Titel;
        	$obj->Dauer = $Dauer;
         
            array_push($Data, $obj);
        }
        
        $sqlcon->close();

	createJsonOutput($Data);
}


	 function createJsonOutput($x){	 	
	 	//$test = $_GET["KiNa"];
 		//echo json_encode(array('a' => $test, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5));
	 	echo json_encode($x);
 	}
?>