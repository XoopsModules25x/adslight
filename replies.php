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

$myts      = \MyTextSanitizer::getInstance(); // MyTextSanitizer object
$module_id = $xoopsModule->getVar('mid');
$groups    = ($GLOBALS['xoopsUser'] instanceof \XoopsUser) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid      = Request::getInt('item_id', 0, 'POST');

//If no access
if (!$grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
    $helper->redirect('index.php', 3, _NOPERM);
}
//require_once XOOPS_ROOT_PATH . '/modules/adslight/class/Tree.php';
$mytree = new Adslight\Tree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');

$lid                                     = Request::getInt('lid', 0, 'GET');
$GLOBALS['xoopsOption']['template_main'] = 'adslight_replies.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$GLOBALS['xoopsTpl']->assign('nav_main', '<a href="index.php">' . _ADSLIGHT_MAIN . '</a>');
$show = 1;
$min  = Request::getInt('min', 0, 'GET');
if (!isset($max)) {
    $max = $min + $show;
}
$orderby = 'date_created Desc';

$GLOBALS['xoopsTpl']->assign('lid', $lid);
$countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_replies') . ' WHERE lid=' . $xoopsDB->escape($lid));
[$trow] = $xoopsDB->fetchRow($countresult);
$trows   = $trow;
$pagenav = '';

if ($trows < '1') {
    $GLOBALS['xoopsTpl']->assign('has_replies', false);
    $GLOBALS['xoopsTpl']->assign('no_more_replies', _ADSLIGHT_NO_REPLIES);
}

if ($trows > '0') {
    $GLOBALS['xoopsTpl']->assign('has_replies', true);
    $GLOBALS['xoopsTpl']->assign('last_head', _ADSLIGHT_THE . ' ' . $GLOBALS['xoopsModuleConfig']['adslight_newcount'] . ' ' . _ADSLIGHT_LASTADD);
    $GLOBALS['xoopsTpl']->assign('last_head_title', _ADSLIGHT_TITLE);
    $GLOBALS['xoopsTpl']->assign('last_head_price', _ADSLIGHT_PRICE);
    $GLOBALS['xoopsTpl']->assign('last_head_date', _ADSLIGHT_DATE);
    $GLOBALS['xoopsTpl']->assign('last_head_local', _ADSLIGHT_LOCAL2);
    $GLOBALS['xoopsTpl']->assign('last_head_views', _ADSLIGHT_VIEW);
    $GLOBALS['xoopsTpl']->assign('last_head_photo', _ADSLIGHT_PHOTO);
    $GLOBALS['xoopsTpl']->assign('min', $min);

    $sql    = 'SELECT r_lid, lid, title, date_created, submitter, message, tele, email, r_usid FROM ' . $xoopsDB->prefix('adslight_replies') . ' WHERE lid=' . $xoopsDB->escape($lid) . " ORDER BY $orderby";
    $result = $xoopsDB->query($sql, $show, $min);

    if ($trows > '1') {
        $GLOBALS['xoopsTpl']->assign('has_replies', true);
        $GLOBALS['xoopsTpl']->assign('show_nav', true);
        $GLOBALS['xoopsTpl']->assign('lang_sortby', _ADSLIGHT_SORTBY);
        $GLOBALS['xoopsTpl']->assign('lang_title', _ADSLIGHT_TITLE);
        $GLOBALS['xoopsTpl']->assign('lang_titleatoz', _ADSLIGHT_TITLEATOZ);
        $GLOBALS['xoopsTpl']->assign('lang_titleztoa', _ADSLIGHT_TITLEZTOA);
        $GLOBALS['xoopsTpl']->assign('lang_date', _ADSLIGHT_DATE);
        $GLOBALS['xoopsTpl']->assign('lang_dateold', _ADSLIGHT_DATEOLD);
        $GLOBALS['xoopsTpl']->assign('lang_datenew', _ADSLIGHT_DATENEW);
        $GLOBALS['xoopsTpl']->assign('lang_popularity', _ADSLIGHT_POPULARITY);
        $GLOBALS['xoopsTpl']->assign('lang_popularityleast', _ADSLIGHT_POPULARITYLTOM);
        $GLOBALS['xoopsTpl']->assign('lang_popularitymost', _ADSLIGHT_POPULARITYMTOL);
        $GLOBALS['xoopsTpl']->assign('lang_cursortedby', _ADSLIGHT_CURSORTEDBY . '' . $orderby);
    }

    while (false !== (list($r_lid, $lid, $title, $date_created, $submitter, $message, $tele, $email, $r_usid) = $xoopsDB->fetchRow($result))) {
        $useroffset = '';
        if ($GLOBALS['xoopsUser']) {
            $timezone   = $GLOBALS['xoopsUser']->timezone();
            $useroffset = isset($timezone) ? $GLOBALS['xoopsUser']->timezone() : $xoopsConfig['default_TZ'];
        }
        $GLOBALS['xoopsTpl']->assign('submitter', " <a href='" . XOOPS_URL . "/userinfo.php?uid=$r_usid'>$submitter</a>");
        $date_created = ($useroffset * 3600) + $date_created;
        $date_created = formatTimestamp($date_created, 's');
        $GLOBALS['xoopsTpl']->assign('title', "<a href='viewads.php?lid=$lid'>$title</a>");
        $GLOBALS['xoopsTpl']->assign('title_head', _ADSLIGHT_REPLY_TITLE);
        $GLOBALS['xoopsTpl']->assign('date_head', _ADSLIGHT_REPLIED_ON);
        $GLOBALS['xoopsTpl']->assign('submitter_head', _ADSLIGHT_REPLIED_BY);
        $GLOBALS['xoopsTpl']->assign('message_head', _ADSLIGHT_REPLY_MESSAGE);
        $GLOBALS['xoopsTpl']->assign('email_head', _ADSLIGHT_EMAIL);
        $GLOBALS['xoopsTpl']->assign('tele_head', _ADSLIGHT_TEL);
        $GLOBALS['xoopsTpl']->assign('email', "<a href ='mailto:$email'>$email</a>");
        $GLOBALS['xoopsTpl']->assign('delete_reply', "<a href='modify.php?op=DelReply&amp;r_lid=$r_lid'>" . _ADSLIGHT_DELETE_REPLY . '</a>');
        $GLOBALS['xoopsTpl']->append('items', [
            'id'      => $lid,
            'title'   => htmlspecialchars($title, ENT_QUOTES | ENT_HTML5),
            'date_created'    => $date_created,
            'message' => $myts->displayTarea($message),
            'tele'    => htmlspecialchars($tele, ENT_QUOTES | ENT_HTML5),
        ]);
    }
    $lid = Request::getInt('lid', 0, 'GET');
    //Calculates how many pages exist.  Which page one should be on, etc...
    $linkpages = ceil($trows / $show);
    //Page Numbering
    if (1 != $linkpages && 0 != $linkpages) {
        $prev = $min - $show;
        if ($prev >= 0) {
            $pagenav .= "<a href='replies.php?lid=$lid&min=$prev&show=$show'><strong><u>&laquo;</u></strong></a> ";
        }
        $counter     = 1;
        $currentpage = ($max / $show);
        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage) {
                $pagenav .= "<strong>($counter)</strong> ";
            } else {
                $pagenav .= "<a href='replies.php?lid=$lid&min=$mintemp&show=$show'>$counter</a> ";
            }
            ++$counter;
        }
        if ($trows > $max) {
            $pagenav .= "<a href='replies.php?lid=$lid&min=$max&show=$show'>";
            $pagenav .= '<strong><u>&raquo;</u></strong></a>';
        }
        $GLOBALS['xoopsTpl']->assign('nav_page', '<strong>' . _ADSLIGHT_REPLY . "</strong>&nbsp;&nbsp; $pagenav");
    }
}

require_once XOOPS_ROOT_PATH . '/footer.php';
