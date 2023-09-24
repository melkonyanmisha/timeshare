<?php
/*
Plugin Name:    Timeshare
Description:    Add custom functionality to Timeshare theme
Author:         Melkonyan Misha
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
         *
         * @return void
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

            //To save price calculation data in wp_options table
            define('TIMESHARE_PRICE_CALC_DATA', 'timeshare_price_calc_data');

            //Meta Key
            define('TIMESHARE_USER_DATA', 'timeshare_user_data');
            //The Timeshare user package duration
            define('TIMESHARE_PACKAGE_DURATION', 'timeshare_package_duration');
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
         * @return bool
         */
        private function is_timeshare_plugin_page(): bool
        {
            return get_current_screen()->id === 'users_page_timeshare-users-page';
        }

        /**
         * Load javascript and css files
         *
         * @return void
         */
        public function timeshare_enqueue_scripts(): void
        {
            if ( ! $this->is_timeshare_plugin_page()) {
                return;
            }

            #########Start User Package##########
            //Enqueue Main CSS files
            wp_enqueue_style(
                'timeshare-user-package-main-css',
                TIMESHARE_ASSETS_URL . '/css/user-package/style.css',
                [],
                TIMESHARE_VERSION
            );
            #########End User Package##########

            #########Start Price Calculation##########
            //Enqueue JS files
            wp_enqueue_script(
                'price-calc-jquery-js',
                TIMESHARE_ASSETS_URL . '/js/price-calculation/jquery.min.js',
                [],
                TIMESHARE_VERSION
            );

            wp_enqueue_script(
                'price-calc-disable-js',
                TIMESHARE_ASSETS_URL . '/js/price-calculation/disable.js',
                ['price-calc-jquery-js'],
                TIMESHARE_VERSION
            );

            wp_enqueue_script(
                'price-calc-date-range-js',
                TIMESHARE_ASSETS_URL . '/js/price-calculation/date-range.js',
                ['price-calc-jquery-js'],
                TIMESHARE_VERSION
            );

            wp_enqueue_script(
                'price-calc-week-js',
                TIMESHARE_ASSETS_URL . '/js/price-calculation/week.js',
                ['price-calc-jquery-js'],
                TIMESHARE_VERSION
            );

            wp_enqueue_script(
                'price-calc-storage-js',
                TIMESHARE_ASSETS_URL . '/js/price-calculation/storage.js',
                ['price-calc-jquery-js'],
                TIMESHARE_VERSION
            );

            wp_enqueue_script(
                'price-calc-main-js',
                TIMESHARE_ASSETS_URL . '/js/price-calculation/index.js',
                ['price-calc-jquery-js'],
                TIMESHARE_VERSION
            );

            wp_localize_script('price-calc-main-js', 'timeshareMain', [
                'ajaxUrl'                => admin_url('admin-ajax.php'),
                'security'               => wp_create_nonce('calc-security-nonce'),
                'timesharePriceCalcData' => get_option(TIMESHARE_PRICE_CALC_DATA) ? get_option(
                    TIMESHARE_PRICE_CALC_DATA
                ) : ''
            ]);

            //Enqueue CSS files
            wp_enqueue_style(
                'timeshare-price-calc-css',
                TIMESHARE_ASSETS_URL . '/css/price-calculation/style.css',
                [],
                TIMESHARE_VERSION
            );
            #########End Price Calculation##########
        }

        /**
         * Include required core files used in admin and on the frontend.
         *
         * @return void
         */
        private function includes(): void
        {
            if (defined('TIMESHARE_INC_PATH')) {
                require_once(TIMESHARE_INC_PATH . '/user/custom-fields/timeshare/index.php');
            }
        }

        /**
         * Display an admin error notice when PHP is older the version 7.4. Hook it to the 'admin_notices' action.
         *
         * @return void
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
         *
         * @return void
         */
        private function load_translations(): void
        {
            load_plugin_textdomain('timeshare', false, dirname(TIMESHARE_BASENAME) . '/languages');
            load_plugin_textdomain('timeshare');
        }
    }
}

new Timeshare();