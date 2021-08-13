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

require_once __DIR__ . '/header.php';

if (null !== Request::getInt('cod_img', null, 'GET')) {
    $cod_img = Request::getInt('cod_img', null, 'GET');
} else {
    redirect_header('index.php', 1, _ADSLIGHT_VALIDATE_FAILED);
}
xoops_header();

global $xoopsConfig, $xoopsTheme, $xoopsDB, $xoops_footer, $xoopsLogger;
$currenttheme = getTheme();

$result      = $xoopsDB->query('SELECT url FROM ' . $xoopsDB->prefix('adslight_picture') . " WHERE cod_img={$cod_img}");
$recordexist = $xoopsDB->getRowsNum($result);

if ($recordexist) {
    [$url] = $xoopsDB->fetchRow($result);
    echo "<br><br><div style='text-align:center'><img class=\"thumb\" src=\"photo/{$url}\" border=0></div>";
}

echo "<table><tr><td><div style='text-align:center'><a href=#  onClick='window.close()'>" . _ADSLIGHT_CLOSEF . '</a></div></td></tr></table>';

xoops_footer();
