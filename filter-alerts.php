<?php
/**
 * Plugin Name: Filter Alerts
 * Description: Adds site-wide alerts with status taxonomy support.
 * Version: 0.1.0
 * Requires at least: 6.7
 * Requires PHP: 7.2
 * Author: Brent Ransom
 * Text Domain: filter-alerts
 *
 * @package FilterAlerts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FILTER_ALERTS_VERSION', '0.1.0' );
define( 'FILTER_ALERTS_PLUGIN_FILE', __FILE__ );
define( 'FILTER_ALERTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FILTER_ALERTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FILTER_ALERTS_SITE_BANNER_OPTION', 'filter_alerts_site_banner_settings' );

/**
 * Register custom post type and taxonomy.
 */
function filter_alerts_register_content_types() {
	$alert_labels = array(
		'name'                     => _x( 'Site-Wide Alerts', 'post type general name', 'filter-alerts' ),
		'singular_name'            => _x( 'Site-Wide Alert', 'post type singular name', 'filter-alerts' ),
		'menu_name'                => _x( 'Site-Wide Alerts', 'admin menu', 'filter-alerts' ),
		'name_admin_bar'           => _x( 'Site-Wide Alert', 'add new on admin bar', 'filter-alerts' ),
		'add_new'                  => _x( 'Add New', 'site-wide alert', 'filter-alerts' ),
		'add_new_item'             => __( 'Add New Site-Wide Alert', 'filter-alerts' ),
		'new_item'                 => __( 'New Site-Wide Alert', 'filter-alerts' ),
		'edit_item'                => __( 'Edit Site-Wide Alert', 'filter-alerts' ),
		'view_item'                => __( 'View Site-Wide Alert', 'filter-alerts' ),
		'all_items'                => __( 'All Site-Wide Alerts', 'filter-alerts' ),
		'search_items'             => __( 'Search Site-Wide Alerts', 'filter-alerts' ),
		'parent_item_colon'        => __( 'Parent Site-Wide Alerts:', 'filter-alerts' ),
		'not_found'                => __( 'No site-wide alerts found.', 'filter-alerts' ),
		'not_found_in_trash'       => __( 'No site-wide alerts found in Trash.', 'filter-alerts' ),
		'archives'                 => __( 'Site-Wide Alert Archives', 'filter-alerts' ),
		'attributes'               => __( 'Site-Wide Alert Attributes', 'filter-alerts' ),
		'insert_into_item'         => __( 'Insert into site-wide alert', 'filter-alerts' ),
		'uploaded_to_this_item'    => __( 'Uploaded to this site-wide alert', 'filter-alerts' ),
		'filter_items_list'        => __( 'Filter site-wide alerts list', 'filter-alerts' ),
		'items_list_navigation'    => __( 'Site-wide alerts list navigation', 'filter-alerts' ),
		'items_list'               => __( 'Site-wide alerts list', 'filter-alerts' ),
		'item_published'           => __( 'Site-wide alert published.', 'filter-alerts' ),
		'item_published_privately' => __( 'Site-wide alert published privately.', 'filter-alerts' ),
		'item_reverted_to_draft'   => __( 'Site-wide alert reverted to draft.', 'filter-alerts' ),
		'item_trashed'             => __( 'Site-wide alert trashed.', 'filter-alerts' ),
		'item_scheduled'           => __( 'Site-wide alert scheduled.', 'filter-alerts' ),
		'item_updated'             => __( 'Site-wide alert updated.', 'filter-alerts' ),
	);

	register_post_type(
		'site_wide_alert',
		array(
			'labels'             => $alert_labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-warning',
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'supports'           => array( 'title', 'editor', 'revisions' ),
		)
	);

	$status_labels = array(
		'name'                       => _x( 'Statuses', 'taxonomy general name', 'filter-alerts' ),
		'singular_name'              => _x( 'Status', 'taxonomy singular name', 'filter-alerts' ),
		'search_items'               => __( 'Search Statuses', 'filter-alerts' ),
		'popular_items'              => __( 'Popular Statuses', 'filter-alerts' ),
		'all_items'                  => __( 'All Statuses', 'filter-alerts' ),
		'parent_item'                => __( 'Parent Status', 'filter-alerts' ),
		'parent_item_colon'          => __( 'Parent Status:', 'filter-alerts' ),
		'edit_item'                  => __( 'Edit Status', 'filter-alerts' ),
		'view_item'                  => __( 'View Status', 'filter-alerts' ),
		'update_item'                => __( 'Update Status', 'filter-alerts' ),
		'add_new_item'               => __( 'Add New Status', 'filter-alerts' ),
		'new_item_name'              => __( 'New Status Name', 'filter-alerts' ),
		'separate_items_with_commas' => __( 'Separate statuses with commas', 'filter-alerts' ),
		'add_or_remove_items'        => __( 'Add or remove statuses', 'filter-alerts' ),
		'choose_from_most_used'      => __( 'Choose from the most used statuses', 'filter-alerts' ),
		'not_found'                  => __( 'No statuses found.', 'filter-alerts' ),
		'no_terms'                   => __( 'No statuses', 'filter-alerts' ),
		'filter_by_item'             => __( 'Filter by status', 'filter-alerts' ),
		'items_list_navigation'      => __( 'Statuses list navigation', 'filter-alerts' ),
		'items_list'                 => __( 'Statuses list', 'filter-alerts' ),
		'most_used'                  => __( 'Most Used', 'filter-alerts' ),
		'back_to_items'              => __( '&larr; Go to Statuses', 'filter-alerts' ),
		'menu_name'                  => __( 'Status', 'filter-alerts' ),
	);

	register_taxonomy(
		'alert_status',
		array( 'site_wide_alert' ),
		array(
			'labels'            => $status_labels,
			'public'            => false,
			'publicly_queryable' => false,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'filter_alerts_register_content_types' );

/**
 * Add a dedicated block inserter category for Filter Alerts blocks.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function filter_alerts_register_block_category( $categories ) {
	foreach ( $categories as $category ) {
		if ( isset( $category['slug'] ) && 'filter-alerts' === $category['slug'] ) {
			return $categories;
		}
	}

	array_unshift(
		$categories,
		array(
			'slug'  => 'filter-alerts',
			'title' => __( 'Filter Alert Blocks', 'filter-alerts' ),
			'icon'  => 'warning',
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'filter_alerts_register_block_category' );

/**
 * Get default settings for the global active alert banner.
 *
 * @return array
 */
function filter_alerts_get_site_banner_defaults() {
	return array(
		'icon_url'         => '',
		'multiple_label'   => __( '2 or more Active Alerts', 'filter-alerts' ),
		'background_type'  => 'gradient',
		'solid_color'      => '#d4ebff',
		'gradient_start'   => '#eef7ff',
		'gradient_end'     => '#d4ebff',
		'badge_color'      => '#dce6fb',
		'arrow_color'      => '#0075d8',
	);
}

/**
 * Get saved settings for the global active alert banner.
 *
 * @return array
 */
function filter_alerts_get_site_banner_settings() {
	return wp_parse_args(
		get_option( FILTER_ALERTS_SITE_BANNER_OPTION, array() ),
		filter_alerts_get_site_banner_defaults()
	);
}

/**
 * Sanitize global active alert banner settings.
 *
 * @param array $settings Submitted settings.
 * @return array
 */
function filter_alerts_sanitize_site_banner_settings( $settings ) {
	$defaults = filter_alerts_get_site_banner_defaults();
	$settings = is_array( $settings ) ? $settings : array();

	$background_type = isset( $settings['background_type'] ) ? sanitize_key( $settings['background_type'] ) : $defaults['background_type'];
	$background_type = in_array( $background_type, array( 'solid', 'gradient' ), true ) ? $background_type : $defaults['background_type'];

	$solid_color    = isset( $settings['solid_color'] ) ? sanitize_hex_color( $settings['solid_color'] ) : $defaults['solid_color'];
	$gradient_start = isset( $settings['gradient_start'] ) ? sanitize_hex_color( $settings['gradient_start'] ) : $defaults['gradient_start'];
	$gradient_end   = isset( $settings['gradient_end'] ) ? sanitize_hex_color( $settings['gradient_end'] ) : $defaults['gradient_end'];
	$badge_color    = isset( $settings['badge_color'] ) ? sanitize_hex_color( $settings['badge_color'] ) : $defaults['badge_color'];
	$arrow_color    = isset( $settings['arrow_color'] ) ? sanitize_hex_color( $settings['arrow_color'] ) : $defaults['arrow_color'];

	return array(
		'icon_url'         => isset( $settings['icon_url'] ) ? esc_url_raw( $settings['icon_url'] ) : $defaults['icon_url'],
		'multiple_label'   => isset( $settings['multiple_label'] ) && '' !== trim( $settings['multiple_label'] ) ? sanitize_text_field( $settings['multiple_label'] ) : $defaults['multiple_label'],
		'background_type'  => $background_type,
		'solid_color'      => $solid_color ? $solid_color : $defaults['solid_color'],
		'gradient_start'   => $gradient_start ? $gradient_start : $defaults['gradient_start'],
		'gradient_end'     => $gradient_end ? $gradient_end : $defaults['gradient_end'],
		'badge_color'      => $badge_color ? $badge_color : $defaults['badge_color'],
		'arrow_color'      => $arrow_color ? $arrow_color : $defaults['arrow_color'],
	);
}

/**
 * Register global active alert banner settings.
 */
function filter_alerts_register_site_banner_settings() {
	register_setting(
		'filter_alerts_site_banner',
		FILTER_ALERTS_SITE_BANNER_OPTION,
		array(
			'type'              => 'object',
			'sanitize_callback' => 'filter_alerts_sanitize_site_banner_settings',
			'default'           => filter_alerts_get_site_banner_defaults(),
			'show_in_rest'      => array(
				'schema' => array(
					'type'       => 'object',
					'properties' => array(
						'icon_url'        => array(
							'type' => 'string',
						),
						'multiple_label'  => array(
							'type' => 'string',
						),
						'background_type' => array(
							'type' => 'string',
						),
						'solid_color'     => array(
							'type' => 'string',
						),
						'gradient_start'  => array(
							'type' => 'string',
						),
						'gradient_end'    => array(
							'type' => 'string',
						),
						'badge_color'     => array(
							'type' => 'string',
						),
						'arrow_color'     => array(
							'type' => 'string',
						),
					),
				),
			),
		)
	);
}
add_action( 'init', 'filter_alerts_register_site_banner_settings' );

/**
 * Add plugin settings under the Site-Wide Alerts menu.
 */
function filter_alerts_register_settings_page() {
	add_submenu_page(
		'edit.php?post_type=site_wide_alert',
		__( 'Filter Alert Settings', 'filter-alerts' ),
		__( 'Settings', 'filter-alerts' ),
		'manage_options',
		'filter-alerts-settings',
		'filter_alerts_render_settings_page'
	);
}
add_action( 'admin_menu', 'filter_alerts_register_settings_page' );

/**
 * Enqueue Media Library controls for the settings page.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function filter_alerts_enqueue_settings_assets( $hook_suffix ) {
	if ( false === strpos( $hook_suffix, 'filter-alerts-settings' ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'filter-alerts-site-banner-admin',
		FILTER_ALERTS_PLUGIN_URL . 'assets/site-alert-banner-admin.js',
		array(),
		filemtime( FILTER_ALERTS_PLUGIN_DIR . 'assets/site-alert-banner-admin.js' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'filter_alerts_enqueue_settings_assets' );

/**
 * Render the plugin settings page.
 */
function filter_alerts_render_settings_page() {
	$settings = filter_alerts_get_site_banner_settings();
	$defaults = filter_alerts_get_site_banner_defaults();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Filter Alert Settings', 'filter-alerts' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'filter_alerts_site_banner' ); ?>
			<script type="application/json" data-filter-alerts-banner-defaults><?php echo wp_json_encode( $defaults ); ?></script>

			<h2><?php esc_html_e( 'Global Active Alert Banner', 'filter-alerts' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="filter-alerts-site-banner-icon-url"><?php esc_html_e( 'Icon', 'filter-alerts' ); ?></label></th>
					<td>
						<input class="regular-text" id="filter-alerts-site-banner-icon-url" name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[icon_url]" type="url" value="<?php echo esc_attr( $settings['icon_url'] ); ?>" data-filter-alerts-icon-url />
						<button class="button" type="button" data-filter-alerts-upload-icon><?php esc_html_e( 'Choose Image', 'filter-alerts' ); ?></button>
						<button class="button" type="button" data-filter-alerts-remove-icon><?php esc_html_e( 'Remove', 'filter-alerts' ); ?></button>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="filter-alerts-site-banner-multiple-label"><?php esc_html_e( 'Multiple Alerts Text', 'filter-alerts' ); ?></label></th>
					<td>
						<input class="regular-text" id="filter-alerts-site-banner-multiple-label" name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[multiple_label]" type="text" value="<?php echo esc_attr( $settings['multiple_label'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Background Type', 'filter-alerts' ); ?></th>
					<td>
						<select name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[background_type]" data-filter-alerts-background-type>
							<option value="gradient" <?php selected( 'gradient', $settings['background_type'] ); ?>><?php esc_html_e( 'Gradient', 'filter-alerts' ); ?></option>
							<option value="solid" <?php selected( 'solid', $settings['background_type'] ); ?>><?php esc_html_e( 'Solid', 'filter-alerts' ); ?></option>
						</select>
					</td>
				</tr>
				<tr data-filter-alerts-background-solid>
					<th scope="row"><label for="filter-alerts-site-banner-solid-color"><?php esc_html_e( 'Solid Background Color', 'filter-alerts' ); ?></label></th>
					<td>
						<input id="filter-alerts-site-banner-solid-color" name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[solid_color]" type="color" value="<?php echo esc_attr( $settings['solid_color'] ); ?>" />
					</td>
				</tr>
				<tr data-filter-alerts-background-gradient>
					<th scope="row"><label for="filter-alerts-site-banner-gradient-start"><?php esc_html_e( 'Gradient Start Color', 'filter-alerts' ); ?></label></th>
					<td>
						<input id="filter-alerts-site-banner-gradient-start" name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[gradient_start]" type="color" value="<?php echo esc_attr( $settings['gradient_start'] ); ?>" />
					</td>
				</tr>
				<tr data-filter-alerts-background-gradient>
					<th scope="row"><label for="filter-alerts-site-banner-gradient-end"><?php esc_html_e( 'Gradient End Color', 'filter-alerts' ); ?></label></th>
					<td>
						<input id="filter-alerts-site-banner-gradient-end" name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[gradient_end]" type="color" value="<?php echo esc_attr( $settings['gradient_end'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="filter-alerts-site-banner-badge-color"><?php esc_html_e( 'Icon/Text Box Background Color', 'filter-alerts' ); ?></label></th>
					<td>
						<input id="filter-alerts-site-banner-badge-color" name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[badge_color]" type="color" value="<?php echo esc_attr( $settings['badge_color'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="filter-alerts-site-banner-arrow-color"><?php esc_html_e( 'Arrow Color', 'filter-alerts' ); ?></label></th>
					<td>
						<input id="filter-alerts-site-banner-arrow-color" name="<?php echo esc_attr( FILTER_ALERTS_SITE_BANNER_OPTION ); ?>[arrow_color]" type="color" value="<?php echo esc_attr( $settings['arrow_color'] ); ?>" />
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
			<button class="button button-secondary" type="button" data-filter-alerts-restore-defaults><?php esc_html_e( 'Restore Banner Defaults', 'filter-alerts' ); ?></button>
		</form>
	</div>
	<?php
}

/**
 * Render the alert bar block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function filter_alerts_render_alert_bar_block( $attributes ) {
	$background_type = isset( $attributes['backgroundType'] ) ? $attributes['backgroundType'] : 'gradient';
	$solid_color     = isset( $attributes['solidColor'] ) ? sanitize_hex_color( $attributes['solidColor'] ) : '#6f8cc3';
	$gradient_start  = isset( $attributes['gradientStart'] ) ? sanitize_hex_color( $attributes['gradientStart'] ) : '#6f8cc3';
	$gradient_end    = isset( $attributes['gradientEnd'] ) ? sanitize_hex_color( $attributes['gradientEnd'] ) : '#9fb5df';
	$text_color      = isset( $attributes['textColor'] ) ? sanitize_hex_color( $attributes['textColor'] ) : '#ffffff';
	$font_size       = isset( $attributes['fontSize'] ) ? absint( $attributes['fontSize'] ) : 13;
	$toggle_background_type = isset( $attributes['toggleShowBackgroundType'] ) ? $attributes['toggleShowBackgroundType'] : 'solid';
	$toggle_solid_color     = isset( $attributes['toggleShowSolidColor'] ) ? sanitize_hex_color( $attributes['toggleShowSolidColor'] ) : '#7b7b7b';
	$toggle_gradient_start  = isset( $attributes['toggleShowGradientStart'] ) ? sanitize_hex_color( $attributes['toggleShowGradientStart'] ) : '#7b7b7b';
	$toggle_gradient_end    = isset( $attributes['toggleShowGradientEnd'] ) ? sanitize_hex_color( $attributes['toggleShowGradientEnd'] ) : '#5f5f5f';
	$toggle_hide_background_type = isset( $attributes['toggleHideBackgroundType'] ) ? $attributes['toggleHideBackgroundType'] : 'solid';
	$toggle_hide_solid_color     = isset( $attributes['toggleHideSolidColor'] ) ? sanitize_hex_color( $attributes['toggleHideSolidColor'] ) : '#7b7b7b';
	$toggle_hide_gradient_start  = isset( $attributes['toggleHideGradientStart'] ) ? sanitize_hex_color( $attributes['toggleHideGradientStart'] ) : '#7b7b7b';
	$toggle_hide_gradient_end    = isset( $attributes['toggleHideGradientEnd'] ) ? sanitize_hex_color( $attributes['toggleHideGradientEnd'] ) : '#5f5f5f';
	$inactive_background_type = isset( $attributes['inactiveBackgroundType'] ) ? $attributes['inactiveBackgroundType'] : 'solid';
	$inactive_solid_color     = isset( $attributes['inactiveSolidColor'] ) ? sanitize_hex_color( $attributes['inactiveSolidColor'] ) : '#cccccc';
	$inactive_gradient_start  = isset( $attributes['inactiveGradientStart'] ) ? sanitize_hex_color( $attributes['inactiveGradientStart'] ) : '#000000';
	$inactive_gradient_end    = isset( $attributes['inactiveGradientEnd'] ) ? sanitize_hex_color( $attributes['inactiveGradientEnd'] ) : '#333333';

	$solid_color    = $solid_color ? $solid_color : '#6f8cc3';
	$gradient_start = $gradient_start ? $gradient_start : '#6f8cc3';
	$gradient_end   = $gradient_end ? $gradient_end : '#9fb5df';
	$text_color     = $text_color ? $text_color : '#ffffff';
	$font_size      = $font_size ? min( max( $font_size, 10 ), 32 ) : 13;
	$toggle_solid_color    = $toggle_solid_color ? $toggle_solid_color : '#7b7b7b';
	$toggle_gradient_start = $toggle_gradient_start ? $toggle_gradient_start : '#7b7b7b';
	$toggle_gradient_end   = $toggle_gradient_end ? $toggle_gradient_end : '#5f5f5f';
	$toggle_hide_solid_color    = $toggle_hide_solid_color ? $toggle_hide_solid_color : '#7b7b7b';
	$toggle_hide_gradient_start = $toggle_hide_gradient_start ? $toggle_hide_gradient_start : '#7b7b7b';
	$toggle_hide_gradient_end   = $toggle_hide_gradient_end ? $toggle_hide_gradient_end : '#5f5f5f';
	$inactive_solid_color    = $inactive_solid_color ? $inactive_solid_color : '#cccccc';
	$inactive_gradient_start = $inactive_gradient_start ? $inactive_gradient_start : '#000000';
	$inactive_gradient_end   = $inactive_gradient_end ? $inactive_gradient_end : '#333333';

	$styles = array(
		'color: ' . $text_color,
		'font-size: ' . $font_size . 'px',
	);

	if ( 'solid' === $background_type ) {
		$styles[] = 'background: ' . $solid_color;
	} else {
		$styles[] = 'background: linear-gradient(90deg, ' . $gradient_start . ' 0%, ' . $gradient_end . ' 100%)';
	}

	$toggle_show_background = 'solid' === $toggle_background_type ? $toggle_solid_color : 'linear-gradient(90deg, ' . $toggle_gradient_start . ' 0%, ' . $toggle_gradient_end . ' 100%)';
	$toggle_hide_background = 'solid' === $toggle_hide_background_type ? $toggle_hide_solid_color : 'linear-gradient(90deg, ' . $toggle_hide_gradient_start . ' 0%, ' . $toggle_hide_gradient_end . ' 100%)';
	$inactive_background    = 'solid' === $inactive_background_type ? $inactive_solid_color : 'linear-gradient(90deg, ' . $inactive_gradient_start . ' 0%, ' . $inactive_gradient_end . ' 100%)';

	$wrapper_styles = array(
		'--filter-alerts-toggle-show-background: ' . $toggle_show_background,
		'--filter-alerts-toggle-hide-background: ' . $toggle_hide_background,
		'--filter-alerts-inactive-background: ' . $inactive_background,
	);

	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => 'filter-alerts-alert-table',
			'style' => implode( '; ', $wrapper_styles ),
		)
	);

	$alerts = new WP_Query(
		array(
			'post_type'      => 'site_wide_alert',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);

	ob_start();
	?>
	<div <?php echo $wrapper_attributes; ?>>
		<div class="filter-alerts-alert-table__toolbar">
			<div class="filter-alerts-alert-toggle">
				<span class="filter-alerts-alert-toggle__label"><?php esc_html_e( 'Inactive Alerts', 'filter-alerts' ); ?></span>
				<button class="filter-alerts-alert-toggle__switch" type="button" aria-pressed="false" data-filter-alerts-inactive-toggle>
					<span class="filter-alerts-alert-toggle__thumb" aria-hidden="true"></span>
					<span class="filter-alerts-alert-toggle__text" data-filter-alerts-inactive-toggle-text><?php esc_html_e( 'Show', 'filter-alerts' ); ?></span>
				</button>
			</div>
		</div>

		<div class="filter-alerts-alert-bar" style="<?php echo esc_attr( implode( '; ', $styles ) ); ?>">
			<div class="filter-alerts-alert-bar__cell filter-alerts-alert-bar__cell--alert">
				<span class="filter-alerts-alert-bar__icon" aria-hidden="true"><?php echo filter_alerts_get_alert_icon(); ?></span>
				<span><?php esc_html_e( 'Alert', 'filter-alerts' ); ?></span>
			</div>
			<div class="filter-alerts-alert-bar__cell filter-alerts-alert-bar__cell--details"><?php esc_html_e( 'Details', 'filter-alerts' ); ?></div>
			<div class="filter-alerts-alert-bar__cell filter-alerts-alert-bar__cell--date"><?php esc_html_e( 'Date', 'filter-alerts' ); ?></div>
		</div>

		<div class="filter-alerts-alert-table__body">
			<?php if ( $alerts->have_posts() ) : ?>
				<?php
				$rendered_alert_count = 0;

				while ( $alerts->have_posts() ) :
					$alerts->the_post();
					$status_flags = filter_alerts_get_alert_status_flags( get_the_ID() );
					$is_inactive  = $status_flags['inactive'];
					$is_archived  = $status_flags['archived'];

					if ( $is_archived ) {
						continue;
					}

					$row_classes = 'filter-alerts-alert-row';

					if ( $is_inactive ) {
						$row_classes .= ' filter-alerts-alert-row--inactive';
					}

					++$rendered_alert_count;
					?>
					<div class="<?php echo esc_attr( $row_classes ); ?>"<?php echo $is_inactive ? ' data-filter-alerts-inactive-row' : ''; ?>>
						<div class="filter-alerts-alert-row__cell filter-alerts-alert-row__cell--alert"><?php echo esc_html( get_the_title() ); ?></div>
						<div class="filter-alerts-alert-row__cell filter-alerts-alert-row__cell--details"><?php echo wp_kses_post( apply_filters( 'the_content', get_the_content() ) ); ?></div>
						<div class="filter-alerts-alert-row__cell filter-alerts-alert-row__cell--date"><?php echo esc_html( get_the_date( 'm/d/Y' ) ); ?></div>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
				<?php if ( 0 === $rendered_alert_count ) : ?>
					<div class="filter-alerts-alert-row filter-alerts-alert-row--empty">
						<div class="filter-alerts-alert-row__cell filter-alerts-alert-row__cell--empty"><?php esc_html_e( 'No site-wide alerts found.', 'filter-alerts' ); ?></div>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="filter-alerts-alert-row filter-alerts-alert-row--empty">
					<div class="filter-alerts-alert-row__cell filter-alerts-alert-row__cell--empty"><?php esc_html_e( 'No site-wide alerts found.', 'filter-alerts' ); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php

	return ob_get_clean();
}

/**
 * Get the alert icon SVG markup.
 *
 * @return string
 */
function filter_alerts_get_alert_icon() {
	return '<svg viewBox="0 0 24 24" focusable="false" role="img" aria-label=""><path d="M12 3.75 2.75 20.25h18.5L12 3.75Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 8.75v5.25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="17" r="1.15" fill="currentColor"/></svg>';
}

/**
 * Determine whether an alert post has inactive or archived status terms.
 *
 * @param int $post_id Alert post ID.
 * @return array
 */
function filter_alerts_get_alert_status_flags( $post_id ) {
	$status_terms = get_the_terms( $post_id, 'alert_status' );
	$flags        = array(
		'inactive' => false,
		'archived' => false,
	);

	if ( is_wp_error( $status_terms ) || empty( $status_terms ) ) {
		return $flags;
	}

	foreach ( $status_terms as $status_term ) {
		$status_slug = sanitize_title( $status_term->slug );
		$status_name = sanitize_title( $status_term->name );

		if ( in_array( $status_slug, array( 'archive', 'archived' ), true ) || in_array( $status_name, array( 'archive', 'archived' ), true ) ) {
			$flags['archived'] = true;
			break;
		}

		if ( 'inactive' === $status_slug || 'inactive' === $status_name ) {
			$flags['inactive'] = true;
		}
	}

	return $flags;
}

/**
 * Get published active alert IDs.
 *
 * Active alerts are published alerts that are not Inactive, Archive, or Archived.
 *
 * @return array
 */
function filter_alerts_get_active_alert_ids() {
	static $active_alert_ids = null;

	if ( null !== $active_alert_ids ) {
		return $active_alert_ids;
	}

	$active_alert_ids = array();
	$alert_ids        = get_posts(
		array(
			'post_type'      => 'site_wide_alert',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);

	foreach ( $alert_ids as $alert_id ) {
		$status_flags = filter_alerts_get_alert_status_flags( $alert_id );

		if ( ! $status_flags['inactive'] && ! $status_flags['archived'] ) {
			$active_alert_ids[] = $alert_id;
		}
	}

	return $active_alert_ids;
}

/**
 * Count published active alerts.
 *
 * @return int
 */
function filter_alerts_get_active_alert_count() {
	return count( filter_alerts_get_active_alert_ids() );
}

/**
 * Enqueue front-end assets for the site-wide active alert banner.
 */
function filter_alerts_enqueue_site_banner_assets() {
	if ( is_admin() || 0 === filter_alerts_get_active_alert_count() ) {
		return;
	}

	wp_enqueue_style(
		'filter-alerts-site-banner',
		FILTER_ALERTS_PLUGIN_URL . 'assets/site-alert-banner.css',
		array(),
		filemtime( FILTER_ALERTS_PLUGIN_DIR . 'assets/site-alert-banner.css' )
	);

	wp_enqueue_script(
		'filter-alerts-site-banner',
		FILTER_ALERTS_PLUGIN_URL . 'assets/site-alert-banner.js',
		array(),
		filemtime( FILTER_ALERTS_PLUGIN_DIR . 'assets/site-alert-banner.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'filter_alerts_enqueue_site_banner_assets' );

/**
 * Render the site-wide active alert banner at the top of the page.
 */
function filter_alerts_render_site_banner() {
	$active_alert_ids   = filter_alerts_get_active_alert_ids();
	$active_alert_count = count( $active_alert_ids );

	if ( is_admin() || 0 === $active_alert_count ) {
		return;
	}

	$settings   = filter_alerts_get_site_banner_settings();
	$background = 'solid' === $settings['background_type']
		? $settings['solid_color']
		: 'linear-gradient(90deg, ' . $settings['gradient_start'] . ' 0%, ' . $settings['gradient_end'] . ' 100%)';
	$styles     = array(
		'--filter-alerts-site-banner-background: ' . $background,
		'--filter-alerts-site-banner-badge-background: ' . $settings['badge_color'],
		'--filter-alerts-site-banner-arrow-color: ' . $settings['arrow_color'],
	);
	$default_settings = filter_alerts_get_site_banner_defaults();
	$label            = $settings['multiple_label'];

	if ( $default_settings['multiple_label'] === $settings['multiple_label'] ) {
		$label = 1 === $active_alert_count
			? __( '1 Active Alert', 'filter-alerts' )
			: $settings['multiple_label'];
	}

	if ( 1 === $active_alert_count ) {
		$label = get_the_title( $active_alert_ids[0] );
	}

	$dropdown_alert_ids = array_slice( $active_alert_ids, 0, 4 );
	?>
	<div class="filter-alerts-site-banner-wrap" style="<?php echo esc_attr( implode( '; ', $styles ) ); ?>" data-filter-alerts-site-banner>
		<div class="filter-alerts-site-banner" role="region" aria-label="<?php esc_attr_e( 'Active alerts', 'filter-alerts' ); ?>">
			<div class="filter-alerts-site-banner__badge">
				<?php if ( '' !== $settings['icon_url'] ) : ?>
					<img class="filter-alerts-site-banner__icon" src="<?php echo esc_url( $settings['icon_url'] ); ?>" alt="" aria-hidden="true" />
				<?php else : ?>
					<span class="filter-alerts-site-banner__icon" aria-hidden="true"><?php echo filter_alerts_get_alert_icon(); ?></span>
				<?php endif; ?>
				<span><?php echo esc_html( $label ); ?></span>
			</div>
			<button class="filter-alerts-site-banner__toggle" type="button" aria-expanded="false" data-filter-alerts-site-banner-toggle>
				<span class="filter-alerts-site-banner__chevron" aria-hidden="true"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle active alerts', 'filter-alerts' ); ?></span>
			</button>
		</div>
		<div class="filter-alerts-site-banner__panel" data-filter-alerts-site-banner-panel>
			<div class="filter-alerts-site-banner__alerts" style="<?php echo esc_attr( '--filter-alerts-site-banner-alert-count: ' . count( $dropdown_alert_ids ) ); ?>">
				<?php foreach ( $dropdown_alert_ids as $alert_id ) : ?>
					<article class="filter-alerts-site-banner__alert-card">
						<h2 class="filter-alerts-site-banner__alert-title"><?php echo esc_html( get_the_title( $alert_id ) ); ?></h2>
						<div class="filter-alerts-site-banner__alert-description">
							<?php echo wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $alert_id ) ) ); ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_body_open', 'filter_alerts_render_site_banner', 5 );

/**
 * Register plugin blocks.
 */
function filter_alerts_register_blocks() {
	$block_dir = FILTER_ALERTS_PLUGIN_DIR . 'blocks/alert-bar';

	wp_register_script(
		'filter-alerts-alert-bar-editor',
		FILTER_ALERTS_PLUGIN_URL . 'blocks/alert-bar/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-api-fetch' ),
		filemtime( $block_dir . '/index.js' ),
		true
	);

	wp_register_style(
		'filter-alerts-alert-bar',
		FILTER_ALERTS_PLUGIN_URL . 'blocks/alert-bar/style.css',
		array(),
		filemtime( $block_dir . '/style.css' )
	);

	wp_register_script(
		'filter-alerts-alert-bar-view',
		FILTER_ALERTS_PLUGIN_URL . 'blocks/alert-bar/view.js',
		array(),
		filemtime( $block_dir . '/view.js' ),
		true
	);

	register_block_type(
		$block_dir,
		array(
			'render_callback' => 'filter_alerts_render_alert_bar_block',
		)
	);
}
add_action( 'init', 'filter_alerts_register_blocks' );

/**
 * Flush rewrite rules after registering plugin content types.
 */
function filter_alerts_activate() {
	filter_alerts_register_content_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'filter_alerts_activate' );

/**
 * Clean up rewrite rules on deactivation.
 */
function filter_alerts_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'filter_alerts_deactivate' );
