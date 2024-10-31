<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

namespace SpiralGrafanaTests\Front;

if (!defined('ABSPATH')) {
    return;
}

use SpiralGrafana\Plugin;
use SpiralGrafana\Front\Front;

use SpiralGrafanaTests\TestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\Functions\stubs;
use function Brain\Monkey\Actions\expectDone;
use function Brain\Monkey\Filters\expectApplied;

class FrontTest  extends TestCase
{
	protected $instance;

	/**
	 * Sets the instance.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->instance = \Mockery::mock( Front::class)->makePartial();
	}

    public function testHooks(): void
    {
	    $this->instance->hooks();
    }
}
