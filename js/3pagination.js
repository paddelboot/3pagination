/**
 * Inpsyde Brauser24 admin javascript file
 * 
 * @copyright Inpsyde GmbH
 * @version v0.1a;
 * 
 *
 * Changelog:
 *
 * 
 */

jQuery.noConflict();

( function( $ ) {
    
	threepagination = {

		/**
		 * Setup timeline calendar
		 *
		 * @since 0.1a
		 */
		init : function () {
                    
			console.log( threepag_vars );
			console.log( JSON.parse( threepag_vars.html ) );
                        
                        this.placement();
		},
		
		
		/**
		 * Backend exporter use the datetimepicker
		 * 
		 * @since 0.1a
		 */
		placement : function() {
                    
                        var html = JSON.parse( threepag_vars.html );
			
                        // Inject the HTML below header
                        if ( '1' == threepag_vars.placement_header ) {
                            // Check for DOM structure
                            if ( $( '#header' ).length ) {
                                $( '#header' ).after( html );
                            }
                            else if ( $( 'header#branding' ).length )
                                $( 'header:first' ).after( html )
                        }
                    
                        // Inject above footer
                        if ( '1' == threepag_vars.placement_footer ) {
                            // Check for DOM structure
                            if ( $( '#footer' ).length ) {
                                $( '#footer' ).before( html );
                            }
                            else if ( $( 'footer' ).length )
                                $( 'footer_first' ).before( html )
                        }
                        
                        // Prepend to custom container
                        if ( '1' == threepag_vars.placement_prepend ) {
                            if ( $( threepag_vars.placement_prepend_id ).length )
                                $( threepag_vars.placement_prepend_id ).prepend( html );
                        }
                        
                        // Append to custom container
                        if ( '1' == threepag_vars.placement_append ) {
                            if ( $( threepag_vars.placement_append_id ).length )
                                $( threepag_vars.placement_append_id ).append( html );
                        }
			
		}
	}
} )( jQuery );

jQuery( document ).ready( function() {
       
	threepagination.init();
    
});
