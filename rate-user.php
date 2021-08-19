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
use XoopsModules\Adslight\{
    Utility
};

/** @var Helper $helper */

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$moduleDirName = \basename(__DIR__);
$myts          = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

if (!empty($_POST['submit'])) {
    //    $erh         = new ErrorHandler; //ErrorHandler object
    $ratinguser = $GLOBALS['xoopsUser'] instanceof \XoopsUser ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip           = getenv('REMOTE_ADDR');
    $usid         = Request::getInt('usid', 0, 'POST');
    $rating       = Request::getInt('rating', 0, 'POST');

    // Check if Rating is Null
    if ('--' === $rating) {
        $helper->redirect('rate-user.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_NORATING'));
    }

    // Check if Link POSTER is voting (UNLESS Anonymous users allowed to post)
    if (0 !== (int)$ratinguser) {
        $result = $xoopsDB->query('SELECT submitter FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $xoopsDB->escape($usid));
        while ([$ratinguserDB] = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB === $ratinguser) {
                $helper->redirect('members.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_CANTVOTEOWN'));
            }
        }

        // Check if REG user is trying to vote twice.
        $result = $xoopsDB->query('SELECT ratinguser FROM ' . $xoopsDB->prefix('adslight_user_votedata') . ' WHERE usid=' . $xoopsDB->escape($usid));
        while ([$ratinguserDB] = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB === $ratinguser) {
                $helper->redirect('members.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_VOTEONCE2'));
            }
        }
    } else {
        // Check if ANONYMOUS user is trying to vote more than once per day.
        $yesterday = time() - (86400 * $anonwaitdays);
        $result    = $xoopsDB->query('SELECT count(*) FROM ' . $xoopsDB->prefix('adslight_user_votedata') . ' WHERE usid=' . $xoopsDB->escape($usid) . " AND ratinguser=0 AND ratinghostname = '${ip}' AND date_created > ${yesterday}");
        [$anonvotecount] = $xoopsDB->fetchRow($result);
        if ($anonvotecount > 0) {
            $helper->redirect('members.php?usid=' . addslashes($usid) . '', 4, constant('_ADSLIGHT_VOTEONCE2'));
        }
    }
    $rating = $rating > 10 ? 10 : $rating;
    //All is well.  Add to Line Item Rate to DB.
    $newid    = $xoopsDB->genId($xoopsDB->prefix('adslight_user_votedata') . '_ratingid_seq');
    $datetime = time();
    $sql      = sprintf("INSERT INTO `%s` (ratingid, usid, ratinguser, rating, ratinghostname, date_created) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix('adslight_user_votedata'), $newid, $usid, $ratinguser, $rating, $ip, $datetime);
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
    //    updateUrating($usid);
    Utility::updateUserRating($usid);
    $ratemessage = constant('_ADSLIGHT_VOTEAPPRE') . '<br>' . sprintf(constant('_ADSLIGHT_THANKURATEUSER'), $xoopsConfig['sitename']);
    $helper->redirect('members.php?usid=' . addslashes($usid) . '', 3, $ratemessage);
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'adslight_rate_user.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';
    $usid   = Request::getInt('usid', 0, 'GET');
    $result = $xoopsDB->query('SELECT title, usid, submitter FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE usid=' . $xoopsDB->escape($usid));
    [$title, $usid, $submitter] = $xoopsDB->fetchRow($result);
    $GLOBALS['xoopsTpl']->assign('link', [
        'usid'      => $usid,
        'title'     => \htmlspecialchars($title, ENT_QUOTES | ENT_HTML5),
        'submitter' => $submitter,
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
