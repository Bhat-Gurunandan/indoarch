<?
include('include/constants.inc.php');
include('include/adodb/adodb.inc.php');
include('include/template.inc.php');


$regionid = array_key_exists('regionid', $HTTP_POST_VARS) ? $HTTP_POST_VARS['regionid'] : '0'; 
$subregionid = array_key_exists('subregionid', $HTTP_POST_VARS) ? $HTTP_POST_VARS['subregionid'] : '0' ;
$placeid = array_key_exists('placeid', $HTTP_POST_VARS) ? $HTTP_POST_VARS['placeid'] : '0';
$structurename = array_key_exists('structurename', $HTTP_POST_VARS) ? trim($HTTP_POST_VARS['structurename']) : '';
$structureperiod = array_key_exists('builtin', $HTTP_POST_VARS) ? trim($HTTP_POST_VARS['builtin']) : '';
$structurestyle = array_key_exists('style', $HTTP_POST_VARS) ? trim($HTTP_POST_VARS['style']) : '';
$bodycontent = array_key_exists('content', $HTTP_POST_VARS) ? trim($HTTP_POST_VARS['content']) : '';

$t = new Template('templates');
$t->set_file('formh', 'form.tpl');
$t->set_block('formh', 'regionrow', 'regionlist');
$t->set_block('formh', 'subregionrow', 'subregionlist');
$t->set_block('formh', 'placerow', 'placelist');

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$dbconn = ADONewConnection('mysql');
$dbconn->PConnect($dbhost, $dbuser, $dbpassword, $dbname) or die("Cannot connect to Database");

$regionquery = "select id, name from region order by name";
$region = $dbconn->Execute($regionquery);
if (!$region) die ("Could not execute $regionquery");
while ($row = $region->FetchNextObject(true)) {
  if ($row->ID == $regionid) {
    $t->set_var('regionselected', 'selected');
  }
  else {
    $t->set_var('regionselected', '');
  }    
  $t->set_var('regionid', $row->ID);
  $t->set_var('region', $row->NAME);
  $t->parse('regionlist', 'regionrow', true);
}

$subregionquery = "select id, name from subregion order by name";
$subregion = $dbconn->Execute($subregionquery);
if (!$subregion) die ("Could not execute $subregionquery");
while ($row = $subregion->FetchNextObject(true)) {
  if ($row->ID == $subregionid) {
    $t->set_var('subregionselected', 'selected');
  }
  else {
    $t->set_var('subregionselected', '');
  }
  $t->set_var('subregionid', $row->ID);
  $t->set_var('subregion', $row->NAME);
  $t->parse('subregionlist', 'subregionrow', true);
}

$placequery = "select id, name from place order by name";
$place = $dbconn->Execute($placequery);
if (!$place) die ("Could not execute $placequery");
while ($row = $place->FetchNextObject(true)) {
  if ($row->ID == $placeid) {
    $t->set_var('placeselected', 'selected');
  }
  else {
    $t->set_var('placeselected', '');
  }
  $t->set_var('placeid', $row->ID);
  $t->set_var('place', $row->NAME);
  $t->parse('placelist', 'placerow', true);
}

$t->pparse('output', 'formh');

if ($HTTP_POST_VARS['submitpressed'] == 'submitpressed') {
  $structureid = 0;
  if ($structurename != '') {
    if ($placeid=='0') die("Please select a place.");
    $sql = 'select * from structure where id = -1';
    $emptystructurerecordset = $dbconn->Execute($sql);
    $structurerecord = array();
    $structurerecord['name'] = $structurename;
    $structurerecord['parentid'] = $placeid;
    $structurerecord['builtin'] = $structureperiod;
    $structurerecord['style'] = $structurestyle;
    $insertsql = $dbconn->GetInsertSQL($emptystructurerecordset, $structurerecord);
    $dbconn->Execute($insertsql);
    $structureid = $dbconn->Insert_ID();
  }
  
  if ($placeid) {
    if ($subregionid=='0') die("Please select a sub-region.");
  }
  
  if ($subregionid) {
    if ($regionid=='0') die("Please select a region.");
  }
  
  $contentkey = "R=" . $regionid . "+S=" . $subregionid . "+P=" . $placeid . "+M=" . $structureid;
  $sql = 'select * from content where id = -1';
  $emptycontentrecordset = $dbconn->Execute($sql);
  $contentrecord = array();
  $contentrecord['akey'] = $contentkey;
  $contentrecord['body'] = $bodycontent;
  $contentrecord['eexists'] = 1;
  $insertsql = $dbconn->GetInsertSQL($emptycontentrecordset, $contentrecord);
  $dbconn->Execute($insertsql);
  $contentid = $dbconn->Insert_ID();
  
  for ($icount = 1; $icount <= 12; ++$icount) {
    $ivarname = "image" . $icount;
    if ($HTTP_POST_FILES[$ivarname]['name']!='') {
      $filename = $HTTP_POST_FILES[$ivarname]['name'];
      $tmpfilename = $HTTP_POST_FILES[$ivarname]['tmp_name'];
      $imgtype = $HTTP_POST_FILES[$ivarname]['type'];
      if (!ereg('^image/(.*)', $imgtype, $format)) {
	echo "Uploaded File is not an Image!! Aborting<br>";
	exit();
      }
      $imgformat = $format[1];
      $finalfilename = $imagedirname . $filename;
      if (!is_uploaded_file($tmpfilename)) die ("Could Not Upload File: $filename<br>"); 
      move_uploaded_file($tmpfilename, $finalfilename);
      $sql = 'select * from images where id = -1';
      $emptyimagerecordset = $dbconn->Execute($sql);
      $imagerecord = array();
      $imagerecord['absfname'] = $finalfilename;
      $imagerecord['pagekey'] = $contentkey;
      $insertsql = $dbconn->GetInsertSQL($emptyimagerecordset, $imagerecord);
      $dbconn->Execute($insertsql);
      $imageid = $dbconn->Insert_ID();
    }
  }
}
?>
