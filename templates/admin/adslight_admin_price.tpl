<{if $priceRows > 0}>
    <div class="outer">
         <form name="select" action="price.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('priceId[]');} else if (isOneChecked('priceId[]')) {return true;} else {alert('<{$smarty.const.AM_ADSLIGHT_SELECTED_ERROR}>'); return false;}">
            <input type="hidden" name="confirm" value="1">
            <div class="floatleft">
                   <select name="op">
                       <option value=""><{$smarty.const.AM_ADSLIGHT_SELECT}></option>
                       <option value="delete"><{$smarty.const.AM_ADSLIGHT_SELECTED_DELETE}></option>
                   </select>
                   <input id="submitUp" class="formButton" type="submit" name="submitselect" value="<{$smarty.const._SUBMIT}>" title="<{$smarty.const._SUBMIT}>"  >
               </div>
            <div class="floatcenter0">
                <div id="pagenav"><{$pagenav|default:''}></div>
            </div>



          <table class="$price" cellpadding="0" cellspacing="0" width="100%">
            <tr><th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All"  value="Check All" ></th>  <th class="left"><{$selectorid_price}></th>  <th class="left"><{$selectornom_price}></th>

<th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
</tr>
<{foreach item=priceArray from=$priceArrays}>
<tr class="<{cycle values="odd,even"}>">

<td align="center" style="vertical-align:middle;"><input type="checkbox" name="price_id[]"  title ="price_id[]" id="price_id[]" value="<{$priceArray.price_id|default:''}>" ></td>
<td class='left'><{$priceArray.id_price}></td>
<td class='left'><{$priceArray.nom_price}></td>


<td class="center width5"><{$priceArray.edit_delete}></td>
</tr>
<{/foreach}>
</table>
<br>
<br>
<{else}>
<table width="100%" cellspacing="1" class="outer">
<tr>

<th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All"  value="Check All" ></th>  <th class="left"><{$selectorid_price}></th>  <th class="left"><{$selectornom_price}></th>

<th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
</tr>
<tr>
<td class="errorMsg" colspan="11">There are no $price</td>
</tr>
</table>
</div>
<br>
<br>
<{/if}>
