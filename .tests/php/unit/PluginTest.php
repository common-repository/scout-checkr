<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

namespace SpiralGrafanaTests;

use Exception;
use Auryn\Injector;
use Auryn\InjectionException;
use SpiralGrafana\Plugin;
use SpiralGrafana\Front\Front;
use SpiralGrafana\Front\Cron;
use SpiralGrafana\Admin\Settings;

use SpiralGrafanaTests\TestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\Functions\stubs;
use function Brain\Monkey\Actions\expectDone;
use function Brain\Monkey\Filters\expectApplied;

class PluginTest extends TestCase
{
    public function testRunAdmin(): void
    {
	    expect( '\get_option' )
		    ->once()
		    ->with( 'grafana_url' )
		    ->andReturn( 'grafana_url' );

	    expect( '\get_option' )
		    ->once()
		    ->with( 'grafana_api' )
		    ->andReturn( 'grafana_api' );

	    $injector = \Mockery::mock( 'Auryn\Injector' );

	    $plugin = new Plugin( $injector );

	    expect( 'is_admin' )
		    ->once()
		    ->withNoArgs()
		    ->andReturn( true );

	    $settings = \Mockery::mock( Settings::class );
	    $settings
		    ->shouldReceive( 'hooks' )
		    ->once()
		    ->withNoArgs();

	    $injector
		    ->shouldReceive( 'make' )
		    ->once()
		    ->with( Settings::class, [$plugin] )
		    ->andReturn( $settings );

	    $cron = \Mockery::mock( Cron::class );
	    $cron
		    ->shouldReceive( 'hooks' )
		    ->once()
		    ->withNoArgs();

	    $injector
		    ->shouldReceive( 'make' )
		    ->once()
		    ->with( Cron::class, [$plugin] )
		    ->andReturn( $cron );

	    $plugin->run();
    }

    public function testRunFront(): void
    {
	    expect( '\get_option' )
		    ->once()
		    ->with( 'grafana_url' )
		    ->andReturn( 'grafana_url' );

	    expect( '\get_option' )
		    ->once()
		    ->with( 'grafana_api' )
		    ->andReturn( 'grafana_api' );

	    $injector = \Mockery::mock( 'Auryn\Injector' );

	    $plugin = new Plugin( $injector );

	    $front = \Mockery::mock( Front::class );
	    $front
		    ->shouldReceive( 'hooks' )
		    ->once()
		    ->withNoArgs();

	    $injector
		    ->shouldReceive( 'make' )
		    ->once()
		    ->with( Front::class, [$plugin] )
		    ->andReturn( $front );

	    $cron = \Mockery::mock( Cron::class );
	    $cron
		    ->shouldReceive( 'hooks' )
		    ->once()
		    ->withNoArgs();

	    $injector
		    ->shouldReceive( 'make' )
		    ->once()
		    ->with( Cron::class, [$plugin] )
		    ->andReturn( $cron );

	    expect( 'is_admin' )
		    ->once()
		    ->withNoArgs()
		    ->andReturn( false );

	    $plugin->run();
    }
}
