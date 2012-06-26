<?php
/**
 * Plugin Name: 3pagination
 * Description: Reach any page with no more than 3 clicks
 * Version: 0.1a
 * Author: Michael Schröder <ms@ts-webdesign.net>
 * TextDomain: 3pagination
 * DomainPath: /l10n
 */

// Load example CSS
add_action( 'wp_enqueue_scripts', 'load_example_css' );

// Example callback function
function load_example_css () {
	wp_enqueue_style( 'threepagination-css', plugins_url( 'examples/style.css', __FILE__ ) );
}

if ( ! class_exists( 'threepagination' ) ) {

	class threepagination {

		/**
		 * Returns a HTML string containing the navigation.
		 * 
		 * @global object $wp_query | the current query object used to gather pagination information
		 * @global type $wp
		 * @param bool $pretty | pretty permalink strukture. TRUE or FALSE
		 * @param int $num_items | can be used to override the global number of items
		 * @param int $per_page | can be used to override the global posts per page
		 * @param bool $labels | show labels, TRUE or FALSE
		 * @param string $css | the css class name appended to the 'threepagination' wrapper div
		 * @return void 
		 * 
		 * @since 0.1a
		 */
		public static function get ( $pretty = TRUE, $max_num_pages = FALSE, $labels = TRUE, $css = 'classic' ) {

			global $wp_query, $wp;

			// Get the page count
			$total_pages = ( FALSE == $max_num_pages ) ? $wp_query->max_num_pages : $max_num_pages;

			// No need for navi
			if ( 1 == $total_pages )
				return;

			// Get currently visited page 
			$on_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			$digits = strlen( $on_page );

			$page_string = '';

			// Start some complicated calculations. The benfit should
			// be a navigation that lets you navigate to any page between 1 and 999
			// without more than 3 mouse clicks. Maximum page number is 999.
			switch ( $digits ) {
				case 1:
				case 2:
					for ( $i = 1; $i <= $total_pages; ++$i ) {

						if ( $i == 1
								OR ($i <= 10 AND $digits == 1)
								OR ($i < 100 AND substr( $i, -2, 1 ) == substr( $on_page, -2, 1 ) AND $digits == strlen( $i ))
								OR ($i >= 10 AND $i < 100 AND is_int( $i / 10 ))
								OR (is_int( $i / 100 ))
								OR ($i == $total_pages)
						) {
							$url = self::url( $wp, $i, $pretty );
							$page_string .= ( $i == $on_page ) ? "<span class='page-numbers current'>" . $i . "</span>" : "<a class='page-numbers' href='" . $url . "'>" . $i . "</a>";
							if ( $i < $total_pages ) {
								$page_string .= "&nbsp;";
							}
						}
					}
					break;

				case 3:
					for ( $i = 1; $i <= $total_pages; ++$i ) {

						if ( $i == 1
								OR ($i >= 100 AND substr( $i, -3, 2 ) == substr( $on_page, -3, 2 ))
								OR ($i >= 100 AND is_int( $i / 10 ) AND substr( $i, -3, 1 ) == substr( $on_page, -3, 1 ))
								OR (is_int( $i / 100 ))
								OR ($i == $total_pages)
						) {
							$url = self::url( $wp, $i, $pretty );
							$page_string .= ( $i == $on_page ) ? "<span class='page-numbers current'>" . $i . "</span>" : "<a href='" . $url . "'>" . $i . "</a>";
							if ( $i < $total_pages ) {
								$page_string .= "&nbsp;";
							}
						}
					}
					break;
					
				default:
					for ( $i = 1; $i <= 999; ++$i ) {

						if ( $i == 1
								OR ($i >= 100 AND substr( $i, -3, 2 ) == substr( $on_page, -3, 2 ))
								OR ($i >= 100 AND is_int( $i / 10 ) AND substr( $i, -3, 1 ) == substr( $on_page, -3, 1 ))
								OR (is_int( $i / 100 ))
								OR ($i == $total_pages)
						) {
							$url = self::url( $wp, $i, $pretty );
							$page_string .= ( $i == $on_page ) ? "<span class='page-numbers current'>" . $i . "</span>" : "<a href='" . $url . "'>" . $i . "</a>";
							if ( $i < $total_pages ) {
								$page_string .= "&nbsp;";
							}
						}
					}
					break;
			}

			// Navigation labels
			if ( FALSE !== $labels ) {
				if ( $on_page > 1 ) {
					$i = $on_page - 1;
					$page_string = "<a class='page-numbers label-first' href='" . self::url( $wp, 1, $pretty ) . "'>&laquo;</a>&nbsp;" . $page_string;
					$page_string = "<a class='page-numbers label-previous' href='" . self::url( $wp, $i, $pretty ) . "'>&lsaquo;</a>&nbsp;" . $page_string;
				}

				if ( $on_page < $total_pages ) {
					$i = $on_page + 1;
					$page_string .= "&nbsp;<a class='page-numbers label-next' href='" . self::url( $wp, $i, $pretty ) . "'>&rsaquo;</a>";
					$page_string .= "&nbsp;<a class='page-numbers label-last' href='" . self::url( $wp, $total_pages, $pretty ) . "'>&raquo;</a>";
				}
			}

			// Glue together the HTML string
			$page_string = "<div class='threepagination classic'><div class='threepagination-pages'>" . $page_string . "</div></div>";

			// Return string
			return $page_string;
		}
		
		/**
		 * Main display function. Should be called in a static fashion:
		 * threepagination::draw();
		 * 
		 * @global object $wp_query | the current query object used to gather pagination information
		 * @global type $wp
		 * @param bool $pretty | pretty permalink structure. TRUE or FALSE, defaults to TRUE
		 * @param int $num_items | can be used to override the global number of items
		 * @param int $per_page | can be used to override the global posts per page
		 * @param bool $labels | show labels, TRUE or FALSE
		 * @return void 
		 * 
		 * @since 0.1a
		 */
		public static function draw ( $pretty = TRUE, $max_num_pages = FALSE, $labels = TRUE, $css = 'classic' ) {

			echo self::get( $pretty, $max_num_pages, $labels, $css );
		}
		
		/**
		 * Create link href
		 * 
		 * @param object $wp | WP object
		 * @param int $i | current element
		 * @param bool $pretty | pretty permalink structure. TRUE or FALSE, defaults to TRUE
		 * @return string $url | the href attribute of our pagination element link
		 */
		private static function url( $wp, $i, $pretty ) {
					
			if ( TRUE == $pretty ) {
				if ( get_query_var( 'paged' ) )
					$url = preg_replace( '!(/page/\d+)/?$!', '/page/' . $i, home_url( $wp->request ) );
				else 
					$url = home_url( $wp->request ) . '/page/' . $i; 
			}
			else
				$url = home_url( $wp->request ) . '?paged=' . $i;
			
			return $url;	
		}

	}

}
?>