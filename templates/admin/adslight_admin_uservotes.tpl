<{if $uservotesRows > 0}>
    <div class="outer">
        <form name="select" action="uservotes.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('uservotesId[]');} else if (isOneChecked('uservotesId[]')) {return true;} else {alert('<{$smarty.const.AM_ADSLIGHT_SELECTED_ERROR}>'); return false;}">
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
                    <th class="left"><{$selectorratingid}></th>
                    <th class="left"><{$selectorusid}></th>
                    <th class="left"><{$selectorratinguser}></th>
                    <th class="left"><{$selectorrating}></th>
                    <th class="left"><{$selectorratinghostname}></th>
                    <th class="left"><{$selectorratingtimestamp}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                </thead>

                <tbody>
                <{foreach item=uservotesArray from=$uservotesArrays}>
                    <tr class="<{cycle values="odd,even"}>">

                        <td align="center" style="vertical-align:middle;"><input type="checkbox" name="uservotes_id[]" title="uservotes_id[]" id="uservotes_id[]" value="<{$uservotesArray.uservotes_id|default:''}>"></td>
                        <td class='left'><{$uservotesArray.ratingid}></td>
                        <td class='left'><{$uservotesArray.usid}></td>
                        <td class='left'><{$uservotesArray.ratinguser}></td>
                        <td class='left'><{$uservotesArray.rating}></td>
                        <td class='left'><{$uservotesArray.ratinghostname}></td>
                        <td class='left'><{$uservotesArray.ratingtimestamp}></td>


                        <td class="center width5"><{$uservotesArray.edit_delete}></td>
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
                    <th class="left"><{$selectorratingid}></th>
                    <th class="left"><{$selectorusid}></th>
                    <th class="left"><{$selectorratinguser}></th>
                    <th class="left"><{$selectorrating}></th>
                    <th class="left"><{$selectorratinghostname}></th>
                    <th class="left"><{$selectorratingtimestamp}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                <tr>
                    <td class="errorMsg" colspan="11">There are no $uservotes</td>
                </tr>
            </table>
    </div>
    <br>
    <br>
<{/if}>
