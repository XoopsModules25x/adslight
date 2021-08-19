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

/////////////////////////////////////
// AdsLight UrlRewrite By Nikita   //
// http://www.aideordi.com         //
/////////////////////////////////////

use Xmf\Request;

$seoOp    = Request::getString('seoOp', '', 'GET');
$seoArg   = Request::getString('seoArg', '', 'GET');
$seoOther = Request::getString('seoOther', '', 'GET');

if (!empty($seoOther)) {
    $seoOther = explode('/', $seoOther);
}

$seoMap = [
    'c' => 'viewcats.php',
    'p' => 'viewads.php',
    //  'addlisting' => 'addlisting.php'
];

if (!empty($seoOp) && !empty($seoMap[$seoOp])) {
    // module specific dispatching logic, other module must implement as
    // per their requirements.
    $newUrl = '/modules/adslight/' . $seoMap[$seoOp];

    // if your site is in a folder.  ex: www.welcome.com/xoops_site/
    // Replace the line above, for it
    // $newUrl = '/yourfile/modules/adslight/' . $seoMap[$seoOp];
    $_ENV['SCRIPT_NAME']    = $newUrl;
    $_SERVER['SCRIPT_NAME'] = $newUrl;
    $_SERVER['SCRIPT_NAME'] = $newUrl;
    switch ($seoOp) {
        case 'c':
            $_SERVER['REQUEST_URI'] = $newUrl . '?cid=' . $seoArg;
            $_GET['cid']            = $seoArg;
            break;
        case 'p':
            $_SERVER['REQUEST_URI'] = $newUrl . '?lid=' . $seoArg;
            $_GET['lid']            = $seoArg;
            break;
    }

    require_once $seoMap[$seoOp];
}

exit;
