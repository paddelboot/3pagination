<?php
if ( !class_exists( 'threepagination_settings' ) ) {

	class threepagination_settings extends threepagination {

		protected $textdomain;

		public function __construct() {

			// Management page submenu
			add_filter( 'admin_menu', array( $this, 'add_submenu' ) );

			// Settings API
			add_filter( 'admin_init', array( $this, 'settings_api_init' ) );
		}

		/**
		 * Add management page submenu
		 * 
		 * @since 1.1 
		 */
		public function add_submenu() {

			add_management_page( __( '3pagination Configuration', $this->textdomain ), '3pagination', 'activate_plugins', '3pagination', array( $this, 'draw_settings_page' ) );
		}

		public function draw_settings_page() {
			?>
			<div class="wrap">
				<?php screen_icon( 'options-general' ); ?>
				<h2><?php _e( '3pagination Settings', $this->textdomain ); ?></h2>

				<form action="options.php" method="post">
					<?php 
					settings_fields( '3pagination_settings' );
					do_settings_sections( '3pagination' );
					submit_button( __( 'Save Changes', $this->textdomain ), 'button-primary', 'submit', TRUE );
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Init the settings API 
		 * 
		 * @since 0.1
		 */
		public function settings_api_init() {

			// Register our setting so that $_POST handling is done for us and
			// our callback function just has to echo the <input>
			register_setting( '3pagination_settings', '3pagination_settings', array( $this, 'threepagination_validate' ) );

			// Add the section to reading settings so we can add our
			// fields to it
			add_settings_section( '3pagination_labels', __( 'Labels', $this->textdomain ), array( $this, 'section_labels' ), '3pagination' );
			add_settings_section( '3pagination_placement', __( 'Placement', $this->textdomain ), array( $this, 'section_placement' ), '3pagination' );

			// Add the field with the names and function to use for our new
			// settings, put it in our new section
			add_settings_field( 'threepagination_labels_show', __( 'Show labels', $this->textdomain ), array( $this, 'labels_show' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_previous', __( 'Previous page', $this->textdomain ), array( $this, 'labels_previous' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_next', __( 'Next page', $this->textdomain ), array( $this, 'labels_next' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_first', __( 'First page', $this->textdomain ), array( $this, 'labels_first' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_last', __( 'Last page', $this->textdomain ), array( $this, 'labels_last' ), '3pagination', '3pagination_labels' );
		
			add_settings_field( 'threepagination_placement_header', __( 'Inject below header', $this->textdomain ), array( $this, 'placement_header' ), '3pagination', '3pagination_placement' );
			add_settings_field( 'threepagination_placement_footer', __( 'Inject above footer', $this->textdomain ), array( $this, 'placement_footer' ), '3pagination', '3pagination_placement' );		
			
			add_settings_field( 'threepagination_placement_prepend', __( 'Prepend to', $this->textdomain ), array( $this, 'placement_prepend' ), '3pagination', '3pagination_placement' );		
			add_settings_field( 'threepagination_placement_append', __( 'Append to', $this->textdomain ), array( $this, 'placement_append' ), '3pagination', '3pagination_placement' );		

		}

		/**
		 * Head of "labels" section
		 * 
		 * @since 0.1 
		 */
		public function section_labels() {
			?>
			<span class="description"><?php _e( 'Define the navigation labels characters' ); ?></span>
			<?php
		}
		
		/**
		 * Head of "placement" section
		 * 
		 * @since 0.1 
		 */
		public function section_placement() {
			?>
			<span class="description"><?php _e( '<b>Inject:</b> You can "inject" the navigation bar below the header or above the footer div. Your theme needs to have an according HTML structure (which most WordPress themes do).' ); ?></span>
			<br />
			<span class="description"><?php _e( '<b>Append/Prepend:</b> You can specifiy a container into which the pagination will be prepended/appended. Write it jQuery-style, i.e. #mycontainer' ); ?></span>
			<?php
		}

		public function labels_show() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="checkbox" name="3pagination_settings[labels_show]" <?php checked( $this->init_var( $settings, 'labels_show', 'on' ), 'on' ); ?> />
			<?php
		}

		public function labels_previous() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_previous]" value="<?php echo $this->init_var( $settings, 'labels_previous', '&lsaquo;', TRUE ); ?>" />
			<?php
		}

		public function labels_next() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_next]" value="<?php echo $this->init_var( $settings, 'labels_next', '&rsaquo;', TRUE ); ?>" />
			<?php
		}

		public function labels_first() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_first]" value="<?php echo $this->init_var( $settings, 'labels_first', '&laquo;', TRUE ); ?>" />
			<?php
		}

		public function labels_last() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_last]" value="<?php echo $this->init_var( $settings, 'labels_last', '&raquo;', TRUE ); ?>" />
			<?php
		}
		
		public function placement_header() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="checkbox" name="3pagination_settings[placement_header_index]" <?php checked( $this->init_var( $settings, 'placement_header_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_header_index]"><?php _e( 'Index pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_header_archive]" <?php checked( $this->init_var( $settings, 'placement_header_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_header_archive]"><?php _e( 'Archive pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_header_category]" <?php checked( $this->init_var( $settings, 'placement_header_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_header_category]"><?php _e( 'Category pages' ); ?></label>			
			<?php
		}
		
		public function placement_footer() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="checkbox" name="3pagination_settings[placement_footer_index]" <?php checked( $this->init_var( $settings, 'placement_footer_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_footer_index]"><?php _e( 'Index pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_footer_archive]" <?php checked( $this->init_var( $settings, 'placement_footer_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_footer_archive]"><?php _e( 'Archive pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_footer_category]" <?php checked( $this->init_var( $settings, 'placement_footer_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_footer_category]"><?php _e( 'Category pages' ); ?></label>			
			<?php
		}
		
		public function placement_prepend() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[placement_prepend_id]" value="<?php echo $this->init_var( $settings, 'placement_prepend_id', FALSE ); ?>" />			
			<label for="3pagination_settings[placement_prepend_id]"><?php _e( 'in' ); ?></label>			
			<input type="checkbox" name="3pagination_settings[placement_prepend_index]" <?php checked( $this->init_var( $settings, 'placement_prepend_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_prepend_index]"><?php _e( 'Index pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_prepend_archive]" <?php checked( $this->init_var( $settings, 'placement_prepend_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_prepend_archive]"><?php _e( 'Archive pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_prepend_category]" <?php checked( $this->init_var( $settings, 'placement_prepend_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_prepend_category]"><?php _e( 'Category pages' ); ?></label>			
			<?php
		}
		
		public function placement_append() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[placement_append_id]" value="<?php echo $this->init_var( $settings, 'placement_append_id', FALSE ); ?>" />			
			<label for="3pagination_settings[placement_append_id]"><?php _e( 'in' ); ?></label>						
			<input type="checkbox" name="3pagination_settings[placement_append_index]" <?php checked( $this->init_var( $settings, 'placement_append_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_append_index]"><?php _e( 'Index pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_append_archive]" <?php checked( $this->init_var( $settings, 'placement_append_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_append_archive]"><?php _e( 'Archive pages' ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_append_category]" <?php checked( $this->init_var( $settings, 'placement_append_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_append_category]"><?php _e( 'Category pages' ); ?></label>			
			<?php
		}
		

		/**
		 * @TODO add_settings_error()
		 * @param type $data
		 * @return type 
		 */
		public function threepagination_validate( $data ) {

			$settings = get_option( '3pagination_settings' );

			$settings[ 'labels_show' ] = esc_attr( $data[ 'labels_show' ] );
			$settings[ 'labels_previous' ] = esc_attr( $data[ 'labels_previous' ] );
			$settings[ 'labels_next' ] = esc_attr( $data[ 'labels_next' ] );
			$settings[ 'labels_first' ] = esc_attr( $data[ 'labels_first' ] );
			$settings[ 'labels_last' ] = esc_attr( $data[ 'labels_last' ] );
			
			$settings[ 'placement_header_index' ] = esc_attr( $data[ 'placement_header_index' ] );
			$settings[ 'placement_header_archive' ] = esc_attr( $data[ 'placement_header_archive' ] );
			$settings[ 'placement_header_category' ] = esc_attr( $data[ 'placement_header_category' ] );
			
			$settings[ 'placement_footer_index' ] = esc_attr( $data[ 'placement_footer_index' ] );
			$settings[ 'placement_footer_archive' ] = esc_attr( $data[ 'placement_footer_archive' ] );
			$settings[ 'placement_footer_category' ] = esc_attr( $data[ 'placement_footer_category' ] );
			
			$settings[ 'placement_prepend_id' ] = esc_attr( $data[ 'placement_prepend_id' ] );
			$settings[ 'placement_prepend_index' ] = esc_attr( $data[ 'placement_prepend_index' ] );
			$settings[ 'placement_prepend_archive' ] = esc_attr( $data[ 'placement_prepend_archive' ] );
			$settings[ 'placement_prepend_category' ] = esc_attr( $data[ 'placement_prepend_category' ] );
			
			$settings[ 'placement_append_id' ] = esc_attr( $data[ 'placement_append_id' ] );
			$settings[ 'placement_append_index' ] = esc_attr( $data[ 'placement_append_index' ] );
			$settings[ 'placement_append_archive' ] = esc_attr( $data[ 'placement_append_archive' ] );
			$settings[ 'placement_append_category' ] = esc_attr( $data[ 'placement_append_category' ] );

			return $settings;
		}

		/**
		 * Unregister and delete settings; clean database
		 *
		 * @uses unregister_setting, delete_option
		 * @access public
		 * @since 0.0.1
		 * @return void
		 */
		public function unregister_settings() {

			unregister_setting( $this->option_string . '_group', $this->option_string );
			delete_option( $this->option_string );
		}

	}

	new threepagination_settings();
}
?>
