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
use XoopsModules\Adslight\Common;

require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$myts = \MyTextSanitizer::getInstance();

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks;  //checkVerXoops, checkVerPhp Traits
    use Common\ServerStats;    // getServerStats Trait
    use Common\FilesManagement;    // Files Management Trait

    //--------------- Custom module methods -----------------------------

    public static function expireAd()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $meta;

        $datenow = \time();
        $message = '';

        $result5 = $xoopsDB->query('SELECT lid, title, expire, type, desctext, date, email, submitter, photo, valid, hits, comments, remind FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid='Yes'");

        while (false !== (list($lids, $title, $expire, $type, $desctext, $dateann, $email, $submitter, $photo, $valid, $hits, $comments, $remind) = $xoopsDB->fetchRow($result5))) {
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
                && 0 == $remind) {
                $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('adslight_listing') . " SET remind='1' WHERE lid=$lids");

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
                if (0 != $photo) {
                    $result2 = $xoopsDB->query('SELECT url FROM ' . $xoopsDB->prefix('adslight_pictures') . ' WHERE lid=' . $xoopsDB->escape($lids));

                    while (false !== (list($url) = $xoopsDB->fetchRow($result2))) {
                        $destination  = XOOPS_ROOT_PATH . '/uploads/adslight';
                        $destination2 = XOOPS_ROOT_PATH . '/uploads/adslight/thumbs';
                        $destination3 = XOOPS_ROOT_PATH . '/uploads/adslight/midsize';
                        if (\is_file("$destination/$url")) {
                            \unlink("$destination/$url");
                        }
                        if (\is_file("$destination2/thumb_$url")) {
                            \unlink("$destination2/thumb_$url");
                        }
                        if (\is_file("$destination3/resized_$url")) {
                            \unlink("$destination3/resized_$url");
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
                    $tags['META_TITLE'] = $meta['title'];
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
        $finalrating = \number_format($finalrating, 4);
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
        $finalrating = \number_format($finalrating, 4);
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
        if (\in_array($sel_id, $categories)) {
            $query = 'SELECT SQL_CACHE count(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE cid=' . (int)$sel_id . " AND valid='Yes' AND status!='1'";

            $result = $xoopsDB->query($query);
            [$thing] = $xoopsDB->fetchRow($result);
            $count = $thing;
            $arr   = $mytree->getAllChildId($sel_id);
            foreach ($arr as $iValue) {
                if (\in_array($iValue, $categories)) {
                    $query2 = 'SELECT SQL_CACHE count(*) FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE cid=' . (int)$iValue . " AND valid='Yes' AND status!='1'";

                    $result2 = $xoopsDB->query($query2);
                    [$thing] = $xoopsDB->fetchRow($result2);
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
                && $xoopsModule->getVar('dirname') == $repmodule
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
        switch (\trim($orderby)) {
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
        if ('date ASC' === $orderby) {
            $orderbyTrans = '' . \_ADSLIGHT_DATEOLD . '';
        }
        if ('date DESC' === $orderby) {
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

        if (\class_exists('XoopsFormEditor')) {
            $options['name']   = $name;
            $options['value']  = $value;
            $options['rows']   = 20;
            $options['cols']   = '100%';
            $options['width']  = $width;
            $options['height'] = $height;
            if ($isAdmin) {
                $myEditor = new \XoopsFormEditor(\ucfirst($name), $GLOBALS['xoopsModuleConfig']['adslightAdminUser'], $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $myEditor = new \XoopsFormEditor(\ucfirst($name), $GLOBALS['xoopsModuleConfig']['adslightEditorUser'], $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $myEditor = new \XoopsFormDhtmlTextArea(\ucfirst($name), $name, $value, '100%', '100%');
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
     * @return array
     */
    public static function goCategory()
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
        [$nom_type] = $xoopsDB->fetchRow($sql);

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
        if ('C' === \setlocale(\LC_MONETARY, 0)) {
            \setlocale(\LC_MONETARY, '');
        }

        //JJDai
        // setlocale(LC_ALL, 'en_US');
        //setlocale(LC_ALL, 'fr_FR');
        //$symb = $helper->getConfig('adslight_currency_symbol');

        $locale = \localeconv();
        \preg_match_all($regex, $format, $matches, \PREG_SET_ORDER);
        foreach ($matches as $fmatch) {
            $value      = (float)$number;
            $flags      = [
                'fillchar'  => \preg_match('/\=(.)/', $fmatch[1], $match) ? $match[1] : ' ',
                'nogroup'   => \preg_match('/\^/', $fmatch[1]) > 0,
                'usesignal' => \preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0] : '+',
                'nosimbol'  => \preg_match('/\!/', $fmatch[1]) > 0,
                'isleft'    => \preg_match('/\-/', $fmatch[1]) > 0,
            ];
            $width      = \trim($fmatch[2]) ? (int)$fmatch[2] : 0;
            $left       = \trim($fmatch[3]) ? (int)$fmatch[3] : 0;
            $right      = \trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
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
            if ($flags['nosimbol']) {
                $currency = '';
            } else {
                $currency = $cprefix . ('i' === $conversion ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . $csuffix;
            }
            $space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

            $value = \number_format($value, $right, $locale['mon_decimal_point'], $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
            $value = @\explode($locale['mon_decimal_point'], $value);

            $n = \mb_strlen($prefix) + \mb_strlen($currency) + \mb_strlen($value[0]);
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
    public static function saveCategoryPermissions($groups, $categoryId, $permName)
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


    //======================= NEW ========================
    //--------------- Custom module methods -----------------------------

    /**
     * @param $text
     * @param $form_sort
     * @return string
     */
    public static function selectSorting($text, $form_sort)
    {
        global $start, $order, $file_cat, $sort, $xoopsModule;

        $select_view   = '';
        $moduleDirName = \basename(\dirname(__DIR__));
        $helper        = Adslight\Helper::getInstance();

        $pathModIcon16 = XOOPS_URL . '/modules/' . $moduleDirName . '/' . $helper->getModule()->getInfo('modicons16');

        $select_view = '<form name="form_switch" id="form_switch" action="' . Request::getString('REQUEST_URI', '', 'SERVER') . '" method="post"><span style="font-weight: bold;">' . $text . '</span>';
        //$sorts =  $sort ==  'asc' ? 'desc' : 'asc';
        if ($form_sort == $sort) {
            $sel1 = 'asc' === $order ? 'selasc.png' : 'asc.png';
            $sel2 = 'desc' === $order ? 'seldesc.png' : 'desc.png';
        } else {
            $sel1 = 'asc.png';
            $sel2 = 'desc.png';
        }
        $select_view .= '  <a href="' . Request::getString('PHP_SELF', '', 'SERVER') . '?start=' . $start . '&sort=' . $form_sort . '&order=asc"><img src="' . $pathModIcon16 . '/' . $sel1 . '" title="ASC" alt="ASC"></a>';
        $select_view .= '<a href="' . Request::getString('PHP_SELF', '', 'SERVER') . '?start=' . $start . '&sort=' . $form_sort . '&order=desc"><img src="' . $pathModIcon16 . '/' . $sel2 . '" title="DESC" alt="DESC"></a>';
        $select_view .= '</form>';

        return $select_view;
    }

    /***************Blocks***************/
    /**
     * @param array $cats
     * @return string
     */
    public static function blockAddCatSelect($cats)
    {
        $cat_sql = '';
        if (\is_array($cats)) {
            $cat_sql = '(' . \current($cats);
            \array_shift($cats);
            foreach ($cats as $cat) {
                $cat_sql .= ',' . $cat;
            }
            $cat_sql .= ')';
        }

        return $cat_sql;
    }

    /**
     * @param $content
     */
    public static function metaKeywords($content)
    {
        global $xoopsTpl, $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (null !== $xoTheme && \is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'keywords', \strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_metaKeywords', \strip_tags($content));
        }
    }

    /**
     * @param $content
     */
    public static function metaDescription($content)
    {
        global $xoopsTpl, $xoTheme;
        $myts    = \MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (null !== $xoTheme && \is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'description', \strip_tags($content));
        } else {    // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_metaDescription', \strip_tags($content));
        }
    }

    /**
     * @param $tableName
     * @param $columnName
     *
     * @return array
     */
    public static function enumerate($tableName, $columnName)
    {
        $table = $GLOBALS['xoopsDB']->prefix($tableName);

        //    $result = $GLOBALS['xoopsDB']->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        //        WHERE TABLE_NAME = '" . $table . "' AND COLUMN_NAME = '" . $columnName . "'")
        //    || exit ($GLOBALS['xoopsDB']->error());

        $sql    = 'SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "' . $table . '" AND COLUMN_NAME = "' . $columnName . '"';
        $result = $GLOBALS['xoopsDB']->query($sql);
        if (!$result) {
            exit($GLOBALS['xoopsDB']->error());
        }

        $row      = $GLOBALS['xoopsDB']->fetchBoth($result);
        $enumList = \explode(',', \str_replace("'", '', \substr($row['COLUMN_TYPE'], 5, -6)));
        return $enumList;
    }

    /**
     * @param array|string $tableName
     * @param int          $id_field
     * @param int          $id
     *
     * @return false|void
     */
    public static function cloneRecord($tableName, $id_field, $id)
    {
        $new_id = false;
        $table  = $GLOBALS['xoopsDB']->prefix($tableName);
        // copy content of the record you wish to clone
        $tempTable = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query("SELECT * FROM $table WHERE $id_field='$id' "), \MYSQLI_ASSOC) || exit('Could not select record');
        // set the auto-incremented id's value to blank.
        unset($tempTable[$id_field]);
        // insert cloned copy of the original  record
        $result = $GLOBALS['xoopsDB']->queryF("INSERT INTO $table (" . \implode(', ', \array_keys($tempTable)) . ") VALUES ('" . \implode("', '", \array_values($tempTable)) . "')") || exit($GLOBALS['xoopsDB']->error());

        if ($result) {
            // Return the new id
            $new_id = $GLOBALS['xoopsDB']->getInsertId();
        }
        return $new_id;
    }

    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
     * www.cakephp.org
     *
     * @param string $text         String to truncate.
     * @param int    $length       Length of returned string, including ellipsis.
     * @param string $ending       Ending to be appended to the trimmed string.
     * @param bool   $exact        If false, $text will not be cut mid-word
     * @param bool   $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
    {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (\strlen(\preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            \preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, \PREG_SET_ORDER);
            $total_length = \strlen($ending);
            $openTags     = [];
            $truncate     = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (\preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (\preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $openTags list
                        $pos = \array_search($tag_matchings[1], $openTags);
                        if (false !== $pos) {
                            unset($openTags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (\preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $openTags list
                        \array_unshift($openTags, \strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = \strlen(\preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left            = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (\preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, \PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entities_length <= $left) {
                                $left--;
                                $entities_length += \strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= \substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate     .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } elseif (\strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = \substr($text, 0, $length - \strlen($ending));
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = \strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = \substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /***********************************************************************
     * $fldVersion : dossier version de fancybox
     ***********************************************************************/
    public static function load_lib_js()
    {
        global $xoTheme, $xoopsModuleConfig;

        $fld = XOOPS_URL . '/modules/adslight/' . 'assets/';

        if (1 == $GLOBALS['xoopsModuleConfig']['adslight_lightbox']) {
            // $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/plugins/jquery.lightbox.js');
            // $xoTheme->addStyleSheet(XOOPS_URL . '/browse.php?Frameworks/jquery/plugins/jquery.lightbox.js');

            $xoTheme->addScript($fld . '/js/lightbox/js/lightbox.js');
            $xoTheme->addStyleSheet($fld . '/js/lightbox/css/lightbox.css');
        } else {
            //$xoTheme->addStyleSheet($fld . "/css/galery.css" type="text/css" media="screen");

        }
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
     * @param float $number
     * @param string $currency The 3-letter ISO 4217 currency code indicating the currency to use.
     * @param string $localeCode (local language code, e.g. en_US)
     * @return string formatted currency value
     */
    public static function formatCurrency($number, $currency='USD', $localeCode='')
    {
        $localeCode?? locale_get_default();
        $fmt = new \NumberFormatter( $localeCode, \NumberFormatter::CURRENCY );
        return $fmt->formatCurrency($number, $currency);;
    }

    /**
     * Currency Format (temporary)
     *
     * @param float $number
     * @param string $currency The 3-letter ISO 4217 currency code indicating the currency to use.
     * @param string $currencySymbol
     * @param int $currencyPosition
     * @return string formatted currency value
     */
    public static function formatCurrencyTemp($number, $currency='USD', $currencySymbol='$', $currencyPosition=0)
    {
        $currentDefault = locale_get_default();
        $fmt = new \NumberFormatter( $currentDefault, \NumberFormatter::DECIMAL  );
        $formattedNumber =  $fmt->format($number);
        return 1 === $currencyPosition ? $currencySymbol . $formattedNumber : $formattedNumber . ' ' . $currencySymbol;
    }
}
