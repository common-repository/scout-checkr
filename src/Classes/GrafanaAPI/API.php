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

use SpiralGrafana\Classes\GrafanaAPI\Config;

class API extends Config
{
    protected $settings;

    public function __construct()
    {
        $this->settings = $this ->  getApiCredentionals();
    }

    protected function sendRequest($data, $route, $method = 'POST')
    {
	    $args = array(
		    'headers'     =>
			                            [
			                            	'Accept' => 'application/json',
				                            'Content-Type' => 'application/json',
				                            'Authorization' => 'Bearer ' . $this->settings['key']
			                            ]
	    );

	    if ($method === 'DELETE') {
		    $args['method'] = 'DELETE';
	    }

        if (sizeof($data)) {
	        $args['body'] = json_encode( $data );
	        $result = wp_remote_post( $this->settings['url'] . $route, $args );
        }  else {
	        $result = wp_remote_get( $this->settings['url'] . $route, $args );
        }
        
	    if (!is_wp_error( $result )) {
	        $body     = wp_remote_retrieve_body( $result );
		    return json_decode($body, true);
        } else {
            return false;
        }
    }
}
