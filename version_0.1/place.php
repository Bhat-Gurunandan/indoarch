<?
include('include/constants.inc.php');
include('include/adodb/adodb.inc.php');
include('include/template.inc.php');

$t = new Template('templates');
$t->set_file('insidepageh', 'insidepage.tpl');


$t->set_block('insidepageh', 'placerow', 'placelist');
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$dbconn = ADONewConnection('mysql');
$dbconn->PConnect($dbhost, $dbuser, $dbpassword, $dbname) or die("Cannot connect to Database");
$placequery = "select place.name as name, 
                      concat('R=', region.id, '+S=', subregion.id, '+P=', place.id, '+M=0') as link 
               from   place, subregion, region 
               where  place.parentid=subregion.id and 
                      subregion.parentid=region.id 
               order by place.name";
$place = $dbconn->Execute($placequery);
if (!$place) die ("Could not execute $placequery");
while ($row = $place->FetchNextObject(true)) {
  $t->set_var('placeid', urlencode($row->LINK));
  $t->set_var('place', $row->NAME);
  $t->parse('placelist', 'placerow', true);
}
$placelink = $HTTP_POST_VARS['placelink'] ? $HTTP_POST_VARS['placelink'] : $HTTP_GET_VARS['placelink'];
list($regionl, $subregionl, $placel, $structurel) = explode("+", $placelink);
list($r, $region) = explode("=", $regionl);
list($s, $subregion) = explode("=", $subregionl);
list($p, $place) = explode("=", $placel);
list($m, $structure) = explode("=", $structurel);

if ($region == 0) {
  // treat this like a country
  $t->set_var('locationtitle', 'Regions.gif');
  $t->set_var('locationheading', 'Regions of the Subcontinent');
  $t->set_block('insidepageh', 'location', 'locationlist');
  $regionquery = "select region.name, concat('R=', region.id, '+S=0+P=0+M=0') as link from region order by region.name";
  $regionresult = $dbconn->Execute($regionquery);
  if (!$regionresult) die ("Could not execute $regionquery");
  while ($regionrow = $regionresult->FetchNextObject(true)) {
    $t->set_var('locationname', $regionrow->NAME);
    $t->set_var('locationlink', urlencode($regionrow->LINK));
    $t->parse('locationlist', 'location', true);
  }
  $contentquery = "select body from content where akey = 'R=0+S=0+P=0+M=0'";
  $contentresult = $dbconn->Execute($contentquery);
  $contentrow = $contentresult->FetchNextObject(true);
  $body = $contentrow->BODY;
  $t->set_var('body', $body);
}
elseif ($subregion == 0) {
  // treat this like a region
  $t->set_var('locationtitle', 'States.gif');
  $namequery = "select name from region where id = " . $region;
  $nameresult = $dbconn->Execute($namequery) or die("Could not execute $namequery<br>");
  $namerow = $nameresult->FetchNextObject(true);
  $name = $namerow->NAME;
  $t->set_var('locationheading', "States in $name"); 
  $title = "<p><b>About " . $name . "</b></p>"; 
  $t->set_block('insidepageh', 'location', 'locationlist');
  $t->set_var('uplink', urlencode("R=0+S=0+P=0+M=0"));
  $t->set_var('uplinktext', '<img src="images/Button.gif">');
  $t->set_var('uplinkaccompanyingtext', "Move Up to See All Regions of the Subcontinent"); 
  $subregionquery = "select subregion.name, concat('R=" . $region . "+S=', subregion.id, '+P=0+M=0') as link from subregion where subregion.parentid = " . $region;
  $subregionresult = $dbconn->Execute($subregionquery);
  if (!$subregionresult) die ("Could not execute $subregionquery");
  while ($subregionrow = $subregionresult->FetchNextObject(true)) {
    $t->set_var('locationname', $subregionrow->NAME);
    $t->set_var('locationlink', urlencode($subregionrow->LINK));
    $t->parse('locationlist', 'location', true);
  }
  $contentquery = "select body from content where akey = 'R=" . $region ."+S=0+P=0+M=0'";
  $contentresult = $dbconn->Execute($contentquery);
  $contentrow = $contentresult->FetchNextObject(true);
  $body = $title . $contentrow->BODY;
  $t->set_var('body', $body);
  $imagequery = "select fname, tbabsfname from images where pagekey = 'R=" . $region ."+S=0+P=0+M=0'";
  $imageresult = $dbconn->Execute($imagequery);
  $imagecount = $imageresult->RecordCount();
  if ($imagecount > 0) {
    $t->set_var('imagecount', 2*$imagecount);
    $imagerow = $imageresult->FetchNextObject(true);
    $file = $imagerow->FNAME;
    $image = "<img class=\"framed\" src=\"images/$file\">";
    $t->set_var('image0', $image);
    $bigimage = "bigimages/$file";
    $t->set_var('bigimage0', $bigimage);
    $t->set_var('imagecaption0', $imagerow->TBABSFNAME);
    $t->set_block('insidepageh', 'imagerow', 'imagelist');
    while ($imagerow = $imageresult->FetchNextObject(true)) {
      $file = $imagerow->FNAME;
      $image = "<img class=\"framed\" src=\"images/$file\">";
      $t->set_var('image', $image);
      $bigimage = "bigimages/$file";
      $t->set_var('bigimage', $bigimage);
      $t->set_var('imagecaption', $imagerow->TBABSFNAME);
      $t->parse('imagelist', 'imagerow', true);
    }
  }
}
elseif ($place == 0) {
  // treat this like a subregion
  $t->set_var('locationtitle', 'Cities.gif');
  $namequery = "select name from subregion where id = " . $subregion;
  $nameresult = $dbconn->Execute($namequery);
  $namerow = $nameresult->FetchNextObject(true);
  $name = $namerow->NAME;
  $t->set_var('locationheading', "Cities in $name"); 
  $title = "<p><b>About " . $name . "</b></p>"; 
  $t->set_block('insidepageh', 'location', 'locationlist');
  $t->set_var('uplink', urlencode("R=" . $region . "+S=0+P=0+M=0"));
  $t->set_var('uplinktext', '<img src="images/Button.gif">');
  $t->set_var('uplinkaccompanyingtext', "Move Up to See All States of this Region"); 
  $placequery = "select place.name, concat('R=" . $region . "+S=" . $subregion . "+P=', place.id, '+M=0') as link from place where place.parentid = " . $subregion;
  $placeresult = $dbconn->Execute($placequery);
  if (!$placeresult) die ("Could not execute $placequery");
  while ($placerow = $placeresult->FetchNextObject(true)) {
    $t->set_var('locationname', $placerow->NAME);
    $t->set_var('locationlink', urlencode($placerow->LINK));
    $t->parse('locationlist', 'location', true);
  }
  $contentquery = "select body from content where akey = 'R=" . $region ."+S=" . $subregion . "+P=0+M=0'";
  $contentresult = $dbconn->Execute($contentquery);
  $contentrow = $contentresult->FetchNextObject(true);
  $body = $title . $contentrow->BODY;
  $t->set_var('body', $body);
  $imagequery = "select fname, tbabsfname from images where pagekey = 'R=" . $region ."+S=" . $subregion . "+P=0+M=0'";
  $imageresult = $dbconn->Execute($imagequery);
  $imagecount = $imageresult->RecordCount();
  if ($imagecount > 0) {
    $t->set_var('imagecount', 2*$imagecount);
    $imagerow = $imageresult->FetchNextObject(true);
    $file = $imagerow->FNAME;
    $image = "<img class=\"framed\" src=\"images/$file\">";
    $t->set_var('image0', $image);
    $bigimage = "bigimages/$file";
    $t->set_var('bigimage0', $bigimage);
    $t->set_var('imagecaption0', $imagerow->TBABSFNAME);
    $t->set_block('insidepageh', 'imagerow', 'imagelist');
    while ($imagerow = $imageresult->FetchNextObject(true)) {
      $file = $imagerow->FNAME;
      $image = "<img class=\"framed\" src=\"images/$file\">";
      $t->set_var('image', $image);
      $bigimage = "bigimages/$file";
      $t->set_var('bigimage', $bigimage);
      $t->set_var('imagecaption', $imagerow->TBABSFNAME);
      $t->parse('imagelist', 'imagerow', true);
    }
  }
}
elseif ($structure == 0) {
  // treat this like a place
  $t->set_var('locationtitle', 'Monuments.gif');
  $namequery = "select name from place where id = " . $place;
  $nameresult = $dbconn->Execute($namequery);
  $namerow = $nameresult->FetchNextObject(true);
  $name = $namerow->NAME;
  $t->set_var('locationheading', "Monuments in $name"); 
  //$title = "<p><b>About " . $name . "</b></p>";
  $title = '';
  $t->set_block('insidepageh', 'location', 'locationlist');
  $t->set_var('uplink', urlencode("R=" . $region . "+S=" . $subregion . "+P=0+M=0"));
  $t->set_var('uplinktext', '<img src="images/Button.gif">');
  $t->set_var('uplinkaccompanyingtext', "Move Up to See All Cities of this State"); 
  $structurequery = "select structure.name, concat('R=" . $region . "+S=" . $subregion . "+P=" . $place . "+M=', structure.id) as link from structure where structure.parentid = " . $place . " order by name";
  $structureresult = $dbconn->Execute($structurequery);
  if (!$structureresult) die ("Could not execute $structurequery");
  while ($structurerow = $structureresult->FetchNextObject(true)) {
    $t->set_var('locationname', $structurerow->NAME);
    $t->set_var('locationlink', urlencode($structurerow->LINK));
    $t->parse('locationlist', 'location', true);
  }
  $contentquery = "select body from content where akey = 'R=" . $region ."+S=" . $subregion . "+P=" . $place . "+M=0'";
  $contentresult = $dbconn->Execute($contentquery);
  $contentrow = $contentresult->FetchNextObject(true);
  $body = $title . $contentrow->BODY;
  $t->set_var('body', $body);
  $imagequery = "select fname, tbabsfname from images where pagekey = 'R=" . $region ."+S=" . $subregion . "+P=" . $place . "+M=0'";
  $imageresult = $dbconn->Execute($imagequery);
  $imagecount = $imageresult->RecordCount();
  if ($imagecount > 0) {
    $t->set_var('imagecount', 2*$imagecount);
    $imagerow = $imageresult->FetchNextObject(true);
    $file = $imagerow->FNAME;
    $image = "<img class=\"framed\" src=\"images/$file\">";
    $t->set_var('image0', $image);
    $bigimage = "bigimages/$file";
    $t->set_var('bigimage0', $bigimage);
    $t->set_var('imagecaption0', $imagerow->TBABSFNAME);
    $t->set_block('insidepageh', 'imagerow', 'imagelist');
    while ($imagerow = $imageresult->FetchNextObject(true)) {
      $file = $imagerow->FNAME;
      $image = "<img class=\"framed\" src=\"images/$file\">";
      $t->set_var('image', $image);
      $bigimage = "bigimages/$file";
      $t->set_var('bigimage', $bigimage);
      $t->set_var('imagecaption', $imagerow->TBABSFNAME);
      $t->parse('imagelist', 'imagerow', true);
    }
  }
}
else {
  // treat this like a structure
  $t->set_var('locationtitle', 'Monuments.gif');
  $t->set_var('uplink', urlencode("R=" . $region . "+S=" . $subregion . "+P=" . $place . "+M=0"));
  $t->set_var('uplinktext', '<img src="images/Button.gif">');
  $t->set_var('uplinkaccompanyingtext', "Move Up to See All Monuments in this City"); 
  $structurequery = "select structure.name, structure.style, structure.builtin from structure where structure.id = " . $structure;
  $structureresult = $dbconn->Execute($structurequery);
  $structurerow = $structureresult->FetchNextObject(true);
  $structurename = $structurerow->NAME;
  $structurestyle = $structurerow->STYLE;
  $structurebuiltin = $structurerow->BUILTIN;
  $contentquery = "select body from content where akey = 'R=" . $region ."+S=" . $subregion . "+P=" . $place . "+M=" . $structure . "'";
  $contentresult = $dbconn->Execute($contentquery);
  $contentrow = $contentresult->FetchNextObject(true);
  $body = "<p>
           <b> $structurename </b><br>
           <b> $structurestyle </b><br>
           <b> $structurebuiltin </b>
           </p>" . $contentrow->BODY;
  $t->set_var('body', $body);
  $imagequery = "select fname, tbabsfname from images where pagekey = 'R=" . $region ."+S=" . $subregion . "+P=" . $place . "+M=" . $structure . "'";
  $imageresult = $dbconn->Execute($imagequery);
  $imagecount = $imageresult->RecordCount();
  if ($imagecount > 0) {
    $t->set_var('imagecount', 2*$imagecount);
    $imagerow = $imageresult->FetchNextObject(true);
    $file = $imagerow->FNAME;
    $image = "<img class=\"framed\" src=\"images/$file\">";
    $t->set_var('image0', $image);
    $bigimage = "bigimages/$file";
    $t->set_var('bigimage0', $bigimage);
    $t->set_var('imagecaption0', $imagerow->TBABSFNAME);
    $t->set_block('insidepageh', 'imagerow', 'imagelist');
    while ($imagerow = $imageresult->FetchNextObject(true)) {
      $file = $imagerow->FNAME;
      $image = "<img class=\"framed\" src=\"images/$file\">";
      $t->set_var('image', $image);
      $bigimage = "bigimages/$file";
      $t->set_var('bigimage', $bigimage);
      $t->set_var('imagecaption', $imagerow->TBABSFNAME);
      $t->parse('imagelist', 'imagerow', true);
    }
  }
}
  $t->pparse('thispage', 'insidepageh');
?>
