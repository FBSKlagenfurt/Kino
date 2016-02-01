<?php 
    session_start();
    set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]. "/../" ."/libary");
    require_once("general.php");
    require_once("base.php"); 
    isLoggedIn();
?>
<?php BuildPageHead(1) ?>            
  <div class="Teaser">
   <div class="col1">
     <br /><br /><br />
     <div style="font-weight:800;color: #1acdc9;font-size: 2.8em;padding-bottom:20px;">STAR MOVIES</div>
     <div style="font-weight:300;color:#68696b;font-size:2.3em;">Watch the star, watch us.</div>
     <br />
     <div style="font-family: Signika;font-style:normal;font-size: 1.25em;line-height:1.7em;">
       Besuchen Sie Star Movies und erleben Sie Kino <br />
       auf einen anderen Dimension.<br />
       <!--Jetzt neu-->Bald verfügbar: Tickets bis zu einer Woche vor Reservieren!<br />
       <a href="/"  style="font-size: 0.8em;">&nbsp;&gt; MEHR INFO</a>
     </div>
   </div>
   <div class="col2">
     <br /><br />
     <img src="/Images/movie.png" width="400px" height="260px" alt="Movie Image"  />
   </div>
  </div>   
  <div class="clear"></div>
  <br />
  
<?php BuildPageFoot() ?>
        