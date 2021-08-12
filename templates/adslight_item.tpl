<{if $adslight_active_menu == 1 }>
    <{include file='db:adslight_menu.tpl'}>
<{/if}>
<{include file='db:adslight_search.tpl'}>
<br>
<{if $ad_exists}>
    <table>
        <tr>
            <td>
                <!-- Stard Block Ads -->
                <h1><{$title}></h1>
                <strong><{$type}></strong>
                <strong><{$price_price}></strong>
                <{$price_typeprice}>&nbsp;-&nbsp;<{$user_typeuser|default:''}>&nbsp;
                <{$admin|default:false}>
                <hr>
                <br>
                <{if $sold|default:false}>
                    <br>
                    <{$sold}>
                    <br>
                    <span style="color: #cccccc;  text-decoration: line-through;"><{$desctext}></span><{else}>
                    <{$desctext}>
                <{/if}>
                <{if $adslight_active_bookmark == 1 }>
                    <br>
                    <hr>
                    <{include file='db:adslight_bookmark.tpl'}>
                <{/if}>
                <br>
                <hr>
                <{if $photo != "0"}>
                    <{section name=i loop=$pics_array|default:null}>
                        <a href="<{$path_uploads}>/midsize/resized_<{$pics_array[i].url}>" target="_self"
                           rel="lightbox[album]" title="<{$pics_array[i].desc}>">
                            <img class="thumb" src="<{$path_uploads}>/thumbs/thumb_<{$pics_array[i].url}>"
                                 rel="lightbox" title="<{$pics_array[i].desc}>">
                        </a>
                        &nbsp;&nbsp;
                    <{/section}>
                <{else}>
                    <div>
                        <img src="<{$xoops_url}>/modules/adslight/assets/images/nophoto_item.gif" alt="no photo">&nbsp;&nbsp;
                        <img src="<{$xoops_url}>/modules/adslight/assets/images/nophoto_item.gif" alt="no photo">&nbsp;&nbsp;
                        <img src="<{$xoops_url}>/modules/adslight/assets/images/nophoto_item.gif" alt="no photo">
                    </div>
                <{/if}>
                <{if $xoops_isuser}>
                <{if $adslight_active_xpayement|default:false == 1 }>
                <!-- xpayment -->
                <{if $purchasable && !$sold && $price_amount > 0}>
                <{include file="db:adslight_xpayment_form.tpl"}>
            </tr>
            <{/if}>
            <!-- xpayment -->
            <{/if}><{/if}>
            <br>
            <div align="center"><strong>[ <{$link_main}> | <{$link_cat}> ]</strong></div>
            </td><!-- End Block Ads -->
            <td><!-- Start Block Right -->
                <br>
                <table border="0" cellspacing="1" class="outer" style="width:200px;">
                    <tr>
                        <td class="blockright">
                            <{$date|default:false}><br>
                            <strong><{$local_head}></strong> <{$local_town}><br>
                            <strong><{$country_head}></strong> <{$local_country}><br>
                            <hr>
                            <{$submitter}><br>
                            <{$user_profile}><br>
                            <{$printA}><br>
                            <{$friend}><br>
                            <hr>
                            <{$add_photos|default:false}><br>
                            <{$modifyads|default:false}><br>
                            <{$deleteads|default:false}><br>
                            <{$alerteabus}><br>
                            <br>
                            <{if $local_country}>
                                <img class="mapsgoogle"
                                     src="http://maps.google.com/maps/api/staticmap?size=200x200&maptype=roadmap\&markers=size:mid|color:red|<{$local_town}>+<{$local_country}>&sensor=false">
                            <{/if}>
                        </td>
                    </tr>
                </table>
                <!-- End Block de Droite -->
            </td>
        </tr>
    </table>
    <!-- Block Comment -->
    <div style="text-align: center; padding: 3px; margin:3px;">
        <{$commentsnav|default:false}>
        <{$lang_notice|default:false}>
    </div>
    <div style="margin:3px; padding: 3px;">
        <{if $comment_mode|default:false == "flat"}>
            <{include file="db:system_comments_flat.tpl"}>
        <{elseif $comment_mode|default:false == "thread"}>
            <{include file="db:system_comments_thread.tpl"}>
        <{elseif $comment_mode|default:false == "nest"}>
            <{include file="db:system_comments_nest.tpl"}>
        <{/if}>
    </div>
<{/if}>
