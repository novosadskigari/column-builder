<?php
namespace Nwd\Module\ColumnBuilder\Site\Helper;

class BackgroundHelper
{
    public static function generateBackgroundStyle($row)
    {
        if (empty($row['background_type'])) {
            return '';
        }

        if ($row['background_type'] === 'solid' && !empty($row['background_color'])) {
            return 'background-color: ' . htmlspecialchars($row['background_color'], ENT_QUOTES, 'UTF-8') . ';';
        }

        if ($row['background_type'] === 'gradient') {
            // Get gradient values with defaults
            $startColor = !empty($row['gradient_start_color']) ?
                htmlspecialchars($row['gradient_start_color'], ENT_QUOTES, 'UTF-8') :
                'rgba(54, 114, 205, 0.5)';

            $endColor = !empty($row['gradient_end_color']) ?
                htmlspecialchars($row['gradient_end_color'], ENT_QUOTES, 'UTF-8') :
                'rgba(54, 114, 205, 0)';

            $angle = isset($row['gradient_angle']) ?
                (float)$row['gradient_angle'] :
                184.01;

            $startPos = isset($row['gradient_start_position']) ?
                (float)$row['gradient_start_position'] :
                3.28;

            $endPos = isset($row['gradient_end_position']) ?
                (float)$row['gradient_end_position'] :
                90.72;

            return sprintf(
                'background: linear-gradient(%fdeg, %s %f%%, %s %f%%);',
                $angle,
                $startColor,
                $startPos,
                $endColor,
                $endPos
            );
        }

        return '';
    }
}
