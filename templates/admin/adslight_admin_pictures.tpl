<{if $picturesRows > 0}>
    <div class="outer">
        <form name="select" action="pictures.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('picturesId[]');} else if (isOneChecked('picturesId[]')) {return true;} else {alert('<{$smarty.const.AM_ADSLIGHT_SELECTED_ERROR}>'); return false;}">
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
                    <th class="left"><{$selectorcod_img}></th>
                    <th class="left"><{$selectortitle}></th>
                    <th class="left"><{$selectordate_created}></th>
                    <th class="left"><{$selectordate_updated}></th>
                    <th class="left"><{$selectorlid}></th>
                    <th class="left"><{$selectoruid_owner}></th>
                    <th class="left"><{$selectorurl}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                </thead>

                <tbody>
                <{foreach item=picturesArray from=$picturesArrays}>
                    <tr class="<{cycle values="odd,even"}>">

                        <td align="center" style="vertical-align:middle;"><input type="checkbox" name="pictures_id[]" title="pictures_id[]" id="pictures_id[]" value="<{$picturesArray.pictures_id|default:''}>"></td>
                        <td class='left'><{$picturesArray.cod_img}></td>
                        <td class='left'><{$picturesArray.title}></td>
                        <td class='left'><{$picturesArray.date_created}></td>
                        <td class='left'><{$picturesArray.date_updated}></td>
                        <td class='left'><{$picturesArray.lid}></td>
                        <td class='left'><{$picturesArray.uid_owner}></td>
                        <td class='left'><{$picturesArray.url}></td>


                        <td class="center width5"><{$picturesArray.edit_delete}></td>
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
                    <th class="left"><{$selectorcod_img}></th>
                    <th class="left"><{$selectortitle}></th>
                    <th class="left"><{$selectordate_created}></th>
                    <th class="left"><{$selectordate_updated}></th>
                    <th class="left"><{$selectorlid}></th>
                    <th class="left"><{$selectoruid_owner}></th>
                    <th class="left"><{$selectorurl}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                <tr>
                    <td class="errorMsg" colspan="11">There are no $pictures</td>
                </tr>
            </table>
    </div>
    <br>
    <br>
<{/if}>
