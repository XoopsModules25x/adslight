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
 * @copyrightXOOPS Project (https://xoops.org)
 * @license        GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author         XOOPS Development Team
 */

use Xmf\Module\Admin;

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
xoops_loadLanguage('common', $moduleDirName);
return (object)[
    'name'            => $moduleDirNameUpper . ' Module Configurator',
    'paths'           => [
        'dirname'    => $moduleDirName,
        'admin'      => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/admin',
        'modPath'    => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName,
        'modUrl'     => XOOPS_URL . '/modules/' . $moduleDirName,
        'uploadPath' => XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        'uploadUrl'  => XOOPS_UPLOAD_URL . '/' . $moduleDirName,
    ],
    'uploadFolders'   => [
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/attachments',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/category',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/midsize',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/thumbs',
        //XOOPS_UPLOAD_PATH . '/flags'
    ],
    'copyBlankFiles'  => [
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/attachments',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/category',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/midsize',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/thumbs',
        //XOOPS_UPLOAD_PATH . '/flags'
    ],
    'copyTestFolders' => [
        [
            XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/testdata/uploads',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        ],
        //[
        //XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/testdata/thumbs',
        //XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/thumbs',
        //],
    ],

    'templateFolders' => [
        '/templates/',
        //'/templates/blocks/',
        //'/templates/admin/'
    ],
    'oldFiles'        => [
        '/class/request.php',
        '/class/registry.php',
        '/class/utilities.php',
        '/class/util.php',
        '/class/ClassifiedObjectTree.php',
        '/class/ClassifiedsTree.php',
        '/ajaxrating.txt',
        '/admin/index.html',
        '/assets/css/index.html',
        '/assets/images/bubble/index.html',
        '/assets/images/deco/index.html',
        '/assets/images/icons/16/index.html',
        '/assets/images/icons/32/index.html',
        '/assets/images/icons/index.html',
        '/assets/images/img_cat/index.html',
        '/assets/images/users/index.html',
        '/assets/images/index.html',
        '/assets/js/lightbox/css/index.html',
        '/assets/js/lightbox/images/index.html',
        '/assets/js/lightbox/js/index.html',
        '/assets/js/lightbox/index.html',
        '/assets/js/index.html',
        '/assets/index.html',
        '/blocks/index.html',
        '/class/Common/index.html',
        '/class/index.html',
        '/docs/index.html',
        '/include/index.html',
        '/language/english/help/index.html',
        '/language/english/mail_template/index.html',
        '/language/english/index.html',
        '/language/index.html',
        '/maps/french/images/index.html',
        '/maps/french/index.html',
        '/maps/russian/images/index.html',
        '/maps/russian/index.html',
        '/maps/index.html',
        '/preloads/index.html',
        '/sql/bosanski/index.html',
        '/sql/english/index.html',
        '/sql/french/index.html',
        '/sql/german/index.html',
        '/sql/italian/index.html',
        '/sql/nederlands/index.html',
        '/sql/polish/index.html',
        '/sql/portuguesebr/index.html',
        '/sql/spanish/index.html',
        '/sql/index.html',
        '/templates/blocks/index.html',
        '/templates/index.html',
    ],
    'oldFolders'      => [
        '/images',
        '/css',
        '/js',
        '/tcpdf',
    ],
    'renameTables'    => [
        'adslight_usure' => 'adslight_condition',
    ],
    'renameColumns'   => [
//        ['tablename' => 'adslight_listing', 'from' => 'typeusure', 'to' => 'typecondition'],
//        ['tablename' => 'adslight_listing', 'from' => 'date', 'to' => 'date_created'],
//        ['tablename' => 'adslight_condition', 'from' => 'id_usure', 'to' => 'id_condition'],
//        ['tablename' => 'adslight_condition', 'from' => 'nom_usure', 'to' => 'nom_condition'],
//        ['tablename' => 'adslight_ip_log', 'from' => 'date', 'to' => 'date_created'],
//        ['tablename' => 'adslight_item_votedata', 'from' => 'ratingtimestamp', 'to' => 'date_created'],
//        ['tablename' => 'adslight_pictures', 'from' => 'date_added', 'to' => 'date_created'],
//        ['tablename' => 'adslight_pictures', 'from' => 'date_modified', 'to' => 'date_updated'],
//        ['tablename' => 'adslight_replies', 'from' => 'date', 'to' => 'date_created'],
    ],
    'moduleStats'     => [
        //'totalcategories' => $helper->getHandler('Categories')->getCategoriesCount(-1),
        //'totalitems'  => $helper->getHandler('Item')->getItemsCount(),
        //'totalsubmitted'  => $helper->getHandler('Item')->getItemsCount(-1, [Constants::PUBLISHER_STATUS_SUBMITTED]),
    ],
    'modCopyright'    => "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
  <img src='" . Admin::iconUrl('xoopsmicrobutton.gif') . "' alt='XOOPS Project'></a>",
];
