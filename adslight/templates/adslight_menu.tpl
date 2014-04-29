<div align="center">
<a href="<{$xoops_url}>/modules/adslight/"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/back_alt.png" border="0" title="index" alt="index"/></a>
&nbsp;&nbsp;<img src="<{$xoops_url}>/modules/adslight/assets/images/menu/line_verti.png" border="0" alt="Line"/>&nbsp;&nbsp;
<{if !$xoops_isuser}>
<a href="<{$xoops_url}>/register.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/edit2.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU1}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU1}>" /></a>&nbsp;

<a href="<{$xoops_url}>/register.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/newdoc2.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU2}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU2}>" /></a>&nbsp;

<a href="<{$xoops_url}>/register.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/status_offline.png" border="0"  title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU9}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU9}>"  /></a>&nbsp;

<a href="<{$xoops_url}>/viewpmsg.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/noacces-mail.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU8}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU8}>" /></a>&nbsp;

<{else}>
<a href="<{$show_user_link}>"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/edit.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU1}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU1}>" /></a>&nbsp;

<a href="<{$xoops_url}>/modules/adslight/add.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/newdoc.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU2}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU2}>" /></a>&nbsp;

<a href="<{$xoops_url}>/user.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/status_online.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU10}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU10}>" /></a>&nbsp;

<{xoInboxCount assign=pmcount}>
<{if $pmcount}>
<a href="<{$xoops_url}>/viewpmsg.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/unread-mail.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU7}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU7}>" /></a>&nbsp;

<{else}>
<a href="<{$xoops_url}>/viewpmsg.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/read-mail.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU6}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU6}>" /></a>&nbsp;

<{/if}>
<{/if}>
&nbsp;&nbsp;<img src="<{$xoops_url}>/modules/adslight/assets/images/menu/line_verti.png" border="0" alt="Line"/>&nbsp;&nbsp;
<a href="<{$xoops_url}>/modules/adslight/tips_writing_ad.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/info.png" title="Info" title="Info" border="0" /></a>&nbsp;
<a href="<{$xoops_url}>/modules/adslight/maps.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/search.png" border="0" title="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU5}>" alt="<{$smarty.const._ADSLIGHT_ADD_TITLEMENU5}>" /></a>&nbsp;

<{if $adslight_active_rss == 1 }>
<a href="<{$xoops_url}>/modules/adslight/fluxrss.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/rss2.png" title="Rss" alt="Rss" border="0" /></a>
<{/if}>
<{if $xoops_isadmin}>
&nbsp;&nbsp;<img src="<{$xoops_url}>/modules/adslight/assets/images/menu/line_verti.png" border="0" alt="Line" />&nbsp;&nbsp;
<a href="<{$xoops_url}>/modules/adslight/admin/index.php"><img src="<{$xoops_url}>/modules/adslight/assets/images/menu/advanced.png" title="Admin" alt="Admin" border="0" /></a>
<{/if}>
</div>
