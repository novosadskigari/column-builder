<?php
namespace Nwd\Module\ColumnBuilder\Site\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

class ColumnHelper
{
    public static function initializeAssets()
    {
        try {
            Factory::getApplication()->getDocument()->getWebAssetManager()
                ->registerAndUseStyle('mod_column_builder', 'mod_column_builder/mod-column-builder.css');
        } catch (\Exception $e) {
            // Handle asset errors quietly
        }
    }

    public static function getRows($params)
    {
        $rows = $params->get('rows', []);
        return is_object($rows) ? json_decode(json_encode($rows), true) : $rows;
    }

    public static function getModuleId($moduleId)
    {
        return 'mod-column-builder-' . $moduleId;
    }

    public static function getColumnId($moduleId, $rowIndex, $columnIndex, $column, $isDynamic = false)
    {
        // Create a unique string based on stable column data
        $uniqueString = $moduleId . '-' . $rowIndex . '-' . $columnIndex;

        // Include column-specific data in the hash
        if (!empty($column['custom_class'])) {
            $uniqueString .= '-' . $column['custom_class'];
        }

        // Handle accessiblemedia field structure
        if (!empty($column['image']) && is_array($column['image'])) {
            $uniqueString .= '-' . basename($column['image']['imagefile'] ?? '');
        }

        if (!empty($column['editor_content'])) {
            // Add first 20 characters of content to make ID more specific
            $uniqueString .= '-' . substr(strip_tags($column['editor_content']), 0, 20);
        }

        // Add column widths to make sure ID changes if layout changes
        $uniqueString .= '-' . ($column['small_width'] ?? '12');
        $uniqueString .= '-' . ($column['medium_width'] ?? '6');

        // Generate a short, stable hash
        $hash = substr(md5($uniqueString), 0, 6);

        return 'mod-column-' . $hash;
    }

    public static function getRowId($moduleId, $rowIndex, $row)
    {
        // Create a unique string based on stable row data
        $uniqueString = $moduleId . '-' . $rowIndex;

        // If row has custom_class, include it in the hash
        if (!empty($row['custom_class'])) {
            $uniqueString .= '-' . $row['custom_class'];
        }

        // Generate a short, stable hash
        $hash = substr(md5($uniqueString), 0, 6);

        return 'mod-row-' . $hash;
    }

    public static function renderImage($imageData)
    {
        if (empty($imageData)) {
            return '';
        }

        // Handle accessiblemedia field structure
        if (is_array($imageData)) {
            $imagePath = $imageData['imagefile'] ?? '';
            $altText = $imageData['alt_text'] ?? '';
        } else {
            // Fallback for legacy media field
            $imagePath = $imageData;
            $altText = '';
        }

        // Ensure we have a valid image path
        if (empty($imagePath)) {
            return '';
        }

        return HTMLHelper::_('image',
            Uri::root(false) . htmlspecialchars($imagePath, ENT_QUOTES),
            htmlspecialchars($altText, ENT_QUOTES),
            ['loading' => 'lazy', 'class' => 'img-fluid'],
            false
        );
    }

    public static function getBootstrapColumnClasses($column)
    {
        $defaults = [
            'small_width' => 12,
            'medium_width' => 6,
            'large_width' => 4,
            'xlarge_width' => 4,
            'xxlarge_width' => 4
        ];

        return sprintf(
            'col-%d col-md-%d col-lg-%d col-xl-%d col-xxl-%d',
            isset($column['small_width']) ? (int)$column['small_width'] : $defaults['small_width'],
            isset($column['medium_width']) ? (int)$column['medium_width'] : $defaults['medium_width'],
            isset($column['large_width']) ? (int)$column['large_width'] : $defaults['large_width'],
            isset($column['xlarge_width']) ? (int)$column['xlarge_width'] : $defaults['xlarge_width'],
            isset($column['xxlarge_width']) ? (int)$column['xxlarge_width'] : $defaults['xxlarge_width']
        );
    }

    public static function getDynamicColumnWidth($columns)
    {
        $columnCount = count($columns);
        return $columnCount > 0 ? floor(12 / $columnCount) : 12;
    }

    public static function getCustomClass($item)
    {
        return !empty($item['custom_class'])
            ? htmlspecialchars($item['custom_class'], ENT_QUOTES, 'UTF-8')
            : '';
    }

    public static function sanitizeContent($content)
    {
        if (empty($content)) {
            return '';
        }

        return is_string($content) ? $content : '';
    }

    public static function validateColumnSettings($column)
    {
        return [
            'has_image' => isset($column['enable_image']) && $column['enable_image'] && !empty($column['image']),
            'has_content' => isset($column['enable_editor']) && $column['enable_editor'] && !empty($column['editor_content'])
        ];
    }
}