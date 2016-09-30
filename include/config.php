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
 * @copyright    XOOPS Project http://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$moduleDirName = basename(dirname(__DIR__));

$modir   = strtoupper($moduleDirName);

if (!defined($modir . '_DIRNAME')) {
    define($modir . '_DIRNAME', $moduleDirName);
    define($modir . '_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($modir . '_DIRNAME'));
    define($modir . '_URL', XOOPS_URL . '/modules/' . constant($modir . '_DIRNAME'));
    define($modir . '_ADMIN', constant($modir . '_URL') . '/admin/index.php');
    define($modir . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($modir . '_DIRNAME'));
    define($modir . '_AUTHOR_LOGOIMG', constant($modir . '_URL') . '/assets/images/logoModule.png');
}

// Define here the place where main upload path

//$img_dir = $GLOBALS['xoopsModuleConfig']['uploaddir'];

define($modir . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . constant($modir . '_DIRNAME')); // WITHOUT Trailing slash
//define("ADSLIGHT_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
define($modir . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . constant($modir . '_DIRNAME')); // WITHOUT Trailing slash

//Configurator
return array(
    'name'          => 'Module Configurator',
    'uploadFolders' => array(
        constant($modir . '_UPLOAD_PATH'),
        constant($modir . '_UPLOAD_PATH') . '/midsize',
        constant($modir . '_UPLOAD_PATH') . '/thumbs',
    ),
    'copyFiles'     => array(
        constant($modir . '_UPLOAD_PATH') . '/midsize',
        constant($modir . '_UPLOAD_PATH') . '/thumbs',
    ),

    'templateFolders' => array(
        '/templates/',
        '/templates/blocks/',
        '/templates/admin/'

    ),
    'oldFiles'        => array(
        '/admin/admin.css',
    ),
    'oldFolders'        => array(
        '/images',
        '/style',
    ),
);


// module information
$mod_copyright  = "<a href='http://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . constant($modir . '_AUTHOR_LOGOIMG') . "' alt='XOOPS Project' /></a>";
