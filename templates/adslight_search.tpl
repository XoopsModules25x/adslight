<table cellspacing="1" class="outer" style="width:100%;">
  <tr>
    <th><{$add_from_title}> <{$add_from_sitename}></th>
  </tr>
  <{if $moderated}>
	  <{if $xoops_isadmin}>
	  <tr>
	    <td align="center" class="even">
	    <table class="outer" cellspacing="0">
		  <tr>
		    <td class="head" align="center"><{$admin_block}></td>
		  </tr>
		  <tr>
		    <td class="odd" align="center"><{$confirm_ads}></td>
		  </tr>
	   </table>
	   </td>	
	  </tr>
	  <{/if}>
  <{/if}>
<tr>
  	<td class="even" align="center">
			<table border="0" cellspacing="0" cellpadding="0">
	<tr>
<td>
<table class="bullinfo">
<tr>
<td class="bullinfotext">
<{$bullinfotext}>
</td>
</tr>
</table>
</td>
<td>
<table width="122" border="0" cellspacing="0" cellpadding="0">
<tr>
  <form name='search' id='search' action='search.php' method='post' onsubmit='return xoopsFormValidate_search();'>
	<input type='hidden' name='mids[]' value='<{$xmid}>' />
	   <td colspan="2"><strong><{$smarty.const._ADSLIGHT_SEARCH_LISTINGS}></strong><br /><br /></td>
	</tr>
	<tr>
		<td colspan="2"><input type='text' name='query' id='query' size='30' maxlength='255' value='' /><br /><br /></td>
	</tr>
	<tr>
		<td><select  size='1' name='andor' id='andor'>
					<option value='AND' selected='selected'><{$smarty.const._ADSLIGHT_ALL_WORDS}></option>
					<option value='OR'><{$smarty.const._ADSLIGHT_ANY_WORDS}></option>
					<option value='exact'><{$smarty.const._ADSLIGHT_EXACT_MATCH}></option>
					</select></td>
		<td><input type='submit' class='formButton' name='submit'  id='submit' value='<{$smarty.const._ADSLIGHT_SEARCH}>' /></td></tr>
<input type='hidden' name='action' id='action' value='results' />
</form>
</tr>	
</table>
</td>
</tr>
</table>
</td></tr>
</table>
<script type='text/javascript'>
<!--
function xoopsFormValidate_search(){}
//-->
</script>