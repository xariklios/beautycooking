/* global jQuery:false */

jQuery(document).ready(function() {
	SOAPERY_STORAGE['media_frame'] = null;
	SOAPERY_STORAGE['media_link'] = '';
	jQuery('.soapery_media_selector').on('click', function(e) {
		soapery_show_media_manager(this);
		e.preventDefault();
		return false;
	});
});

function soapery_show_media_manager(el) {
	"use strict";

	SOAPERY_STORAGE['media_link'] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( SOAPERY_STORAGE['media_frame'] ) {
		SOAPERY_STORAGE['media_frame'].open();
		return false;
	}

	// Create the media frame.
	SOAPERY_STORAGE['media_frame'] = wp.media({
		// Set the title of the modal.
		title: SOAPERY_STORAGE['media_link'].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: 'image'
		},
		// Multiple choise
		multiple: SOAPERY_STORAGE['media_link'].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: SOAPERY_STORAGE['media_link'].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	SOAPERY_STORAGE['media_frame'].on( 'select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+SOAPERY_STORAGE['media_link'].data('linked-field')).eq(0);
		var attachment = '';
		if (SOAPERY_STORAGE['media_link'].data('multiple')===true) {
			SOAPERY_STORAGE['media_frame'].state().get('selection').map( function( att ) {
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = SOAPERY_STORAGE['media_frame'].state().get('selection').first().toJSON().url;
		}
		field.val(attachment);
		if (field.siblings('img').length > 0) field.siblings('img').attr('src', attachment);
		field.trigger('change');
	});

	// Finally, open the modal.
	SOAPERY_STORAGE['media_frame'].open();
	return false;
}
