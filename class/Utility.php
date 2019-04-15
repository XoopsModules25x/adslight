<?php

namespace XoopsModules\Adslight;

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

/**
 * AdslightUtil Class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      XOOPS Development Team
 * @package     AdsLight
 * @since       1.03
 */

use Xmf\Request;
use XoopsModules\Adslight;

require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$myts = \MyTextSanitizer::getInstance();

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------

    public static function expireAd()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $meta;

        $datenow = time();
        $message = '';

        $result5 = $xoopsDB->query('SELECT lid, title, expire, type, desctext, date, email, submitter, photo, valid, hits, comments, remind FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='Yes'");

        while (false !== (list($lids, $title, $expire, $type, $desctext, $dateann, $email, $submitter, $photo, $valid, $hits, $comments, $remind) = $xoopsDB->fetchRow($result5))) {
            $title     = $myts->htmlSpecialChars($title);
            $expire    = $myts->htmlSpecialChars($expire);
            $type      = $myts->htmlSpecialChars($type);
            $desctext  = &$myts->displayTarea($desctext, 1, 1, 1, 1, 1);
            $submitter = $myts->htmlSpecialChars($submitter);
            $remind    = $myts->htmlSpecialChars($remind);
            $supprdate = $dateann + ($expire * 86400);
            $almost    = $GLOBALS['xoopsModuleConfig']['adslight_almost'];

            // give warning that add is about to expire

            if ($almost > 0 && ($supprdate - $almost * 86400) < $datenow
                && 'Yes' === $valid
                && 0 == $remind) {
                $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET remind='1' WHERE lid=$lids");

                if ($email) {
                    $tags               = [];
                    $subject            = '' . _ADSLIGHT_ALMOST . '';
                    $tags['TITLE']      = $title;
                    $tags['HELLO']      = '' . _ADSLIGHT_HELLO . '';
                    $tags['YOUR_AD_ON'] = '' . _ADSLIGHT_YOUR_AD_ON . '';
                    $tags['VEDIT_AD']   = '' . _ADSLIGHT_VEDIT_AD . '';
                    $tags['YOUR_AD']    = '' . _ADSLIGHT_YOUR_AD . '';
                    $tags['SOON']       = '' . _ADSLIGHT_SOON . '';
                    $tags['VIEWED']     = '' . _ADSLIGHT_VU . '';
                    $tags['TIMES']      = '' . _ADSLIGHT_TIMES . '';
                    $tags['WEBMASTER']  = '' . _ADSLIGHT_WEBMASTER . '';
                    $tags['THANKS']     = '' . _ADSLIGHT_THANKS . '';
                    $tags['TYPE']       = static::getNameType($type);
                    $tags['DESCTEXT']   = $desctext;
                    $tags['HITS']       = $hits;
                    $tags['META_TITLE'] = $meta['title'];
                    $tags['SUBMITTER']  = $submitter;
                    $tags['DURATION']   = $expire;
                    $tags['LINK_URL']   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewads.php?' . '&lid=' . $lids;
                    $mail               = &getMailer();
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
                if (0 != $photo) {
                    $result2 = $xoopsDB->query('SELECT url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE lid=' . $xoopsDB->escape($lids));

                    while (false !== (list($url) = $xoopsDB->fetchRow($result2))) {
                        $destination  = XOOPS_ROOT_PATH . '/uploads/AdsLight';
                        $destination2 = XOOPS_ROOT_PATH . '/uploads/AdsLight/thumbs';
                        $destination3 = XOOPS_ROOT_PATH . '/uploads/AdsLight/midsize';
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

                $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $xoopsDB->escape($lids));

                //  Specification for Japan:
                //  $message = ""._ADS_HELLO." $submitter,\n\n"._ADS_STOP2."\n $type : $title\n $desctext\n"._ADS_STOP3."\n\n"._ADS_VU." $lu "._ADS_VU2."\n\n"._ADS_OTHER." ".XOOPS_URL."/modules/myAds\n\n"._ADS_THANK."\n\n"._ADS_TEAM." ".$meta['title']."\n".XOOPS_URL."";
                if ($email) {
                    $tags               = [];
                    $subject            = '' . _ADSLIGHT_STOP . '';
                    $tags['TITLE']      = $title;
                    $tags['HELLO']      = '' . _ADSLIGHT_HELLO . '';
                    $tags['TYPE']       = static::getNameType($type);
                    $tags['DESCTEXT']   = $desctext;
                    $tags['HITS']       = $hits;
                    $tags['META_TITLE'] = $meta['title'];
                    $tags['SUBMITTER']  = $submitter;
                    $tags['YOUR_AD_ON'] = '' . _ADSLIGHT_YOUR_AD_ON . '';
                    $tags['EXPIRED']    = '' . _ADSLIGHT_EXPIRED . '';
                    $tags['MESSTEXT']   = stripslashes($message);
                    $tags['OTHER']      = '' . _ADSLIGHT_OTHER . '';
                    $tags['WEBMASTER']  = '' . _ADSLIGHT_WEBMASTER . '';
                    $tags['THANKS']     = '' . _ADSLIGHT_THANKS . '';
                    $tags['VIEWED']     = '' . _ADSLIGHT_VU . '';
                    $tags['TIMES']      = '' . _ADSLIGHT_TIMES . '';
                    $tags['TEAM']       = '' . _ADSLIGHT_TEAM . '';
                    $tags['DURATION']   = $expire;
                    $tags['LINK_URL']   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewads.php?' . '&lid=' . $lids;
                    $mail               = &getMailer();
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
    public static function updateUserRating($sel_id)
    {
        global $xoopsDB;

        $usid = Request::getInt('usid', 0, 'GET');

        $query = 'SELECT rating FROM ' . $xoopsDB->prefix('adslight_user_votedata') . ' WHERE usid=' . $xoopsDB->escape($sel_id) . ' ';
        //echo $query;
        $voteresult  = $xoopsDB->query($query);
        $votesDB     = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
        while (false !== (list($rating) = $xoopsDB->fetchRow($voteresult))) {
            $totalrating += $rating;
        }
        $finalrating = $totalrating / $votesDB;
        $finalrating = number_format($finalrating, 4);
        $query       = 'UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET user_rating=$finalrating, user_votes=$votesDB WHERE usid=" . $xoopsDB->escape($sel_id) . '';
        //echo $query;
        $xoopsDB->query($query) || exit();
    }

    //updates rating data in itemtable for a given item

    /**
     * @param $sel_id
     */
    public static function updateItemRating($sel_id)
    {
        global $xoopsDB;

        $lid = Request::getInt('lid', 0, 'GET');

        $query = 'SELECT rating FROM ' . $xoopsDB->prefix('adslight_item_votedata') . ' WHERE lid=' . $xoopsDB->escape($sel_id) . ' ';
        //echo $query;
        $voteresult  = $xoopsDB->query($query);
        $votesDB     = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
        while (false !== (list($rating) = $xoopsDB->fetchRow($voteresult))) {
            $totalrating += $rating;
        }
        $finalrating = $totalrating / $votesDB;
        $finalrating = number_format($finalrating, 4);
        $query       = 'UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET item_rating=$finalrating, item_votes=$votesDB WHERE lid=" . $xoopsDB->escape($sel_id) . '';
        //echo $query;
        $xoopsDB->query($query) || exit();
    }

    /**
     * @param        $sel_id
     * @param string $status
     *
     * @return int
     */
    public static function getTotalItems($sel_id, $status = '')
    {
        global $xoopsDB, $mytree;
        $categories = self::getMyItemIds('adslight_view');
        $count      = 0;
        $arr        = [];
        if (in_array($sel_id, $categories, true)) {
            $query = 'SELECT SQL_CACHE count(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE cid=' . (int)$sel_id . " AND valid='Yes' AND status!='1'";

            $result = $xoopsDB->query($query);
            list($thing) = $xoopsDB->fetchRow($result);
            $count = $thing;
            $arr   = $mytree->getAllChildId($sel_id);
            foreach ($arr as $iValue) {
                if (in_array($iValue, $categories, true)) {
                    $query2 = 'SELECT SQL_CACHE count(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE cid=' . (int)$iValue . " AND valid='Yes' AND status!='1'";

                    $result2 = $xoopsDB->query($query2);
                    list($thing) = $xoopsDB->fetchRow($result2);
                    $count += $thing;
                }
            }
        }

        return $count;
    }

    /**
     * @param $permtype
     *
     * @return mixed
     */
    public static function getMyItemIds($permtype)
    {
        static $permissions = [];
        if (is_array($permissions)
            && array_key_exists($permtype, $permissions)) {
            return $permissions[$permtype];
        }

        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $myModule      = $moduleHandler->getByDirname('adslight');
        $groups        = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler       = xoops_getHandler('groupperm');
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
        /** @var \XoopsModules\Adslight\Helper $helper */
        $helper = \XoopsModules\Adslight\Helper::getInstance();
        static $tbloptions = [];
        if (is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
            return $tbloptions[$option];
        }

        $retval = false;
        if (isset($GLOBALS['xoopsModuleConfig'])
            && (is_object($xoopsModule)
                && $xoopsModule->getVar('dirname') == $repmodule
                && $xoopsModule->getVar('isactive'))) {
            if (isset($GLOBALS['xoopsModuleConfig'][$option])) {
                $retval = $GLOBALS['xoopsModuleConfig'][$option];
            }
        } else {
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = xoops_getHandler('module');
            $module        = $moduleHandler->getByDirname($repmodule);
            /** @var \XoopsConfigHandler $configHandler */
            $configHandler = xoops_getHandler('config');
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

    public static function showImage()
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
     *
     * @return string
     */
    public static function convertOrderByIn($orderby)
    {
        switch (trim($orderby)) {
            case 'titleA':
                $orderby = 'title ASC';
                break;
            case 'dateA':
                $orderby = 'date ASC';
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
                $orderby = 'date DESC';
                break;
        }

        return $orderby;
    }

    /**
     * @param $orderby
     *
     * @return string
     */
    public static function convertOrderByTrans($orderby)
    {
        $orderbyTrans = '';
        if ('hits ASC' === $orderby) {
            $orderbyTrans = '' . _ADSLIGHT_POPULARITYLTOM . '';
        }
        if ('hits DESC' === $orderby) {
            $orderbyTrans = '' . _ADSLIGHT_POPULARITYMTOL . '';
        }
        if ('title ASC' === $orderby) {
            $orderbyTrans = '' . _ADSLIGHT_TITLEATOZ . '';
        }
        if ('title DESC' === $orderby) {
            $orderbyTrans = '' . _ADSLIGHT_TITLEZTOA . '';
        }
        if ('date ASC' === $orderby) {
            $orderbyTrans = '' . _ADSLIGHT_DATEOLD . '';
        }
        if ('date DESC' === $orderby) {
            $orderbyTrans = '' . _ADSLIGHT_DATENEW . '';
        }
        if ('price ASC' === $orderby) {
            $orderbyTrans = _ADSLIGHT_PRICELTOH;
        }
        if ('price DESC' === $orderby) {
            $orderbyTrans = '' . _ADSLIGHT_PRICEHTOL . '';
        }

        return $orderbyTrans;
    }

    /**
     * @param $orderby
     *
     * @return string
     */
    public static function convertOrderByOut($orderby)
    {
        if ('title ASC' === $orderby) {
            $orderby = 'titleA';
        }
        if ('date ASC' === $orderby) {
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
        if ('date DESC' === $orderby) {
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
     * @param string $caption
     * @param string $name
     * @param string $value
     * @param string $width
     * @param string $height
     * @param string $supplemental
     *
     * @return \XoopsFormDhtmlTextArea|\XoopsFormEditor
     */
    public static function getEditor($caption, $name, $value = '', $width = '100%', $height = '300px', $supplemental = '')
    {
        global $xoopsModule;
        $options = [];
        $isAdmin = $GLOBALS['xoopsUser']->isAdmin($xoopsModule->getVar('mid'));

        if (class_exists('XoopsFormEditor')) {
            $options['name']   = $name;
            $options['value']  = $value;
            $options['rows']   = 20;
            $options['cols']   = '100%';
            $options['width']  = $width;
            $options['height'] = $height;
            if ($isAdmin) {
                $myEditor = new \XoopsFormEditor(ucfirst($name), $GLOBALS['xoopsModuleConfig']['adslightAdminUser'], $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $myEditor = new \XoopsFormEditor(ucfirst($name), $GLOBALS['xoopsModuleConfig']['adslightEditorUser'], $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $myEditor = new \XoopsFormDhtmlTextArea(ucfirst($name), $name, $value, '100%', '100%');
        }

        //        $form->addElement($descEditor);

        return $myEditor;
    }

    /**
     * @param $tablename
     *
     * @return bool
     */
    public static function checkTableExists($tablename)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * @param $fieldname
     * @param $table
     *
     * @return bool
     */
    public static function checkFieldExists($fieldname, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * @param $field
     * @param $table
     *
     * @return mixed
     */
    public static function addField($field, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");

        return $result;
    }

    /**
     * @param $cid
     *
     * @return bool
     */
    public static function getCatNameFromId($cid)
    {
        global $xoopsDB, $myts;

        $sql = 'SELECT SQL_CACHE title FROM ' . $xoopsDB->prefix('adslight_categories') . " WHERE cid = '$cid'";

        if (!$result = $xoopsDB->query($sql)) {
            return false;
        }

        if (!$arr = $xoopsDB->fetchArray($result)) {
            return false;
        }

        $title = $arr['title'];

        return $title;
    }

    /**
     * @return mixed
     */
    public static function goCategory()
    {
        global $xoopsDB;

        $xoopsTree   = new \XoopsTree($xoopsDB->prefix('adslight_categories'), 'cid', 'pid');
        $jump = XOOPS_URL . '/modules/adslight/viewcats.php?cid=';
        ob_start();
        $xoopsTree->makeMySelBox('title', 'title', 0, 1, 'pid', 'location="' . $jump . '"+this.options[this.selectedIndex].value');
        $block['selectbox'] = ob_get_clean();

        return $block;
    }

    // ADSLIGHT Version 2 //
    // Fonction rss.php RSS par categories

    /**
     * @return array
     */
    public static function returnAllAdsRss()
    {
        global $xoopsDB;

        $cid = Request::getInt('cid', null, 'GET');

        $result = [];

        $sql = 'SELECT lid, title, price, date, town FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='yes' AND cid=" . $xoopsDB->escape($cid) . ' ORDER BY date DESC';

        $resultValues = $xoopsDB->query($sql);
        while (false !== ($resultTemp = $xoopsDB->fetchBoth($resultValues))) {
            $result[] = $resultTemp;
        }

        return $result;
    }

    // Fonction fluxrss.php RSS Global

    /**
     * @return array
     */
    public static function returnAllAdsFluxRss()
    {
        global $xoopsDB;

        $result = [];

        $sql = 'SELECT lid, title, price, desctext, date, town FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='yes' ORDER BY date DESC LIMIT 0,15";

        $resultValues = $xoopsDB->query($sql);
        while (false !== ($resultTemp = $xoopsDB->fetchBoth($resultValues))) {
            $result[] = $resultTemp;
        }

        return $result;
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    public static function getNameType($type)
    {
        global $xoopsDB;
        $sql = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('adslight_type') . " WHERE id_type='" . $xoopsDB->escape($type) . "'");
        list($nom_type) = $xoopsDB->fetchRow($sql);

        return $nom_type;
    }

    /**
     * @param $format
     * @param $number
     *
     * @return mixed
     */
    public static function getMoneyFormat($format, $number)
    {
        $regex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' . '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
        if ('C' === setlocale(LC_MONETARY, 0)) {
            setlocale(LC_MONETARY, '');
        }

        setlocale(LC_ALL, 'en_US');
        //        setlocale(LC_ALL, 'fr_FR');

        $locale = localeconv();
        preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
        foreach ($matches as $fmatch) {
            $value      = (float)$number;
            $flags      = [
                'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? $match[1] : ' ',
                'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
                'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0] : '+',
                'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
                'isleft'    => preg_match('/\-/', $fmatch[1]) > 0,
            ];
            $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
            $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
            $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
            $conversion = $fmatch[5];

            $positive = true;
            if ($value < 0) {
                $positive = false;
                $value    *= -1;
            }
            $letter = $positive ? 'p' : 'n';

            $prefix = $suffix = $cprefix = $csuffix = $signal = '';

            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
            switch (true) {
                case 1 == $locale["{$letter}_sign_posn"]
                     && '+' == $flags['usesignal']:
                    $prefix = $signal;
                    break;
                case 2 == $locale["{$letter}_sign_posn"]
                     && '+' == $flags['usesignal']:
                    $suffix = $signal;
                    break;
                case 3 == $locale["{$letter}_sign_posn"]
                     && '+' == $flags['usesignal']:
                    $cprefix = $signal;
                    break;
                case 4 == $locale["{$letter}_sign_posn"]
                     && '+' == $flags['usesignal']:
                    $csuffix = $signal;
                    break;
                case '(' === $flags['usesignal']:
                case 0 == $locale["{$letter}_sign_posn"]:
                    $prefix = '(';
                    $suffix = ')';
                    break;
            }
            if (!$flags['nosimbol']) {
                $currency = $cprefix . ('i' === $conversion ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . $csuffix;
            } else {
                $currency = '';
            }
            $space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

            $value = number_format($value, $right, $locale['mon_decimal_point'], $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
            $value = @explode($locale['mon_decimal_point'], $value);

            $n = mb_strlen($prefix) + mb_strlen($currency) + mb_strlen($value[0]);
            if ($left > 0 && $left > $n) {
                $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
            }
            $value = implode($locale['mon_decimal_point'], $value);
            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix . $currency . $space . $value . $suffix;
            } else {
                $value = $prefix . $value . $space . $currency . $suffix;
            }
            if ($width > 0) {
                $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT);
            }

            $format = str_replace($fmatch[0], $value, $format);
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
    public static function saveCategoryPermissions($groups, $categoryId, $permName)
    {
        global $xoopsModule;
        /** @var \XoopsModules\Adslight\Helper $helper */
        $helper = \XoopsModules\Adslight\Helper::getInstance();

        $result = true;
        //        $xoopsModule = sf_getModuleInfo();
        $moduleId = $helper->getModule()->getVar('mid');

        $grouppermHandler = xoops_getHandler('groupperm');
        // First, if the permissions are already there, delete them
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler->deleteByModule($moduleId, $permName, $categoryId);
        // Save the new permissions
        if (count($groups) > 0) {
            foreach ($groups as $groupId) {
                $grouppermHandler->addRight($permName, $categoryId, $groupId, $moduleId);
            }
        }

        return $result;
    }
}
