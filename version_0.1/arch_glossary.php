<?
include('include/constants.inc.php');
include('include/adodb/adodb.inc.php');
include('include/template.inc.php');

$t = new Template('templates');
$t->set_file('defaultpageh', 'defaultpage.tpl');
$t->set_file('contenth', 'glossary.tpl');
$t->set_var('titlestrip', 'blank.gif');
$t->set_block('defaultpageh', 'placerow', 'placelist');

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

$t->parse('content', 'contenth');
$t->pparse('thispage', 'defaultpageh');

?>
