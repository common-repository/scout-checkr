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

use SpiralGrafana\Plugin;
use SpiralGrafana\Classes\SiteHealth;
use SpiralGrafana\Classes\GrafanaAPI\Dashboard;

class Cron
{
    /**
     * @var Plugin
     */
    private $plugin;
/**
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Init hooks
     */
    public function hooks(): void
    {
        add_filter('cron_schedules', [$this, 'cronSchedules']);

        add_action('spiral_grafana_test_results_event', [$this -> plugin -> grafanaDashboardAPI(), 'create']);
    }

    /**
     * Shedule events
     */
    public static function scheduleEvents()
    {
        if (! wp_next_scheduled('spiral_grafana_test_results_event')) {
            wp_schedule_event(time(), '5min', 'spiral_grafana_test_results_event');
        }
    }

    /**
     * Unshedule events
     */
    public static function unscheduleEvents()
    {
        wp_clear_scheduled_hook('spiral_grafana_test_results_event');
    }

    /**
     * @param $schedules
     *
     * @return mixed
     */
    public static function cronSchedules($schedules)
    {
        if (! isset($schedules['1min'])) {
            $schedules['1min'] = [
                'interval' => 60, 'display' => __('Every minute'),
            ];
        }
        if (! isset($schedules['5min'])) {
            $schedules['5min'] = [
                'interval' => 5 * 60, 'display' => __('Every 5 minutes'),
            ];
        }
        if (! isset($schedules['10min'])) {
            $schedules['10min'] = [
                'interval' => 10 * 60, 'display' => __('Every 10 minutes'),
            ];
        }
        if (! isset($schedules['30min'])) {
            $schedules['30min'] = [
                'interval' => 30 * 60, 'display' => __('Every 30 minutes'),
            ];
        }

        return $schedules;
    }
}
