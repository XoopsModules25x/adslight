<?php

declare(strict_types=1);

###############################################################################
##                RSSFit - Extendable XML news feed generator                ##
##                Copyright (c) 2004 - 2005 NS Tai (aka tuff)                ##
##                       <http://www.brandycoke.com> & ... nbsp;   ##
###############################################################################
##                    XOOPS - PHP Content Management System                  ##
##                       Copyright (c) 2000 XOOPS.org                        ##
##                          <https://xoops.org>   ... nbsp;   ##
###############################################################################
##  This program is free software; you can redistribute it and/or modify     ##
##  it under the terms of the GNU General Public License as published by     ##
##  the Free Software Foundation; either version 2 of the License, or        ##
##  (at your option) any later version.                                      ##
##                                                                           ##
##  You may not change or alter any portion of this comment or credits       ##
##  of supporting developers from this source code or any supporting         ##
##  source code which is considered copyrighted (c) material of the          ##
##  original comment or credit authors.                                      ##
##                                                                           ##
##  This program is distributed in the hope that it will be useful,          ##
##  but WITHOUT ANY WARRANTY; without even the implied warranty of           ##
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            ##
##  GNU General Public License for more details.                             ##
##                                                                           ##
##  You should have received a copy of the GNU General Public License        ##
##  along with this program; if not, write to the Free Software              ##
##  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA ##
###############################################################################
/*
* Adslight RSSFit plugin based on Jobs RSSFit plugin by www.jlmzone.com
* Done by Bjuti (www.bjuti.info)
*  Last release date:  Jan. 18 2010
*  RSSFit version: 1.2 / 1.5
*  XOOPS version: 2.0.13.2 / 2.2.3 / 2.3.2b / 2.4.3
*/
if (!defined('RSSFIT_ROOT_PATH')) {
    exit();
}

/**
 * Class RssfitAdslight
 */
class RssfitAdslight
{
    public $dirname = 'adslight';
    public $modname;
    public $grab;
    public $module;    // optional, see line 74

    public function __construct()
    {
    }

    public function loadModule(): bool
    {
        $mod = &$GLOBALS['moduleHandler']->getByDirname($this->dirname);
        if (!$mod || !$mod->getVar('isactive')) {
            return false;
        }
        $this->modname = $mod->getVar('name');
        //$this->module =& $mod;    // optional, remove this line if there is nothing
        // to do with module info when grabbing entries
        return $mod;
    }

    /**
     * @param $obj
     * @return bool
     */
    public function &grabEntries($obj)
    {
        global $xoopsDB;
        $myts = \MyTextSanitizer::getInstance();
        $ret  = false;
        $i    = 0;
        //    The following example code grabs the latest entries from the module MyLinks
        $sql    = 'SELECT lid, title, status, desctext, date_created from ' . $xoopsDB->prefix('adslight_listing') . " WHERE valid = 'Yes' ORDER BY date_created DESC";
        $result = $xoopsDB->query($sql, $this->grab, 0);
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $link = XOOPS_URL . '/modules/' . $this->dirname . '/viewads.php?lid=' . $row['lid'];
            /*
             * Required elements of an RSS item
             */
            //    1. Title of an item
            $ret[$i]['title'] = $row['title'];
            //    2. URL of an item
            $ret[$i]['link'] = $link;
            //    3. Item modification date_created, must be in Unix time format
            $ret[$i]['timestamp'] = $row['date_created'];
            //    4. The item synopsis, or description, whatever
            $ret[$i]['description'] = $row['desctext'];  // $myts->displayTarea($row['desctext']);
            /*
            * Optional elements of an RSS item
            */ //    5. The item synopsis, or description, whatever
            // $ret[$i]['guid'] = $link;
            //    6. A string + domain that identifies a categorization taxonomy
            //    $ret[$i]['category'] = $this->modname;
            // $ret[$i]['domain'] = XOOPS_URL.'/modules/'.$this->dirname.'/';
            //    7. extra tags examples
            $ret[$i]['extras'] = [];
            $i++;
        }
        return $ret;
    }
}
