<?php
/**
 * Timeshare functions and definitions
 *
 * @package Timeshare
 * @since   1.0.0
 * @version 1.0.0
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


/**
 * Register taxonomies
 *
 * @return void
 */
function timeshare_register_user_group_taxonomy(): void {

	register_taxonomy(
		USER_GROUP_TAXONOMY, //taxonomy name
		'user', //object for which the taxonomy is created
		[ //taxonomy details
			'public' => true,
			'labels' => [
				'name'          => __( 'User Groups', 'timeshare' ),
				'singular_name' => __( 'User Group', 'timeshare' ),
				'menu_name'     => __( 'User Groups', 'timeshare' ),
				'search_items'  => __( 'Search User Group', 'timeshare' ),
				'popular_items' => __( 'Popular User Groups', 'timeshare' ),
				'all_items'     => __( 'All User Groups', 'timeshare' ),
				'edit_item'     => __( 'Edit User Group', 'timeshare' ),
				'update_item'   => __( 'Update User Group', 'timeshare' ),
				'add_new_item'  => __( 'Add New User Group', 'timeshare' ),
				'new_item_name' => __( 'New User Group Name', 'timeshare' ),
			],
//			'update_count_callback' => function () {
//				return; //important
//			}
		]
	);
}

add_action( 'init', 'timeshare_register_user_group_taxonomy' );


/**
 * To display Dashboard=>Users=>User Groups
 *
 * @return void
 */
function timeshare_add_user_groups_admin_page(): void {
	$taxonomy = get_taxonomy( USER_GROUP_TAXONOMY );
	add_users_page(
		esc_attr( $taxonomy->labels->menu_name ),//page title
		esc_attr( $taxonomy->labels->menu_name ),//menu title
		$taxonomy->cap->manage_terms,//capability
		'edit-tags.php?taxonomy=' . $taxonomy->name, //menu slug
	);
}

add_action( 'admin_menu', 'timeshare_add_user_groups_admin_page' );

/**
 * To avoid show in POSTS section
 *
 * @param string|null $submenu_file
 *
 * @return string|null
 */
function nopio_set_user_group_submenu_active( ?string $submenu_file ): ?string {
	global $parent_file;

	if ( 'edit-tags.php?taxonomy=' . USER_GROUP_TAXONOMY == $submenu_file ) {
		$parent_file = 'users.php';
	}

	return $submenu_file;
}

add_filter( 'submenu_file', 'nopio_set_user_group_submenu_active' );


/**
 * Draw "select-option"
 *
 * @param string $name
 * @param string $value
 * @param array $options
 *
 * @return void
 */
function user_group_custom_form_select( string $name, string $value, array $options ): void {
	if ( empty( $options ) ) {
		$options = array( '' => '---choose---' );
	}

	echo "<select name='{$name}'>";

	foreach ( $options as $options_value => $options_label ) {
		if ( $options_value == $value ) {
			$selected = " selected='selected'";
		} else {
			$selected = '';
		}

		echo "<option value='{$options_value}'{$selected}>{$options_label}</option>";
	}

	echo "</select>";
}

/**
 * The field on the editing screens.
 *
 * @param WP_User $user
 *
 * @return void
 */
function timeshare_admin_user_profile_group_select( WP_User $user ): void {
	if ( ! current_user_can( 'administrator' ) ) {
		return;
	}

	ob_start();
	?>
    <table class="form-table">
        <tr>
            <th>
                <label for="<?php echo USER_GROUP_TAXONOMY_META_KEY ?>">User Group</label>
            </th>
            <td>
				<?php
				$user_group_terms = get_terms( [
					'taxonomy'   => USER_GROUP_TAXONOMY,
					'hide_empty' => 0
				] );

				$select_options = [];

				foreach ( $user_group_terms as $term ) {
					$select_options[ $term->term_id ] = $term->name;
				}

				$meta_values = get_user_meta( $user->ID, USER_GROUP_TAXONOMY_META_KEY, true );

				user_group_custom_form_select(
					USER_GROUP_TAXONOMY_META_KEY,
					$meta_values,
					$select_options,
				);
				?>
            </td>
        </tr>
    </table>
	<?php
	ob_end_flush();
}

// Add the field to user's own profile editing screen.
add_action( 'show_user_profile', 'timeshare_admin_user_profile_group_select' );
// Add the field to user profile editing screen.
add_action( 'edit_user_profile', 'timeshare_admin_user_profile_group_select' );

/**
 * Save data
 *
 * @param int $user_id
 *
 * @return void
 */
function timeshare_admin_save_user_group( int $user_id ): void {
	if ( ! current_user_can( 'administrator' ) ) {
		return;
	}

	$new_group_id = $_POST[ USER_GROUP_TAXONOMY_META_KEY ];
	update_user_meta( $user_id, USER_GROUP_TAXONOMY_META_KEY, $new_group_id );
	$terms = (array) get_term( $new_group_id, USER_GROUP_TAXONOMY );

	//To change "Count" of current user group on "User Groups" page
	wp_set_object_terms( $user_id, $terms['slug'], USER_GROUP_TAXONOMY, false );
}

add_action( 'personal_options_update', 'timeshare_admin_save_user_group' );
add_action( 'edit_user_profile_update', 'timeshare_admin_save_user_group' );

/**
 * Customization of "Edit User Group" page
 *
 * @param WP_Term $term
 *
 * @return void
 */
function user_group_taxonomy_edit_form_fields( WP_Term $term ): void {
	$user_ids = get_objects_in_term( $term->term_id, 'user_group' );

	if ( ! empty( $user_ids ) ) {
		$users = get_users( [ 'include' => $user_ids, 'fields' => [ 'ID', 'user_login' ] ] );
	}

	ob_start();
	?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="my_field"><?php _e( 'Users list', 'timeshare' ); ?></label>
        </th>
        <td>
			<?php
			if ( ! empty( $users ) ) {
				foreach ( $users as $user ) { ?>
                    <p>
                        <a href="<?= esc_attr( get_edit_user_link( $user->ID ) ); ?>">
							<?= esc_html( $user->user_login ); ?>
                        </a>
                    </p>
				<?php }
			} ?>
        </td>
    </tr>
	<?php
	ob_end_flush();
}

add_action( USER_GROUP_TAXONOMY . '_edit_form_fields', 'user_group_taxonomy_edit_form_fields' );