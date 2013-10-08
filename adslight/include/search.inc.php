<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops                           

        Redesigned and ameliorate By Luc Bizet user at www.frxoops.org
		Started with the Classifieds module and made MANY changes 
        Website : http://www.luc-bizet.fr
        Contact : adslight.translate@gmail.com
-------------------------------------------------------------------------
             Original credits below Version History                       
##########################################################################
#                    Classified Module for Xoops                         #
#  By John Mordo user jlm69 at www.xoops.org and www.jlmzone.com         #
#      Started with the MyAds module and made MANY changes               #
##########################################################################
 Original Author: Pascal Le Boustouller                                   
 Author Website : pascal.e-xoops@perso-search.com                         
 Licence Type   : GPL                                                     
------------------------------------------------------------------------- 
*/


include_once (XOOPS_ROOT_PATH."/modules/adslight/include/functions.php");

function adslight_search($queryarray, $andor, $limit, $offset, $userid){

	if (strpos($_SERVER["REQUEST_URI"], "/modules/adslight/search.php"))
	{
	$visible = true;
	}else{
	$visible = false;
	}

	global $xoopsDB, $xoopsModuleConfig, $mydirname;
	
	$sql = "SELECT lid,title,type,desctext,tel,price,typeprice,date,submitter,usid,town,country FROM ".$xoopsDB->prefix("adslight_listing")." WHERE valid='Yes' AND status!='1' AND date<=".time()."";

	if ( $userid != 0 ) {
		$sql .= " AND usid=".$userid." ";
	}
	
//if (lid=lid)
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((title LIKE '%$queryarray[0]%' OR type LIKE '%$queryarray[0]%' OR desctext LIKE '%$queryarray[0]%' OR tel LIKE '%$queryarray[0]%' OR price LIKE '%$queryarray[0]%' OR typeprice LIKE '%$queryarray[0]%' OR submitter LIKE '%$queryarray[0]%' OR town LIKE '%$queryarray[0]%' OR country LIKE '%$queryarray[0]%' )";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(title LIKE '%$queryarray[$i]%' OR type LIKE '%$queryarray[$i]%' OR desctext LIKE '%$queryarray[$i]%' OR tel LIKE '%$queryarray[$i]%' OR price LIKE '%$queryarray[$i]%' OR typeprice LIKE '%$queryarray[$i]%' OR submitter LIKE '%$queryarray[$i]%' OR town LIKE '%$queryarray[$i]%' OR country LIKE '%$queryarray[$i]%' )";
		}
		$sql .= ") ";
	}
	$sql .= " ORDER BY premium DESC, date DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $xoopsDB->fetchArray($result)){

	$myts =& MyTextSanitizer::getInstance();
	$result2 = $xoopsDB->query("SELECT url FROM ".$xoopsDB->prefix("adslight_pictures")." WHERE lid=".$myrow['lid']." ORDER BY date_added LIMIT 1 ");
	list($url) = $xoopsDB->fetchRow($result2);
    $url = $myts->htmlSpecialChars($url);
    
		$ret[$i]['image'] = "images/deco/icon.png";
		$ret[$i]['link'] = "viewads.php?lid=".$myrow['lid']."";
		$ret[$i]['title'] = $myrow['title'];
		$ret[$i]['type'] = adslight_NameType($myrow['type']);
		$ret[$i]['price'] = number_format($myrow['price'], 2, ".", ",");
		$ret[$i]['typeprice'] = $myrow['typeprice'];
		$ret[$i]['town'] = $myrow['town'];
		$ret[$i]['desctext'] = $myts->displayTarea($myrow['desctext'],1,1,1,1,1);
		$ret[$i]['nophoto'] = "images/nophoto.jpg";
		$ret[$i]['photo'] = $url;
		if ( $visible ){
		$ret[$i]['sphoto'] = $xoopsModuleConfig['adslight_link_upload']."thumbs/thumb_".$url."";
		}
		$ret[$i]['time'] = $myrow['date'];
		$ret[$i]['uid'] = $myrow['usid'];
		$i++;	
	}
	return $ret;
}