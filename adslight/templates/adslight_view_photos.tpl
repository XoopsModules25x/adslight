<br />
<div id="head"><{$lang_albumtitle}></div>
<hr />
<div id="Titulo">
<h2><{$lang_gtitle}></h2>
</div>

<{if $isOwner}>
<{if $permit}>
<p><{$lang_nb_pict}><br />
<{$lang_max_nb_pict}></p>

<{else}>
<{$lang_no_prem_nb}><br />
<{$lang_not_premium}>
<br /><{$lang_upgrade_now}>
<{ /if }>
<h2><{$lang_nopicyet}></h2>
<ul id="album_photos">
	<{section name=i loop=$pics_array}>
    <div class="photo_in_album">
        	<h3><{$pics_array[i].desc}></h3><{ if ($isOwner) }>
		<table border="0" cellspacing="2" cellpadding="0">
			<tr>
			  <td colspan="3">
            	<a href="<{$path_uploads}>/midsize/resized_<{$pics_array[i].url}>" target ="_self" rel="lightbox[album]">
                	<img class="thumb" src="<{$path_uploads}>/thumbs/thumb_<{$pics_array[i].url}>" rel="lightbox" alt="<{$pics_array[i].desc}>" />
                </a>
              </td>
			</tr>
			<tr>
			  <td width="5">
			  <div align="right">
					<form action="<{$xoops_url}>/modules/adslight/delpicture.php" method="post" id="deleteform" class="lado">
						<input type="hidden" value="<{$pics_array[i].cod_img}>" name="cod_img" /><input type="hidden" value="<{$pics_array[i].lid}>" name="lid" />
						<{$token}>
						<input name="submit2" type="image" alt="<{$lang_delete}>" src="<{xoModuleIcons16 delete.png}>"/>
					</form>
			  </div>
			</td>
			<td width="5">	
					<form action="<{$xoops_url}>/modules/adslight/editdesc.php" method="post" id="editform" class="lado">
						<input type="hidden" value="<{$pics_array[i].cod_img}>" name="cod_img" /><input type="hidden" value="<{$pics_array[i].lid}>" name="lid" />
						<{$token}>
						<input name="submit" type="image" alt="<{$lang_editdesc}>" src="<{xoModuleIcons16 edit.png}>"/>
					</form>
			 </td>
			 <td width="90">
			 </td>
			 </tr>
		</table>
		<{ /if }>
	</div>
  <{/section}>
</ul>
<br /><br />
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td><th><{$form_picture.title}></th></td>
	</tr>
</table>
	<form name="<{$form_picture.name}>" action="<{$form_picture.action}>" method="<{$form_picture.method}>" <{$form_picture.extra}> id="submitpicture">
<{if $xcube}>
<{$form_picture.elements.XOOPS_G_TICKET.body}>
<{else}>
<{$form_picture.elements.XOOPS_TOKEN_REQUEST.body}>
<{/if }>
<p><strong><{$form_picture.elements.1.caption}></strong></p>
<p><strong><{$form_picture.elements.sel_photo.caption}></strong>
<{$form_picture.elements.sel_photo.body}></p>
<p><strong><{$form_picture.elements.caption.caption}></strong>
<{$form_picture.elements.caption.body}></p>
<{$form_picture.elements.lid.body}>
<{$form_picture.elements.submit_button.body}>
</form><{$form_picture.javascript}><{ /if }>
<div style="text-align: center; padding: 3px; margin: 3px;">
  <{$commentsnav}>
  <{$lang_notice}>
</div>
<div style="margin: 3px; padding: 3px;">
<{if $comment_mode == "flat"}>
  <{include file="db:system_comments_flat.html"}>
<{elseif $comment_mode == "thread"}>
  <{include file="db:system_comments_thread.html"}>
<{elseif $comment_mode == "nest"}>
  <{include file="db:system_comments_nest.html"}>
<{/if}>
</div>