<?php
/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

namespace SpiralGrafanaTests\Admin;

if (! defined('ABSPATH')) {
	return;
}

use SpiralGrafana\Plugin;
use SpiralGrafana\Admin\Settings;

use SpiralGrafanaTests\TestCase;

use Mockery;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\Functions\stubs;
use function Brain\Monkey\Actions\expectDone;
use function Brain\Monkey\Filters\expectApplied;

class SettingsTest extends TestCase
{
	protected $instance;

	/**
	 * Sets the instance.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->instance = \Mockery::mock( Settings::class)->makePartial();
	}

	public function testHooks(): void
	{
		$this->instance->hooks();

		$this->assertSame( 10, has_action('admin_menu', [ $this->instance, 'adminMenu']));
		$this->assertSame( 10, has_action('admin_init', [ $this->instance, 'saveGrafanaSettings']));
		$this->assertSame( 10, has_action('admin_notices', [ $this->instance, 'adminNotice']));
		$this->assertSame( 10, has_action('admin_init', [ $this->instance, 'optionFieldsInit']));
	}

	public function testAdminMenu()
	{
		expect( '\add_menu_page' )
			->once()
			->with( 'Scout Checkr', 'Scout Checkr', 'manage_options', 'wordpress-plugin-grafana-settings',  \Mockery::type( 'string' ),  \Mockery::type( 'string' ) )
			->andReturn( true );

		expect( '\add_submenu_page' )
			->once()
			->with( 'wordpress-plugin-grafana-settings', \Mockery::type( 'string' ), \Mockery::type( 'string' ), 'manage_options', 'wordpress-plugin-grafana-settings', [ $this->instance, 'display' ] )
			->andReturn( true );

		expect( '\__' )
			->with( \Mockery::type( 'string' ), 'scout-checkr' )
			->atLeast()
			->andReturn( 'translated' );

		$this->instance -> adminMenu();
	}

	public function testDisplay()
	{
		expect( '\wp_enqueue_style' )->once();

		expect( '\wp_enqueue_script' )->once();

		expect( '\wp_nonce_field' )
			->once()
			->with( 'wordpress-plugin-grafana-settings' )
			->andReturn( true );

		expect( '\wp_create_nonce' )
			->once()
			->with( 'wordpress-plugin-grafana-settings-nonce' )
			->andReturn( 'translated' );

		expect( '\esc_html_e' )
			->with( \Mockery::type( 'string' ), 'textdomain' )
			->atLeast()
			->andReturn( 'translated' );

		expect( '\esc_attr' )
			->twice()
			->with('translated')
			->andReturn( 'translated' );

		expect( '\get_option' )
			->once()
			->with( 'grafana_dashboard_title' )
			->andReturn( 'grafana_dashboard_title' );

		expect( '\get_option' )
			->once()
			->with( 'grafana_api' )
			->andReturn( 'grafana_api' );

		expect( '\get_option' )
			->once()
			->with( 'grafana_url' )
			->andReturn( 'grafana_url' );

		expect( '\get_option' )
			->once()
			->with( 'grafana_dashboard_url' )
			->andReturn( 'grafana_dashboard_url' );

		expect( '\update_option' )
			->once()
			->with( 'grafana_dashboard_url', \Mockery::type( 'string' ) );

        expect( '\admin_url' )
            ->once()
            ->with( 'admin.php' )
            ->andReturn( 'admin.php' );

        expect( '\esc_url' )
            ->with( \Mockery::type( 'string' ), 'textdomain' )
            ->atLeast()
            ->andReturn( 'translated' );

        expect( '\esc_html' )
            ->with( \Mockery::type( 'string' ), 'textdomain' )
            ->atLeast()
            ->andReturn( 'translated' );


		$this->instance->display();
	}
}
