<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

declare(strict_types=1);

namespace SpiralGrafana\Admin;

if (! defined('ABSPATH')) {
    return;
}

use SpiralGrafana\Plugin;

class Settings
{
    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @param Plugin $plugin
     * @param array $data_fields
     */
    public function __construct(Plugin $plugin, array $data_fields = [])
    {
        $this->plugin = $plugin;
        $this->data_fields = $data_fields;
    }

    /**
     * Init hooks
     */
    public function hooks(): void
    {
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_init', [$this, 'saveGrafanaSettings']);
        add_action('admin_notices', [$this, 'adminNotice']);
        add_action('admin_init', [$this, 'optionFieldsInit']);
        add_action('admin_init', [$this, 'redirectToSettings']);
    }

    /**
     *
     */
    public function adminMenu()
    {

        add_menu_page('Scout Checkr', 'Scout Checkr', 'manage_options', 'wordpress-plugin-grafana-settings', '', SPIRAL_GRAFANA_URL . 'images/ss_logomark.svg');

        add_submenu_page('wordpress-plugin-grafana-settings', __('Settings', 'scout-checkr'), __('Settings', 'scout-checkr'), 'manage_options', 'wordpress-plugin-grafana-settings', [ $this, 'display' ]);
    }

    /**
     *
     */
    public function display()
    {
        wp_enqueue_style('scout-checkr-admin-css-api-settings', SPIRAL_GRAFANA_URL . 'assets/admin/api_settings.css', [], SPIRAL_GRAFANA_VERSION);

        wp_enqueue_script('scout-checkr-admin-css-api-js', SPIRAL_GRAFANA_URL . 'assets/admin/api_settings.js', ['jquery'], SPIRAL_GRAFANA_VERSION, true);

        $title = get_option('grafana_dashboard_title') ?? '';
        $api = get_option('grafana_api') ?? '';
        $url = get_option('grafana_url') ?? '';
        $grafana_dashboard_url = get_option('grafana_dashboard_url') ?? '';

        if ($grafana_dashboard_url != '') {
            $dashboardStatus = true;
            if (preg_match('#/d/(.*)/#', $grafana_dashboard_url, $matches)) {
                $dashboardUrl = $this -> plugin -> grafanaDashboardAPI() -> getByUid($matches[1]);
                if (isset($dashboardUrl['message']) && $dashboardUrl['message'] == 'Dashboard not found') {
                    $dashboardStatus = false;
                }
            } else {
                $dashboardStatus = false;
            }

            if (!$dashboardStatus) {
                $grafana_dashboard_url = '';
                update_option('grafana_dashboard_url', '');
            }
        }

        $eff = filter_input(INPUT_GET, 'eff', FILTER_VALIDATE_INT);

        if (!empty($grafana_dashboard_url)) {
            $eff = 'success';
        } else {
            $eff++;
            if ($eff === 3) {
                $eff = 'error';
            }
        }

        require SPIRAL_GRAFANA_DIR . "templates/admin/api_settings.php";
    }

    public function optionFieldsInit()
    {
        add_option('grafana_api');
        add_option('grafana_dashboard_title');
        add_option('grafana_dashboard_id');
    }

    public function adminNotice()
    {
        ?>

        <style>
            .spiral-notification  {
                border-top: 10px solid #A30000;
                background: #f3cec8;
                padding: 20px;
            }

            .spiral-notification p {
                font-size: 20px;
            }
        </style>

        <?php
        require SPIRAL_GRAFANA_DIR . "templates/admin/notice.php";
    }

    public function saveGrafanaSettings()
    {

        $wordpress_plugin_grafana_settings_nonce = isset($this->data_fields['wordpress_plugin_grafana_settings_nonce']) ? sanitize_text_field(wp_unslash($this->data_fields['wordpress_plugin_grafana_settings_nonce'])) : '';

//            if (wp_verify_nonce($wordpress_plugin_grafana_settings_nonce, 'wordpress-plugin-grafana-settings-nonce')) {
        global $wpdb;

        $dashboardTitle = isset($this->data_fields['api_grafana_dashboard_title']) ? sanitize_text_field(wp_unslash($this->data_fields['api_grafana_dashboard_title'])) : '';
        $grafanaApi = isset($this->data_fields['api_grafana']) ? sanitize_text_field(wp_unslash($this->data_fields['api_grafana'])) : '';
        $grafanaUrl = isset($this->data_fields['api_grafana_dashboard_url']) ? sanitize_text_field(wp_unslash($this->data_fields['api_grafana_dashboard_url'])) : '';
        $grafanaUrl = filter_var($grafanaUrl, FILTER_VALIDATE_URL);

        if (empty($dashboardTitle) || empty($grafanaApi) || empty($grafanaUrl)) {
            return 'fill_all_fields';
        }

        update_option('grafana_dashboard_title', $dashboardTitle);
        update_option('grafana_api', $grafanaApi);
        update_option('grafana_url', $grafanaUrl);

        $dashboardUrl = $this->plugin->grafanaDashboardAPI()->create();

        if ($dashboardUrl) {
            update_option('grafana_dashboard_url', $dashboardUrl);

            return $dashboardUrl;
        }

        return 'repeat';
//        }
    }

    public function redirectToSettings()
    {
        if (get_option('wp_status_do_activation_redirect', false)) {
            delete_option('wp_status_do_activation_redirect');
            wp_redirect(admin_url('admin.php?page=wordpress-plugin-grafana-settings'));
            exit;
        }
    }
}
