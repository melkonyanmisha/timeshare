<?php
/**
 * Timeshare functions and definitions
 *
 * @package Timeshare
 * @since   1.0.0
 * @version 1.0.0
 */

// Prevent direct script access.
if ( ! defined('ABSPATH')) {
    die('No direct script access allowed');
}

require_once 'timeshare-user-package.php';

/**
 * @return void
 */
function handle_timeshare_user_data(): void
{
    if (isset($_POST['timeshare_user_data']) && ! empty($_POST['timeshare_user'])) {
        $timeshare_user_id = intval($_POST['timeshare_user']);

        $timeshare_user_data = [
            'timeshare_package_duration' => intval($_POST['timeshare_package_duration'])
        ];

        // Save user meta data
        update_user_meta($timeshare_user_id, 'timeshare_user_data', json_encode($timeshare_user_data));
    }
}


add_action('admin_init', 'handle_timeshare_user_data');

/**
 * @return void
 */
function custom_users_menu_page(): void
{
    add_users_page(
        'Timeshare Users',
        'Timeshare Users',
        'read',
        'timeshare-users-page',
        'timeshare_users_page_content'
    );
}

add_action('admin_menu', 'custom_users_menu_page');

/**
 * @return void
 */
function timeshare_users_page_content(): void
{
    // Get users with the 'timeshare_user' role
    $timeshare_users = get_users(array(
        'role' => 'timeshare_user'
    ));


    // Start output buffering
    ob_start();
    ?>
    <div class="wrap">
        <h2>Timeshare Users Configuration Page</h2>

        <div id="timeshare-user-page">
            <?php
            render_timeshare_user_package($timeshare_users);

            ?>

        </div>

    </div>

    <?php
    // Get the captured output and clear the buffer
    $output = ob_get_clean();

    // Display the modified output
    echo $output;
}



