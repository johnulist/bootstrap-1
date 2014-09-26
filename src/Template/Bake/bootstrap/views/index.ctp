<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
	->filter(function($field) use ($schema) {
		return !in_array($schema->columnType($field), ['binary', 'text']);
	})
	->take(7);
?>
<div class="actions columns col-md-2 col-sm-3">
	<h3><?= "<?= __('Actions') ?>"; ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?= "<?= \$this->Html->link(__('New " . $singularHumanName . "'), ['action' => 'add']) ?>"; ?></li>
		<li class="active"><?= "<?= \$this->Html->link(__('List " . $pluralHumanName . "'), ['action' => 'index']) ?>" ?></li>
<?php
	$done = [];
	foreach ($associations as $type => $data) {
		foreach ($data as $alias => $details) {
			if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
				echo "\t\t<li><?= \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), ['controller' => '{$details['controller']}', 'action' => 'index']) ?> </li>\n";
				echo "\t\t<li><?= \$this->Html->link(__('New " . Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) . "'), ['controller' => '{$details['controller']}', 'action' => 'add']) ?> </li>\n";
				$done[] = $details['controller'];
			}
		}
	}
?>
	</ul>
</div>
<div class="<?= $pluralVar ?> index col-md-10 col-sm-9">
	<div class="table-responsive">
		<table class="table table-striped">
		<thead>
			<tr>
	<?php foreach ($fields as $field): ?>
			<th><?= "<?= \$this->Paginator->sort('{$field}') ?>"; ?></th>
	<?php endforeach; ?>
			<th class="actions"><?= "<?= __('Actions') ?>"; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
		echo "\t\t<tr>\n";
			foreach ($fields as $field) {
				$isKey = false;
				if (!empty($associations['BelongsTo'])) {
					foreach ($associations['BelongsTo'] as $alias => $details) {
						if ($field === $details['foreignKey']) {
							$isKey = true;
							echo "\t\t\t<td>\n\t\t\t\t<?= \${$singularVar}->has('{$details['property']}') ? \$this->Html->link(\${$singularVar}->{$details['property']}->{$details['displayField']}, ['controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}->{$details['property']}->{$details['primaryKey'][0]}]) : '' ?>\n\t\t\t</td>\n";
							break;
						}
					}
				}
				if ($isKey !== true) {
					if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
						echo "\t\t\t<td><?= h(\${$singularVar}->{$field}) ?></td>\n";
					} else {
						echo "\t\t\t<td><?= \$this->Number->format(\${$singularVar}->{$field}) ?></td>\n";
					}
				}
			}

			$pk = "\${$singularVar}->{$primaryKey[0]}";

			echo "\t\t\t<td class=\"actions\">\n";
			echo "\t\t\t\t<?= \$this->Html->link('<span class=\"glyphicon glyphicon-zoom-in\"></span>', ['action' => 'view', {$pk}], ['escape' => false, 'class' => 'btn btn-xs btn-default', 'title' => __('View')]) ?>\n";
			echo "\t\t\t\t<?= \$this->Html->link('<span class=\"glyphicon glyphicon-pencil\"></span>', ['action' => 'edit', {$pk}], ['escape' => false, 'class' => 'btn btn-xs btn-default', 'title' => __('Edit')]) ?>\n";
			echo "\t\t\t\t<?= \$this->Form->postLink('<span class=\"glyphicon glyphicon-trash\"></span>', ['action' => 'delete', {$pk}], ['confirm' => __('Are you sure you want to delete # {0}?', {$pk}), 'escape' => false, 'class' => 'btn btn-xs btn-danger', 'title' => __('Delete')]) ?>\n";
			echo "\t\t\t</td>\n";
		echo "\t\t</tr>\n";

		echo "\t<?php endforeach; ?>\n";
		?>
		</tbody>
		</table>
	</div>
	<div class="paginator">
		<ul class="pagination">
		<?php
			echo "<?php\n";
			echo "\t\t\techo \$this->Paginator->prev('< ' . __('previous'));\n";
			echo "\t\t\techo \$this->Paginator->numbers();\n";
			echo "\t\t\techo \$this->Paginator->next(__('next') . ' >');\n";
			echo "\t\t?>\n";
		?>
		</ul>
		<p><?= "<?= \$this->Paginator->counter() ?>"; ?></p>
	</div>
</div>
