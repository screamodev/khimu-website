/* eslint no-undef: "off", no-alert: "off" */
( function( $ ) {
	// Function to handle the click event for each box type
	function handleBoxSelection( selector ) {
		const boxElements = $( selector );
		boxElements.click( function() {
			boxElements.removeClass( 'checked' );
			$( this ).addClass( 'checked' );
		} );
	}

	// Apply the function to different box types
	handleBoxSelection( 'tr.icons td .box-setting-option.icons .box-control .boxes .box label' );
	handleBoxSelection( 'tr.size td .box-setting-option.size .box-control .boxes .box label' );
	handleBoxSelection( 'tr.border td .box-setting-option.border .box-control .boxes .box label' );

	// Event to handle click action on specific label elements
	$( 'tr.icons td .box-setting-option.icons .box-control .boxes .box label' ).on( 'click', function() {
		// Get the 'src' attribute from the child <img> element inside the clicked label
		const getImage = $( this ).children().attr( 'src' );

		// Update the 'src' attribute for the image in the 'size' section
		$( 'tr.size td .box-setting-option.size .box-control .boxes .box label img' ).attr( 'src', getImage );

		// Update the 'src' attribute for the image in the 'border' section
		$( 'tr.border td .box-setting-option.border .box-control .boxes .box label img' ).attr( 'src', getImage );
	} );

	// Add 'active' class to the default desktop layout
	$( 'tr.position-top-bottom, tr.position-left-right, tr.widge-position' ).addClass( 'active' );

	// Handle button click events in the device selection box
	$( '.box-setting-option .box-device .boxes button' ).on( 'click', function() {
		$( '.box-setting-option .box-device .boxes button' ).removeClass( 'active' );
		$( this ).addClass( 'active' );

		const getClass = $( this ).attr( 'class' );
		if ( 'desktop active' === getClass ) {
			$( '.box-setting-option .box-device .boxes button.desktop' ).addClass( 'active' );

			$( 'tr.position-top-bottom' ).addClass( 'active' );
			$( 'tr.position-left-right' ).addClass( 'active' );
			$( 'tr.widge-position' ).addClass( 'active' );

			$( 'tr.position-top-bottom-tablet' ).removeClass( 'active' );
			$( 'tr.position-left-right-tablet' ).removeClass( 'active' );
			$( 'tr.widge-position-tablet' ).removeClass( 'active' );

			$( 'tr.position-top-bottom-mobile' ).removeClass( 'active' );
			$( 'tr.position-left-right-mobile' ).removeClass( 'active' );
			$( 'tr.widge-position-mobile' ).removeClass( 'active' );
		} else if ( 'tablet active' === getClass ) {
			$( '.box-setting-option .box-device .boxes button.tablet' ).addClass( 'active' );

			$( 'tr.position-top-bottom' ).removeClass( 'active' );
			$( 'tr.position-left-right' ).removeClass( 'active' );
			$( 'tr.widge-position' ).removeClass( 'active' );

			$( 'tr.position-top-bottom-tablet' ).addClass( 'active' );
			$( 'tr.position-left-right-tablet' ).addClass( 'active' );
			$( 'tr.widge-position-tablet' ).addClass( 'active' );

			$( 'tr.position-top-bottom-mobile' ).removeClass( 'active' );
			$( 'tr.position-left-right-mobile' ).removeClass( 'active' );
			$( 'tr.widge-position-mobile' ).removeClass( 'active' );
		} else if ( 'mobile active' === getClass ) {
			$( '.box-setting-option .box-device .boxes button.mobile' ).addClass( 'active' );

			$( 'tr.position-top-bottom' ).removeClass( 'active' );
			$( 'tr.position-left-right' ).removeClass( 'active' );
			$( 'tr.widge-position' ).removeClass( 'active' );

			$( 'tr.position-top-bottom-tablet' ).removeClass( 'active' );
			$( 'tr.position-left-right-tablet' ).removeClass( 'active' );
			$( 'tr.widge-position-tablet' ).removeClass( 'active' );

			$( 'tr.position-top-bottom-mobile' ).addClass( 'active' );
			$( 'tr.position-left-right-mobile' ).addClass( 'active' );
			$( 'tr.widge-position-mobile' ).addClass( 'active' );
		} else {
			$( '.box-setting-option .box-device .boxes button.desktop' ).addClass( 'active' );

			$( 'tr.position-top-bottom' ).addClass( 'active' );
			$( 'tr.position-left-right' ).addClass( 'active' );
			$( 'tr.widge-position' ).addClass( 'active' );

			$( 'tr.position-top-bottom-tablet' ).removeClass( 'active' );
			$( 'tr.position-left-right-tablet' ).removeClass( 'active' );
			$( 'tr.widge-position-tablet' ).removeClass( 'active' );

			$( 'tr.position-top-bottom-mobile' ).removeClass( 'active' );
			$( 'tr.position-left-right-mobile' ).removeClass( 'active' );
			$( 'tr.widge-position-mobile' ).removeClass( 'active' );
		}
	} );

	// Additional event to set default image on page load
	// Get the 'src' attribute of the first icon image as the default
	const initialImage = $( 'tr.icons td .box-setting-option.icons .box-control .boxes .box label.checked' ).first().children().attr( 'src' );

	// If initialImage exists, set it as the default for 'size' and 'border' sections
	if ( initialImage ) {
		$( 'tr.size td .box-setting-option.size .box-control .boxes .box label img' ).attr( 'src', initialImage );
		$( 'tr.border td .box-setting-option.border .box-control .boxes .box label img' ).attr( 'src', initialImage );
	}

	// Attach a click event listener to all <a> tags with href starting with '#'
	$( 'a[href^="#"]' ).on( 'click', function( event ) {
		// Get the target element based on the href attribute of the clicked link
		const target = $( $.attr( this, 'href' ) );

		// Check if the target element exists
		if ( target.length ) {
			// Prevent the default anchor link behavior (default jump)
			event.preventDefault();

			// Set the offset for how far above the target the scroll should stop
			const offset = 110;

			// Animate scrolling to the target element minus the offset
			$( 'html, body' ).animate( {
				scrollTop: target.offset().top - offset,
			}, 0 ); // Duration of the scroll (0 means no animation)
		}
	} );

	const link = document.querySelectorAll(
		'.wrap .tabs .mycontainer .myrow .box-menu a'
	);
	const row = document.querySelectorAll( '.wrap .data-content' );

	link.forEach( function( item, index ) {
		link[ index ].addEventListener( 'click', function() {
			// Get id
			const getId = '.' + this.getAttribute( 'myid' );

			// Remove all class active link
			link.forEach( function( element ) {
				element.classList.remove( 'active' );
			} );

			// Active class link
			this.classList.add( 'active' );

			// Hide all data content
			row.forEach( function( element ) {
				element.classList.add( 'hide' );
				element.classList.remove( 'active' );
			} );

			// Show data content active current
			document
				.querySelector( getId + '.data-content' )
				.classList.remove( 'hide' );
			document
				.querySelector( getId + '.data-content' )
				.classList.add( 'active' );
		} );
	} );
}( jQuery ) );
