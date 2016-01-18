<br />
<table border=0 cellpadding=1 cellspacing=0 width="80%">
  <tr>
    <td>
      <h4><{$link.title}></h4>
      <ul>
     	<li><{$lang_voteonce}>
     	<li><{$lang_ratingscale}>
     	<li><{$lang_beobjective}>
     	<li><{$lang_donotvote}>
      </ul>
    </td>
  </tr>
  <tr>
    <td align="center">
      <form method="post" action="rate-item.php">
        <input type="hidden" name="lid" value="<{$link.lid}>">
     	<select name="rating"><option>--</option><option>10</option><option>9</option><option>8</option><option>7</option><option>6</option><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select>&nbsp;&nbsp;
        <input type="submit" name="submit" value="<{$lang_rateit}>" /><input type=button value="<{$lang_cancel}>" onclick="location='<{$xoops_url}>/modules/adslight/viewads.php?lid=<{$link.lid}>'" />
      </form>
    </td>
  </tr>
</table>
