<{if $adslight_active_menu == 1 }>
    <{include file='db:adslight_menu.tpl'}>
<{/if}>
<{include file='db:adslight_search.tpl'}>
<br>
<table cellspacing="1" border="0" style="width:100%;">
    <tr>
        <td style="text-align: center;"><strong><h2>
                    <{if $ads_use_tipswrite == 1}>
                        <{$adslight_writetitle}>
                    <{else}>
                        <{$smarty.const._ADSLIGHT_TIPSWRITE_TITLE}>
                    <{/if}>
                </h2></strong></td>
    </tr>
    <tr>
        <td class="tipswrite"><br><br>
            <{if $ads_use_tipswrite == 1}>
                <{$adslight_writetexte}>
            <{else}>
                <{$smarty.const._ADSLIGHT_TIPSWRITE_TEXT}>
            <{/if}>
        </td>
    </tr>
</table>
<div align="center"><br><{$nav_page|default:false}></div>
<{include file='db:system_notification_select.tpl'}>
<br>
<br>
