<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$page = sanitize_text_field(wp_unslash($_GET['page'] ?? ''));

if (!empty(wp_unslash(get_option('grafana_api')) || esc_html($page) === 'wordpress-plugin-grafana-settings')) {
    return;
}
?>
<div class="wrap spiral-notification">
    <p>
        Looks like Scout Checkr plugin didn`t configurated yet. You can configurate it  <a href="<?php echo esc_url(get_dashboard_url()); ?>admin.php?page=wordpress-plugin-grafana-settings">here</a>.
    </p>
</div>
