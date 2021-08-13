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

header('Content-Type: application/rss+xml; charset=UTF-8');
require_once __DIR__ . '/header.php';
$xoopsLogger->activated = false;
$allads                 = Adslight\Utility::returnAllAdsFluxRss();
$base_xoops             = 'http://' . $_SERVER['SERVER_NAME'] . mb_substr($_SERVER['REQUEST_URI'], 0, mb_strpos($_SERVER['REQUEST_URI'], 'modules'));
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
<channel>
<title>{$xoopsConfig['sitename']}</title>
<link>{$base_xoops}</link>
<description>" . $xoopsConfig['slogan'] . '</description>
<language>fr</language>';
$adslink = 'http://' . $_SERVER['SERVER_NAME'] . mb_substr($_SERVER['REQUEST_URI'], 0, mb_strrpos($_SERVER['REQUEST_URI'], '/'));
foreach ($allads as $allad) {
    echo "<item>
    <title>{$allad['title']}</title>
    <description><![CDATA[" . stripslashes($allad['desctext']) . '<br><strong>Ville:</strong> ' . htmlspecialchars($allad['town'], ENT_QUOTES | ENT_HTML5) . ' - <strong>Prix:</strong> ' . htmlspecialchars($allad['price'], ENT_QUOTES | ENT_HTML5) . '&#8364; <br>';
    echo "]]></description>
    <link><![CDATA[{$adslink}/viewads.php?lid={$allad['lid']}]]></link>
    <guid><![CDATA[{$adslink}/viewads.php?lid={$allad['lid']}]]></guid>
    <pubDate>" . date('D, d M Y H:i:s +0100', $allad['date_created']) . '</pubDate>
    </item>';
}
echo '</channel>
</rss>';
