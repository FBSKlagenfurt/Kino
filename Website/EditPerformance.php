<?php 
  session_start();
  set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
  require_once("general.php"); 
  require_once("base.php");
  
  //check for login
  $IsLoggedID = isManagerLoggedIn();
  if(isset($_GET["hid"]))
  {
      $hid = $_GET["hid"];
  }
  if(isset($_GET["cid"]))
  {
      $cid = $_GET["cid"];
  }
  if(!$IsLoggedID)
  {
      session_start();
      $_SESSION["ReturnUrl"] = "/ManageOverview.php";
      redirect("/login.php");
  }
  //handle postback
  else if(isset($_POST["hid"]) && isset($_POST["Titel"]) && isset($_POST["BeginnDate"]) && isset($_POST["BeginnTime"]) && isset($_POST["cid"]))
  {
      require_once("getSqlConnection.php");
      if(isset($_POST["pid"]))
          $myval = $_POST["pid"];
      else 
          $myval = 0;
      $hid = $_POST["hid"];
      $myval1 = $_POST["Titel"];
      $myval2 = $_POST['BeginnDate'] . " " . $_POST['BeginnTime'];
      $cid = $_POST["cid"];
      require_once("getSqlConnection.php");
      $sqlcon = getSqlCon();
      $x = $sqlcon->prepare("CALL p_ManipulatePerformance (?, ?, ?, ?)");
      $x->bind_param("iiis", $myval, $myval1, $hid, $myval2);
      $result = $x->execute();
      $sqlcon->close();
      redirect("/EditCinemahall.php?cid=$cid&id=$hid");
  }
  //parameter validation
  else if(!isset($hid) && !isset($cid) && isset($_GET["delid"]))
  {    
      redirect('/ManageOverview.php');
  }
  //load data from id -> edit exist element
  else if(isset($_GET["id"]))
  {
      require_once("getSqlConnection.php");
      $sqlcon = getSqlCon();
      $x = $sqlcon->prepare("SELECT * FROM t_filmauffuerung WHERE ID = ?");
      $mypar = $_GET["id"];
      $x->bind_param("i", $mypar);
      $x->execute();
      $x->bind_result($ID, $FilmID, $SaalID, $AuffZeit);
      $x->fetch();
      $sqlcon->close();
      $datetime = explode(" ", $AuffZeit);
  }
  //delete performace
  else if(isset($_GET["delid"]) && isset($hid))
  {
      require_once("getSqlConnection.php");
      $delId = $_GET["delid"];
      $sqlcon = getSqlCon();
      $x = $sqlcon->prepare("CALL p_DeletePerformance( ? )");
      $x->bind_param("i", $delId);
      $result = $x->execute();
      $sqlcon->close();
      redirect("/EditCinemahall.php?cid=$cid&id=$hid");
  }
?>
<?php 
  //load HTML head and timehandle 
  BuildPageHead(4,'<link rel="stylesheet" href="/scripts/jqueryui/jquery-ui.min.css">
    <script src="/scripts/jqueryui/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#BeginnDate").datepicker();
            $("#BeginnDate").datepicker("option", "dateFormat", "yy-mm-dd" );
            $("#BeginnDate").datepicker("setDate", new Date("' .$datetime[0]. '"));
        });
    </script>'
  ,1);
  //form for edit data + fill data for performance 
?>
        <form id="performaceForm" action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
          <input type="hidden" name="pid" value="<?PHP if(isset($_GET['id'])) echo $_GET['id']; ?>">
          <input type="hidden" name="hid" value="<?PHP echo $hid ?>">
          <input type="hidden" name="cid" value="<?PHP echo $cid ?>">
          <table>
            <tbody>
          <tr>
              <td colspan="2">
                  <h1 style="margin-left:auto;margin-right:auto;">Vorstellungen</h1>
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
                      $x->bind_result($fid,$Titel);
                      echo "<select name='Titel' id='FiNa'>";
                      while($x->fetch())
                      {
                       
                          echo "<option " .((isset($_GET["id"]) && $FilmID == $fid) ? "selected" : ''). " value='".$fid."'>".$Titel."</option>";   

                      }
                      echo "</select>";
                      $sqlcon->close();  
                  ?>
              </td>
          </tr>
          

            <tr>
              <td>Datum:</td>
              <td>
                <input type="text" id="BeginnDate" name="BeginnDate" value="<?php if(isset($_GET["id"])) echo $datetime[0] ?>"/>
              </td>
            </tr>
            <tr>
              <td>Zeit:</td>
              <td>
                <input type="text" id="BeginnTime" name="BeginnTime" value="<?php if(isset($_GET["id"])) echo $datetime[1] ?>"/>
              </td>
            </tr>

         
          <tr>
              <td style="min-width:120px;">
                  <input class="submitbutton" type="submit" value="Speichern"/>
              </td>
              <td>
                  <input class="submitbutton" type="button" value="Abbrechen" onclick="location.href='/editCinemahall.php<?php echo "?cid＝" . $cid . "&id=" . $hid ?>'" />
              </td>
          </tr>
      </tbody>
  </table>
</form>
<?php 
  //load footer 
  BuildPageFoot() 
?>