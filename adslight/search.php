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

foreach ($_REQUEST as $key => $val) 
{
  $val = preg_replace("/[^_A-Za-z0-9-\.&=]/i",'', $val);
  $_REQUEST[$key] = $val;
}  

$xoopsOption['pagetype'] = "search";

include '../../mainfile.php';

$xmid = $xoopsModule->getVar('mid');
$config_handler =& xoops_gethandler('config');
$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);

if ($xoopsConfigSearch['enable_search'] != 1) {
    header("Location: '.XOOPS_URL.'modules/adslight/index.php");
    exit();
}
$action = "search";
if (!empty($_GET['action'])) {
  $action = $_GET['action'];
} elseif (!empty($_POST['action'])) {
  $action = $_POST['action'];
}
$query = "";
if (!empty($_GET['query'])) {
  $query = $_GET['query'];
} elseif (!empty($_POST['query'])) {
  $query = $_POST['query'];
}
$andor = "AND";
if (!empty($_GET['andor'])) {
  $andor = $_GET['andor'];
} elseif (!empty($_POST['andor'])) {
  $andor = $_POST['andor'];
}
$mid = $uid = $start = 0;
if ( !empty($_GET['mid']) ) {
  $mid = intval($_GET['mid']);
} elseif ( !empty($_POST['mid']) ) {
  $mid = intval($_POST['mid']);
}
if (!empty($_GET['uid'])) {
  $uid = intval($_GET['uid']);
} elseif (!empty($_POST['uid'])) {
  $uid = intval($_POST['uid']);
}
if (!empty($_GET['start'])) {
  $start = intval($_GET['start']);
} elseif (!empty($_POST['start'])) {
  $start = intval($_POST['start']);
}
$queries = array();

if ($action == "results") {
    if ($query == "") {
         redirect_header("search.php",1,_SR_PLZENTER);
        exit();
    }
} elseif ($action == "showall") {
    if ($query == "" || empty($mid)) {
        redirect_header("search.php",1,_SR_PLZENTER);
        exit();
    }
} elseif ($action == "showallbyuser") {
    if (empty($mid) || empty($uid)) {
        redirect_header("search.php",1,_SR_PLZENTER);
        exit();
    }
}

$groups = is_object($xoopsUser) ? $xoopsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler = & xoops_gethandler( 'groupperm' );
$available_modules = $gperm_handler->getItemIds('module_read', $groups);

if ($action == 'search') {
    include XOOPS_ROOT_PATH.'/header.php';
    include 'include/searchform.php';
    $search_form->display();
    include XOOPS_ROOT_PATH.'/footer.php';
    exit();
}

if ( $andor != "OR" && $andor != "exact" && $andor != "AND" ) {
    $andor = "AND";
}

$myts =& MyTextSanitizer::getInstance();
if ($action != 'showallbyuser') {
    if ( $andor != "exact" ) {
        $ignored_queries = array(); // holds keywords that are shorter than allowed mininum length
        $temp_queries = preg_split('/[\s,]+/', $query);
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (strlen($q) >= $xoopsConfigSearch['keyword_min']) {
                $queries[] = $myts->addSlashes($q);
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (count($queries) == 0) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
            exit();
        }
    } else {
        $query = trim($query);
        if (strlen($query) < $xoopsConfigSearch['keyword_min']) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
            exit();
        }
        $queries = array($myts->addSlashes($query));
    }
}
switch ($action) {
    case "results":
    $module_handler =& xoops_gethandler('module');
    $criteria = new CriteriaCompo(new Criteria('hassearch', 1));
    $criteria->add(new Criteria('isactive', 1));
    $criteria->add(new Criteria('mid', "(".implode(',', $available_modules).")", 'IN'));
    $modules =& $module_handler->getObjects($criteria, true);
    $mids = isset($_REQUEST['mids']) ? $_REQUEST['mids'] : array();
    if (empty($mids) || !is_array($mids)) {
        unset($mids);
        $mids = array_keys($xmid);
    }
    include XOOPS_ROOT_PATH."/header.php";


// for xoops 2.2.x versions
if ( file_exists("language/".$xoopsConfig['language']."/main.php") ) {
include_once("language/".$xoopsConfig['language']."/main.php");
} else {
include_once("language/english/main.php");
}
// end

    echo "<h3>"._ADSLIGHT_SEARCHRESULTS."</h3>\n";
    echo _SR_KEYWORDS.':';
    if ($andor != 'exact') {
        foreach ($queries as $q) {
            echo ' <strong>'.htmlspecialchars(stripslashes($q)).'</strong>';
        }
        if (!empty($ignored_queries)) {
            echo '<br />';
            printf(_SR_IGNOREDWORDS, $xoopsConfigSearch['keyword_min']);
            foreach ($ignored_queries as $q) {
                echo ' <strong>'.htmlspecialchars(stripslashes($q)).'</strong>';
            }
        }
    } else {
        echo ' "<strong>'.htmlspecialchars(stripslashes($queries[0])).'</strong>"';
    }
    echo '<br />';
    foreach ($mids as $mid) {
        $mid = intval($mid);
        if ( in_array($mid, $available_modules) ) {
            $module =& $modules[$mid];
            $results =& $module->search($queries, $andor, 5, 0);
            $count = count($results);
            if (!is_array($results) || $count == 0) {
                echo "<p>"._SR_NOMATCH."</p>";
            } else {
                for ($i = 0; $i < $count; $i++) {
	
		echo "<style type=\"text/css\" media=\"all\">@import url(".XOOPS_URL."/modules/adslight/style/adslight.css);</style>";
		echo "<table width=\"100%\" class=\"outer\"><tr>";
		echo "<td width=\"30%\">";echo "<strong>".$myts->htmlSpecialChars($results[$i]['type'])."</strong><br />";
                    if (isset($results[$i]['photo']) && $results[$i]['photo'] != "") {
                        echo "<a href='".$results[$i]['link']."'><img class='thumb' src='".$results[$i]['sphoto']."' alt='' width='100' /></a></td>&nbsp;";
                    } else {
                        echo "<a href='".$results[$i]['link']."'><img class='thumb' src='".$results[$i]['nophoto']."' alt='' width='100' /></a></td>&nbsp;";
                    }
                    if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                        $results[$i]['link'] = "".$results[$i]['link'];
                    }
                    echo "<td width=\"50%\">";


echo "<strong><a href='".$results[$i]['link']."'>".$myts->htmlSpecialChars($results[$i]['title'])."</a></strong><br /><br />";

if ( !XOOPS_USE_MULTIBYTES ) {
			if (strlen($results[$i]['desctext']) >= 14) {
				$results[$i]['desctext'] = $myts->displayTarea(substr($results[$i]['desctext'],0,90), 1, 1, 1, 1, 1)."";
			}
		}

echo "".$myts->displayTarea($results[$i]['desctext'], 1, 1, 1, 1, 1)."";
 

echo "</td><td width=\"20%\">";
	echo "".$xoopsModuleConfig["adslight_money"]."
".$myts->htmlSpecialChars($results[$i]['price'])."</a>&nbsp;".$myts->htmlSpecialChars($results[$i]['typeprice'])."</a>";
	
 echo "</td></tr><tr><td>";
                    echo "<small>";
                    $results[$i]['uid'] = @intval($results[$i]['uid']);
                    if ( !empty($results[$i]['uid']) ) {
                        $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
                        echo "&nbsp;&nbsp;"._ADSLIGHT_FROM."<a href='".XOOPS_URL."/userinfo.php?uid=".$results[$i]['uid']."'>".$uname."</a>\n";
                    }
                    echo !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
                    echo "</small>";
		   echo "</td></tr></table><table>";
                }
                if ( $count >= 5 ) {
                    $search_url = XOOPS_URL."/modules/adslight/search.php?query=".urlencode(stripslashes(implode(' ', $queries)));
                    $search_url .= "&mid=$mid&action=showall&andor=$andor";
                    echo '<br /><a href="'.htmlspecialchars($search_url).'">'._SR_SHOWALLR.'</a>';
                }
		 echo"<table>";
            }
        }
        unset($results);
        unset($module);
    }
    include "include/searchform.php";
    $search_form->display();
    break;
    case "showall":
    case 'showallbyuser':
  
    include XOOPS_ROOT_PATH."/header.php";

// for xoops 2.2.x versions
if ( file_exists("language/".$xoopsConfig['language']."/main.php") ) {
include_once("language/".$xoopsConfig['language']."/main.php");
} else {
include_once("language/english/main.php");
}
// end
  $xoopsTpl->assign('imgscss', XOOPS_URL."/modules/adslight/style/adslight.css");
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->get($mid);
    $results =& $module->search($queries, $andor, 20, $start, $uid);
    $count = count($results);
    if (is_array($results) && $count > 0) {
        $next_results =& $module->search($queries, $andor, 1, $start + 20, $uid);
        $next_count = count($next_results);
        $has_next = false;
        if (is_array($next_results) && $next_count == 1) {
            $has_next = true;
        }
        echo "<h4>"._ADSLIGHT_SEARCHRESULTS."</h4>\n";
        if ($action == 'showall') {
            echo _SR_KEYWORDS.':';
            if ($andor != 'exact') {
                foreach ($queries as $q) {
                    echo ' <strong>'.htmlspecialchars(stripslashes($q)).'</strong>';
                }
            } else {
                echo ' "<strong>'.htmlspecialchars(stripslashes($queries[0])).'</strong>"';
            }
            echo '<br /><br />';
        }
        //    printf(_SR_FOUND,$count);
        //    echo "<br />";
        printf(_SR_SHOWING, $start+1, $start + $count);
        for ($i = 0; $i < $count; $i++) {
		echo "<table width=\"100%\" class=\"outer\"><tr>";
		echo "<td width=\"30%\">";echo "<strong>".$myts->htmlSpecialChars($results[$i]['type'])."</strong><br />";
                    if (isset($results[$i]['photo']) && $results[$i]['photo'] != "") {
                        echo "<a href='".$results[$i]['link']."'><img class='thumb' src='".$results[$i]['sphoto']."' alt='' width='100' /></a></td>&nbsp;";
                    } else {
                        echo "<a href='".$results[$i]['link']."'><img class='thumb' src='".$results[$i]['nophoto']."' alt='' width='100' /></a></td>&nbsp;";
                    }
                    if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                        $results[$i]['link'] = "".$results[$i]['link'];
                    }
                    echo "<td width=\"50%\">";


echo "<strong><a href='".$results[$i]['link']."'>".$myts->htmlSpecialChars($results[$i]['title'])."</a></strong><br /><br />";

if ( !XOOPS_USE_MULTIBYTES ) {
			if (strlen($results[$i]['desctext']) >= 14) {
				$results[$i]['desctext'] = substr($results[$i]['desctext'],0,90)."...";
			}
		}

echo "".$myts->htmlSpecialChars($results[$i]['desctext'])."";
 

echo "</td><td width=\"20%\">";
	echo "".$xoopsModuleConfig["adslight_money"]."
".$myts->htmlSpecialChars($results[$i]['price'])."</a>&nbsp;".$myts->htmlSpecialChars($results[$i]['typeprice'])."</a>";
	
 echo "</td></tr><tr><td>";
                    echo "<small>";
                    $results[$i]['uid'] = @intval($results[$i]['uid']);
                    if ( !empty($results[$i]['uid']) ) {
                        $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
                        echo "&nbsp;&nbsp;"._ADSLIGHT_FROM."<a href='".XOOPS_URL."/userinfo.php?uid=".$results[$i]['uid']."'>".$uname."</a><br />";
                    }
                    echo !empty($results[$i]['time']) ? " (". formatTimestamp(intval($results[$i]['time'])).")" : "";
                    echo "</small>";
		   echo "</td></tr></table><table>";
                }
                
        echo '
        <table>
          <tr>
        ';
        $search_url = XOOPS_URL."/modules/adslight/search.php?query=".urlencode(stripslashes(implode(' ', $queries)));
        $search_url .= "&mid=$mid&action=$action&andor=$andor";
        if ($action=='showallbyuser') {
            $search_url .= "&uid=$uid";
        }
        if ( $start > 0 ) {
            $prev = $start - 20;
            echo '<td align="left">
            ';
            $search_url_prev = $search_url."&start=$prev";
            echo '<a href="'.htmlspecialchars($search_url_prev).'">'._SR_PREVIOUS.'</a></td>
            ';
        }
        echo '<td>&nbsp;&nbsp;</td>
        ';
        if (false != $has_next) {
            $next = $start + 20;
            $search_url_next = $search_url."&start=$next";
            echo '<td align="right"><a href="'.htmlspecialchars($search_url_next).'">'._SR_NEXT.'</a></td>
            ';
        }
        echo '
          </tr>
        </table>
        <p>
        ';
    } else {
        echo '<p>'._SR_NOMATCH.'</p>';
    }
    include "include/searchform.php";
    $search_form->display();
    echo '</p>
    ';
    break;
}
include XOOPS_ROOT_PATH."/footer.php";