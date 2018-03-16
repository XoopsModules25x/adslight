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

/**
 * Protection against inclusion outside the site
 */
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Includes of form objects and uploader
 */
require_once XOOPS_ROOT_PATH . '/class/uploader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/modules/adslight/class/Utility.php';

/**
 * light_pictures class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Pictures extends XoopsObject
{
    public $db;
    // constructor

    /**
     * @param null       $id
     * @param null|array $lid
     */
    public function __construct($id = null, $lid = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cod_img', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('date_added', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('date_modified', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('uid_owner', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX, null, false);
        if (!empty($lid)) {
            if (is_array($lid)) {
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
    public function load($id)
    {
        global $moduleDirName;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('adslight_pictures') . ' WHERE cod_img=' . $id . '';
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
    public function getAllPictures($criteria = [], $asobject = false, $sort = 'cod_img', $cat_order = 'ASC', $limit = 0, $start = 0)
    {
        global $moduleDirName;
        $db          = \XoopsDatabaseFactory::getDatabaseConnection();
        $ret         = [];
        $where_query = '';
        if (is_array($criteria) && count($criteria) > 0) {
            $where_query = ' WHERE';
            foreach ($criteria as $c) {
                $where_query .= " {$c} AND";
            }
            $where_query = substr($where_query, 0, -4);
        } elseif (!is_array($criteria) && $criteria) {
            $where_query = " WHERE {$criteria}";
        }
        if (!$asobject) {
            $sql    = 'SELECT cod_img FROM ' . $db->prefix('adslight_pictures') . "$where_query ORDER BY $sort $cat_order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['cog_img'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('adslight_pictures') . "$where_query ORDER BY $sort $cat_order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = new Pictures($myrow);
            }
        }

        return $ret;
    }
}

// -------------------------------------------------------------------------
// ------------------light_pictures user handler class -------------------
// -------------------------------------------------------------------------
