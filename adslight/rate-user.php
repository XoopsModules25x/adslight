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

include "header.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once XOOPS_ROOT_PATH."/modules/adslight/include/functions.php";
if (!empty($HTTP_POST_VARS['submit'])) {
	$eh = new ErrorHandler; //ErrorHandler object
	if(empty($xoopsUser)){
		$ratinguser = 0;
	}else{
		$ratinguser = $xoopsUser->getVar('uid');
	}

   	//Make sure only 1 anonymous from an IP in a single day.
   	$anonwaitdays = 1;
   	$ip = getenv("REMOTE_ADDR");
	//$lid = intval($_POST['lid']);
	if (isset($_POST['usid']) ) {
	$usid = intval($_POST['usid']);
} else {
	$usid = 0;
}
	$rating = intval($_POST['rating']);

   	// Check if Rating is Null
   	if ($rating=="--") {
		redirect_header("rate-user.php?usid=".addslashes($usid)."",4,constant("_ADSLIGHT_NORATING"));
		exit();
   	}

   	// Check if Link POSTER is voting (UNLESS Anonymous users allowed to post)
   	if ($ratinguser != 0) {
       	$result=$xoopsDB->query("select submitter from ".$xoopsDB->prefix("adslight_listing")." where usid=".mysql_real_escape_string($usid)."");
       	while(list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
       		if ($ratinguserDB == $ratinguser) {
				redirect_header("members.php?usid=".addslashes($usid)."",4,constant("_ADSLIGHT_CANTVOTEOWN"));
				exit();
          	}
       	}

    	// Check if REG user is trying to vote twice.
   		$result=$xoopsDB->query("select ratinguser from ".$xoopsDB->prefix("adslight_user_votedata")." where usid=".mysql_real_escape_string($usid)."");
       	while(list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
       		if ($ratinguserDB == $ratinguser) {
				redirect_header("members.php?usid=".addslashes($usid)."",4,constant("_ADSLIGHT_VOTEONCE2"));
				exit();
           	}
      	}

   	} else {

   		// Check if ANONYMOUS user is trying to vote more than once per day.
		$yesterday = (time()-(86400 * $anonwaitdays));
       	$result=$xoopsDB->query("select count(*) FROM ".$xoopsDB->prefix("adslight_user_votedata")." WHERE usid=".mysql_real_escape_string($usid)." AND ratinguser=0 AND ratinghostname = '$ip' AND ratingtimestamp > $yesterday");
   		list($anonvotecount) = $xoopsDB->fetchRow($result);
   		if ($anonvotecount > 0) {
			redirect_header("members.php?usid=".addslashes($usid)."",4,constant("_ADSLIGHT_VOTEONCE2"));
			exit();
       	}
   	}
	if($rating > 10){
		$rating = 10;
	}

    //All is well.  Add to Line Item Rate to DB.
	$newid = $xoopsDB->genId($xoopsDB->prefix("adslight_user_votedata")."_ratingid_seq");
	$datetime = time();
	$sql = sprintf("INSERT INTO %s (ratingid, usid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix("adslight_user_votedata"), $newid, $usid, $ratinguser, $rating, $ip, $datetime);
	$xoopsDB->query($sql) or $eh->show("0013");

    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
    updateUrating($usid);
	$ratemessage = constant("_ADSLIGHT_VOTEAPPRE")."<br />".sprintf(constant("_ADSLIGHT_THANKURATEUSER"),$xoopsConfig['sitename']);
	redirect_header("members.php?usid=".addslashes($usid)."",3,$ratemessage);
	exit();

} else {

	$xoopsOption['template_main'] = "adslight_rate_user.html";
	include XOOPS_ROOT_PATH."/header.php";
	//$lid = intval($_GET['lid']);
	if (isset($_GET['usid']) ) {
	$usid = intval($_GET['usid']);
		} else {
	$usid = 0;
	}
	$result=$xoopsDB->query("select title, usid, submitter from ".$xoopsDB->prefix("adslight_listing")." where usid=".mysql_real_escape_string($usid)."");
	list($title, $usid, $submitter) = $xoopsDB->fetchRow($result);
	$xoopsTpl->assign('link', array('usid' => $usid, 'title' => $myts->htmlSpecialChars($title), 'submitter' => $submitter));
	$xoopsTpl->assign('lang_voteonce', constant("_ADSLIGHT_VOTEONCE"));
	$xoopsTpl->assign('lang_ratingscale', constant("_ADSLIGHT_RATINGSCALE"));
	$xoopsTpl->assign('lang_beobjective', constant("_ADSLIGHT_BEOBJECTIVE"));
	$xoopsTpl->assign('lang_donotvote', constant("_ADSLIGHT_DONOTVOTE"));
	$xoopsTpl->assign('lang_rateit', constant("_ADSLIGHT_RATEIT"));
	$xoopsTpl->assign('lang_cancel', _CANCEL);
	$xoopsTpl->assign('mydirname', $mydirname);
	include XOOPS_ROOT_PATH.'/footer.php';
}