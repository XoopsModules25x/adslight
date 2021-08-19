<?php

declare(strict_types=1);

namespace XoopsModules\Adslight;

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
    Common,
    Categories,
    CategoriesHandler
};


/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------

    public static function expireAd(): void
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $meta;

        $datenow = \time();
        $message = '';

        $result5 = $xoopsDB->query('SELECT lid, title, expire, type, desctext, date_created, email, submitter, photo, valid, hits, comments, remind FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='Yes'");

        while (false !== [$lids, $title, $expire, $type, $desctext, $dateann, $email, $submitter, $photo, $valid, $hits, $comments, $remind] = $xoopsDB->fetchRow($result5)) {
            $title     = \htmlspecialchars($title, \ENT_QUOTES | \ENT_HTML5);
            $expire    = \htmlspecialchars($expire, \ENT_QUOTES | \ENT_HTML5);
            $type      = \htmlspecialchars($type, \ENT_QUOTES | \ENT_HTML5);
            $desctext  = &$myts->displayTarea($desctext, 1, 1, 1, 1, 1);
            $submitter = \htmlspecialchars($submitter, \ENT_QUOTES | \ENT_HTML5);
            $remind    = \htmlspecialchars($remind, \ENT_QUOTES | \ENT_HTML5);
            $supprdate = $dateann + ($expire * 86400);
            $almost    = $GLOBALS['xoopsModuleConfig']['adslight_almost'];

            // give warning that add is about to expire

            if ($almost > 0 && ($supprdate - $almost * 86400) < $datenow
                && 'Yes' === $valid
                && 0 === $remind) {
                $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET remind='1' WHERE lid=${lids}");

                if ($email) {
                    $tags               = [];
                    $subject            = '' . \_ADSLIGHT_ALMOST . '';
                    $tags['TITLE']      = $title;
                    $tags['HELLO']      = '' . \_ADSLIGHT_HELLO . '';
                    $tags['YOUR_AD_ON'] = '' . \_ADSLIGHT_YOUR_AD_ON . '';
                    $tags['VEDIT_AD']   = '' . \_ADSLIGHT_VEDIT_AD . '';
                    $tags['YOUR_AD']    = '' . \_ADSLIGHT_YOUR_AD . '';
                    $tags['SOON']       = '' . \_ADSLIGHT_SOON . '';
                    $tags['VIEWED']     = '' . \_ADSLIGHT_VU . '';
                    $tags['TIMES']      = '' . \_ADSLIGHT_TIMES . '';
                    $tags['WEBMASTER']  = '' . \_ADSLIGHT_WEBMASTER . '';
                    $tags['THANKS']     = '' . \_ADSLIGHT_THANKS . '';
                    $tags['TYPE']       = static::getNameType($type);
                    $tags['DESCTEXT']   = $desctext;
                    $tags['HITS']       = $hits;
                    $tags['META_TITLE'] = $meta['title'];
                    $tags['SUBMITTER']  = $submitter;
                    $tags['DURATION']   = $expire;
                    $tags['LINK_URL']   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewads.php?' . '&lid=' . $lids;
                    $mail               = \getMailer();
                    $mail->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
                    $mail->setTemplate('listing_expires.tpl');
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
                if (0 !== $photo) {
                    $result2 = $xoopsDB->query('SELECT url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE lid=' . $xoopsDB->escape($lids));

                    while (false !== [$url] = $xoopsDB->fetchRow($result2)) {
                        $destination  = XOOPS_ROOT_PATH . '/uploads/adslight';
                        $destination2 = XOOPS_ROOT_PATH . '/uploads/adslight/thumbs';
                        $destination3 = XOOPS_ROOT_PATH . '/uploads/adslight/midsize';
                        if (\is_file("${destination}/${url}")) {
                            \unlink("${destination}/${url}");
                        }
                        if (\is_file("${destination2}/thumb_${url}")) {
                            \unlink("${destination2}/thumb_${url}");
                        }
                        if (\is_file("${destination3}/resized_${url}")) {
                            \unlink("${destination3}/resized_${url}");
                        }
                    }
                }

                $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $xoopsDB->escape($lids));

                //  Specification for Japan:
                //  $message = ""._ADS_HELLO." $submitter,\n\n"._ADS_STOP2."\n $type : $title\n $desctext\n"._ADS_STOP3."\n\n"._ADS_VU." $lu "._ADS_VU2."\n\n"._ADS_OTHER." ".XOOPS_URL."/modules/myAds\n\n"._ADS_THANK."\n\n"._ADS_TEAM." ".$meta['title']."\n".XOOPS_URL."";
                if ($email) {
                    $tags               = [];
                    $subject            = '' . \_ADSLIGHT_STOP . '';
                    $tags['TITLE']      = $title;
                    $tags['HELLO']      = '' . \_ADSLIGHT_HELLO . '';
                    $tags['TYPE']       = static::getNameType($type);
                    $tags['DESCTEXT']   = $desctext;
                    $tags['HITS']       = $hits;
                    $tags['META_TITLE'] = $meta['title'] ?? '';
                    $tags['SUBMITTER']  = $submitter;
                    $tags['YOUR_AD_ON'] = '' . \_ADSLIGHT_YOUR_AD_ON . '';
                    $tags['EXPIRED']    = '' . \_ADSLIGHT_EXPIRED . '';
                    $tags['MESSTEXT']   = \stripslashes($message);
                    $tags['OTHER']      = '' . \_ADSLIGHT_OTHER . '';
                    $tags['WEBMASTER']  = '' . \_ADSLIGHT_WEBMASTER . '';
                    $tags['THANKS']     = '' . \_ADSLIGHT_THANKS . '';
                    $tags['VIEWED']     = '' . \_ADSLIGHT_VU . '';
                    $tags['TIMES']      = '' . \_ADSLIGHT_TIMES . '';
                    $tags['TEAM']       = '' . \_ADSLIGHT_TEAM . '';
                    $tags['DURATION']   = $expire;
                    $tags['LINK_URL']   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewads.php?' . '&lid=' . $lids;
                    $mail               = \getMailer();
                    $mail->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
                    $mail->setTemplate('listing_expired.tpl');
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

    /**
     * @param $sel_id
     */
    public static function updateUserRating($sel_id): void
    {
        global $xoopsDB;

        $usid = Request::getInt('usid', 0, 'GET');

        $query = 'SELECT rating FROM ' . $xoopsDB->prefix('adslight_user_votedata') . ' WHERE usid=' . $xoopsDB->escape($sel_id) . ' ';
        //echo $query;
        $voteresult  = $xoopsDB->query($query);
        $votesDB     = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
        while (false !== [$rating] = $xoopsDB->fetchRow($voteresult)) {
            $totalrating += $rating;
        }
        $finalrating = $totalrating / $votesDB;
        $finalrating = \number_format($finalrating, 4);
        $query = 'UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET user_rating=${finalrating}, user_votes=${votesDB} WHERE usid=" . $xoopsDB->escape($sel_id) . '';
        //echo $query;
        $xoopsDB->query($query) || exit();
    }

    //updates rating data in itemtable for a given item

    /**
     * @param $sel_id
     */
    public static function updateItemRating($sel_id): void
    {
        global $xoopsDB;

        $lid = Request::getInt('lid', 0, 'GET');

        $query = 'SELECT rating FROM ' . $xoopsDB->prefix('adslight_item_votedata') . ' WHERE lid=' . $xoopsDB->escape($sel_id) . ' ';
        //echo $query;
        $voteresult  = $xoopsDB->query($query);
        $votesDB     = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
        while (false !== [$rating] = $xoopsDB->fetchRow($voteresult)) {
            $totalrating += $rating;
        }
        $finalrating = $totalrating / $votesDB;
        $finalrating = \number_format($finalrating, 4);
        $query = 'UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET item_rating=${finalrating}, item_votes=${votesDB} WHERE lid=" . $xoopsDB->escape($sel_id) . '';
        //echo $query;
        $xoopsDB->query($query) || exit();
    }

    /**
     * @param        $sel_id
     * @param string $status
     */
    public static function getTotalItems($sel_id, $status = ''): int
    {
        global $xoopsDB, $mytree;
        $categories = self::getMyItemIds('adslight_view');
        $count      = 0;
        $arr        = [];
        if (\in_array($sel_id, $categories, true)) {
            $query = 'SELECT SQL_CACHE count(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE cid=' . (int)$sel_id . " AND valid='Yes' AND status!='1'";

            $result = $xoopsDB->query($query);
            [$thing] = $xoopsDB->fetchRow($result);
            $count = $thing;
            $arr   = $mytree->getAllChildId($sel_id);
            foreach ($arr as $iValue) {
                if (\in_array($iValue, $categories, true)) {
                    $query2 = 'SELECT SQL_CACHE count(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE cid=' . (int)$iValue . " AND valid='Yes' AND status!='1'";

                    $result2 = $xoopsDB->query($query2);
                    [$thing] = $xoopsDB->fetchRow($result2);
                    $count += $thing;
                }
            }
        }

        return (int)$count;
    }

    /**
     * @param $permtype
     */
    public static function getMyItemIds($permtype)
    {
        static $permissions = [];
        if (\is_array($permissions)
            && \array_key_exists($permtype, $permissions)) {
            return $permissions[$permtype];
        }

        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $myModule      = $moduleHandler->getByDirname('adslight');
        $groups        = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler       = \xoops_getHandler('groupperm');
        $categories             = $grouppermHandler->getItemIds($permtype, $groups, $myModule->getVar('mid'));
        $permissions[$permtype] = $categories;

        return $categories;
    }

    /**
     * Returns a module's option
     * @param string $option module option's name
     * @param string $repmodule
     *
     * @return bool|mixed option's value
     */
    public static function getModuleOption($option, $repmodule = 'adslight')
    {
        global $xoopsModule;
        $helper = \XoopsModules\Adslight\Helper::getInstance();
        static $tbloptions = [];
        if (\is_array($tbloptions) && \array_key_exists($option, $tbloptions)) {
            return $tbloptions[$option];
        }

        $retval = false;
        if (isset($GLOBALS['xoopsModuleConfig'])
            && (\is_object($xoopsModule)
                && $xoopsModule->getVar('dirname') === $repmodule
                && $xoopsModule->getVar('isactive'))) {
            if (isset($GLOBALS['xoopsModuleConfig'][$option])) {
                $retval = $GLOBALS['xoopsModuleConfig'][$option];
            }
        } else {
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = \xoops_getHandler('module');
            $module        = $moduleHandler->getByDirname($repmodule);
            /** @var \XoopsConfigHandler $configHandler */
            $configHandler = \xoops_getHandler('config');
            if ($module) {
                $moduleConfig = $configHandler->getConfigsByCat(0, $GLOBALS['xoopsModule']->getVar('mid'));
                if (null !== $helper->getConfig($option)) {
                    $retval = $helper->getConfig($option);
                }
            }
        }
        $tbloptions[$option] = $retval;

        return $retval;
    }

    public static function showImage(): void
    {
        echo "<script type=\"text/javascript\">\n";
        echo "<!--\n\n";
        echo "function showimage() {\n";
        echo "if (!document.images)\n";
        echo "return\n";
        echo "document.images.avatar.src=\n";
        echo "'" . XOOPS_URL . "/modules/adslight/assets/images/img_cat/' + document.imcat.img.options[document.imcat.img.selectedIndex].value\n";
        echo "}\n\n";
        echo "//-->\n";
        echo "</script>\n";
    }

    //Reusable Link Sorting Functions

    /**
     * @param $orderby
     */
    public static function convertOrderByIn($orderby): string
    {
        switch (\trim($orderby)) {
            case 'titleA':
                $orderby = 'title ASC';
                break;
            case 'dateA':
                $orderby = 'date_created ASC';
                break;
            case 'hitsA':
                $orderby = 'hits ASC';
                break;
            case 'priceA':
                $orderby = 'price ASC';
                break;
            case 'titleD':
                $orderby = 'title DESC';
                break;
            case 'hitsD':
                $orderby = 'hits DESC';
                break;
            case 'priceD':
                $orderby = 'price DESC';
                break;
            case'dateD':
            default:
                $orderby = 'date_created DESC';
                break;
        }

        return $orderby;
    }

    /**
     * @param $orderby
     */
    public static function convertOrderByTrans($orderby): string
    {
        $orderbyTrans = '';
        if ('hits ASC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_POPULARITYLTOM . '';
        }
        if ('hits DESC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_POPULARITYMTOL . '';
        }
        if ('title ASC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_TITLEATOZ . '';
        }
        if ('title DESC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_TITLEZTOA . '';
        }
        if ('date_created ASC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_DATEOLD . '';
        }
        if ('date_created DESC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_DATENEW . '';
        }
        if ('price ASC' === $orderby) {
            $orderbyTrans = \_ADSLIGHT_PRICELTOH;
        }
        if ('price DESC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_PRICEHTOL . '';
        }

        return $orderbyTrans;
    }

    /**
     * @param $orderby
     */
    public static function convertOrderByOut($orderby): string
    {
        if ('title ASC' === $orderby) {
            $orderby = 'titleA';
        }
        if ('date_created ASC' === $orderby) {
            $orderby = 'dateA';
        }
        if ('hits ASC' === $orderby) {
            $orderby = 'hitsA';
        }
        if ('price ASC' === $orderby) {
            $orderby = 'priceA';
        }
        if ('title DESC' === $orderby) {
            $orderby = 'titleD';
        }
        if ('date_created DESC' === $orderby) {
            $orderby = 'dateD';
        }
        if ('hits DESC' === $orderby) {
            $orderby = 'hitsD';
        }
        if ('price DESC' === $orderby) {
            $orderby = 'priceD';
        }

        return $orderby;
    }

    /**
     * @param $tablename
     */
    public static function checkTableExists($tablename): bool
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '${tablename}'");

        return $xoopsDB->getRowsNum($result) > 0;
    }

    /**
     * @param $fieldname
     * @param $table
     */
    public static function checkFieldExists($fieldname, $table): bool
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW COLUMNS FROM ${table} LIKE '${fieldname}'");

        return $xoopsDB->getRowsNum($result) > 0;
    }

    /**
     * @param $cid
     */
    public static function getCatNameFromId($cid): bool
    {
        global $xoopsDB, $myts;

        $sql = 'SELECT SQL_CACHE title FROM ' . $xoopsDB->prefix('adslight_categories') . " WHERE cid = '${cid}'";

        if (!$result = $xoopsDB->query($sql)) {
            return false;
        }

        if (!$arr = $xoopsDB->fetchArray($result)) {
            return false;
        }

        return $arr['title'];
    }


    public static function goCategory(): array
    {
        global $xoopsDB;

        $xoopsTree = new \XoopsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
        $jump      = XOOPS_URL . '/modules/adslight/viewcats.php?cid=';
        \ob_start();
        $xoopsTree->makeMySelBox('title', 'title', 0, 1, 'pid', 'location="' . $jump . '"+this.options[this.selectedIndex].value');
        $block['selectbox'] = \ob_get_clean();

        return $block;
    }

    // ADSLIGHT Version 2 //
    // Fonction rss.php RSS par categories


    public static function returnAllAdsRss(): array
    {
        global $xoopsDB;

        $cid = Request::getInt('cid', null, 'GET');

        $result = [];

        $sql = 'SELECT lid, title, price, date_created, town FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='yes' AND cid=" . $xoopsDB->escape($cid) . ' ORDER BY date_created DESC';

        $resultValues = $xoopsDB->query($sql);
        while (false !== ($resultTemp = $xoopsDB->fetchBoth($resultValues))) {
            $result[] = $resultTemp;
        }

        return $result;
    }

    // Fonction fluxrss.php RSS Global


    public static function returnAllAdsFluxRss(): array
    {
        global $xoopsDB;

        $result = [];

        $sql = 'SELECT lid, title, price, desctext, date_created, town FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='yes' ORDER BY date_created DESC LIMIT 0,15";

        $resultValues = $xoopsDB->query($sql);
        while (false !== ($resultTemp = $xoopsDB->fetchBoth($resultValues))) {
            $result[] = $resultTemp;
        }

        return $result;
    }

    /**
     * @param $type
     */
    public static function getNameType($type)
    {
        global $xoopsDB;
        $sql = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type='" . $xoopsDB->escape($type) . "'");
        [$nom_type] = $xoopsDB->fetchRow($sql);

        return $nom_type;
    }

    /**
     * @param $format
     * @param $number
     */
    public static function getMoneyFormat(
        $format,
        $number
    ) {
        $regex = '/%((?:[\^!\-]|\+|\(|\=.)*)(\d+)?' . '(?:#(\d+))?(?:\.(\d+))?([in%])/';
        if ('C' === \setlocale(\LC_MONETARY, 0)) {
            \setlocale(\LC_MONETARY, '');
        }
        \setlocale(\LC_ALL, 'en_US');
        //        setlocale(LC_ALL, 'fr_FR');
        $locale = \localeconv();
        \preg_match_all($regex, $format, $matches, \PREG_SET_ORDER);
        foreach ($matches as $fmatch) {
            $value      = (float)$number;
            $flags      = [
                'fillchar'  => \preg_match('#\=(.)#', $fmatch[1], $match) ? $match[1] : ' ',
                'nogroup'   => \preg_match('#\^#', $fmatch[1]) > 0,
                'usesignal' => \preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0] : '+',
                'nosimbol'  => \preg_match('#\!#', $fmatch[1]) > 0,
                'isleft'    => \preg_match('#\-#', $fmatch[1]) > 0,
            ];
            $width      = \trim($fmatch[2]) ? (int)$fmatch[2] : 0;
            $left       = \trim($fmatch[3]) ? (int)$fmatch[3] : 0;
            $right      = \trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
            $conversion = $fmatch[5];
            $positive   = true;
            if ($value < 0) {
                $positive = false;
                $value    *= -1;
            }
            $letter = $positive ? 'p' : 'n';
            $prefix = $suffix = $cprefix = $csuffix = $signal = '';
            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
            switch (true) {
                case 1 === $locale["{$letter}_sign_posn"]
                     && '+' === $flags['usesignal']:
                    $prefix = $signal;
                    break;
                case 2 === $locale["{$letter}_sign_posn"]
                     && '+' === $flags['usesignal']:
                    $suffix = $signal;
                    break;
                case 3 === $locale["{$letter}_sign_posn"]
                     && '+' === $flags['usesignal']:
                    $cprefix = $signal;
                    break;
                case 4 === $locale["{$letter}_sign_posn"]
                     && '+' === $flags['usesignal']:
                    $csuffix = $signal;
                    break;
                case '(' === $flags['usesignal']:
                case 0 === $locale["{$letter}_sign_posn"]:
                    $prefix = '(';
                    $suffix = ')';
                    break;
            }
            if ($flags['nosimbol']) {
                $currency = '';
            } else {
                $currency = $cprefix . ('i' === $conversion ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . $csuffix;
            }
            $space = $locale["{$letter}_sep_by_space"] ? ' ' : '';
            $value = \number_format(
                $value,
                $right,
                $locale['mon_decimal_point'],
                $flags['nogroup'] ? '' : $locale['mon_thousands_sep']
            );
            $value = @\explode($locale['mon_decimal_point'], $value);
            $n     = \mb_strlen($prefix) + \mb_strlen($currency) + \mb_strlen($value[0]);
            if ($left > 0 && $left > $n) {
                $value[0] = \str_repeat($flags['fillchar'], $left - $n) . $value[0];
            }
            $value = \implode($locale['mon_decimal_point'], $value);
            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix . $currency . $space . $value . $suffix;
            } else {
                $value = $prefix . $value . $space . $currency . $suffix;
            }
            if ($width > 0) {
                $value = \str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? \STR_PAD_RIGHT : \STR_PAD_LEFT);
            }
            $format = \str_replace($fmatch[0], $value, $format);
        }
        return $format;
    }

    /**
     * Saves permissions for the selected category
     *
     *   saveCategory_Permissions()
     *
     * @param array  $groups : group with granted permission
     * @param        $categoryId
     * @param        $permName
     * @return bool : TRUE if the no errors occured
     */
    public static function saveCategoryPermissions($groups, $categoryId, $permName): bool
    {
        global $xoopsModule;
        $helper = \XoopsModules\Adslight\Helper::getInstance();

        $result = true;
        //        $xoopsModule = sf_getModuleInfo();
        //        $moduleId = $helper->getModule()->getVar('mid');
        $moduleId = $xoopsModule->getVar('mid');

        $grouppermHandler = \xoops_getHandler('groupperm');
        // First, if the permissions are already there, delete them
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler->deleteByModule($moduleId, $permName, $categoryId);
        // Save the new permissions
        if (\count($groups) > 0) {
            foreach ($groups as $groupId) {
                $grouppermHandler->addRight($permName, $categoryId, $groupId, $moduleId);
            }
        }

        return $result;
    }

    /***********************************************************************
     * $fldVersion : dossier version de fancybox
     ***********************************************************************/
    public static function load_lib_js(): void
    {
        global $xoTheme, $xoopsModuleConfig;

        $fld = XOOPS_URL . '/modules/adslight/' . 'assets/';

        if (1 === $GLOBALS['xoopsModuleConfig']['adslight_lightbox']) {
            // $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/plugins/jquery.lightbox.js');
            // $xoTheme->addStyleSheet(XOOPS_URL . '/browse.php?Frameworks/jquery/plugins/jquery.lightbox.js');

            $xoTheme->addScript($fld . '/js/lightbox/js/lightbox.js');
            $xoTheme->addStyleSheet($fld . '/js/lightbox/css/lightbox.css');
        }
            //$xoTheme->addStyleSheet($fld . "/css/galery.css" type="text/css" media="screen");


        /*
                    if (1 == $GLOBALS['xoopsModuleConfig']['adslight_lightbox']) {
                        $header_lightbox = '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >
        <script type="text/javascript" src="assets/lightbox/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="assets/lightbox/js/jquery-ui-1.8.18.custom.min"></script>
        <script type="text/javascript" src="assets/lightbox/js/jquery.smooth-scroll.min.js"></script>
        <script type="text/javascript" src="assets/lightbox/js/lightbox.js"></script>

        <link rel="stylesheet" href="assets/css/galery.css" type="text/css" media="screen" >
        <link rel="stylesheet" type="text/css" media="screen" href="assets/lightbox/css/lightbox.css"></link>';
                    } else {
                        $header_lightbox = '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >
        <link rel="stylesheet" href="assets/css/galery.css" type="text/css" media="screen" >';
                    }


          $fldVersion = "fancybox_215";
          $fbFolder =  XOOPS_URL . "/Frameworks/" . $fldVersion;
          //$modFolder = "modules/" . $module_dirname;
          $modFolder = "modules/" . 'mediatheque';

            //$xoTheme->addStyleSheet($fModule . '/css/style.css');
            $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');

          //to-do : a remplacer par  jquery.mousewheel-3.0.6.pack.js
          $xoTheme->addScript($fbFolder . "/jquery.mousewheel-3.0.4.pack.js");

            $xoTheme->addStyleSheet($fbFolder . "/jquery.fancybox.css?v=2.1.5");
            $xoTheme->addScript($fbFolder . "/jquery.fancybox.js?v=2.1.5");

        //-----------------------------------------
        //  OPTIONAL
            $xoTheme->addStyleSheet($fbFolder . "/helpers/jquery.fancybox-buttons.css?v=1.0.5");
            $xoTheme->addScript($fbFolder . "/helpers/jquery.fancybox-buttons.js?v=1.0.5");

            $xoTheme->addStyleSheet($fbFolder . "/helpers/jquery.fancybox-thumbs.css?v=1.0.7");
            $xoTheme->addScript($fbFolder . "/helpers/jquery.fancybox-thumbs.js?v=1.0.7");

            $xoTheme->addScript($fbFolder . "/helpers/jquery.fancybox-media.js?v=1.0.6");

        //-----------------------------------------



            $xoTheme->addScript($modFolder . "/js/media.fancybox.js");

        */
    }

    /**
     * Currency Format
     *
     * @param float  $number
     * @param string $currency   The 3-letter ISO 4217 currency code indicating the currency to use.
     * @param string $localeCode (local language code, e.g. en_US)
     * @return string formatted currency value
     */
    public static function formatCurrency($number, $currency = 'USD', $localeCode = ''): ?string
    {
        $localeCode ?? \locale_get_default();
        $fmt = new \NumberFormatter($localeCode, \NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($number, $currency);
    }

    /**
     * Currency Format (temporary)
     *
     * @param float  $number
     * @param string $currency The 3-letter ISO 4217 currency code indicating the currency to use.
     * @param string $currencySymbol
     * @param int    $currencyPosition
     * @return string formatted currency value
     */
    public static function formatCurrencyTemp($number, $currency = 'USD', $currencySymbol = '$', $currencyPosition = 0): string
    {
        $currentDefault  = \locale_get_default();
        $fmt             = new \NumberFormatter($currentDefault, \NumberFormatter::DECIMAL);
        $formattedNumber = $fmt->format((float)$number);
        return 1 === $currencyPosition ? $currencySymbol . $formattedNumber : $formattedNumber . ' ' . $currencySymbol;
    }


    /**
     * @param Categories $categoryObj
     * @param int      $level
     */
    public static function displayCategory(Categories $categoryObj, $level = 0)
    {
        $helper = Helper::getInstance();
        $configurator = new Common\Configurator();
        $icons = $configurator->icons;

        $description = $categoryObj->cat_desc;
        if (!XOOPS_USE_MULTIBYTES && !empty($description)) {
            if (\mb_strlen($description) >= 100) {
                $description = \mb_substr($description, 0, 100 - 1) . '...';
            }
        }
        $modify = "<a href='category.php?op=mod&amp;cid=" . $categoryObj->cid() . '&amp;pid=' . $categoryObj->pid() . "'>" . $icons['edit'] . '</a>';
        $delete = "<a href='category.php?op=del&amp;cid=" . $categoryObj->cid() . "'>" . $icons['delete'] . '</a>';
        $spaces = \str_repeat('&nbsp;', ($level * 3));
        /*
        $spaces = '';
        for ($j = 0; $j < $level; ++$j) {
            $spaces .= '&nbsp;&nbsp;&nbsp;';
        }
        */
        echo "<tr>\n"
             . "<td class='even center'>"
             . $categoryObj->cid()
             . "</td>\n"
             . "<td class='even left'>"
             . $spaces
             . "<a href='" . $helper->url() . 'category.php?cid=' . $categoryObj->cid()
             . "'><img src='" . $helper->url() . "assets/images/links/subcat.gif' alt=''>&nbsp;"
             . $categoryObj->name()
             . "</a></td>\n"
             . "<td class='even center'>"
             . $categoryObj->weight()
             . "</td>\n"
             . "<td class='even center'> {$modify} {$delete} </td>\n"
             . "</tr>\n";
        $subCategoriesObj = $helper->getHandler('Categories')->getCategories(0, 0, $categoryObj->cid());
        if (\count($subCategoriesObj) > 0) {
            ++$level;
            foreach ($subCategoriesObj as $thiscat) {
                self::displayCategory($thiscat, $level);
            }
            unset($key);
        }
        //        unset($categoryObj);
    }
}
