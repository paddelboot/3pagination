<?php
/**
 * 3pagination settings page class
 * 
 * @version 1.0
 * @author Michael Schröder
 * 
 * @TODO: 
 * - Set placement template globally, not for each option
 * - Contextual help
 * 
 *  
 */
if ( !class_exists( 'threepagination_settings' ) ) {

	class threepagination_settings extends threepagination {

		/**
		 * Plugin's textdomain string
		 * 
		 * @var string $textdomain
		 */
		protected $textdomain;

		/**
		 * Set filters
		 * 
		 * @since 0.1 
		 */
		public function __construct() {

			// Management page submenu
			add_filter( 'admin_menu', array( $this, 'add_submenu' ) );

			// Settings API
			add_filter( 'admin_init', array( $this, 'settings_api_init' ) );

			// Contextual help
			add_filter( 'contextual_help', array( $this, 'contextual_help'), 10, 3 );
		}

		/**
		 * Add management page submenu
		 * 
		 * @since 0.1 
		 */
		public function add_submenu() {

			global $threepagination_screen;

			$threepagination_screen = add_options_page( __( '3pagination Configuration', parent::$_object->textdomain ), '3pagination', 'activate_plugins', '3pagination', array( $this, 'draw_settings_page' ) );
		}

		public function contextual_help( $content, $screen_id, $screen ) {

			global $threepagination_screen;
			
			if ( $screen_id == $threepagination_screen ) {

				$content = '<p><strong>' . __( 'Preview', parent::$_object->textdomain ) . '</strong>';
				$content.= __( ' - See a preview of the pagination according to your current settings', parent::$_object->textdomain ) . '</p>';
				$content.= '<p><strong>' . __( 'Labels', parent::$_object->textdomain ) . '</strong>';
				$content.= __( ' - Enable navigation labels. Set navigation labels: first and last, previous and next page. Leave empty to revert to defaults', parent::$_object->textdomain ) . '</p>';
				$content.= '<p><strong>' . __( 'Placement', parent::$_object->textdomain ) . '</strong>';
				$content.= __( ' - Determine where the pagination should appear on your site. <br /><i>Inject:</i> You can "inject" the navigation bar below the header or above the footer div. Your theme needs to have an according HTML structure (which most WordPress themes do).<br /><i>Append/prepend:</i> You can specifiy a container into which the pagination will be prepended/appended. Write it CSS-style, i.e. #mycontainer', parent::$_object->textdomain ) . '</p>';
				$content.= '<p><strong>' . __( 'DOM & CSS', parent::$_object->textdomain ) . '</strong>';
				$content.= __( ' - Define the CSS class that will be attributed to the main pagination container div.', parent::$_object->textdomain ) . '</p>';
				$content.= '<p><strong>' . __( 'Other options', parent::$_object->textdomain ) . '</strong>';
				$content.= __( ' - Limit the pagination to a maximum number of pages, enable pagination links for pretty URLs (checked by default).', parent::$_object->textdomain ) . '</p>';

				$content = apply_filters( 'threepagination_help', $content );

				$screen->add_help_tab( array( 'id' => 'threepagination-help', 'title' => __( '3pagination' ), 'content' => $content ) );
			}
			//return $content;
		}

		/**
		 * Settings page output
		 * 
		 * @since 0.1 
		 */
		public function draw_settings_page() {
			?>
			<div class="wrap">
				<?php screen_icon( 'options-general' ); ?>
				<h2><?php _e( '3pagination Settings', parent::$_object->textdomain ); ?></h2>
				<p>
					<span class="description"><?php _e( 'See "Help" tab for further explanations', parent::$_object->textdomain ); ?></span>
				</p>
				
				<form action="options.php" method="post">
					<?php
					settings_fields( '3pagination_settings' );
					do_settings_sections( '3pagination' );
					submit_button( __( 'Save Changes', parent::$_object->textdomain ), 'button-primary', 'submit', TRUE );
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Init the settings API 
		 * 
		 * @TODO checkbox "I am using pretty URLS"
		 * 
		 * @since 0.1
		 */
		public function settings_api_init() {

			// Register our setting so that $_POST handling is done for us and
			// our callback function just has to echo the <input>
			register_setting( '3pagination_settings', '3pagination_settings', array( $this, 'threepagination_validate' ) );

			// Add the section to reading settings so we can add our
			// fields to it			
			add_settings_section( '3pagination_preview', __( 'Preview', parent::$_object->textdomain ), array( $this, 'section_preview' ), '3pagination' );
			add_settings_section( '3pagination_labels', __( 'Labels', parent::$_object->textdomain ), array( $this, 'section_labels' ), '3pagination' );
			add_settings_section( '3pagination_placement', __( 'Placement', parent::$_object->textdomain ), array( $this, 'section_placement' ), '3pagination' );
			add_settings_section( '3pagination_css', __( 'DOM & CSS', parent::$_object->textdomain ), array( $this, 'section_css' ), '3pagination' );
			add_settings_section( '3pagination_other', __( 'Other options', parent::$_object->textdomain ), array( $this, 'section_other' ), '3pagination' );

			// Add the field with the names and function to use for our new
			// settings, put it in our new section
			add_settings_field( 'threepagination_preview', __( 'Preview of pagination', parent::$_object->textdomain ), array( $this, 'preview' ), '3pagination', '3pagination_preview' );

			add_settings_field( 'threepagination_labels_show', __( 'Show labels', parent::$_object->textdomain ), array( $this, 'labels_show' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_previous', __( 'Previous page', parent::$_object->textdomain ), array( $this, 'labels_previous' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_next', __( 'Next page', parent::$_object->textdomain ), array( $this, 'labels_next' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_first', __( 'First page', parent::$_object->textdomain ), array( $this, 'labels_first' ), '3pagination', '3pagination_labels' );
			add_settings_field( 'threepagination_labels_last', __( 'Last page', parent::$_object->textdomain ), array( $this, 'labels_last' ), '3pagination', '3pagination_labels' );

			add_settings_field( 'threepagination_css_class', __( 'Set CSS class', parent::$_object->textdomain ), array( $this, 'css_class' ), '3pagination', '3pagination_css' );

			add_settings_field( 'threepagination_placement_header', __( 'Inject below header', parent::$_object->textdomain ), array( $this, 'placement_header' ), '3pagination', '3pagination_placement' );
			add_settings_field( 'threepagination_placement_footer', __( 'Inject above footer', parent::$_object->textdomain ), array( $this, 'placement_footer' ), '3pagination', '3pagination_placement' );

			add_settings_field( 'threepagination_placement_prepend', __( 'Prepend to', parent::$_object->textdomain ), array( $this, 'placement_prepend' ), '3pagination', '3pagination_placement' );
			add_settings_field( 'threepagination_placement_append', __( 'Append to', parent::$_object->textdomain ), array( $this, 'placement_append' ), '3pagination', '3pagination_placement' );

			add_settings_field( 'threepagination_other_maxnumpages', __( 'Max no. of pages', parent::$_object->textdomain ), array( $this, 'other_maxnumpages' ), '3pagination', '3pagination_other' );
			add_settings_field( 'threepagination_other_pretty', __( 'Pretty URLs enabled?', parent::$_object->textdomain ), array( $this, 'other_pretty' ), '3pagination', '3pagination_other' );
		}

		/**
		 * Head of "preview" section
		 * 
		 * @since 0.1 
		 */
		public function section_preview() {
			?>
			<!--<span class="description"><?php _e( 'Preview of pagination', parent::$_object->textdomain ); ?></span>-->
			<?php
		}

		/**
		 * Head of "labels" section
		 * 
		 * @since 0.1 
		 */
		public function section_labels() {
			?>
			<span class="description"><?php _e( 'Define the navigation labels characters', parent::$_object->textdomain ); ?></span>
			<?php
		}

		/**
		 * Head of "CSS" section
		 * 
		 * @since 0.1 
		 */
		public function section_css() {
			?>
			<span class="description"><?php _e( 'Set your stylesheet for the pagination', parent::$_object->textdomain ); ?></span>
			<?php
		}

		/**
		 * Head of "Other options" section
		 * 
		 * @since 0.1 
		 */
		public function section_other() {
			?>
			<span class="description"><?php _e( '', parent::$_object->textdomain ); ?></span>
			<?php
		}

		/**
		 * Head of "placement" section
		 * 
		 * @since 0.1 
		 */
		public function section_placement() {
			?>
			<p>
				<span class="description"><?php _e( '<b>Note:</b> If you want your site to degrade gracefully, you should additionaly hardcode the pagination function into your theme files: <input style="background-color:lightgrey" size="45" type="text" value="<noscript><?php threepagination::draw(); ?></noscript>"', parent::$_object->textdomain ); ?></span>
			</p>
			<?php
		}

		/**
		 * Display a preview of the pagination
		 * 
		 * @since 0.1 
		 */
		public function preview() {
			set_query_var( 'paged', 3 );
			parent::draw( TRUE, 999 );
		}

		/**
		 * Show pagination labels or not
		 * 
		 * @since 0.1 
		 */
		public function labels_show() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="checkbox" name="3pagination_settings[labels_show]" <?php checked( $this->init_var( $settings, 'labels_show', 'on' ), 'on' ); ?> />
			<?php
		}

		/**
		 * Set 'previous page' label
		 * 
		 * @since 0.1 
		 */
		public function labels_previous() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_previous]" value="<?php echo $this->init_var( $settings, 'labels_previous', '&lsaquo;', TRUE ); ?>" />
			<?php
		}

		/**
		 * Set 'next page' label
		 * 
		 * @since 0.1 
		 */
		public function labels_next() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_next]" value="<?php echo $this->init_var( $settings, 'labels_next', '&rsaquo;', TRUE ); ?>" />
			<?php
		}

		/**
		 * Set 'first page' label
		 * 
		 * @since 0.1 
		 */
		public function labels_first() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_first]" value="<?php echo $this->init_var( $settings, 'labels_first', '&laquo;', TRUE ); ?>" />
			<?php
		}

		/**
		 * Set 'last page' label
		 * 
		 * @since 0.1 
		 */
		public function labels_last() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[labels_last]" value="<?php echo $this->init_var( $settings, 'labels_last', '&raquo;', TRUE ); ?>" />
			<?php
		}

		/**
		 * Inject below header
		 * 
		 * @since 0.1
		 */
		public function placement_header() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="checkbox" name="3pagination_settings[placement_header_index]" <?php checked( $this->init_var( $settings, 'placement_header_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_header_index]"><?php _e( 'Index pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_header_archive]" <?php checked( $this->init_var( $settings, 'placement_header_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_header_archive]"><?php _e( 'Archive pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_header_category]" <?php checked( $this->init_var( $settings, 'placement_header_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_header_category]"><?php _e( 'Category pages', parent::$_object->textdomain ); ?></label>			
			<input type="checkbox" name="3pagination_settings[placement_header_search]" <?php checked( $this->init_var( $settings, 'placement_header_search', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_header_search]"><?php _e( 'Search pages', parent::$_object->textdomain ); ?></label>
			<?php
		}

		/**
		 * Inject above footer
		 * 
		 * @since 0.1 
		 */
		public function placement_footer() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="checkbox" name="3pagination_settings[placement_footer_index]" <?php checked( $this->init_var( $settings, 'placement_footer_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_footer_index]"><?php _e( 'Index pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_footer_archive]" <?php checked( $this->init_var( $settings, 'placement_footer_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_footer_archive]"><?php _e( 'Archive pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_footer_category]" <?php checked( $this->init_var( $settings, 'placement_footer_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_footer_category]"><?php _e( 'Category pages', parent::$_object->textdomain ); ?></label>			
			<input type="checkbox" name="3pagination_settings[placement_footer_search]" <?php checked( $this->init_var( $settings, 'placement_footer_search', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_footer_search]"><?php _e( 'Search pages', parent::$_object->textdomain ); ?></label>			
			<?php
		}

		/**
		 * Prepend to custom container
		 * 
		 * @since 0.1 
		 */
		public function placement_prepend() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[placement_prepend_id]" value="<?php echo $this->init_var( $settings, 'placement_prepend_id', FALSE ); ?>" />			
			<label for="3pagination_settings[placement_prepend_id]"><?php _e( 'in', parent::$_object->textdomain ); ?></label>			
			<input type="checkbox" name="3pagination_settings[placement_prepend_index]" <?php checked( $this->init_var( $settings, 'placement_prepend_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_prepend_index]"><?php _e( 'Index pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_prepend_archive]" <?php checked( $this->init_var( $settings, 'placement_prepend_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_prepend_archive]"><?php _e( 'Archive pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_prepend_category]" <?php checked( $this->init_var( $settings, 'placement_prepend_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_prepend_category]"><?php _e( 'Category pages', parent::$_object->textdomain ); ?></label>	
			<input type="checkbox" name="3pagination_settings[placement_prepend_search]" <?php checked( $this->init_var( $settings, 'placement_prepend_search', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_prepend_search]"><?php _e( 'Search pages', parent::$_object->textdomain ); ?></label>			
			<?php
		}

		/**
		 * Append to custom container
		 * 
		 * @since 0.1 
		 */
		public function placement_append() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[placement_append_id]" value="<?php echo $this->init_var( $settings, 'placement_append_id', FALSE ); ?>" />			
			<label for="3pagination_settings[placement_append_id]"><?php echo _x( 'in', 'Zeige Pagination "in" diesen Seiten an', parent::$_object->textdomain ); ?></label>						
			<input type="checkbox" name="3pagination_settings[placement_append_index]" <?php checked( $this->init_var( $settings, 'placement_append_index', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_append_index]"><?php _e( 'Index pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_append_archive]" <?php checked( $this->init_var( $settings, 'placement_append_archive', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_append_archive]"><?php _e( 'Archive pages', parent::$_object->textdomain ); ?></label>
			<input type="checkbox" name="3pagination_settings[placement_append_category]" <?php checked( $this->init_var( $settings, 'placement_append_category', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_append_category]"><?php _e( 'Category pages', parent::$_object->textdomain ); ?></label>	
			<input type="checkbox" name="3pagination_settings[placement_append_search]" <?php checked( $this->init_var( $settings, 'placement_append_search', FALSE ), 'on' ); ?> />
			<label for="3pagination_settings[placement_append_search]"><?php _e( 'Search pages', parent::$_object->textdomain ); ?></label>
			<?php
		}

		/**
		 * Set CSS class
		 * 
		 * @since 0.1 
		 */
		public function css_class() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[css_class]" value="<?php echo $this->init_var( $settings, 'css_class', 'classic', TRUE ); ?>" />
			<br />
			<span class="description"><?php _e( 'Default classes: classic, classic-glow, classic-small', parent::$_object->textdomain ); ?></span>
			<?php
		}

		public function other_maxnumpages() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="text" name="3pagination_settings[other_maxnumpages]" value="<?php echo $this->init_var( $settings, 'other_maxnumpages', '999', TRUE ); ?>" />
			<br />
			<span class="description"><?php _e( 'Limit to a maximum number of pagelinks', parent::$_object->textdomain ); ?></span>
			<?php
		}

		public function other_pretty() {

			$settings = get_option( '3pagination_settings', TRUE );
			?>
			<input type="checkbox" name="3pagination_settings[other_pretty]" <?php checked( $this->init_var( $settings, 'other_pretty', 'on' ), 'on' ); ?> />
			<?php
		}

		/**
		 * Validate user input
		 * 
		 * @TODO add_settings_error()
		 * @param type $data
		 * @return type 
		 * 
		 * @since 0.1
		 */
		public function threepagination_validate( $data ) {

			$settings = get_option( '3pagination_settings' );

			$settings[ 'labels_show' ] = esc_attr( $data[ 'labels_show' ] );
			$settings[ 'labels_previous' ] = esc_attr( $data[ 'labels_previous' ] );
			$settings[ 'labels_next' ] = esc_attr( $data[ 'labels_next' ] );
			$settings[ 'labels_first' ] = esc_attr( $data[ 'labels_first' ] );
			$settings[ 'labels_last' ] = esc_attr( $data[ 'labels_last' ] );

			$settings[ 'css_class' ] = esc_attr( $data[ 'css_class' ] );

			$settings[ 'placement_header_index' ] = esc_attr( $data[ 'placement_header_index' ] );
			$settings[ 'placement_header_archive' ] = esc_attr( $data[ 'placement_header_archive' ] );
			$settings[ 'placement_header_search' ] = esc_attr( $data[ 'placement_header_search' ] );
			$settings[ 'placement_header_category' ] = esc_attr( $data[ 'placement_header_category' ] );

			$settings[ 'placement_footer_index' ] = esc_attr( $data[ 'placement_footer_index' ] );
			$settings[ 'placement_footer_archive' ] = esc_attr( $data[ 'placement_footer_archive' ] );
			$settings[ 'placement_footer_category' ] = esc_attr( $data[ 'placement_footer_category' ] );
			$settings[ 'placement_footer_search' ] = esc_attr( $data[ 'placement_footer_search' ] );

			$settings[ 'placement_prepend_id' ] = esc_attr( $data[ 'placement_prepend_id' ] );
			$settings[ 'placement_prepend_index' ] = esc_attr( $data[ 'placement_prepend_index' ] );
			$settings[ 'placement_prepend_archive' ] = esc_attr( $data[ 'placement_prepend_archive' ] );
			$settings[ 'placement_prepend_category' ] = esc_attr( $data[ 'placement_prepend_category' ] );
			$settings[ 'placement_prepend_search' ] = esc_attr( $data[ 'placement_prepend_search' ] );

			$settings[ 'placement_append_id' ] = esc_attr( $data[ 'placement_append_id' ] );
			$settings[ 'placement_append_index' ] = esc_attr( $data[ 'placement_append_index' ] );
			$settings[ 'placement_append_archive' ] = esc_attr( $data[ 'placement_append_archive' ] );
			$settings[ 'placement_append_search' ] = esc_attr( $data[ 'placement_append_search' ] );
			$settings[ 'placement_append_category' ] = esc_attr( $data[ 'placement_append_category' ] );

			$settings[ 'other_maxnumpages' ] = intval( $data[ 'other_maxnumpages' ] );
			$settings[ 'other_pretty' ] = esc_attr( $data[ 'other_pretty' ] );

			return $settings;
		}

		/**
		 * Unregister and delete settings; clean database
		 *
		 * @uses unregister_setting, delete_option
		 * @access public
		 * @since 0.1
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
