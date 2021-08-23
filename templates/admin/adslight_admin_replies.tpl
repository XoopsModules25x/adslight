<{if $repliesRows > 0}>
    <div class="outer">
        <form name="select" action="replies.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('repliesId[]');} else if (isOneChecked('repliesId[]')) {return true;} else {alert('<{$smarty.const.AM_ADSLIGHT_SELECTED_ERROR}>'); return false;}">
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


            <table class="$replies" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"></th>
                    <th class="left"><{$selectorr_lid}></th>
                    <th class="left"><{$selectorlid}></th>
                    <th class="left"><{$selectortitle}></th>
                    <th class="left"><{$selectordate}></th>
                    <th class="left"><{$selectorsubmitter}></th>
                    <th class="left"><{$selectormessage}></th>
                    <th class="left"><{$selectortele}></th>
                    <th class="left"><{$selectoremail}></th>
                    <th class="left"><{$selectorr_usid}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                <{foreach item=repliesArray from=$repliesArrays}>
                    <tr class="<{cycle values="odd,even"}>">

                        <td align="center" style="vertical-align:middle;"><input type="checkbox" name="replies_id[]" title="replies_id[]" id="replies_id[]" value="<{$repliesArray.replies_id|default:''}>"></td>
                        <td class='left'><{$repliesArray.r_lid}></td>
                        <td class='left'><{$repliesArray.lid}></td>
                        <td class='left'><{$repliesArray.title}></td>
                        <td class='left'><{$repliesArray.date}></td>
                        <td class='left'><{$repliesArray.submitter}></td>
                        <td class='left'><{$repliesArray.message}></td>
                        <td class='left'><{$repliesArray.tele}></td>
                        <td class='left'><{$repliesArray.email}></td>
                        <td class='left'><{$repliesArray.r_usid}></td>


                        <td class="center width5"><{$repliesArray.edit_delete}></td>
                    </tr>
                <{/foreach}>
            </table>
            <br>
            <br>
            <{else}>
            <table width="100%" cellspacing="1" class="outer">
                <tr>

                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"></th>
                    <th class="left"><{$selectorr_lid}></th>
                    <th class="left"><{$selectorlid}></th>
                    <th class="left"><{$selectortitle}></th>
                    <th class="left"><{$selectordate}></th>
                    <th class="left"><{$selectorsubmitter}></th>
                    <th class="left"><{$selectormessage}></th>
                    <th class="left"><{$selectortele}></th>
                    <th class="left"><{$selectoremail}></th>
                    <th class="left"><{$selectorr_usid}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                <tr>
                    <td class="errorMsg" colspan="11">There are no $replies</td>
                </tr>
            </table>
    </div>
    <br>
    <br>
<{/if}>
