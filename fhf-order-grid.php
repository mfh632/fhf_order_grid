<?php

/**
 * Plugin Name: FHF Order Grid
 * Plugin URI: https://wordpress661.test
 * Description: A custom plugin for display order grid
 * Version: 1.0.0
 * Author: Fizul Haque
 * Author URI: https://fhaque.com.bd
 * License: GPL2
 * Text Domain: fogrid
 */

define('FOGRID_PLUGIN_DIR', dirname(__FILE__));
define('FOGRID_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once('autoload.php');

\FHF\OrderGrid\Settings::create_instance();
\FHF\OrderGrid\Enqueue::create_instance();