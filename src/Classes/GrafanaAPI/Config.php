<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

declare(strict_types=1);

namespace SpiralGrafana\Classes\GrafanaAPI;

if (!defined('ABSPATH')) {
    return;
}

class Config
{
    /**
     * @return array
     */
    public function getApiCredentionals()
    {
        $grafanaUrl = get_option('grafana_url');

        $grafanaApi = get_option('grafana_api');

        if (!preg_match("/.*\/$/", $grafanaUrl)) {
            $grafanaUrl = "$grafanaUrl" . "/";
        }

        return [
                        'url' => $grafanaUrl,
                        'key' => $grafanaApi,
               ];
    }
}
