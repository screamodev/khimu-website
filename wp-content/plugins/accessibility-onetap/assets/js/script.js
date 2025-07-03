/* eslint no-undef: "off", no-alert: "off" */
( function( $ ) {
	'use strict';

	const accessibilityOnetapToggleClose = $( '.onetap-accessibility-plugin .onetap-close' );
	const accessibilityOnetapToggleOpen = $( '.onetap-accessibility-plugin .onetap-toggle' );
	const accessibilityOnetapAccessibility = $( '.onetap-accessibility-plugin .onetap-accessibility' );
	const accessibilityOnetapLanguageList = $( '.onetap-accessibility-plugin .onetap-list-of-languages' );
	const accessibilityOnetapToggleLanguages = $( '.onetap-accessibility-plugin .onetap-languages' );

	const accessibilityOnetapSkipElements = '.onetap-plugin-onetap, .onetap-plugin-onetap *, .onetap-toggle, .onetap-toggle *, #wpadminbar, #wpadminbar *, rs-fullwidth-wrap, rs-fullwidth-wrap *, rs-module-wrap, rs-module-wrap *, sr7-module, sr7-module *';

	// Open Accessibility.
	accessibilityOnetapToggleOpen.click( function( event ) {
		event.stopPropagation();
		accessibilityOnetapAccessibility.removeClass( 'onetap-toggle-close' ).addClass( 'onetap-toggle-open' );
		accessibilityOnetapToggleClose.show( 100 );
	} );

	// Close Accessibility.
	accessibilityOnetapToggleClose.click( function( event ) {
		event.stopPropagation();
		accessibilityOnetapAccessibility.removeClass( 'onetap-toggle-open' ).addClass( 'onetap-toggle-close' );
		accessibilityOnetapToggleClose.hide( 100 );
	} );

	// Prevent auto-close when clicking inside accessibility panel.
	accessibilityOnetapAccessibility.click( function( event ) {
		accessibilityOnetapLanguageList.fadeOut( 350 );
		accessibilityOnetapToggleLanguages.removeClass( 'onetap-active' );
		if ( ! $( event.target ).closest( '.onetap-reset-settings' ).length ) {
			event.stopPropagation();
		}
	} );

	// Toggle list of languages.
	accessibilityOnetapToggleLanguages.click( function( event ) {
		event.stopPropagation();
		$( this ).toggleClass( 'onetap-active' );
		accessibilityOnetapLanguageList.fadeToggle( 350 );
	} );

	// Auto-close elements when clicking outside
	$( document ).click( function( event ) {
		const isClickInsideAccessibility = $( event.target ).closest( '.onetap-accessibility' ).length > 0;
		const isClickInsideLanguages = $( event.target ).closest( '.onetap-languages, .onetap-list-of-languages' ).length > 0;

		// If clicking outside the accessibility panel, close accessibility
		if ( ! isClickInsideAccessibility ) {
			accessibilityOnetapAccessibility.removeClass( 'onetap-toggle-open' ).addClass( 'onetap-toggle-close' );
			accessibilityOnetapToggleClose.hide( 100 );
		}

		// If clicking outside the language list, close the language list
		if ( ! isClickInsideLanguages ) {
			accessibilityOnetapLanguageList.fadeOut( 350 );
			accessibilityOnetapToggleLanguages.removeClass( 'onetap-active' );
		}
	} );

	// Get the current date
	const accessibilityOnetapToday = new Date();

	// Extract the accessibilityOnetapYear, accessibilityOnetapMonth, and accessibilityOnetapDay
	const accessibilityOnetapYear = accessibilityOnetapToday.getFullYear(); // Get the full accessibilityOnetapYear (e.g., 2024)
	const accessibilityOnetapMonth = String( accessibilityOnetapToday.getMonth() + 1 ).padStart( 2, '0' ); // Get the accessibilityOnetapMonth (0-11) and add 1; pad with 0 if needed
	const accessibilityOnetapDay = String( accessibilityOnetapToday.getDate() ).padStart( 2, '0' ); // Get the accessibilityOnetapDay of the accessibilityOnetapMonth (1-31) and pad with 0 if needed

	// Create a formatted date string for the start date in the format YYYY-MM-DD
	const accessibilityOnetapStartDate = `${ accessibilityOnetapYear }-${ accessibilityOnetapMonth }-${ accessibilityOnetapDay }`;

	// Create a new date object for the end date by adding 2 days to the current date
	const accessibilityOnetapEndDateObject = new Date( accessibilityOnetapToday ); // Create a new Date object based on accessibilityOnetapToday
	accessibilityOnetapEndDateObject.setDate( accessibilityOnetapEndDateObject.getDate() + 2 ); // Add 2 days

	// Extract the year, month, and day for the end date
	const accessibilityOnetapEndYear = accessibilityOnetapEndDateObject.getFullYear();
	const accessibilityOnetapEndMonth = String( accessibilityOnetapEndDateObject.getMonth() + 1 ).padStart( 2, '0' );
	const accessibilityOnetapEndDay = String( accessibilityOnetapEndDateObject.getDate() ).padStart( 2, '0' );

	// Create a formatted date string for the end date
	const accessibilityOnetapEndDate = `${ accessibilityOnetapEndYear }-${ accessibilityOnetapEndMonth }-${ accessibilityOnetapEndDay }`;

	// console.log(accessibilityOnetapStartDate); // Output the start date
	// console.log(accessibilityOnetapEndDate);   // Output the end date

	// Default values for accessibilityOnetapLocalStorage
	const accessibilityOnetapDefault = {
		dynamicFeatureSet: {
			visionImpairedMode: false,
			seizureSafeProfileMode: false,
			adhdFriendlyMode: false,
			blindnessMode: false,
			epilepsySafeMode: false,
		},
		activeBorders: {
			biggerText: 0,
			cursor: 0,
			lineHeight: 0,
			letterSpacing: 0,
			textAlign: 0,
			textMagnifier: 0,
			invertColors: 0,
			brightness: 0,
			contrast: 0,
			grayscale: 0,
			saturation: 0,
		},
		biggerText: false,
		cursor: false,
		lineHeight: false,
		letterSpacing: false,
		readableFont: false,
		dyslexicFont: false,
		textAlign: false,
		textMagnifier: false,
		highlightLinks: false,
		invertColors: false,
		brightness: false,
		contrast: false,
		grayscale: false,
		saturation: false,
		readingLine: false,
		keyboardNavigation: false,
		highlightTitles: false,
		readingMask: false,
		hideImages: false,
		highlightAll: false,
		readPage: false,
		muteSounds: false,
		stopAnimations: false,
		information: {
			updated: 'onetap-version-13',
			language: accessibilityOnetapAjaxObject.getSettings.language,
			developer: 'Yuky Hendiawan',
			startDate: accessibilityOnetapStartDate,
			endDate: accessibilityOnetapEndDate,
		},
	};

	// If 'accessibilityOnetapLocalStorage' does not exist in localStorage, create it
	const accessibilityOnetapLocalStorage = 'accessibility-onetap';
	if ( ! localStorage.getItem( accessibilityOnetapLocalStorage ) ) {
		localStorage.setItem( accessibilityOnetapLocalStorage, JSON.stringify( accessibilityOnetapDefault ) );
	} else {
		// Retrieve the existing data from localStorage
		const accessibilityData = JSON.parse( localStorage.getItem( accessibilityOnetapLocalStorage ) );

		// Check if 'information.updated' exists and whether its value is 'onetap-version-13'
		if ( typeof accessibilityData.information === 'undefined' ||
			typeof accessibilityData.information.updated === 'undefined' ||
			accessibilityData.information.updated !== 'onetap-version-13' ) {
			localStorage.removeItem( accessibilityOnetapLocalStorage );
			localStorage.setItem( accessibilityOnetapLocalStorage, JSON.stringify( accessibilityOnetapDefault ) );
		}
	}

	// Retrieves accessibility data from local storage.
	function accessibilityOnetapGetData() {
		const accessibilityData = JSON.parse( localStorage.getItem( accessibilityOnetapLocalStorage ) );
		return accessibilityData;
	}

	// Updates the country flag based on the selected language.
	updateLanguageFlag();
	function updateLanguageFlag() {
		// Remove the 'onetap-active' class from all country flag images
		$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img' ).removeClass( 'onetap-active' );

		// Add the 'onetap-active' class to the image with the alt attribute matching the selected language
		$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img[alt="' + accessibilityOnetapGetData().information.language + '"]' ).addClass( 'onetap-active' );
	}

	// Event handler for language selection
	$( 'nav.onetap-accessibility header.onetap-header-top .onetap-list-of-languages ul li' ).click( function() {
		const selectedLanguage = $( this ).attr( 'data-language' ); // Get the selected language from the data attribute
		const languageName = $( this ).text(); // Get the name of the selected language

		// Remove active class from the images
		$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img' ).removeClass( 'onetap-active' );

		// Add active class from the images
		$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img[alt="' + selectedLanguage + '"]' ).addClass( 'onetap-active' );

		// Remove active class from the language toggle
		$( accessibilityOnetapToggleLanguages ).removeClass( 'onetap-active' );

		// Update the displayed language name
		$( 'nav.onetap-accessibility header.onetap-header-top .onetap-languages .onetap-text span' ).text( languageName );

		// Update the header content based on the selected language
		accessibilityOnetapUpdateContentBasedOnLanguage( selectedLanguage );

		// Fade out the language settings panel
		$( '.onetap-accessibility-settings header.onetap-header-top .onetap-list-of-languages' ).fadeOut( 350 );

		const getDataAccessibilityDefault = accessibilityOnetapGetData();
		getDataAccessibilityDefault.information.language = selectedLanguage;
		localStorage.setItem( accessibilityOnetapLocalStorage, JSON.stringify( getDataAccessibilityDefault ) );
	} );

	// Function to update content based on the selected language
	accessibilityOnetapUpdateContentBasedOnLanguage( accessibilityOnetapGetData().information.language );
	function accessibilityOnetapUpdateContentBasedOnLanguage( language ) {
		// Define a list of valid languages
		const validLanguages = [ 'en', 'de', 'es', 'fr', 'it', 'pl', 'se', 'fi', 'pt', 'ro', 'si', 'sk', 'nl', 'dk', 'gr', 'cz', 'hu', 'lt', 'lv', 'ee', 'hr', 'ie', 'bg', 'no', 'tr', 'id', 'pt-br', 'ja', 'ko', 'zh', 'ar', 'ru', 'hi', 'uk', 'sr' ];

		// Check if the provided language is valid
		if ( validLanguages.includes( language ) ) {
			const languageData = accessibilityOnetapAjaxObject.languages[ language ];

			// Define an array of selectors and their corresponding data keys
			const updates = [
				// Header.
				{ selector: 'nav.onetap-accessibility header.onetap-header-top .onetap-languages .onetap-text span', text: languageData.header.language },
				{ selector: 'nav.onetap-accessibility header.onetap-header-top .onetap-site-container .onetap-site-info .onetap-title h2', text: languageData.header.title },
				{ selector: 'nav.onetap-accessibility header.onetap-header-top .onetap-site-container .onetap-site-info .onetap-desc p span', text: languageData.header.desc },
				{ selector: 'nav.onetap-accessibility header.onetap-header-top .onetap-site-container .onetap-site-info .onetap-desc p a', text: languageData.header.anchor },

				// Multi Functions Title.
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-title h2', text: languageData.multiFunctionalFeature.title },

				// Vision Impaired Mode.
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-vision-impaired-mode .onetap-left .onetap-text .onetap-title span', text: languageData.multiFunctionalFeature.visionImpairedMode.title },
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-vision-impaired-mode .onetap-left .onetap-text .onetap-desc span', text: languageData.multiFunctionalFeature.visionImpairedMode.desc },
				{ selectorOn: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-vision-impaired-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', on: languageData.multiFunctionalFeature.visionImpairedMode.on },
				{ selectorOff: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-vision-impaired-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', off: languageData.multiFunctionalFeature.visionImpairedMode.off },

				// Seizure Safe Profile.
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-seizure-safe-profile-mode .onetap-left .onetap-text .onetap-title span', text: languageData.multiFunctionalFeature.seizureSafeProfile.title },
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-seizure-safe-profile-mode .onetap-left .onetap-text .onetap-desc span', text: languageData.multiFunctionalFeature.seizureSafeProfile.desc },
				{ selectorOn: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-seizure-safe-profile-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', on: languageData.multiFunctionalFeature.seizureSafeProfile.on },
				{ selectorOff: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-seizure-safe-profile-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', off: languageData.multiFunctionalFeature.seizureSafeProfile.off },

				// ADHD Friendly Mode.
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-adhd-friendly-mode .onetap-left .onetap-text .onetap-title span', text: languageData.multiFunctionalFeature.aDHDFriendlyMode.title },
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-adhd-friendly-mode .onetap-left .onetap-text .onetap-desc span', text: languageData.multiFunctionalFeature.aDHDFriendlyMode.desc },
				{ selectorOn: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-adhd-friendly-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', on: languageData.multiFunctionalFeature.aDHDFriendlyMode.on },
				{ selectorOff: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-adhd-friendly-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', off: languageData.multiFunctionalFeature.aDHDFriendlyMode.off },

				// Blindness Mode.
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-blindness-mode .onetap-left .onetap-text .onetap-title span', text: languageData.multiFunctionalFeature.blindnessMode.title },
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-blindness-mode .onetap-left .onetap-text .onetap-desc span', text: languageData.multiFunctionalFeature.blindnessMode.desc },
				{ selectorOn: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-blindness-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', on: languageData.multiFunctionalFeature.blindnessMode.on },
				{ selectorOff: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-blindness-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', off: languageData.multiFunctionalFeature.blindnessMode.off },

				// Epilepsy Safe Mode.
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-epilepsy-safe-mode .onetap-left .onetap-text .onetap-title span', text: languageData.multiFunctionalFeature.epilepsySafeMode.title },
				{ selector: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-epilepsy-safe-mode .onetap-left .onetap-text .onetap-desc span', text: languageData.multiFunctionalFeature.epilepsySafeMode.desc },
				{ selectorOn: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-epilepsy-safe-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', on: languageData.multiFunctionalFeature.epilepsySafeMode.on },
				{ selectorOff: 'nav.onetap-accessibility .onetap-container .onetap-accessibility-settings .onetap-multi-functional-feature .onetap-box-functions .onetap-box-epilepsy-safe-mode .onetap-right .onetap-toggle-container .label-mode-switch .label-mode-switch-inner', off: languageData.multiFunctionalFeature.epilepsySafeMode.off },

				// Content.
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-bigger-text .onetap-title h3', text: languageData.content.biggerText },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-cursor .onetap-title h3', text: languageData.content.cursor },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-line-height .onetap-title h3', text: languageData.content.lineHeight },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-letter-spacing .onetap-title h3', text: languageData.content.letterSpacing },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-readable-font .onetap-title h3', text: languageData.content.readableFont },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-dyslexic-font .onetap-title h3', text: languageData.content.dyslexicFont },

				// Content Bottom.
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-text-align .onetap-title h3', text: languageData.contentBottom.textAlign },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-text-magnifier .onetap-title h3', text: languageData.contentBottom.textMagnifier },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-highlight-links .onetap-title h3', text: languageData.contentBottom.highlightLinks },

				// Colors.
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-invert-colors .onetap-title h3', text: languageData.colors.invertColors },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-brightness .onetap-title h3', text: languageData.colors.brightness },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-contrast .onetap-title h3', text: languageData.colors.contrast },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-grayscale .onetap-title h3', text: languageData.colors.grayscale },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-saturation .onetap-title h3', text: languageData.colors.saturation },

				// Orientation.
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-reading-line .onetap-title h3', text: languageData.orientation.readingLine },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-keyboard-navigation .onetap-title h3', text: languageData.orientation.keyboardNavigation },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-highlight-titles .onetap-title h3', text: languageData.orientation.highlightTitles },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-reading-mask .onetap-title h3', text: languageData.orientation.readingMask },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-hide-images .onetap-title h3', text: languageData.orientation.hideImages },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-highlight-all .onetap-title h3', text: languageData.orientation.highlightAll },

				// Content Bottom.
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-read-page .onetap-title h3', text: languageData.orientationBottom.readPage },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-mute-sounds .onetap-title h3', text: languageData.orientationBottom.muteSounds },
				{ selector: 'nav.onetap-accessibility .onetap-features .onetap-stop-animations .onetap-title h3', text: languageData.orientationBottom.stopAnimations },

				// Divider.
				{ selector: 'nav.onetap-accessibility .onetap-divider-separator .onetap-content', text: languageData.divider.content },
				{ selector: 'nav.onetap-accessibility .onetap-divider-separator .onetap-colors', text: languageData.divider.colors },
				{ selector: 'nav.onetap-accessibility .onetap-divider-separator .onetap-orientation', text: languageData.divider.navigation },

				// ResetSettings.
				{ selector: 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-reset-settings span', text: languageData.resetSettings },

				// Footer.
				{ selector: 'nav.onetap-accessibility .onetap-footer-bottom .onetap-icon-list-text', text: languageData.footer.accessibilityStatement },
				{ selector: 'nav.onetap-accessibility footer.onetap-footer-bottom .onetap-divider-container .onetap-divider__text', text: languageData.footer.version },
			];

			// Update each element with the corresponding text
			updates.forEach( ( update ) => {
				$( update.selector ).text( update.text );
			} );
		}
	}

	// Updates the font-size of elements except for the excluded selectors
	function accessibilityOnetapUpdateHeadingFontSize( heading, excludedSelectors, fontSize, activeBorderValue ) {
		$( '*' ).not( excludedSelectors ).each( function() {
			// Get the current inline style of the element, or use an empty string if none exists
			let currentStyle = $( this ).attr( 'style' ) || '';

			if ( 0 === activeBorderValue ) {
				// Remove the font-size if activeBorderValue is 0
				currentStyle = currentStyle.replace( /font-size:\s*[^;]+;?/, '' );
			} else if ( [ 1, 2, 3 ].includes( activeBorderValue ) ) {
				// Check if the element has a style attribute and if it ends with a semicolon
				if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
					currentStyle += ';';
				}

				// Check if 'font-size' is already defined in the style
				if ( /font-size:\s*[^;]+;?/.test( currentStyle ) ) {
					// If it exists, replace the existing font-size with the new value
					currentStyle = currentStyle.replace( /font-size:\s*[^;]+;?/, 'font-size: ' + fontSize );
				} else {
					// If font-size is not present, append it to the style attribute
					currentStyle += ' font-size: ' + fontSize;
				}
			}

			// Trim any extra spaces and ensure there's no trailing space
			currentStyle = currentStyle.trim();

			// Set the updated style attribute back to the element
			$( this ).attr( 'style', currentStyle );
		} );
	}

	// Updates the letter-spacing of elements except for the excluded selectors
	function accessibilityOnetapUpdateLetterSpacing( letter, excludedSelectors, letterSpacing, activeBorderValue ) {
		$( '*' ).not( excludedSelectors ).each( function() {
			// Get the current inline style of the element, or use an empty string if none exists
			let currentStyle = $( this ).attr( 'style' ) || '';

			if ( 0 === activeBorderValue ) {
				// Remove the letter-spacing if activeBorderValue is 0
				currentStyle = currentStyle.replace( /letter-spacing:\s*[^;]+;?/, '' );
			} else if ( [ 1, 2, 3 ].includes( activeBorderValue ) ) {
				// Check if the element has a style attribute and if it ends with a semicolon
				if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
					currentStyle += ';';
				}

				// Check if 'letter-spacing' is already defined in the style
				if ( /letter-spacing:\s*[^;]+;?/.test( currentStyle ) ) {
					// If it exists, replace the existing letter-spacing with the new value
					currentStyle = currentStyle.replace( /letter-spacing:\s*[^;]+;?/, 'letter-spacing: ' + letterSpacing );
				} else {
					// If letter-spacing is not present, append it to the style attribute
					currentStyle += ' letter-spacing: ' + letterSpacing;
				}
			}

			// Trim any extra spaces and ensure there's no trailing space
			currentStyle = currentStyle.trim();

			// Set the updated style attribute back to the element
			$( this ).attr( 'style', currentStyle );
		} );
	}

	// This function adjusts the text size based on the 'biggerText'
	function accessibilityOnetapBiggerText( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'bigger-text' ] ) {
			return;
		}

		// Check if the key is 'fontSize'. If it is, the function will proceed with font size adjustments.
		if ( 'biggerText' === key ) {
			let increasePercent = 0;

			if ( 1 === activeBorderValue ) {
				increasePercent = 0.25;
			} else if ( 2 === activeBorderValue ) {
				increasePercent = 0.5;
			} else if ( 3 === activeBorderValue ) {
				increasePercent = 0.75;
			}

			// General
			const validTags = [
				'p', 'span', 'a', 'li', 'td', 'th', 'label', 'button', 'input', 'textarea',
				'strong', 'em', 'b', 'i', 'u', 'small', 'time', 'code', 'pre',
				'blockquote', 'cite', 'abbr', 'address', 'q', 'dt', 'dd',
				'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
				'mark', 'sup', 'sub', 'del', 'ins', 's',
				'kbd', 'samp', 'var',
				'legend', 'figcaption', 'summary', 'body',
			];

			$( 'body, body *' ).each( function() {
				const el = this;
				const tag = el.tagName ? el.tagName.toLowerCase() : '';

				// Skip if tag is not in validTags
				if ( ! validTags.includes( tag ) ) {
					return;
				}

				// Skip elements in accessibilityOnetapSkipElements if needed
				if ( $( this ).is( accessibilityOnetapSkipElements ) ) {
					return;
				}

				// Skip <li> elements that are inside another <li>.
				if ( tag === 'li' && $( this ).parents( 'li' ).length > 0 ) {
					return;
				}

				const computedStyle = window.getComputedStyle( el );

				const currentFontSize = parseFloat( computedStyle.fontSize );

				if ( ! el.dataset.originalFontSize ) {
					el.dataset.originalFontSize = currentFontSize;
				}

				const baseFontSize = parseFloat( el.dataset.originalFontSize );
				let newStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Reset to default
					newStyle = newStyle.replace( /font-size:\s*[^;]+;?/, '' );
				} else if ( baseFontSize ) {
					const newFontSize = ( baseFontSize * ( 1 + increasePercent ) ).toFixed( 2 );
					const fontSizeStr = `font-size: ${ newFontSize }px !important;`;

					if ( /font-size:\s*[^;]+;?/.test( newStyle ) ) {
						newStyle = newStyle.replace( /font-size:\s*[^;]+;?/, fontSizeStr );
					} else {
						newStyle += ( newStyle.trim().endsWith( ';' ) ? ' ' : '; ' ) + fontSizeStr;
					}
				}

				$( this ).attr( 'style', newStyle.trim() );
			} );
		}
	}

	// This function modifies the cursor size by adding and removing classes
	function accessibilityOnetapCursor( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules.cursor ) {
			return;
		}

		// Check if the key is 'Cursor'. If it is, the function will proceed with font size adjustments.
		if ( 'cursor' === key ) {
			if ( 1 === activeBorderValue ) {
				// Add the class to the root <html> element
				$( 'html' ).addClass( 'onetap-cursor-feature1' );
				$( 'html' ).removeClass( 'onetap-cursor-feature2' );
				$( 'html' ).removeClass( 'onetap-cursor-feature3' );
			} else if ( 2 === activeBorderValue ) {
				// Add the class to the root <html> element
				$( 'html' ).removeClass( 'onetap-cursor-feature1' );
				$( 'html' ).addClass( 'onetap-cursor-feature2' );
				$( 'html' ).removeClass( 'onetap-cursor-feature3' );
			} else if ( 3 === activeBorderValue ) {
				// Add the class to the root <html> element
				$( 'html' ).removeClass( 'onetap-cursor-feature1' );
				$( 'html' ).removeClass( 'onetap-cursor-feature2' );
				$( 'html' ).addClass( 'onetap-cursor-feature3' );
			} else {
				// Add the class to the root <html> element
				$( 'html' ).removeClass( 'onetap-cursor-feature1' );
				$( 'html' ).removeClass( 'onetap-cursor-feature2' );
				$( 'html' ).removeClass( 'onetap-cursor-feature3' );
			}
		}
	}

	// This function adjusts the line height based on the 'lineHeight'
	function accessibilityOnetapLineHeight( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'line-height' ] ) {
			return;
		}

		// Check if the key is 'lineHeight'. If it is, the function will proceed with line height adjustments.
		if ( 'lineHeight' === key ) {
			let increasePercent = 0;

			if ( 1 === activeBorderValue ) {
				increasePercent = 0.25;
			} else if ( 2 === activeBorderValue ) {
				increasePercent = 0.5;
			} else if ( 3 === activeBorderValue ) {
				increasePercent = 0.75;
			}

			// General
			const validTags = [
				'p', 'span', 'a', 'li', 'td', 'th', 'label', 'button', 'input', 'textarea',
				'strong', 'em', 'b', 'i', 'u', 'small', 'time', 'code', 'pre',
				'blockquote', 'cite', 'abbr', 'address', 'q', 'dt', 'dd',
				'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
				'mark', 'sup', 'sub', 'del', 'ins', 's',
				'kbd', 'samp', 'var',
				'legend', 'figcaption', 'summary', 'body',
			];

			$( 'body, body *' ).each( function() {
				const el = this;
				const tag = el.tagName ? el.tagName.toLowerCase() : '';

				// Skip if tag is not in validTags
				if ( ! validTags.includes( tag ) ) {
					return;
				}

				// Skip elements in accessibilityOnetapSkipElements if needed
				if ( $( this ).is( accessibilityOnetapSkipElements ) ) {
					return;
				}

				// Skip <li> elements that are inside another <li>.
				if ( tag === 'li' && $( this ).parents( 'li' ).length > 0 ) {
					return;
				}

				const computedStyle = window.getComputedStyle( el );

				const currentLineHeight = parseFloat( computedStyle.lineHeight );

				if ( ! el.dataset.originalLineHeight ) {
					el.dataset.originalLineHeight = currentLineHeight;
				}

				const baseLineHeight = parseFloat( el.dataset.originalLineHeight );
				let newStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Reset to default
					newStyle = newStyle.replace( /line-height:\s*[^;]+;?/, '' );
				} else if ( baseLineHeight ) {
					const newLineHeight = ( baseLineHeight * ( 1 + increasePercent ) ).toFixed( 2 );
					const lineHeightStr = `line-height: ${ newLineHeight }px !important;`;

					if ( /line-height:\s*[^;]+;?/.test( newStyle ) ) {
						newStyle = newStyle.replace( /line-height:\s*[^;]+;?/, lineHeightStr );
					} else {
						newStyle += ( newStyle.trim().endsWith( ';' ) ? ' ' : '; ' ) + lineHeightStr;
					}
				}

				$( this ).attr( 'style', newStyle.trim() );
			} );
		}
	}

	// This function adjusts the letter spacing based on the 'letterSpacing'
	function accessibilityOnetapLetterSpacing( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'letter-spacing' ] ) {
			return;
		}

		// Check if the key is 'letterSpacing'. If it is, the function will proceed with font size adjustments.
		if ( 'letterSpacing' === key ) {
			let letterSpacing = null;

			// Determine letterSpacing value based on activeBorderValue
			if ( 1 === activeBorderValue ) {
				letterSpacing = '1px !important;';
			} else if ( 2 === activeBorderValue ) {
				letterSpacing = '3px !important;';
			} else if ( 3 === activeBorderValue ) {
				letterSpacing = '5px !important;';
			} else {
				letterSpacing = null;
			}

			// General
			$( '*' ).not( accessibilityOnetapSkipElements ).each( function() {
				// Get the current inline style of the element, or use an empty string if none exists
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Remove the letter-spacing if activeBorderValue is 0
					currentStyle = currentStyle.replace( /letter-spacing:\s*[^;]+;?/, '' );
				} else if ( 1 === activeBorderValue || 2 === activeBorderValue || 3 === activeBorderValue ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					// Check if 'letter-spacing' is already defined in the style
					if ( /letter-spacing:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing letter-spacing with the new value
						currentStyle = currentStyle.replace( /letter-spacing:\s*[^;]+;?/, 'letter-spacing: ' + letterSpacing );
					} else {
						// If letter-spacing is not present, append it to the style attribute
						currentStyle += ' letter-spacing: ' + letterSpacing;
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );

			// Call the function for each heading type
			accessibilityOnetapUpdateLetterSpacing( null, accessibilityOnetapSkipElements, letterSpacing, activeBorderValue );
		}
	}

	// This function adjusts the readablefont based on the 'readableFont'
	function accessibilityOnetapReadableFont( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'readable-font' ] ) {
			return;
		}

		// Check if the key is 'readableFont'. If it is, the function will proceed with font size adjustments.
		if ( 'readableFont' === key ) {
			// Update style for all elements except specific ones
			$( '*' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( ! accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( accessibilityOnetapGetData().dyslexicFont ) {
						currentStyle = currentStyle.replace( 'font-family: Roboto, sans-serif !important;', 'font-family: OpenDyslexic, sans-serif !important;' );
					} else {
						// Remove the font-family if accessibilityDataKey is 0
						currentStyle = currentStyle.replace( /font-family:\s*[^;]+;?/, '' );
					}
				} else if ( accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /font-family:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing font-family with the new value
						currentStyle = currentStyle.replace( /font-family:\s*[^;]+;?/, 'font-family: Roboto, sans-serif !important;' );
					} else {
						// If font-family is not present, append it to the style attribute
						currentStyle += ' font-family: Roboto, sans-serif !important;';
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the dyslexicfont based on the 'dyslexicFont'
	function accessibilityOnetapDyslexicFont( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'dyslexic-font' ] ) {
			return;
		}

		// Check if the key is 'dyslexicFont'. If it is, the function will proceed with font size adjustments.
		if ( 'dyslexicFont' === key && ! accessibilityOnetapGetData().readableFont ) {
			// Update style for all elements except specific ones
			$( '*' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( ! accessibilityDataKey ) {
					// Remove the font-family if accessibilityDataKey is 0
					currentStyle = currentStyle.replace( /font-family:\s*[^;]+;?/, '' );
				} else if ( accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /font-family:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing font-family with the new value
						currentStyle = currentStyle.replace( /font-family:\s*[^;]+;?/, 'font-family: OpenDyslexic, sans-serif !important;' );
					} else {
						// If font-family is not present, append it to the style attribute
						currentStyle += ' font-family: OpenDyslexic, sans-serif !important;';
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the text align based on the 'textAlign'
	function accessibilityOnetapTextAlign( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'text-align' ] ) {
			return;
		}

		// Check if the key is 'textAlign'. If it is, the function will proceed with font size adjustments.
		if ( 'textAlign' === key ) {
			let textAlign = null;

			// Determine textAlign value based on activeBorderValue
			if ( 1 === activeBorderValue ) {
				textAlign = 'left !important;';
			} else if ( 2 === activeBorderValue ) {
				textAlign = 'center !important;';
			} else if ( 3 === activeBorderValue ) {
				textAlign = 'right !important;';
			} else {
				textAlign = null;
			}

			// Update style for all elements except specific ones
			$( '*' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Remove the text-align if activeBorderValue is 0
					currentStyle = currentStyle.replace( /text-align:\s*[^;]+;?/, '' );
				} else if ( 1 === activeBorderValue || 2 === activeBorderValue || 3 === activeBorderValue ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /text-align:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing text-align with the new value
						currentStyle = currentStyle.replace( /text-align:\s*[^;]+;?/, 'text-align: ' + textAlign );
					} else {
						// If text-align is not present, append it to the style attribute
						currentStyle += ' text-align: ' + textAlign;
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the text magnifier based on the 'textMagnifier'
	function accessibilityOnetapTextMagnifier( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'text-magnifier' ] ) {
			return;
		}

		// Check if the key is 'textMagnifier'. If it is, the function will proceed with font size adjustments.
		if ( 'textMagnifier' === key ) {
			// Update style for all elements except specific ones
			if ( ! accessibilityDataKey ) {
				$( '.onetap-markup-text-magnifier' ).hide();
				$( document ).off( 'mousemove' );
			} else if ( accessibilityDataKey ) {
				$( document ).on( 'mousemove', function( event ) {
					const windowWidth = $( window ).width();
					const windowHeight = $( window ).height();

					// Get element position
					const hoveredElement = document.elementFromPoint( event.clientX, event.clientY );

					// Skip if hovered element has class 'onetap-markup-text-magnifier'
					if ( $( hoveredElement ).hasClass( 'onetap-markup-text-magnifier' ) ) {
						return; // Skip further execution
					}

					// Check hovered element position
					if ( hoveredElement ) {
						const $this = $( hoveredElement ); // Convert the DOM element to a jQuery object
						const text = $this.contents().filter( function() {
							return this.nodeType === 3; // Filter only text nodes
						} ).text().trim(); // Get and trim text from the direct text nodes

						// Add a title only if the element has direct text and no child elements
						if ( text ) {
							$( '.onetap-markup-text-magnifier' ).text( text );
							$( '.onetap-markup-text-magnifier' ).show();
						} else {
							$( '.onetap-markup-text-magnifier' ).text( '' );
							$( '.onetap-markup-text-magnifier' ).hide();
						}
					}

					// Determine quadrant and calculate position
					if ( event.pageX < windowWidth / 2 && event.pageY < windowHeight / 2 ) {
						// Left Top -> Right Bottom
						$( '.onetap-markup-text-magnifier' ).css( {
							left: event.pageX + 15 + 'px',
							top: event.pageY + 30 + 'px',
						} );
					} else if ( event.pageX >= windowWidth / 2 && event.pageY < windowHeight / 2 ) {
						// Right Top -> Left Bottom
						$( '.onetap-markup-text-magnifier' ).css( {
							left: event.pageX - 115 + 'px',
							top: event.pageY + 30 + 'px',
						} );
					} else if ( event.pageX < windowWidth / 2 && event.pageY >= windowHeight / 2 ) {
						// Left Bottom -> Right Top
						$( '.onetap-markup-text-magnifier' ).css( {
							left: event.pageX + 15 + 'px',
							top: event.pageY - 115 + 'px',
						} );
					} else {
						// Right Bottom -> Left Top
						$( '.onetap-markup-text-magnifier' ).css( {
							left: event.pageX - 115 + 'px',
							top: event.pageY - 115 + 'px',
						} );
					}
				} );
			}
		}
	}

	// This function adjusts the highlight links based on the 'highlightLinks'
	function accessibilityOnetapHighlightLinks( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'highlight-links' ] ) {
			return;
		}

		// Check if the key is 'highlightLinks'. If it is, the function will proceed with font size adjustments.
		if ( 'highlightLinks' === key ) {
			// Update style for all elements except specific ones
			$( 'a' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( ! accessibilityDataKey ) {
					// Remove the background and color if accessibilityDataKey is 0
					currentStyle = currentStyle.replace( /background:\s*[^;]+;?/, '' );
					currentStyle = currentStyle.replace( /color:\s*[^;]+;?/, '' );
				} else if ( accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					// Handle background
					if ( /background:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing background with the new value
						currentStyle = currentStyle.replace( /background:\s*[^;]+;?/, 'background: #f7ff00 !important;' );
					} else {
						// If background is not present, append it to the style attribute
						currentStyle += ' background: #f7ff00 !important;';
					}

					// Handle color
					if ( /color:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing color with the new value
						currentStyle = currentStyle.replace( /color:\s*[^;]+;?/, 'color: #000 !important;' );
					} else {
						// If color is not present, append it to the style attribute
						currentStyle += ' color: #000 !important;';
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the invert colors based on the 'invertColors'
	function accessibilityOnetapInvertColors( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'invert-colors' ] ) {
			return;
		}

		// Check if the key is 'invertColors'. If it is, the function will proceed with font size adjustments.
		if ( 'invertColors' === key ) {
			let invertColors = null;

			// Determine invertColors value based on activeBorderValue
			if ( 1 === activeBorderValue ) {
				invertColors = 'invert(70%) !important;';
			} else if ( 2 === activeBorderValue ) {
				invertColors = 'invert(85%) !important;';
			} else if ( 3 === activeBorderValue ) {
				invertColors = 'invert(100%) !important;';
			} else {
				invertColors = null;
			}

			// Update style for all elements except specific ones
			$( 'html, img' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Remove the filter if activeBorderValue is 0
					currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, '' );
				} else if ( 1 === activeBorderValue || 2 === activeBorderValue || 3 === activeBorderValue ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /filter:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing filter with the new value
						currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, 'filter: ' + invertColors );
					} else {
						// If filter is not present, append it to the style attribute
						currentStyle += ' filter: ' + invertColors;
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the brightness based on the 'brightness'
	function accessibilityOnetapBrightness( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules.brightness ) {
			return;
		}

		// Check if the key is 'brightness'. If it is, the function will proceed with font size adjustments.
		if ( 'brightness' === key ) {
			let brightness = null;

			// Determine brightness value based on activeBorderValue
			if ( 1 === activeBorderValue ) {
				brightness = 'brightness(50%) !important;';
			} else if ( 2 === activeBorderValue ) {
				brightness = 'brightness(80%) !important;';
			} else if ( 3 === activeBorderValue ) {
				brightness = 'brightness(110%) !important;';
			} else {
				brightness = null;
			}

			// Update style for all elements except specific ones
			$( 'html' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Remove the filter if activeBorderValue is 0
					currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, '' );
				} else if ( 1 === activeBorderValue || 2 === activeBorderValue || 3 === activeBorderValue ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /filter:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing filter with the new value
						currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, 'filter: ' + brightness );
					} else {
						// If filter is not present, append it to the style attribute
						currentStyle += ' filter: ' + brightness;
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the contrast based on the 'contrast'
	function accessibilityOnetapContrast( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules.contrast ) {
			return;
		}

		// Check if the key is 'contrast'. If it is, the function will proceed with font size adjustments.
		if ( 'contrast' === key ) {
			let contrast = null;

			// Determine contrast value based on activeBorderValue
			if ( 1 === activeBorderValue ) {
				contrast = 'contrast(50%) !important;';
			} else if ( 2 === activeBorderValue ) {
				contrast = 'contrast(80%) !important;';
			} else if ( 3 === activeBorderValue ) {
				contrast = 'contrast(110%) !important;';
			} else {
				contrast = null;
			}

			// Update style for all elements except specific ones
			$( 'html' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Remove the filter if activeBorderValue is 0
					currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, '' );
				} else if ( 1 === activeBorderValue || 2 === activeBorderValue || 3 === activeBorderValue ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /filter:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing filter with the new value
						currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, 'filter: ' + contrast );
					} else {
						// If filter is not present, append it to the style attribute
						currentStyle += ' filter: ' + contrast;
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the grayscale based on the 'grayscale'
	function accessibilityOnetapGrayscale( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules.grayscale ) {
			return;
		}

		// Check if the key is 'grayscale'. If it is, the function will proceed with font size adjustments.
		if ( 'grayscale' === key ) {
			let grayscale = null;

			// Determine grayscale value based on activeBorderValue
			if ( 1 === activeBorderValue ) {
				grayscale = 'grayscale(33%) !important;';
			} else if ( 2 === activeBorderValue ) {
				grayscale = 'grayscale(66%) !important;';
			} else if ( 3 === activeBorderValue ) {
				grayscale = 'grayscale(100%) !important;';
			} else {
				grayscale = null;
			}

			// Update style for all elements except specific ones
			$( 'html' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Remove the filter if activeBorderValue is 0
					currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, '' );
				} else if ( 1 === activeBorderValue || 2 === activeBorderValue || 3 === activeBorderValue ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /filter:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing filter with the new value
						currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, 'filter: ' + grayscale );
					} else {
						// If filter is not present, append it to the style attribute
						currentStyle += ' filter: ' + grayscale;
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the saturation based on the 'saturation'
	function accessibilityOnetapSaturation( key, activeBorderValue ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules.saturation ) {
			return;
		}

		// Check if the key is 'grayscale'. If it is, the function will proceed with font size adjustments.
		if ( 'saturation' === key ) {
			let saturation = null;

			// Determine saturation value based on activeBorderValue
			if ( 1 === activeBorderValue ) {
				saturation = 'saturate(150%) !important;';
			} else if ( 2 === activeBorderValue ) {
				saturation = 'saturate(200%) !important;';
			} else if ( 3 === activeBorderValue ) {
				saturation = 'saturate(250%) !important;';
			} else {
				saturation = null;
			}

			// Update style for all elements except specific ones
			$( 'html' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';

				if ( 0 === activeBorderValue ) {
					// Remove the filter if activeBorderValue is 0
					currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, '' );
				} else if ( 1 === activeBorderValue || 2 === activeBorderValue || 3 === activeBorderValue ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /filter:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing filter with the new value
						currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, 'filter: ' + saturation );
					} else {
						// If filter is not present, append it to the style attribute
						currentStyle += ' filter: ' + saturation;
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the reading line based on the 'readingLine'
	function accessibilityOnetapReadingLine( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'reading-line' ] ) {
			return;
		}

		// Check if the key is 'readingLine'. If it is, the function will proceed with font size adjustments.
		if ( 'readingLine' === key ) {
			// Update style for all elements except specific ones
			if ( ! accessibilityDataKey ) {
				$( '.onetap-markup-reading-line' ).removeClass( 'onetap-active' );
			} else if ( accessibilityDataKey ) {
				$( '.onetap-markup-reading-line' ).addClass( 'onetap-active' );
				$( document ).mousemove( function( event ) {
					// Get the X and Y coordinates of the mouse
					const mouseY = event.pageY; // Vertical position

					// Apply the Y position to the 'top' style of the '.onetap-markup-reading-line' element
					$( '.onetap-markup-reading-line' ).css( 'top', mouseY + 'px' );
				} );
			}
		}
	}

	// This function adjusts the keyboard navigation based on the 'keyboardNavigation'
	function accessibilityOnetapKeyboardNavigation( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'keyboard-navigation' ] ) {
			return;
		}

		// Ensure hotkeys library is loaded.
		if ( typeof hotkeys === 'undefined' ) {
			console.error( 'Hotkeys library is not loaded.' );
			return;
		}

		/**
		 * Initialize hotkeys.
		 * @param {Object} options - Configurable hotkey options.
		 */
		function hotKeys( options ) {
			/** Open popup by hotkey. */
			openInterface( options );

			/** Navigate to next/prev Menu. */
			focusElements( options.hotKeyMenu, 'nav, [role="navigation"]' );

			/** Navigate to next/prev Heading. */
			focusElements( options.hotKeyHeadings, 'h1, h2, h3, h4, h5, h6, [role="heading"]' );

			/** Navigate to next/prev Form. */
			focusElements( options.hotKeyForms, 'form:not([disabled])' );

			/** Navigate to next/prev Button. */
			focusElements( options.hotKeyButtons, 'button:not([disabled]), [role="button"]:not([disabled])' );

			/** Navigate to next/prev Graphic. */
			focusElements( options.hotKeyGraphics, 'img, picture, svg' );

			/**
			 * Enable/Disable controls by pressing Spacebar.
			 * @param {KeyboardEvent} e - The keyboard event object.
			 */
			document.body.onkeydown = function( e ) {
				const keyPressed = e.keyCode || e.charCode || e.which;

				/** Spacebar pressed. */
				if ( keyPressed === 32 ) {
					spacePressed( e );
				}
			};
		}

		/**
		 * Set focus on next/prev elements.
		 * @param {string} shortcutKey - Key for triggering focus.
		 * @param {string} selector    - Selector for target elements.
		 */
		function focusElements( shortcutKey, selector ) {
			// Register hotkeys for both forward and backward navigation
			hotkeys( shortcutKey + ', shift+' + shortcutKey, function( event, handler ) {
				// Check if Keyboard Navigation mode is active
				if ( ! document.body.classList.contains( 'onetap-keyboard-navigation' ) ) {
					console.warn( 'Keyboard Navigation is not active.' );
					return;
				}

				// Prevent the default browser behavior
				event.preventDefault();

				// Select all elements based on the provided selector
				let elements = document.querySelectorAll( selector );

				// Convert the NodeList to an array to allow filtering
				elements = Array.from( elements ).filter( ( element ) => {
				// Exclude elements that have the 'onetap-heading' class
					return ! element.classList.contains( 'onetap-heading' );
				} );

				// Iterate through all selected elements
				elements.forEach( ( element ) => {
				// Check if the element has the 'onetap-heading' class
					if ( element.classList.contains( 'onetap-heading' ) ) {
					// Remove the element from the DOM if it has the 'onetap-heading' class
						element.remove();
					}
				} );

				if ( ! elements.length ) {
					console.warn( `No elements found for selector: ${ selector }` );
					return;
				}

				// Determine navigation direction
				const forward = ! handler.key.startsWith( 'shift+' );

				// Get the currently focused element
				const currentIndex = Array.from( elements ).findIndex( ( el ) => el === document.activeElement );

				// Calculate the next index
				const nextIndex = forward
					? ( currentIndex + 1 ) % elements.length
					: ( currentIndex - 1 + elements.length ) % elements.length;

				// Set focus on the next element
				const nextElement = elements[ nextIndex ];
				nextElement.setAttribute( 'tabindex', '-1' ); // Ensure element is focusable
				nextElement.focus();

				// console.log( `Focused element index: ${ nextIndex }` );
			} );
		}

		/**
		 * Placeholder function for opening a popup interface.
		 * @param {Object} options - Configurable options for popup behavior.
		 */
		function openInterface( options ) {
			/* eslint no-unused-vars: "off" */
			// console.log( 'Open interface triggered with options:', options );
		}

		/**
		 * Handle Spacebar pressed for enabling/disabling controls.
		 * @param {Event} event - The keydown event.
		 */
		function spacePressed( event ) {
			event.preventDefault();
			// console.log( 'Spacebar pressed. Toggle controls here.' );
			// Implement your logic for enabling/disabling controls.
		}

		// Check if the key is 'highlightTitles'. If it is, the function will proceed with font size adjustments.
		if ( 'keyboardNavigation' === key ) {
			// Update style for all elements except specific ones
			if ( ! accessibilityDataKey ) {
				$( 'body' ).removeClass( 'onetap-keyboard-navigation' );
			} else if ( accessibilityDataKey ) {
				$( 'body' ).addClass( 'onetap-keyboard-navigation' );
				if ( typeof accessibilityHotkeys !== 'undefined' ) {
					hotKeys( accessibilityHotkeys );
				} else {
					console.error( 'accessibilityHotkeys object is undefined.' );
				}
			}
		}
	}

	// This function adjusts the highlight titles based on the 'highlightTitles'
	function accessibilityOnetapHighlightTitles( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'highlight-titles' ] ) {
			return;
		}

		// Check if the key is 'highlightTitles'. If it is, the function will proceed with font size adjustments.
		if ( 'highlightTitles' === key ) {
			// Update style for all elements except specific ones
			if ( ! accessibilityDataKey ) {
				$( 'body' ).removeClass( 'onetap-highlight-titles' );
			} else if ( accessibilityDataKey ) {
				$( 'body' ).addClass( 'onetap-highlight-titles' );
			}
		}
	}

	// This function adjusts the reading mask based on the 'readingmask'
	function accessibilityOnetapReadingMask( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'reading-mask' ] ) {
			return;
		}

		// Check if the key is 'readingmask'. If it is, the function will proceed with font size adjustments.
		if ( 'readingMask' === key ) {
			// Update style for all elements except specific ones
			if ( ! accessibilityDataKey ) {
				$( '.onetap-markup-reading-mask' ).removeClass( 'onetap-active' );
			} else if ( accessibilityDataKey ) {
				$( '.onetap-markup-reading-mask' ).addClass( 'onetap-active' );
				$( document ).mousemove( function( event ) {
					// Get the vertical position of the cursor within the viewport
					const cursorYPosition = event.clientY;

					// Define the height of the focus box (the area that remains visible)
					const focusHeight = 200;
					const halfFocusHeight = focusHeight / 2;

					// Calculate the top mask height, subtracting half of the focus area height
					const topMaskHeight = cursorYPosition - halfFocusHeight;

					// Calculate the bottom mask height based on the remaining space after the focus area
					const bottomMaskHeight = $( window ).height() - cursorYPosition - halfFocusHeight;

					// Apply the calculated height to the top mask
					$( '.onetap-markup-reading-mask.onetap-top' ).css( 'height', topMaskHeight + 'px' );

					// Apply the calculated height to the bottom mask
					$( '.onetap-markup-reading-mask.onetap-bottom' ).css( 'height', bottomMaskHeight + 'px' );
				} );
			}
		}
	}

	// This function adjusts the hide images based on the 'hideImages'
	function accessibilityOnetapHideImages( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'hide-images' ] ) {
			return;
		}

		// Check if the key is 'hideImages'. If it is, the function will proceed with font size adjustments.
		if ( 'hideImages' === key ) {
			// Update style for all elements except specific ones
			$( 'img' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';
				if ( ! accessibilityDataKey ) {
					// Remove the visibility if accessibilityDataKey is 0
					currentStyle = currentStyle.replace( /visibility:\s*[^;]+;?/, '' );
				} else if ( accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /visibility:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing visibility with the new value
						currentStyle = currentStyle.replace( /visibility:\s*[^;]+;?/, 'visibility: hidden !important;' );
					} else {
						// If visibility is not present, append it to the style attribute
						currentStyle += ' visibility: hidden !important;';
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );

			// Update style for all elements except specific ones
			$( '*' ).not( accessibilityOnetapSkipElements ).each( function() {
				let currentStyle = $( this ).attr( 'style' ) || '';
				if ( ! accessibilityDataKey ) {
					// Remove the background-size if accessibilityDataKey is 0
					currentStyle = currentStyle.replace( /background-size:\s*[^;]+;?/, '' );
				} else if ( accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle.trim() && ! /;$/.test( currentStyle.trim() ) ) {
						currentStyle += ';';
					}

					if ( /background-size:\s*[^;]+;?/.test( currentStyle ) ) {
						// If it exists, replace the existing background-size with the new value
						currentStyle = currentStyle.replace( /background-size:\s*[^;]+;?/, 'background-size: 0 0 !important;' );
					} else {
						// If background-size is not present, append it to the style attribute
						currentStyle += ' background-size: 0 0 !important;';
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle = currentStyle.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle );
			} );
		}
	}

	// This function adjusts the highlight all based on the 'highlightAll'
	function accessibilityOnetapHighlightAll( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'highlight-all' ] ) {
			return;
		}

		// Check if the key is 'highlightAll'. If it is, the function will proceed with font size adjustments.
		if ( 'highlightAll' === key ) {
			// Update style for all elements except specific ones
			if ( ! accessibilityDataKey ) {
				$( 'body' ).removeClass( 'onetap-highlight-all' );
			} else if ( accessibilityDataKey ) {
				$( 'body' ).addClass( 'onetap-highlight-all' );
			}
		}
	}

	// This function adjusts the read page based on the 'readPage'
	function accessibilityOnetapReadPage( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'read-page' ] ) {
			return;
		}

		// Check if the key is 'readPage'. If it is, the function will proceed with font size adjustments.
		if ( 'readPage' === key ) {
			// Update style for all elements except specific ones
			if ( accessibilityDataKey ) {
				let currentlySpeakingElement = null; // To track the currently highlighted element

				// Event delegation to capture clicks on any element except some form elements
				$( document ).on( 'click', '*', function( event ) {
					// Prevent bubbling to avoid selecting the whole page's text
					event.stopPropagation();

					if ( ! accessibilityOnetapGetData()[ key ] ) {
						return;
					}

					// Ignore input, textarea, and select elements
					if ( $( this ).is( 'input, textarea, select, .onetap-plugin-onetap, .onetap-plugin-onetap *, .onetap-toggle, .onetap-toggle *' ) ) {
						return;
					}

					// Get only the direct text from the clicked element
					const textToSpeak = $( this ).clone() // Clone the element to manipulate
						.children() // Remove the children elements
						.remove() // Remove child elements
						.end() // Go back to the original element
						.text().trim(); // Get the text and trim any spaces

					// Check if the text is not empty
					if ( textToSpeak.length > 0 ) {
						// Stop any ongoing speech before continuing
						window.speechSynthesis.cancel();

						// Move the onetap-highlight to the new element
						if ( currentlySpeakingElement ) {
							$( currentlySpeakingElement ).removeClass( 'onetap-highlight' ); // Remove onetap-highlight from the previous element
						}
						$( this ).addClass( 'onetap-highlight' ); // Add onetap-highlight to the newly clicked element
						currentlySpeakingElement = this; // Store the currently highlighted element

						// Use Web Speech API to convert the text to speech
						const speech = new SpeechSynthesisUtterance( textToSpeak );

						// Remove onetap-highlight when speech ends
						speech.onend = function() {
							$( currentlySpeakingElement ).removeClass( 'onetap-highlight' );
							currentlySpeakingElement = null; // Reset the highlighted element
						};

						window.speechSynthesis.speak( speech );
					}

					// Prevent the default action if the clicked element is a link
					// if ( $( this ).is( 'a' ) ) {
					// 	event.preventDefault();
					// }
				} );
			}
		}
	}

	// This function adjusts the mute sounds based on the 'muteSounds'
	function accessibilityOnetapMuteSounds( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'mute-sounds' ] ) {
			return;
		}

		// Check if the key is 'muteSounds'.
		if ( 'muteSounds' === key ) {
			if ( ! accessibilityDataKey ) {
				// Unmute all video and audio
				$( 'audio, video' ).not( accessibilityOnetapSkipElements ).each( function() {
					$( this ).prop( 'muted', false );
				} );

				// Unmute all YouTube iframes
				$( 'iframe[src*="youtube.com"]' ).each( function() {
					const src = $( this ).attr( 'src' );
					if ( src.includes( 'mute=1' ) ) {
						$( this ).attr( 'src', src.replace( 'mute=1', '' ) );
					}
				} );
			} else if ( accessibilityDataKey ) {
				// Mute all video and audio
				$( 'audio, video' ).not( accessibilityOnetapSkipElements ).each( function() {
					$( this ).prop( 'muted', true );
				} );

				// Mute all YouTube iframes
				$( 'iframe[src*="youtube.com"]' ).each( function() {
					const src = $( this ).attr( 'src' );
					if ( ! src.includes( 'mute=1' ) ) {
						$( this ).attr( 'src', src + ( src.includes( '?' ) ? '&' : '?' ) + 'mute=1' );
					}
				} );
			}
		}
	}

	// This function adjusts the stop animations based on the 'stopAnimations'
	function accessibilityOnetapStopAnimations( key, accessibilityDataKey ) {
		// if value off, return.
		if ( 'off' === accessibilityOnetapAjaxObject.showModules[ 'stop-animations' ] ) {
			return;
		}

		// Check if the key is 'stopAnimations'. If it is, the function will proceed with font size adjustments.
		if ( 'stopAnimations' === key ) {
			// Update style for all elements except specific ones
			$( '*' ).not( accessibilityOnetapSkipElements ).each( function() {
				// Transition.
				let currentStyle1 = $( this ).attr( 'style' ) || '';
				if ( ! accessibilityDataKey ) {
					// Remove the background and color if accessibilityDataKey is 0
					currentStyle1 = currentStyle1.replace( /transition:\s*[^;]+;?/, '' );
				} else if ( accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle1.trim() && ! /;$/.test( currentStyle1.trim() ) ) {
						currentStyle1 += ';';
					}

					if ( /transition:\s*[^;]+;?/.test( currentStyle1 ) ) {
						// If it exists, replace the existing transition with the new value
						currentStyle1 = currentStyle1.replace( /transition:\s*[^;]+;?/, 'transition: none !important;' );
					} else {
						// If transition is not present, append it to the style attribute
						currentStyle1 += ' transition: none !important;';
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle1 = currentStyle1.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle1 );

				// Animations.
				let currentStyle2 = $( this ).attr( 'style' ) || '';
				if ( ! accessibilityDataKey ) {
					// Remove the background and color if accessibilityDataKey is 0
					currentStyle2 = currentStyle2.replace( /animation:\s*[^;]+;?/, '' );
				} else if ( accessibilityDataKey ) {
					// Check if the element has a style attribute and if it ends with a semicolon
					if ( currentStyle2.trim() && ! /;$/.test( currentStyle2.trim() ) ) {
						currentStyle2 += ';';
					}

					if ( /animation:\s*[^;]+;?/.test( currentStyle2 ) ) {
						// If it exists, replace the existing animation with the new value
						currentStyle2 = currentStyle2.replace( /animation:\s*[^;]+;?/, 'animation: none !important;' );
					} else {
						// If animation is not present, append it to the style attribute
						currentStyle2 += ' animation: none !important;';
					}
				}

				// Trim any extra spaces and ensure there's no trailing space
				currentStyle2 = currentStyle2.trim();

				// Set the updated style attribute back to the element
				$( this ).attr( 'style', currentStyle2 );
			} );
		}
	}

	// List of accessibilityOnetapGetTlements and their keys
	const accessibilityOnetapGetTlements = [
		{ selector: '.onetap-bigger-text', key: 'biggerText' },
		{ selector: '.onetap-cursor', key: 'cursor' },
		{ selector: '.onetap-line-height', key: 'lineHeight' },
		{ selector: '.onetap-letter-spacing', key: 'letterSpacing' },
		{ selector: '.onetap-readable-font', key: 'readableFont' },
		{ selector: '.onetap-dyslexic-font', key: 'dyslexicFont' },
		{ selector: '.onetap-text-align', key: 'textAlign' },
		{ selector: '.onetap-text-magnifier', key: 'textMagnifier' },
		{ selector: '.onetap-highlight-links', key: 'highlightLinks' },
		{ selector: '.onetap-invert-colors', key: 'invertColors' },
		{ selector: '.onetap-brightness', key: 'brightness' },
		{ selector: '.onetap-contrast', key: 'contrast' },
		{ selector: '.onetap-grayscale', key: 'grayscale' },
		{ selector: '.onetap-saturation', key: 'saturation' },
		{ selector: '.onetap-reading-line', key: 'readingLine' },
		{ selector: '.onetap-keyboard-navigation', key: 'keyboardNavigation' },
		{ selector: '.onetap-highlight-titles', key: 'highlightTitles' },
		{ selector: '.onetap-reading-mask', key: 'readingMask' },
		{ selector: '.onetap-hide-images', key: 'hideImages' },
		{ selector: '.onetap-highlight-all', key: 'highlightAll' },
		{ selector: '.onetap-read-page', key: 'readPage' },
		{ selector: '.onetap-mute-sounds', key: 'muteSounds' },
		{ selector: '.onetap-stop-animations', key: 'stopAnimations' },
	];

	// Utility function to update class based on current value
	function accessibilityOnetapToggleLevelClass( $element, currentValue ) {
		const levels = [ 'onetap-lv1', 'onetap-lv2', 'onetap-lv3' ];
		$element.removeClass( levels.join( ' ' ) );

		if ( currentValue >= 1 && currentValue <= 3 ) {
			$element.addClass( levels[ currentValue - 1 ] );
		}
	}

	// Toggles the 'onetap-active' class on the provided element
	function toggleActiveClass( $element ) {
		$element.toggleClass( 'onetap-active ' );
	}

	// Utility function to handle click events
	let activeStagedValue = 0;
	function accessibilityOnetapHandleClick( $element, key, accessibilityData, useActiveBorder ) {
		$element.on( 'click', function() {
			accessibilityData = accessibilityOnetapGetData();
			if ( useActiveBorder ) {
				activeStagedValue = accessibilityData.activeBorders[ key ] = ( accessibilityData.activeBorders[ key ] + 1 ) % 4;
				accessibilityData[ key ] = activeStagedValue !== 0;

				accessibilityOnetapToggleLevelClass( $element, activeStagedValue );
				accessibilityOnetapBiggerText( key, activeStagedValue );
				accessibilityOnetapCursor( key, activeStagedValue );
				accessibilityOnetapLineHeight( key, activeStagedValue );
				accessibilityOnetapLetterSpacing( key, activeStagedValue );
				accessibilityOnetapTextAlign( key, activeStagedValue );
				accessibilityOnetapInvertColors( key, activeStagedValue );
				accessibilityOnetapBrightness( key, activeStagedValue );
				accessibilityOnetapContrast( key, activeStagedValue );
				accessibilityOnetapGrayscale( key, activeStagedValue );
				accessibilityOnetapSaturation( key, activeStagedValue );
			} else {
				accessibilityData[ key ] = ! accessibilityData[ key ];
				toggleActiveClass( $element, accessibilityData[ key ] );
				accessibilityOnetapReadableFont( key, accessibilityData[ key ] );
				accessibilityOnetapDyslexicFont( key, accessibilityData[ key ] );
				accessibilityOnetapTextMagnifier( key, accessibilityData[ key ] );
				accessibilityOnetapHighlightLinks( key, accessibilityData[ key ] );
				accessibilityOnetapReadingLine( key, accessibilityData[ key ] );
				accessibilityOnetapKeyboardNavigation( key, accessibilityData[ key ] );
				accessibilityOnetapHighlightTitles( key, accessibilityData[ key ] );
				accessibilityOnetapReadingMask( key, accessibilityData[ key ] );
				accessibilityOnetapHideImages( key, accessibilityData[ key ] );
				accessibilityOnetapHighlightAll( key, accessibilityData[ key ] );
				accessibilityOnetapReadPage( key, accessibilityData[ key ] );
				accessibilityOnetapMuteSounds( key, accessibilityData[ key ] );
				accessibilityOnetapStopAnimations( key, accessibilityData[ key ] );
			}

			localStorage.setItem( accessibilityOnetapLocalStorage, JSON.stringify( accessibilityData ) );
		} );
	}

	// Initialize functionality for multiple accessibilityOnetapGetTlements
	function accessibilityOnetapInitAccessibilityHandlers( accessibilityData ) {
		accessibilityOnetapGetTlements.forEach( ( { selector, key } ) => {
			const $element = $( `nav.onetap-accessibility.onetap-plugin-onetap .onetap-accessibility-settings ${ selector }` );
			if ( $element.length ) {
				// Use activeBorder for some keys, otherwise, just toggle true/false
				const useActiveBorder = ! [
					'readableFont',
					'dyslexicFont',
					'textMagnifier',
					'highlightLinks',
					'readingLine',
					'keyboardNavigation',
					'highlightTitles',
					'readingMask',
					'hideImages',
					'highlightAll',
					'readPage',
					'muteSounds',
					'stopAnimations',
				].includes( key );

				accessibilityOnetapHandleClick( $element, key, accessibilityData, useActiveBorder );
			}
		} );
	}

	// Handles the application of accessibility features on elements based on user settings
	function handleAccessibilityFeatures() {
		accessibilityOnetapGetTlements.forEach( ( { selector, key } ) => {
			const $element = $( `nav.onetap-accessibility.onetap-plugin-onetap .onetap-accessibility-settings ${ selector }` );
			if ( $element.length && accessibilityOnetapGetData()[ key ] !== undefined ) {
				const useActiveBorder = ! [
					'readableFont',
					'dyslexicFont',
					'textMagnifier',
					'highlightLinks',
					'readingLine',
					'keyboardNavigation',
					'highlightTitles',
					'readingMask',
					'hideImages',
					'highlightAll',
					'readPage',
					'muteSounds',
					'stopAnimations',
				].includes( key );

				if ( useActiveBorder ) {
					if ( accessibilityOnetapGetData().activeBorders[ key ] !== undefined ) {
						accessibilityOnetapToggleLevelClass( $element, accessibilityOnetapGetData().activeBorders[ key ] );
						if ( 0 !== accessibilityOnetapGetData().activeBorders[ key ] ) {
							accessibilityOnetapBiggerText( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapCursor( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapLineHeight( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapLetterSpacing( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapInvertColors( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapBrightness( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapContrast( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapGrayscale( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapSaturation( key, accessibilityOnetapGetData().activeBorders[ key ] );
							accessibilityOnetapTextAlign( key, accessibilityOnetapGetData().activeBorders[ key ] );
						}
					}
				} else if ( accessibilityOnetapGetData()[ key ] !== undefined ) {
					if ( accessibilityOnetapGetData()[ key ] !== undefined && accessibilityOnetapGetData()[ key ] ) {
						toggleActiveClass( $element, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapHideImages( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapReadableFont( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapDyslexicFont( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapHighlightTitles( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapHighlightAll( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapReadingLine( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapTextMagnifier( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapHighlightLinks( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapStopAnimations( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapReadPage( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapReadingMask( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapKeyboardNavigation( key, accessibilityOnetapGetData()[ key ] );
						accessibilityOnetapMuteSounds( key, accessibilityOnetapGetData()[ key ] );
					}
				}
			}
		} );

		// Initialize handlers
		accessibilityOnetapInitAccessibilityHandlers( accessibilityOnetapGetData() );
	}
	handleAccessibilityFeatures();

	// Reset settings
	$( document ).on( 'click', 'nav.onetap-accessibility.onetap-plugin-onetap .onetap-reset-settings span', function( event ) {
		event.stopPropagation(); // Ensure this doesn't trigger auto-close

		// Select all elements with the class .onetap-box-feature
		$( 'nav.onetap-accessibility.onetap-plugin-onetap .onetap-box-feature' ).each( function() {
			// Remove specified classes
			$( this ).removeClass( 'onetap-lv1 onetap-lv2 onetap-lv3 onetap-active' );
		} );

		// Check if the localStorage item exists
		if ( localStorage.getItem( accessibilityOnetapLocalStorage ) ) {
			// Parse the existing localStorage item
			const currentSettings = JSON.parse( localStorage.getItem( accessibilityOnetapLocalStorage ) );

			// Check if any of the specified values are true
			const hasActiveSettings =
				currentSettings.dynamicFeatureSet.visionImpairedMode ||
				currentSettings.dynamicFeatureSet.seizureSafeProfileMode ||
				currentSettings.dynamicFeatureSet.adhdFriendlyMode ||
				currentSettings.dynamicFeatureSet.blindnessMode ||
				currentSettings.dynamicFeatureSet.epilepsySafeMode ||
				currentSettings.biggerText ||
				currentSettings.cursor ||
				currentSettings.lineHeight ||
				currentSettings.letterSpacing ||
				currentSettings.readableFont ||
				currentSettings.dyslexicFont ||
				currentSettings.textAlign ||
				currentSettings.textMagnifier ||
				currentSettings.highlightLinks ||
				currentSettings.invertColors ||
				currentSettings.brightness ||
				currentSettings.contrast ||
				currentSettings.grayscale ||
				currentSettings.saturation ||
				currentSettings.readingLine ||
				currentSettings.keyboardNavigation ||
				currentSettings.highlightTitles ||
				currentSettings.readingMask ||
				currentSettings.hideImages ||
				currentSettings.highlightAll ||
				currentSettings.readPage ||
				currentSettings.muteSounds ||
				currentSettings.stopAnimations ||
				currentSettings.information.language;

			if ( currentSettings.textMagnifier ) {
				$( document ).off( 'mousemove' );
			}

			if ( hasActiveSettings ) {
				// Remove the 'onetap-active' class from all country flag images
				$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img' ).removeClass( 'onetap-active' );

				// Add the 'onetap-active' class to the image with the alt attribute matching the selected language
				$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img[alt="en"]' ).addClass( 'onetap-active' );

				// Remove the 'onetap-active' class from all country flag images
				$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img' ).removeClass( 'onetap-active' );

				// Add the 'onetap-active' class to the image with the alt attribute matching the selected language
				$( 'nav.onetap-accessibility .onetap-accessibility-settings .onetap-languages .onetap-icon img[alt="' + accessibilityOnetapAjaxObject.getSettings.language + '"]' ).addClass( 'onetap-active' );

				// Reset language
				accessibilityOnetapUpdateContentBasedOnLanguage( accessibilityOnetapAjaxObject.getSettings.language );

				// Remove localStorage item if any value is true
				localStorage.removeItem( accessibilityOnetapLocalStorage );

				// Create a new localStorage item with default values
				localStorage.setItem( accessibilityOnetapLocalStorage, JSON.stringify( accessibilityOnetapDefault ) );

				// Reset Mode Preset Toggle.
				const checkboxPresetToggle = [
					'#onetap-box-vision-impaired-mode',
					'#onetap-box-seizure-safe-profile',
					'#onetap-box-adhd-friendly-mode',
					'#onetap-box-blindness-mode',
					'#onetap-box-epilepsy-safe-mode',
				];

				checkboxPresetToggle.forEach( ( id ) => {
					const checkbox = document.querySelector( id );
					if ( checkbox ) {
						checkbox.checked = false;
					}
				} );

				// Remove the active class for the currently active preset mode.
				$( 'nav.onetap-accessibility.onetap-plugin-onetap .onetap-functional-feature' ).removeClass( 'onetap-active' );

				// Remove style inline
				$( '*' ).not( accessibilityOnetapSkipElements ).each( function() {
					let currentStyle = $( this ).attr( 'style' ) || '';

					// Reset (Bigger Text)
					currentStyle = currentStyle.replace( /font-size:\s*[^;]+;?/, '' );

					// Reset (Line Height)
					currentStyle = currentStyle.replace( /line-height:\s*[^;]+;?/, '' );

					// Reset (Letter Spacing)
					currentStyle = currentStyle.replace( /letter-spacing:\s*[^;]+;?/, '' );

					// Reset (Text Align)
					currentStyle = currentStyle.replace( /text-align:\s*[^;]+;?/, '' );

					// Reset (Readable Font & Dyslexic Font)
					currentStyle = currentStyle.replace( /font-family:\s*[^;]+;?/, '' );

					// Reset (Hide Images)
					currentStyle = currentStyle.replace( /background-size:\s*[^;]+;?/, '' );
					currentStyle = currentStyle.replace( /visibility:\s*[^;]+;?/, '' );

					// Reset (Stop Animations)
					currentStyle = currentStyle.replace( /transition:\s*[^;]+;?/, '' );
					currentStyle = currentStyle.replace( /animation:\s*[^;]+;?/, '' );

					// Trim any extra spaces and ensure there's no trailing space
					currentStyle = currentStyle.trim();

					// Set the updated style attribute back to the element
					$( this ).attr( 'style', currentStyle );
				} );

				// Remove style inline
				$( 'a' ).not( accessibilityOnetapSkipElements ).each( function() {
					let currentStyle = $( this ).attr( 'style' ) || '';

					// Reset (Highlight Links)
					currentStyle = currentStyle.replace( /background:\s*[^;]+;?/, '' );
					currentStyle = currentStyle.replace( /color:\s*[^;]+;?/, '' );

					// Trim any extra spaces and ensure there's no trailing space
					currentStyle = currentStyle.trim();

					// Set the updated style attribute back to the element
					$( this ).attr( 'style', currentStyle );
				} );

				// Remove style inline
				$( 'img' ).not( accessibilityOnetapSkipElements ).each( function() {
					let currentStyle = $( this ).attr( 'style' ) || '';

					// Reset (Hide Images)
					currentStyle = currentStyle.replace( /visibility:\s*[^;]+;?/, '' );

					// Trim any extra spaces and ensure there's no trailing space
					currentStyle = currentStyle.trim();

					// Set the updated style attribute back to the element
					$( this ).attr( 'style', currentStyle );
				} );

				// ============= Content =============

				// Reset (Cursor)
				$( 'html' ).removeClass( 'onetap-cursor-feature1' );
				$( 'html' ).removeClass( 'onetap-cursor-feature2' );
				$( 'html' ).removeClass( 'onetap-cursor-feature3' );

				// Reset (Highlight titles)
				$( 'body' ).removeClass( 'onetap-highlight-titles' );

				// Reset (Highlight all)
				$( 'body' ).removeClass( 'onetap-highlight-all' );

				// ============= Content Bottom =============

				// Reset (Text magnifier)
				$( '.onetap-markup-text-magnifier' ).hide();

				// ============= Colors =============

				$( 'html, img' ).not( accessibilityOnetapSkipElements ).each( function() {
					let currentStyle = $( this ).attr( 'style' ) || '';

					// Remove the filter if activeBorderValue is 0
					currentStyle = currentStyle.replace( /filter:\s*[^;]+;?/, '' );

					// Trim any extra spaces and ensure there's no trailing space
					currentStyle = currentStyle.trim();

					// Set the updated style attribute back to the element
					$( this ).attr( 'style', currentStyle );
				} );

				// ============= Orientation =============

				// Reset (Reading line)
				$( '.onetap-markup-reading-line' ).removeClass( 'onetap-active' );

				// Reset (Keyboard navigation)
				$( 'body' ).removeClass( 'onetap-keyboard-navigation' );

				// Reset (Reading mask)
				$( '.onetap-markup-reading-mask' ).removeClass( 'onetap-active' );

				// ============= Orientation bottom =============

				// Unmute all video and audio
				$( 'audio, video' ).not( accessibilityOnetapSkipElements ).each( function() {
					$( this ).prop( 'muted', false );
				} );

				// Unmute all YouTube iframes
				$( 'iframe[src*="youtube.com"]' ).each( function() {
					const src = $( this ).attr( 'src' );
					if ( src.includes( 'mute=1' ) ) {
						$( this ).attr( 'src', src.replace( 'mute=1', '' ) );
					}
				} );
			}
		} else {
			// Create localStorage item if it does not exist
			localStorage.setItem( accessibilityOnetapLocalStorage, JSON.stringify( accessibilityOnetapDefault ) );
		}
	} );
}( jQuery ) );
