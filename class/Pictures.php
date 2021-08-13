<?php

declare(strict_types=1);

namespace XoopsModules\Adslight;

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

/**
 * Protection against inclusion outside the site
 */

/**
 * Includes of form objects and uploader
 */
require_once XOOPS_ROOT_PATH . '/class/uploader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * light_pictures class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Pictures extends \XoopsObject
{
    /** @var \XoopsMySQLDatabase $db */
    public $db;
    // constructor

    /**
     * @param null       $id
     * @param null|array $lid
     */
    public function __construct($id = null, $lid = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cod_img', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('title', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('date_created', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('date_updated', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('lid', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('uid_owner', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('url', \XOBJ_DTYPE_TXTBOX, null, false);
        if (!empty($lid)) {
            if (\is_array($lid)) {
                $this->assignVars($lid);
            } else {
                $this->load((int)$lid);
            }
        } else {
            $this->setNew();
        }
    }

    /**
     * @param $id
     */
    public function load($id): void
    {
        $sql   = 'SELECT * FROM ' . $this->db->prefix('adslight_pictures') . ' WHERE cod_img=' . $id . ' ';
        $myrow = $this->db->fetchArray($this->db->query($sql));
        $this->assignVars($myrow);
        if (!$myrow) {
            $this->setNew();
        }
    }

    /**
     * @param array  $criteria
     * @param bool   $asobject
     * @param string $sort
     * @param string $cat_order
     * @param int    $limit
     * @param int    $start
     * @return array
     * @internal   param string $order
     * @deprecated this should be handled through {@see PicturesHandler}
     */
    public function getAllPictures($criteria = [], $asobject = false, $sort = 'cod_img', $cat_order = 'ASC', $limit = 0, $start = 0): array
    {
        /** @var \XoopsMySQLDatabase $xoopsDB */
        $xoopsDB     = \XoopsDatabaseFactory::getDatabaseConnection();
        $ret         = [];
        $where_query = '';
        if (\is_array($criteria) && \count($criteria) > 0) {
            $where_query = ' WHERE';
            foreach ($criteria as $c) {
                $where_query .= " {$c} AND";
            }
            $where_query = \mb_substr($where_query, 0, -4);
        } elseif (!\is_array($criteria) && $criteria) {
            $where_query = " WHERE {$criteria}";
        }
        if ($asobject) {
            $sql    = 'SELECT * FROM ' . $xoopsDB->prefix('adslight_pictures') . "$where_query ORDER BY $sort $cat_order";
            $result = $xoopsDB->query($sql, $limit, $start);
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                $ret[] = new self($myrow);
            }
        } else {
            $sql    = 'SELECT cod_img FROM ' . $xoopsDB->prefix('adslight_pictures') . "$where_query ORDER BY $sort $cat_order";
            $result = $xoopsDB->query($sql, $limit, $start);
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                $ret[] = $myrow['cog_img'];
            }
        }

        return $ret;
    }
}

// -------------------------------------------------------------------------
// ------------------light_pictures user handler class -------------------
// -------------------------------------------------------------------------
