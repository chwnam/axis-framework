<?php
//\axis_framework\includes\core\utils\axis_dump_pre( $log_array );
/** @var array $log_array array of Log_Item */
?>
<div class="axis-dev-toolbar-container" id="axis-dev-toolbar-container">
	<div>
		this will be menu-layer!
	</div>
	<div class="axis-dev-toolbar-logging-div" id="axis-dev-toolbar-logging-div">
		<table>
			<thead>
				<tr>
					<td>TAG</td>
					<td>TIME</td>
					<td>FILE</td>
					<td>LINE</td>
					<td>MSG</td>
				</tr>
			</thead>
			<tbody>
			<?php foreach( $log_array as &$la ) : ?>
			<?php /** @var \axis_framework\includes\dev\Log_Item $la log item */ ?>
				<tr>
					<td><?=$la->tag?></td>
					<td><?=$la->time?></td>
					<td>
						<span title="<?=$la->path?>">
							<?=basename($la->path)?>
						</span>
					</td>
					<td><?=$la->line_no?></td>
					<td>
						<pre><?=$la->message?></pre>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

