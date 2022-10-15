<?php
session_start();
require "../incl/dashboardLib.php";
require "../".$dbPath."incl/lib/connection.php";
$dl = new dashboardLib();
require "../".$dbPath."incl/lib/mainLib.php";
$gs = new mainLib();
include "../".$dbPath."incl/lib/connection.php";
$dl->title($dl->getLocalizedString("levels"));
$dl->printFooter('../');
if(isset($_GET["page"]) AND is_numeric($_GET["page"]) AND $_GET["page"] > 0){
	$page = ($_GET["page"] - 1) * 10;
	$actualpage = $_GET["page"];
}else{
	$page = 0;
	$actualpage = 1;
}
if($gs->checkPermission($_SESSION["accountID"], "dashboardModTools")){
	$table = '<table class="table table-inverse"><tr><th>#</th><th>'.$dl->getLocalizedString("levelid").'</th><th>'.$dl->getLocalizedString("levelname").'</th><th>'.$dl->getLocalizedString("levelAuthor").'</th><th>'.$dl->getLocalizedString("leveldesc").'</th><th>'.$dl->getLocalizedString("levelpass").'</th><th>'.$dl->getLocalizedString("stars").'</th><th>'.$dl->getLocalizedString("songIDw").'</th></tr>';
} else {
	$table = '<table class="table table-inverse"><tr><th>#</th><th>'.$dl->getLocalizedString("levelid").'</th><th>'.$dl->getLocalizedString("levelname").'</th><th>'.$dl->getLocalizedString("levelAuthor").'</th><th>'.$dl->getLocalizedString("leveldesc").'</th><th>'.$dl->getLocalizedString("stars").'</th><th>'.$dl->getLocalizedString("songIDw").'</th></tr>';
}
$query = $db->prepare("SELECT * FROM levels WHERE unlisted=0 ORDER BY levelID DESC LIMIT 10 OFFSET $page");
$query->execute();
$result = $query->fetchAll();
$x = $page + 1;
if(empty($result)) {
	$dl->printSong('<div class="form">
    <h1>'.$dl->getLocalizedString("errorGeneric").'</h1>
    <form class="form__inner" method="post" action=".">
		<p>'.$dl->getLocalizedString("emptyPage").'</p>
        <button type="submit" class="btn-primary">'.$dl->getLocalizedString("dashboard").'</button>
    </form>
</div>', 'browse');
	die();
} 
foreach($result as &$action){
	$levelid = $action["levelID"];
	$levelname = $action["levelName"];
	$levelDesc = base64_decode($action["levelDesc"]);
  	if(strlen($levelDesc) > 31) $levelDesc = "<details><summary>".$dl->getLocalizedString("spoiler")."</summary>$levelDesc</details>";
  	if(empty($levelDesc)) $levelDesc = '<div style="color:gray">'.$dl->getLocalizedString("noDesc").'</div>';
	$levelpass = $action["password"];
	$levelpass = substr($levelpass, 1);
  	$levelpass = preg_replace('/(0)\1+/', '', $levelpass);
	if($levelpass == 0 OR empty($levelpass)) $levelpass = '<div style="color:gray">'.$dl->getLocalizedString("nopass").'</div>';
	$songid = $action["songID"];
	if($songid == 0) $songid = '<div style="color:#d0d0d0">'.strstr($gs->getAudioTrack($action["audioTrack"]), ' by ', true).'</div>';
	$username =  '<form style="margin:0" method="post" action="profile/"><button style="margin:0" class="accbtn" name="accountID" value="'.$gs->getAccountIDFromName($action["userName"]).'">'.$action["userName"].'</button></form>';
  	$stars = $action["starStars"];
    if($stars < 5) {
          $star = 1;
      } elseif($stars > 4) {
          $star = 2;
      } else {
          $star = 0;
      }
	$stars = $action["starStars"].' '.$dl->getLocalizedString("starsLevel$star");
	if($stars == 0 AND $gs->checkPermission($_SESSION["accountID"], "dashboardModTools")) {
      	$stars = '<a class="dropdown" href="#" data-toggle="dropdown">'.$dl->getLocalizedString("unrated").'</a>
								<div class="dropdown-menu" style="padding:17px 17px 0px 17px; top:0%;">
									 <form class="form__inner" method="post" action="levels/rateLevel.php">
										<div class="field"><input type="number" name="rateStars" placeholder="'.$dl->getLocalizedString("stars").'"></div>
                                        <div class="ratecheck"><input type="radio" style="margin-right:5px;margin-left: 2px" name="featured" value="0">'.$dl->getLocalizedString("isAdminNo").'</input>
                                        <input type="radio" style="margin-right:5px;margin-left: 2px" name="featured" value="1">Featured</input>
                                        <input type="radio" style="margin-right:5px;margin-left: 2px" name="featured" value="2">Epic</div>
										<button type="submit" class="btn-song" name="level" value="'.$levelid.'">'.$dl->getLocalizedString("rate").'</button>
									</form>
								</div>';
	} elseif($stars == 0) $stars = '<div style="color:gray">'.$dl->getLocalizedString("unrated").'</div>';
	if($gs->checkPermission($_SESSION["accountID"], "dashboardModTools")){
	$table .= "<tr><th scope='row'>".$x."</th><td>".$levelid."</td><td>".$levelname."</td><td>".$username."</td><td>".$levelDesc."</td><td>".$levelpass."</td><td>".$stars."</td><td>".$songid."</td></tr>";
	$x++;
	} else {
	$table .= "<tr><th scope='row'>".$x."</th><td>".$levelid."</td><td>".$levelname."</td><td>".$username."</td><td>".$levelDesc."</td><td>".$stars."</td><td>".$songid."</td></tr>";
	$x++;
	}
}
$table .= "</table>";
/*
	bottom row
*/
//getting count
$query = $db->prepare("SELECT count(*) FROM levels WHERE unlisted=0");
$query->execute();
$packcount = $query->fetchColumn();
$pagecount = ceil($packcount / 10);
$bottomrow = $dl->generateBottomRow($pagecount, $actualpage);
$dl->printPage($table . $bottomrow, true, "browse");
?>