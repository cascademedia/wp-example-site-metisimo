<?php
/**
 * Configuration overrides for WP_ENV === 'development'
 */

use Roots\WPConfig\Config;

Config::define('WP_SITE_TITLE', env('WP_SITE_TITLE'));
Config::define('WP_ADMIN_USER', env('WP_ADMIN_USER'));
Config::define('WP_ADMIN_PASS', env('WP_ADMIN_PASS'));
Config::define('WP_ADMIN_EMAIL', env('WP_ADMIN_EMAIL'));

Config::define('SAVEQUERIES', true);
Config::define('WP_DEBUG', true);
Config::define('WP_DEBUG_DISPLAY', true);
Config::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
Config::define('SCRIPT_DEBUG', true);

ini_set('display_errors', '1');

//@todo
//Config::define('WP_DEFAULT_THEME', 'generatepress');
