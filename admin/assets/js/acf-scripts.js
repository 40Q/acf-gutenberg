(function( $ ) {
	'use strict';

	$(document).ready(function() {
		$('.acf-field.select2 select').select2();
	});
	console.log('hereee');

	var postID = acf.get('post_id');
	console.log(postID);


	acf.add_action('load', function( $el ){

		// $el will be equivalent to $('body')

		// acf.newSelect2( '.acf-field.select2 select' );

		// find a specific field
		var $field = $el.find('#my-wrapper-id');
		$('.acf-field.select2 select').select2();

		console.log('acf action');
		console.log(acf.newSelect2);


		// do something to $field

	});


	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
