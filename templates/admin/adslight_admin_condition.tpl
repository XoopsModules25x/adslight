<{if $conditionRows > 0}>
    <div class="outer">
        <form name="select" action="condition.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('conditionId[]');} else if (isOneChecked('conditionId[]')) {return true;} else {alert('<{$smarty.const.AM_ADSLIGHT_SELECTED_ERROR}>'); return false;}">
            <input type="hidden" name="confirm" value="1">
            <div class="floatleft">
                <select name="op">
                    <option value=""><{$smarty.const.AM_ADSLIGHT_SELECT}></option>
                    <option value="delete"><{$smarty.const.AM_ADSLIGHT_SELECTED_DELETE}></option>
                </select>
                <input id="submitUp" class="formButton" type="submit" name="submitselect" value="<{$smarty.const._SUBMIT}>" title="<{$smarty.const._SUBMIT}>">
            </div>
            <div class="floatcenter0">
                <div id="pagenav"><{$pagenav|default:''}></div>
            </div>


            <table id="sortTable" class="tablesorter-blue"  cellpadding="0" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"></th>
                    <th class="left"><{$selectorid_condition}></th>
                    <th class="left"><{$selectornom_condition}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                </thead>

                <tbody>
                <{foreach item=conditionArray from=$conditionArrays}>
                    <tr class="<{cycle values="odd,even"}>">

                        <td align="center" style="vertical-align:middle;"><input type="checkbox" name="condition_id[]" title="condition_id[]" id="condition_id[]" value="<{$conditionArray.condition_id|default:''}>"></td>
                        <td class='left'><{$conditionArray.id_condition}></td>
                        <td class='left'><{$conditionArray.nom_condition}></td>
                        <td class="center width5"><{$conditionArray.edit_delete}></td>
                    </tr>
                <{/foreach}>
                </tbody>
            </table>
            <br>
            <br>
            <{else}>
            <table class="tablesorter-blue" width="100%" cellspacing="1" class="outer">
                <tr>
                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"></th>
                    <th class="left"><{$selectorid_condition}></th>
                    <th class="left"><{$selectornom_condition}></th>
                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                <tr>
                    <td class="errorMsg" colspan="11">There are no $condition</td>
                </tr>
            </table>
    </div>
    <br>
    <br>
<{/if}>
