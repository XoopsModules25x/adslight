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

	$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
	$main_lang =  '_' . strtoupper( $mydirname ) ;
	require_once( XOOPS_ROOT_PATH."/modules/adslight/include/gtickets.php" ) ;
	include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
	$myts =& MyTextSanitizer::getInstance();


function ExpireAd()
{
	
	global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts, $meta, $mydirname, $main_lang;

	$datenow = time();

	$result5 = $xoopsDB->query("select lid, title, expire, type, desctext, date, email, submitter, photo, valid, hits, comments, remind FROM ".$xoopsDB->prefix("adslight_listing")." WHERE valid='Yes'");

	while(list($lids, $title, $expire, $type, $desctext, $dateann, $email, $submitter, $photo, $valid, $hits, $comments, $remind) = $xoopsDB->fetchRow($result5)) {

		$title = $myts->htmlSpecialChars($title);
		$expire = $myts->htmlSpecialChars($expire);
		$type = $myts->htmlSpecialChars($type);
		$desctext = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
		$submitter = $myts->htmlSpecialChars($submitter);
		$remind = $myts->htmlSpecialChars($remind);
		$supprdate = $dateann + ($expire*86400);
		$almost = $xoopsModuleConfig["adslight_almost"];

// give warning that add is about to expire

if( $almost > 0 && ($supprdate - $almost*86400) < $datenow && $valid == 'Yes' && $remind==0){

$xoopsDB->queryF("update ".$xoopsDB->prefix("adslight_listing")." set remind='1' where lid=$lids");

			if ($email) {
				$tags =array();
				$subject =  ""._ADSLIGHT_ALMOST."";
				$tags['TITLE'] = $title;
				$tags['HELLO'] =  ""._ADSLIGHT_HELLO."";
				$tags['YOUR_AD_ON'] =  ""._ADSLIGHT_YOUR_AD_ON."";
				$tags['VEDIT_AD'] =  ""._ADSLIGHT_VEDIT_AD."";
				$tags['YOUR_AD'] =  ""._ADSLIGHT_YOUR_AD."";
				$tags['SOON'] =  ""._ADSLIGHT_SOON."";
				$tags['VIEWED'] = ""._ADSLIGHT_VU."";
				$tags['TIMES'] = ""._ADSLIGHT_TIMES."";
				$tags['WEBMASTER'] = ""._ADSLIGHT_WEBMASTER."";
				$tags['THANKS'] = ""._ADSLIGHT_THANKS."";
				$tags['TYPE'] = adslight_NameType($type);
				$tags['DESCTEXT'] = $desctext;
				$tags['HITS'] = $hits;
				$tags['META_TITLE'] = $meta['title'];
				$tags['SUBMITTER'] = $submitter;
				$tags['DURATION'] = $expire;
				$tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/viewads.php?'. '&lid=' . $lids;
				$mail =& getMailer();
				$mail->setTemplateDir(XOOPS_ROOT_PATH."/modules/". $xoopsModule->getVar('dirname') ."/language/".$xoopsConfig['language']."/mail_template/");
				$mail->setTemplate("listing_expires.tpl");
				$mail->useMail();
				$mail->multimailer->isHTML(true);
				$mail->setFromName($meta['title']);
				$mail->setFromEmail($xoopsConfig['adminmail']);
				$mail->setToEmails($email);
				$mail->setSubject($subject);
				$mail->assign($tags);
				$mail->send();
				echo $mail->getErrors();
			}
		}

// expire ad

		if ($supprdate < $datenow) {



	if($photo != 0) {
	$result2 = $xoopsDB->query("select url from ".$xoopsDB->prefix("adslight_pictures")." where lid=".mysql_real_escape_string($lids)."");

	while(list($url) = $xoopsDB->fetchRow($result2)) {

	$destination = XOOPS_ROOT_PATH."/uploads/AdsLight";
	$destination2 = XOOPS_ROOT_PATH."/uploads/AdsLight/thumbs";
	$destination3 = XOOPS_ROOT_PATH."/uploads/AdsLight/midsize";
		if (file_exists("$destination/$url")) {
			unlink("$destination/$url");
			}
		if (file_exists("$destination2/thumb_$url")) {
			unlink("$destination2/thumb_$url");
			}
		if (file_exists("$destination3/resized_$url")) {
			unlink("$destination3/resized_$url");
			}
		}
	}


			$xoopsDB->queryF("delete from ".$xoopsDB->prefix("adslight_listing")." where lid=".mysql_real_escape_string($lids)."");



			//	Specification for Japan: 
			//	$message = ""._ADS_HELLO." $submitter,\n\n"._ADS_STOP2."\n $type : $title\n $desctext\n"._ADS_STOP3."\n\n"._ADS_VU." $lu "._ADS_VU2."\n\n"._ADS_OTHER." ".XOOPS_URL."/modules/myAds\n\n"._ADS_THANK."\n\n"._ADS_TEAM." ".$meta['title']."\n".XOOPS_URL."";
			if ($email) {
				$tags =array();
				$subject =  ""._ADSLIGHT_STOP."";
				$tags['TITLE'] = $title;
				$tags['HELLO'] =  ""._ADSLIGHT_HELLO."";
				$tags['TYPE'] = adslight_NameType($type);
				$tags['DESCTEXT'] = $desctext;
				$tags['HITS'] = $hits;
				$tags['META_TITLE'] = $meta['title'];
				$tags['SUBMITTER'] = $submitter;
				$tags['YOUR_AD_ON'] =  ""._ADSLIGHT_YOUR_AD_ON."";
				$tags['EXPIRED'] =  ""._ADSLIGHT_EXPIRED."";
				$tags['MESSTEXT'] = stripslashes($message);
				$tags['OTHER'] = ""._ADSLIGHT_OTHER."";
				$tags['WEBMASTER'] = ""._ADSLIGHT_WEBMASTER."";
				$tags['THANKS'] = ""._ADSLIGHT_THANKS."";
				$tags['VIEWED'] = ""._ADSLIGHT_VU."";
				$tags['TIMES'] = ""._ADSLIGHT_TIMES."";
				$tags['TEAM'] = ""._ADSLIGHT_TEAM."";
				$tags['DURATION'] = $expire;
				$tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/viewads.php?'. '&lid=' . $lids;
				$mail =& getMailer();
				$mail->setTemplateDir(XOOPS_ROOT_PATH."/modules/". $xoopsModule->getVar('dirname') ."/language/".$xoopsConfig['language']."/mail_template/");
				$mail->setTemplate("listing_expired.tpl");
				$mail->useMail();
				$mail->multimailer->isHTML(true);
				$mail->setFromName($meta['title']);
				$mail->setFromEmail($xoopsConfig['adminmail']);
				$mail->setToEmails($email);
				$mail->setSubject($subject);
				$mail->assign($tags);
				$mail->send();
				echo $mail->getErrors();
				}
			}
		}
	}


//updates rating data in itemtable for a given user
function updateUrating($sel_id){
        global $xoopsDB, $xoopsUser, $mydirname, $main_lang;

if (isset($_GET['usid']) ) {
	$usid = intval($_GET['usid']);
} else {
	$usid = 0;
}
        $query = "select rating FROM ".$xoopsDB->prefix("adslight_user_votedata")." WHERE usid=".mysql_real_escape_string($sel_id)."";
        //echo $query;
        $voteresult = $xoopsDB->query($query);
            $votesDB = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
            while(list($rating)=$xoopsDB->fetchRow($voteresult)){
                $totalrating += $rating;
        }
        $finalrating = $totalrating/$votesDB;
        $finalrating = number_format($finalrating, 4);
        $query =  "UPDATE ".$xoopsDB->prefix("adslight_listing")." SET user_rating=$finalrating, user_votes=$votesDB WHERE usid=".mysql_real_escape_string($sel_id)."";
        //echo $query;
            $xoopsDB->query($query) or exit();
}

//updates rating data in itemtable for a given user
function updateIrating($sel_id){
        global $xoopsDB, $xoopsUser, $mydirname, $main_lang;

if (isset($_GET['lid']) ) {
	$lid = intval($_GET['lid']);
} else {
	$lid = 0;
}
        $query = "select rating FROM ".$xoopsDB->prefix("adslight_item_votedata")." WHERE lid=".mysql_real_escape_string($sel_id)."";
        //echo $query;
        $voteresult = $xoopsDB->query($query);
            $votesDB = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
            while(list($rating)=$xoopsDB->fetchRow($voteresult)){
                $totalrating += $rating;
        }
        $finalrating = $totalrating/$votesDB;
        $finalrating = number_format($finalrating, 4);
        $query =  "UPDATE ".$xoopsDB->prefix("adslight_listing")." SET item_rating=$finalrating, item_votes=$votesDB WHERE lid=".mysql_real_escape_string($sel_id)."";
        //echo $query;
            $xoopsDB->query($query) or exit();
}


function adslight_getTotalItems($sel_id, $status=""){
	global $xoopsDB, $mytree, $mydirname;
	$categories = adslight_MygetItemIds("adslight_view");
	$count = 0;
	$arr = array();
	if(in_array($sel_id, $categories)) {
		$query = "select SQL_CACHE count(*) from ".$xoopsDB->prefix("adslight_listing")." where cid=".intval($sel_id)." and valid='Yes' and status!='1'";
		
		$result = $xoopsDB->query($query);
		list($thing) = $xoopsDB->fetchRow($result);
		$count = $thing;
		$arr = $mytree->getAllChildId($sel_id);
		$size = count($arr);
		for($i=0;$i<$size;$i++){
			if(in_array($arr[$i], $categories)) {
				$query2 = "select SQL_CACHE count(*) from ".$xoopsDB->prefix("adslight_listing")." where cid=".intval($arr[$i])." and valid='Yes' and status!='1'";
				
				$result2 = $xoopsDB->query($query2);
				list($thing) = $xoopsDB->fetchRow($result2);
				$count += $thing;
			}
		}
	}
	return $count;
}


function adslight_MygetItemIds($permtype)
{
	global $xoopsUser, $mydirname;
	static $permissions = array();
	if(is_array($permissions) && array_key_exists($permtype, $permissions)) {
		return $permissions[$permtype];
	}

   	$module_handler =& xoops_gethandler('module');
   	$myModule =& $module_handler->getByDirname('adslight');
   	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
   	$gperm_handler =& xoops_gethandler('groupperm');
   	$categories = $gperm_handler->getItemIds($permtype, $groups, $myModule->getVar('mid'));
   	$permissions[$permtype] = $categories;
    return $categories;
}

function adslight_getmoduleoption($option, $repmodule='adslight')
{
	global $xoopsModuleConfig, $xoopsModule;
	static $tbloptions= Array();
	if(is_array($tbloptions) && array_key_exists($option,$tbloptions)) {
		return $tbloptions[$option];
	}

	$retval = false;
	if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
		if(isset($xoopsModuleConfig[$option])) {
			$retval= $xoopsModuleConfig[$option];
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($repmodule);
		$config_handler =& xoops_gethandler('config');
		if ($module) {
		    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    	if(isset($moduleConfig[$option])) {
	    		$retval= $moduleConfig[$option];
	    	}
		}
	}
	$tbloptions[$option]=$retval;
	return $retval;
}

function ShowImg()
{
global $mydirname;
	echo "<script type=\"text/javascript\">\n";
	echo "<!--\n\n";
	echo "function showimage() {\n";
	echo "if (!document.images)\n";
	echo "return\n";
	echo "document.images.avatar.src=\n";
	echo "'".XOOPS_URL."/modules/adslight/images/img_cat/' + document.imcat.img.options[document.imcat.img.selectedIndex].value\n";
	echo "}\n\n";
	echo "//-->\n";
	echo "</script>\n";
}
//Reusable Link Sorting Functions
function adslight_convertorderbyin($orderby) {
	switch (trim($orderby)) {
	case "titleA":
		$orderby = "title ASC";
		break;
	case "dateA":
		$orderby = "date ASC";
		break;
	case "hitsA":
		$orderby = "hits ASC";
		break;
	case "priceA":
		$orderby = "price ASC";
		break;
	case "titleD":
		$orderby = "title DESC";
		break;
	case "hitsD":
		$orderby = "hits DESC";
		break;
	case "priceD":
		$orderby = "price DESC";
		break;
	case"dateD":
	default:
		$orderby = "date DESC";
		break;
	}
	return $orderby;
}

function adslight_convertorderbytrans($orderby) {

	global $main_lang;

            if ($orderby == "hits ASC")   $orderbyTrans = ""._ADSLIGHT_POPULARITYLTOM."";
            if ($orderby == "hits DESC")    $orderbyTrans = ""._ADSLIGHT_POPULARITYMTOL."";
            if ($orderby == "title ASC")    $orderbyTrans = ""._ADSLIGHT_TITLEATOZ."";
           if ($orderby == "title DESC")   $orderbyTrans = ""._ADSLIGHT_TITLEZTOA."";
            if ($orderby == "date ASC") $orderbyTrans = ""._ADSLIGHT_DATEOLD."";
            if ($orderby == "date DESC")   $orderbyTrans = ""._ADSLIGHT_DATENEW."";
            if ($orderby == "price ASC")  $orderbyTrans = _ADSLIGHT_PRICELTOH;
            if ($orderby == "price DESC") $orderbyTrans = ""._ADSLIGHT_PRICEHTOL."";
            return $orderbyTrans;
}
function adslight_convertorderbyout($orderby) {
            if ($orderby == "title ASC")            $orderby = "titleA";
            if ($orderby == "date ASC")            $orderby = "dateA";
            if ($orderby == "hits ASC")          $orderby = "hitsA";
            if ($orderby == "price ASC")        $orderby = "priceA";
            if ($orderby == "title DESC")              $orderby = "titleD";
            if ($orderby == "date DESC")            $orderby = "dateD";
            if ($orderby == "hits DESC")          $orderby = "hitsD";
            if ($orderby == "price DESC")        $orderby = "priceD";
            return $orderby;
}

function adslight_getEditor($caption, $name, $value = "", $width = '100%', $height ='300px', $supplemental='') {

    global $xoopsModuleConfig, $mydirname;
$editor = false;
$x22=false;
$xv=str_replace('XOOPS ','',XOOPS_VERSION);
if(substr($xv,2,1)=='2') {
$x22=true;
}
$editor_configs=array();
$editor_configs["name"] =$name;
$editor_configs["value"] = $value;
$editor_configs["rows"] = 25;
$editor_configs["cols"] = 80;
$editor_configs["width"] = "100%";
$editor_configs["height"] = "300px";

    switch(strtolower($xoopsModuleConfig["adslight_form_options"])){

       case 'tinymce' :
        if (!$x22) {

            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/tinymce/formtinymce.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinymce/formtinymce.php");
                $editor = new XoopsFormTinymce(array('caption'=>$caption, 'name'=>$name, 'value'=>$value, 'width'=>'100%', 'height'=>'300px'));
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
        } else {
            $editor = new XoopsFormEditor($caption, "tinyeditor", $editor_configs);
        }
        break;

        case 'fckeditor' :
        if (!$x22) {
            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/fckeditor/formfckeditor.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/fckeditor/formfckeditor.php");
                $editor = new XoopsFormFckeditor($editor_configs,true);
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
        } else {
            $editor = new XoopsFormEditor($caption, "fckeditor", $editor_configs);
        }
        break;

        case 'koivi' :
        if (!$x22) {
            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formkoivi.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formkoivi.php");
		        include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/language/english.php");
                $editor = new XoopsFormKoivi($editor_configs,true);
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
	} else {
            $editor = new XoopsFormEditor($caption, "koivi", $editor_configs);
        }        
        break;

        case "textarea":
        if(!$x22) {
            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/textarea/textarea.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/textarea/textarea.php");
                $editor = new FormTextArea($caption, $name, $value);
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
        } else {
            $editor = new XoopsFormEditor($caption, "htmlarea", $editor_configs);
        }
        break;

        default :
//        if ($dhtml) {
		include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/dhtmltextarea/dhtmltextarea.php");
            $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 40, $supplemental);
 //       } else {
 //           $editor = new XoopsFormEditor($caption, 'dhtmltextarea', $editor_configs);
 //       }

        break;
    }

    return $editor;
}

function adslight_adminEditor($caption, $name, $value = "", $width = '100%', $height ='300px', $supplemental='') {

    global $xoopsModuleConfig, $mydirname;
$editor = false;
$x22=false;
$xv=str_replace('XOOPS ','',XOOPS_VERSION);
if(substr($xv,2,1)=='2') {
$x22=true;
}
$editor_configs=array();
$editor_configs["name"] =$name;
$editor_configs["value"] = $value;
$editor_configs["rows"] = 35;
$editor_configs["cols"] = 60;
$editor_configs["width"] = "100%";
$editor_configs["height"] = "300px";

    switch(strtolower($xoopsModuleConfig["adslight_admin_editor"])){

       case 'tinymce' :
        if (!$x22) {

            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/tinymce/formtinymce.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinymce/formtinymce.php");
                $editor = new XoopsFormTinymce(array('caption'=>$caption, 'name'=>$name, 'value'=>$value, 'width'=>'100%', 'height'=>'300px'));
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
        } else {
            $editor = new XoopsFormEditor($caption, "tinyeditor", $editor_configs);
        }
        break;

        case 'fckeditor' :
        if (!$x22) {
            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/fckeditor/formfckeditor.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/fckeditor/formfckeditor.php");
                $editor = new XoopsFormFckeditor($editor_configs,true);
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
        } else {
            $editor = new XoopsFormEditor($caption, "fckeditor", $editor_configs);
        }
        break;

        case 'koivi' :
        if (!$x22) {
            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formkoivi.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formkoivi.php");
		        include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/language/english.php");
                $editor = new XoopsFormKoivi($editor_configs,true);
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
	} else {
            $editor = new XoopsFormEditor($caption, "koivi", $editor_configs);
        }
        break;

        case "textarea":
        if(!$x22) {
            if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/textarea/textarea.php"))    {
                include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/textarea/textarea.php");
                $editor = new FormTextArea($caption, $name, $value);
            } else {
                if ($dhtml) {
                    $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);
                } else {
                    $editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);
                }
            }
        } else {
            $editor = new XoopsFormEditor($caption, "textarea", $editor_configs);
        }
        break;

        default :

		include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/dhtmltextarea/dhtmltextarea.php");
            $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 40, $supplemental);

        break;
    }

    return $editor;
}

function jlm_ads_TableExists($tablename)
	{
		global $xoopsDB;
		$result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
		return($xoopsDB->getRowsNum($result) > 0);
	}

function jlm_ads_FieldExists($fieldname,$table)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");
	return($xoopsDB->getRowsNum($result) > 0);
}

function jlm_ads_AddField($field, $table)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");
	return $result;
}

function adslight_getCatNameFromId($cid) {
           global $xoopsDB, $xoopsConfig, $myts, $xoopsUser, $mydirname;

           $sql = "SELECT SQL_CACHE title FROM ".$xoopsDB->prefix("adslight_categories")." WHERE cid = '$cid'";

           if ( !$result = $xoopsDB->query($sql) ) {
               return false;
           }

           if ( !$arr = $xoopsDB->fetchArray($result) ) {
               return false;
           }

           $title = $arr['title'];

           return $title;
       }
function adslight_gocategory() {
global $xoopsDB;
	
	$xt = new XoopsTree($xoopsDB->prefix("adslight_categories"),'cid','pid');
	$jump = XOOPS_URL."/modules/adslight/viewcats.php?cid=";
	ob_start();
	$xt->makeMySelBox('title','title',0,1, 'pid', "location=\"".$jump."\"+this.options[this.selectedIndex].value");
	$block['selectbox'] = ob_get_contents();
	ob_end_clean();

    return $block;
}

// ADSLIGHT Version 2 //
// Fonction rss.php RSS par categories
function returnAllAdsRss(){
	global $xoopsDB, $xoopsModuleConfig, $xoopsUser;
	
	$cid = !isset($_GET['cid'])? NULL : $_GET['cid'];
	
	$result = array() ;
	
	$sql = "SELECT lid, title, price, date, town FROM ".$xoopsDB->prefix("adslight_listing")." WHERE valid='yes' AND cid=".mysql_real_escape_string($cid)." ORDER BY date DESC" ;	
	
	$resultValues = $xoopsDB->query($sql) ;
	while(($resultTemp = mysql_fetch_array($resultValues))!==FALSE){
		
		array_push($result, $resultTemp);
	}
	return($result);
}

// Fonction fluxrss.php RSS Global
function returnAllAdsFluxRss(){
	global $xoopsDB, $xoopsModuleConfig, $xoopsUser;
	
	$result = array() ;
	
	$sql = "SELECT lid, title, price, desctext, date, town FROM ".$xoopsDB->prefix("adslight_listing")." WHERE valid='yes' ORDER BY date DESC LIMIT 0,15" ;	
	
	$resultValues = $xoopsDB->query($sql) ;
	while(($resultTemp = mysql_fetch_array($resultValues))!==FALSE){
		
		array_push($result, $resultTemp);
	}
	return($result);
}

function adslight_NameType($type) {
global $xoopsDB;
			$sql=$xoopsDB->query("select nom_type from ".$xoopsDB->prefix("adslight_type")." WHERE id_type=".mysql_real_escape_string($type)."");
			list($nom_type) = $xoopsDB->fetchRow($sql);
return $nom_type;
}