<?php
/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

/**
 * Bootstrap file for unit tests that run before all tests.
 */

define('SPIRAL_GRAFANA_DEBUG', true);
define('SPIRAL_GRAFANA_PATH', realpath(__DIR__ . '/../../../') . '/');
define('ABSPATH', realpath(SPIRAL_GRAFANA_PATH . '../../../') . '/');
define('SPIRAL_GRAFANA_URL', 'https://example.com/wp-content/plugins/scout-checkr/');
define('SPIRAL_GRAFANA_DIR', realpath(__DIR__ . '/../../../') . '/');
define('SPIRAL_GRAFANA_VERSION', '0.1.51');
define('PHPUNIT_RUNNING', 1);
define( 'AUTH_SALT',        'j+r&T-78MAQeb5BJF:UXVkE)^JAgSR&@.S4%jn2SqcfN:TJBh{PN1C|z]T3dq|Fg' );
require_once SPIRAL_GRAFANA_PATH . 'vendor/autoload.php';

class WP_Site_Health
{
	static public function get_tests() {

	}

	static public function get_instance() {

	}

	static public function perform_test() {

	}
}
