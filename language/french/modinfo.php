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
define('_MI_ADSLIGHT_NAME', 'AdsLight');
define('_MI_ADSLIGHT_MENUADD', 'Ajouter une annonce');
// A brief description of this module
define('_MI_ADSLIGHT_DESC', 'Module de petites annonces');
// Names of blocks for this module (Not all module has blocks)
define('_MI_ADSLIGHT_BNAME1', 'Annonces récentes');
define('_MI_ADSLIGHT_BNAME1_DESC', 'Les derniers blocs d\'annonces');
define('_MI_ADSLIGHT_BNAME2', 'Top annonces');
define('_MI_ADSLIGHT_BNAME2_DESC', 'Bloc des Top annonces');
// Names of admin menu items
define('_MI_ADSLIGHT_ADMENU0', 'Tableau de bord');
define('_MI_ADSLIGHT_ADMENU1', 'Catégories');
define('_MI_ADSLIGHT_ADMENU2', 'Permissions');
define('_MI_ADSLIGHT_ADMENU3', 'Blocs');
define('_MI_ADSLIGHT_ADMENU4', 'Préférences');
define('_MI_ADSLIGHT_ADMENU5', 'Types');
define('_MI_ADSLIGHT_ADMENU6', 'Importer');
define('_MI_ADSLIGHT_ADMENU7', 'À propos');
define('_MI_ADSLIGHT_ADMENU8', 'Documentation');
define('_MI_ADSLIGHT_ADMENU9', 'Aller au Module');
define('_MI_ADSLIGHT_ADMENU10', 'Mise à jour');
define('_MI_ADSLIGHT_ADMENU11', 'Forum Entraide');
define('_MI_ADSLIGHT_CONFSAVE', 'Configuration sauvegardée');
define('_MI_ADSLIGHT_CANPOST', 'Les utilisateurs anonymes peuvent envoyer des annonces :');
define('_MI_ADSLIGHT_PERPAGE', 'Annonces par page :');
define('_MI_ADSLIGHT_RES_PERPAGE', 'Résumés par page :');
define('_MI_ADSLIGHT_CURRENCY_SYMBOL', 'Symbole de la devise :<br />exemple pour l\'euro (&#8364;)');
define('_MI_ADSLIGHT_KOIVI', 'Utiliser l\'éditeur Koivi :');
define('_MI_ADSLIGHT_NUMNEW', 'Nombre de nouvelles annonces :');
define('_MI_ADSLIGHT_MODERAT', 'Modérer l\'accès des annonces :');
define('_MI_ADSLIGHT_DAYS', 'Durée de l\'annonce :');
define('_MI_ADSLIGHT_MAXIIMGS', 'Taille maximale des photos :');
define('_MI_ADSLIGHT_MAXWIDE', 'Largeur maximale des photos :');
define('_MI_ADSLIGHT_MAXHIGH', 'Hauteur maximale des photos :');
define('_MI_ADSLIGHT_INBYTES', 'en octets');
define('_MI_ADSLIGHT_INPIXEL', 'en pixels');
define('_MI_ADSLIGHT_INDAYS', 'en jours');
define('_MI_ADSLIGHT_MUSTLOGIN', 'Autoriser les utilisateurs anonymes à répondre à une petite annonce.');
define('_MI_ADSLIGHT_THRUMAIL', 'utiliser l\'envoi par courriel (Il est recommandé de répondre non, à cause du spam .)');
define('_MI_ADSLIGHT_TYPEBLOC', 'Type de bloc :');
define('_MI_ADSLIGHT_JOBRAND', 'Annonce au hazard');
define('_MI_ADSLIGHT_LASTTEN', 'Les 10 dernières annonces');
define('_MI_ADSLIGHT_NEWTIME', 'Durée pendant laquelle l\'annonce est considérée comme nouvelle :');
define('_MI_ADSLIGHT_DISPLPRICE', 'Affichage des prix :');
define('_MI_ADSLIGHT_DISPLPRICE2', 'Affichage des prix :');
define('_MI_ADSLIGHT_INTHISCAT', 'dans cette catégorie');
define('_MI_ADSLIGHT_DISPLSUBCAT', 'Affichage des sous-catégories :');
define('_MI_ADSLIGHT_ONHOME', 'sur la page d\'accueil du module');
define('_MI_ADSLIGHT_NBDISPLSUBCAT', 'Nombre de sous-catégories à afficher :');
define('_MI_ADSLIGHT_IF', 'si');
define('_MI_ADSLIGHT_ISAT', 'est à');
define('_MI_ADSLIGHT_VIEWNEWCLASS', 'Afficher les nouvelles annonces :');
define('_MI_ADSLIGHT_ORDERALPHA', 'Tri alphabétique');
define('_MI_ADSLIGHT_ORDERPERSO', 'Tri personnalisé');
define('_MI_ADSLIGHT_CSORT_ORDER', 'Tri par défaut des catégories');
define('_MI_ADSLIGHT_LSORT_ORDER', 'Tri par défaut des propositions');
define('_MI_ADSLIGHT_ORDER_TITLE', 'Trier les propositions par titre');
define('_MI_ADSLIGHT_ORDER_PRICE', 'Classement des annonces par prix');
define('_MI_ADSLIGHT_ORDER_DATE', 'Trier les propositions par date (défaut)');
define('_MI_ADSLIGHT_ORDER_POP', 'Trier les propositions par nombre de clics');
define('_MI_ADSLIGHT_DBUPDATED', 'La base de données a été mise à jour. ');
define('_MI_ADSLIGHT_GPERM_G_ADD', 'Peut ajouter');
define('_MI_ADSLIGHT_CAT2GROUPDESC', 'Vérifiez les catégories dont vous autorisez l\'accès');
define('_MI_ADSLIGHT_GROUPPERMDESC', 'Choisir le(s) groupe(s) autorisés à soumettre des annonces.');
define('_MI_ADSLIGHT_GROUPPERM', 'Permissions de soumission');
define('_MI_ADSLIGHT_SUBMITFORM', 'Permissions de déposer une annonce');
define('_MI_ADSLIGHT_SUBMITFORM_DESC', 'Sélectionnez qui peut présenter des annonces');
define('_MI_ADSLIGHT_VIEWFORM', 'Permissions de voir les annonces');
define('_MI_ADSLIGHT_VIEWFORM_DESC', 'Sélectionnez les groupes qui peuvent voir les annonces');
define('_MI_ADSLIGHT_VIEW_RESUMEFORM_DESC', 'Sélectionner qui peut voir les CV');
define('_MI_ADSLIGHT_SUPPORT', 'Aider ce logiciel');
define('_MI_ADSLIGHT_OP', 'Lire mon avis');
define('_MI_ADSLIGHT_PREMIUM', 'Annonces Premium');
define('_MI_ADSLIGHT_PREMIUM_DESC', 'Qui peut choisir la durée de validité des offres');
define('_MI_ADSLIGHT_CATEGORY_NOTIFY', 'Categorie');
define('_MI_ADSLIGHT_CATEGORY_NOTIFYDSC', 'Options de notification s\'appliquant à la catégorie courante.');
define('_MI_ADSLIGHT_NOTIFY', 'Annonce');
define('_MI_ADSLIGHT_NOTIFYDSC', 'Options de notification qui s\'appliquent à l\'annonce actuelle. ');
define('_MI_ADSLIGHT_GLOBAL_NOTIFY', 'Globale');
define('_MI_ADSLIGHT_GLOBAL_NOTIFYDSC', 'Options globales de notification de l\'annonce.');
define('_MI_ADSLIGHT_NEWPOST_NOTIFY', 'Nouvelle inscription');
define('_MI_ADSLIGHT_NEWPOST_NOTIFYCAP', 'Avertissez-moi des nouvelles annonces dans la catégorie actuelle. ');
define('_MI_ADSLIGHT_NEWPOST_NOTIFYDSC', 'Recevoir un avis quand un nouveau référencement est affiché dans la catégorie courante. ');
define('_MI_ADSLIGHT_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Nouvelle annonce dans la catégorie ');
define('_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFY', 'Nouvelle inscription');
define('_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFYCAP', 'Avertissez-moi des nouvelles annonces dans la catégorie actuelle. ');
define('_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFYDSC', 'Recevoir un avis quand un nouveau référencement est affiché dans la catégorie courante. ');
define('_MI_ADSLIGHT_VALIDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Nouvelle annonce dans la catégorie ');
define('_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFY', 'Annonce mise à jour');
define('_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFYCAP', 'Avertissez-moi des mises à jour des annonces de la catégorie en cours. ');
define('_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFYDSC', 'Recevoir une notification lorsque l\'annonce est mise à jour dans la catégorie en cours.');
define('_MI_ADSLIGHT_UPDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Nouvelle annonce dans la catégorie ');
define('_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFY', 'Annonce supprimée ');
define('_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFYCAP', 'Avertissez-moi des nouvelles annonces dans la catégorie actuelle. ');
define('_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFYDSC', 'Recevoir une notification lorsque l\'annonce est supprimée de la catégorie en cours.');
define('_MI_ADSLIGHT_DELETE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Nouvelle annonce dans la catégorie ');
define('_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFY', 'Nouvelle inscription');
define('_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYCAP', 'Avertissez-moi des nouvelles annonces dans toutes les catégories. ');
define('_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYDSC', 'Recevoir un avis quand un nouveau référencement est affiché dans toutes les catégories. ');
define('_MI_ADSLIGHT_GLOBAL_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Nouvelle annonce dans la catégorie ');
define('_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFY', 'Nouvelle inscription');
define('_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFYCAP', 'Avertissez-moi des nouvelles annonces dans toutes les catégories. ');
define('_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFYDSC', 'Recevoir un avis quand un nouveau référencement est affiché dans toutes les catégories. ');
define('_MI_ADSLIGHT_GLOBAL_VALIDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Nouvelle annonce dans la catégorie ');
define('_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFY', 'Annonce mise à jour');
define('_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFYCAP', 'Avertissez-moi des mises à jour des annonces dans toutes les catégories. ');
define('_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFYDSC', 'Recevoir une notification lorsque la annonce est mise à jour dans toutes les catégories.');
define('_MI_ADSLIGHT_GLOBAL_UPDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Annonce mise à jour dans certaines des catégories');
define('_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFY', 'Annonce supprimée ');
define('_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFYCAP', 'Avertissez-moi des annonces supprimées dans toutes les catégories. ');
define('_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFYDSC', 'Recevoir une notification lorsqu\'une annonce est supprimée dans toutes les catégories.');
define('_MI_ADSLIGHT_GLOBAL_DELETE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} : notification automatique : Annonce supprimée dans des catégories');
define('_MI_ADSLIGHT_RATE_USER', 'Autoriser les utilisateurs à évaluer les vendeurs');
define('_MI_ADSLIGHT_RATE_ITEM', 'Autoriser les utilisateurs à évaluer les annonces');
define('_MI_ADSLIGHT_DIFF_NAME', 'Autoriser les utilisateurs à utiliser plusieurs noms');
define('_MI_ADSLIGHT_DIFF_EMAIL', 'Autoriser les utilisateurs à utiliser différentes adresses électroniques');
define('_MI_ADSLIGHT_NOT_PREMIUM', 'Nombre d\'images - Non Premium');
define('_MI_ADSLIGHT_NOT_PREMIUM_DESC', 'Nombre d\'images qu\'un utilisateur non-premium peut avoir sur sa page');
define('_MI_ADSLIGHT_NUMBPICT_TITLE', 'Nombre d\'images - Premium');
define('_MI_ADSLIGHT_NUMBPICT_DESC', 'Nombre d\'images qu\'un utilisateur Premium peut avoir sur sa page');
define('_MI_ADSLIGHT_SMNAME1', 'Envoyer');
define('_MI_ADSLIGHT_THUMW_TITLE', 'Largeur vignette');
define('_MI_ADSLIGHT_THUMBW_DESC', 'Largeur de la vignette en pixels <br /> Cela signifie que la vignette de votre photo aura <br /> au plus cette taille en largeur <br /> Toutes proportions gardées');
define('_MI_ADSLIGHT_THUMBH_TITLE', 'Hauteur vignette');
define('_MI_ADSLIGHT_THUMBH_DESC', 'Hauteur de la vignette en pixels <br /> Cela signifie que la vignette de votre photo aura <br /> au plus cette taille en hauteur <br /> Toutes proportions gardées');
define('_MI_ADSLIGHT_RESIZEDW_TITLE', 'Largeur d\'image affichée');
define('_MI_ADSLIGHT_RESIZEDW_DESC', 'Réglage de la largeur de l\'image en pixels <br /> Cela signifie que votre image aura <br /> au plus cette taille en largeur <br /> Toutes proportions gardées <br /> Si la photo originale est plus grande que cette taille, elle sera redimensionnée pour ne pas déstructurer votre mise en page');
define('_MI_ADSLIGHT_RESIZEDH_TITLE', 'Hauteur d\'image affichée');
define('_MI_ADSLIGHT_RESIZEDH_DESC', 'Réglage de la hauteur de l\'image en pixels <br /> Cela signifie que votre image aura <br /> au plus cette taille en hauteur <br /> Toutes proportions gardées <br /> Si la photo originale est plus grande que cette taille, elle sera redimensionnée pour ne pas déstructurer votre mise en page');
define('_MI_ADSLIGHT_ORIGW_TITLE', 'Largeur maxi de l\'image originale');
define('_MI_ADSLIGHT_ORIGW_DESC', "Largeur maximale en pixels de la photo originale <br /> Cela signifie que l'image originale de l'utilisateur ne peut excéder cette taille en largeur ou bien elle ne sera pas transférée");
define('_MI_ADSLIGHT_ORIGH_TITLE', 'Hauteur maxi de l\'image originale');
define('_MI_ADSLIGHT_ORIGH_DESC', "Hauteur maximale de l'image originale en pixels <br /> Cela signifie que l'image originale de l'utilisateur ne peut excéder cette taille en hauteur ou bien elle ne sera pas transférée");
define('_MI_ADSLIGHT_UPLOAD_TITLE', 'Chemin des fichiers téléchargés');
define('_MI_ADSLIGHT_UPLOAD_DESC', 'Chemin vers votre répertoire d\'envoi <br /> dans linux cela devrait ressembler à /var/www/uploads <br /> et dans Windows à C:/Program Files/www/uploads');
define('_MI_ADSLIGHT_LINKUPLOAD_TI', 'Lien vers votre répertoire de téléchargement');
define('_MI_ADSLIGHT_LINKUPLOAD_DE', 'C\'est l\'adresse de la racine de vos téléchargements<br /> exemple : http://www.votresite.com/uploads');
define('_MI_ADSLIGHT_MAXFILEBYTES_T', 'Taille maxi en octets');
define('_MI_ADSLIGHT_MAXFILEBYTES_D', 'C\'est la taille maximale qu\'un fichier image peut avoir en octets <br /> exemple : 512000 pour 500 KB');
define('_MI_ADSLIGHT_EDITOR', 'Éditeur à utiliser :');
define('_MI_ADSLIGHT_LIST_EDITORS', 'Choisissez l\'éditeur à utiliser.');
define('_MI_ADSLIGHT_LIGHTBOX', 'Effet Lightbox');
define('_MI_ADSLIGHT_LIGHTBOX_DESC', 'Utiliser l\'effet lightbox pour voir les photos..');
define('_MI_ADSLIGHT_USE_COUNTRY', 'Utiliser les champs Pays');
define('_MI_ADSLIGHT_USE_COUNTRY_DESC', " S'il est réglé sur « Non », le Pays ne sera pas affiché");
define('_MI_ADSLIGHT_SOLD_DAYS', ' Durée d\'affichage de l\'annonce après qu\'elle soit classée comme Vendue');
define('_MI_ADSLIGHT_SOLDINDAYS', 'en jours');
define('_MI_ADSLIGHT_ALMOST', ' Quand prévenir que l\'annonce est sur le point d\'expirer ?');
define('_MI_ADSLIGHT_ALMOST_DESC', 'en jours');
define('_MI_ADSLIGHT_MAIN_CAT', ' Permet aux utilisateurs d\'ajouter des annonces dans les catégories principales');
define('_MI_ADSLIGHT_MAIN_CAT_DESC', ' ou juste de permettre l\'ajout d\'annonces dans les sous-catégories');
define('_MI_ADSLIGHT_ADMIN_EDITOR', 'Éditeur à utiliser par l\'administrateur :');
define('_MI_ADSLIGHT_LIST_ADMIN_EDITORS', 'Sélectionnez l\'éditeur à utiliser pour le côté admin.');
define('_MI_ADSLIGHT_CAT_DESC', ' Code Google AdSense ou bannière');
define('_MI_ADSLIGHT_DESC_CAT_DESC', ' Permet d\'ajouter un code Google AdSense ou une bannière publicitaire pour chaque catégorie.');
define('_MI_ADSLIGHT_MUST_ADD_CAT', ' Vous devez ajouter une première catégorie.');
define('_MI_ADSLIGHT_BNAME3', ' Annonces récentes + photos');
define('_MI_ADSLIGHT_BNAME3_DESC', ' Annonces récentes + bloc photos');
define('_MI_ADSLIGHT_BNAME4', 'Top Annonces + photos');
define('_MI_ADSLIGHT_BNAME4_DESC', 'Top Annonces + bloc photos');
define('_MI_ADSLIGHT_USE_CAPTCHA', 'Utiliser un captcha');
define('_MI_ADSLIGHT_USE_CAPTCHA_DESC', 'Sélectionnez pour utiliser le Captcha');
// Menu AdsLight
define('_MI_ADSLIGHT_SMENU1', 'Vos annonces');
define('_MI_ADSLIGHT_SMENU2', 'Envoyer');
define('_MI_ADSLIGHT_SMENU3', 'Recherche');
// support.php
define('_MI_ADSLIGHT_SUPPORT01', 'Si vous souhaitez proposer une amélioration :<br /><br />- Une correction<br />- Une traduction<br />- Une suggestion<br />- Signaler un Bogue<br />');
define('_MI_ADSLIGHT_SUPPORT02', 'Rejoignez-nous sur le forum de support d\'AdsLight');
define('_MI_ADSLIGHT_SUPPORT03', '> Forum de support AdsLight <');
// Bloc Carte de France
define('_MI_ADSLIGHT_MAPFRANCE', 'Carte de France');
define('_MI_ADSLIGHT_MAPFRANCE_DESC', 'Dans votre région');
// Bloc Ajouter une annonce
define('_MI_ADSLIGHT_ADDMENU', 'Ajouter une annonce');
define('_MI_ADSLIGHT_ADDMENU_DESC', 'Menu');
// version Adslight 1.0.2
// Active RewriteUrl
define('_MI_ADSLIGHT_ACTIVE_REWRITEURL', 'Activer la réécriture d\'URL');
define('_MI_ADSLIGHT_ACTIVE_REWRITEURL_DESC', 'Permet d\'activer la réécriture d\'URL, pour un meilleur référencement.<br /> Pour l\'installer, merci de lire le fichier README.txt');
// Activer thumbs_index
define('_MI_ADSLIGHT_ACTIVE_THUMBSINDEX', 'Activer les photos miniatures en page d\'index');
define('_MI_ADSLIGHT_ACTIVE_THUMBSINDEX_DESC', "Enable the display of thumbnail images to homepage<br>If it is off an icon <img src='" . XOOPS_URL . "/modules/adslight/assets/images/camera_photo.png' width='24px' ><br> indicates whether or not there is a picture in the ad.");
// Activer thumbs_cats
define('_MI_ADSLIGHT_ACTIVE_THUMBSCATS', 'Activer les photos miniatures dans les catégories');
define('_MI_ADSLIGHT_ACTIVE_THUMBSCATS_DESC', "Enable the display of thumbnail pictures in the categories<br>If it is off an icon <img src='" . XOOPS_URL . "/modules/adslight/assets/images/camera_photo.png' width='24px' ><br> indicates whether or not there is a picture in the ad.");
// Code Adscence index
define('_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE', 'Code supplémentaire sur la page d\'accueil');
define('_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_DESC', 'Placez votre code adsense ou autre ici');
define('_MI_ADSLIGHT_ADSLIGHT_USE_INDEX_CODE', 'Utiliser un code supplémentaire sur la page d\'accueil');
define('_MI_ADSLIGHT_ADSLIGHT_USE_INDEX_CODE_DESC', 'Le code supplémentaire sera affiché entre les annonces<br />sur la page d\'accueil et la page des catégories.<br /><br />Bannières, code Adsense, etc ...');
define('_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_PLACE', 'Place où le code sera affiché dans la liste ');
define('_MI_ADSLIGHT_ADSLIGHT_INDEX_CODE_PLACE_DESC', 'Ex : Si vous choisissez 4 il y aura 3 annonces avant ce code.<br />Le code sera affiché en 4ème position.');
define('_MI_ADSLIGHT_ADSLIGHT_USE_BANNER', 'Utiliser le code bannières de Xoops');
define('_MI_ADSLIGHT_ADSLIGHT_USE_BANNER_DESC', 'vous permettra d\'insérer des bannières Xoops entre les annonces.<br />Si vous choisissez Oui<br />N\'insérez <strong>AUCUN</strong> code ci-dessous');
// Version 1.0.5
// Code Adscence Catégories
define('_MI_ADSLIGHT_ADSLIGHT_CATS_CODE', 'Code pub sur les pages catégories');
define('_MI_ADSLIGHT_ADSLIGHT_CATS_CODE_DESC', 'Code Google Adsence ou code d\'une bannière :<br />Format : width = 300 height = 250');
// adslight_theme_set
define('_MI_ADSLIGHT_THEMESET', 'Choix du thème d\'ensemble');
// Méta Description / keywords Categories
define('_MI_ADSLIGHT_CAT_META', 'Permettre la saisie Méta description, Méta mots-clés des catégories ?');
define('_MI_ADSLIGHT_CAT_META_DESCRIPTION', "Si vous positionnez cette option sur « Oui »,<br />Vous pourrez écrire les données méta mots-clés et description pour chaque catégories<br /> Recommandé pour un meilleur référencement.");
// Version 1.0.51
// tips_writing_ad.php
define('_MI_ADSLIGHT_USE_TIPS_WRITE', 'Personnaliser la page infos / conseils ?');
define('_MI_ADSLIGHT_USEDESC_TIPS_WRITE', "Si vous sélectionnez « Oui », vous pouvez écrire ci-dessous le titre et le texte de la page infos / conseils");
define('_MI_ADSLIGHT_TITLE_TIPS_WRITE', 'Titre de la page infos / conseils');
define('_MI_ADSLIGHT_TITLEDESC_TIPS_WRITE', 'Écrivez ici le titre qui s\'affichera sur la page infos / conseils');
define('_MI_ADSLIGHT_TEXT_TIPS_WRITE', 'Texte de la page infos / conseils');
define('_MI_ADSLIGHT_TEXTDESC_TIPS_WRITE', 'Écrivez ici le texte qui s\'affichera sur la page infos / conseils');
// Version 1.0.53
// adslight_maps
define('_MI_ADSLIGHT_MAPSSET', 'Choisissez la carte de votre pays');
define('_MI_ADSLIGHT_MAPSSET_DESC', 'Si vous souhaitez créer la carte de votre pays.<br />Merci de lire la documentation du module (dans /docs).');
define('_MI_ADSLIGHT_MAPSW_TITLE', 'Largeur de la carte');
define('_MI_ADSLIGHT_MAPSH_TITLE', 'Hauteur de la carte');
/*  Adslight 2  */
define('_MI_ADSLIGHT_ACTIVE_MENU', 'Activer le menu ?');
define('_MI_ADSLIGHT_USEDESC_ACTIVEMENU', "Activation du menu sur les pages du module");
define('_MI_ADSLIGHT_ACTIVE_RSS', 'Activer le flux rss?');
define('_MI_ADSLIGHT_USEDESC_ACTIVERSS', 'Activation de la page des flux RSS');
define('_MI_ADSLIGHT_ACTIVE_BOOKMARK', 'Activer les boutons des réseaux sociaux');
define('_MI_ADSLIGHT_USEDESC_ACTIVEBOOKMARK', 'Activation des signets sur les annonces.');
define('_MI_ADSLIGHT_STBOOKMARK', 'Choisir le style de signet (Twitter, Facebook, Google...)');
define('_MI_ADSLIGHT_DESC_STBOOKMARK', '3 styles de signets sont disponibles.');
define('_MI_ADSLIGHT_ACTIVE_XPAYMENT', 'Activer le Module Xpayment');
define('_MI_ADSLIGHT_TEXTDESC_XPAYMENT', 'Le module Xpayment doit être installé');
// Xpayment
define('_MI_ADSLIGHT_PURCHASE', 'Disponibles à l\'achat');
define('_MI_ADSLIGHT_PURCHASE_DESC', 'Il s\'agit des catégories qui sont disponibles à l\'achat sur Xpayment');
define('_MI_ADSLIGHT_CURRENCY_CODE', 'Code ISO pour les devises');
define('_MI_ADSLIGHT_LISTING_COST', 'L\'inscription sera facturée à ce taux par jour');
define('_MI_ADSLIGHT_PURCHASELISTING', 'Inscriptions achetables');
define('_MI_ADSLIGHT_PURCHASELISTING_DESC', 'Ce sont les catégories payables par jour, pour afficher une annonce');
//2.2

define('_MI_ADSLIGHT_ADVISE_TITLE', 'Notre conseil');
define('_MI_ADSLIGHT_ADVISE_TEXT', 'Voici votre texte');
//Help
define('_MI_ADSLIGHT_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_ADSLIGHT_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_ADSLIGHT_BACK_2_ADMIN', 'Retour à l\'administration de ');
define('_MI_ADSLIGHT_HELP_OVERVIEW', 'Vue d\'ensemble');

//2.2
define('_MI_ADSLIGHT_CURRENCY_POSITION', 'Position du symbole monétaire ?');
define('_MI_ADSLIGHT_CURRENCY_POSITION_DESC', 'Oui = gauche (par exemple $ 25 ), Non = droite (par exemple 25 €)');
// Settings
define('_MI_ADSLIGHT_SETTING_1', '€uro(s)');
define('_MI_ADSLIGHT_SETTING_2', '$');
define('_MI_ADSLIGHT_SETTING_3', '2');
define('_MI_ADSLIGHT_SETTING_4', '1');
define('_MI_ADSLIGHT_SETTING_5', '[espace]');
define('_MI_ADSLIGHT_SETTING_6', '.');
define('_MI_ADSLIGHT_SETTING_7', '0');

define('_MI_ADSLIGHT_HOME', 'Accueil');
define('_MI_ADSLIGHT_ABOUT', 'À propos');

define('MI_ADSLIGHT_ADMENU2', 'Annonce');
