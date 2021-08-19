<{if $listingRows > 0}>
    <div class="outer">
        <form name="select" action="listing.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('listingId[]');} else if (isOneChecked('listingId[]')) {return true;} else {alert('<{$smarty.const.AM_ADSLIGHT_SELECTED_ERROR}>'); return false;}">
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


            <table class="$listing" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"></th>
                    <th class="left"><{$selectorlid}></th>
                    <th class="left"><{$selectorcid}></th>
                    <th class="left"><{$selectortitle}></th>
                    <th class="left"><{$selectorstatus}></th>
                    <th class="left"><{$selectorexpire}></th>
                    <th class="left"><{$selectortype}></th>
                    <th class="left"><{$selectordesctext}></th>
                    <th class="left"><{$selectortel}></th>
                    <th class="left"><{$selectorprice}></th>
                    <th class="left"><{$selectortypeprice}></th>
                    <th class="left"><{$selectortypecondition}></th>
                    <th class="left"><{$selectordate_created}></th>
                    <th class="left"><{$selectoremail}></th>
                    <th class="left"><{$selectorsubmitter}></th>
                    <th class="left"><{$selectorusid}></th>
                    <th class="left"><{$selectortown}></th>
                    <th class="left"><{$selectorcountry}></th>
                    <th class="left"><{$selectorcontactby}></th>
                    <th class="left"><{$selectorpremium}></th>
                    <th class="left"><{$selectorvalid}></th>
                    <th class="left"><{$selectorphoto}></th>
                    <th class="left"><{$selectorhits}></th>
                    <th class="left"><{$selectoritem_rating}></th>
                    <th class="left"><{$selectoritem_votes}></th>
                    <th class="left"><{$selectoruser_rating}></th>
                    <th class="left"><{$selectoruser_votes}></th>
                    <th class="left"><{$selectorcomments}></th>
                    <th class="left"><{$selectorremind}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                <{foreach item=listingArray from=$listingArrays}>
                    <tr class="<{cycle values="odd,even"}>">

                        <td align="center" style="vertical-align:middle;"><input type="checkbox" name="listing_id[]" title="listing_id[]" id="listing_id[]" value="<{$listingArray.listing_id|default:''}>"></td>
                        <td class='left'><{$listingArray.lid}></td>
                        <td class='left'><{$listingArray.cid}></td>
                        <td class='left'><{$listingArray.title}></td>
                        <td class='left'><{$listingArray.status}></td>
                        <td class='left'><{$listingArray.expire}></td>
                        <td class='left'><{$listingArray.type}></td>
                        <td class='left'><{$listingArray.desctext}></td>
                        <td class='left'><{$listingArray.tel}></td>
                        <td class='left'><{$listingArray.price}></td>
                        <td class='left'><{$listingArray.typeprice}></td>
                        <td class='left'><{$listingArray.typecondition}></td>
                        <td class='left'><{$listingArray.date_created}></td>
                        <td class='left'><{$listingArray.email}></td>
                        <td class='left'><{$listingArray.submitter}></td>
                        <td class='left'><{$listingArray.usid}></td>
                        <td class='left'><{$listingArray.town}></td>
                        <td class='left'><{$listingArray.country}></td>
                        <td class='left'><{$listingArray.contactby}></td>
                        <td class='left'><{$listingArray.premium}></td>
                        <td class='left'><{$listingArray.valid}></td>
                        <td class='left'><{$listingArray.photo}></td>
                        <td class='left'><{$listingArray.hits}></td>
                        <td class='left'><{$listingArray.item_rating}></td>
                        <td class='left'><{$listingArray.item_votes}></td>
                        <td class='left'><{$listingArray.user_rating}></td>
                        <td class='left'><{$listingArray.user_votes}></td>
                        <td class='left'><{$listingArray.comments}></td>
                        <td class='left'><{$listingArray.remind}></td>


                        <td class="center width5"><{$listingArray.edit_delete}></td>
                    </tr>
                <{/foreach}>
            </table>
            <br>
            <br>
            <{else}>
            <table width="100%" cellspacing="1" class="outer">
                <tr>

                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"></th>
                    <th class="left"><{$selectorlid}></th>
                    <th class="left"><{$selectorcid}></th>
                    <th class="left"><{$selectortitle}></th>
                    <th class="left"><{$selectorstatus}></th>
                    <th class="left"><{$selectorexpire}></th>
                    <th class="left"><{$selectortype}></th>
                    <th class="left"><{$selectordesctext}></th>
                    <th class="left"><{$selectortel}></th>
                    <th class="left"><{$selectorprice}></th>
                    <th class="left"><{$selectortypeprice}></th>
                    <th class="left"><{$selectortypecondition}></th>
                    <th class="left"><{$selectordate_created}></th>
                    <th class="left"><{$selectoremail}></th>
                    <th class="left"><{$selectorsubmitter}></th>
                    <th class="left"><{$selectorusid}></th>
                    <th class="left"><{$selectortown}></th>
                    <th class="left"><{$selectorcountry}></th>
                    <th class="left"><{$selectorcontactby}></th>
                    <th class="left"><{$selectorpremium}></th>
                    <th class="left"><{$selectorvalid}></th>
                    <th class="left"><{$selectorphoto}></th>
                    <th class="left"><{$selectorhits}></th>
                    <th class="left"><{$selectoritem_rating}></th>
                    <th class="left"><{$selectoritem_votes}></th>
                    <th class="left"><{$selectoruser_rating}></th>
                    <th class="left"><{$selectoruser_votes}></th>
                    <th class="left"><{$selectorcomments}></th>
                    <th class="left"><{$selectorremind}></th>

                    <th class="center width5"><{$smarty.const.AM_ADSLIGHT_FORM_ACTION}></th>
                </tr>
                <tr>
                    <td class="errorMsg" colspan="11">There are no $listing</td>
                </tr>
            </table>
    </div>
    <br>
    <br>
<{/if}>
