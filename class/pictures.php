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
// defined('XOOPS_ROOT_PATH') || exit('XOOPS Root Path not defined');

/**
 * Includes of form objects and uploader
 */
require_once XOOPS_ROOT_PATH . '/class/uploader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/modules/adslight/class/utility.php';

/**
 * light_pictures class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class AdslightPictures extends XoopsObject
{
    public $db;
    // constructor

    /**
     * @param null       $id
     * @param null|array $lid
     */
    public function __construct($id = null, $lid = null)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
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
     * @deprecated this should be handled through {@see AdslightPicturesHandler}
     */
    public function getAllPictures($criteria = array(), $asobject = false, $sort = 'cod_img', $cat_order = 'ASC', $limit = 0, $start = 0)
    {
        global $moduleDirName;
        $db          = XoopsDatabaseFactory::getDatabaseConnection();
        $ret         = array();
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
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = $myrow['cog_img'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('adslight_pictures') . "$where_query ORDER BY $sort $cat_order";
            $result = $db->query($sql, $limit, $start);
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = new AdslightPictures($myrow);
            }
        }

        return $ret;
    }
}

// -------------------------------------------------------------------------
// ------------------light_pictures user handler class -------------------
// -------------------------------------------------------------------------

/**
 * AdslightPicturesHandler class definition
 *
 * This class provides simple mechanism to manage {@see AdslightPictures} objects
 * and generate forms for inclusion
 *
 * @todo change this to a XoopsPersistableObjectHandler and remove 'most' method overloads
 */
class AdslightPicturesHandler extends XoopsObjectHandler
{
    /**
     * Class constructor
     * @param XoopsDatabase $db
     */

    public function __construct($db)
    {
        parent::__construct($db, 'adslight_pictures', 'AdslightPictures', 'cod_img', 'title');
    }

    /**
     * create a new light_pictures
     *
     * @param  bool $isNew flag the new objects as "new"?
     * @return XoopsObject light_pictures
     */
    public function create($isNew = true)
    {
        $adslightPictures = new AdslightPictures();
        if ($isNew) {
            $adslightPictures->setNew();
        } else {
            $adslightPictures->unsetNew();
        }

        return $adslightPictures;
    }

    /**
     * retrieve a light_pictures
     *
     * @param int $id of the light_pictures
     * @param     $lid
     *
     * @return mixed reference to the {@link light_pictures} object, FALSE if failed
     */
    public function get($id, $lid = null)
    {
        global $moduleDirName;

        $sql = 'SELECT * FROM ' . $this->db->prefix('adslight_pictures') . ' WHERE cod_img=' . $id . ' AND lid=' . $lid . '';
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if ($numrows == 1) {
            $adslightPictures = new AdslightPictures();
            $adslightPictures->assignVars($this->db->fetchArray($result));

            return $adslightPictures;
        }

        return false;
    }

    /**
     * insert a new AdslightPicture object into the database
     *
     * @param XoopsObject $adslightPictures
     * @param bool        $force
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(XoopsObject $adslightPictures, $force = false)
    {
        global $xoopsConfig, $lid, $moduleDirName;
        if (!$adslightPictures instanceof AdslightPictures) {
            return false;
        }
        if (!$adslightPictures->isDirty()) {
            return true;
        }
        if (!$adslightPictures->cleanVars()) {
            return false;
        }
        foreach ($adslightPictures->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = time();
        if ($adslightPictures->isNew()) {
            // add/modify of AdslightPictures
            $adslightPictures = new AdslightPictures();

            $format = 'INSERT INTO %s (cod_img, title, date_added, date_modified, lid, uid_owner, url)';
            $format .= 'VALUES (%u, %s, %s, %s, %s, %s, %s)';
            $sql    = sprintf($format, $this->db->prefix('adslight_pictures'), $cod_img, $this->db->quoteString($title), $now, $now, $this->db->quoteString($lid), $this->db->quoteString($uid_owner), $this->db->quoteString($url));
            $force  = true;
        } else {
            $format = 'UPDATE %s SET ';
            $format .= 'cod_img=%u, title=%s, date_added=%s, date_modified=%s, lid=%s, uid_owner=%s, url=%s';
            $format .= ' WHERE cod_img = %u';
            $sql    = sprintf($format, $this->db->prefix('adslight_pictures'), $cod_img, $this->db->quoteString($title), $now, $now, $this->db->quoteString($lid), $this->db->quoteString($uid_owner), $this->db->quoteString($url), $cod_img);
        }
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (empty($cod_img)) {
            $cod_img = $this->db->getInsertId();
        }
        $adslightPictures->assignVars(array(
                                          'cod_img' => $cod_img,
                                          'lid'     => $lid,
                                          'url'     => $url
                                      ));

        return true;
    }

    /**
     * delete AdslightPictures object from the database
     *
     * @param  XoopsObject $adslightPictures reference to the AdslightPictures to delete
     * @param  bool        $force
     * @return bool        FALSE if failed.
     */
    public function delete(XoopsObject $adslightPictures, $force = false)
    {
        global $moduleDirName;

        if (!$adslightPictures instanceof AdslightPictures) {
            return false;
        }
        $sql = sprintf('DELETE FROM %s WHERE cod_img = %u', $this->db->prefix('adslight_pictures'), $adslightPictures->getVar('cod_img'));
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * retrieve AdslightPictures object(s) from the database
     *
     * @param  CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param  bool            $id_as_key use the UID as key for the array?
     * @return array  array of {@link AdslightPictures} objects
     */
    public function &getObjects(CriteriaElement $criteria = null, $id_as_key = false)
    {
        global $moduleDirName;

        $ret   = array();
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('adslight_pictures');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $adslightPictures = new AdslightPictures();
            $adslightPictures->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $adslightPictures;
            } else {
                $ret[$myrow['cod_img']] = $adslightPictures;
            }
            unset($adslightPictures);
        }

        return $ret;
    }

    /**
     * count AdslightPictures matching a condition
     *
     * @param  CriteriaElement $criteria {@link CriteriaElement} to match
     * @return int    count of AdslightPictures
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        global $moduleDirName;

        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('adslight_pictures');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * delete AdslightPictures matching a set of conditions
     *
     * @param  CriteriaElement $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll(CriteriaElement $criteria = null)
    {
        global $moduleDirName;
        $sql = 'DELETE FROM ' . $this->db->prefix('adslight_pictures');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Render a form to send pictures
     *
     * @param int      $uid
     * @param int      $lid
     * @param int      $maxbytes the maximum size of a picture
     * @param XoopsTpl $xoopsTpl the one in which the form will be rendered
     * @return bool   TRUE
     *
     * obs: Some functions wont work on php 4 so edit lines down under acording to your version
     */
    public function renderFormSubmit($uid, $lid, $maxbytes, $xoopsTpl)
    {
        global $moduleDirName, $main_lang;
        $uid        = (int)$uid;
        $lid        = (int)$lid;
        $form       = new XoopsThemeForm(_ADSLIGHT_SUBMIT_PIC_TITLE, 'form_picture', XOOPS_URL . "/modules/adslight/add_photo.php?lid={$lid}&uid=" . $xoopsUser->getVar('uid'), 'post', true);
        $field_url  = new XoopsFormFile(_ADSLIGHT_SELECT_PHOTO, 'sel_photo', 2000000);
        $field_desc = new XoopsFormText(_ADSLIGHT_CAPTION, 'caption', 35, 55);

        $form->setExtra('enctype="multipart/form-data"');
        $button_send   = new XoopsFormButton('', 'submit_button', _ADSLIGHT_UPLOADPICTURE, 'submit');
        $field_warning = new XoopsFormLabel(sprintf(_ADSLIGHT_YOUCANUPLOAD, $maxbytes / 1024));
        $field_lid     = new XoopsFormHidden('lid', $lid);
        $field_uid     = new XoopsFormHidden('uid', $uid);

        $field_token = $GLOBALS['xoopsSecurity']->getTokenHTML();

        $form->addElement($field_warning);
        $form->addElement($field_url, true);
        $form->addElement($field_desc, true);
        $form->addElement($field_lid, true);
        $form->addElement($field_uid, true);

        $form->addElement($field_token, true);

        $form->addElement($button_send);
        if (str_replace('.', '', PHP_VERSION) > 499) {
            $form->assign($xoopsTpl);
        } else {
            $form->display();
        }

        return true;
    }

    /**
     * Render a form to edit the description of the pictures
     *
     * @param  string $caption  The description of the picture
     * @param  int    $cod_img  the id of the image in database
     * @param  text   $filename the url to the thumb of the image so it can be displayed
     * @return bool   TRUE
     */
    public function renderFormEdit($caption, $cod_img, $filename)
    {
        global $moduleDirName, $main_lang;

        $form       = new XoopsThemeForm(_ADSLIGHT_EDIT_CAPTION, 'form_picture', 'editdesc.php', 'post', true);
        $field_desc = new XoopsFormText($caption, 'caption', 35, 55);
        $form->setExtra('enctype="multipart/form-data"');
        $button_send = new XoopsFormButton(_ADSLIGHT_EDIT, 'submit_button', _SUBMIT, 'submit');
        //@todo - replace alt with language string
        $field_warning = new XoopsFormLabel("<img src='{$filename}' alt='sssss'>");
        $field_cod_img = new XoopsFormHidden('cod_img', $cod_img);
        //    $field_lid = new XoopsFormHidden('lid', $lid);
        $field_marker = new XoopsFormHidden('marker', 1);

        $field_token = $GLOBALS['xoopsSecurity']->getTokenHTML();

        $form->addElement($field_warning);
        $form->addElement($field_desc);
        $form->addElement($field_cod_img);
        $form->addElement($field_marker);
        $form->addElement($field_token);
        $form->addElement($button_send);
        $form->display();

        return true;
    }

    /**
     * Upload the file and Save into database
     *
     * @param  text $title         A litle description of the file
     * @param  text $path_upload   The path to where the file should be uploaded
     * @param  int  $thumbwidth    the width in pixels that the thumbnail will have
     * @param  int  $thumbheight   the height in pixels that the thumbnail will have
     * @param  int  $pictwidth     the width in pixels that the pic will have
     * @param  int  $pictheight    the height in pixels that the pic will have
     * @param  int  $maxfilebytes  the maximum size a file can have to be uploaded in bytes
     * @param  int  $maxfilewidth  the maximum width in pixels that a pic can have
     * @param  int  $maxfileheight the maximum height in pixels that a pic can have
     * @return bool FALSE if upload fails or database fails
     */
    public function receivePicture($title, $path_upload, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $maxfilebytes, $maxfilewidth, $maxfileheight)
    {
        global $xoopsDB, $lid;
        //busca id do user logado
        $uid = $GLOBALS['xoopsUser']->getVar('uid');
        $lid = Request::getInt('lid', 0, 'POST');
        //create a hash so it does not erase another file
        $hash1 = time();
        $hash  = substr($hash1, 0, 4);
        // mimetypes and settings put this in admin part later
        $allowed_mimetypes = array(
            'image/jpeg',
            'image/gif'
        );
        $maxfilesize       = $maxfilebytes;
        // create the object to upload
        $uploader = new XoopsMediaUploader($path_upload, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        // fetch the media
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            //lets create a name for it
            $uploader->setPrefix("pic_{$lid}_");
            //now let s upload the file
            if (!$uploader->upload()) {
                // if there are errors lets return them
                echo '<div style="color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center;"><p>' . $uploader->getErrors() . '</p></div>';

                return false;
            } else {
                // now let s create a new object picture and set its variables
                $picture = $this->create();
                $url     = $uploader->getSavedFileName();
                $picture->setVar('url', $url);
                $picture->setVar('title', $title);
                $uid = $GLOBALS['xoopsUser']->getVar('uid');
                $lid = (int)$lid;
                $picture->setVar('lid', $lid);
                $picture->setVar('uid_owner', $uid);
                $this->insert($picture);
                $saved_destination = $uploader->getSavedDestination();
                $this->resizeImage($saved_destination, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $path_upload);
            }
        } else {
            echo '<div style="color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center;"><p>' . $uploader->getErrors() . '</p></div>';

            return false;
        }

        return true;
    }

    /**
     * Resize a picture and save it to $path_upload
     *
     * @param  string $img         the path to the file
     * @param  string $path_upload The path to where the files should be saved after resizing
     * @param  int    $thumbwidth  the width in pixels that the thumbnail will have
     * @param  int    $thumbheight the height in pixels that the thumbnail will have
     * @param  int    $pictwidth   the width in pixels that the pic will have
     * @param  int    $pictheight  the height in pixels that the pic will have
     * @return nothing
     */
    public function resizeImage($img, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $path_upload)
    {
        $img2   = $img;
        $path   = pathinfo($img);
        $img    = imagecreatefromjpeg($img);
        $xratio = $thumbwidth / imagesx($img);
        $yratio = $thumbheight / imagesy($img);
        if ($xratio < 1 || $yratio < 1) {
            if ($xratio < $yratio) {
                $resized = imagecreatetruecolor($thumbwidth, floor(imagesy($img) * $xratio));
            } else {
                $resized = imagecreatetruecolor(floor(imagesx($img) * $yratio), $thumbheight);
            }
            imagecopyresampled($resized, $img, 0, 0, 0, 0, imagesx($resized) + 1, imagesy($resized) + 1, imagesx($img), imagesy($img));
            imagejpeg($resized, "{$path_upload}/thumbs/thumb_{$path['basename']}");
            imagedestroy($resized);
        } else {
            imagejpeg($img, "{$path_upload}/thumbs/thumb_{$path['basename']}");
        }
        imagedestroy($img);
        $path2   = pathinfo($img2);
        $img2    = imagecreatefromjpeg($img2);
        $xratio2 = $pictwidth / imagesx($img2);
        $yratio2 = $pictheight / imagesy($img2);
        if ($xratio2 < 1 || $yratio2 < 1) {
            if ($xratio2 < $yratio2) {
                $resized2 = imagecreatetruecolor($pictwidth, floor(imagesy($img2) * $xratio2));
            } else {
                $resized2 = imagecreatetruecolor(floor(imagesx($img2) * $yratio2), $pictheight);
            }
            imagecopyresampled($resized2, $img2, 0, 0, 0, 0, imagesx($resized2) + 1, imagesy($resized2) + 1, imagesx($img2), imagesy($img2));
            imagejpeg($resized2, "{$path_upload}/midsize/resized_{$path2['basename']}");
            imagedestroy($resized2);
        } else {
            imagejpeg($img2, "{$path_upload}/midsize/resized_{$path2['basename']}");
        }
        imagedestroy($img2);
    }
}
