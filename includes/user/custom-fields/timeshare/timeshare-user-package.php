<?php

/**
 * Handle data saving via POST request
 *
 * @return void
 */
function handle_timeshare_user_data(): void
{
    if (isset($_POST['timeshare_user_data']) && ! empty($_POST['timeshare_user'])) {
        $timeshare_user_id = intval($_POST['timeshare_user']);

        $timeshare_user_data = [
            TIMESHARE_PACKAGE_DURATION => intval($_POST[TIMESHARE_PACKAGE_DURATION])
        ];

        // Save user meta data
        update_user_meta($timeshare_user_id, 'timeshare_user_data', json_encode($timeshare_user_data));
    }
}

add_action('admin_init', 'handle_timeshare_user_data');

/**
 * @param array $timeshare_users
 *
 * @return void
 */
function render_timeshare_user_package(array $timeshare_users): void
{
    ob_start();
    ?>
    <div id="timeshare-user-package-section">
        <h3>Timeshare user package</h3>
        <div>
            <form id="timeshare-user-form" method="post" action="">
                <label for="timeshare-user"><b>Select Timeshare User:</b></label>
                <select id="timeshare-user" name="timeshare_user" required>
                    <option value="">Select User</option>
                    <?php
                    foreach ($timeshare_users as $current_user) { ?>
                        <option value="<?= esc_attr($current_user->ID); ?>">
                            <?= esc_html($current_user->user_login);; ?>
                        </option>
                        <?php
                    } ?>

                </select>

                <label for="timeshare-package-duration"><b>Timeshare package duration:</b></label>
                <select id="timeshare-package-duration" name="<?= TIMESHARE_PACKAGE_DURATION; ?>" required>
                    <option value="">Select Duration</option>
                    <option value="7">7</option>
                    <option value="14">14</option>
                    <option value="21">21</option>
                </select>

                <input type="submit" class="btn" name="timeshare_user_data" value="Save">

            </form>
        </div>

        <div>
            <?php
            render_timeshare_user_package_table();
            ?>
        </div>
    </div>
    <?php
    // Get the captured output and clear the buffer
    $output = ob_get_clean();

    // Display the modified output
    echo $output;
}

function render_timeshare_user_package_table()
{
    // Get the current page number from the URL query parameter
    $current_page = isset($_GET['package-page']) ? intval($_GET['package-page']) : 1;

    // Number of rows to display per page
    //todo@@@@ need to change to 50
    $rows_per_page = 1;

    // Call the function to get user data for the current page
    $users_timeshare_data_paginated = get_users_timeshare_data_paginated($current_page, $rows_per_page);
    $users_timeshare_data           = $users_timeshare_data_paginated['users_timeshare_data'];

    ob_start();
    ?>

    <table id="timeshare-user-table">
        <tr>
            <th>Username</th>
            <th>Timeshare package duration</th>
        </tr>
        <?php
        foreach ($users_timeshare_data as $current_user_timeshare_data) {
            $timeshare_user_data = json_decode($current_user_timeshare_data->meta_value, true);
            ?>
            <tr>
                <td>
                    <?= esc_html($current_user_timeshare_data->user_login); ?>
                </td>
                <td>
                    <?= esc_html($timeshare_user_data[TIMESHARE_PACKAGE_DURATION]); ?>
                </td>
            </tr>
            <?php
        } ?>

    </table>

    <div>
        <?php
        get_pagination($users_timeshare_data_paginated, $rows_per_page, $current_page, 2);
        ?>
    </div>

    <?php

    // Get the captured output and clear the buffer
    $output = ob_get_clean();

    // Display the modified output
    echo $output;
}


function get_pagination($users_timeshare_data_paginated, $rows_per_page, $current_page, $pages_to_show)
{
    // Calculate the total number of pages
    $total_pages = ceil($users_timeshare_data_paginated['total_count'] / $rows_per_page);


    // Calculate the range of pagination links to display
    $start_page = max(1, $current_page - floor($pages_to_show / 2));
    $end_page   = min($total_pages, $start_page + $pages_to_show - 1);

    ob_start();
    ?>
    <div class="pagination">
        <?php
        if ($current_page > 1) {
            $prev_page = $current_page - 1;
            $prev_url  = add_query_arg(array('package-page' => $prev_page), $_SERVER['REQUEST_URI']);
            echo '<a href="' . esc_url($prev_url) . '">Prev</a>';
        }

        for ($page = $start_page; $page <= $end_page; $page++) {
            $pagination_url = add_query_arg(array('package-page' => $page), $_SERVER['REQUEST_URI']);
            $class          = $page == $current_page ? 'active' : '';
            echo '<a class="' . $class . '" href="' . esc_url($pagination_url) . '">' . $page . '</a>';
        }

        if ($current_page < $total_pages) {
            $next_page = $current_page + 1;
            $next_url  = add_query_arg(array('package-page' => $next_page), $_SERVER['REQUEST_URI']);
            echo '<a href="' . esc_url($next_url) . '">Next</a>';
        }
        ?>
    </div>
    <?php
    // Get the captured output and clear the buffer
    $output = ob_get_clean();

    // Display the modified output
    echo $output;
}

/**
 * @param int $page
 * @param int $rows_per_page
 *
 * @return array
 */
function get_users_timeshare_data_paginated(int $page = 1, int $rows_per_page): array
{
    global $wpdb;
    $meta_key = 'timeshare_user_data';
    // Calculate the offset based on the current page and rows per page
    $offset = ($page - 1) * $rows_per_page;

    $query = $wpdb->prepare(
        "SELECT u.ID, u.user_login, um.meta_key, um.meta_value
         FROM {$wpdb->users} u
         LEFT JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
         WHERE um.meta_key = %s
         LIMIT %d OFFSET %d",
        $meta_key,
        $rows_per_page,
        $offset
    );

    // Query to retrieve total count of rows
    $count_query = $wpdb->prepare(
        "SELECT COUNT(*) as count
         FROM {$wpdb->users} u
         LEFT JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
         WHERE um.meta_key = %s",
        $meta_key
    );

    return [
        'users_timeshare_data' => $wpdb->get_results($query),
        'total_count'          => isset($wpdb->get_results($count_query)[0]->count) ? $wpdb->get_results(
            $count_query
        )[0]->count : 0
    ];
}