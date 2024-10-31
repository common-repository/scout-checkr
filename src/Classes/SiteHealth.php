<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

declare(strict_types=1);

namespace SpiralGrafana\Classes;

use SpiralGrafana\Front\MarkupGenerator;
use SpiralGrafana\Classes\GrafanaAPI\Dashboard;

if (!defined('ABSPATH')) {
    return;
}

if (!defined('PHPUNIT_RUNNING') && ! class_exists('WP_Site_Health')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
}

class SiteHealth extends \WP_Site_Health
{
    public static function getResults()
    {
        if (!defined('PHPUNIT_RUNNING')) {
            // Bootstrap wp-admin, as WP_Cron doesn't do this for us.
            require_once trailingslashit(ABSPATH) . 'wp-admin/includes/admin.php';
        }

        $tests = \WP_Site_Health::get_tests();

        if (!$tests) {
            return false;
        }

        $reflectionMethod = new \ReflectionMethod(parent::class, 'perform_test');

        $reflectionMethod->setAccessible(true);

        $results = [];

        $site_status = [
            'good' => 0,
            'recommended' => 0,
            'critical' => 0,
        ];

        foreach ($tests['direct'] as $test) {
            if (! empty($test['skip_cron'])) {
                continue;
            }

            if (is_string($test['test'])) {
                $test_function = sprintf(
                    'get_test_%s',
                    $test['test']
                );

                if (method_exists(parent::class, $test_function) && is_callable([ parent::class, $test_function ])) {
                    $testResult = $reflectionMethod->invoke(\WP_Site_Health::get_instance(), [\WP_Site_Health::get_instance(), $test_function ]);
                    $testResult['description'] = html_entity_decode(strip_tags($testResult['description']));

                    $results[] = $testResult;

                    continue;
                }
            }

            if (is_callable($test['test'])) {
                $testResult = $reflectionMethod->invoke(\WP_Site_Health::get_instance(), $test['test']);

                $testResult['description'] = html_entity_decode(strip_tags($testResult['description']));

                $results[] = $testResult;
            }
        }

        foreach ($tests['async'] as $test) {
            if (! empty($test['skip_cron'])) {
                continue;
            }

            // Local endpoints may require authentication, so asynchronous tests can pass a direct test runner as well.
            if (! empty($test['async_direct_test']) && is_callable($test['async_direct_test'])) {
                // This test is callable, do so and continue to the next asynchronous check.
                $testResult = $reflectionMethod->invoke(\WP_Site_Health::get_instance(), $test['async_direct_test']);

                $testResult['description'] = html_entity_decode(strip_tags($testResult['description']));

                $results[] = $testResult;

                continue;
            }

            if (is_string($test['test'])) {
                // Check if this test has a REST API endpoint.
                if (isset($test['has_rest']) && $test['has_rest']) {
                    $result_fetch = wp_remote_get(
                        $test['test'],
                        [
                            'body' => [
                                '_wpnonce' => wp_create_nonce('wp_rest'),
                            ],
                        ]
                    );
                } else {
                    $result_fetch = wp_remote_post(
                        admin_url('admin-ajax.php'),
                        [
                            'body' => [
                                'action' => $test['test'],
                                '_wpnonce' => wp_create_nonce('health-check-site-status'),
                            ],
                        ]
                    );
                }

                if (! is_wp_error($result_fetch) && 200 === wp_remote_retrieve_response_code($result_fetch)) {
                    $result = json_decode(wp_remote_retrieve_body($result_fetch), true);
                } else {
                    $result = false;
                }

                if (is_array($result)) {
                    $results[] = $result;
                } else {
                    $results[] = [
                        'status' => 'recommended',
                        'label' => __('A test is unavailable'),
                    ];
                }
            }
        }

        $markupGenerator = new MarkupGenerator();
        $output = $markupGenerator->generateHtmlTable($results);

        return $output;
    }
}
