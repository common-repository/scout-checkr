<?php

/**
 * Scout Checkr.
 *
 * @license   MIT
 * @author    Spiral Scout
 * @link    https://spiralscout.com/
 */

declare(strict_types=1);

namespace SpiralGrafana\Front;

if (! defined('ABSPATH')) {
    return;
}

use SpiralGrafana\Plugin;

class Front
{
    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Init hooks
     */
    public function hooks(): void
    {
    }
}
