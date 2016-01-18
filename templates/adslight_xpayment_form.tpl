<form action="<{$xoops_url}>/modules/xpayment/" method="post">
<!-- This is the header of the invoice -->
<input type="hidden" name="op" value="createinvoice">
<input type="hidden" name="plugin" value="adslight">
<input type="hidden" name="drawfor" value="<{$drawfor}>"><br/>
<{php}>
if (is_object($GLOBALS['xoopsUser'])) { <{/php}>
<label><strong><{$smarty.const._MN_ADSLIGHT_YOURNAME}></strong>&nbsp;<{$user_name}></label><br/>
<label><strong><{$smarty.const._MN_ADSLIGHT_YOUREMAIL}></strong>&nbsp;<{$user_email}></label><br/>
<input type="hidden" name="drawto" value="<{$user_email}> - <{$user_name}>">
<input type="hidden" name="drawto_email" value="<{$user_email}>">
<{php}> } else { <{/php}>
<label><{$smarty.const._MN_ADSLIGHT_YOURNAME}>&nbsp;</label><input type="text" name="drawto" value="" size="15" maxlen="128"><br/>
<label><{$smarty.const._MN_ADSLIGHT_YOUREMAIL}>&nbsp;</label><input type="text" name="drawto_email" value="" size="15" maxlen="128"><br/>
<{php}> } <{/php}>
<input type="hidden" name="currency" value="<{$price_currency}>">
<input type="hidden" name="key" value="<{$lid}>">
<input type="hidden" name="weight_unit" value="kgs">

<input type="hidden" name="item[A][cat]" value="<{$category_title}>">
<input type="hidden" name="item[A][name]" value="<{$title}>">
<input type="hidden" name="item[A][amount]" value="<{$price_amount}>">
<input type="hidden" name="item[A][quantity]" value="1">
<input type="hidden" name="item[A][shipping]" value="0.00">
<input type="hidden" name="item[A][handling]" value="0.00">
<input type="hidden" name="item[A][weight]" value="0.00">
<input type="hidden" name="item[A][tax]" value="0">

<input type="hidden" name="return" value="<{$xoops_url}>/modules/adslight/viewads.php?lid=<{$lid}>">
<input type="hidden" name="cancel" value="<{$xoops_url}>/modules/adslight/viewads.php?lid=<{$lid}>">
<input type="submit" name='submit' value="<{$smarty.const._MN_ADSLIGHT_PURCHASE}>" />
</form>
<hr />