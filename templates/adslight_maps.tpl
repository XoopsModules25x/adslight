<{if $adslight_active_menu == 1 }>
    <{include file='db:adslight_menu.tpl'}>
<{/if}>
<{include file='db:adslight_search.tpl'}>
<br>
<table cellspacing="1" class="outer" style="width:100%;">
    <tr>
        <td class="twtgtitle" style="text-align: center;">
            <{$adlight_maps_title}>
        </td>
    </tr>
    <tr>
        <td>
            <div align="center" style="text-align: center;">
                <object type="application/x-shockwave-flash" data="maps/<{$maps_name}>/map.swf" width="<{$maps_width}>"
                        height="<{$maps_height}>">
                    <param name="movie" value="maps/<{$maps_name}>/map.swf">
                    <param name="loop" value="false">
                    <param name="wmode\" value="transparent">
                    <param name="quality" value="best">
                </object>
            </div>
        </td>
    </tr>
</table>
<div align="center"><br><{$nav_page|default:false}></div>
<{include file='db:system_notification_select.tpl'}>
<br>
