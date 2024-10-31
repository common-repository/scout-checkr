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

class Dashboard extends \SpiralGrafana\Classes\GrafanaAPI\API
{
    /**
     * @return false|string
     */
    public function create()
    {
        $dashboardTitle = get_option('grafana_dashboard_title') ?? '';

        if (!$dashboardTitle) {
            return false;
        }

        $dashboard_id = get_option('grafana_dashboard_id') ?? null;

        $dashboard_uid = get_option('grafana_dashboard_uid') ?? null;

        $dashboard_version = get_option('grafana_dashboard_version') ?? 1;

        $content_data = \SpiralGrafana\Classes\SiteHealth::getResults();

        $panels = $this->generatePanel($content_data, $dashboardTitle);

        $data = $this->generateData($dashboard_id, $dashboard_uid, $dashboard_version, $dashboardTitle);

        $result = $this->sendRequest($data, 'api/dashboards/db');

        if ($result && $result['status'] == 'success') {
            $data = [
                "dashboard" => [
                    "id" => $result['id'],
                    "panels" => $panels,
                    "uid" => $result['uid'],
                    "title" => $dashboardTitle,
                    "version" => $result['version'],
                ],
            ];

            $result = $this->sendRequest($data, 'api/dashboards/db');

            if ($result && $result['status'] == 'success') {
                update_option('grafana_dashboard_id', $result['id']);

                update_option('grafana_dashboard_uid', $result['uid']);

                update_option('grafana_dashboard_version', $result['version']);

                return $this->settings['url'] . substr($result['url'], 1);
            }
        }

        return false;
    }

    /**
     * @param $content_data
     * @param $dashboardTitle
     *
     * @return array[]
     */
    public function generatePanel($content_data, $dashboardTitle)
    {
        $panel = [
            [
                "datasource" => [
                    "type" => "datasource",
                    "uid" => "grafana",
                ],
                "gridPos" => [
                    "h" => 18,
                    "w" => 24,
                    "x" => 0,
                    "y" => 0,
                ],
                "id" => 2,
                "options" => [
                    "content" => $content_data,
                ],
                "pluginVersion" => "8.5.2",
                "title" => $dashboardTitle,
                "type" => "text",
            ],
        ];

        return $panel;
    }

    /**
     * @param $dashboard_id
     * @param $dashboardTitle
     *
     * @return array
     */
    public function generateData($dashboard_id, $dashboard_uid, $dashboard_version, $dashboardTitle)
    {
        $data = [
            "dashboard" => [
                "id" => $dashboard_id,
                "uid" => $dashboard_uid,
                "title" => $dashboardTitle,
                "tags" => ["Scout Checkr", "Site Health", "WordPress"],
                "timezone" => "browser",
                "schemaVersion" => 16,
                "version" => $dashboard_version,
                "refresh" => "25s",
            ],
            "message" => "Made changes to Scout Checkr",
            "overwrite" => true,
        ];

        return $data;
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $grafana_dashboard_url = get_option('grafana_dashboard_url') ?? '';

        preg_match('#/d/(.*)/#', $grafana_dashboard_url, $matches);

        $uid = $matches[1] ?? '';

        if (!$uid) {
            return false;
        }

        return $this->sendRequest(['DELETE' => 'DELETE'], 'api/dashboards/uid/' . $matches[1], 'DELETE');
    }

    /**
     * @param $uid
     *
     * @return mixed
     */
    public function getByUid($uid)
    {
        if (!$uid) {
            return false;
        }

        return $this->sendRequest($data = [], 'api/dashboards/uid/' . $uid);
    }
}
