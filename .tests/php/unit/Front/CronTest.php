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
use SpiralGrafana\Front\Cron;
use SpiralGrafana\Classes\SiteHealth;
use SpiralGrafana\Classes\GrafanaAPI\Dashboard;

use SpiralGrafanaTests\TestCase;

use Mockery;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\Functions\stubs;
use function Brain\Monkey\Actions\expectDone;
use function Brain\Monkey\Filters\expectApplied;

class CronTest extends TestCase
{
	protected $instance;

	/**
	 * Sets the instance.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->instance = \Mockery::mock( Cron::class, [\Mockery::mock(Plugin::class)])->makePartial();
	}

    public function testHooks(): void
    {

    }

    public function testScheduleEvents()
    {
	    expect( '\wp_next_scheduled' )
		    ->once()
		    ->with( 'spiral_grafana_test_results_event' )
		    ->andReturn( false );

	    expect( '\wp_schedule_event' )
		    ->once()
		    ->with( time(), '5min', 'spiral_grafana_test_results_event' )
		    ->andReturn( true );

        $this->instance->scheduleEvents();
    }

    public function testUnscheduleEvents()
    {
        expect( '\wp_clear_scheduled_hook' )
            ->once()
            ->with( 'spiral_grafana_test_results_event' )
            ->andReturn( 'translated' );

        $this->instance->unscheduleEvents();
    }

    public function testCronSchedules()
    {
        $schedules = [
          '1min' => true,
          '5min' => true,
          '10min' => true,
          //'30min' => true,
        ];

	    expect( '\__' )
		    ->with( \Mockery::type( 'string' ) )
		    ->atLeast()
		    ->andReturn( \Mockery::type( 'string' ) );

        $this->instance->cronSchedules($schedules);
    }
}
