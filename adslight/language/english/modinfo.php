<?php
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
// The name of this module
define("_MI_ADSLIGHT_NAME","adslight");
define("_MI_ADSLIGHT_MENUADD","Add an Advertisement");
// A brief description of this module
define("_MI_ADSLIGHT_DESC","Classified Ad Module");
// Names of blocks for this module (Not all module has blocks)
define("_MI_ADSLIGHT_BNAME1","Recent Listings");
define("_MI_ADSLIGHT_BNAME1_DESC","Recent Listings Block");
define("_MI_ADSLIGHT_BNAME2","Top Listings");
define("_MI_ADSLIGHT_BNAME2_DESC","Top Listings Block");
// Names of admin menu items
define("_MI_ADSLIGHT_ADMENU0","Dashboard");
define("_MI_ADSLIGHT_ADMENU1","Categories");
define("_MI_ADSLIGHT_ADMENU2","Permissions");
define("_MI_ADSLIGHT_ADMENU3","Blocks");
define("_MI_ADSLIGHT_ADMENU4","Preferences");
define("_MI_ADSLIGHT_ADMENU5","Types");
define("_MI_ADSLIGHT_ADMENU6","Import");
define("_MI_ADSLIGHT_ADMENU7","About");
define("_MI_ADSLIGHT_ADMENU8","Docs");
define("_MI_ADSLIGHT_ADMENU9","Go to Module");
define("_MI_ADSLIGHT_ADMENU10","Updated");
define("_MI_ADSLIGHT_ADMENU11","Support Forum");
define("_MI_ADSLIGHT_CONFSAVE","Configuration saved");
define("_MI_ADSLIGHT_CANPOST","Anonymous user can post Listings :");
define("_MI_ADSLIGHT_PERPAGE","Listings per page :");
define("_MI_ADSLIGHT_RES_PERPAGE","Resumes per page :");
define("_MI_ADSLIGHT_MONEY","Currency symbol :");
define("_MI_ADSLIGHT_KOIVI","Use Koivi Editor :");
define("_MI_ADSLIGHT_NUMNEW","Number of new Listings :");
define("_MI_ADSLIGHT_MODERAT","Moderate Listings :");
define("_MI_ADSLIGHT_DAYS","Listing Duration :");
define("_MI_ADSLIGHT_MAXIIMGS","Maximum Photo Size :");
define("_MI_ADSLIGHT_MAXWIDE","Maximum Photo Width :");
define("_MI_ADSLIGHT_MAXHIGH","Maximum Photo Height :");
define("_MI_ADSLIGHT_INBYTES","in bytes");
define("_MI_ADSLIGHT_INPIXEL","in pixels");
define("_MI_ADSLIGHT_INDAYS","in days");
define("_MI_ADSLIGHT_MUSTLOGIN","Allow anonymous users to reply to a classified ad.");
define("_MI_ADSLIGHT_THRUMAIL","using the e-mail form (recommended is No, because of spam.)");
define("_MI_ADSLIGHT_TYPEBLOC","Type of Block :");
define("_MI_ADSLIGHT_JOBRAND","Random Listing");
define("_MI_ADSLIGHT_LASTTEN","Last 10 Listings");
define("_MI_ADSLIGHT_NEWTIME","New Listings from :");
define("_MI_ADSLIGHT_DISPLPRICE","Display price :");
define("_MI_ADSLIGHT_DISPLPRICE2","Display price :");
define("_MI_ADSLIGHT_INTHISCAT","in this category");
define("_MI_ADSLIGHT_DISPLSUBCAT","Display subcategories :");
define("_MI_ADSLIGHT_ONHOME","on the Front Page of Module");
define("_MI_ADSLIGHT_NBDISPLSUBCAT","Number of subcategories to show :");
define("_MI_ADSLIGHT_IF","if");
define("_MI_ADSLIGHT_ISAT","is at");
define("_MI_ADSLIGHT_VIEWNEWCLASS","Show new Listings :");
define("_MI_ADSLIGHT_ORDREALPHA","Sort alphabetically");
define("_MI_ADSLIGHT_ORDREPERSO","Personalised Order");
define("_MI_ADSLIGHT_CSORT_ORDER","Category Default Sort Order");
define("_MI_ADSLIGHT_LSORT_ORDER","Listing Default Sort Order");
define("_MI_ADSLIGHT_ORDER_TITLE","Sort listings by title");
define("_MI_ADSLIGHT_ORDER_PRICE","Sort listings by price");
define("_MI_ADSLIGHT_ORDER_DATE","Sort listings by date (default)");
define("_MI_ADSLIGHT_ORDER_POP","Sort listings by hits");
define("_MI_ADSLIGHT_DBUPDATED","The database has been updated.");
define("_MI_ADSLIGHT_GPERM_G_ADD","Can add" ) ;
define("_MI_ADSLIGHT_CAT2GROUPDESC","Check categories which you allow to access" ) ;
define("_MI_ADSLIGHT_GROUPPERMDESC","Select group(s) allowed to submit listings." ) ;
define("_MI_ADSLIGHT_GROUPPERM","Submit Permissions");
define("_MI_ADSLIGHT_SUBMITFORM","Ads Submit Permissions");
define("_MI_ADSLIGHT_SUBMITFORM_DESC","Select, who can submit Ads");
define("_MI_ADSLIGHT_VIEWFORM","View Ads Permissions");
define("_MI_ADSLIGHT_VIEWFORM_DESC","Select, who can view Ads");
define("_MI_ADSLIGHT_VIEW_RESUMEFORM_DESC","Select, who can view resumes");
define("_MI_ADSLIGHT_SUPPORT","Support this software");
define("_MI_ADSLIGHT_OP","Read my opinion");
define("_MI_ADSLIGHT_PREMIUM","Ads Premium");
define("_MI_ADSLIGHT_PREMIUM_DESC","Who can select days listing will last");
define("_MI_ADSLIGHT_CATEGORY_NOTIFY","Category"); 
define("_MI_ADSLIGHT_CATEGORY_NOTIFYDSC","Notification options that apply to the current category.");
define("_MI_ADSLIGHT_NOTIFY","Listing"); 
define("_MI_ADSLIGHT_NOTIFYDSC","Notification options that apply to the current listing.");	
define("_MI_ADSLIGHT_GLOBAL_NOTIFY","Whole Module");
define("_MI_ADSLIGHT_GLOBAL_NOTIFYDSC","Global advert notification options.");
define("_MI_ADSLIGHT_NEWPOST_NOTIFY","New Listing");
define("_MI_ADSLIGHT_NEWPOST_NOTIFYCAP","Notify me of new listings in the current category.");
define("_MI_ADSLIGHT_NEWPOST_NOTIFYDSC","Receive notification when a new listing is posted to the current category.");
define("_MI_ADSLIGHT_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category");
define("_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFY","New Listing");
define("_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFYCAP","Notify me of new listings in the current category.");
define("_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFYDSC","Receive notification when a new listing is posted to the current category.");
define("_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category");
define("_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFY","Listing Updated");
define("_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFYCAP","Notify me of updated listings in the current category.");
define("_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFYDSC","Receive notification when an listing is updated in the current category.");
define("_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category");
define("_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFY","Listing Deleted");
define("_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFYCAP","Notify me of new listings in the current category.");
define("_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFYDSC","Receive notification when an listing is deleted from the current category.");
define("_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category");
define("_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFY","New Listing");
define("_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYCAP","Notify me of new listings in all categories.");
define("_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYDSC","Receive notification when a new listing is posted to all categories.");
define("_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category");
define("_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFY","New Listing");
define("_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFYCAP","Notify me of new listings in all categories.");
define("_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFYDSC","Receive notification when a new listing is posted to all categories.");
define("_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category");
define("_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFY","Listing Updated");
define("_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFYCAP","Notify me of updated listings in all categories.");
define("_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFYDSC","Receive notification when an listing is updated in all categories.");
define("_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : Listing updated in categories");
define("_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFY","Listing Deleted");
define("_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFYCAP","Notify me of deleted listings in all categories.");
define("_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFYDSC","Receive notification when an listing is deleted in all categories.");
define("_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : Listing deleted in categories");
define("_MI_ADSLIGHT_RATE_USER","Allow Users to rate sellers");
define("_MI_ADSLIGHT_RATE_ITEM","Allow Users to rate items");
define("_MI_ADSLIGHT_DIFF_NAME","Allow Users to use different name");
define("_MI_ADSLIGHT_DIFF_EMAIL","Allow Users to use different e-mail");
define("_MI_ADSLIGHT_NOT_PREMIUM","Number of Pictures - Not Premium");
define("_MI_ADSLIGHT_NOT_PREMIUM_DESC","Number of pictures a non-Premium user can have in his page");
define("_MI_ADSLIGHT_NUMBPICT_TITLE","Number of Pictures - Premium");
define("_MI_ADSLIGHT_NUMBPICT_DESC","Number of pictures a Premium user can have in his page");
define("_MI_ADSLIGHT_SMNAME1","Submit");
define("_MI_ADSLIGHT_THUMW_TITLE","Thumb Width");
define("_MI_ADSLIGHT_THUMBW_DESC","Thumbnails width in pixels<br />This means your picture thumbnail will have<br />at most this size in width<br />All proportions are maintained");
define("_MI_ADSLIGHT_THUMBH_TITLE","Thumb Height");
define("_MI_ADSLIGHT_THUMBH_DESC","Thumbnails Height in pixels<br />This means your picture thumbnail will have<br />at most this size in height<br />All proportions are maintained");
define("_MI_ADSLIGHT_RESIZEDW_TITLE","Resized picture width");
define("_MI_ADSLIGHT_RESIZEDW_DESC","Resized picture width in pixels<br />This means your picture will have<br />at most this size in width<br />All proportions are maintained<br /> The original picture if bigger than this size will <br />be resized so it wont break your template");
define("_MI_ADSLIGHT_RESIZEDH_TITLE","Resized picture height");
define("_MI_ADSLIGHT_RESIZEDH_DESC","Resized picture height in pixels<br />This means your picture will have<br />at most this size in height<br />All proportions are maintained<br /> The original picture if bigger than this size will <br />be resized so it wont break your template design");
define("_MI_ADSLIGHT_ORIGW_TITLE","Max original picture width");
define("_MI_ADSLIGHT_ORIGW_DESC","Maximum original picture width in pixels<br />This means user's original picture can't exceed <br />this size in height<br />or else it won't be uploaded");
define("_MI_ADSLIGHT_ORIGH_TITLE","Max original picture height");
define("_MI_ADSLIGHT_ORIGH_DESC","Maximum original picture height in pixels<br />This means user's original picture can't exceed <br />this size in height<br />or else it won't be uploaded");
define("_MI_ADSLIGHT_UPLOAD_TITLE","Path Uploads");
define("_MI_ADSLIGHT_UPLOAD_DESC","Path to your uploads directory<br />in linux should look like /var/www/uploads<br />in windows like C:/Program Files/www");
define("_MI_ADSLIGHT_LINKUPLOAD_TI","Link to your uploads directory");
define("_MI_ADSLIGHT_LINKUPLOAD_DE","This is the address of the root of your uploads <br />like http://www.yoursite.com/uploads");
define("_MI_ADSLIGHT_MAXFILEBYTES_T","Max size in bytes");
define("_MI_ADSLIGHT_MAXFILEBYTES_D","This the maximum size a file of your pictue can have in bytes <br />like 512000 for 500 KB");
define("_MI_ADSLIGHT_EDITOR","Editor to use:");
define("_MI_ADSLIGHT_LIST_EDITORS","Select the editor to use.");
define("_MI_ADSLIGHT_LIGHTBOX","Lightbox effects");
define("_MI_ADSLIGHT_LIGHTBOX_DESC","Use the lightbox effects when viewing photos.");
define("_MI_ADSLIGHT_USE_COUNTRY","Use the Country Field");
define("_MI_ADSLIGHT_USE_COUNTRY_DESC","If set to 'No' the Country Field will not be shown");
define("_MI_ADSLIGHT_SOLD_DAYS","Listing Duration after marked Sold");
define("_MI_ADSLIGHT_SOLDINDAYS","in days");
define("_MI_ADSLIGHT_ALMOST","When to send notice that the ad is about to expire");
define("_MI_ADSLIGHT_ALMOST_DESC","in days");
define("_MI_ADSLIGHT_MAIN_CAT","Allow users to add listings in the main categories");
define("_MI_ADSLIGHT_MAIN_CAT_DESC","or just allow adding listings in sub-categoreies");
define("_MI_ADSLIGHT_ADMIN_EDITOR","Editor to use for admin:");
define("_MI_ADSLIGHT_LIST_ADMIN_EDITORS","Select the editor to use on the admin side.");
define("_MI_ADSLIGHT_CAT_DESC","Category Description");
define("_MI_ADSLIGHT_DESC_CAT_DESC","Add a description for each category.");
define("_MI_ADSLIGHT_MUST_ADD_CAT","You must add a category first.");
define("_MI_ADSLIGHT_BNAME3","Recent Listings w/Photo");
define("_MI_ADSLIGHT_BNAME3_DESC","Recent Listings w/Photo Block");
define("_MI_ADSLIGHT_BNAME4","Top Listings w/Photo");
define("_MI_ADSLIGHT_BNAME4_DESC","Top Listings w/Photo Block");
define("_MI_ADSLIGHT_USE_CAPTCHA","Use Captcha");
define("_MI_ADSLIGHT_USE_CAPTCHA_DESC","Select to Use Captcha");
// Menu AdsLight
define("_MI_ADSLIGHT_SMENU1","Your Listings");
define("_MI_ADSLIGHT_SMENU2","Submit");
define("_MI_ADSLIGHT_SMENU3","Search"); 
// support.php 
define("_MI_ADSLIGHT_SUPPORT01"," If you wish to suggest an improvement:<br /><br />- A correction<br />- A translation<br />- A suggestion<br />- Report a Bug<br />");
define("_MI_ADSLIGHT_SUPPORT02","Join us at the Support Forum AdsLight");
define("_MI_ADSLIGHT_SUPPORT03","> Support Forum AdsLight <");
// Bloc Carte de France
define("_MI_ADSLIGHT_MAPFRANCE","Map of France");
define("_MI_ADSLIGHT_MAPFRANCE_DESC","In your area");
// Bloc Ajouter une annonce
define("_MI_ADSLIGHT_ADDMENU","Add Ad");
define("_MI_ADSLIGHT_ADDMENU_DESC","Menu");
// version Adslight 1.0.2
// Active RewriteUrl
define("_MI_ADSLIGHT_ACTIVE_REWRITEURL","Enable URL Rewrite");
define("_MI_ADSLIGHT_ACTIVE_REWRITEURL_DESC","Enable URL rewriting for a better ranking.<br/>To install the rewrite, thank you to read the file README.txt");
// Activer thumbs_index
define("_MI_ADSLIGHT_ACTIVE_THUMBSINDEX","Enable Snapshot page index");
define("_MI_ADSLIGHT_ACTIVE_THUMBSINDEX_DESC","Enable the display of thumbnail images to homepage<br/>If it is off an icon <img src='".XOOPS_URL."/modules/adslight/images/camera_photo.png' width='24px' ><br/> indicates whether or not there is a picture in the ad.");
// Activer thumbs_cats
define("_MI_ADSLIGHT_ACTIVE_THUMBSCATS","Enable Snapshot in categories");
define("_MI_ADSLIGHT_ACTIVE_THUMBSCATS_DESC","Enable the display of thumbnail pictures in the categories<br/>If it is off an icon <img src='".XOOPS_URL."/modules/adslight/images/camera_photo.png' width='24px' ><br/> indicates whether or not there is a picture in the ad.");
// Code Adscence index
define("_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE","Code additional home page");
define("_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_DESC","Put your adsense code or other here");
define("_MI_ADSLIGHT_ADSLIGHT_USE_INDEX_CODE","Use additional code to homepage");
define("_MI_ADSLIGHT_ADSLIGHT_USE_INDEX_CODE_DESC","Place the additional code between listings<br />on the home page and category page.<br /><br />Banners, Adsense code, etc. ...");
define("_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_PLACE","Place where the code will be displayed in the list");
define("_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_PLACE_DESC","Ex. If you choose 4 there will be 3 proposals before this code.<br />The code will be displayed in 4th position.");
define("_MI_ADSLIGHT_ADSLIGHT_USE_BANNER","Use code banner Xoops");
define("_MI_ADSLIGHT_ADSLIGHT_USE_BANNER_DESC","allows you to insert banners Xoops between proposals.<br />If you choose Yes<br />NO insert code here");
// Version 1.0.5
// Code Adscence Catégories
define("_MI_ADSLIGHT_ADSLIGHT_CATS_CODE","Pub code on pages categories");
define("_MI_ADSLIGHT_ADSLIGHT_CATS_CODE_DESC","Google adsence code or code of a banner:<br/>Format: width = 300 height = 250");
// adslight_theme_set
define("_MI_ADSLIGHT_THEMESET","Choice of template set");
// Méta Description / keywords Categories
define("_MI_ADSLIGHT_CAT_META","Allow the seizure Meta description, Meta keywords categories?");
define("_MI_ADSLIGHT_CAT_META_DESCRIPTION","If you set this option to 'Yes',<br/> 
You can write data meta keywords and description for each category<br/>( Recommend to a better ranking. )");
// Version 1.0.51
// tips_writing_ad.php
define("_MI_ADSLIGHT_USE_TIPS_WRITE","Customize page info / advice?");
define("_MI_ADSLIGHT_USEDESC_TIPS_WRITE","If you select 'yes', you can write below the title and text of the page info / advice");
define("_MI_ADSLIGHT_TITLE_TIPS_WRITE","Page Title information / advice");
define("_MI_ADSLIGHT_TITLEDESC_TIPS_WRITE","Write here the title that will appear on the page info / advice");
define("_MI_ADSLIGHT_TEXT_TIPS_WRITE","Text of the page info / advice");
define("_MI_ADSLIGHT_TEXTDESC_TIPS_WRITE","Enter the text that appears here on the page info / advice");
// Version 1.0.53
// adslight_maps
define("_MI_ADSLIGHT_MAPSSET","Select the card from your country");
define("_MI_ADSLIGHT_MAPSSET_DESC","If you want to create the map of your country.<br />Thank you to read the module docs.");
define("_MI_ADSLIGHT_MAPSW_TITLE","Width of the map");
define("_MI_ADSLIGHT_MAPSH_TITLE","Height map");
/*  Adslight 2  */
define("_MI_ADSLIGHT_ACTIVE_MENU","Activate the menu?");
define("_MI_ADSLIGHT_USEDESC_ACTIVEMENU","Activation of the menu on the module's pages");
define("_MI_ADSLIGHT_ACTIVE_RSS","Enable RSS?");
define("_MI_ADSLIGHT_USEDESC_ACTIVERSS","Enabling page of RSS Feed");
define("_MI_ADSLIGHT_ACTIVE_BOOKMARK","Enable bookmark buttons?");
define("_MI_ADSLIGHT_USEDESC_ACTIVEBOOKMARK","Enabling Bookmark on ads.");
define("_MI_ADSLIGHT_STBOOKMARK","Choose the style of Bookmark (Twitter, Facebook, Google ...)");
define("_MI_ADSLIGHT_DESC_STBOOKMARK","3 styles of Bookmark are available.");
define("_MI_ADSLIGHT_ACTIVE_XPAYMENT","Enable Module Xpayment");
define("_MI_ADSLIGHT_TEXTDESC_XPAYMENT","Xpayment module must be installed");
// Xpayment
define("_MI_ADSLIGHT_PURCHASE","Available for purchase");
define("_MI_ADSLIGHT_PURCHASE_DESC","These are the categories that are available for purchase on Xpayment");
define("_MI_ADSLIGHT_CURRENCY","ISO currency code");
define("_MI_ADSLIGHT_LISTING_COST","The registration will be charged at this rate per day");
define("_MI_ADSLIGHT_PURCHASELISTING","Inscriptions Purchasable");
define('_MI_ADSLIGHT_PURCHASELISTING_DESC',"These are the categories that the cost per day to list an ad");