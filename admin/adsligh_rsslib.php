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

$RSS_Content = array();

/**
 * @param $item
 * @param $type
 *
 * @return array
 */
function RSS_Tags($item, $type)
{
    $y     = array();
    $tnl   = $item->getElementsByTagName('title');
    $tnl   = $tnl->item(0);
    $title = $tnl->firstChild->textContent;

    $tnl  = $item->getElementsByTagName('link');
    $tnl  = $tnl->item(0);
    $link = $tnl->firstChild->textContent;

    $tnl  = $item->getElementsByTagName('pubDate');
    $tnl  = $tnl->item(0);
    $date = $tnl->firstChild->textContent;

    $tnl         = $item->getElementsByTagName('description');
    $tnl         = $tnl->item(0);
    $description = $tnl->firstChild->textContent;

    $y['title']       = $title;
    $y['link']        = $link;
    $y['date']        = $date;
    $y['description'] = $description;
    $y['type']        = $type;

    return $y;
}

/**
 * @param $channel
 */
function RSS_Channel($channel)
{
    global $RSS_Content;

    $items = $channel->getElementsByTagName('item');

    // Processing channel

    $y = RSS_Tags($channel, 0);        // get description of channel, type 0
    array_push($RSS_Content, $y);

    // Processing articles

    foreach ($items as $item) {
        $y = RSS_Tags($item, 1);    // get description of article, type 1
        array_push($RSS_Content, $y);
    }
}

/**
 * @param $url
 */
function RSS_Retrieve($url)
{
    global $RSS_Content;

    $doc = new DOMDocument();
    $doc->load($url);

    $channels = $doc->getElementsByTagName('channel');

    $RSS_Content = array();

    foreach ($channels as $channel) {
        RSS_Channel($channel);
    }
}

/**
 * @param $url
 */
function RSS_RetrieveLinks($url)
{
    global $RSS_Content;

    $doc = new DOMDocument();
    $doc->load($url);

    $channels = $doc->getElementsByTagName('channel');

    $RSS_Content = array();

    foreach ($channels as $channel) {
        $items = $channel->getElementsByTagName('item');
        foreach ($items as $item) {
            $y = RSS_Tags($item, 1);    // get description of article, type 1
            array_push($RSS_Content, $y);
        }
    }
}

/**
 * @param     $url
 * @param int $size
 *
 * @return string
 */
function RSS_Links($url, $size = 15)
{
    global $RSS_Content;

    $page = '<ul>';

    RSS_RetrieveLinks($url);
    if ($size > 0) {
        $recents = array_slice($RSS_Content, 0, $size + 1);
    }

    foreach ($recents as $article) {
        $type = $article['type'];
        if ($type == 0) {
            continue;
        }
        $title = $article['title'];
        $link  = $article['link'];
        $page .= "<li><a href=\"$link\">$title</a></li>\n";
    }

    $page .= "</ul>\n";

    return $page;
}

/**
 * @param     $url
 * @param int $size
 * @param int $site
 *
 * @return string
 */
function RSS_Display($url, $size = 15, $site = 0)
{
    global $RSS_Content;

    $opened = false;
    $page   = '';
    $site   = ((int)$site == 0) ? 1 : 0;

    RSS_Retrieve($url);
    if ($size > 0) {
        $recents = array_slice($RSS_Content, $site, $size + 1 - $site);
    }

    foreach ($recents as $article) {
        $type = $article['type'];
        if ($type == 0) {
            if ($opened == true) {
                $page .= '</ul>';
                $opened = false;
            }
            $page .= '<b>';
        } else {
            if ($opened == false) {
                $page .= '<ul>';
                $opened = true;
            }
        }
        $title = $article['title'];
        $link  = $article['link'];
        $page .= "<tr class=\"even\"><td width=\"300\"><img src=\"../assets/images/admin/info_button.png\" border=0 /> <a href=\"$link\">$title</a><br>";

        $description = $article['description'];
        if ($description != false) {
            $page .= "$description<br><br></td></tr>";
        }
        $page .= '';

        if ($type == 0) {
            $page .= '</b>';
        }
    }

    if ($opened == true) {
        $page .= '</ul>';
    }

    return $page . '';
}

/**
 * @param     $url
 * @param int $size
 * @param int $site
 * @param int $withdate
 *
 * @return string
 */
function RSS_DisplayForum($url, $size = 15, $site = 0, $withdate = 0)
{
    global $RSS_Content;

    $opened = false;
    $page   = '';
    $site   = ((int)$site == 0) ? 1 : 0;

    RSS_Retrieve($url);
    if ($size > 0) {
        $recents = array_slice($RSS_Content, $site, $size + 1 - $site);
    }

    foreach ($recents as $article) {
        $type = $article['type'];
        if ($type == 0) {
            if ($opened == true) {
                $page .= '</ul>';
                $opened = false;
            }
            $page .= '<b>';
        } else {
            if ($opened == false) {
                $page .= '<ul>';
                $opened = true;
            }
        }

        $title = $article['title'];
        $link  = $article['link'];

        $page .= "<img src=\"../assets/images/admin/comment.png\" border=0 />&nbsp;&nbsp;&nbsp;<a href=\"$link\">$title</a><br><br>";

        if ($type == 0) {
            $page .= '</b>';
        }
    }

    if ($opened == true) {
        $page .= '</ul>';
    }

    return $page . '';
}
