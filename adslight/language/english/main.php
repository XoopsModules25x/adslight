<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2: Module for Xoops                           

        Redesigned and ameliorate By iluc user at www.frxoops.org
		Started with the Classifieds module and made MANY changes 
        Website: http://www.limonads.com
        Contact: adslight.translate@gmail.com
-------------------------------------------------------------------------
             Original credits below Version History                       
##########################################################################
#                    Classified Module for Xoops                         #
#  By John Mordo user jlm69 at www.xoops.org and www.jlmzone.com         #
#      Started with the MyAds module and made MANY changes               #
##########################################################################
 Original Author: Pascal Le Boustouller                                   
 Author Website: pascal.e-xoops@perso-search.com                         
 Licence Type  : GPL                                                     
------------------------------------------------------------------------- 
*/
define("_ADSLIGHT_ADDON","Addon");
define("_ADSLIGHT_ANNONCES","Classified Ads");
define("_ADSLIGHT_LOGOLINK","Free Xoops Module AdsLight classifieds Ads");
define("_ADSLIGHT_MAIN","Main");
define("_ADSLIGHT_ADDFROM","Classified Advertisements");
define("_ADSLIGHT_NEWTOD","new advertisements have been added in this category today");
define("_ADSLIGHT_NEWTRED","new advertisements have been added in this category during the last 3 days");
define("_ADSLIGHT_NEWWEEK","new advertisements have been added in this category during the last week");
define("_ADSLIGHT_INTRO","<b>You can add your Classified Ad(s) for free.<br />Just click on the category you wish to add it to,<br />You will see an 'Add a Classified Ad in this category' link.</b>");
define("_ADSLIGHT_PRICE","Price");
define("_ADSLIGHT_DATE","Date");
define("_ADSLIGHT_TITLE","Title");
define("_ADSLIGHT_CAT","Category");
define("_ADSLIGHT_THE","The");
define("_ADSLIGHT_LASTADD","Latest Classified Ads");
define("_ADSLIGHT_PREV","Previous Ads");
define("_ADSLIGHT_NEXT","Next Ads");
define("_ADSLIGHT_THEREIS","There are");
define("_ADSLIGHT_INTHISCAT","Classified Ads in this category");
define("_ADSLIGHT_AVAILAB","Subcategories available:");
define("_ADSLIGHT_ADMINCADRE","Administration Panel");
define("_ADSLIGHT_WAIT","Classified Ads waiting to be approved");
define("_ADSLIGHT_NO_CLA","There are no advertisements waiting to be approved");
define("_ADSLIGHT_SEEIT","View pending advertisements");
define("_ADSLIGHT_LOCAL","Location:");
define("_ADSLIGHT_LOCAL2","Location");
define("_ADSLIGHT_ANNFROM","Ads from");
define("_ADSLIGHT_ADDED","added");
define("_ADSLIGHT_GORUB","Go to ");
define("_ADSLIGHT_DATE2","Added");
define("_ADSLIGHT_SUPPRANN","Delete your Ad");
define("_ADSLIGHT_MODIFANN","Modify your Ad");
define("_ADSLIGHT_PHOTO","Photo");
define("_ADSLIGHT_VIEW","Viewed");
define("_ADSLIGHT_CONTACT","Contact");
define("_ADSLIGHT_BYMAIL2","Email");
define("_ADSLIGHT_STOP2","The listing period for your Ad :");
define("_ADSLIGHT_STOP3","has ended, it has been deleted");
define("_ADSLIGHT_VU2","this");
define("_ADSLIGHT_OTHER","If your want to place another ad ");
define("_ADSLIGHT_TEAM","Team");
define("_ADSLIGHT_ACTUALY","There are");
define("_ADSLIGHT_ADVERTISEMENTS","Ad(s) active");
	//define("_ADSLIGHT_DISPO","available until");
define("_ADSLIGHT_MODADMIN","Change this Ad (administrator)");
define("_ADSLIGHT_AND","and");
define("_ADSLIGHT_WAIT3","Ad(s) waiting to be approved");
define("_ADSLIGHT_CREATBY","was written by");
define("_ADSLIGHT_FOR","for");
define("_ADSLIGHT_OF","of");
define("_ADSLIGHT_VIEW2","views");
define("_ADSLIGHT_VIEWANN2","See your Ad");
define("_ADSLIGHT_ACCESADMIN","MyAds Administration");
define("_ADSLIGHT_NOANNINCAT","There are no advertisements in this category");
define("_ADSLIGHT_INCAT","in");
define("_ADSLIGHT_CAT2","categories"); 
// addlisting.php //
define("_ADSLIGHT_DESC","Description");
define("_ADSLIGHT_ADDANNONCE2","Add a Classified Ad in this category");
define("_ADSLIGHT_ADDANNONCE3","Adding a Classified Ad");
define("_ADSLIGHT_ANNMODERATE","Your Classified Ad will be queued for approval and once approved, will run for");
define("_ADSLIGHT_NOBIZ","Business Advertising Not Allowed");
define("_ADSLIGHT_REDIRECT_BIZ","View our Business Rates");
define("_ADSLIGHT_ANNNOMODERATE","Your Classified Ad will start immediately and will run for ");
define("_ADSLIGHT_FORMEMBERS2","Posting advertisements is restricted to members <br />Please register or login if you are already a member.");
define("_ADSLIGHT_OR","or");
define("_ADSLIGHT_DAY","days");
define("_ADSLIGHT_CAT3","Category:");
define("_ADSLIGHT_TITLE2","Title:");
define("_ADSLIGHT_TYPE","Type:");
define('_ADSLIGHT_TYPE_USURE', "The state of wear:");
define("_ADSLIGHT_NOTYPE","No type");
define("_ADSLIGHT_CLASSIFIED_AD","Classified Ads:");
define("_ADSLIGHT_CHARMAX","(255 Character max)");
define("_ADSLIGHT_IMG","Add an image:");
define("_ADSLIGHT_IMG2","Add a second image:");
define("_ADSLIGHT_IMG3","Add a third image:");
define("_ADSLIGHT_PRICE2","Price:");
define("_ADSLIGHT_EMAIL","Email:");
define("_ADSLIGHT_TEL","Telephone:");
define("_ADSLIGHT_TOWN","Town:");
define("_ADSLIGHT_COUNTRY","Country:");
define("_ADSLIGHT_VALIDATE","Validate");
define("_ADSLIGHT_SELECTYPE","Select Classified Ad Type");
define("_ADSLIGHT_SELECTCAT","Select a category");
define("_ADSLIGHT_SELECTYPOTHER","Other type");
define("_ADSLIGHT_SELECTCATOTHER","Other category");
define("_ADSLIGHT_RETURN","Return");
define("_ADSLIGHT_FILES","Files with this extension");
define("_ADSLIGHT_FILESTOP","may not be uploaded. Upload stopped");
define("_ADSLIGHT_YIMG","Your image");
define("_ADSLIGHT_TOBIG","is too big.<p> Use the function <b>Previous page</b> to return to the form an add a file");
define("_ADSLIGHT_ADSADDED","Your advertisement has been added.");
define("_ADSLIGHT_PRINT","Print this Classified Ad");
define("_ADSLIGHT_FRIENDSEND","Send this advertisement to a friend");
define("_ADSLIGHT_IMGPISP","Photo available");
define("_ADSLIGHT_VALIDERORMSG","There are errors which prevent this listing from being saved!");
define("_ADSLIGHT_VALIDTITLE","Title is required.");
define("_ADSLIGHT_VALIDTYPE","Type is required.");
define("_ADSLIGHT_VALIDCAT","Category is required.");
define("_ADSLIGHT_VALIDANN","Description is required.");
define("_ADSLIGHT_VALIDTOWN","Town is required.");
define("_ADSLIGHT_VALIDEMAIL","Email is required.");
define("_ADSLIGHT_VALIDSUBMITTER","Name is required.");
define("_ADSLIGHT_VALIDMSG","Please correct these errors to save the listing.");
// display-image.php //
define("_ADSLIGHT_CLOSEF","Close this window");	
// listing-p-f.php //
define("_ADSLIGHT_EXTRANN","This advertisement is from the classified ads section on the website ");
define("_ADSLIGHT_SENDTO","<b>Send this advertisement to a friend</b><br><br>You can send ad No ");
define("_ADSLIGHT_FRIEND","to a friend:");
define("_ADSLIGHT_NAME","Your Name:");
define("_ADSLIGHT_MAIL","Your Email:");
define("_ADSLIGHT_NAMEFR","Friend's Name:");
define("_ADSLIGHT_MAILFR","Friend's Email:");
define("_ADSLIGHT_SENDFR","Send");
define("_ADSLIGHT_ANNSEND","The Classified Ad selected has been sent");
define("_ADSLIGHT_SUBJET","An interesting Classified Ad from");
define("_ADSLIGHT_HELLO","Hello");
define("_ADSLIGHT_MESSAGE","thinks this Classified Ad might interest you and has sent you this message.");
define("_ADSLIGHT_INTERESS","Other Ads can be seen at ");
define("_ADSLIGHT_TEL2","Telephone:");
define("_ADSLIGHT_BYMAIL","Email:");
define("_ADSLIGHT_DISPO","Expires on");
define("_ADSLIGHT_NOMAIL","We do not give out users email addresses, to contact them by e-mail please use the contact form on our site by clicking on the e-mail link in the ad, you can view the ad at the following web address. ");
// modify.php //
define("_ADSLIGHT_OUI","Yes");
define("_ADSLIGHT_NON","No");
define("_ADSLIGHT_SURDELANN","ATTENTION: Are you sure you want to delete this advertisement");
define("_RETURNANN","Return classified ads listing");
define("_ADSLIGHT_ANNDEL","Classified Ad selected has been deleted");
define("_ADSLIGHT_ANNMOD2","Classified Ad selected has been modified");
define("_ADSLIGHT_NOMODIMG","Your Classified Ad includes a photo<br />(pictures may not be changed)");
define("_ADSLIGHT_DU","added on");   
define("_ADSLIGHT_MODIFBEFORE","Changes to this Classified Ad must be approved by the administrator and it will be queued for approval");
define("_ADSLIGHT_SENDBY","Added by:");
define("_ADSLIGHT_NUMANNN","Classified Ad No.:");
define("_ADSLIGHT_NEWPICT","New image:");
define("_ADSLIGHT_ACTUALPICT","Current image:");
define("_ADSLIGHT_DELPICT","Delete this image"); 
// contact.php //
define("_ADSLIGHT_CONTACTAUTOR","Contact the author of this Classified Ad");
define("_ADSLIGHT_TEXTAUTO","The message automatically sends the first three fields, your name, your email, and your telephone number, you don't need to enter them again in your message.");
define("_ADSLIGHT_YOURNAME","Your name:");
define("_ADSLIGHT_YOUREMAIL","Your email:");
define("_ADSLIGHT_YOURPHONE","Your telephone:");
define("_ADSLIGHT_YOURMESSAGE","Your message:");
define("_ADSLIGHT_VALIDMESS","Message is required.");
define("_ADSLIGHT_MESSEND","your message has been sent...");
define("_ADSLIGHT_CLASSIFIED","Classified Ad ");
define("_ADSLIGHT_FROM","Submitted by ");
//contact form ip
define("_ADSLIGHT_YOUR_IP","Your IP is ");
define("_ADSLIGHT_IP_LOGGED"," and has been logged! Action will be taken on any abuse on this system.");
// message //
define("_ADSLIGHT_CONTACTAFTERANN","Reply to your Classified ad");
define("_ADSLIGHT_MESSFROM","Message from");
define("_ADSLIGHT_FROMANNOF","from the classified ads on");
define("_ADSLIGHT_REMINDANN","You have a reply to your Classified Ad on ");
define("_ADSLIGHT_STARTMESS","Below is the reply to your Ad. ");
define("_ADSLIGHT_ENDMESS","!!!  Do not reply to this e-mail, it will not reach the sender. If you want to reply to the sender, use the contact information above.  !!!");
define("_ADSLIGHT_CANJOINT","You can reply to");
define("_ADSLIGHT_TO","at");
define("_ADSLIGHT_ORAT","or at ");
define("_ADSLIGHT_NOMAIL2","We do not give out users email addresses, to contact them by e-mail please use the contact form on our site, you can reply to the ad at the following address. ");
define("_ADSLIGHT_MESSAGE_END","End of message.");
define("_ADSLIGHT_SECURE_SEND","This message was sent using a secure contact form, the sender does not know your email address.");
// message end //
define("_ADSLIGHT_HOW_LONG","How long do you want the listing shown.");
define("_ADSLIGHT_WILL_LAST","This listing will last.");	
//for search on index page
define("_ADSLIGHT_SEARCHRESULTS","Classified Ads Search Results");
define("_ADSLIGHT_SEARCH_LISTINGS","Search Listings: ");
define("_ADSLIGHT_ALL_WORDS","All Words");
define("_ADSLIGHT_ANY_WORDS","Any Words");
define("_ADSLIGHT_EXACT_MATCH","Exact Match");
define("_ADSLIGHT_ONLYPIX","Show only<br /> Ads with photo");
define("_ADSLIGHT_SEARCH","Search");
define("_ADSLIGHT_REQUIRED","* required");
define("_ADSLIGHT_MY_ADS","All Ads from ");
define("_ADSLIGHT_VIEW_MY_ADS","View all ads from ");
define("_ADSLIGHT_COMMENTS_HEAD","<h3>Comments about this seller</h3>");
define("_ADSLIGHT_PREMIUM_DAY","<b> days, if you don't change it.</b> ");
define("_ADSLIGHT_PREMIUM_LONG_HEAD","<b>Your Classified Ad will start immediately</b> ");
define("_ADSLIGHT_PREMIUM_MEMBER","<b>Since you are a Premium Member, you can choose how long your ad will last.<br /><br />It will last </b>");
define("_ADSLIGHT_PREMIUM_MODERATED_HEAD","<b>Your Classified Ad will be queued for approval</b>");
// ADDED FOR RATINGS
define("_ADSLIGHT_TOPRATED","Top Rated");
define("_ADSLIGHT_RATINGC","Rating: ");
define("_ADSLIGHT_ONEVOTE","1 vote");
define("_ADSLIGHT_NUMVOTES","%s votes");
define("_ADSLIGHT_RATETHIS","Rate this User");
define("_ADSLIGHT_VOTEAPPRE","Your vote is appreciated.");
define("_ADSLIGHT_THANKURATE","Thank you for taking the time to rate this user here at %s.");
define("_ADSLIGHT_VOTEFROMYOU","Input from users such as yourself will help other visitors better decide which Ads to choose.");
define("_ADSLIGHT_VOTEONCE","Please do not vote for the same resource more than once.");
define("_ADSLIGHT_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define("_ADSLIGHT_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define("_ADSLIGHT_DONOTVOTE","Do not vote for your own resource.");
define("_ADSLIGHT_RATEIT","Rate It!");
define("_ADSLIGHT_RATING","Rating");
define("_ADSLIGHT_VOTE","Vote");
define("_ADSLIGHT_NORATING","No rating selected.");
define("_ADSLIGHT_CANTVOTEOWN","You cannot vote on the resource you submitted.<br />All votes are logged and reviewed.");
define("_ADSLIGHT_VOTEONCE2","Vote for the selected resource only once.<br />All votes are logged and reviewed.");
define("_ADSLIGHT_TOTALVOTES","Classified Ad Votes (total votes: %s)");
define("_ADSLIGHT_USERTOTALVOTES","Registered User Votes (total votes: %s)");
define("_ADSLIGHT_ANONTOTALVOTES","Anonymous User Votes (total votes: %s)");
define("_ADSLIGHT_USERAVG","User AVG Rating");
define("_ADSLIGHT_TOTALRATE","Total Ratings");
define("_ADSLIGHT_NOREGVOTES","No Registered User Votes");
define("_ADSLIGHT_NOUNREGVOTES","No Unregistered User Votes");
define("_ADSLIGHT_VOTEDELETED","Vote data deleted.");
define("_ADSLIGHT_USER_RATING","User Rating: ");
define("_ADSLIGHT_RATETHISUSER","Rate this User");
define("_ADSLIGHT_THANKURATEUSER","Thank you for taking the time to rate this User here at %s.");
define("_ADSLIGHT_RATETHISITEM","Rate this Item");
define("_ADSLIGHT_THANKURATEITEM","Thank you for taking the time to rate this item here at %s.");
define("_ADSLIGHT_MY_PRICE","Price");
define("_ADSLIGHT_MY_DATE","Date");
define("_ADSLIGHT_MY_TITLE","Title");
define("_ADSLIGHT_MY_LOCAL2","Location");
define("_ADSLIGHT_MY_VIEW","Viewed");
define("_ADSLIGHT_SORTBY","Sort by:");
define("_ADSLIGHT_CURSORTEDBY","Listings currently sorted by: %s");
define("_ADSLIGHT_POPULARITYLTOM","Popularity (Least to Most Hits)");
define("_ADSLIGHT_POPULARITYMTOL","Popularity (Most to Least Hits)");
define("_ADSLIGHT_TITLEATOZ","Title (A to Z)");
define("_ADSLIGHT_TITLEZTOA","Title (Z to A)");
define("_ADSLIGHT_DATEOLD","Date (Old Listings First)");
define("_ADSLIGHT_DATENEW","Date (New Listings First)");
define("_ADSLIGHT_RATINGLTOH","Rating (Lowest Score to Highest Score)");
define("_ADSLIGHT_RATINGHTOL","Rating (Highest Score to Lowest Score)");
define("_ADSLIGHT_PRICELTOH","Price (lowest to highest)");
define("_ADSLIGHT_PRICEHTOL","Price (highest to lowest)");
define("_ADSLIGHT_POPULARITY","Popularity");
define("_ADSLIGHT_ACTUALPICT2","Current second image:");
define("_ADSLIGHT_ACTUALPICT3","Current third image:");
define("_ADSLIGHT_NEWPICT2","New second image:");
define("_ADSLIGHT_NEWPICT3","New third image:");
define("_ADSLIGHT_SELECT_CONTACTBY","Select an option");
define("_ADSLIGHT_CONTACTBY","Contact me by:");
define("_ADSLIGHT_CONTACT_BY_EMAIL","E-mail");
define("_ADSLIGHT_CONTACT_BY_PM","Private Message(PM)");
define("_ADSLIGHT_CONTACT_BY_BOTH","Both E-mail or PM");
define("_ADSLIGHT_CONTACT_BY_PHONE","By phone only");
define("_ADSLIGHT_ORBY"," or by ");
define("_ADSLIGHT_PREMYOUHAVE","You have %s picture in your album.");
define("_ADSLIGHT_YOUHAVE","You have %s picture(s) in your album.");
define("_ADSLIGHT_YOUCANHAVE","As a Premium Member you can have up to %s picture(s).");
define("_ADSLIGHT_BMCANHAVE","As a Basic Member you can have only %s picture.");
define("_ADSLIGHT_UPGRADE_NOW","Upgrade to a Premium Member");
define("_ADSLIGHT_DELETE","Delete");
define("_ADSLIGHT_EDITDESC","Edit description");
define("_ADSLIGHT_TOKENEXPIRED","Your Security Token has Expired<br /> Try Again");
define("_ADSLIGHT_DESC_EDITED","Your image's description was edited succesfuly");
define("_ADSLIGHT_DELETED","Image deleted succesfuly");
define("_ADSLIGHT_SUBMIT_PIC_TITLE","Submit a Picture to Your Album");
define("_ADSLIGHT_SELECT_PHOTO","Select Photo");
define("_ADSLIGHT_CAPTION","Caption");
define("_ADSLIGHT_UPLOADPICTURE","Upload Picture");
define("_ADSLIGHT_YOUCANUPLOAD","You can upload only jpg's files up to %s KBytes");
define("_ADSLIGHT_ALBUMTITLE","%s's Album");
define("_ADSLIGHT_WELCOME","Welcome to your album");
define("_ADSLIGHT_NOTHINGYET","No pictures in this album yet");
define("_ADSLIGHT_NOCACHACA","Sorry no cachaca for you");
define("_ADSLIGHT_ADD_PHOTOS","Add Photos");
define("_ADSLIGHT_SHOWCASE","Gallery");
define("_ADSLIGHT_EDIT_CAPTION","Edit the caption");
define("_ADSLIGHT_EDIT","Edit");
define("_ADSLIGHT_SUBMITTER","Name:");
define("_ADSLIGHT_ADD_LISTING","Add a Listing");
define("_ADSLIGHT_SUBMIT","Submit");
define("_ADSLIGHT_PRICETYPE","Price Type:");
define("_ADSLIGHT_ADD_PHOTO_NOW","Do you want to add Photos Now");
define("_ADSLIGHT_ACTIVE","Active");
define("_ADSLIGHT_INACTIVE","Inactive");
define("_ADSLIGHT_SOLD","Reserved");
define("_ADSLIGHT_STATUS","Status");
define("_ADSLIGHT_REPLIES","Replies");
define("_ADSLIGHT_EXPIRES_ON","Expires on");
define("_ADSLIGHT_ADDED_ON","Added on");
define("_ADSLIGHT_REPLY_MESSAGE","Reply");
define("_ADSLIGHT_REPLIED_ON","Replied on: ");
define("_ADSLIGHT_VIEWNOW","view");
define("_ADSLIGHT_REPLY_TITLE","Replies for Listing ");
define("_ADSLIGHT_ALL_USER_LISTINGS","All Listings for ");
define("_ADSLIGHT_REPLY","Reply -");
define("_ADSLIGHT_PAGES","Page -");
define("_ADSLIGHT_REALNAME","Name");
define("_ADSLIGHT_VIEW_YOUR_LISTINGS","View Your Listings");
define("_ADSLIGHT_REPLIED_BY","Reply by: ");
define("_ADSLIGHT_DELETE_REPLY","Delete This Reply");
define("_ADSLIGHT_NO_REPLIES","There are no replies");
define("_ADSLIGHT_THANKS","Thank You for using our Classified Ads");
define("_ADSLIGHT_WEBMASTER","Webmaster");
define("_ADSLIGHT_YOUR_AD","Your ad");
define("_ADSLIGHT_AT","at");
define("_ADSLIGHT_VEDIT_AD","View or edit your ad here");
define("_ADSLIGHT_ALMOST","Your ad is about to expire");
define("_ADSLIGHT_EXPIRED","has expired and has been deleted.");
define("_ADSLIGHT_YOUR_AD_ON","Your ad on");
define("_ADSLIGHT_VU","Your Ad has been viewed");
define("_ADSLIGHT_TIMES","times.");
define("_ADSLIGHT_STOP","Your Classified Ad has expired");
define("_ADSLIGHT_SOON","is going to expire soon.");
define("_ADSLIGHT_MUSTLOGIN","You must login to reply to this Ad.");
define("_ADSLIGHT_VIEW_AD","View Your ad at");
define("_ADSLIGHT_MORE_PHOTOS","View more photos");
define("_ADSLIGHT_CANCEL","Cancel");
//Added for 1.2 RC1
define("_ADSLIGHT_ADDED_TO_CAT","A new listing has been added to the category ");
define("_ADSLIGHT_RECIEVING_NOTIF","You have subscribed to receive notifications of this sort.");
define("_ADSLIGHT_ERROR_NOTIF","If this is an error or you wish not to receive further such notifications, please update your subscriptions by visiting the link below:");
define("_ADSLIGHT_FOLLOW_LINK","Here is a link to the new listing");
define("_ADSLIGHT_YOU_CAN_VIEW_BELOW","You can view the full ad at the link below");
define("_ADSLIGHT_LISTING_NUMBER","Listing Number:");
define("_ADSLIGHT_NOREPLY","!!!  Do not reply to this e-mail, you will not get a reply.  !!!");
define("_ADSLIGHT_CAPTCHA","Security Code:");
define("_ADSLIGHT_NEW_WAITING_SUBJECT","New Ad! Waiting approval.");
define("_ADSLIGHT_NEW_WAITING","There is a new listing waiting to be moderated.");
define("_ADSLIGHT_PLEASE_CHECK","Please click the URL below to check this ad.");
define("_ADSLIGHT_ADMIN","Administrator");
define("_ADSLIGHT_NEWAD","The new listing is below.");
define("_ADSLIGHT_NEED_TO_LOGIN","You will need to be logged in.");
////AJOUTE PAR ILUC////
define("_ADSLIGHT_PROFILE","Profile of ");
define("_ADSLIGHT_MI_ADSLIGHT_SMENU1","Your Listings");
define("_ADSLIGHT_MI_ADSLIGHT_SMENU2","Submit");
define("_ADSLIGHT_MI_ADSLIGHT_SMENU3","Search");
//viewads.php
define("_ADSLIGHT_ALERTEABUS","Report Abuse");
define("_ADSLIGHT_CONTACT_SUBMITTER","Contact");
define("_ADSLIGHT_SENDFRIENDS","Email this listing");
//report-abuse.php
define("_ADSLIGHT_REPORTSENDTO","<b>Report this ad:</b><br/><br/>ad No. ");
define("_ADSLIGHT_REPORTANNSEND","Thank you for your help!<br/>The ad just be reported to Admin.");
define("_ADSLIGHT_REPORTSUBJET","[Alert] An ad undesirable ");
define("_ADSLIGHT_REPORTMESSAGE","Believes that this announcement is illegal and you wanted to know.");
//index.php >> Infos Bulle //
define("_ADSLIGHT_ADD_LISTING_BULL","To add a listing<br />Thank you for ");
define("_ADSLIGHT_ADD_LISTING_SUB","register");
define("_ADSLIGHT_ADD_LISTING_BULLOK","You can add or<br />Ads: ");
define("_ADSLIGHT_ADD_LISTING_SUBOK","Click here");
//index.php >> Title Menu //
define("_ADSLIGHT_ADD_TITLEMENU1","Edit/delete your ads, or also reported as [Reserved] ...");
define("_ADSLIGHT_ADD_TITLEMENU2","Post a free ad, if you can browse the categories.");
define("_ADSLIGHT_ADD_TITLEMENU4","All good tips for writing ads.");
define("_ADSLIGHT_ADD_TITLEMENU5","Search Cyble or research in your area.");
define("_ADSLIGHT_ADD_TITLEMENU6","Read and send pm.");
define("_ADSLIGHT_ADD_TITLEMENU7","You have a new message.");
define("_ADSLIGHT_ADD_TITLEMENU8","You must be logged in to read your messages.");
define("_ADSLIGHT_ADD_TITLEMENU9","You must be logged in to see your profile.");
define("_ADSLIGHT_ADD_TITLEMENU10","View or edit your profile here.");
//viewcats.php >> Infos Bulle //
define("_ADSLIGHT_ADD_LISTING_BULLCATS","You can add or<br />ads in this category<br />");
define("_ADSLIGHT_ADD_LISTING_BULLCATSOK","To add one or<br />ads in this category<br />Thank you for ");
// Reserved
//define("_ADSLIGHT_RESERVED","Reserved");
// tips_writing_ad.php
define("_ADSLIGHT_TIPSWRITE","All good advice<br />write your ads for many");
define("_ADSLIGHT_TIPSWRITE_TITLE","The Tips on writing your ad");
define("_ADSLIGHT_TIPSWRITE_TEXT","<strong> 1. One or more photos </strong> <br /> <br />
The first contact with visitors qu'auront your ad will be a photo or photos of the item you sell. <br /> It is advisable to put a photo or photos of your object. <Br />
An ad with photo is viewed 7 times more than an ad without a photo! <br /> It also gives a first idea of ??the state of your object. <br /> <br /> <br />
- A proper object is always more attractive. <br /> - Heal the quality of the photo. (Not too dark) <br />
- Frame the object so that it is visible. <br />
- Avoid photos 'fuzzy' <br /> <br /> <strong> 2. Clear and detailed </ strong> <br /> <br />
After carefully preparing the photos of the item you sell. <br /> Now you need to write the ad. <br /> <br />
- Avoid language 'SMS', it is imperative that the announcement is clearly visible. <br /> Otherwise, you lose your chance to sell spare parts. <Br /> <br />
- The title in uppercase and any ad written in uppercase, <br /> is strongly discouraged. <br /> <br />
- Superlatives are avoided. <br /> <br />
- Write down all the details and make sure that visitors can best identify your object. <br /> Otherwise they will contact you by email or telephone to ask you. (Loss of time for you and the buyer) <br /> <br />
- Do not write a novel, it must remain an ad. <br /> <br />
- The visitor must be able to obtain a maximum of information when reading your ad, and that quickly. <br /> <br />
More an ad is clear and precise, it has a chance to reach a deal. <br /> <br /> <strong>
And do not forget, a good deal <br /> this is when the buyer and seller find their happiness </strong>");
//version 1.053
// maps.php
define("_ADSLIGHT_MAPS_TITLE","Search by region");
define("_ADSLIGHT_MAPS_TEXT","Select a region on the map<br />to see the ads in a region.");
//viewads.php
define("_ADSLIGHT_NOCLAS","There are currently no advertisements<br />in this category ...");
//version 1.063
// viawcats.php
define("_ADSLIGHT_ADD_LISTING_NOTADDSINTHISCAT","There are no ads in this category.<br />");
define("_ADSLIGHT_ADD_LISTING_NOTADDSSUBMIT","Add Ad");
//version 1.064
define("_ADSLIGHT_CATPLUS","<br/>&#187;&nbsp;More ...");
/* AdsLight 2 */
define("_ADSLIGHT_RESERVED","This ad is: <br /> <font color='red'> <strong> [Reserved] </ strong> </ font>");
define("_ADSLIGHT_RESERVEDMEMBER","<strong> Status: </ strong> <font color='red'> <strong> [Reserved] </ strong> </ font>");
// Xpayment //
define("_MN_ADSLIGHT_PURCHASE","Buy It Now");
define("_MN_ADSLIGHT_YOURNAME","Establish invoice:");
define("_MN_ADSLIGHT_YOUREMAIL","Billing Email:");

//2.2 Beta 2
define("_MN_ADSLIGHT_ERROR404","Error 404");
define("_MN_ADSLIGHT_ERROR404_TEXT",'<table class="errorMsg" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<center>
	<h1>Error 404</h1><br /><br />
</center>
</td>
</tr>
<tr>
<td>
<br />
<b><font size="3">The page does not exist or has been moved</font></b><br />
<br /><b>Did you enter this address directly into your browser? ?</b><br />
- Check your entry and try again.<br /><br />
<b>Did you click on a link? ?</b><br />
-  Maybe it is an old link that is no longer valid..<br />
<p>You can also use our <u><a href="search.php">search engine</a></u><br />
</td>
</tr>
</table>');

define("_ADSLIGHT_CONTACTBY2"," by:");