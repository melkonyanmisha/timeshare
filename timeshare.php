<?php
/*
Plugin Name:    Timeshare
Description:    Add custom functionality to Timeshare theme
Author:         Misha
Version:        1.0.0
Text Domain:    timeshare
Domain Path:    /languages/
License:        GNU General Public License v2.0
License URI:    http://www.gnu.org/licenses/gpl-2.0.html
*/


// Prevent direct script access
if ( ! defined('ABSPATH')) {
    die ('No direct script access allowed');
}


if ( ! class_exists('Timeshare')) {
    final class Timeshare
    {

        /**
         * Constructor.
         */
        public function __construct()
        {
            //Invokes here to avoid errors e.g when used in a theme.
            $this->define_constants();

            if (version_compare(phpversion(), REQUIRED_PHP, '>=')) {
                $this->hooks();
                $this->includes();
                $this->load_translations();
            } else {
                add_action('admin_notices', array($this, 'old_php_admin_error_notice'));
            }
        }

        /**
         * Define Constants
         */
        private function define_constants(): void
        {
            //To include files
            define('TIMESHARE_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
            define('TIMESHARE_BASENAME', plugin_basename(__FILE__));
            define('TIMESHARE_INC_PATH', untrailingslashit(plugin_dir_path(__FILE__)) . '/includes');
            define('TIMESHARE_ASSETS_URL', untrailingslashit(plugin_dir_url(__FILE__)) . '/assets');

            //To work with plugin core functionality
            define('TIMESHARE_VERSION', '1.0.0');
            define('REQUIRED_PHP', '7.4');
            define('USER_GROUP_TAXONOMY', 'user_group');
            define('USER_GROUP_TAXONOMY_META_KEY', '_user_group');

            define('TIMESHARE_USER_TAXONOMY', 'timeshare_user');
            define('TIMESHARE_USER_TAXONOMY_META_KEY', '_timeshare_user_group');
        }

        /**
         * Plugin main hooks
         *
         * @return void
         */
        private function hooks(): void
        {
            add_action('admin_enqueue_scripts', [$this, 'timeshare_enqueue_scripts']);
        }

        /**
         * Load javascript and css files
         *
         * @return void
         */
        public function timeshare_enqueue_scripts(): void
        {
            //Enqueue JS
            wp_enqueue_script(
                'timeshare-main-js',
                TIMESHARE_ASSETS_URL . '/js/script.js',
                ['jquery'],
                TIMESHARE_VERSION
            );

            wp_localize_script('timeshare-main-js', 'timeshare_main', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'USER_GROUP_TAXONOMY' => USER_GROUP_TAXONOMY,
            ]);

            //Enqueue CSS
            wp_enqueue_style('timeshare-main-css', TIMESHARE_ASSETS_URL . '/css/style.css', [], TIMESHARE_VERSION);
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        private function includes(): void
        {
            if (defined('TIMESHARE_INC_PATH')) {
                require_once(TIMESHARE_INC_PATH . '/user/custom-fields/user-group.php');
                require_once(TIMESHARE_INC_PATH . '/user/custom-fields/timeshare/index.php');
            }
        }

        /**
         * Display an admin error notice when PHP is older the version 7.4.
         * Hook it to the 'admin_notices' action.
         */
        public function old_php_admin_error_notice(): void
        { /* translators: %1$s - the PHP version, %2$s and %3$s - strong HTML tags, %4$s - br HTMl tag. */
            $message = sprintf(
                esc_html__(
                    'The %2$sTimeshare%3$s plugin requires %2$sPHP %4$s+%3$s to run properly.: Current version is %2$s%1$s%3$s',
                    'timeshare'
                ),
                phpversion(),
                '<strong>',
                '</strong>',
                REQUIRED_PHP
            );
            printf('<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post($message));
        }

        /**
         * Load the plugin translations
         */
        private function load_translations(): void
        {
            load_plugin_textdomain('timeshare', false, dirname(TIMESHARE_BASENAME) . '/languages');
            load_plugin_textdomain('timeshare');
        }

    }
}


new Timeshare();
