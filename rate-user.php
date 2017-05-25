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

use Xmf\Request;

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
//require_once XOOPS_ROOT_PATH . '/modules/adslight/class/utility.php';
if (!empty($HTTP_POST_VARS['submit'])) {
    //    $erh         = new ErrorHandler; //ErrorHandler object
    $ratinguser = ($GLOBALS['xoopsUser'] instanceof XoopsUser) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;

    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip           = getenv('REMOTE_ADDR');
    $usid         = Request::getInt('usid', 0, 'POST');
    $rating       = Request::getInt('rating', 0, 'POST');

    // Check if Rating is Null
    if ($rating == '--') {
        redirect_header('rate-user.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_NORATING'));
    }

    // Check if Link POSTER is voting (UNLESS Anonymous users allowed to post)
    if ($ratinguser != 0) {
        $result = $xoopsDB->query('SELECT submitter FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $xoopsDB->escape($usid));
        while (list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header('members.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_CANTVOTEOWN'));
            }
        }

        // Check if REG user is trying to vote twice.
        $result = $xoopsDB->query('SELECT ratinguser FROM ' . $xoopsDB->prefix('adslight_user_votedata') . ' WHERE usid=' . $xoopsDB->escape($usid));
        while (list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header('members.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_VOTEONCE2'));
            }
        }
    } else {

        // Check if ANONYMOUS user is trying to vote more than once per day.
        $yesterday = (time() - (86400 * $anonwaitdays));
        $result    = $xoopsDB->query('SELECT count(*) FROM '
                                     . $xoopsDB->prefix('adslight_user_votedata')
                                     . ' WHERE usid='
                                     . $xoopsDB->escape($usid)
                                     . " AND ratinguser=0 AND ratinghostname = '$ip' AND ratingtimestamp > $yesterday");
        list($anonvotecount) = $xoopsDB->fetchRow($result);
        if ($anonvotecount > 0) {
            redirect_header('members.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_VOTEONCE2'));
        }
    }
    $rating = ($rating > 10) ? 10 : $rating;

    //All is well.  Add to Line Item Rate to DB.
    $newid    = $xoopsDB->genId($xoopsDB->prefix('adslight_user_votedata') . '_ratingid_seq');
    $datetime = time();
    $sql      = sprintf("INSERT INTO %s (ratingid, usid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix('adslight_user_votedata'), $newid,
                        $usid, $ratinguser, $rating, $ip, $datetime);
    // $xoopsDB->query($sql) || $erh->show('0013'); //            '0013' => 'Could not query the database.', // <br>Error: ' . $GLOBALS['xoopsDB']->error() . '',
    $success = $xoopsDB->query($sql);
    if (!$success) {
        $moduleHandler = xoops_getModuleHandler('module');
        $myModule   = $moduleHandler->getByDirname('adslight');
        $myModule->setErrors('Could not query the database.');
    }

    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
    //    updateUrating($usid);
    AdslightUtility::updateUserRating($usid);
    $ratemessage = constant('_ADSLIGHT_VOTEAPPRE') . '<br>' . sprintf(constant('_ADSLIGHT_THANKURATEUSER'), $xoopsConfig['sitename']);
    redirect_header('members.php?usid=' . addslashes($usid) . '', 3, $ratemessage);
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'adslight_rate_user.tpl';
    include XOOPS_ROOT_PATH . '/header.php';
    $usid   = Request::getInt('usid', 0, 'GET');
    $result = $xoopsDB->query('SELECT title, usid, submitter FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $xoopsDB->escape($usid));
    list($title, $usid, $submitter) = $xoopsDB->fetchRow($result);
    $xoopsTpl->assign('link', array(
        'usid'      => $usid,
        'title'     => $myts->htmlSpecialChars($title),
        'submitter' => $submitter
    ));
    $xoopsTpl->assign('lang_voteonce', constant('_ADSLIGHT_VOTEONCE'));
    $xoopsTpl->assign('lang_ratingscale', constant('_ADSLIGHT_RATINGSCALE'));
    $xoopsTpl->assign('lang_beobjective', constant('_ADSLIGHT_BEOBJECTIVE'));
    $xoopsTpl->assign('lang_donotvote', constant('_ADSLIGHT_DONOTVOTE'));
    $xoopsTpl->assign('lang_rateit', constant('_ADSLIGHT_RATEIT'));
    $xoopsTpl->assign('lang_cancel', _CANCEL);
    $xoopsTpl->assign('mydirname', $moduleDirName);
    include XOOPS_ROOT_PATH . '/footer.php';
}
