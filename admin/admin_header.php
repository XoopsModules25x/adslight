<?php
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
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 * @version    $Id $
 */

$roothpath = dirname(dirname(dirname(__DIR__)));
//$moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');
//$moduleDirName = $xoopsModule->getVar('dirname');

include_once $roothpath . '/mainfile.php';
include_once $roothpath . '/include/cp_functions.php';
require_once $roothpath . '/include/cp_header.php';
include_once $roothpath . "/class/xoopsformloader.php" ;

global $xoopsModule;
$moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');
//$moduleDirName2 = basename(dirname(__DIR__));

include_once $roothpath . "/modules/". $moduleDirName ."/include/gtickets.php";
include_once $roothpath . "/modules/". $moduleDirName ."/include/functions.php";
include_once $roothpath . "/modules/". $moduleDirName ."/class/classifiedstree.php";
//include_once $GLOBALS['xoops']->path( "/modules/adslight/class/grouppermform.php");
include_once $roothpath . '/class/xoopsform/grouppermform.php';
include_once $roothpath ."/modules/adslight/class/classifiedstree.php";

//if functions.php file exist
//require_once dirname(__DIR__) . '/include/functions.php';

// Load language files
xoops_loadLanguage('admin', $moduleDirName);
xoops_loadLanguage('modinfo', $moduleDirName);
xoops_loadLanguage('main', $moduleDirName);

$pathIcon16 = '../'.$xoopsModule->getInfo('icons16');
$pathIcon32 = '../'.$xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');

if ($xoopsUser) {
//	$xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
        redirect_header(XOOPS_URL."/",3,_NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL."/",3,_NOPERM);
    exit();
}

$myts =& MyTextSanitizer::getInstance();
