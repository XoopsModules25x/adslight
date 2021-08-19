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
$RSS_Content = [];
/**
 * @param $item
 * @param $type
 */
function RSS_Tags($item, $type): array
{
    $y                 = [];
    $tnl               = $item->getElementsByTagName('title');
    $tnl               = $tnl->item(0);
    $title             = $tnl->firstChild->textContent;
    $tnl               = $item->getElementsByTagName('link');
    $tnl               = $tnl->item(0);
    $link              = $tnl->firstChild->textContent;
    $tnl               = $item->getElementsByTagName('pubDate');
    $tnl               = $tnl->item(0);
    $date              = $tnl->firstChild->textContent;
    $tnl               = $item->getElementsByTagName('description');
    $tnl               = $tnl->item(0);
    $description       = $tnl->firstChild->textContent;
    $y['title']        = $title;
    $y['link']         = $link;
    $y['date_created'] = $date;
    $y['description']  = $description;
    $y['type']         = $type;
    return $y;
}

/**
 * @param $channel
 */
function RSS_Channel($channel): void
{
    global $RSS_Content;
    $items = $channel->getElementsByTagName('item');
    // Processing channel
    $y             = RSS_Tags($channel, 0);        // get description of channel, type 0
    $RSS_Content[] = $y;
    // Processing articles
    foreach ($items as $item) {
        $y             = RSS_Tags($item, 1);    // get description of article, type 1
        $RSS_Content[] = $y;
    }
}

/**
 * @param $url
 */
function RSS_Retrieve($url): void
{
    global $RSS_Content;
    $doc = new DOMDocument();
    $doc->load($url);
    $channels    = $doc->getElementsByTagName('channel');
    $RSS_Content = [];
    foreach ($channels as $channel) {
        RSS_Channel($channel);
    }
}

/**
 * @param $url
 */
function RSS_RetrieveLinks($url): void
{
    global $RSS_Content;
    $doc = new DOMDocument();
    $doc->load($url);
    $channels    = $doc->getElementsByTagName('channel');
    $RSS_Content = [];
    foreach ($channels as $channel) {
        $items = $channel->getElementsByTagName('item');
        foreach ($items as $item) {
            $y             = RSS_Tags($item, 1);    // get description of article, type 1
            $RSS_Content[] = $y;
        }
    }
}

/**
 * @param     $url
 * @param int $size
 */
function RSS_Links($url, $size = 15): string
{
    global $RSS_Content;
    $recents = [];
    $page    = '<ul>';
    RSS_RetrieveLinks($url);
    if ($size > 0) {
        $recents = array_slice($RSS_Content, 0, $size + 1);
    }
    foreach ($recents as $article) {
        $type = $article['type'];
        if (0 === $type) {
            continue;
        }
        $title = $article['title'];
        $link  = $article['link'];
        $page  .= "<li><a href=\"{$link}\">{$title}</a></li>\n";
    }

    return $page . "</ul>\n";
}

/**
 * @param     $url
 * @param int $size
 * @param int $site
 */
function RSS_Display(
    $url,
    $size = 15,
    $site = 0
): string {
    global $RSS_Content;
    $recents = [];
    $opened  = false;
    $page    = '';
    $site    = 0 === (int)$site ? 1 : 0;
    RSS_Retrieve($url);
    if ($size > 0) {
        $recents = array_slice($RSS_Content, $site, $size + 1 - $site);
    }
    foreach ($recents as $article) {
        $type = $article['type'];
        if (0 === $type) {
            if ($opened) {
                $page   .= '</ul>';
                $opened = false;
            }
            $page .= '<b>';
        } elseif (!$opened) {
            $page   .= '<ul>';
            $opened = true;
        }

        $title       = $article['title'];
        $link        = $article['link'];
        $page        .= "<tr class=\"even\"><td width=\"300\"><img src=\"../assets/images/admin/info_button.png\" border=\"0\"> <a href=\"{$link}\">{$title}</a><br>";
        $description = $article['description'];
        if (false !== $description) {
            $page .= "{$description}<br><br></td></tr>";
        }
        $page .= '';
        if (0 === $type) {
            $page .= '</b>';
        }
    }
    if ($opened) {
        $page .= '</ul>';
    }
    return $page;
}

/**
 * @param     $url
 * @param int $size
 * @param int $site
 * @param int $withdate
 */
function RSS_DisplayForum(
    $url,
    $size = 15,
    $site = 0,
    $withdate = 0
): string {
    global $RSS_Content;
    $recents = [];
    $opened  = false;
    $page    = '';
    $site    = 0 === (int)$site ? 1 : 0;
    RSS_Retrieve($url);
    if ($size > 0) {
        $recents = array_slice($RSS_Content, $site, $size + 1 - $site);
    }
    foreach ($recents as $article) {
        $type = $article['type'];
        if (0 === $type) {
            if ($opened) {
                $page   .= '</ul>';
                $opened = false;
            }
            $page .= '<b>';
        } elseif (!$opened) {
            $page   .= '<ul>';
            $opened = true;
        }

        $title = $article['title'];
        $link  = $article['link'];

        $page .= "<img src=\"../assets/images/admin/comment.png\" border=0 >&nbsp;&nbsp;&nbsp;<a href=\"${link}\">${title}</a><br><br>";

        if (0 === $type) {
            $page .= '</b>';
        }
    }
    if ($opened) {
        $page .= '</ul>';
    }
    return $page;
}
