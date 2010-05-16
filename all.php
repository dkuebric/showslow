<?php 
require_once(dirname(__FILE__).'/global.php');
require_once(dirname(__FILE__).'/users/users.php');

$TITLE = 'URLs measured';
$SECTION = 'all';
require_once(dirname(__FILE__).'/header.php');
?>
<h1>URLs measured</h1>
<style>
td { white-space: nowrap; }

.score {
        text-align: right;
        padding: 0 10px 0 10px;
}

.gbox {
        background-color: silver;
        width: 101px;
}

.url {
        padding-left:10px;
}
</style>
<div style="width: 100%; overflow: hidden">
<table>
<tr><th colspan="2">YSlow grade</th><th colspan="2">Page Speed score</th><th style="padding-left:10px; text-align: left">URL</th></tr>
<?php 
$query = sprintf("SELECT DISTINCT url, yslow2.o as o, pagespeed.o as ps_o FROM urls LEFT JOIN yslow2 on urls.yslow2_last_id = yslow2.id LEFT JOIN pagespeed on urls.pagespeed_last_id = pagespeed.id WHERE last_update IS NOT NULL");
$result = mysql_query($query);

if (!$result) {
	error_log(mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
?><tr>

<?php if (is_null($row['o'])) {?>
	<td></td><td></td>
<?php }else{?>
	<td class="score"><?php echo yslowPrettyScore($row['o'])?> (<?php echo $row['o']?>)</td>
	<td><div class="gbox" title="Current YSlow grade: <?php echo yslowPrettyScore($row['o'])?> (<?php echo $row['o']?>)"><div style="width: <?php echo $row['o']+1?>px; height: 0.7em; background-color: <?php echo scoreColor($row['o'])?>"/></div></td>
<?php }?>

<?php if (is_null($row['ps_o'])) {?>
	<td></td><td></td>
<?php }else{?>
	<td class="score"><?php echo yslowPrettyScore($row['ps_o'])?> (<?php echo $row['ps_o']?>)</td>
	<td><div class="gbox" title="Current Page Speed score: <?php echo yslowPrettyScore($row['ps_o'])?> (<?php echo $row['ps_o']?>)"><div style="width: <?php echo $row['ps_o']+1?>px; height: 0.7em; background-color: <?php echo scoreColor($row['ps_o'])?>"/></div></td>
<?php }?>
	<td class="url"><a href="details/?url=<?php echo urlencode($row['url'])?>"><?php echo htmlentities(substr($row['url'], 0, 100))?><?php if (strlen($row['url']) > 100) { ?>...<?php } ?></a></td>
</tr><?php 
}

mysql_free_result($result);
?>
</table>
</div>
<?php
require_once(dirname(__FILE__).'/footer.php');
