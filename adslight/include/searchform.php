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
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
// create form
$search_form = new XoopsThemeForm(_SR_SEARCH, "search", "search.php", 'get');
$mids = $xoopsModule->getVar('mid');
$mid = $xoopsModule->getVar('name');

// create form elements
$search_form->addElement(new XoopsFormText(_SR_KEYWORDS, "query", 30, 255, htmlspecialchars(stripslashes(implode(" ", $queries)), ENT_QUOTES)), true);
$type_select = new XoopsFormSelect(_SR_TYPE, "andor", $andor);
$type_select->addOptionArray(array("AND"=>_SR_ALL, "OR"=>_SR_ANY, "exact"=>_SR_EXACT));
$search_form->addElement($type_select);



if (!empty($mids)) {
  $mods_checkbox = new XoopsFormCheckBox(_SR_SEARCHIN, "mids[]", $mids);
}
if (empty($modules)) {

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('hassearch', 1));
    $criteria->add(new Criteria('isactive', 1));
 
    $module_handler =& xoops_gethandler('module');
    $mods_checkbox->addOptionArray($module_handler->getList($criteria));
    
    
} else {
    foreach ($modules as $mids => $module) {
        $module_array[$mids] = $module->getVar('name');
    }
$mods_checkbox->addOptionArray($module_array);
}
$search_form->addElement($mods_checkbox);
if ($xoopsConfigSearch['keyword_min'] > 0) {
	$search_form->addElement(new XoopsFormLabel(_SR_SEARCHRULE, sprintf(_SR_KEYIGNORE, $xoopsConfigSearch['keyword_min'])));
}
$search_form->addElement(new XoopsFormHidden("action", "results"));
$search_form->addElement(new XoopsFormHiddenToken('id'));
$search_form->addElement(new XoopsFormButton("", "submit", _SR_SEARCH, "submit"));