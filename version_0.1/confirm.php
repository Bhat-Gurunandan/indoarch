<?
include('include/constants.inc.php');
include('include/adodb/adodb.inc.php');
include('include/template.inc.php');
include('include/libfuncs.php');
$t = new Template('templates');
$t->set_file('defaultpageh', 'defaultpage.tpl');

$t->set_var('titlestrip', 'header_order.gif');
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

$copies = $HTTP_POST_VARS['number'];
if (!ereg('^[0-9]+$', $copies) || $copies == "0") {
  $t->set_file('contenth', 'invalidcopies.tpl');
  $t->parse('content', 'contenth');
  $t->pparse('thispage', 'defaultpageh');
  exit();
}

$workingkey = '4x1z8icaia4bc2ntd75jg2rwiz0pofmv';  
$redirecturl = 'http://www.indoarch.com';
$referer = $HTTP_POST_VARS['location'];
if ($referer == 'inside') {
  $cost = 1200 * $copies;
  $inrcost = $cost;
  $orderid = date('ymdHis');
  $checksum = getchecksum('M_archauto_1683', $inrcost, $orderid , $redirecturl, $workingkey);
  $t->set_var('orderid', $orderid);
  $t->set_var('cost', $cost);
  $t->set_var('inrcost', $inrcost);
  $t->set_var('copies', $copies);
  $t->set_var('redirecturl', $redirecturl);
  $t->set_var('checksum', $checksum);
  $t->set_var('currency', "Indian Rupees");
  $t->set_var('location', "inside");
  $t->set_file('contenth', 'confirm.tpl');
}
elseif ($referer == 'outside') {
  $cost = 35 * $copies;
  $inrcost = 45.5 * $cost;
  $orderid = date('ymdHis');
  $checksum = getchecksum('M_archauto_1683', $inrcost, $orderid , $redirecturl, $workingkey);
  $t->set_var('orderid', $orderid);
  $t->set_var('cost', $cost);
  $t->set_var('inrcost', $inrcost);
  $t->set_var('copies', $copies);
  $t->set_var('redirecturl', $redirecturl);
  $t->set_var('checksum', $checksum);
  $t->set_var('currency', "US Dollars");
  $t->set_var('location', "outside");
  $t->set_file('contenth', 'confirm.tpl');
}
else {
  $t->set_file('contenth', 'errororder.tpl');
}

$t->parse('content', 'contenth');
$t->pparse('thispage', 'defaultpageh');
?>
