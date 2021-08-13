<?php

declare(strict_types=1);

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

use Xmf\Request;
use XoopsModules\Adslight;

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$myts          = \MyTextSanitizer::getInstance(); // MyTextSanitizer object
$moduleDirName = \basename(__DIR__);

if (!empty($_POST['submit'])) {
    //    $erh         = new ErrorHandler; //ErrorHandler object
    $ratinguser = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;

    $anonwaitdays = 1; // Make sure only 1 anonymous rating from an IP in a single day.
    $ip           = getenv('REMOTE_ADDR');
    $lid          = Request::getInt('lid', 0, 'POST');
    $rating       = Request::getInt('rating', 0, 'POST');

    // Check if Rating is Null
    if ('--' == $rating) {
        redirect_header('rate-item.php?lid=' . $lid . '', 4, constant('_ADSLIGHT_NORATING'));
    }

    // Check if Link POSTER is voting (UNLESS Anonymous users allowed to post)
    if (0 != $ratinguser) {
        $result = $xoopsDB->query('SELECT submitter FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $xoopsDB->escape($lid));
        while (false !== (list($ratinguserDB) = $xoopsDB->fetchRow($result))) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header('viewads.php?lid=' . $lid . '', 4, constant('_ADSLIGHT_CANTVOTEOWN'));
            }
        }

        // Check if REG user is trying to vote twice.
        $result = $xoopsDB->query('SELECT ratinguser FROM ' . $xoopsDB->prefix('adslight_item_votedata') . ' WHERE lid=' . $xoopsDB->escape($lid));
        while (false !== (list($ratinguserDB) = $xoopsDB->fetchRow($result))) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header('viewads.php?lid=' . $lid . '', 4, constant('_ADSLIGHT_VOTEONCE2'));
            }
        }
    } else {
        // Check if ANONYMOUS user is trying to vote more than once per day.
        $yesterday = (time() - (86400 * $anonwaitdays));
        $result    = $xoopsDB->query('SELECT count(*) FROM ' . $xoopsDB->prefix('adslight_item_votedata') . ' WHERE lid=' . $xoopsDB->escape($lid) . " AND ratinguser=0 AND ratinghostname = '$ip' AND ratingtimestamp > $yesterday");
        [$anonvotecount] = $xoopsDB->fetchRow($result);
        if ($anonvotecount > 0) {
            redirect_header('viewads.php?lid=' . $lid . '', 4, constant('_ADSLIGHT_VOTEONCE2'));
        }
    }
    $rating = ($rating > 10) ? 10 : $rating;

    //All is well.  Add to Line Item Rate to DB.
    $newid    = $xoopsDB->genId($xoopsDB->prefix('adslight_item_votedata') . '_ratingid_seq');
    $datetime = time();
    $sql      = sprintf("INSERT INTO `%s` (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix('adslight_item_votedata'), $newid, $lid, $ratinguser, $rating, $ip, $datetime);
    // $xoopsDB->query($sql) || $eh->show('0013'); //            '0013' => 'Could not query the database.', // <br>Error: ' . $GLOBALS['xoopsDB']->error() . '',
    $success = $xoopsDB->query($sql);
    if (!$success) {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        /** @var \XoopsModule $myModule */
        $myModule = $moduleHandler->getByDirname('adslight');
        $myModule->setErrors('Could not query the database.');
    }

    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
    //    updateIrating($lid);
    Adslight\Utility::updateItemRating($lid);
    $ratemessage = constant('_ADSLIGHT_VOTEAPPRE') . '<br>' . sprintf(constant('_ADSLIGHT_THANKURATEITEM'), $xoopsConfig['sitename']);
    redirect_header('viewads.php?lid=' . $lid . '', 3, $ratemessage);
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'adslight_rate_item.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';
    $lid    = Request::getInt('lid', 0, 'GET');
    $result = $xoopsDB->query('SELECT lid, title FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $xoopsDB->escape($lid));
    [$lid, $title] = $xoopsDB->fetchRow($result);
    $GLOBALS['xoopsTpl']->assign('link', [
        'lid'   => $lid,
        'title' => htmlspecialchars($title, ENT_QUOTES | ENT_HTML5),
    ]);
    $GLOBALS['xoopsTpl']->assign('lang_voteonce', constant('_ADSLIGHT_VOTEONCE'));
    $GLOBALS['xoopsTpl']->assign('lang_ratingscale', constant('_ADSLIGHT_RATINGSCALE'));
    $GLOBALS['xoopsTpl']->assign('lang_beobjective', constant('_ADSLIGHT_BEOBJECTIVE'));
    $GLOBALS['xoopsTpl']->assign('lang_donotvote', constant('_ADSLIGHT_DONOTVOTE'));
    $GLOBALS['xoopsTpl']->assign('lang_rateit', constant('_ADSLIGHT_RATEIT'));
    $GLOBALS['xoopsTpl']->assign('lang_cancel', _CANCEL);
    $GLOBALS['xoopsTpl']->assign('mydirname', $moduleDirName);
    require_once XOOPS_ROOT_PATH . '/footer.php';
}
