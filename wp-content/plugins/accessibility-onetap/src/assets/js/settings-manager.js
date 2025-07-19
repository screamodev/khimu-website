/* eslint no-undef: "off", no-alert: "off" */
( function( $ ) {
	//Initiate Color Picker
	$( '.wp-color-picker-field' ).wpColorPicker();

	// Switches option sections
	$( '.group' ).hide();
	let activetab = '';
	if ( typeof ( localStorage ) !== 'undefined' ) {
		activetab = localStorage.getItem( 'activetab' );
	}

	if ( activetab !== '' && $( activetab ).length ) {
		$( activetab ).fadeIn();
	} else {
		$( '.group:first' ).fadeIn();
	}
	$( '.group .collapsed' ).each( function() {
		$( this ).find( 'input:checked' ).parent().parent().parent().nextAll().each(
			function() {
				if ( $( this ).hasClass( 'last' ) ) {
					$( this ).removeClass( 'hidden' );
					return false;
				}
				$( this ).filter( '.hidden' ).removeClass( 'hidden' );
			} );
	} );

	if ( activetab !== '' && $( activetab + '-tab' ).length ) {
		$( activetab + '-tab' ).addClass( 'nav-tab-active' );
	} else {
		$( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
	}
	$( '.nav-tab-wrapper a' ).click( function( evt ) {
		$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$( this ).addClass( 'nav-tab-active' ).blur();
		const clickedGroup = $( this ).attr( 'href' );
		if ( typeof ( localStorage ) !== 'undefined' ) {
			localStorage.setItem( 'activetab', $( this ).attr( 'href' ) );
		}
		$( '.group' ).hide();
		$( clickedGroup ).fadeIn();
		evt.preventDefault();
	} );

	$( '.setting-manager-meida-browse' ).on( 'click', function( event ) {
		event.preventDefault();

		const self = $( this );

		// Create the media frame.
		const fileFrame = wp.media.frames.fileFrame = wp.media( {
			title: self.data( 'uploader_title' ),
			button: {
				text: self.data( 'uploader_button_text' ),
			},
			multiple: false,
		} );

		fileFrame.on( 'select', function() {
			attachment = fileFrame.state().get( 'selection' ).first().toJSON();
			self.prev( '.setting-manager-url' ).val( attachment.url ).change();
		} );

		// Finally, open the modal.
		fileFrame.open();
	} );
}( jQuery ) );
