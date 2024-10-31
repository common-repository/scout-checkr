<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="spiral-status-wrapper">

    <div class="spiral-status-form">
        <div class="spiral-status-sidebar">

            <div class="spiral-status-header">
                <a href="https://spiralscout.com/" target="_blank">
                    <img src="<?php echo esc_url(SPIRAL_GRAFANA_URL . 'images/ss_logomark.svg'); ?>" alt="">
                    <h1>
                        Spiral Scout
                    </h1>
                </a>
            </div>
        </div>
        <div class="spiral-status-main">
            <div class="spiral-status-steps">
                <div class="spiral-status-step-1 spiral-status-step">
                    <h3><?php esc_html_e('To get an API key in Grafana: Open the side menu and click on the organization configuration icon and within the dropdown options, select the API Keys option (If you already have an API key from Admin Role you can skip this instruction):
', 'scout-checkr'); ?></h3>
                    <img src="<?php echo esc_url(SPIRAL_GRAFANA_URL . 'images/step-1.png'); ?>" alt="">
                </div>
                <div class="spiral-status-step-1 spiral-status-step">
                    <h3><?php esc_html_e('Press the button to create your first API key or add a new key', 'scout-checkr'); ?></h3>
                    <img src="<?php echo esc_url(SPIRAL_GRAFANA_URL . 'images/step-2.png'); ?>" alt="">
                </div>
                <div class="spiral-status-step-1 spiral-status-step">
                    <h3><?php esc_html_e('Please name your Key, then set the "Admin" role. We recommend using “10y” as the value for time to live. Then press the [Add] button and proceed to the next step of the instruction.', 'scout-checkr'); ?></h3>
                    <img src="<?php echo esc_url(SPIRAL_GRAFANA_URL . 'images/step-3.png'); ?>" alt="">
                </div>
                <div class="spiral-status-step-1 spiral-status-step">
                    <h3><?php esc_html_e('Press the [Copy] button to copy the API key and then hit the [Next Step] button.', 'scout-checkr'); ?></h3>
                    <img src="<?php echo esc_url(SPIRAL_GRAFANA_URL . 'images/step-4.jpg'); ?>" alt="">
                </div>
                <div class="spiral-status-step-2 grafana-config-step spiral-status-step">

                    <h1><?php esc_html_e('Grafana API Data for integration with site info', 'scout-checkr'); ?></h1>
                    <p class="alert-danger hide">
                        <strong>Error:</strong> Dashboard is not created. Please double check your Grafana Dashboard URL as well as API key and make sure that you don’t have any other dashboard titles with the same name
                    </p>
                    <form method="post" action="<?php echo esc_url(admin_url('admin.php')); ?>?page=wordpress-plugin-grafana-settings" id="spiral-status-form" enctype="multipart/form-data">
                        <?php wp_nonce_field('wordpress-plugin-grafana-settings'); ?>

                        <input type="hidden" name="wordpress_plugin_grafana_settings_nonce" id="wordpress_plugin_grafana_settings_nonce"
                               value="<?php echo esc_attr(wp_create_nonce('wordpress-plugin-grafana-settings-nonce')); ?>"/>
                        <p class="input-wrapper">
                            <label for="api-grafana-dashboard-title">
                                <?php esc_html_e('Next, enter the name of the website you are monitoring. This should be a unique name that you will remember.', 'scout-checkr'); ?>
                            </label>
                            <input type="text" id="api-grafana-dashboard-title" name="api-grafana-dashboard-title"
                                   value="<?php echo empty($title) ? esc_attr(get_bloginfo('name')) : esc_attr($title); ?>" class="width100"
                                    <?php echo !empty(esc_url($grafana_dashboard_url)) ? 'disabled="disabled"' : ''; ?>
                                   required/>
                            <span class="error">
                                Please fill the field
                            </span>
                        </p>
                        <p class="input-wrapper">
                            <label for="api-grafana-dashboard-url">
                                <?php esc_html_e('Then link to your Grafana Account by entering the correctly formatted URL', 'scout-checkr'); ?>
                            </label>
                            <input type="url" pattern="https://.*\/$" id="api-grafana-dashboard-url"
                                   placeholder="https://your-dashboard.grafana.net/" name="api-grafana-dashboard-url"
                                   value="<?php echo esc_attr($url); ?>" class="width100" <?php echo !empty(esc_url($grafana_dashboard_url)) ? 'disabled="disabled"' : ''; ?> required/>
                            <span class="error">
                                Please fill the field
                            </span>
                        </p>
                        <p class="input-wrapper">
                            <label for="api-grafana">
                                <?php esc_html_e('Next, enter your Grafana API key (Created on previous steps). You should be able to paste it into the form.', 'scout-checkr'); ?>
                            </label>
                            <input type="text" id="api-grafana" name="api-grafana" value="<?php echo esc_attr($api); ?>"
                                   class="width100" <?php echo !empty(esc_url($grafana_dashboard_url)) ? 'disabled="disabled"' : ''; ?> required/>
                            <span class="error">
                                Please fill the field
                            </span>
                        </p>
                        <p class="input-wrapper">
                            <label for="api-grafana">
                                <?php esc_html_e('Once everything is entered correctly you can click the [Connect] button below.', 'scout-checkr'); ?>
                            </label>
                        </p>
                        <input type="submit" <?php echo !empty(esc_url($grafana_dashboard_url)) ? 'disabled="disabled"' : ''; ?> name="Submit" id="grafana-submit" class="spiral-status-button button submit"
                               value="<?php esc_attr(esc_html_e('Connect your site to Grafana Account', 'scout-checkr')); ?>"/>
                    </form>
                </div>
                <div class="spiral-status-step <?php echo !empty(esc_url($grafana_dashboard_url)) ? 'show' : ''; ?>">
                    <div class="spiral-status-link">
                        <?php if (!empty(esc_url($grafana_dashboard_url))) : ?>
                            <h3>
                                You can use your Grafana Dashboard link to monitor your site’s health:<br>
                                <a id="grafana-panel-link" href="<?php echo esc_url($grafana_dashboard_url); ?>"
                                   target="_blank"><?php echo esc_html($grafana_dashboard_url); ?></a><br><br>
                                For further questions please contact <a href="mailto:team@spiralscout.com">team@spiralscout.com</a> for additional help and support.
                            </h3>
                        <?php endif ?>
                    </div>
                </div>

            </div>
            <?php if (empty(esc_url($grafana_dashboard_url))) : ?>
                <div class="spiral-status-buttons">
                    <div class="spiral-status-button button prev <?php echo empty(esc_url($grafana_dashboard_url)) ? 'hide' : ''; ?>">
                        Previous step
                    </div>
                    <div class="spiral-status-button button next <?php echo !empty(esc_url($grafana_dashboard_url)) ? 'hide' : ''; ?>">
                        Next step
                    </div>
                </div>
                <div class="spiral-status-skip">
                    <div class="spiral-status-button skip-instructions">Skip
                        instruction
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
