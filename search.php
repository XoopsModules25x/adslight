<?php
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

use Xmf\Request;
use XoopsModules\Adslight;

$moduleDirName = basename(__DIR__);
//@todo replace the following code - use Filters
foreach ($_REQUEST as $key => $val) {
    $val            = preg_replace("/[^_A-Za-z0-9-\.&=]/i", '', $val);
    $_REQUEST[$key] = $val;
}

$xoopsOption['pagetype'] = 'search';

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xmid = $xoopsModule->getVar('mid');
/** @var XoopsConfigHandler $configHandler */
$configHandler     = xoops_getHandler('config');
$xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);

if (1 != $xoopsConfigSearch['enable_search']) {
    //    header("Location: '.XOOPS_URL.'modules/adslight/index.php");
    redirect_header('index.php', 1);
}

$action = Request::getString('action', 'search');
$query  = Request::getString('query', '');
$andor  = Request::getString('andor', 'AND');
$mid    = Request::getInt('mid', 0);
$uid    = Request::getInt('uid', 0);
$start  = Request::getInt('start', 0);

$queries = [];

if ('results' === $action) {
    if ('' === $query) {
        redirect_header('search.php', 1, _SR_PLZENTER);
    }
} elseif ('showall' === $action) {
    if ('' === $query || empty($mid)) {
        redirect_header('search.php', 1, _SR_PLZENTER);
    }
} elseif ('showallbyuser' === $action) {
    if (empty($mid) || empty($uid)) {
        redirect_header('search.php', 1, _SR_PLZENTER);
    }
}

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
/** @var XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler      = xoops_getHandler('groupperm');
$available_modules = $grouppermHandler->getItemIds('module_read', $groups);

if ('search' === $action) {
    include XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/include/searchform.php';
    $search_form->display();
    include XOOPS_ROOT_PATH . '/footer.php';
    exit();
}

if ('OR' !== $andor && 'exact' !== $andor && 'AND' !== $andor) {
    $andor = 'AND';
}

$myts = \MyTextSanitizer::getInstance();
if ('showallbyuser' !== $action) {
    if ('exact' !== $andor) {
        $ignored_queries = []; // holds keywords that are shorter than allowed mininum length
        $temp_queries    = preg_split('/[\s,]+/', $query);
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (strlen($q) >= $xoopsConfigSearch['keyword_min']) {
                $queries[] = $myts->addSlashes($q);
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (0 == count($queries)) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
        }
    } else {
        $query = trim($query);
        if (strlen($query) < $xoopsConfigSearch['keyword_min']) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
        }
        $queries = [$myts->addSlashes($query)];
    }
}
switch ($action) {
    case 'results':
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $criteria      = new \CriteriaCompo(new \Criteria('hassearch', 1));
        $criteria->add(new \Criteria('isactive', 1));
        $criteria->add(new \Criteria('mid', '(' . implode(',', $available_modules) . ')', 'IN'));
        $modules = $moduleHandler->getObjects($criteria, true);
        $mids    = Request::getArray('mids', []);
        if (empty($mids) || !is_array($mids)) {
            unset($mids);
            $mids = array_keys($xmid);
        }
        include XOOPS_ROOT_PATH . '/header.php';

        // for xoops 2.2.x versions
        xoops_loadLanguage('main', $moduleDirName);
        // end

        echo '<h3>' . _ADSLIGHT_SEARCHRESULTS . "</h3>\n";
        echo _SR_KEYWORDS . ':';
        if ('exact' !== $andor) {
            foreach ($queries as $q) {
                echo ' <strong>' . htmlspecialchars(stripslashes($q), ENT_QUOTES | ENT_HTML5) . '</strong>';
            }
            if (!empty($ignored_queries)) {
                echo '<br>';
                printf(_SR_IGNOREDWORDS, $xoopsConfigSearch['keyword_min']);
                foreach ($ignored_queries as $q) {
                    echo ' <strong>' . htmlspecialchars(stripslashes($q), ENT_QUOTES | ENT_HTML5) . '</strong>';
                }
            }
        } else {
            echo ' "<strong>' . htmlspecialchars(stripslashes($queries[0]), ENT_QUOTES | ENT_HTML5) . '</strong>"';
        }
        echo '<br>';
        foreach ($mids as $mid) {
            $mid = (int)$mid;
            if (in_array($mid, $available_modules)) {
                $module  = $modules[$mid];
                $results = $module->search($queries, $andor, 5, 0);
                $count   = 0;
                if (is_array($results)) {
                    $count = count($results);
                }
                if (!is_array($results) || 0 == $count) {
                    echo '<p>' . _SR_NOMATCH . '</p>';
                } else {
                    for ($i = 0; $i < $count; ++$i) {
                        echo '<style type="text/css" media="all">@import url(' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css);</style>';
                        echo '<table width="100%" class="outer"><tr>';
                        echo '<td width="30%">';
                        echo '<strong>' . $myts->htmlSpecialChars($results[$i]['type']) . '</strong><br>';
                        if (isset($results[$i]['photo'])
                            && '' !== $results[$i]['photo']) {
                            echo "<a href='" . $results[$i]['link'] . "'><img class='thumb' src='" . $results[$i]['sphoto'] . "' alt='' width='100' ></a></td>&nbsp;";
                        } else {
                            echo "<a href='" . $results[$i]['link'] . "'><img class='thumb' src='" . $results[$i]['nophoto'] . "' alt='' width='100' ></a></td>&nbsp;";
                        }
                        if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                            $results[$i]['link'] = '' . $results[$i]['link'];
                        }
                        echo '<td width="50%">';

                        echo "<strong><a href='" . $results[$i]['link'] . "'>" . $myts->htmlSpecialChars($results[$i]['title']) . '</a></strong><br><br>';

                        if (!XOOPS_USE_MULTIBYTES) {
                            if (strlen($results[$i]['desctext']) >= 14) {
                                $results[$i]['desctext'] = $myts->displayTarea(substr($results[$i]['desctext'], 0, 90), 1, 1, 1, 1, 1) . '';
                            }
                        }

                        echo '' . $myts->displayTarea($results[$i]['desctext'], 1, 1, 1, 1, 1) . '';

                        echo '</td><td width="20%">';
                        echo '' . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . '' . $myts->htmlSpecialChars($results[$i]['price']) . '</a>&nbsp;' . $myts->htmlSpecialChars($results[$i]['typeprice']) . '</a>';

                        echo '</td></tr><tr><td>';
                        echo '<small>';
                        $results[$i]['uid'] = @(int)$results[$i]['uid'];
                        if (!empty($results[$i]['uid'])) {
                            $uname = \XoopsUser::getUnameFromId($results[$i]['uid']);
                            echo '&nbsp;&nbsp;' . _ADSLIGHT_FROM . "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $results[$i]['uid'] . "'>" . $uname . "</a>\n";
                        }
                        echo !empty($results[$i]['time']) ? ' (' . formatTimestamp((int)$results[$i]['time']) . ')' : '';
                        echo '</small>';
                        echo '</td></tr></table><table>';
                    }
                    if ($count >= 5) {
                        $search_url = XOOPS_URL . '/modules/adslight/search.php?query=' . urlencode(stripslashes(implode(' ', $queries)));
                        $search_url .= "&mid=$mid&action=showall&andor=$andor";
                        echo '<br><a href="' . htmlspecialchars($search_url, ENT_QUOTES | ENT_HTML5) . '">' . _SR_SHOWALLR . '</a>';
                    }
                    echo '<table>';
                }
            }
            unset($results, $module);
        }
        require_once __DIR__ . '/include/searchform.php';
        $search_form->display();
        break;
    case 'showall':
    case 'showallbyuser':

        include XOOPS_ROOT_PATH . '/header.php';

        /** @var Adslight\Helper $helper */
        $helper = Adslight\Helper::getInstance();
        $helper->loadLanguage('admin');

        $GLOBALS['xoopsTpl']->assign('imgscss', XOOPS_URL . '/modules/adslight/assets/css/adslight.css');
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->get($mid);
        $results       =& $module->search($queries, $andor, 20, $start, $uid);
        $count         = 0;
        if (is_array($results)) {
            $count = count($results);
        }
        if ($count > 0) {
            $next_results =& $module->search($queries, $andor, 1, $start + 20, $uid);
            $count        = 0;
            if (is_array($next_results)) {
                $count = count($next_results);
            }
            $has_next = false;
            if (is_array($next_results) && 1 == $next_count) {
                $has_next = true;
            }
            echo '<h4>' . _ADSLIGHT_SEARCHRESULTS . "</h4>\n";
            if ('showall' === $action) {
                echo _SR_KEYWORDS . ':';
                if ('exact' !== $andor) {
                    foreach ($queries as $q) {
                        echo ' <strong>' . htmlspecialchars(stripslashes($q), ENT_QUOTES | ENT_HTML5) . '</strong>';
                    }
                } else {
                    echo ' "<strong>' . htmlspecialchars(stripslashes($queries[0]), ENT_QUOTES | ENT_HTML5) . '</strong>"';
                }
                echo '<br><br>';
            }
            //    printf(_SR_FOUND,$count);
            //    echo "<br>";
            printf(_SR_SHOWING, $start + 1, $start + $count);
            for ($i = 0; $i < $count; ++$i) {
                echo '<table width="100%" class="outer"><tr>';
                echo '<td width="30%">';
                echo '<strong>' . $myts->htmlSpecialChars($results[$i]['type']) . '</strong><br>';
                if (isset($results[$i]['photo'])
                    && '' !== $results[$i]['photo']) {
                    echo "<a href='" . $results[$i]['link'] . "'><img class='thumb' src='" . $results[$i]['sphoto'] . "' alt='' width='100' ></a></td>&nbsp;";
                } else {
                    echo "<a href='" . $results[$i]['link'] . "'><img class='thumb' src='" . $results[$i]['nophoto'] . "' alt='' width='100' ></a></td>&nbsp;";
                }
                if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                    $results[$i]['link'] = '' . $results[$i]['link'];
                }
                echo '<td width="50%">';

                echo "<strong><a href='" . $results[$i]['link'] . "'>" . $myts->htmlSpecialChars($results[$i]['title']) . '</a></strong><br><br>';

                if (!XOOPS_USE_MULTIBYTES) {
                    if (strlen($results[$i]['desctext']) >= 14) {
                        $results[$i]['desctext'] = substr($results[$i]['desctext'], 0, 90) . '...';
                    }
                }

                echo '' . $myts->htmlSpecialChars($results[$i]['desctext']) . '';

                echo '</td><td width="20%">';
                echo '' . $GLOBALS['xoopsModuleConfig']['adslight_currency_symbol'] . '
' . $myts->htmlSpecialChars($results[$i]['price']) . '</a>&nbsp;' . $myts->htmlSpecialChars($results[$i]['typeprice']) . '</a>';

                echo '</td></tr><tr><td>';
                echo '<small>';
                $results[$i]['uid'] = @(int)$results[$i]['uid'];
                if (!empty($results[$i]['uid'])) {
                    $uname = \XoopsUser::getUnameFromId($results[$i]['uid']);
                    echo '&nbsp;&nbsp;' . _ADSLIGHT_FROM . "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $results[$i]['uid'] . "'>" . $uname . '</a><br>';
                }
                echo !empty($results[$i]['time']) ? ' (' . formatTimestamp((int)$results[$i]['time']) . ')' : '';
                echo '</small>';
                echo '</td></tr></table><table>';
            }

            echo '
        <table>
          <tr>
        ';
            $search_url = XOOPS_URL . '/modules/adslight/search.php?query=' . urlencode(stripslashes(implode(' ', $queries)));
            $search_url .= "&mid=$mid&action=$action&andor=$andor";
            if ('showallbyuser' === $action) {
                $search_url .= "&uid=$uid";
            }
            if ($start > 0) {
                $prev = $start - 20;
                echo '<td align="left">
            ';
                $search_url_prev = $search_url . "&start=$prev";
                echo '<a href="' . htmlspecialchars($search_url_prev, ENT_QUOTES | ENT_HTML5) . '">' . _SR_PREVIOUS . '</a></td>
            ';
            }
            echo '<td>&nbsp;&nbsp;</td>
        ';
            if (false !== $has_next) {
                $next            = $start + 20;
                $search_url_next = $search_url . "&start=$next";
                echo '<td align="right"><a href="' . htmlspecialchars($search_url_next, ENT_QUOTES | ENT_HTML5) . '">' . _SR_NEXT . '</a></td>
            ';
            }
            echo '
          </tr>
        </table>
        <p>
        ';
        } else {
            echo '<p>' . _SR_NOMATCH . '</p>';
        }
        require_once __DIR__ . '/include/searchform.php';
        $search_form->display();
        echo '</p>
    ';
        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
