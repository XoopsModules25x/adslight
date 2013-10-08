<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops                           

        Redesigned and ameliorate By iluc user at www.frxoops.org
		Started with the Classifieds module and made MANY changes 
        Website : http://www.limonads.com
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

include 'header.php';
require XOOPS_ROOT_PATH.'/modules/adslight/include/gtickets.php';

$myts =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

(is_object($xoopsUser)) ? $groups = $xoopsUser->getGroups() : $groups = XOOPS_GROUP_ANONYMOUS;

$gperm_handler =& xoops_gethandler('groupperm');

(isset($_POST['item_id'])) ? $perm_itemid = intval($_POST['item_id']) : $perm_itemid = 0;


if (!$gperm_handler->checkRight('adslight_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL.'/index.php', 3, _NOPERM);
    exit();
    }

(!$gperm_handler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) ? $prem_perm = '0' : $prem_perm = '1';


include XOOPS_ROOT_PATH.'/modules/adslight/class/classifiedstree.php';
include XOOPS_ROOT_PATH.'/modules/adslight/include/functions.php';
$mytree = new ClassifiedsTree($xoopsDB->prefix('adslight_categories'),'cid','pid');

#  function index
#####################################################
function index()
{
	global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $xoopsTpl, $myts, $mytree, $meta, $mid, $mydirname, $main_lang, $prem_perm, $xoopsModule;
    $pathIcon16 = $xoopsModule->getInfo('icons16');
	
	$GLOBALS['xoopsOption']['template_main'] = 'adslight_index.html';
	
	include XOOPS_ROOT_PATH.'/header.php';

	$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
	$xoopsTpl->assign('add_from', _ADSLIGHT_ADDFROM." ".$xoopsConfig['sitename']);
	$xoopsTpl->assign('add_from_title', _ADSLIGHT_ADDFROM );
	$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
	$xoopsTpl->assign('only_pix', _ADSLIGHT_ONLYPIX );
	$xoopsTpl->assign('adslight_logolink', _ADSLIGHT_LOGOLINK );
	$xoopsTpl->assign('permit', $prem_perm);
	
	$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" href="'.XOOPS_URL.'/modules/adslight/style/adslight.css" type="text/css" media="all" />');
	
	$banner = xoops_getbanner();
	$xoopsTpl->assign('banner', $banner);
	$xoopsTpl->assign('use_extra_code', $xoopsModuleConfig['adslight_use_index_code']);
	$xoopsTpl->assign('adslight_use_banner', $xoopsModuleConfig['adslight_use_banner']);
	$xoopsTpl->assign('index_extra_code', $xoopsModuleConfig['adslight_index_code']);
	$xoopsTpl->assign('index_code_place', $xoopsModuleConfig['adslight_index_code_place']);
	$xoopsTpl->assign('category_title2', _ADSLIGHT_ANNONCES);
	// adslight 2
	$xoopsTpl->assign('adslight_active_menu', $xoopsModuleConfig['adslight_active_menu']);
	$xoopsTpl->assign('adslight_active_rss', $xoopsModuleConfig['adslight_active_rss']);
	
	ExpireAd();

	    if ($xoopsUser) {
	$member_usid = $xoopsUser->getVar('uid');
	if ($usid = $member_usid) {
		$xoopsTpl->assign('istheirs', true);
		
	list($show_user) = $xoopsDB->fetchRow($xoopsDB->query('select SQL_CACHE COUNT(*) FROM '.$xoopsDB->prefix('adslight_listing').' WHERE usid='.$member_usid.''));

	$xoopsTpl->assign('show_user', $show_user);
	$xoopsTpl->assign('show_user_link', 'members.php?usid='.$member_usid.'');
		}
	}

	$result = $xoopsDB->query('select SQL_CACHE COUNT(*)  FROM '.$xoopsDB->prefix('adslight_listing').' WHERE valid="No"');
		list($propo) = $xoopsDB->fetchRow($result);


if ($propo > 0) {
	$xoopsTpl->assign('moderated', true);
	}
	    if ($xoopsUser) {
			if ($xoopsUser->isAdmin()) {
				$xoopsTpl->assign('admin_block', _ADSLIGHT_ADMINCADRE);
				if($propo == 0) {
					$xoopsTpl->assign('confirm_ads', _ADSLIGHT_NO_CLA);
				} else {
					$xoopsTpl->assign('confirm_ads', _ADSLIGHT_THEREIS.' '.$propo.'  '._ADSLIGHT_WAIT.'<br /><a href="'.XOOPS_URL.'/modules/adslight/admin/validate_ads.php">'._ADSLIGHT_SEEIT.'</a>');
				}
			}

$categories = adslight_MygetItemIds('adslight_submit');
if(is_array($categories) && count($categories) > 0) {
	$intro = _ADSLIGHT_INTRO;
} else {	
	$intro = "";
}
	$xoopsTpl->assign('intro', $intro);

		}

	
	$sql = 'SELECT SQL_CACHE cid, title, img FROM '.$xoopsDB->prefix('adslight_categories').' WHERE pid = 0 ';

$categories = adslight_MygetItemIds('adslight_view');
if(is_array($categories) && count($categories) > 0) {
	$sql .= ' AND cid IN ('.implode(',', $categories).') ';
} else {	
	redirect_header(XOOPS_URL.'/index.php', 3, _NOPERM);
	exit();	
}

if ($xoopsModuleConfig['adslight_csortorder'] == 'ordre') {
			$sql .= 'ORDER BY ordre';
		} else {
$sql .= 'ORDER BY title';
}

$result = $xoopsDB->query($sql);

$count = 1;
$content = '';
while($myrow = $xoopsDB->fetchArray($result)) {
	$title = $myts->htmlSpecialChars($myrow['title']);
	
	if ($myrow['img'] && $myrow['img'] != 'http://'){

		$cat_img = $myts->htmlSpecialChars($myrow['img']);
		$img = '<a href="viewcats.php?cid='.$myrow['cid'].'"><img src="'.XOOPS_URL.'/modules/adslight/images/img_cat/'.$cat_img.'" align="middle" alt="'.$title.'" /></a>';

	} else {
		$img = '';
	}
	
	$totallisting = adslight_getTotalItems($myrow['cid'], 1);
	$content .= $title.' ';

	$arr = array();
	if(in_array($myrow['cid'], $categories)) {
		$arr = $mytree->getFirstChild($myrow['cid'], 'title');
		$space = 0;
		$chcount = 1;
		$subcategories = '';
	if ($xoopsModuleConfig["adslight_souscat"] == 1) {
		foreach($arr as $ele){
			if(in_array($ele['cid'], $categories)) {
				$chtitle=$myts->htmlSpecialChars($ele['title']);
				if ($chcount>$xoopsModuleConfig['adslight_nbsouscat']) {
					$subcategories .= '<a href="viewcats.php?cid='.$myrow['cid'].'">'._ADSLIGHT_CATPLUS.'</a>';
					break;
				}
				if ($space>0) {
					$subcategories .= '<br />';
				}
				$subcategories .= '-&nbsp;<a href="'.XOOPS_URL.'/modules/adslight/viewcats.php?cid='.$ele['cid'].'">'.$chtitle.'</a>';
				$space++;
				$chcount++;
				$content .= $ele['title'].' ';
			}
		}
	}
		$xoopsTpl->append('categories', array('image' => $img, 'id' => $myrow['cid'], 'title' => $myts->htmlSpecialChars($myrow['title']), 'new' => categorynewgraphic($myrow['cid']), 'subcategories' => $subcategories, 'totallisting' => $totallisting, 'count' => $count));
		 $count++;
	}
}
	$cat_perms = '';
if(is_array($categories) && count($categories) > 0) {
	$cat_perms .= ' AND cid IN ('.implode(',', $categories).') ';
}

	list($ads) = $xoopsDB->fetchRow($xoopsDB->query("select SQL_CACHE COUNT(*)  FROM ".$xoopsDB->prefix('adslight_listing')." WHERE valid='Yes' AND status!='1' $cat_perms"));

	list($catt) = $xoopsDB->fetchRow($xoopsDB->query("select COUNT(*)  FROM ".$xoopsDB->prefix("".$mydirname."_categories").""));
	
	$submit_perms = adslight_MygetItemIds('adslight_submit');
	
	if($xoopsUser) {
		$add_listing = ''._ADSLIGHT_ADD_LISTING_BULLOK.'<a href="add.php">'._ADSLIGHT_ADD_LISTING_SUBOK.'</a>';	
	} else {	
		$add_listing = ''._ADSLIGHT_ADD_LISTING_BULL.'<a href="'.XOOPS_URL.'/register.php">'._ADSLIGHT_ADD_LISTING_SUB.'</a>.';
	}
	
	$xoopsTpl->assign('bullinfotext', _ADSLIGHT_ACTUALY.  ' '.$ads.' '  ._ADSLIGHT_ADVERTISEMENTS.'<br />'.$add_listing);
	$xoopsTpl->assign('total_confirm', _ADSLIGHT_AND." $propo "._ADSLIGHT_WAIT3);

	if ($xoopsModuleConfig['adslight_newad'] == 1) {
	$cat_perms = "";
	if(is_array($categories) && count($categories) > 0) {
	$cat_perms .= ' AND cid IN ('.implode(',', $categories).') ';
	}

	$result=$xoopsDB->query("select SQL_CACHE lid, title, status, type, price, typeprice, date, town, country, usid, premium, valid, photo, hits from ".$xoopsDB->prefix('adslight_listing')." WHERE valid='Yes' and status!='1' $cat_perms ORDER BY date DESC LIMIT ".$xoopsModuleConfig['adslight_newcount'].'');
	if ($result){
		$xoopsTpl->assign('last_head', _ADSLIGHT_THE.' '.$xoopsModuleConfig['adslight_newcount'].' '._ADSLIGHT_LASTADD);
		$xoopsTpl->assign('last_head_title', _ADSLIGHT_TITLE);
		$xoopsTpl->assign('last_head_price', _ADSLIGHT_PRICE);
		$xoopsTpl->assign('last_head_date', _ADSLIGHT_DATE);
		$xoopsTpl->assign('last_head_local', _ADSLIGHT_LOCAL2);
		$xoopsTpl->assign('last_head_hits', _ADSLIGHT_VIEW);
		$xoopsTpl->assign('last_head_photo', _ADSLIGHT_PHOTO);
		$rank = 1;

		while(list($lid, $title, $status, $type, $price, $typeprice, $date, $town, $country, $usid, $premium, $valid, $photo, $hits)=$xoopsDB->fetchRow($result)) {

			$title = $myts->htmlSpecialChars($title);
			$type = $myts->htmlSpecialChars($type);
			$price = number_format($price, 2, ',', ' ');
			$town = $myts->htmlSpecialChars($town);
			$country = $myts->htmlSpecialChars($country);
			$premium = $myts->htmlSpecialChars($premium);
            $a_item = array();
		    $newcount = $xoopsModuleConfig['adslight_countday'];
		    $startdate = (time()-(86400 * $newcount));
		    
		if ($startdate < $date) {
		$newitem = '<img src="'.XOOPS_URL.'/modules/adslight/images/newred.gif" alt="new" />';
		$a_item['new'] = $newitem;
			}

			$useroffset = '';
	    	if($xoopsUser) {
			$timezone = $xoopsUser->timezone();
		if(isset($timezone)) {
			$useroffset = $xoopsUser->timezone();
			} else {
			$useroffset = $xoopsConfig['default_TZ'];
				}
			}

			$date = ($useroffset*3600) + $date;
			$date = formatTimestamp($date,'s');
		if ($xoopsUser) {
			if ($xoopsUser->isAdmin()) {
				$a_item['admin'] = '<a href="'.XOOPS_URL.'/modules/adslight/admin/validate_ads.php?op=ModifyAds&amp;lid='.$lid.'"><img src="'. $pathIcon16 .'/edit.png'.'" border=0 alt="'._ADSLIGHT_MODADMIN.'" /></a>';
			}
			}
			
			$result7=$xoopsDB->query("select nom_type from ".$xoopsDB->prefix("adslight_type")." WHERE id_type=".mysql_real_escape_string($type)."");
			list($nom_type) = $xoopsDB->fetchRow($result7);

			$a_item['type'] = $myts->htmlSpecialChars($nom_type);
			$a_item['title'] = '<a href="'.XOOPS_URL.'/modules/adslight/viewads.php?lid='.$lid.'"><strong>'.$title.'</strong></a>';
			
			$result8=$xoopsDB->query("select nom_price from ".$xoopsDB->prefix("adslight_price")." WHERE id_price=".mysql_real_escape_string($typeprice)."");
			list($nom_price) = $xoopsDB->fetchRow($result8);
			
		if ($price > 0) {
			
			$a_item['price'] = $price. ' '. $xoopsModuleConfig['adslight_money'].'';
			$a_item['price_typeprice'] = $myts->htmlSpecialChars($nom_price);
		}else {
			$a_item['price'] = '';
			$a_item['price_typeprice'] = $myts->htmlSpecialChars($nom_price);
				}
			$a_item['premium'] = $premium;
			$a_item['date'] = $date;
			$a_item['local'] = '';
			if ($town) {
				$a_item['local'] .= $town;
			}
			$a_item['country'] = '';
			if ($country) {
			$a_item['country'] = $country;
			}

		if ($status == 2) {
		$a_item['sold'] = _ADSLIGHT_RESERVEDMEMBER;
		}
		
if ( $xoopsModuleConfig['active_thumbsindex'] > 0 )
{		
		$a_item['no_photo'] = '<a href="'.XOOPS_URL.'/modules/adslight/viewads.php?lid='.$lid.'"><img class="thumb" src="'.XOOPS_URL.'/modules/adslight/images/nophoto.jpg" align="left" width="100px" alt="'.$title.'" /></a>';
		
		$updir = $xoopsModuleConfig['adslight_link_upload'];
		$sql = "select cod_img, lid, uid_owner, url from ".$xoopsDB->prefix('adslight_pictures')." where  uid_owner=".mysql_real_escape_string($usid)." and lid=".mysql_real_escape_string($lid)." order by date_added ASC limit 1";
		$resultp = $xoopsDB->query($sql);
		
while(list($cod_img, $pic_lid, $uid_owner, $url)=$xoopsDB->fetchRow($resultp)) {
		
	if ($photo) {
				$a_item['photo'] = '<a href="'.XOOPS_URL.'/modules/adslight/viewads.php?lid='.$lid.'"><img class="thumb" src="'.$updir.'/thumbs/thumb_'.$url.'" align="left" width="100px" alt="'.$title.'" /></a>';
			}
		}
} else {

		$a_item['no_photo'] = '<img src="'.XOOPS_URL.'/modules/adslight/images/camera_nophoto.png" align="left" width="24" alt="'.$title.'" />';
		$updir = $xoopsModuleConfig['adslight_link_upload'];
		$sql = "select cod_img, lid, uid_owner, url from ".$xoopsDB->prefix('adslight_pictures')." where  uid_owner=".mysql_real_escape_string($usid)." and lid=".mysql_real_escape_string($lid)." order by date_added ASC limit 1";
		$resultp = $xoopsDB->query($sql);
		
		while(list($cod_img, $pic_lid, $uid_owner, $url)=$xoopsDB->fetchRow($resultp)) {
			if ($photo) {
				$a_item['photo'] = '<img src="'.XOOPS_URL.'/modules/adslight/images/camera_photo.png" align="left" width="24" alt="'.$title.'" />';
			}
		}			
	
	
	}		
			$a_item['hits'] = $hits;
			$rank++;
			$xoopsTpl->append('items', $a_item);
			}
		}
	}
}


#  function categorynewgraphic
#####################################################
function categorynewgraphic($cid)
{
    global $xoopsDB, $xoopsModuleConfig;

}

######################################################

$pa = !isset($_GET['pa'])? NULL : $_GET['pa'];
$lid = !isset($_GET['lid'])? NULL : $_GET['lid'];
$cid = !isset($_GET['cid'])? NULL : $_GET['cid'];
$usid = isset( $_GET['usid'] ) ? $_GET['usid'] : '' ;
$min = !isset($_GET['min'])? NULL : $_GET['min'];
$show = !isset($_GET['show'])? NULL : $_GET['show'];
$orderby = !isset($_GET['orderby'])? NULL : $_GET['orderby'];

switch($pa)
{
	case 'Adsview':
		$xoopsOption['template_main'] = 'adslight_category.html';
		Adsview($cid,$min,$orderby,$show);
		break;
	case 'viewads':
		$xoopsOption['template_main'] = 'adslight_item.html';
		viewads($lid);
		break;
    default:
		$xoopsOption['template_main'] = 'adslight_index.html';
		index();
		break;
}
include XOOPS_ROOT_PATH.'/footer.php';