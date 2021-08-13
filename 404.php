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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

if (file_exists(__DIR__ . '/mainfile.php')) {
    require_once __DIR__ . '/mainfile.php';
} elseif (file_exists(__DIR__ . '/../mainfile.php')) {
    require_once \dirname(__DIR__) . '/mainfile.php';
} else {
    require_once \dirname(__DIR__, 2) . '/mainfile.php';
}
require_once XOOPS_ROOT_PATH . '/header.php';

$GLOBALS['xoopsTpl']->assign('xoops_showrblock', 1); // 1 = Avec blocs de droite - 0 = Sans blocs de droite
$GLOBALS['xoopsTpl']->assign('xoops_showlblock', 1); // 1 = Avec blocs de gauche - 0 = Sans blocs de gauche
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', _MN_ADSLIGHT_ERROR404);
$GLOBALS['xoTheme']->addMeta('meta', 'robots', 'noindex, nofollow');

echo _MN_ADSLIGHT_ERROR404_TEXT;

require_once XOOPS_ROOT_PATH . '/footer.php';
