<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

declare(strict_types=1);

namespace SpiralGrafana\Front;

if (!defined('ABSPATH')) {
    return;
}

class MarkupGenerator
{
    /**
     * @param
     */
    public function __construct()
    {
    }

    /**
     * Generate HTML Table
     */
    public function generateHtmlTable($data): string
    {
        $dataSort = [];

        $dataSort['good'] = $dataSort['recommended'] = $dataSort['critical'] = [];

        foreach ($data as $item) {
            $dataSort[$item['status']][] = $item;
        }

        $color_options = [
            'good' => '#73bf69',
            'recommended' => '#fade2a',
            'critical' => '#f2495c',
        ];

        $output = '';

        foreach ($dataSort as $status => $items) {
            if (sizeof($items)) {
                $output .= '<p style="text-align: center"><h3>' . ucfirst($status) . '</h3></p>';

                $output .= '<table>';
                $output .= '<thead>';
                $output .= '<tr>';
                $output .= '<th style="with: 100px;">Test</th>';
                $output .= '<th style="with: 200px;">Label</th>';
                $output .= '<th>Description</th>';
                $output .= '<th style="with: 100px;">Status</th>';
                $output .= ' </tr>';
                $output .= '</thead><tbody>';

                foreach ($items as $item) :
                    $output .= '<tr style="color:' . $color_options[$status] . '"><td>' . $item['test'] . '</td>';
                    $output .= '<td>' . $item['label'] . '</td>';
                    $output .= '<td>' . $item['description'] . '</td>';
                    $output .= '<td>' . $item['status'] . '</td></tr>';
                endforeach;

                $output .= '</tbody></table>';
            }
        }

        return $output;
    }
}
