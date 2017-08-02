<?php
	$results = $displayData;
	$xsmb = $results->xsmb;
	$loto = [];
	for ($i=0;$i<=9;$i++) {
		$loto[$i] = [];
		foreach ($results->loto as $lo) {
			if (substr($lo, 0,1) == $i) {
				$loto[$i][] = $lo;
			}
		}
	}
?>
<h3><?php echo $results->day ?>, <?php echo $results->date ?></h3>
<table width="60%">
	<?php foreach ($xsmb as $g => $rows) : ?>
		<?php if (count($rows) != 6) : ?>
			<tr>
				<td><?php echo $g; ?></td>
				<?php foreach ($rows as $r) : ?>
					<td colspan="<?php echo 12 / intval(count($rows)) ?>"><?php if ($g == 'Đặc biệt') echo '<b style="color: red;">'?><?php echo $r ?><?php if ($g == 'Đặc biệt') echo '</b>'; ?></td>
				<?php endforeach; ?>
			</tr>
		<?php else : ?>
			<tr>
				<td rowspan="2"><?php echo $g; ?></td>
				<?php for($k=0; $k<=2; $k++) : ?>
					<td colspan="<?php echo 12 / intval(count($rows)/2) ?>"><?php echo $rows[$k] ?></td>
				<?php endfor; ?>
			</tr>
			<tr>
				<td style="display:none;"> </td>
				<?php for($h=3; $h<=5; $h++) : ?>
					<td colspan="<?php echo 12 / intval(count($rows)/2) ?>"><?php echo $rows[$h] ?></td>
				<?php endfor; ?>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
</table>
<table width="25%">
	<?php foreach ($loto as $j => $ns) : ?>
		<tr>
			<td><?php echo $j ?></td>
			<td style="text-align: left;">
				<?php echo implode(' , ', $ns); ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>