<?php

/**
 * Plugin Name:         Scout Checkr
 * Description:         Scout Checkr
 * Version:             0.1.75
 * Requires at least:   4.9
 * Requires PHP:        5.5
 * Tested up to: 6.0
 * Author:              Spiral Scout
 * Author URI:          https://spiralscout.com/
 * License:             MIT
 * Text Domain:         scout-checkr
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

use SpiralGrafana\Plugin;
use Auryn\Injector;
use SpiralGrafana\Admin\Settings;
use SpiralGrafana\Classes\GrafanaAPI\Dashboard;

define('SPIRAL_GRAFANA_DIR', plugin_dir_path(__FILE__));
define('SPIRAL_GRAFANA_URL', plugin_dir_url(__FILE__));
define('SPIRAL_GRAFANA_VERSION', '0.1.75');

require_once SPIRAL_GRAFANA_DIR . 'vendor/autoload.php';

function activateSpiralGrafana(): void
{
    // this can't be lazy loaded
    add_filter('cron_schedules', '\SpiralGrafana\Front\Cron::cronSchedules');

    \SpiralGrafana\Front\Cron::scheduleEvents();

    add_option('wp_status_do_activation_redirect', true);
}

register_activation_hook(__FILE__, 'activateSpiralGrafana');

function deactivateSpiralGrafana(): void
{
    // this can't be lazy loaded
    add_filter('cron_schedules', '\SpiralGrafana\Front\Cron::cronSchedules');

    (new Dashboard())->delete();

    \SpiralGrafana\Front\Cron::unscheduleEvents();

    update_option('grafana_dashboard_title', '');
    update_option('grafana_api', '');
    update_option('grafana_url', '');
    update_option('grafana_dashboard_url', '');
    update_option('grafana_dashboard_id', '');
    update_option('grafana_dashboard_uid', '');
    update_option('grafana_dashboard_version', '');
}

register_deactivation_hook(__FILE__, 'deactivateSpiralGrafana');

/**
 * @throws \Auryn\InjectionException
 */
function run_wp_status()
{
    load_plugin_textdomain('scout-checkr', false, SPIRAL_GRAFANA_DIR . '/languages/');

    $injector = new Injector();

    ( $injector->make(Plugin::class) )->run();

    do_action('wp_status_init', $injector);
}

add_action('plugins_loaded', 'run_wp_status');
