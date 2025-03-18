<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_column_builder
 * @copyright   Copyright (C) 2025 Damir B. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Nwd\Module\ColumnBuilder\Site\Helper\BackgroundHelper;
use Nwd\Module\ColumnBuilder\Site\Helper\ColumnHelper;

// Initialize assets and get data
ColumnHelper::initializeAssets();
$rows = ColumnHelper::getRows($params);
$modId = ColumnHelper::getModuleId($module->id);
?>

<div id="<?php echo $modId; ?>">
    <?php if (!empty($rows)) : ?>
        <?php foreach ($rows as $rowIndex => $row) : ?>
            <?php
            $rowClass = ColumnHelper::getCustomClass($row);
            $isDynamic = isset($row['dynamic_width']) ? (int)$row['dynamic_width'] : 0;
            $rowId = ColumnHelper::getRowId($module->id, $rowIndex, $row);
            $rowStyle = BackgroundHelper::generateBackgroundStyle($row);
            $useContainer = !empty($row['use_container']) && $row['use_container'] == 1;
            ?>

            <div id="<?php echo $rowId; ?>"
                 class="column-row"
                <?php echo !empty($rowStyle) ? 'style="' . $rowStyle . '"' : ''; ?>>

                <?php if ($useContainer) : ?>
                <div class="container">
                    <?php endif; ?>

                    <div class="row <?php echo $rowClass; ?>">
                        <?php if ($isDynamic) : ?>
                            <?php
                            $columns = isset($row['dynamic_columns']) ? (array)$row['dynamic_columns'] : [];
                            $standardWidth = ColumnHelper::getDynamicColumnWidth($columns);

                            foreach ($columns as $columnIndex => $column) :
                                $column = (array)$column;
                                $columnClass = ColumnHelper::getCustomClass($column);
                                $columnId = ColumnHelper::getColumnId($module->id, $rowIndex, $columnIndex, $column, true);
                                ?>
                                <div id="<?php echo $columnId; ?>" class="col-12 col-md-<?php echo $standardWidth; ?> <?php echo $columnClass; ?>">
                                    <div class="column-item">
                                        <?php if (isset($column['enable_image']) && $column['enable_image'] && !empty($column['image'])) : ?>
                                            <div class="column-image <?php echo isset($column['image_custom_class']) ? htmlspecialchars($column['image_custom_class']) : ''; ?>">
                                                <?php echo ColumnHelper::renderImage($column['image']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (isset($column['enable_editor']) && $column['enable_editor'] && !empty($column['editor_content'])) : ?>
                                            <div class="column-text <?php echo isset($column['editor_custom_class']) ? htmlspecialchars($column['editor_custom_class']) : ''; ?>">
                                                <?php echo $column['editor_content']; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php
                            $columns = isset($row['columns']) ? (array)$row['columns'] : [];

                            foreach ($columns as $columnIndex => $column) :
                                $column = (array)$column;
                                $columnClass = ColumnHelper::getCustomClass($column);
                                $bootstrapClass = ColumnHelper::getBootstrapColumnClasses($column);
                                $columnId = ColumnHelper::getColumnId($module->id, $rowIndex, $columnIndex, $column, false);
                                ?>
                                <div id="<?php echo $columnId; ?>" class="<?php echo $bootstrapClass; ?> <?php echo $columnClass; ?>">
                                    <div class="column-item">
                                        <?php if (isset($column['enable_image']) && $column['enable_image'] && !empty($column['image'])) : ?>
                                            <div class="column-image <?php echo isset($column['image_custom_class']) ? htmlspecialchars($column['image_custom_class']) : ''; ?>">
                                                <?php echo ColumnHelper::renderImage($column['image']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (isset($column['enable_editor']) && $column['enable_editor'] && !empty($column['editor_content'])) : ?>
                                            <div class="column-text <?php echo isset($column['editor_custom_class']) ? htmlspecialchars($column['editor_custom_class']) : ''; ?>">
                                                <?php echo $column['editor_content']; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($useContainer) : ?>
                </div>
            <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="alert alert-info">No rows found. Please configure the module by adding rows and columns.</div>
    <?php endif; ?>
</div>
