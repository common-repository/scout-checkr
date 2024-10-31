<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

namespace SpiralGrafana;

use Exception;
use Auryn\Injector;
use Auryn\InjectionException;
use SpiralGrafana\Front\Front;
use SpiralGrafana\Front\Cron;
use SpiralGrafana\Admin\Settings;
use SpiralGrafana\Classes\GrafanaAPI\Dashboard as GrafanaDashboardAPI;

    /**
 * Class Plugin
 *
 * @package SpiralGrafana
 */
class Plugin
{
    /**
     * Dependency Injection Container.
     *
     * @since 1.0.1
     *
     * @var Injector
     */
    private $injector;

    private $grafanaDashboardAPI;

    /**
     * Plugin constructor.
     *
     * @param Injector $injector Dependency Injection Container.
     */
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;

        $this->grafanaDashboardAPI = new GrafanaDashboardAPI();
    }

    /**
     * Run plugin
     *
     * @since 1.0.1
     *
     * @throws Exception Object doesn't exist.
     */
    public function run(): void
    {
        ( is_admin() && (!defined('DOING_AJAX') || (defined('DOING_AJAX') && !DOING_AJAX)))
            ? $this->runAdmin()
            : $this->runFront();

        $this->injector->make(Cron::class, [$this])->hooks();

        add_action('wp_ajax_wp_status_install', [$this, 'wp_status_install']);
    }

    /**
     * Run admin part
     *
     * @since 1.0.1
     *
     * @throws InjectionException If a cyclic gets detected when provisioning.
     */
    private function runAdmin(): void
    {
        $this->injector->make(Settings::class, [$this])->hooks();
    }

    /**
     * Run frontend part
     *
     * @since 1.0.1
     *
     * @throws InjectionException If a cyclic gets detected when provisioning.
     */
    private function runFront(): void
    {
        $this->injector->make(Front::class, [$this])->hooks();
    }

    /**
     * @return GrafanaDashboardAPI
     */
    public function grafanaDashboardAPI()
    {
        return $this->grafanaDashboardAPI;
    }

    public function wp_status_install($effort)
    {
        $injector = new Injector();
        $plugin = new Plugin($injector);
        $settings = new Settings($plugin, $_POST);
        $update = $settings->saveGrafanaSettings();

        $grafana_dashboard_url = get_option('grafana_dashboard_url');

        if (empty($effort)) {
            $effort = 1;
        }
        $effort++;
        if ($effort === 4) {
            echo 'error';
            wp_die();
        }

        if ($update === 'repeat') {
            $this -> wp_status_install($effort);
        }

        $statuses = [
            'error',
            'fill_all_fields',
        ];

        if (in_array($update, $statuses) && empty($grafana_dashboard_url)) {
            echo esc_html($update);
        } else {
            echo esc_html($update);
        }

        wp_die();
    }
}
