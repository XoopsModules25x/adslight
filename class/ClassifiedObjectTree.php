<?php

namespace XoopsModules\Adslight;

/**
 * Class ClassifiedObjectTree
 * @package XoopsModules\Adslight
 */
class ClassifiedObjectTree extends \XoopsObjectTree
{
    //generates id path from the root id to a given id
    // the path is delimetered with "/"
    /**
     * @param        $sel_id
     * @param string $path
     *
     * @return string
     */
    public function getIdPathFromId($sel_id, $path = '')
    {
        global $xoopsDB;
        $sel_id = (int)$sel_id;
        $result = $xoopsDB->query('SELECT ' . $this->parentId . ' FROM ' . $this->table . ' WHERE ' . $this->myId . "=$sel_id");
        if (0 == $xoopsDB->getRowsNum($result)) {
            return $path;
        }
        [$parentid] = $xoopsDB->fetchRow($result);
        $path = '/' . $sel_id . $path . '';
        if (0 == $parentid) {
            return $path;
        }
        $path = $this->getIdPathFromId($parentid, $path);

        return $path;
    }

    //generates nicely formatted linked path from the root id to a given id
    /**
     * @param        $sel_id
     * @param        $title
     * @param        $funcURL
     * @param string $path
     *
     * @return string
     */
    public function getNicePathFromId($sel_id, $title, $funcURL, $path = '')
    {
        global $xoopsDB;
        $path   = !empty($path) ? '&nbsp;:&nbsp;' . $path : $path;
        $sel_id = (int)$sel_id;
        $sql    = 'SELECT ' . $this->parentId . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->myId . "=$sel_id";
        $result = $xoopsDB->query($sql);
        if (0 == $xoopsDB->getRowsNum($result)) {
            return $path;
        }
        [$parentid, $name] = $xoopsDB->fetchRow($result);
        $myts = \MyTextSanitizer::getInstance();
        $name = htmlspecialchars($name);
        $path = "<a href='" . $funcURL . '&amp;' . $this->myId . '=' . $sel_id . "'>" . $name . '</a>' . $path . '';
        if (0 == $parentid) {
            return $path;
        }
        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

        return $path;
    }
}
