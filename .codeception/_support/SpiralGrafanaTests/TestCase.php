<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

/**
 * TestCase for Unit tests
 */

namespace SpiralGrafanaTests;

use Mockery;

use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

/**
 * Class TestCase
 *
 * @since   1.0.0
 *
 * @package SpiralGrafana
 */
abstract class TestCase extends \Codeception\PHPUnit\TestCase {

	/**
	 * This method is called before each test.
	 *
	 * @since   1.0.0
	 */
	protected function setUp(): void {
		parent::setUp();
		setUp();
	}

	/**
	 * This method is called after each test.
	 *
	 * @since   1.0.0
	 */
	protected function tearDown(): void {
		tearDown();
		Mockery::close();
		parent::tearDown();
	}

}
