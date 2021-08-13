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

use XoopsModules\Adslight;

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 *
 * @return array
 */
function adslight_search($queryarray, $andor, $limit, $offset, $userid): array
{
    $visible = (bool)mb_strpos($_SERVER['REQUEST_URI'], '/modules/adslight/search.php');

    global $xoopsDB;

    $sql = 'SELECT lid,title,type,desctext,tel,price,typeprice,date_created,submitter,usid,town,country FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='Yes' AND status!='1' AND date_created<=" . time() . ' ';

    if (0 != $userid) {
        $sql .= " AND usid={$userid} ";
    }

    //if (lid=lid)
    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((title LIKE '%{$queryarray[0]}%' OR type LIKE '%{$queryarray[0]}%' OR desctext LIKE '%{$queryarray[0]}%' OR tel LIKE '%{$queryarray[0]}%' OR price LIKE '%{$queryarray[0]}%' OR typeprice LIKE '%{$queryarray[0]}%' OR submitter LIKE '%{$queryarray[0]}%' OR town LIKE '%{$queryarray[0]}%' OR country LIKE '%{$queryarray[0]}%')";
        for ($i = 1; $i < $count; ++$i) {
            $sql .= " $andor ";
            $sql .= "(title LIKE '%{$queryarray[$i]}%' OR type LIKE '%{$queryarray[$i]}%' OR desctext LIKE '%{$queryarray[$i]}%' OR tel LIKE '%{$queryarray[$i]}%' OR price LIKE '%{$queryarray[$i]}%' OR typeprice LIKE '%{$queryarray[$i]}%' OR submitter LIKE '%{$queryarray[$i]}%' OR town LIKE '%{$queryarray[$i]}%' OR country LIKE '%{$queryarray[$i]}%')";
        }
        $sql .= ') ';
    }
    $sql    .= ' ORDER BY premium DESC, date_created DESC';
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret    = [];
    $i      = 0;
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $myts    = \MyTextSanitizer::getInstance();
        $result2 = $xoopsDB->query('SELECT url FROM ' . $xoopsDB->prefix('adslight_pictures') . " WHERE lid={$myrow['lid']} ORDER BY date_created LIMIT 1 ");
        [$url] = $xoopsDB->fetchRow($result2);
        $url = \htmlspecialchars($url??'', ENT_QUOTES | ENT_HTML5);

        $ret[$i]['image']     = 'assets/images/deco/icon.png';
        $ret[$i]['link']      = 'viewads.php?lid=' . $myrow['lid'] . '';
        $ret[$i]['title']     = $myrow['title'];
        $ret[$i]['type']      = Adslight\Utility::getNameType($myrow['type']);
        $ret[$i]['price']     = number_format((float)$myrow['price'], 2, '.', ',');
        $ret[$i]['typeprice'] = $myrow['typeprice'];
        $ret[$i]['town']      = $myrow['town'];
        $ret[$i]['desctext']  = $myts->displayTarea($myrow['desctext'], 1, 1, 1, 1, 1);
        $ret[$i]['nophoto']   = 'assets/images/nophoto.jpg';
        $ret[$i]['photo']     = $url;
        $ret[$i]['time']      = $myrow['date_created'];
        $ret[$i]['uid']       = $myrow['usid'];
        if ($visible) {
            $ret[$i]['sphoto'] = $GLOBALS['xoopsModuleConfig']['adslight_link_upload'] . "thumbs/thumb_{$url}";
        }
        ++$i;
    }

    return $ret;
}
