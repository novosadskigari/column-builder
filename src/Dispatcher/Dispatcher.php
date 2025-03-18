<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_column_builder
 *
 * @copyright   (C) 2023 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Nwd\Module\ColumnBuilder\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

/**
 * Dispatcher for Column Builder Module
 */
class Dispatcher extends AbstractModuleDispatcher
{
protected function getLayoutData(): array
 {
$data = parent::getLayoutData();
$params = $data['params'];

// Process dynamic or fixed column widths
$columns = $params->get('columns', []);
$dynamicWidth = $params->get('dynamic_width', 1);

$colWidth = ($dynamicWidth && count($columns) > 0) ? floor(12 / count($columns)) : 4;
foreach ($columns as $key => &$column) {
$column['width'] = $colWidth;
}

$data['columns'] = $columns;

return $data;
}
}