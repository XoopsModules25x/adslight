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

if (!defined('XOOPS_ROOT_PATH')) {
	trigger_error ('Access not found');
	exit('Access not found');
}
	$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

function adslight_notify_iteminfo($category, $item_id)
{
  global $xoopsDB, $mydirname;
	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname("$mydirname");

	if ($category=='global') {
		$item['name'] = '';
		$item['url'] = '';
		return $item;
	}

	if ($category=='category') {

		// Assume we have a valid topid id
		$sql = 'SELECT SQL_CACHE title  FROM '. $xoopsDB->prefix("adslight_categories") .' WHERE cid = '. $item_id .' limit 1';

		$result = $xoopsDB->query($sql); // TODO: error check
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['title'];		
		$item['url'] = XOOPS_URL . '/modules/adslight/index.php?pa=adsview&amp;cid=' .  $item_id;
		return $item;
	}

	if ($category=='listing') {
		// Assume we have a valid post id
		$sql = 'SELECT title FROM ' . $xoopsDB->prefix("adslight_listing").  ' WHERE lid = ' . $item_id . ' LIMIT 1';
		$result = $xoopsDB->query($sql);
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['title'];
//		$item['catname'] = $result_array['cat.title'];
		$item['url'] = XOOPS_URL . '/modules/adslight/viewads.php?lid= ' .  $item_id;
		return $item;
	}
}