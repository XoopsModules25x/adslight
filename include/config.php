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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$moduleDirName = basename(dirname(__DIR__));
$capsDirName   = strtoupper($moduleDirName);

if (!defined($capsDirName . '_DIRNAME')) {
    define($capsDirName . '_DIRNAME', $moduleDirName);
    define($capsDirName . '_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($capsDirName . '_DIRNAME'));
    define($capsDirName . '_URL', XOOPS_URL . '/modules/' . constant($capsDirName . '_DIRNAME'));
    define($capsDirName . '_ADMIN', constant($capsDirName . '_URL') . '/admin/index.php');
    define($capsDirName . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($capsDirName . '_DIRNAME'));
    define($capsDirName . '_AUTHOR_LOGOIMG', constant($capsDirName . '_URL') . '/assets/images/logoModule.png');
}

// Define here the place where main upload path

//$img_dir = $GLOBALS['xoopsModuleConfig']['uploaddir'];

define($capsDirName . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
//define("ADSLIGHT_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
define($capsDirName . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash

//Configurator
/*
return array(
    'name'          => 'Module Configurator',
    'uploadFolders' => array(
        constant($capsDirName . '_UPLOAD_PATH'),
        constant($capsDirName . '_UPLOAD_PATH') . '/midsize',
        constant($capsDirName . '_UPLOAD_PATH') . '/thumbs',
    ),
    'copyFiles'     => array(
        constant($capsDirName . '_UPLOAD_PATH') . '/midsize',
        constant($capsDirName . '_UPLOAD_PATH') . '/thumbs',
    ),

    'templateFolders' => array(
        '/templates/',
        '/templates/blocks/',
        '/templates/admin/'

    ),
    'oldFiles'        => array(
        '/admin/admin.css',
        '/class/utilities.php',
    ),
    'oldFolders'      => array(
        '/images',
        '/style',
    ),
);
*/

/**
 * Class AdsligthConfigurator
 */
class AdsligthConfigurator
{
    public $uploadFolders   = array();
    public $blankFiles  = array();
    public $templateFolders = array();
    public $oldFiles        = array();
    public $oldFolders      = array();
    public $name;

    /**
     * AdsligthConfigurator constructor.
     */
    public function __construct()
    {
        $moduleDirName        = basename(dirname(__DIR__));
        $capsDirName          = strtoupper($moduleDirName);
        $this->name           = 'Module Configurator';
        $this->uploadFolders  = array(
            constant($capsDirName . '_UPLOAD_PATH'),
            constant($capsDirName . '_UPLOAD_PATH') . '/midsize',
            constant($capsDirName . '_UPLOAD_PATH') . '/thumbs',
        );
        $this->blankFiles = array(
            constant($capsDirName . '_UPLOAD_PATH'),
            constant($capsDirName . '_UPLOAD_PATH') . '/midsize',
            constant($capsDirName . '_UPLOAD_PATH') . '/thumbs',
        );

        $this->templateFolders = array(
            '/templates/',
            '/templates/blocks/',
            '/templates/admin/'

        );
        $this->oldFiles        = array(
            '/admin/admin.css',
            '/class/utilities.php',
        );
        $this->oldFolders      = array(
            '/images',
            '/style',
        );
    }
}

// module information
$modCopyright = "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . constant($capsDirName . '_AUTHOR_LOGOIMG') . "' alt='XOOPS Project' ></a>";
