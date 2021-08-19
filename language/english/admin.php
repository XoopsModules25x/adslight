<?php

declare(strict_types=1);
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops

        Redesigned and ameliorate By iluc user at www.frxoops.org
        Started with the Classifieds module and made MANY changes
        Website : http://www.limonads.com
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
define('_AM_ADSLIGHT_CONF', 'Ads Configuration');
define('_AM_ADSLIGHT_ADDON', 'Addons');
define('_AM_ADSLIGHT_ANNDEL', 'Selected advertisements have been deleted');
define('_AM_ADSLIGHT_ADDCATPRINC', 'Add a main category');
define('_AM_ADSLIGHT_CATNAME', 'Name:');
define('_AM_ADSLIGHT_ADDSUBCAT', 'Add a sub-category');
define('_AM_ADSLIGHT_DELSUBCAT', 'Delete sub-category');
define('_AM_ADSLIGHT_MODIFSUBCAT', 'Edit a sub-category');
define('_AM_ADSLIGHT_ADD', 'Add');
define('_AM_ADSLIGHT_CATDEL', 'Selected category has been deleted');
//define('_AM_ADSLIGHT_OUI', 'Yes');
//define('_AM_ADSLIGHT_NON', 'No');
define('_AM_ADSLIGHT_SURDELCAT', 'WARNING: Are you sure you want to remove this category, and all subcategories and advertisement.');
define('_AM_ADSLIGHT_IN', 'in');
define('_AM_ADSLIGHT_MODIF', 'Modify');
define('_AM_ADSLIGHT_MODIFCAT', 'Modify a category');
define('_AM_ADSLIGHT_CAT', 'Category:');
define('_AM_ADSLIGHT_SUBCAT', 'Subcategory:');
define('_AM_ADSLIGHT_CONFMYA', 'Ads Configuration');
define('_AM_ADSLIGHT_CATADD', 'Category added');
define('_AM_ADSLIGHT_SUBCATADD', 'Subcategory added');
define('_AM_ADSLIGHT_ANNMOD', 'Advertisement has been changed');
define('_AM_ADSLIGHT_NOANNVAL', 'There are currently no advertisements to approve');
define('_AM_ADSLIGHT_NOMODACTIV', 'Moderation is not active, no advertisements to approve');
define('_AM_ADSLIGHT_MODANN', 'Change an advertisement');
define('_AM_ADSLIGHT_ALLMODANN', '(All ads can be modified by the Ads administrator.)');
// Message
define('_AM_ADSLIGHT_HELLO', 'Hello ');
define('_AM_ADSLIGHT_ANNVALID', 'Advertisement has been approved');
define('_AM_ADSLIGHT_DEL', 'Delete');
define('_AM_ADSLIGHT_SAVMOD', 'Save your changes');
define('_AM_ADSLIGHT_MODTYPE', 'Modify Ad Type');
define('_AM_ADSLIGHT_ANNACCEPT', 'Your advertisement has been approved');
define('_AM_ADSLIGHT_CONSULTTO', 'Please contact:');
define('_AM_ADSLIGHT_TEAMOF', 'Team');
// End message
define('_AM_ADSLIGHT_RETURN', 'Return');
define('_AM_ADSLIGHT_MODSUBCAT', 'Change subcategory name');
define('_AM_ADSLIGHT_MODCAT', 'Change main category name');
define('_AM_ADSLIGHT_GO', 'Go');
define('_AM_ADSLIGHT_SENDBY', 'Added By:');
define('_AM_ADSLIGHT_EMAIL', 'Email:');
define('_AM_ADSLIGHT_TEL', 'Telephone:');
define('_AM_ADSLIGHT_TOWN', 'Town:');
define('_AM_ADSLIGHT_COUNTRY', 'Country:');
define('_AM_ADSLIGHT_TITLE2', 'Title:');
define('_AM_ADSLIGHT_TYPE', 'Type:');
define('_AM_ADSLIGHT_TYPE_CONDITION', 'The item condition:');
define('_AM_ADSLIGHT_PRICE2', 'Price:');
define('_AM_ADSLIGHT_CAT2', 'Category:');
define('_AM_ADSLIGHT_DESCRIPTION', 'Google adsense code or code of a banner:<br>Format: width = 300 height = 250');
define('_AM_ADSLIGHT_THEREIS', 'There are ');
define('_AM_ADSLIGHT_WAIT', 'Ads waiting to be moderated');
define('_AM_ADSLIGHT_ADDTYPE', 'Add Ad type');
define('_AM_ADSLIGHT_ERRORTYPE', 'ERROR: type');
define('_AM_ADSLIGHT_EXIST', 'already exists!');
define('_AM_ADSLIGHT_ERRORCAT', 'ERROR: Category');
define('_AM_ADSLIGHT_ERRORSUBCAT', 'ERROR: Subcategory');
define('_AM_ADSLIGHT_TYPEMOD', 'The ad type has been modified');
define('_AM_ADSLIGHT_TYPEDEL', 'The ad type has been deleted');
define('_AM_ADSLIGHT_ADDTYPE2', 'Ad type has been added');
define('_AM_ADSLIGHT_ACCESMYANN', 'Ads');
define('_AM_ADSLIGHT_IMGCAT', 'Image:');
define('_AM_ADSLIGHT_REPIMGCAT', 'Image directory:');
define('_AM_ADSLIGHT_GESTCAT', 'Category Maintenance');
//Condition of payment
define('_AM_ADSLIGHT_ADDPRICE', 'Add a price type');
define('_AM_ADSLIGHT_MODPRICE', 'Modify a price type');
define('_AM_ADSLIGHT_ADDPRICE2', 'Price type has been added');
define('_AM_ADSLIGHT_PRICEMOD', 'Price type has been modified');
define('_AM_ADSLIGHT_PRICEDEL', 'Price type has been deleted');
define('_AM_ADSLIGHT_ORDER', 'Sort:');
define('_AM_ADSLIGHT_ORDERCLASS', 'Category Order:');
define('_AM_ADSLIGHT_ORDERALPHA', 'Sort alphabetically');
define('_AM_ADSLIGHT_ORDERPERSO', 'Personalised Order');
define('_AM_ADSLIGHT_BIGCAT', 'Main Category');
define(
    '_AM_ADSLIGHT_HELP1',
    '<b>To add a category:</b> click on the image <img src="'
    . XOOPS_URL
    . '/modules/adslight/assets/images/plus.gif" border=0 width=10 height=10 alt="Add a category"> alongside the category you want to add the category under.<p><b>To change or delete a category:</b> click on the name of the category'
);
define('_AM_ADSLIGHT_HELP2', '<B>Category Order:</B> integer in brackets corresponds to the order within the superior category or of the principal category. Negative integers can be used.: -1');
// fichier pref.php //
define('_AM_ADSLIGHT_CONFSAVE', 'Configuration saved');
define('_AM_ADSLIGHT_ANNOCANPOST', 'Anonymous user can post ads:');
define('_AM_ADSLIGHT_PERPAGE', 'Ads per page:');
define('_AM_ADSLIGHT_CURRENCY_SYMBOL', 'Currency symbol:');
define('_AM_ADSLIGHT_NUMNEW', 'Number of new ads:');
define('_AM_ADSLIGHT_MODERAT', 'Moderate Ads:');
define('_CAL_MAXIIMGS', 'Maximum photo size:');
define('_AM_ADSLIGHT_TIMEANN', 'Ad duration:');
define('_AM_ADSLIGHT_INOCTET', 'in bytes');
define('_AM_ADSLIGHT_INDAYS', 'in days');
define('_AM_ADSLIGHT_TYPEBLOC', 'Type of Block:');
define('_AM_ADSLIGHT_ANNRAND', 'Random Ad');
define('_AM_ADSLIGHT_LASTTEN', 'Last 10 Ads');
define('_AM_ADSLIGHT_NEWTIME', 'New Ads from:');
define('_AM_ADSLIGHT_DISPLPRICE', 'Display price:');
define('_AM_ADSLIGHT_DISPLPRICE2', 'Display price:');
define('_AM_ADSLIGHT_INTHISCAT', 'in this category');
define('_AM_ADSLIGHT_DISPLSUBCAT', 'Display subcategories:');
define('_AM_ADSLIGHT_ONHOME', 'on the Front Page of Module');
define('_AM_ADSLIGHT_NBDISPLSUBCAT', 'Number of subcategories to show:');
define('_AM_ADSLIGHT_IF', 'if');
define('_AM_ADSLIGHT_ISAT', 'is at');
define('_AM_ADSLIGHT_VIEWNEWCLASS', 'Show new ads:');
define('_AM_ADSLIGHT_PERMADDNG', 'Could not add %s permission to %s for group %s');
define('_AM_ADSLIGHT_PERMADDOK', 'Added %s permission to %s for group %s');
define('_AM_ADSLIGHT_PERMRESETNG', 'Could not reset group permission for module %s');
define('_AM_ADSLIGHT_PERMADDNGP', 'All parent items must be selected.');
define('_AM_ADSLIGHT_EXPIRE', 'Days listing will last.');
define('_AM_ADSLIGHT_DBUPDATED', 'The database has been updated.');
define('_AM_ADSLIGHT_CONTACT_BY_EMAIL', 'E-mail');
define('_AM_ADSLIGHT_CONTACT_BY_PM', 'Private Message(PM)');
define('_AM_ADSLIGHT_CONTACT_BY_BOTH', 'Both E-mail or PM');
define('_AM_ADSLIGHT_CONTACT_BY_PHONE', 'By phone only');
define('_AM_ADSLIGHT_CONTACTBY', 'Contact by:');
define('_AM_ADSLIGHT_PREMIUM', 'Premium Listing:');
define('_AM_ADSLIGHT_OK', 'Approve');
define('_AM_ADSLIGHT_CATSMOD', 'Category has been Modified');
define('_AM_ADSLIGHT_ADDED_ON', ' added on ');
define('_AM_ADSLIGHT_NUMANN', 'Listing No.:');
define('_AM_ADSLIGHT_ACTIVE', 'Active');
define('_AM_ADSLIGHT_INACTIVE', 'Inactive');
define('_AM_ADSLIGHT_SOLD', 'Reserved');
define('_AM_ADSLIGHT_STATUS', 'Status');
define('_AM_ADSLIGHT_UPDATECOMPLETE', 'Update Complete');
define('_AM_ADSLIGHT_UPDATEMODULE', '<b>Update Module</b>');
define('_AM_ADSLIGHT_UPGRADEFAILED', 'Update Failed');
//define('_AM_ADSLIGHT_UPGRADEFAILED0', 'Update');
define('_AM_ADSLIGHT_UPGR_ACCESS_ERROR', 'Access Error');
define('_AM_ADSLIGHT_WEBMASTER', 'Webmaster');
define('_AM_ADSLIGHT_YOUR_AD', 'Your ad');
define('_AM_ADSLIGHT_AT', 'at');
define('_AM_ADSLIGHT_VEDIT_AD', 'You can view or edit your ad here');
define('_AM_ADSLIGHT_YOUR_AD_ON', 'Your ad on');
define('_AM_ADSLIGHT_APPROVED', 'has been approved.');
define('_AM_ADSLIGHT_EXPIRED', 'has expired and has been deleted.');
define('_AM_ADSLIGHT_CHECKER', 'Directory Permission Checker');
define('_AM_ADSLIGHT_DIRPERMS', 'Change Directory Permission to writable ! => ');
define('_AM_ADSLIGHT_PHOTO1', 'Number of Photos:');
define('_AM_ADSLIGHT_SUBMITTER', 'Submitted by');
define('_AM_ADSLIGHT_NBR_PHOTO', 'Photo(s)');
define('_AM_ADSLIGHT_TITLE', 'Title');
define('_AM_ADSLIGHT_LID', 'ID');
define('_AM_ADSLIGHT_DATE', 'Date Added');
define('_AM_ADSLIGHT_DESC', 'Description');
define('_AM_ADSLIGHT_FREECAT', 'This will be a free category');
define('_AM_ADSLIGHT_MODERATE_CAT', 'Moderate this category');
define('_AM_ADSLIGHT_VISIT_LINK', 'You can view the full ad at the link below:');
define('_AM_ADSLIGHT_LISTING_NUMBER', 'Listing Number ');
define('_AM_ADSLIGHT_YOU_CAN_VIEW_BELOW', 'You can view the full Listing at the link below');
define('_AM_ADSLIGHT_NOREPLY', '!!!  Do not reply to this e-mail, you will not get a reply.  !!!');
define('_AM_ADSLIGHT_ADDED_TO_CAT', 'A new listing has been added to the category ');
define('_AM_ADSLIGHT_RECIEVING_NOTIF', 'You have subscribed to receive notifications of this sort.');
define('_AM_ADSLIGHT_ERROR_NOTIF', 'If this is an error or you wish not to receive further such notifications, please update your subscriptions by visiting the link below:');
define('_AM_ADSLIGHT_FOLLOW_LINK', 'Here is a link to the new listing');
define('_AM_ADSLIGHT_CAPTCHA', 'Security Code:');
define('_AM_ADSLIGHT_MODERATE_SUBCATS', 'Moderate subcats of this category');
define('_AM_ADSLIGHT_DOCUMENTATION', 'Documentation');
define('_AM_ADSLIGHT_FEATURES', 'Features');
define('_AM_ADSLIGHT_CLONE', 'How to Clone');
define('_AM_ADSLIGHT_INCOMPLETE', 'Incomplete');
// Added by iLuc //
//Item Condition 
define('_AM_ADSLIGHT_ADDCONDITION', 'Add item condition type, e.g. Damaged');
define('_AM_ADSLIGHT_MODCONDITION', 'Change item condition type');
define('_AM_ADSLIGHT_ADDCONDITION2', 'The item condition type has been added');
define('_AM_ADSLIGHT_CONDITIONMOD', 'The item condition type was changed');
define('_AM_ADSLIGHT_CONDITIONDEL', 'The item condition type has been deleted');
//about.php
define('_AM_ADSLIGHT_ABOUT_AUTHOR', 'Author');
define('_AM_ADSLIGHT_ABOUT_CHANGELOG', 'Change log');
define('_AM_ADSLIGHT_ABOUT_CREDITS', 'Credits');
define('_AM_ADSLIGHT_ABOUT_LICENSE', 'License');
define('_AM_ADSLIGHT_ABOUT_MODULEINFOS', 'Information Module');
define('_AM_ADSLIGHT_ABOUT_MODULEWEBSITE', 'Website Support');
define('_AM_ADSLIGHT_ABOUT_RELEASEDATE', 'Release Date');
define('_AM_ADSLIGHT_ABOUT_STATUS', 'Status');
define('_AM_ADSLIGHT_PERSONS_PARTICIPATED', 'Persons who participated in the improvement of the module');
define('_AM_ADSLIGHT_PERSONS_PARTICIP_NAME', 'Nickname');
define('_AM_ADSLIGHT_PERSONS_PARTICIP_WEBSITE', 'Website');
define('_AM_ADSLIGHT_PERSONS_PARTICIP_VERSION', 'Version');
define('_AM_ADSLIGHT_PERSONS_PARTICIP_DESC', 'Overview');
//groupperms.php
define('_AM_ADSLIGHT_GPERM_G_ADD', 'Can add');
define('_AM_ADSLIGHT_CAT2GROUPDESC', 'Check the categories which allow you to access');
define('_AM_ADSLIGHT_GROUPPERMDESC', 'Select groups allowed to submit ads.');
define('_AM_ADSLIGHT_GROUPPERM', 'Permission to submit');
define('_AM_ADSLIGHT_SUBMITFORM', 'Permission to file a listing');
define('_AM_ADSLIGHT_SUBMITFORM_DESC', 'Select, which can display ads');
define('_AM_ADSLIGHT_VIEWFORM', 'Permissions to view ads');
define('_AM_ADSLIGHT_VIEWFORM_DESC', 'Select groups that can see listings');
define('_AM_ADSLIGHT_VIEW_RESUMEFORM_DESC', 'Select, who can view resumes');
define('_AM_ADSLIGHT_SUPPORT', 'Support this program');
define('_AM_ADSLIGHT_OP', 'Read my review');
define('_AM_ADSLIGHT_PREMIUM_DESC', 'Choose groups that can select the duration of advertising is');
//Release Test
define('_AM_ADSLIGHT_RELEASEOK', 'You are using the latest version of the module.');
define('_AM_ADSLIGHT_RELEASEISNOTOK', 'Your module is outdated, a new version is available.');
define('_AM_ADSLIGHT_RELEASEDOWNLOAD', 'Download.');
//define('_AM_ADSLIGHT_NBR_PHOTO', "Photo(s)");
//Version 1.05
// Méta Description / keywords Categories
define('_AM_ADSLIGHT_CAT_META_DESCRIPTION', '<strong>Meta Description:</strong><br>For better optimization,</b>add here a precise description of your class. ');
define('_AM_ADSLIGHT_CAT_META_KEYWORDS', "<strong>Meta keywords:</strong><br>For better SEO,</b>add here a precise description of your category..<br><strong><span style='color:#ff3300;'>separate words by commas</span></strong>. ( english, spanish, french,...)");
//Version 1.053
// Test Maps Xml
define('_AM_ADSLIGHT_XMLNOTOK', 'The xml file for the card is absent, or else does not match the card selected in the preferences.');
define('_AM_ADSLIGHT_XMLUPGRADE', 'Update xml');
//Version 1.054
// Support_forum.php
define('_AM_ADSLIGHT_SUPPORTFORUM_TITLE', 'List Support forum');
define('_AM_ADSLIGHT_SUPPORTFORUM_WEBLINKS', 'Link');
define('_AM_ADSLIGHT_SUPPORTFORUM_CONTRYLANG', 'Language');
define('_AM_ADSLIGHT_SUPPORTFORUM_DESC', 'Description');
define('_AM_ADSLIGHT_SUPPORTFORUM_TRANSLATE', 'Translation');
////////////////////////////
//// Version 1.06 //////////
// ../admin/index.php
// Stat
define('_AM_ADSLIGHT_STAT_TITLE', 'Statistics');
define('_AM_ADSLIGHT_STAT_NUM1', 'Ads');
define('_AM_ADSLIGHT_STAT_NUM2', 'Categories');
define('_AM_ADSLIGHT_STAT_NUM3', 'Users');
define('_AM_ADSLIGHT_STAT_NUM4', 'Comments');
// Option Menu
define('_AM_ADSLIGHT_USERMENU_TITLE', 'Options');
define('_AM_ADSLIGHT_USERMENU_SENDMAIL', ' Send email');
define('_AM_ADSLIGHT_USERMENU_COMMENT', ' Comments');
define('_AM_ADSLIGHT_USERMENU_BAMMIER', ' Banners');
// Menu Category
define('_AM_ADSLIGHT_CATMENU_TITLE', 'Categories');
define('_AM_ADSLIGHT_CATMENU_CATEGORY', ' Add categories');
define('_AM_ADSLIGHT_CATMENU_MODIFCAT', ' Category Management');
// Menu Annonces
define('_AM_ADSLIGHT_ADSMENU_TITLE', 'Ads');
define('_AM_ADSLIGHT_ADSMENU_VALIDADS', ' Validate ads');
define('_AM_ADSLIGHT_ADSMENU_VIEWADS', ' View ads');
define('_AM_ADSLIGHT_ADS_MODIFADS', ' Edit Ads');
// Menu Downloads
define('_AM_ADSLIGHT_DOWNLOADS_TITLE', 'Downloading');
define('_AM_ADSLIGHT_DOWNLOADS_PLUGINS', ' Plugins');
define('_AM_ADSLIGHT_DOWNLOADS_MAPS', ' Maps');
// Menu Devellopment
define('_AM_ADSLIGHT_DEVLLP_TITLE', 'Development');
define('_AM_ADSLIGHT_DEVLLP_HACK', ' Suggest a Hack');
define('_AM_ADSLIGHT_DEVLLP_TRANSLATE', ' Submit Translation');
define('_AM_ADSLIGHT_DEVLLP_CORRECTION', ' Suggest a correction');
define('_AM_ADSLIGHT_DEVLLP_MAPFLASH', ' Submit a map (.Swf)');
define('_AM_ADSLIGHT_DEVLLP_FORUM', ' Forum');
// Menu Faire un Don
define('_AM_ADSLIGHT_DONATE_TITLE', ' Donate');
define('_AM_ADSLIGHT_DONATE', 'AdsLight its use is free and will remain so.<br>You can also make a donation<br>If you want support me.<br>');
////RSS Forum
define('_AM_ADSLIGHT_MENURSSFORUM_TITLE', 'Support forum AdsLight');
define('_AM_ADSLIGHT_MENURSSFORUM_URL', 'http://www.i-luc.fr/adslight/modules/newbb/rss.php?f=56');
define('_AM_ADSLIGHT_MENURSSFORUM_LINK1', 'http://www.i-luc.fr/adslight/modules/newbb/viewforum.php?forum=56/#googtrans/auto/en');
define('_AM_ADSLIGHT_MENURSSFORUM_GOFORUM', 'Go to Forum');
define('_AM_ADSLIGHT_MENURSSFORUM_LINK2', 'http://www.i-luc.fr/adslight/modules/profile/register.php#googtrans/auto/en');
define('_AM_ADSLIGHT_MENURSSFORUM_SUBSCRIT', 'Register');
////    RSS AdsLight News
define('_AM_ADSLIGHT_MENURSS_TITLE', 'News AdsLight');
// ../admin/view_ads.php
define('_AM_ADSLIGHT_ADSVALIDE', 'Ads valid');
define('_AM_ADSLIGHT_NOANNVALADS', 'There are currently no ads');
define('_AM_ADSLIGHT_CONDITION', 'Condition');
// Logo Paypall Donate
define(
    '_AM_ADSLIGHT_DONATE_LOGO',
    '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>
'
);
// AdsLight 2
// Block Admin New Annonces
define('_AM_ADSLIGHT_ADSMENU_NEW', 'New in this version');
define(
    '_AM_ADSLIGHT_ADSMENU_NEWTXT',
    '<b>AdsLight Version 2:</b><br>
<br><b>- Bookmark: </b> you can now enable the bookmarks in the ads.<br>
<br><b>- Template Announcements: </b> template ads has improved.<br>
<br><b>- Google Maps: </b> Adding Google Map in the ads.<br>
<br><b>- RSS Feeds: </b>The RSS feed is available by categories.<br>
<br><b>- Menu: </b> Optimization menu. You can disable it in the preferences modules. <br>
<br><b>- Files Languages: </b>  the files languages ??(English) have been improved.<br>
<br><b>- SEO: </b> the module has been improved for a better ranking..<br>
<br><b>- Hack Xpayment: </b> The module can be used with Xpayment. (Thanks Simon Roberts)<br>
'
);
// Block Admin Plugin
define('_AM_ADSLIGHT_ADSMENU_PLUGIN', 'List of Plugins');
define('_AM_ADSLIGHT_SEND_PLUGIN', 'Send a Plugin');
define('_AM_ADSLIGHT_SEND_TRANSLATION', 'Send Translation');

define('_AM_ADSLIGHT_ACTIONS', 'Actions');
define('_AM_ADSLIGHT_ERRORPRICE', 'Price error');
define('_AM_ADSLIGHT_ERRORCONDITION', 'Sorry, we have encountered an Error');
//2.2
define('_AM_ADSLIGHT_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_ADSLIGHT_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_ADSLIGHT_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_ADSLIGHT_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_ADSLIGHT_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_ADSLIGHT_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_ADSLIGHT_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');

//new
//Buttons
define('AM_ADSLIGHT_ADD_LISTING', 'Add new Listing');
define('AM_ADSLIGHT_LISTING_LIST', 'List of Listing');

// Listing
define('AM_ADSLIGHT_LISTING_ADD', 'Add a listing');
define('AM_ADSLIGHT_LISTING_EDIT', 'Edit listing');
define('AM_ADSLIGHT_LISTING_DELETE', 'Delete listing');
define('AM_ADSLIGHT_LISTING_LID', 'ID');
define('AM_ADSLIGHT_LISTING_CID', 'Category');
define('AM_ADSLIGHT_LISTING_TITLE', 'Title');
define('AM_ADSLIGHT_LISTING_STATUS', 'Status');
define('AM_ADSLIGHT_LISTING_EXPIRE', 'Expire');
define('AM_ADSLIGHT_LISTING_TYPE', 'Type');
define('AM_ADSLIGHT_LISTING_DESCTEXT', 'Description');
define('AM_ADSLIGHT_LISTING_TEL', 'Tel');
define('AM_ADSLIGHT_LISTING_PRICE', 'Price');
define('AM_ADSLIGHT_LISTING_TYPEPRICE', 'Type price');
define('AM_ADSLIGHT_LISTING_TYPECONDITION', 'Condition Type');
define('AM_ADSLIGHT_LISTING_DATE', 'Date');
define('AM_ADSLIGHT_LISTING_EMAIL', 'Email');
define('AM_ADSLIGHT_LISTING_SUBMITTER', 'Submitter');
define('AM_ADSLIGHT_LISTING_USID', 'Zip');
define('AM_ADSLIGHT_LISTING_TOWN', 'Town');
define('AM_ADSLIGHT_LISTING_COUNTRY', 'Country');
define('AM_ADSLIGHT_LISTING_CONTACTBY', 'Contactby');
define('AM_ADSLIGHT_LISTING_PREMIUM', 'Premium');
define('AM_ADSLIGHT_LISTING_VALID', 'Valid');
define('AM_ADSLIGHT_LISTING_PHOTO', 'Photo');
define('AM_ADSLIGHT_LISTING_HITS', 'Hits');
define('AM_ADSLIGHT_LISTING_ITEM_RATING', 'ItemRating');
define('AM_ADSLIGHT_LISTING_ITEM_VOTES', 'ItemVotes');
define('AM_ADSLIGHT_LISTING_USER_RATING', 'USer Rating');
define('AM_ADSLIGHT_LISTING_USER_VOTES', 'UserVotes');
define('AM_ADSLIGHT_LISTING_COMMENTS', 'Comments');
define('AM_ADSLIGHT_LISTING_REMIND', 'Remind');
//General
define('AM_ADSLIGHT_FORMOK', 'Registered successfull');
define('AM_ADSLIGHT_FORMDELOK', 'Deleted successfull');
define('AM_ADSLIGHT_FORMSUREDEL', "Are you sure to Delete: <span class='bold red'>%s</span></b>");
define('AM_ADSLIGHT_FORMSURERENEW', "Are you sure to Renew: <span class='bold red'>%s</span></b>");
define('AM_ADSLIGHT_FORMUPLOAD', 'Upload');
define('AM_ADSLIGHT_FORMIMAGE_PATH', 'File presents in %s');
define('AM_ADSLIGHT_FORM_ACTION', 'Action');
define('AM_ADSLIGHT_SELECT', 'Select action for selected item(s)');
define('AM_ADSLIGHT_SELECTED_DELETE', 'Delete selected item(s)');
define('AM_ADSLIGHT_SELECTED_ACTIVATE', 'Activate selected item(s)');
define('AM_ADSLIGHT_SELECTED_DEACTIVATE', 'De-activate selected item(s)');
define('AM_ADSLIGHT_SELECTED_ERROR', 'You selected nothing to delete');
define('AM_ADSLIGHT_CLONED_OK', 'Record cloned successfully');
define('AM_ADSLIGHT_CLONED_FAILED', 'Cloning of the record has failed');

// Type
define('AM_ADSLIGHT_TYPE_ADD', 'Add a type');
define('AM_ADSLIGHT_TYPE_EDIT', 'Edit type');
define('AM_ADSLIGHT_TYPE_DELETE', 'Delete type');
define('AM_ADSLIGHT_TYPE_ID_TYPE', 'ID');
define('AM_ADSLIGHT_TYPE_NOM_TYPE', 'Name');

// Itemvotes
define('AM_ADSLIGHT_ITEMVOTES_ADD', 'Add a itemvotes');
define('AM_ADSLIGHT_ITEMVOTES_EDIT', 'Edit itemvotes');
define('AM_ADSLIGHT_ITEMVOTES_DELETE', 'Delete itemvotes');
define('AM_ADSLIGHT_ITEMVOTES_RATINGID', 'ID');
define('AM_ADSLIGHT_ITEMVOTES_LID', 'Listing');
define('AM_ADSLIGHT_ITEMVOTES_RATINGUSER', 'Ratinguser');
define('AM_ADSLIGHT_ITEMVOTES_RATING', 'Rating');
define('AM_ADSLIGHT_ITEMVOTES_RATINGHOSTNAME', 'Ratinghostname');
define('AM_ADSLIGHT_ITEMVOTES_RATINGTIMESTAMP', 'Ratingtimestamp');
// Uservotes
define('AM_ADSLIGHT_USERVOTES_ADD', 'Add a uservotes');
define('AM_ADSLIGHT_USERVOTES_EDIT', 'Edit uservotes');
define('AM_ADSLIGHT_USERVOTES_DELETE', 'Delete uservotes');
define('AM_ADSLIGHT_USERVOTES_RATINGID', 'ID');
define('AM_ADSLIGHT_USERVOTES_USID', 'User');
define('AM_ADSLIGHT_USERVOTES_RATINGUSER', 'Ratinguser');
define('AM_ADSLIGHT_USERVOTES_RATING', 'Rating');
define('AM_ADSLIGHT_USERVOTES_RATINGHOSTNAME', 'Ratinghostname');
define('AM_ADSLIGHT_USERVOTES_RATINGTIMESTAMP', 'Ratingtimestamp');


//Index
define('AM_ADSLIGHT_STATISTICS', 'Adslight statistics');
define('AM_ADSLIGHT_THEREARE_LISTING', "There are <span class='bold'>%s</span> Listing in the database");
define('AM_ADSLIGHT_THEREARE_CATEGORIES', "There are <span class='bold'>%s</span> Categories in the database");
define('AM_ADSLIGHT_THEREARE_TYPE', "There are <span class='bold'>%s</span> Type in the database");
define('AM_ADSLIGHT_THEREARE_PRICE', "There are <span class='bold'>%s</span> Price in the database");
define('AM_ADSLIGHT_THEREARE_CONDITION', "There are <span class='bold'>%s</span> Condition in the database");
define('AM_ADSLIGHT_THEREARE_IPLOG', "There are <span class='bold'>%s</span> IPlog in the database");
define('AM_ADSLIGHT_THEREARE_ITEMVOTES', "There are <span class='bold'>%s</span> ItemVotes in the database");
define('AM_ADSLIGHT_THEREARE_USERVOTES', "There are <span class='bold'>%s</span> UserVotes in the database");
define('AM_ADSLIGHT_THEREARE_PICTURES', "There are <span class='bold'>%s</span> Pictures in the database");
define('AM_ADSLIGHT_THEREARE_REPLIES', "There are <span class='bold'>%s</span> Replies in the database");

//Buttons
//define('AM_ADSLIGHT_ADD_LISTING', 'Add new Listing');
//define('AM_ADSLIGHT_LISTING_LIST', 'List of Listing');
define('AM_ADSLIGHT_ADD_CATEGORIES', 'Add new Categories');
define('AM_ADSLIGHT_CATEGORIES_LIST', 'List of Categories');
define('AM_ADSLIGHT_ADD_TYPE', 'Add new Type');
define('AM_ADSLIGHT_TYPE_LIST', 'List of Type');
define('AM_ADSLIGHT_ADD_PRICE', 'Add new Price');
define('AM_ADSLIGHT_PRICE_LIST', 'List of Price');
define('AM_ADSLIGHT_ADD_CONDITION', 'Add new Condition');
define('AM_ADSLIGHT_CONDITION_LIST', 'List of Condition');
define('AM_ADSLIGHT_ADD_IPLOG', 'Add new IPlog');
define('AM_ADSLIGHT_IPLOG_LIST', 'List of IPlog');
define('AM_ADSLIGHT_ADD_ITEMVOTES', 'Add new ItemVotes');
define('AM_ADSLIGHT_ITEMVOTES_LIST', 'List of ItemVotes');
define('AM_ADSLIGHT_ADD_USERVOTES', 'Add new UserVotes');
define('AM_ADSLIGHT_USERVOTES_LIST', 'List of UserVotes');
define('AM_ADSLIGHT_ADD_PICTURES', 'Add new Pictures');
define('AM_ADSLIGHT_PICTURES_LIST', 'List of Pictures');
define('AM_ADSLIGHT_ADD_REPLIES', 'Add new Replies');
define('AM_ADSLIGHT_REPLIES_LIST', 'List of Replies');


//General
//define('AM_ADSLIGHT_FORMOK', 'Registered successfull');
//define('AM_ADSLIGHT_FORMDELOK', 'Deleted successfull');
//define('AM_ADSLIGHT_FORMSUREDEL', "Are you sure to Delete: <span class='bold red'>%s</span></b>");
//define('AM_ADSLIGHT_FORMSURERENEW', "Are you sure to Renew: <span class='bold red'>%s</span></b>");
//define('AM_ADSLIGHT_FORMUPLOAD', 'Upload');
//define('AM_ADSLIGHT_FORMIMAGE_PATH', 'File presents in %s');
//define('AM_ADSLIGHT_FORM_ACTION', 'Action');
//define('AM_ADSLIGHT_SELECT', 'Select action for selected item(s)');
//define('AM_ADSLIGHT_SELECTED_DELETE', 'Delete selected item(s)');
//define('AM_ADSLIGHT_SELECTED_ACTIVATE', 'Activate selected item(s)');
//define('AM_ADSLIGHT_SELECTED_DEACTIVATE', 'De-activate selected item(s)');
//define('AM_ADSLIGHT_SELECTED_ERROR', 'You selected nothing to delete');
//define('AM_ADSLIGHT_CLONED_OK', 'Record cloned successfully');
//define('AM_ADSLIGHT_CLONED_FAILED', 'Cloning of the record has failed');

// Listing
//define('AM_ADSLIGHT_LISTING_ADD', 'Add a listing');
//define('AM_ADSLIGHT_LISTING_EDIT', 'Edit listing');
//define('AM_ADSLIGHT_LISTING_DELETE', 'Delete listing');
//define('AM_ADSLIGHT_LISTING_LID', 'ID');
//define('AM_ADSLIGHT_LISTING_CID', 'Category');
//define('AM_ADSLIGHT_LISTING_TITLE', 'Title');
//define('AM_ADSLIGHT_LISTING_STATUS', 'Status');
//define('AM_ADSLIGHT_LISTING_EXPIRE', 'Expire');
//define('AM_ADSLIGHT_LISTING_TYPE', 'Type');
//define('AM_ADSLIGHT_LISTING_DESCTEXT', 'Description');
//define('AM_ADSLIGHT_LISTING_TEL', 'Phone');
//define('AM_ADSLIGHT_LISTING_PRICE', 'Price');
//define('AM_ADSLIGHT_LISTING_TYPEPRICE', 'PriceType');
//define('AM_ADSLIGHT_LISTING_TYPECONDITION', 'Condition');
define('AM_ADSLIGHT_LISTING_DATE_CREATED', 'Created');
//define('AM_ADSLIGHT_LISTING_EMAIL', 'Email');
//define('AM_ADSLIGHT_LISTING_SUBMITTER', 'Submitter');
//define('AM_ADSLIGHT_LISTING_USID', 'Zip');
//define('AM_ADSLIGHT_LISTING_TOWN', 'Town');
//define('AM_ADSLIGHT_LISTING_COUNTRY', 'country');
//define('AM_ADSLIGHT_LISTING_CONTACTBY', 'Contactby');
//define('AM_ADSLIGHT_LISTING_PREMIUM', 'premium');
//define('AM_ADSLIGHT_LISTING_VALID', 'Valid');
//define('AM_ADSLIGHT_LISTING_PHOTO', 'Photo');
//define('AM_ADSLIGHT_LISTING_HITS', 'Hits');
//define('AM_ADSLIGHT_LISTING_ITEM_RATING', 'ItemRating');
//define('AM_ADSLIGHT_LISTING_ITEM_VOTES', 'ItemVotes');
//define('AM_ADSLIGHT_LISTING_USER_RATING', 'user_rating');
//define('AM_ADSLIGHT_LISTING_USER_VOTES', 'user_votes');
//define('AM_ADSLIGHT_LISTING_COMMENTS', 'comments');
//define('AM_ADSLIGHT_LISTING_REMIND', 'remind');
// Categories
define('AM_ADSLIGHT_CATEGORIES_ADD', 'Add a categories');
define('AM_ADSLIGHT_CATEGORIES_EDIT', 'Edit categories');
define('AM_ADSLIGHT_CATEGORIES_DELETE', 'Delete categories');
define('AM_ADSLIGHT_CATEGORIES_CID', 'ID');
define('AM_ADSLIGHT_CATEGORIES_PID', 'Parent');
define('AM_ADSLIGHT_CATEGORIES_TITLE', 'Title');
define('AM_ADSLIGHT_CATEGORIES_CAT_DESC', 'Desc');
define('AM_ADSLIGHT_CATEGORIES_CAT_KEYWORDS', 'Keywords');
define('AM_ADSLIGHT_CATEGORIES_IMG', 'Image');
define('AM_ADSLIGHT_CATEGORIES_CAT_ORDER', 'Order');
define('AM_ADSLIGHT_CATEGORIES_AFFPRICE', 'Price');
define('AM_ADSLIGHT_CATEGORIES_CAT_MODERATE', 'CatModerator');
define('AM_ADSLIGHT_CATEGORIES_MODERATE_SUBCAT', 'SubcatModerator');
// Type
//define('AM_ADSLIGHT_TYPE_ADD', 'Add a type');
//define('AM_ADSLIGHT_TYPE_EDIT', 'Edit type');
//define('AM_ADSLIGHT_TYPE_DELETE', 'Delete type');
//define('AM_ADSLIGHT_TYPE_ID_TYPE', 'ID');
//define('AM_ADSLIGHT_TYPE_NOM_TYPE', 'Name');
// Price
define('AM_ADSLIGHT_PRICE_ADD', 'Add a price');
define('AM_ADSLIGHT_PRICE_EDIT', 'Edit price');
define('AM_ADSLIGHT_PRICE_DELETE', 'Delete price');
define('AM_ADSLIGHT_PRICE_ID_PRICE', 'ID');
define('AM_ADSLIGHT_PRICE_NOM_PRICE', 'Price');
// Condition
define('AM_ADSLIGHT_CONDITION_ADD', 'Add a condition');
define('AM_ADSLIGHT_CONDITION_EDIT', 'Edit condition');
define('AM_ADSLIGHT_CONDITION_DELETE', 'Delete condition');
define('AM_ADSLIGHT_CONDITION_ID_CONDITION', 'ID');
define('AM_ADSLIGHT_CONDITION_NOM_CONDITION', 'Condition');
// Iplog
define('AM_ADSLIGHT_IPLOG_ADD', 'Add a iplog');
define('AM_ADSLIGHT_IPLOG_EDIT', 'Edit iplog');
define('AM_ADSLIGHT_IPLOG_DELETE', 'Delete iplog');
define('AM_ADSLIGHT_IPLOG_IP_ID', 'ID');
define('AM_ADSLIGHT_IPLOG_LID', 'Listing');
define('AM_ADSLIGHT_IPLOG_DATE_CREATED', 'DateTimeCreated');
define('AM_ADSLIGHT_IPLOG_SUBMITTER', 'Submitter');
define('AM_ADSLIGHT_IPLOG_IPNUMBER', 'Ipnumber');
define('AM_ADSLIGHT_IPLOG_EMAIL', 'Email');
// Itemvotes
//define('AM_ADSLIGHT_ITEMVOTES_ADD', 'Add a itemvotes');
//define('AM_ADSLIGHT_ITEMVOTES_EDIT', 'Edit itemvotes');
//define('AM_ADSLIGHT_ITEMVOTES_DELETE', 'Delete itemvotes');
//define('AM_ADSLIGHT_ITEMVOTES_RATINGID', 'ID');
//define('AM_ADSLIGHT_ITEMVOTES_LID', 'Listing');
//define('AM_ADSLIGHT_ITEMVOTES_RATINGUSER', 'Ratinguser');
//define('AM_ADSLIGHT_ITEMVOTES_RATING', 'Rating');
//define('AM_ADSLIGHT_ITEMVOTES_RATINGHOSTNAME', 'Ratinghostname');
//define('AM_ADSLIGHT_ITEMVOTES_RATINGTIMESTAMP', 'Ratingtimestamp');
// Uservotes
//define('AM_ADSLIGHT_USERVOTES_ADD', 'Add a uservotes');
//define('AM_ADSLIGHT_USERVOTES_EDIT', 'Edit uservotes');
//define('AM_ADSLIGHT_USERVOTES_DELETE', 'Delete uservotes');
//define('AM_ADSLIGHT_USERVOTES_RATINGID', 'ID');
//define('AM_ADSLIGHT_USERVOTES_USID', 'User');
//define('AM_ADSLIGHT_USERVOTES_RATINGUSER', 'Ratinguser');
//define('AM_ADSLIGHT_USERVOTES_RATING', 'Rating');
//define('AM_ADSLIGHT_USERVOTES_RATINGHOSTNAME', 'Ratinghostname');
//define('AM_ADSLIGHT_USERVOTES_RATINGTIMESTAMP', 'Ratingtimestamp');
// Pictures
define('AM_ADSLIGHT_PICTURES_ADD', 'Add a pictures');
define('AM_ADSLIGHT_PICTURES_EDIT', 'Edit pictures');
define('AM_ADSLIGHT_PICTURES_DELETE', 'Delete pictures');
define('AM_ADSLIGHT_PICTURES_COD_IMG', 'ID');
define('AM_ADSLIGHT_PICTURES_TITLE', 'Title');
define('AM_ADSLIGHT_PICTURES_DATE_CREATED', 'Added');
define('AM_ADSLIGHT_PICTURES_DATE_UPDATED', 'Updated');
define('AM_ADSLIGHT_PICTURES_LID', 'Listing');
define('AM_ADSLIGHT_PICTURES_UID_OWNER', 'Owner');
define('AM_ADSLIGHT_PICTURES_URL', 'URL');
// Replies
define('AM_ADSLIGHT_REPLIES_ADD', 'Add a replies');
define('AM_ADSLIGHT_REPLIES_EDIT', 'Edit replies');
define('AM_ADSLIGHT_REPLIES_DELETE', 'Delete replies');
define('AM_ADSLIGHT_REPLIES_R_LID', 'ID');
define('AM_ADSLIGHT_REPLIES_LID', 'Listing');
define('AM_ADSLIGHT_REPLIES_TITLE', 'Title');
define('AM_ADSLIGHT_REPLIES_DATE', 'Date');
define('AM_ADSLIGHT_REPLIES_SUBMITTER', 'Submitter');
define('AM_ADSLIGHT_REPLIES_MESSAGE', 'Message');
define('AM_ADSLIGHT_REPLIES_TELE', 'Phone');
define('AM_ADSLIGHT_REPLIES_EMAIL', 'Email');
define('AM_ADSLIGHT_REPLIES_R_USID', 'User');
//Blocks.php
//Permissions
define('AM_ADSLIGHT_PERMISSIONS_GLOBAL', 'Global permissions');
define('AM_ADSLIGHT_PERMISSIONS_GLOBAL_DESC', 'Only users in the group that you select may global this');
define('AM_ADSLIGHT_PERMISSIONS_GLOBAL_4', 'Rate from user');
define('AM_ADSLIGHT_PERMISSIONS_GLOBAL_8', 'Submit from user side');
define('AM_ADSLIGHT_PERMISSIONS_GLOBAL_16', 'Auto approve');
define('AM_ADSLIGHT_PERMISSIONS_APPROVE', 'Permissions to approve');
define('AM_ADSLIGHT_PERMISSIONS_APPROVE_DESC', 'Only users in the group that you select may approve this');
define('AM_ADSLIGHT_PERMISSIONS_VIEW', 'Permissions to view');
define('AM_ADSLIGHT_PERMISSIONS_VIEW_DESC', 'Only users in the group that you select may view this');
define('AM_ADSLIGHT_PERMISSIONS_SUBMIT', 'Permissions to submit');
define('AM_ADSLIGHT_PERMISSIONS_SUBMIT_DESC', 'Only users in the group that you select may submit this');
define('AM_ADSLIGHT_PERMISSIONS_GPERMUPDATED', 'Permissions have been changed successfully');
define('AM_ADSLIGHT_PERMISSIONS_NOPERMSSET', 'Permission cannot be set: No replies created yet! Please create a replies first.');

//Errors
define('AM_ADSLIGHT_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('AM_ADSLIGHT_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('AM_ADSLIGHT_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('AM_ADSLIGHT_ERROR_COLUMN', 'Could not create column in database : %s');
define('AM_ADSLIGHT_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('AM_ADSLIGHT_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('AM_ADSLIGHT_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
//directories
define('AM_ADSLIGHT_AVAILABLE', "<span style='color : #008000;'>Available. </span>");
define('AM_ADSLIGHT_NOTAVAILABLE', "<span style='color : #ff0000;'>is not available. </span>");
define('AM_ADSLIGHT_NOTWRITABLE', "<span style='color : #ff0000;'>" . ' should have permission ( %1$d ), but it has ( %2$d )' . '</span>');
define('AM_ADSLIGHT_CREATETHEDIR', 'Create it');
define('AM_ADSLIGHT_SETMPERM', 'Set the permission');
define('AM_ADSLIGHT_DIRCREATED', 'The directory has been created');
define('AM_ADSLIGHT_DIRNOTCREATED', 'The directory can not be created');
define('AM_ADSLIGHT_PERMSET', 'The permission has been set');
define('AM_ADSLIGHT_PERMNOTSET', 'The permission can not be set');
define('AM_ADSLIGHT_VIDEO_EXPIREWARNING', 'The publishing date is after expiration date!!!');
//Sample Data
define('AM_ADSLIGHT_ADD_SAMPLEDATA', 'Import Sample Data (will delete ALL current data)');
define('AM_ADSLIGHT_SAMPLEDATA_SUCCESS', 'Sample Date uploaded successfully');

define('AM_ADSLIGHT_MAINTAINEDBY', 'is maintained by the');
