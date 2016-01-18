
	<{if $has_replies}>
	<table border="1" cellpadding="0" cellspacing="0" width="100%">
        <tr>
	<td class="head"><strong><{$nav_main}></strong></td></tr><tr>
        <td class="head"><strong><{$title_head}>&nbsp;&nbsp;<{$title}></strong></td></tr></table>
	<br /><br /><{$nav_page}><br /><br />

        <{foreach item=item from=$items}>
	<table border="1" cellpadding="0" cellspacing="0" width="100%">
	<tr><td class="head"><{$delete_reply}><br /><br /></td></tr>
	<tr><td class="head"><{$submitter_head}>&nbsp;&nbsp;<{$submitter}><br /><br /></td></tr>
	<tr><td class="head"><{$date_head}>&nbsp;&nbsp;<{$item.date}><br /><br /></td></tr>
        <tr><td class="head"><{$email_head}>&nbsp;&nbsp;<{$email}><br /><br /></td></tr>
	<tr><td class="head"><{$tele_head}>&nbsp;&nbsp;<{$item.tele}><br /><br /></td></tr>
	</table>
	<table border="1" cellpadding="0" cellspacing="0" width="100%">
	<tr><td class="head" align="center"><{$message_head}></td></tr>
	<tr><td class="even"><br /><{$item.message}></td>
	</tr>
	<{/foreach}>
	</table><br /><{$nav_page}><br /><br />
	<{else}>
	<table border="1" cellpadding="0" cellspacing="0" width="100%" height="200px">
        <tr>
	<td class="head"><strong><{$no_more_replies}></strong></td></tr></table>
	<{/if}>