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

	if (!defined('XOOPS_ROOT_PATH')){ exit(); }
	

// Select Maps
$path = XOOPS_ROOT_PATH.'/modules/adslight/maps';
if ($handle = opendir($path))
{
	while (false !== ($file = readdir($handle)))
	{
	   if (!is_dir($path.'.'.$file) && $file != '.' && $file != '..' && $file != 'index.html')
		{
		$maps_name=$file;
		$adslight_maps[$maps_name]=$file;
	      }
	}
   closedir($handle);
}

// sql customized language file
global $xoopsConfig;
 if( file_exists( XOOPS_ROOT_PATH.'/modules/adslight/sql/' . $xoopsConfig['language'] . '/mysql.sql') )
	{
    $adslight_sql = 'sql/' . $xoopsConfig['language'] . '/mysql.sql' ;
     } else {
    $adslight_sql = 'sql/english/mysql.sql';
 }

$modversion['name'] = 'AdsLight';
$modversion['version'] = '2.2';
$modversion['description'] = _MI_ADSLIGHT_DESC ;
$modversion['credits'] = 'AdsLight';
$modversion['author'] = 'Luc Bizet **';
$modversion['help']        = 'page=help';
$modversion['license'] = 'GPL';
$modversion['license_file'] = 'http://www.gnu.org/licenses/gpl.html';
$modversion['official'] = 0;
$modversion['image'] = 'images/adslight.png';
$modversion['dirname'] = 'adslight';

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
//about
$modversion['release_date']        = '2013/02/02';
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Beta 2";
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = "2.5.6";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);


$modversion['sqlfile']['mysql'] = $adslight_sql;
$modversion['onInstall'] = 'include/install.php';
$modversion['onUpdate'] = 'include/update_function.php';


$modversion["release"] = '25-05-2011';
$modversion['support_site_url']	= 'http://#';
$modversion['support_site_name'] = 'AdsLight';

// Tables crée depuis le fichier sql
$modversion['tables'][0] = 'adslight_categories';
$modversion['tables'][1] = 'adslight_ip_log';
$modversion['tables'][2] = 'adslight_listing';
$modversion['tables'][3] = 'adslight_type';
$modversion['tables'][4] = 'adslight_pictures';
$modversion['tables'][5] = 'adslight_price';
$modversion['tables'][6] = 'adslight_item_votedata';
$modversion['tables'][7] = 'adslight_user_votedata';
$modversion['tables'][8] = 'adslight_replies';
$modversion['tables'][9] = 'adslight_usure';


// Pour l'administration
$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';


// Templates
global $xoopsModuleConfig;


$modversion['templates'][1]['file'] = 'adslight_index.html';
$modversion['templates'][1]['description'] = '';

$modversion['templates'][2]['file'] = 'adslight_category.html';
$modversion['templates'][2]['description'] = '';

$modversion['templates'][3]['file'] = 'adslight_item.html';
$modversion['templates'][3]['description'] = '';

$modversion['templates'][4]['file'] = 'adslight_rate_item.html';
$modversion['templates'][4]['description'] = '';

$modversion['templates'][5]['file'] = 'adslight_rate_user.html';
$modversion['templates'][5]['description'] = '';

$modversion['templates'][6]['file'] = 'adslight_view_photos.html';
$modversion['templates'][6]['description'] = '';

$modversion['templates'][7]['file'] = 'adslight_addlisting.html';
$modversion['templates'][7]['description'] = '';

$modversion['templates'][8]['file'] = 'adslight_members.html';
$modversion['templates'][8]['description'] = '';

$modversion['templates'][9]['file'] = 'adslight_replies.html';
$modversion['templates'][9]['description'] = '';

$modversion['templates'][10]['file'] = 'adslight_tips_writing_ad.html';
$modversion['templates'][10]['description'] = '';

$modversion['templates'][11]['file'] = 'adslight_search.html';
$modversion['templates'][11]['description'] = '';

$modversion['templates'][12]['file'] = 'adslight_search_result.html';
$modversion['templates'][12]['description'] = '';

$modversion['templates'][13]['file'] = 'adslight_maps.html';
$modversion['templates'][13]['description'] = '';

$modversion['templates'][14]['file'] = 'adslight_menu.html';
$modversion['templates'][14]['description'] = '';

$modversion['templates'][15]['file'] = 'adslight_bookmark.html';
$modversion['templates'][15]['description'] = '';

$modversion['templates'][16]['file'] = 'adslight_xpayment_form.html';
$modversion['templates'][16]['description'] = '';

// Blocs
$modversion['blocks'][1]['file'] = 'ads.php';
$modversion['blocks'][1]['name'] = _MI_ADSLIGHT_BNAME1;
$modversion['blocks'][1]['description'] = _MI_ADSLIGHT_BNAME1_DESC;
$modversion['blocks'][1]['show_func'] = 'adslight_show';
$modversion['blocks'][1]['edit_func'] = 'adslight_edit';
$modversion['blocks'][1]['options'] = 'date|10|25|0';
$modversion['blocks'][1]['template'] = 'adslight_block_new.html';

$modversion['blocks'][2]['file'] = 'ads.php';
$modversion['blocks'][2]['name'] = _MI_ADSLIGHT_BNAME2;
$modversion['blocks'][2]['description'] = _MI_ADSLIGHT_BNAME2_DESC;
$modversion['blocks'][2]['show_func'] = 'adslight_show';
$modversion['blocks'][2]['edit_func'] = 'adslight_edit';
$modversion['blocks'][2]['options'] = 'hits|10|25|0';
$modversion['blocks'][2]['template'] = 'adslight_block_top.html';

$modversion['blocks'][3]['file'] = 'ads_2.php';
$modversion['blocks'][3]['name'] = _MI_ADSLIGHT_BNAME3;
$modversion['blocks'][3]['description'] = _MI_ADSLIGHT_BNAME3_DESC;
$modversion['blocks'][3]['show_func'] = 'adslight_b2_show';
$modversion['blocks'][3]['edit_func'] = 'adslight_b2_edit';
$modversion['blocks'][3]['options'] = 'date|10|25|0';
$modversion['blocks'][3]['template'] = 'adslight_block2_new.html';

$modversion['blocks'][4]['file'] = 'adslight_add.php';
$modversion['blocks'][4]['name'] = _MI_ADSLIGHT_ADDMENU;
$modversion['blocks'][4]['description'] = _MI_ADSLIGHT_ADDMENU_DESC;
$modversion['blocks'][4]['show_func'] = 'b_adslight_add';
$modversion['blocks'][4]['template'] = 'adslight_block_add.html';

// Bloc Map_France //
$modversion['blocks'][5]['file'] = 'maps.php';
$modversion['blocks'][5]['name'] = _MI_ADSLIGHT_MAPFRANCE;
$modversion['blocks'][5]['description'] = _MI_ADSLIGHT_MAPFRANCE_DESC;
$modversion['blocks'][5]['show_func'] = 'adslight_maps_show';
$modversion['blocks'][5]['edit_func'] = 'adslight_maps_edit';
$modversion['blocks'][5]['template'] = 'adslight_block_maps.html';

// Menu
$modversion['hasMain'] = 1;

global $xoopsUser;
if ($xoopsUser) {

$member_usid = (is_object($xoopsUser))? $xoopsUser->getVar('uid'): 0;
		$modversion['sub'][1]['name'] = _MI_ADSLIGHT_SMENU2;
		$modversion['sub'][1]['url'] = 'add.php';
		$modversion['sub'][3]['name'] = _MI_ADSLIGHT_SMENU3;
		$modversion['sub'][3]['url'] = 'search.php';
		$modversion['sub'][2]['name'] = _MI_ADSLIGHT_SMENU1;
    	$modversion['sub'][2]['url'] = 'members.php?usid='.$member_usid.'';
}		

// Recherche
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'adslight_search';

// Commentaires
$modversion['hasComments'] = 1;

$modversion['comments']['itemName'] = 'usid';
$modversion['comments']['pageName'] = 'members.php';
$modversion['comments']['extraParams'] = array('usid');

// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'adslight_com_approve';
$modversion['comments']['callback']['update'] = 'adslight_com_update';


// Préférences
$modversion['hasconfig'] = 1;

$modversion['config'][0]['name'] = 'adslight_currency';
$modversion['config'][0]['title'] = '_MI_ADSLIGHT_CURRENCY';
$modversion['config'][0]['description'] = '' ;
$modversion['config'][0]['formtype'] = 'textbox' ;
$modversion['config'][0]['valuetype'] = 'text' ;
$modversion['config'][0]['default'] = 'EUR' ;

$modversion['config'][1]['name'] = 'adslight_money';
$modversion['config'][1]['title'] = '_MI_ADSLIGHT_MONEY';
$modversion['config'][1]['description'] = '' ;
$modversion['config'][1]['formtype'] = 'textbox' ;
$modversion['config'][1]['valuetype'] = 'text' ;
$modversion['config'][1]['default'] = '&euro;' ;

$modversion['config'][2]['name'] = 'adslight_maps_set';
$modversion['config'][2]['title'] =  '_MI_ADSLIGHT_MAPSSET';
$modversion['config'][2]['description'] =  '_MI_ADSLIGHT_MAPSSET_DESC';
$modversion['config'][2]['formtype'] = 'select';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = 'france';
$modversion['config'][2]['options'] = $adslight_maps;

$modversion['config'][3]['name'] = 'adslight_maps_width';
$modversion['config'][3]['title'] = '_MI_ADSLIGHT_MAPSW_TITLE';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'textbox' ;
$modversion['config'][3]['valuetype'] = 'text' ;
$modversion['config'][3]['default'] = '400';

$modversion['config'][4]['name'] = 'adslight_maps_height';
$modversion['config'][4]['title'] = '_MI_ADSLIGHT_MAPSH_TITLE';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'textbox' ;
$modversion['config'][4]['valuetype'] = 'text' ;
$modversion['config'][4]['default'] = '400';

$modversion['config'][5]['name'] = 'adslight_perpage';
$modversion['config'][5]['title'] = '_MI_ADSLIGHT_PERPAGE';
$modversion['config'][5]['description'] = '' ;
$modversion['config'][5]['formtype'] = 'select' ;
$modversion['config'][5]['valuetype'] = 'int' ;
$modversion['config'][5]['default'] = '15' ;
$modversion['config'][5]['options'] = array('10'=>10, '15'=>15, '20'=>20, '25'=>25, '30'=>30, '35'=>35, '40'=>40, '50'=>50) ;

$modversion['config'][6]['name'] = 'adslight_newad';
$modversion['config'][6]['title'] = '_MI_ADSLIGHT_VIEWNEWCLASS';
$modversion['config'][6]['description'] = '_MI_ADSLIGHT_ONHOME';
$modversion['config'][6]['formtype'] = 'yesno' ;
$modversion['config'][6]['valuetype'] = 'int' ;
$modversion['config'][6]['default'] = '1' ;
$modversion['config'][6]['options'] = array();

$modversion['config'][7]['name'] = 'adslight_newcount';
$modversion['config'][7]['title'] = '_MI_ADSLIGHT_NUMNEW';
$modversion['config'][7]['description'] = '_MI_ADSLIGHT_ONHOME';
$modversion['config'][7]['formtype'] = 'textbox' ;
$modversion['config'][7]['valuetype'] = 'int' ;
$modversion['config'][7]['default'] = '10' ;
$modversion['config'][7]['options'] = array();

$modversion['config'][8]['name'] = 'adslight_countday';
$modversion['config'][8]['title'] = '_MI_ADSLIGHT_NEWTIME';
$modversion['config'][8]['description'] = '_MI_ADSLIGHT_INDAYS';
$modversion['config'][8]['formtype'] = 'textbox' ;
$modversion['config'][8]['valuetype'] = 'int' ;
$modversion['config'][8]['default'] = '3' ;
$modversion['config'][8]['options'] = array();

$modversion['config'][9]['name'] = 'adslight_howlong';
$modversion['config'][9]['title'] = '_MI_ADSLIGHT_DAYS';
$modversion['config'][9]['description'] = '_MI_ADSLIGHT_INDAYS';
$modversion['config'][9]['formtype'] = 'textbox' ;
$modversion['config'][9]['valuetype'] = 'int' ;
$modversion['config'][9]['default'] = '14' ;
$modversion['config'][9]['options'] = array();

$modversion['config'][10]['name'] = 'adslight_sold_days';
$modversion['config'][10]['title'] = '_MI_ADSLIGHT_SOLD_DAYS';
$modversion['config'][10]['description'] = '_MI_ADSLIGHT_SOLDINDAYS';
$modversion['config'][10]['formtype'] = 'textbox' ;
$modversion['config'][10]['valuetype'] = 'int' ;
$modversion['config'][10]['default'] = '3' ;
$modversion['config'][10]['options'] = array();

$modversion['config'][11]['name'] = 'adslight_not_premium';
$modversion['config'][11]['title'] = '_MI_ADSLIGHT_NOT_PREMIUM';
$modversion['config'][11]['description'] = '_MI_ADSLIGHT_NOT_PREMIUM_DESC';
$modversion['config'][11]['formtype'] = 'textbox' ;
$modversion['config'][11]['valuetype'] = 'int' ;
$modversion['config'][11]['default'] = '1';

$modversion['config'][12]['name'] = 'adslight_nb_pict';
$modversion['config'][12]['title'] = '_MI_ADSLIGHT_NUMBPICT_TITLE';
$modversion['config'][12]['description'] = '_MI_ADSLIGHT_NUMBPICT_DESC';
$modversion['config'][12]['formtype'] = 'textbox' ;
$modversion['config'][12]['valuetype'] = 'int' ;
$modversion['config'][12]['default'] = '12';

$modversion['config'][13]['name'] = 'adslight_path_upload';
$modversion['config'][13]['title'] = '_MI_ADSLIGHT_UPLOAD_TITLE';
$modversion['config'][13]['description'] = '_MI_ADSLIGHT_UPLOAD_DESC';
$modversion['config'][13]['formtype'] = 'textbox' ;
$modversion['config'][13]['valuetype'] = 'text' ;
$modversion['config'][13]['default'] = XOOPS_ROOT_PATH.'/uploads/AdsLight/';

$modversion['config'][14]['name'] = 'adslight_link_upload';
$modversion['config'][14]['title'] = '_MI_ADSLIGHT_LINKUPLOAD_TI';
$modversion['config'][14]['description'] = '_MI_ADSLIGHT_LINKUPLOAD_DESC';
$modversion['config'][14]['formtype'] = 'textbox' ;
$modversion['config'][14]['valuetype'] = 'text' ;
$modversion['config'][14]['default'] = XOOPS_URL.'/uploads/AdsLight/';

$modversion['config'][15]['name'] = 'adslight_thumb_width';
$modversion['config'][15]['title'] = '_MI_ADSLIGHT_THUMW_TITLE';
$modversion['config'][15]['description'] = '_MI_ADSLIGHT_THUMBW_DESC';
$modversion['config'][15]['formtype'] = 'textbox' ;
$modversion['config'][15]['valuetype'] = 'text' ;
$modversion['config'][15]['default'] = '125';

$modversion['config'][16]['name'] = 'adslight_thumb_height';
$modversion['config'][16]['title'] = '_MI_ADSLIGHT_THUMBH_TITLE';
$modversion['config'][16]['description'] = '_MI_ADSLIGHT_THUMBH_DESC';
$modversion['config'][16]['formtype'] = 'textbox' ;
$modversion['config'][16]['valuetype'] = 'text' ;
$modversion['config'][16]['default'] = '175';

$modversion['config'][17]['name'] = 'adslight_resized_width';
$modversion['config'][17]['title'] = '_MI_ADSLIGHT_RESIZEDW_TITLE';
$modversion['config'][17]['description'] = '_MI_ADSLIGHT_RESIZEDW_DESC';
$modversion['config'][17]['formtype'] = 'textbox' ;
$modversion['config'][17]['valuetype'] = 'text' ;
$modversion['config'][17]['default'] = '650';

$modversion['config'][18]['name'] = 'adslight_resized_height';
$modversion['config'][18]['title'] = '_MI_ADSLIGHT_RESIZEDH_TITLE';
$modversion['config'][18]['description'] = '_MI_ADSLIGHT_RESIZEDH_DESC';
$modversion['config'][18]['formtype'] = 'textbox' ;
$modversion['config'][18]['valuetype'] = 'text' ;
$modversion['config'][18]['default'] = '450';

$modversion['config'][19]['name'] = 'adslight_max_original_width';
$modversion['config'][19]['title'] = '_MI_ADSLIGHT_ORIGW_TITLE';
$modversion['config'][19]['description'] = '_MI_ADSLIGHT_ORIGW_DESC';
$modversion['config'][19]['formtype'] = 'textbox' ;
$modversion['config'][19]['valuetype'] = 'text' ;
$modversion['config'][19]['default'] = '2048';

$modversion['config'][20]['name'] = 'adslight_max_original_height';
$modversion['config'][20]['title'] = '_MI_ADSLIGHT_ORIGH_TITLE';
$modversion['config'][20]['description'] = '_MI_ADSLIGHT_ORIGH_DESC';
$modversion['config'][20]['formtype'] = 'textbox' ;
$modversion['config'][20]['valuetype'] = 'text' ;
$modversion['config'][20]['default'] = '1600';

$modversion['config'][21]['name'] = 'adslight_maxfilesize';
$modversion['config'][21]['title'] = '_MI_ADSLIGHT_MAXFILEBYTES_T';
$modversion['config'][21]['description'] = '_MI_ADSLIGHT_MAXFILEBYTES_D';
$modversion['config'][21]['formtype'] = 'textbox' ;
$modversion['config'][21]['valuetype'] = 'text' ;
$modversion['config'][21]['default'] = '512000';

$modversion['config'][22]['name'] = 'adslight_souscat';
$modversion['config'][22]['title'] = '_MI_ADSLIGHT_DISPLSUBCAT';
$modversion['config'][22]['description'] = '_MI_ADSLIGHT_ONHOME';
$modversion['config'][22]['formtype'] = 'yesno';
$modversion['config'][22]['valuetype'] = 'int';
$modversion['config'][22]['default'] = '1';
$modversion['config'][22]['options'] = array();

$modversion['config'][23]['name'] = 'adslight_cat_desc';
$modversion['config'][23]['title'] = '_MI_ADSLIGHT_CAT_META';
$modversion['config'][23]['description'] = '_MI_ADSLIGHT_CAT_META_DESCRIPTION';
$modversion['config'][23]['formtype'] = 'yesno' ;
$modversion['config'][23]['valuetype'] = 'int' ;
$modversion['config'][23]['default'] = '1' ;
$modversion['config'][23]['options'] = array();

$modversion['config'][24]['name'] = 'adslight_nbsouscat';
$modversion['config'][24]['title'] = '_MI_ADSLIGHT_NBDISPLSUBCAT';
$modversion['config'][24]['description'] = '';
$modversion['config'][24]['formtype'] = 'textbox';
$modversion['config'][24]['valuetype'] = 'int';
$modversion['config'][24]['default'] = '4';
$modversion['config'][24]['options'] = array();

$modversion['config'][25]['name'] = 'adslight_csortorder';
$modversion['config'][25]['title'] = '_MI_ADSLIGHT_CSORT_ORDER';
$modversion['config'][25]['description'] = '';
$modversion['config'][25]['formtype'] = 'select';
$modversion['config'][25]['valuetype'] = 'text';
$modversion['config'][25]['default'] = 'title';
$modversion['config'][25]['options'] = array("_MI_ADSLIGHT_ORDREALPHA"=>'title', "_MI_ADSLIGHT_ORDREPERSO"=>'ordre');

$modversion['config'][26]['name'] = 'adslight_lsort_order';
$modversion['config'][26]['title'] = '_MI_ADSLIGHT_LSORT_ORDER';
$modversion['config'][26]['description'] = '';
$modversion['config'][26]['formtype'] = 'select';
$modversion['config'][26]['valuetype'] = 'text';
$modversion['config'][26]['default'] = 'date DESC';
$modversion['config'][26]['options'] = array("_MI_ADSLIGHT_ORDER_DATE"=>'date DESC', "_MI_ADSLIGHT_ORDER_PRICE"=>'price ASC', "_MI_ADSLIGHT_ORDER_TITLE"=>'title ASC', "_MI_ADSLIGHT_ORDER_POP"=>'hits DESC');

$modversion['config'][27]['name'] = 'adslight_diff_name';
$modversion['config'][27]['title'] = '_MI_ADSLIGHT_DIFF_NAME';
$modversion['config'][27]['description'] = '' ;
$modversion['config'][27]['formtype'] = 'yesno' ;
$modversion['config'][27]['valuetype'] = 'int' ;
$modversion['config'][27]['default'] = '0' ;
$modversion['config'][27]['options'] = array();

$modversion['config'][28]['name'] = 'adslight_diff_email' ;
$modversion['config'][28]['title'] = '_MI_ADSLIGHT_DIFF_EMAIL';
$modversion['config'][28]['description'] = '' ;
$modversion['config'][28]['formtype'] = 'yesno' ;
$modversion['config'][28]['valuetype'] = 'int' ;
$modversion['config'][28]['default'] = '0' ;
$modversion['config'][28]['options'] = array() ;

$modversion['config'][29]['name'] = 'adslight_rate_user';
$modversion['config'][29]['title'] = '_MI_ADSLIGHT_RATE_USER';
$modversion['config'][29]['description'] = '';
$modversion['config'][29]['formtype'] = 'yesno' ;
$modversion['config'][29]['valuetype'] = 'int' ;
$modversion['config'][29]['default'] = '1' ;
$modversion['config'][29]['options'] = array();

$modversion['config'][30]['name'] = 'adslight_rate_item';
$modversion['config'][30]['title'] = '_MI_ADSLIGHT_RATE_ITEM';
$modversion['config'][30]['description'] = '';
$modversion['config'][30]['formtype'] = 'yesno' ;
$modversion['config'][30]['valuetype'] = 'int' ;
$modversion['config'][30]['default'] = '1' ;
$modversion['config'][30]['options'] = array();

$modversion['config'][31]['name'] = 'adslight_use_country';
$modversion['config'][31]['title'] = '_MI_ADSLIGHT_USE_COUNTRY';
$modversion['config'][31]['description'] = '_MI_ADSLIGHT_USE_COUNTRY_DESC';
$modversion['config'][31]['formtype'] = 'yesno' ;
$modversion['config'][31]['valuetype'] = 'int' ;
$modversion['config'][31]['default'] = '1' ;
$modversion['config'][31]['options'] = array();

$modversion['config'][32]['name'] = 'adslight_form_options';
$modversion['config'][32]['title'] =  '_MI_ADSLIGHT_EDITOR';
$modversion['config'][32]['description'] =  '_MI_ADSLIGHT_LIST_EDITORS';
$modversion['config'][32]['formtype'] = 'select';
$modversion['config'][32]['valuetype'] = 'text';
$modversion['config'][32]['default'] = 'textarea';
xoops_load('xoopseditorhandler');
$editor_handler = XoopsEditorHandler::getInstance();
$modversion['config'][32]['options'] = array_flip($editor_handler->getList());

$modversion['config'][33]['name'] = 'adslight_admin_editor';
$modversion['config'][33]['title'] =  '_MI_ADSLIGHT_ADMIN_EDITOR';
$modversion['config'][33]['description'] =  '_MI_ADSLIGHT_LIST_ADMIN_EDITORS';
$modversion['config'][33]['formtype'] = 'select';
$modversion['config'][33]['valuetype'] = 'text';
$modversion['config'][33]['default'] = 'dhtmltextarea';
xoops_load('xoopseditorhandler');
$editor_handler = XoopsEditorHandler::getInstance();
$modversion['config'][33]['options'] = array_flip($editor_handler->getList());

$modversion['config'][34]['name'] = 'adslight_lightbox';
$modversion['config'][34]['title'] = '_MI_ADSLIGHT_LIGHTBOX';
$modversion['config'][34]['description'] = '_MI_ADSLIGHT_LIGHTBOX_DESC';
$modversion['config'][34]['formtype'] = 'yesno' ;
$modversion['config'][34]['valuetype'] = 'int' ;
$modversion['config'][34]['default'] = '1' ;
$modversion['config'][34]['options'] = array();

$modversion['config'][35]['name'] = 'adslight_almost';
$modversion['config'][35]['title'] = '_MI_ADSLIGHT_ALMOST';
$modversion['config'][35]['description'] = '_MI_ADSLIGHT_INDAYS';
$modversion['config'][35]['formtype'] = 'textbox' ;
$modversion['config'][35]['valuetype'] = 'int' ;
$modversion['config'][35]['default'] = '3' ;
$modversion['config'][35]['options'] = array();

$modversion['config'][36]['name'] = 'adslight_main_cat';
$modversion['config'][36]['title'] = '_MI_ADSLIGHT_MAIN_CAT';
$modversion['config'][36]['description'] = '_MI_ADSLIGHT_MAIN_CAT_DESC';
$modversion['config'][36]['formtype'] = 'yesno' ;
$modversion['config'][36]['valuetype'] = 'int' ;
$modversion['config'][36]['default'] = '1' ;
$modversion['config'][36]['options'] = array();

$modversion['config'][37]['name'] = 'adslight_use_catscode';
$modversion['config'][37]['title'] = '_MI_ADSLIGHT_CAT_DESC';
$modversion['config'][37]['description'] = '_MI_ADSLIGHT_DESC_CAT_DESC';
$modversion['config'][37]['formtype'] = 'yesno' ;
$modversion['config'][37]['valuetype'] = 'int' ;
$modversion['config'][37]['default'] = '0' ;
$modversion['config'][37]['options'] = array();

$modversion['config'][38]['name'] = 'adslight_cats_code';
$modversion['config'][38]['title'] = '_MI_ADSLIGHT_ADSLIGHT_CATS_CODE';
$modversion['config'][38]['description'] = '_MI_ADSLIGHT_ADSLIGHT_CATS_CODE_DESC';
$modversion['config'][38]['formtype'] = 'textarea';
$modversion['config'][38]['valuetype'] = 'text';
$modversion['config'][38]['default'] = '';

$modversion['config'][39]['name'] = 'adslight_use_captcha';
$modversion['config'][39]['title'] = '_MI_ADSLIGHT_USE_CAPTCHA';
$modversion['config'][39]['description'] = '_MI_ADSLIGHT_USE_CAPTCHA_DESC';
$modversion['config'][39]['formtype'] = 'yesno' ;
$modversion['config'][39]['valuetype'] = 'int' ;
$modversion['config'][39]['default'] = '1' ;
$modversion['config'][39]['options'] = array();

$modversion['config'][40]['name'] = 'active_rewriteurl';
$modversion['config'][40]['title'] = '_MI_ADSLIGHT_ACTIVE_REWRITEURL';
$modversion['config'][40]['description'] = '_MI_ADSLIGHT_ACTIVE_REWRITEURL_DESC';
$modversion['config'][40]['formtype'] = 'yesno' ;
$modversion['config'][40]['valuetype'] = 'int' ;
$modversion['config'][40]['default'] = '0' ;
$modversion['config'][40]['options'] = array();

$modversion['config'][41]['name'] = 'active_thumbsindex';
$modversion['config'][41]['title'] = '_MI_ADSLIGHT_ACTIVE_THUMBSINDEX';
$modversion['config'][41]['description'] = '_MI_ADSLIGHT_ACTIVE_THUMBSINDEX_DESC';
$modversion['config'][41]['formtype'] = 'yesno' ;
$modversion['config'][41]['valuetype'] = 'int' ;
$modversion['config'][41]['default'] = '0' ;
$modversion['config'][41]['options'] = array();

$modversion['config'][42]['name'] = 'active_thumbscats';
$modversion['config'][42]['title'] = '_MI_ADSLIGHT_ACTIVE_THUMBSCATS';
$modversion['config'][42]['description'] = '_MI_ADSLIGHT_ACTIVE_THUMBSCATS_DESC';
$modversion['config'][42]['formtype'] = 'yesno' ;
$modversion['config'][42]['valuetype'] = 'int' ;
$modversion['config'][42]['default'] = '0' ;
$modversion['config'][42]['options'] = array();

$modversion['config'][43]['name'] = 'adslight_use_index_code';
$modversion['config'][43]['title'] = '_MI_ADSLIGHT_ADSLIGHT_USE_INDEX_CODE';
$modversion['config'][43]['description'] = '_MI_ADSLIGHT_ADSLIGHT_USE_INDEX_CODE_DESC';
$modversion['config'][43]['formtype'] = 'yesno' ;
$modversion['config'][43]['valuetype'] = 'int' ;
$modversion['config'][43]['default'] = '0' ;
$modversion['config'][43]['options'] = array();

$modversion['config'][44]['name'] = 'adslight_use_banner';
$modversion['config'][44]['title'] = '_MI_ADSLIGHT_ADSLIGHT_USE_BANNER';
$modversion['config'][44]['description'] = '_MI_ADSLIGHT_ADSLIGHT_USE_BANNER_DESC';
$modversion['config'][44]['formtype'] = 'yesno' ;
$modversion['config'][44]['valuetype'] = 'int' ;
$modversion['config'][44]['default'] = '0' ;
$modversion['config'][44]['options'] = array();

$modversion['config'][45]['name'] = 'adslight_index_code';
$modversion['config'][45]['title'] = '_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE';
$modversion['config'][45]['description'] = '_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_DESC';
$modversion['config'][45]['formtype'] = 'textarea';
$modversion['config'][45]['valuetype'] = 'text';
$modversion['config'][45]['default'] = '';

$modversion['config'][46]['name'] = 'adslight_index_code_place';
$modversion['config'][46]['title'] = '_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_PLACE';
$modversion['config'][46]['description'] = '_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_PLACE_DESC';
$modversion['config'][46]['formtype'] = 'textbox' ;
$modversion['config'][46]['valuetype'] = 'text' ;
$modversion['config'][46]['default'] = '5';

$modversion['config'][47]['name'] = 'adslight_use_tipswrite';
$modversion['config'][47]['title'] = '_MI_ADSLIGHT_USE_TIPS_WRITE';
$modversion['config'][47]['description'] = '_MI_ADSLIGHT_USEDESC_TIPS_WRITE';
$modversion['config'][47]['formtype'] = 'yesno' ;
$modversion['config'][47]['valuetype'] = 'int' ;
$modversion['config'][47]['default'] = '0' ;
$modversion['config'][47]['options'] = array();

$modversion['config'][48]['name'] = 'adslight_active_menu';
$modversion['config'][48]['title'] = '_MI_ADSLIGHT_ACTIVE_MENU';
$modversion['config'][48]['description'] = '_MI_ADSLIGHT_USEDESC_ACTIVEMENU';
$modversion['config'][48]['formtype'] = 'yesno' ;
$modversion['config'][48]['valuetype'] = 'int' ;
$modversion['config'][48]['default'] = '1' ;
$modversion['config'][48]['options'] = array();

$modversion['config'][49]['name'] = 'adslight_active_rss';
$modversion['config'][49]['title'] = '_MI_ADSLIGHT_ACTIVE_RSS';
$modversion['config'][49]['description'] = '_MI_ADSLIGHT_USEDESC_ACTIVERSS';
$modversion['config'][49]['formtype'] = 'yesno' ;
$modversion['config'][49]['valuetype'] = 'int' ;
$modversion['config'][49]['default'] = '1' ;
$modversion['config'][49]['options'] = array();

$modversion['config'][50]['name'] = 'adslight_active_bookmark';
$modversion['config'][50]['title'] = '_MI_ADSLIGHT_ACTIVE_BOOKMARK';
$modversion['config'][50]['description'] = '_MI_ADSLIGHT_USEDESC_ACTIVEBOOKMARK';
$modversion['config'][50]['formtype'] = 'yesno' ;
$modversion['config'][50]['valuetype'] = 'int' ;
$modversion['config'][50]['default'] = '1' ;
$modversion['config'][50]['options'] = array();

$modversion['config'][51]['name'] = 'adslight_style_bookmark';
$modversion['config'][51]['title'] = '_MI_ADSLIGHT_STBOOKMARK';
$modversion['config'][51]['description'] = '_MI_ADSLIGHT_DESC_STBOOKMARK' ;
$modversion['config'][51]['formtype'] = 'select' ;
$modversion['config'][51]['valuetype'] = 'int' ;
$modversion['config'][51]['default'] = '1' ;
$modversion['config'][51]['options'] = array('1'=>1, '2'=>2, '3'=>3) ;

$modversion['config'][52]['name'] = 'adslight_tips_writetitle';
$modversion['config'][52]['title'] = '_MI_ADSLIGHT_TITLE_TIPS_WRITE';
$modversion['config'][52]['description'] = '_MI_ADSLIGHT_TITLEDESC_TIPS_WRITE';
$modversion['config'][52]['formtype'] = 'textbox' ;
$modversion['config'][52]['valuetype'] = 'text' ;
$modversion['config'][52]['default'] = 'Les conseils';

$modversion['config'][53]['name'] = 'adslight_tips_writetxt';
$modversion['config'][53]['title'] = '_MI_ADSLIGHT_TEXT_TIPS_WRITE';
$modversion['config'][53]['description'] = '_MI_ADSLIGHT_TEXTDESC_TIPS_WRITE';
$modversion['config'][53]['formtype'] = 'textarea';
$modversion['config'][53]['valuetype'] = 'text';
$modversion['config'][53]['default'] = 'Votre texte ici';

$modversion['config'][54]['name'] = 'adslight_active_xpayment';
$modversion['config'][54]['title'] = '_MI_ADSLIGHT_ACTIVE_XPAYMENT';
$modversion['config'][54]['description'] = '_MI_ADSLIGHT_TEXTDESC_XPAYMENT';
$modversion['config'][54]['formtype'] = 'yesno' ;
$modversion['config'][54]['valuetype'] = 'int' ;
$modversion['config'][54]['default'] = '0' ;
$modversion['config'][54]['options'] = array();

//Notifications
$modversion['hasNotification'] = 1;

$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'adslight_notify_iteminfo';

//Catégories
$modversion['notification']['category'][1]['name'] = 'category';
$modversion['notification']['category'][1]['title'] = _MI_ADSLIGHT_CATEGORY_NOTIFY;
$modversion['notification']['category'][1]['description'] = _MI_ADSLIGHT_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = 'viewcats.php';
$modversion['notification']['category'][1]['item_name'] = 'cid';
$modversion['notification']['category'][1]['allow_bookmark'] = 0;
$modversion['notification']['category'][1]['extraParams'] = array('pa');

$modversion['notification']['category'][2]['name'] = 'listing';
$modversion['notification']['category'][2]['title'] = _MI_ADSLIGHT_NOTIFY;
$modversion['notification']['category'][2]['description'] = _MI_ADSLIGHT_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = 'index.php';
$modversion['notification']['category'][2]['item_name'] = 'lid';
$modversion['notification']['category'][2]['allow_bookmark'] = 0;
$modversion['notification']['category'][2]['extraParams'] = array('pa');

$modversion['notification']['category'][3]['name'] = 'global';
$modversion['notification']['category'][3]['title'] = _MI_ADSLIGHT_GLOBAL_NOTIFY;
$modversion['notification']['category'][3]['description'] = _MI_ADSLIGHT_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = 'index.php';
$modversion['notification']['category'][3]['extraParams'] = array('pa');



// AdsLight notifications nouvels annonces dans cette catégories
$modversion['notification']['event'][1]['name'] = 'new_listing';
$modversion['notification']['event'][1]['category'] = 'category';
$modversion['notification']['event'][1]['title'] = _MI_ADSLIGHT_NEWPOST_NOTIFY;
$modversion['notification']['event'][1]['caption'] = _MI_ADSLIGHT_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][1]['description'] = _MI_ADSLIGHT_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'listing_newpost_notify';
$modversion['notification']['event'][1]['mail_subject'] = _MI_ADSLIGHT_NEWPOST_NOTIFYSBJ;
 
// AdsliGht Nouvel annonce dans toute les catégories
$modversion['notification']['event'][2]['name'] = 'new_listing';
$modversion['notification']['event'][2]['category'] = 'global';
$modversion['notification']['event'][2]['title'] = _MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFY;
$modversion['notification']['event'][2]['caption'] = _MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][2]['description'] = _MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'listing_newpost_notify';
$modversion['notification']['event'][2]['mail_subject'] = _MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYSBJ;