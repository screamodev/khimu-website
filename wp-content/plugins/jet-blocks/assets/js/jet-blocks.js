( function( $, elementorFrontend, elementor ) {

	"use strict";

	var JetBlocks = {

		addedScripts: {},

		addedStyles: {},

		addedAssetsPromises: [],

		init: function() {

			var widgets = {
				'jet-nav-menu.default' : JetBlocks.navMenu,
				'jet-search.default' : JetBlocks.searchBox,
				'jet-auth-links.default' : JetBlocks.authLinks,
				'jet-hamburger-panel.default' : JetBlocks.hamburgerPanel,
				'jet-blocks-cart.default': JetBlocks.wooCard,
				'jet-register.default' : JetBlocks.userRegistration,
				'jet-reset.default' : JetBlocks.userResetPassword,
				'jet-login.default' : JetBlocks.userLogin
			};

			$.each( widgets, function( widget, callback ) {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			$( document )
				.on( 'click.jetBlocks', '.jet-search__popup-trigger', JetBlocks.searchPopupSwitch )
				.on( 'click.jetBlocks', '.jet-search__popup-close', JetBlocks.searchPopupSwitch );

			$( window ).on( 'jet-menu/ajax/frontend-init/before', function () {
				$( document.body ).trigger( 'wc_fragment_refresh' );
			} );

			elementorFrontend.hooks.addAction( 'frontend/element_ready/section', JetBlocks.setStickySection );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/column', JetBlocks.setStickySection );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/container', JetBlocks.setStickySection );

			$( JetBlocks.stickySection );
		},

		wooCard: function( $scope ) {

			if ( window.JetBlocksEditor &&  window.JetBlocksEditor.activeSection || JetBlocks.isEditMode() ) {
				let section = window.JetBlocksEditor.activeSection,
					isCart = -1 !== [ 'cart_list_style', 'cart_list_items_style', 'cart_buttons_style' ].indexOf( section );

				$( '.widget_shopping_cart_content' ).empty();
				$( document.body ).trigger( 'wc_fragment_refresh' );
			}

			var $target         =  $( '.jet-blocks-cart', $scope ),
				$toggle         = $( '.jet-blocks-cart__heading-link', $target ),
				settings        = $target.data( 'settings' ),
				firstMouseEvent = true,
				$htmlBody       = $( 'html, body' ),
				scrollOffset;
			

			if ( $target.hasClass( 'jet-blocks-cart--slide-out-layout' ) ) {
				$toggle.on( 'touchend', function( event ) {
					if( $htmlBody.css( 'overflow' ) == 'hidden' ){
						$htmlBody.css( { overflow: '', } );
					} else {
						$htmlBody.css( { overflow: 'hidden', } );
					}
				});
				$( '.jet-blocks-cart__close-button', $target ).on( 'touchend', function( event ) {
					$htmlBody.css( { overflow: '', } );
				} );	
			}

			if ( settings[ 'openMiniCartOnAdd' ] === 'yes' ) {
				$( document ).on( 'added_to_cart', function( event, fragments, cart_hash ) {
									if ( ! $target.hasClass( 'jet-cart-open' ) ) {
										$target.addClass( 'jet-cart-open' );
									}
								} );
				}

			switch ( settings['triggerType' ] ) {
				case 'hover':
					hoverType();
				break;
				case 'click':
					clickType();
				break;
			}
			
			$( '.jet-blocks-cart__close-button', $target ).on( 'click touchend', function( event ) {
				event.stopPropagation();
				if ( ! $target.hasClass( 'jet-cart-open-proccess' ) ) {
					$target.removeClass( 'jet-cart-open' );
				}
			} );

			$( document ).on( 'keydown', function( event ) {
				if ( event.key === 'Escape' || event.keyCode === 27 ) {
					if ( $target.hasClass( 'jet-cart-open' ) ) {
						$target.removeClass( 'jet-cart-open' );
					}
				}
			} );

			function hoverType() {

				if ( 'ontouchend' in window || 'ontouchstart' in window ) {
					$target.on( 'touchstart', function( event ) {
						scrollOffset = $( window ).scrollTop();
					} );
					
					$target.on( 'touchend', function( event ) {
						var $this = $( this );
					
						if ( $this.hasClass( 'jet-cart-open' ) ) {
							return;
						}
					
						event.preventDefault();
					
						if ( scrollOffset !== $( window ).scrollTop() ) {
							return false;
						}
					
						if ( $this.hasClass( 'jet-cart-open-proccess' ) ) {
							return;
						}
					
						setTimeout( function() {
							$this.addClass( 'jet-cart-open' );
						}, 10 );
					} );
					
					$( document ).on( 'touchend', function( event ) {
						if ( $( event.target ).closest( $target ).length ) {
							return;
						}
					
						if ( $target.hasClass( 'jet-cart-open' ) ) {
							$target.removeClass('jet-cart-open' );
						}
					} );
				} else {

					$target.on( 'mouseenter mouseleave', function( event ) {

						if ( ! $( this ).hasClass( 'jet-cart-open-proccess' ) && 'mouseenter' === event.type ) {
							$( this ).addClass( 'jet-cart-open' );
						}

						if ( ! $( this ).hasClass( 'jet-cart-open-proccess' ) && 'mouseleave' === event.type ) {
							$( this ).removeClass( 'jet-cart-open' );
						}
					} );
				}
			}

			function clickType() {
				$toggle.on( 'click', function( event ) {
					event.preventDefault();
					
					if ( ! $target.hasClass( 'jet-cart-open-proccess' ) ) {
						$target.toggleClass( 'jet-cart-open' );
					}
				});
			
				$( document ).on( 'click', function( event ) {
					if ( settings['closeOnClickOutside'] === 'yes' ) {
						if ( ! $( event.target ).closest( $target ).length && ! $( event.target ).closest( $toggle ).length ) {
							$target.removeClass( 'jet-cart-open' );
						}
					}
				});
			}
			
		},

		userRegistration: function( $scope ) {

			var $target            = $( '.jet-register', $scope ),
				pwStrongValidation = $( '.pw-validation', $target ),
				submitBtn          = $( 'button.jet-register__submit', $scope );

			if ( pwStrongValidation.length ) {
				JetBlocks.strongPasswordValidation( $scope, submitBtn );
			}

			JetBlocks.togglePasswordVisibility( $scope );

			JetBlocksTools.googleRecaptcha( $target );
		},

		userResetPassword: function( $scope ) {

			var $target            = $( '.jet-reset', $scope ),
				$form              = $( '.jet-reset__form', $scope ),
				pwStrongValidation = $( '.pw-validation', $target ),
				submitBtn          = $( 'button.jet-reset__button', $scope );

			if ( pwStrongValidation.length ) {
				JetBlocks.strongPasswordValidation( $scope, submitBtn );
			}

			JetBlocks.togglePasswordVisibility( $scope );

			JetBlocksTools.googleRecaptcha( $form );
		},

		userLogin: function( $scope ) {

			var $target = $( '#loginform', $scope );

			JetBlocks.togglePasswordVisibility( $scope );

			JetBlocksTools.googleRecaptcha( $target );
		},

		navMenu: function( $scope ) {

			if ( $scope.data( 'initialized' ) ) {
				return;
			}

			$scope.data( 'initialized', true );

			var hoverClass          = 'jet-nav-hover',
				hoverOutClass       = 'jet-nav-hover-out',
				mobileActiveClass   = 'jet-mobile-menu-active',
				currentDeviceMode   = elementorFrontend.getCurrentDeviceMode(),
				excludedDevices     = [ 'widescreen', 'desktop', 'laptop' ],
				availableDevices    = [ 'tablet_extra', 'tablet', 'mobile_extra', 'mobile' ],
				excludeCheck        = $.inArray( currentDeviceMode, excludedDevices ),
				mobileLayoutDevice  = undefined != $( '.jet-nav-wrap', $scope ).data( 'mobile-trigger-device' ) ? $( '.jet-nav-wrap', $scope ).data( 'mobile-trigger-device' ) : '',
				deviceStartIndex    = null,
				currentDeviceIndex  = availableDevices.indexOf( currentDeviceMode ),
				currentEventCheck   = ('ontouchend' in window ) ? 'touchend.jetNavMenu' : 'click.jetNavMenu';

			if ( '' != mobileLayoutDevice ) {
				deviceStartIndex = availableDevices.indexOf( mobileLayoutDevice );
			}

			$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).hoverIntent({
				over: function() {
					$( this ).addClass( hoverClass );
				},
				out: function() {
					var $this = $( this );
					$this.removeClass( hoverClass );
					$this.addClass( hoverOutClass );
					setTimeout( function() {
						$this.removeClass( hoverOutClass );
					}, 200 );
				},
				timeout: 200,
				selector: '.menu-item-has-children'
			});

			if ( -1 === excludeCheck ) {
				$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).on( 'touchstart.jetNavMenu', '.menu-item > a', touchStartItem );
				$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).on( 'touchend.jetNavMenu', '.menu-item > a', touchEndItem );

				if ( currentDeviceIndex >= deviceStartIndex ) {
					$( '.jet-mobile-menu.jet-nav-wrap', $scope ).addClass( 'jet-mobile-menu-trigger-active' );
				}

				$( document ).on( 'touchstart.jetNavMenu', prepareHideSubMenus );
				$( document ).on( 'touchend.jetNavMenu', hideSubMenus );
			} else {
				$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).on( 'click.jetNavMenu', '.menu-item > a', clickItem );
			}

			$( window ).on( 'resize.jetNavMenu orientationchange.jetNavMenu', JetBlocksTools.debounce( 50, function() {

				currentDeviceMode  = elementorFrontend.getCurrentDeviceMode();
				currentDeviceIndex = availableDevices.indexOf( currentDeviceMode );

				if ( currentDeviceIndex >= deviceStartIndex ) {
					$( '.jet-mobile-menu.jet-nav-wrap', $scope ).addClass( 'jet-mobile-menu-trigger-active' );
				} else {
					$( '.jet-mobile-menu.jet-nav-wrap', $scope ).removeClass( 'jet-mobile-menu-trigger-active' );
				}
			} ) );

			if ( ! JetBlocks.isEditMode() ) {
				initMenuAnchorsHandler();
			}

			function touchStartItem( event ) {
				var $currentTarget = $( event.currentTarget ),
					$this = $currentTarget.closest( '.menu-item' );

				$this.data( 'offset', $( window ).scrollTop() );
				$this.data( 'elemOffset', $this.offset().top );
			}

			function touchEndItem( event ) {
				var $this,
					$siblingsItems,
					$link,
					$currentTarget,
					subMenu,
					offset,
					elemOffset,
					$hamburgerPanel;

				event.preventDefault();

				$currentTarget  = $( event.currentTarget );
				$this           = $currentTarget.closest( '.menu-item' );
				$siblingsItems  = $this.siblings( '.menu-item.menu-item-has-children' );
				$link           = $( '> a', $this );
				subMenu         = $( '.jet-nav__sub:first', $this );
				offset          = $this.data( 'offset' );
				elemOffset      = $this.data( 'elemOffset' );
				$hamburgerPanel = $this.closest( '.jet-hamburger-panel' );

				if ( offset !== $( window ).scrollTop() || elemOffset !== $this.offset().top ) {
					return false;
				}

				if ( $siblingsItems[0] ) {
					$siblingsItems.removeClass( hoverClass );
					$( '.menu-item-has-children', $siblingsItems ).removeClass( hoverClass );
				}

				if ( ! $( '.jet-nav__sub', $this )[0] || $this.hasClass( hoverClass ) ) {
					$link.trigger( 'click' ); // Need for a smooth scroll when clicking on an anchor link
					window.location.href = $link.attr( 'href' );

					if ( $scope.find( '.jet-nav-wrap' ).hasClass( mobileActiveClass ) ) {
						$scope.find( '.jet-nav-wrap' ).removeClass( mobileActiveClass );
					}

					if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
						$hamburgerPanel.removeClass( 'open-state' );
						$( 'html' ).removeClass( 'jet-hamburger-panel-visible' );
					}

					return false;
				}

				if ( subMenu[0] ) {
					$this.addClass( hoverClass );
				}
			}

			function clickItem( event ) {
				var $currentTarget  = $( event.currentTarget ),
					$menuItem       = $currentTarget.closest( '.menu-item' ),
					$hamburgerPanel = $menuItem.closest( '.jet-hamburger-panel' );
				
				if ( $menuItem.hasClass( 'menu-item-has-children' ) ) {
					if ( ! $menuItem.hasClass( hoverClass ) ) {
						$menuItem.addClass( hoverClass );
						event.preventDefault();
						return false;
					} else {
						window.location.href = $link.attr( 'href' );
					}
				} else {
					if ( ! $menuItem.hasClass( hoverClass ) ) {
						if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
							$hamburgerPanel.removeClass(' open-state' );
							$( 'html' ).removeClass( 'jet-hamburger-panel-visible' );
						}
					}
				}
			}

			var scrollOffset;

			function prepareHideSubMenus( event ) {
				scrollOffset = $( window ).scrollTop();
			}

			function hideSubMenus( event ) {
				var $menu = $scope.find( '.jet-nav' );

				if ( 'touchend' === event.type && scrollOffset !== $( window ).scrollTop() ) {
					return;
				}

				if (  'touchend' === event.type || ! $( event.target ).parent().hasClass('jet-nav-arrow') && $( event.target ).closest( $menu ).length) {
					return;
				}

				var $openMenuItems = $( '.menu-item-has-children.' + hoverClass, $menu );

				if ( ! $openMenuItems[0] ) {
					return;
				}

				$openMenuItems.removeClass( hoverClass );
				$openMenuItems.addClass( hoverOutClass );

				setTimeout( function() {
					$openMenuItems.removeClass( hoverOutClass );
				}, 200 );

				if ( $menu.hasClass( 'jet-nav--vertical-sub-bottom' ) ) {
					$( '.jet-nav__sub', $openMenuItems ).slideUp( 200 );
				}

				event.stopPropagation();
			}

			// START Vertical Layout: Sub-menu at the bottom

			$scope.find( '.jet-nav--vertical-sub-bottom' ).on( 'click.jetNavMenu', '.menu-item > a', verticalSubBottomHandler );

			function verticalSubBottomHandler( event ) {
				var $currentTarget  = $( event.currentTarget ),
					$menuItem       = $currentTarget.closest( '.menu-item' ),
					$siblingsItems  = $menuItem.siblings( '.menu-item.menu-item-has-children' ),
					$subMenu        = $( '.jet-nav__sub:first', $menuItem ),
					$hamburgerPanel = $menuItem.closest( '.jet-hamburger-panel' );

				if ( ! $menuItem.hasClass( 'menu-item-has-children' ) || $menuItem.hasClass( hoverClass ) ) {

					if ( $scope.find( '.jet-nav-wrap' ).hasClass( mobileActiveClass ) ) {
						$scope.find( '.jet-nav-wrap' ).removeClass( mobileActiveClass );
					}

					if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
						$hamburgerPanel.removeClass( 'open-state' );
						$( 'html' ).removeClass( 'jet-hamburger-panel-visible' );
					}

					return;
				}

				event.preventDefault();
				event.stopPropagation();

				if ( $siblingsItems[0] ) {
					$siblingsItems.removeClass( hoverClass );
					$( '.menu-item-has-children', $siblingsItems ).removeClass( hoverClass );
					$( '.jet-nav__sub', $siblingsItems ).slideUp( 200 );
				}

				if ( $subMenu[0] ) {
					$subMenu.slideDown( 200 );
					$menuItem.addClass( hoverClass );
				}
			}

			$( document ).on( currentEventCheck, hideVerticalSubBottomMenus );
			$scope.find( '.jet-nav--vertical-sub-bottom' ).on( 'click.jetNavMenu', '.menu-item', hideVerticalSubBottomMenus );

			function hideVerticalSubBottomMenus( event ) {
				if ( ! $scope.find( '.jet-nav' ).hasClass( 'jet-nav--vertical-sub-bottom' ) ) {
					return;
				}

				hideSubMenus( event );
			}
			// END Vertical Layout: Sub-menu at the bottom

			// Mobile trigger click event
			$( '.jet-nav__mobile-trigger', $scope ).on( 'click.jetNavMenu', function( event ) {
				$( this ).closest( '.jet-nav-wrap' ).toggleClass( mobileActiveClass );
			} );

			// START Mobile Layout: Left-side, Right-side
			if ( 'ontouchend' in window ) {
				$( document ).on( 'touchend.jetMobileNavMenu', removeMobileActiveClass );
			} else {
				$( document ).on( 'click.jetMobileNavMenu', removeMobileActiveClass );
			}

			function removeMobileActiveClass( event ) {
				var mobileLayout = $scope.find( '.jet-nav-wrap' ).data( 'mobile-layout' ),
					$navWrap     = $scope.find( '.jet-nav-wrap' ),
					$trigger     = $scope.find( '.jet-nav__mobile-trigger' ),
					$menu        = $scope.find( '.jet-nav' );

				if ( 'left-side' !== mobileLayout && 'right-side' !== mobileLayout ) {
					return;
				}

				if ( 'touchend' === event.type && scrollOffset !== $( window ).scrollTop() ) {
					return;
				}

				if ( $( event.target ).closest( $trigger ).length || $( event.target ).closest( $menu ).length ) {
					return;
				}

				if ( ! $navWrap.hasClass( mobileActiveClass ) ) {
					return;
				}

				$navWrap.removeClass( mobileActiveClass );

				event.stopPropagation();
			}

			$( '.jet-nav__mobile-close-btn', $scope ).on( 'click.jetMobileNavMenu', function( event ) {
				$( this ).closest( '.jet-nav-wrap' ).removeClass( mobileActiveClass );
			} );

			// END Mobile Layout: Left-side, Right-side

			// START Mobile Layout: Full-width
			var initMobileFullWidthCss = false;

			setFullWidthMenuPosition();
			$( window ).on( 'resize.jetMobileNavMenu', setFullWidthMenuPosition );

			function setFullWidthMenuPosition() {
				var mobileLayout = $scope.find( '.jet-nav-wrap' ).data( 'mobile-layout' );

				if ( 'full-width' !== mobileLayout ) {
					return;
				}

				var $menu = $scope.find( '.jet-nav' ),
					currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
					currentDeviceIndex = availableDevices.indexOf( currentDeviceMode );

				if ( currentDeviceIndex < deviceStartIndex ) {
					if ( initMobileFullWidthCss ) {
						$menu.css( { 'left': '' } );
						initMobileFullWidthCss = false;
					}
					return;
				}

				if ( initMobileFullWidthCss ) {
					$menu.css( { 'left': '' } );
				}

				var offset = - $menu.offset().left;

				$menu.css( { 'left': offset } );
				initMobileFullWidthCss = true;
			}
			// END Mobile Layout: Full-width

			// Menu Anchors Handler
			function initMenuAnchorsHandler() {
				var $anchorLinks = $scope.find( '.menu-item-link[href*="#"]' );

				if ( $anchorLinks[0] ) {
					$anchorLinks.each( function() {
						if ( '' !== this.hash && location.pathname === this.pathname ) {
							menuAnchorHandler( $( this ) );
						}
					} );
				}
			}

			function menuAnchorHandler( $anchorLink ) {
				var anchorHash = $anchorLink[0].hash,
					activeClass = 'current-menu-item',
					rootMargin = '-50% 0% -50%',
					$anchor;

				try {
					$anchor = $( decodeURIComponent( anchorHash ) );
				} catch (e) {
					return;
				}

				if ( !$anchor[0] ) {
					return;
				}

				if ( $anchor.hasClass( 'elementor-menu-anchor' ) ) {
					rootMargin = '300px 0% -300px';
				}

				var observer = new IntersectionObserver( function( entries ) {
						if ( entries[0].isIntersecting ) {
							$anchorLink.parent( '.menu-item' ).addClass( activeClass );
						} else {
							$anchorLink.parent( '.menu-item' ).removeClass( activeClass );
						}
					},
					{
						rootMargin: rootMargin
					}
				);

				observer.observe( $anchor[0] );
			}

			if ( JetBlocks.isEditMode() ) {
				$scope.data( 'initialized', false );
			}
		},

		searchBox: function( $scope ) {

			JetBlocks.onSearchSectionActivated( $scope );

			$( document ).on( 'click.jetBlocks', function( event ) {

				var $widget       = $scope.find( '.jet-search' ),
					$popupToggle  = $( '.jet-search__popup-trigger', $widget ),
					$popupContent = $( '.jet-search__popup-content', $widget ),
					activeClass   = 'jet-search-popup-active',
					transitionOut = 'jet-transition-out';

				if ( $( event.target ).closest( $popupToggle ).length || $( event.target ).closest( $popupContent ).length ) {
					return;
				}

				if ( ! $widget.hasClass( activeClass ) ) {
					return;
				}

				$widget.removeClass( activeClass );
				$widget.addClass( transitionOut );
				setTimeout( function() {
					$widget.removeClass( transitionOut );
				}, 300 );

				event.stopPropagation();
			} );
		},

		onSearchSectionActivated: function( $scope ) {
			if ( ! elementor ) {
				return;
			}

			if ( ! window.JetBlocksEditor ) {
				return;
			}

			if ( ! window.JetBlocksEditor.activeSection ) {
				return;
			}

			var section = window.JetBlocksEditor.activeSection;

			var isPopup = -1 !== [ 'section_popup_style', 'section_popup_close_style', 'section_form_style' ].indexOf( section );

			if ( isPopup ) {
				$scope.find( '.jet-search' ).addClass( 'jet-search-popup-active' );
			} else {
				$scope.find( '.jet-search' ).removeClass( 'jet-search-popup-active' );
			}
		},

		authLinks: function( $scope ) {

			if ( ! elementor ) {
				return;
			}

			if ( ! window.JetBlocksEditor ) {
				return;
			}

			if ( ! window.JetBlocksEditor.activeSection ) {
				$scope.find( '.jet-auth-links__logout' ).css( 'display', 'none' );
				$scope.find( '.jet-auth-links__registered' ).css( 'display', 'none' );
				return;
			}

			var section      = window.JetBlocksEditor.activeSection;
			var isLogout     = -1 !== [ 'section_logout_link', 'section_logout_link_style' ].indexOf( section );
			var isRegistered = -1 !== [ 'section_registered_link', 'section_registered_link_style' ].indexOf( section );

			if ( isLogout ) {
				$scope.find( '.jet-auth-links__login' ).css( 'display', 'none' );
			} else {
				$scope.find( '.jet-auth-links__logout' ).css( 'display', 'none' );
			}

			if ( isRegistered ) {
				$scope.find( '.jet-auth-links__register' ).css( 'display', 'none' );
			} else {
				$scope.find( '.jet-auth-links__registered' ).css( 'display', 'none' );
			}
		},

		hamburgerPanel: function( $scope ) {
			var $panel        = $( '.jet-hamburger-panel', $scope ),
				$toggleButton = $( '.jet-hamburger-panel__toggle', $scope ),
				$instance     = $( '.jet-hamburger-panel__instance', $scope ),
				$cover        = $( '.jet-hamburger-panel__cover', $scope ),
				$inner        = $( '.jet-hamburger-panel__inner', $scope ),
				$closeButton  = $( '.jet-hamburger-panel__close-button', $scope ),
				$panelContent = $( '.jet-hamburger-panel__content', $scope),
				scrollOffset,
				timer,
				timer2,
				editMode      = Boolean( elementorFrontend.isEditMode() ),
				$html         = $( 'html' ),
				settings      = $panel.data( 'settings' ) || {},
				eContainer    = $scope.parents( '.e-container' );

			function fixElementorContainerZIndex( e, open = true ) {
				if ( open ) {
					eContainer.css( 'z-index', 999 );
					e.parent( '.e-container' ).css( 'z-index', 999 );
				} else if ( false === open ) {
					eContainer.css( 'z-index', '' );
					e.parent( '.e-container' ).css( 'z-index', '' );
				}
			}

			if ( 'ontouchend' in window || 'ontouchstart' in window ) {
				$toggleButton.on( 'touchstart', function( event ) {
					scrollOffset = $( window ).scrollTop();
				} );

				$toggleButton.on( 'touchend', function( event ) {
					if ( scrollOffset !== $( window ).scrollTop() ) {
						return false;
					}

					if ( timer ) {
						clearTimeout( timer );
					}

					if ( timer2 ) {
						clearTimeout( timer2 );
					}

					if ( ! $panel.hasClass( 'open-state' ) ) {
						timer = setTimeout( function() {
							fixElementorContainerZIndex( $( this ) );
							$panel.addClass( 'open-state' );
						}, 10 );
						$html.addClass( 'jet-hamburger-panel-visible' );
						JetBlocks.initAnimationsHandlers( $inner );

						if ( settings['ajaxTemplate'] ) {
							ajaxLoadTemplate( $panelContent, settings );
						}
					} else {
						$panel.removeClass( 'open-state' );
						$html.removeClass( 'jet-hamburger-panel-visible' );
						timer2 = setTimeout( function() {
							fixElementorContainerZIndex( $( this ), false );
						}, 400 );
					}
				} );

			} else {
				$toggleButton.on( 'click', function( event ) {

					if ( timer ) {
						clearTimeout( timer );
					}

					if ( ! $panel.hasClass( 'open-state' ) ) {
						fixElementorContainerZIndex( $( this ) );
						$panel.addClass( 'open-state' );
						$html.addClass( 'jet-hamburger-panel-visible' );
						JetBlocks.initAnimationsHandlers( $inner );

						if ( settings['ajaxTemplate'] ) {
							ajaxLoadTemplate( $panelContent, settings );
						}
					} else {
						$panel.removeClass( 'open-state' );
						$html.removeClass( 'jet-hamburger-panel-visible' );
						timer = setTimeout( function() {
							fixElementorContainerZIndex( $( this ), false );
						}, 400 );
					}
				} );

				$toggleButton.on( 'keydown', function( e ) {
					if ( e.key === "Enter" ) {
						if ( timer ) {
							clearTimeout( timer );
						}
						if ( ! $panel.hasClass( 'open-state' ) ) {
							fixElementorContainerZIndex( $( this ) );
							$panel.addClass( 'open-state' );
							$html.addClass( 'jet-hamburger-panel-visible' );
							JetBlocks.initAnimationsHandlers( $inner );

							if ( settings['ajaxTemplate'] ) {
								ajaxLoadTemplate( $panelContent, settings );
							}
						} else {
							$panel.removeClass( 'open-state' );
							$html.removeClass( 'jet-hamburger-panel-visible' );
							timer = setTimeout( function() {
								fixElementorContainerZIndex( $( this ), false );
							}, 400 );
						}
					}
				} );
			}

			$closeButton.on( 'click', function( event ) {
				if ( timer ) {
					clearTimeout( timer );
				}

				if ( ! $panel.hasClass( 'open-state' ) ) {
					$panel.addClass( 'open-state' );
					$html.addClass( 'jet-hamburger-panel-visible' );
					JetBlocks.initAnimationsHandlers( $inner );
				} else {
					$panel.removeClass( 'open-state' );
					$html.removeClass( 'jet-hamburger-panel-visible' );
					timer = setTimeout( function() {
						fixElementorContainerZIndex( $( this ), false );
					}, 400 );
				}
			} );

			$( document ).on( 'click.JetHamburgerPanel', function( event ) {
				if ( ( $( event.target ).closest( $toggleButton ).length || $( event.target ).closest( $instance ).length )
					&& ! $( event.target ).closest( $cover ).length
				) {
					return;
				}

				if ( ! $panel.hasClass( 'open-state' ) ) {
					return;
				}

				$panel.removeClass( 'open-state' );

				if ( ! $( event.target ).closest( '.jet-hamburger-panel__toggle' ).length ) {
					$html.removeClass( 'jet-hamburger-panel-visible' );
				}

				event.stopPropagation();
			} );



			/**
			 * [ajaxLoadTemplate description]
			 * @param  {[type]} $index [description]
			 * @return {[type]}        [description]
			 */
			function ajaxLoadTemplate( $panelContent, $settings ) {
				var $contentHolder = $panelContent,
					templateLoaded = $contentHolder.data( 'template-loaded' ) || false,
					templateId     = $contentHolder.data( 'template-id' ),
					loader         = $( '.jet-hamburger-panel-loader', $contentHolder ),
					cachedTemplate = $settings['ajaxTemplateCache'],
					widgetId       = $settings['widget_id'],
					signature      = $settings['signature'];

				if ( templateLoaded ) {
					return false;
				}

				$( window ).trigger( 'jet-blocks/ajax-load-template/before', {
					target: $panel,
					contentHolder: $contentHolder
				} );

				$contentHolder.data( 'template-loaded', true );

				$.ajax( {
					type: 'GET',
					url: window.JetHamburgerPanelSettings.templateApiUrl,
					dataType: 'json',
					data: {
						'id'            : templateId,
						'dev'           : window.JetHamburgerPanelSettings.devMode,
						'cachedTemplate': cachedTemplate,
						'widget_id'     : widgetId,
						'signature'     : signature
					},
					beforeSend: function( jqXHR ) {
						jqXHR.setRequestHeader( 'X-WP-Nonce', window.JetHamburgerPanelSettings.restNonce );
					},
					success: function( responce, textStatus, jqXHR ) {
						var templateContent     = responce['template_content'],
							templateScripts     = responce['template_scripts'],
							templateStyles      = responce['template_styles'];

						for ( var scriptHandler in templateScripts ) {
							JetBlocks.addedAssetsPromises.push( JetBlocks.loadScriptAsync( scriptHandler, templateScripts[ scriptHandler ] ) );
						}

						for ( var styleHandler in templateStyles ) {
							JetBlocks.addedAssetsPromises.push( JetBlocks.loadStyle( styleHandler, templateStyles[ styleHandler ] ) );
						}

						Promise.all( JetBlocks.addedAssetsPromises ).then( function( value ) {
							loader.remove();
							$contentHolder.append( templateContent );
							JetBlocks.elementorFrontendInit( $contentHolder );

							$( window ).trigger( 'jet-blocks/ajax-load-template/after', {
								target: $panel,
								contentHolder: $contentHolder,
								responce: responce
							} );
						}, function( reason ) {
							console.log( 'Script Loaded Error' );
						});
					}
				} );//end
			}
		},

		loadStyle: function( style, uri ) {

			if ( JetBlocks.addedStyles.hasOwnProperty( style ) && JetBlocks.addedStyles[ style ] ===  uri) {
				return style;
			}

			if ( !uri ) {
				return;
			}

			JetBlocks.addedStyles[ style ] = uri;

			return new Promise( function( resolve, reject ) {
				var tag = document.createElement( 'link' );

				tag.id      = style;
				tag.rel     = 'stylesheet';
				tag.href    = uri;
				tag.type    = 'text/css';
				tag.media   = 'all';
				tag.onload  = function() {
					resolve( style );
				};

				document.head.appendChild( tag );
			});
		},

		loadScriptAsync: function( script, uri ) {

			if ( JetBlocks.addedScripts.hasOwnProperty( script ) ) {
				return script;
			}

			if ( !uri ) {
				return;
			}

			JetBlocks.addedScripts[ script ] = uri;

			return new Promise( function( resolve, reject ) {
				var tag = document.createElement( 'script' );

				tag.src    = uri;
				tag.async  = true;
				tag.onload = function() {
					resolve( script );
				};

				document.head.appendChild( tag );
			});
		},

		initAnimationsHandlers: function( $selector ) {
			$selector.find( '[data-element_type]' ).each( function() {
				var $this       = $( this ),
					elementType = $this.data( 'element_type' );

				if ( !elementType ) {
					return;
				}

				window.elementorFrontend.hooks.doAction( 'frontend/element_ready/global', $this, $ );
			} );
		},

		searchPopupSwitch: function( event ) {

			//event.stopPropagation();

			var $this         = $( this ),
				$widget       = $this.closest( '.jet-search' ),
				$input        = $( '.jet-search__field', $widget ),
				activeClass   = 'jet-search-popup-active',
				transitionIn  = 'jet-transition-in',
				transitionOut = 'jet-transition-out';

			if ( ! $widget.hasClass( activeClass ) ) {
				$widget.addClass( transitionIn );
				setTimeout( function() {
					$widget.removeClass( transitionIn );
					$widget.addClass( activeClass );
				}, 300 );
				$input.focus();
			} else {
				$widget.removeClass( activeClass );
				$widget.addClass( transitionOut );
				setTimeout( function() {
					$widget.removeClass( transitionOut );
				}, 300 );
			}
		},

		stickySection: function() {
			var stickySection = {

				isEditMode: Boolean( elementorFrontend.isEditMode() ),

				correctionSelector: $( '#wpadminbar' ),

				initWidescreen:   false,
				initDesktop:      false,
				initLaptop:       false,
				initTabletExtra:  false,
				initTablet:       false,
				initMobileExtra:  false,
				initMobile:       false,

				init: function() {
					var _this = this;

					if ( this.isEditMode ) {
						return;
					}

					$( document ).ready( function(){
						_this.run();
					} );

					$( window ).on( 'resize.JetStickySection orientationchange.JetStickySection', this.run.bind( this ) );
				},

				getOffset: function(){
					var offset = 0;

					if ( this.correctionSelector[0] && 'fixed' === this.correctionSelector.css( 'position' ) ) {
						offset = this.correctionSelector.outerHeight( true );
					}

					return offset;
				},

				run: function() {
					var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
						transitionIn  = 'jet-sticky-transition-in',
						transitionOut = 'jet-sticky-transition-out',
						options = {
							stickyClass: 'jet-sticky-section--stuck',
							topSpacing: this.getOffset()
						};

					function initSticky ( section, options ) {
						section.jetStickySection( options )
							.on( 'jetStickySection:stick', function( event ) {
								$( event.target ).addClass( transitionIn );
								setTimeout( function() {
									$( event.target ).removeClass( transitionIn );
								}, 3000 );
							} )
							.on( 'jetStickySection:unstick', function( event ) {
								$( event.target ).addClass( transitionOut );
								setTimeout( function() {
									$( event.target ).removeClass( transitionOut );
								}, 3000 );
							} );
						section.trigger( 'jetStickySection:activated' );
					}

					function setJetStickySection(DeviceMode, $this){

						if ( $this.initWidescreen && 'widescreen' !== DeviceMode ) {
							JetBlocks.getStickySectionsWidescreen.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							$this.initWidescreen = false;
						}

						if ( $this.initDesktop && 'desktop' !== DeviceMode ) {
							JetBlocks.getStickySectionsDesktop.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							$this.initDesktop = false;
						}

						if ( $this.initLaptop && 'laptop' !== DeviceMode ) {
							JetBlocks.getStickySectionsLaptop.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							$this.initLaptop = false;
						}

						if ( $this.initTabletExtra && 'tablet_extra' !== DeviceMode ) {
							JetBlocks.getStickySectionsTabletExtra.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							$this.initTabletExtra = false;
						}

						if ( $this.initTablet && 'tablet' !== DeviceMode ) {
							JetBlocks.getStickySectionsTablet.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							$this.initTablet = false;
						}

						if ( $this.initMobileExtra && 'mobile_extra' !== DeviceMode ) {
							JetBlocks.getStickySectionsMobiletExtra.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							$this.initMobileExtra = false;
						}

						if ( $this.initMobile && 'mobile' !== DeviceMode ) {
							JetBlocks.getStickySectionsMobile.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							$this.initMobile = false;
						}

					}

					function getJetStickySectionsDevice( getStickySectionsDevice ) {
						getStickySectionsDevice.forEach( function( section, i ) {
							var settings = section.data( 'settings' ) || {};
							var stopAtParentEnd = 'yes' === settings['jet_sticky_section_stop_at_parent_end'];
					
							if ( stopAtParentEnd ) {
								var parentSection = section.closest( '.elementor-section:not(.jet-sticky-section), .e-container:not(.jet-sticky-section), .e-con-inner:not(.jet-sticky-section)' );
								if ( parentSection.length ) {
									var calculateStopper = function( reinit = false ) {
										var parentHeight = parentSection.outerHeight( true );
										var parentTop = parentSection.offset().top;
										var sectionHeight = section.outerHeight( true );
										var parentPaddingBottom = parseFloat( parentSection.css( 'padding-bottom' ) ) || 0;
										var stopPoint = ( parentTop + parentHeight - parentPaddingBottom ) - sectionHeight;
										options.stopper = stopPoint + sectionHeight;
										
										if ( true === reinit ) {
											section.trigger( 'jetStickySection:detach' );
										}
										initSticky( section, options );
									};
									
									calculateStopper();
									
									if ( window.ResizeObserver ) {
										const resizeObserver = new ResizeObserver( function( entries ) {
											calculateStopper( true );
										} );
										resizeObserver.observe( parentSection[0] );
									}
								} else {
									options.stopper = '';
									initSticky( section, options );
								}
							} else if ( getStickySectionsDevice[i+1] ) {
								options.stopper = getStickySectionsDevice[i+1];
								initSticky( section, options );
							} else {
								options.stopper = '';
								initSticky( section, options );
							}
						});
					}

					

					if ( 'widescreen' === currentDeviceMode && ! this.initWidescreen ) {

						setJetStickySection(currentDeviceMode, this);

						if ( JetBlocks.getStickySectionsWidescreen[0] ) {

							getJetStickySectionsDevice(JetBlocks.getStickySectionsWidescreen);

							this.initWidescreen = true;

						}

					}

					if ( 'desktop' === currentDeviceMode && ! this.initDesktop ) {

						setJetStickySection(currentDeviceMode, this);

						if ( JetBlocks.getStickySectionsDesktop[0] ) {

							getJetStickySectionsDevice(JetBlocks.getStickySectionsDesktop);

							this.initDesktop = true;

						}
					}

					if ( 'laptop' === currentDeviceMode && ! this.initLaptop ) {

						setJetStickySection(currentDeviceMode, this);

						if ( JetBlocks.getStickySectionsLaptop[0] ) {

							getJetStickySectionsDevice(JetBlocks.getStickySectionsLaptop);

							this.initLaptop = true;
						}

					}

					if ( 'tablet_extra' === currentDeviceMode && ! this.initTabletExtra ) {

						setJetStickySection(currentDeviceMode, this);

						if ( JetBlocks.getStickySectionsTabletExtra[0] ) {

							getJetStickySectionsDevice(JetBlocks.getStickySectionsTabletExtra);

							this.initTabletExtra = true;
						}
					}

					if ( 'tablet' === currentDeviceMode && ! this.initTablet ) {

						setJetStickySection(currentDeviceMode, this);

						if ( JetBlocks.getStickySectionsTablet[0] ) {

							getJetStickySectionsDevice(JetBlocks.getStickySectionsTablet);

							this.initTablet = true;
						}

					}

					if ( 'mobile_extra' === currentDeviceMode && ! this.initMobileExtra ) {

						setJetStickySection(currentDeviceMode, this);

						if ( JetBlocks.getStickySectionsMobileExtra[0] ) {

							getJetStickySectionsDevice(JetBlocks.getStickySectionsMobileExtra);
						
							this.initMobileExtra = true;
						}

					}

					if ( 'mobile' === currentDeviceMode && ! this.initMobile ) {

						setJetStickySection(currentDeviceMode, this);

						if ( JetBlocks.getStickySectionsMobile[0] ) {

							getJetStickySectionsDevice(JetBlocks.getStickySectionsMobile);
						
							this.initMobile = true;
						}

					}
				}
			};

			stickySection.init();
		},

		getStickySectionsWidescreen:   [],
		getStickySectionsDesktop:      [],
		getStickySectionsLaptop:       [],
		getStickySectionsTabletExtra:  [],
		getStickySectionsTablet:       [],
		getStickySectionsMobileExtra:  [],
		getStickySectionsMobile:       [],

		setStickySection: function( $scope ) {
			var setStickySection = {

				target: $scope,

				isEditMode: Boolean( elementorFrontend.isEditMode() ),

				init: function() {
					if ( this.isEditMode ) {
						return;
					}

					if (  'yes' === this.getSectionSetting( 'jet_sticky_section' ) ) {
						var availableDevices = this.getSectionSetting( 'jet_sticky_section_visibility' ) || [];

						if ( ! availableDevices[0] ) {
							return;
						}

						if ( -1 !== availableDevices.indexOf( 'widescreen' ) ) {
							JetBlocks.getStickySectionsWidescreen.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'desktop' ) ) {
							JetBlocks.getStickySectionsDesktop.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'laptop' ) ) {
							JetBlocks.getStickySectionsLaptop.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'tablet_extra' ) ) {
							JetBlocks.getStickySectionsTabletExtra.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'tablet' ) ) {
							JetBlocks.getStickySectionsTablet.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'mobile_extra' ) ) {
							JetBlocks.getStickySectionsMobileExtra.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'mobile' ) ) {
							JetBlocks.getStickySectionsMobile.push( $scope );
						}
					}
				},

				getSectionSetting: function( setting ){
					var settings = {},
		 				editMode = Boolean( elementorFrontend.isEditMode() );

					if ( editMode ) {
						if ( ! elementorFrontend.hasOwnProperty( 'config' ) ) {
							return;
						}

						if ( ! elementorFrontend.config.hasOwnProperty( 'elements' ) ) {
							return;
						}

						if ( ! elementorFrontend.config.elements.hasOwnProperty( 'data' ) ) {
							return;
						}

						var modelCID = this.target.data( 'model-cid' ),
							editorSectionData = elementorFrontend.config.elements.data[ modelCID ];

						if ( ! editorSectionData ) {
							return;
						}

						if ( ! editorSectionData.hasOwnProperty( 'attributes' ) ) {
							return;
						}

						settings = editorSectionData.attributes || {};
					} else {
						settings = this.target.data( 'settings' ) || {};
					}

					if ( ! settings[ setting ] ) {
						return;
					}

					return settings[ setting ];
				}
			};

			setStickySection.init();
		},

		isEditMode: function() {
			return Boolean( elementorFrontend.isEditMode() );
		},

		elementorFrontendInit: function( $container ) {

			$container.find( '[data-element_type]' ).each( function() {
				var $this       = $( this ),
					elementType = $this.data( 'element_type' );

				if ( ! elementType ) {
					return;
				}

				try {
					if ( 'widget' === elementType ) {
						elementType = $this.data( 'widget_type' );
						window.elementorFrontend.hooks.doAction( 'frontend/element_ready/widget', $this, $ );
					}

					window.elementorFrontend.hooks.doAction( 'frontend/element_ready/global', $this, $ );
					window.elementorFrontend.hooks.doAction( 'frontend/element_ready/' + elementType, $this, $ );

				} catch ( err ) {
					console.log(err);

					$this.remove();

					return false;
				}
			} );

		},

		togglePasswordVisibility: function( $scope ) {
			var target = $( 'input:password', $scope ),
				icon   = $( '.password-visibility__icon', $scope );

			icon.on( 'click', function(){
				if ( target.attr( 'type') === "password" ) {
					target.attr( 'type', 'text' );
					$( '.password-visibility__icon--show', $scope ).removeClass( 'show' );
					$( '.password-visibility__icon--hide', $scope ).addClass( 'show' );
				} else {
					target.attr( 'type', 'password' );
					$( '.password-visibility__icon--show', $scope ).addClass( 'show' );
					$( '.password-visibility__icon--hide', $scope ).removeClass( 'show' );
				}
			} );
		},

		strongPasswordValidation: function( $scope, submitBtn ) {
			var target         = $( 'input.pw-validation', $scope ),
				wrapper        = $( '.jet-reset', $scope ),
				validationList = $( '.jet-password-requirements', $scope ),
				length         = $( '.jet-password-requirements-length', validationList ),
				lowercase      = $( '.jet-password-requirements-lowercase', validationList ),
				uppercase      = $( '.jet-password-requirements-uppercase', validationList ),
				number         = $( '.jet-password-requirements-number', validationList ),
				special        = $( '.jet-password-requirements-special', validationList ),
				passwordLength = wrapper.data( 'option' ) || 8;

			target.on( 'input', checkRequirements );

			target.keydown( function( event ) {
				if ( event.keyCode == 13 && false === checkRequirements() ) {
					event.preventDefault();
					return false;
				}
			} );

			submitBtn.on( 'click touchend', function( event ) {

				if ( false === checkRequirements() ){
					event.preventDefault();

					validationList.find( 'li:not(.success)' ).each( function() {
						$( this ).addClass( 'error' );
					} )

					return false;
				}
			} )

			function checkRequirements() {
				var text = target.val(),
					lengthCheck,
					lowercaseCheck,
					uppercaseCheck,
					numberCheck,
					specialCheck,
					checkFalse = 0,
					activeReq = {};

				if ( 0 < length.length ) {
					lengthCheck = checkIfEightChar( text );
					lengthCheck ? length.addClass( 'success' ).removeClass( 'error' ) : length.removeClass( 'success' );
					activeReq.length = lengthCheck;
				}

				if ( 0 < lowercase.length ) {
					lowercaseCheck = checkIfOneLowercase( text );
					lowercaseCheck ? lowercase.addClass( 'success' ).removeClass( 'error' ) : lowercase.removeClass( 'success' );
					activeReq.lowercase = lowercaseCheck;
				}

				if ( 0 < uppercase.length ) {
					uppercaseCheck = checkIfOneUppercase( text );
					uppercaseCheck ? uppercase.addClass( 'success' ).removeClass( 'error' ) : uppercase.removeClass( 'success' );
					activeReq.uppercase = uppercaseCheck;
				}

				if ( 0 < number.length ) {
					numberCheck = checkIfOneDigit( text );
					numberCheck ? number.addClass( 'success' ).removeClass( 'error' ) : number.removeClass( 'success' );
					activeReq.number = numberCheck;
				}

				if ( 0 < special.length ) {
					specialCheck = checkIfOneSpecialChar( text );
					specialCheck ? special.addClass( 'success' ).removeClass( 'error' ) : special.removeClass( 'success' );
					activeReq.special = specialCheck;
				}

				Object.keys( activeReq ).forEach( function( reqName )  {
					if ( false === activeReq[reqName] ) {
						checkFalse++;
					}
				} );

				if ( 0 < checkFalse ) {
					return false;
				} else {
					return true;
				}
			}

			function checkIfEightChar(text){
				return text.length >= passwordLength;
			}

			function checkIfOneLowercase(text) {
				return /[a-z]/.test(text);
			}

			function checkIfOneUppercase(text) {
				return /[A-Z]/.test(text);
			}

			function checkIfOneDigit(text) {
				return /[0-9]/.test(text);
			}

			function checkIfOneSpecialChar(text) {
				return /[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(text);
			}
		}
	};

	$( window ).on( 'elementor/frontend/init', JetBlocks.init );

	var JetBlocksTools = {

		debounce: function( threshold, callback ) {
			var timeout;

			return function debounced( $event ) {
				function delayed() {
					callback.call( this, $event );
					timeout = null;
				}

				if ( timeout ) {
					clearTimeout( timeout );
				}

				timeout = setTimeout( delayed, threshold );
			};
		},
		googleRecaptcha: function( $target ) {
			if ( "true" === window.jetBlocksData.recaptchaConfig.enable && '' != window.jetBlocksData.recaptchaConfig.site_key && '' != window.jetBlocksData.recaptchaConfig.secret_key ) {
				window.grecaptcha.ready( function() {
					grecaptcha.execute( window.jetBlocksData.recaptchaConfig.site_key, { action: 'submit' }).then( function( token ) {
						$target.append('<input type="hidden" name="token" value="' + token + '">');
						$target.append('<input type="hidden" name="action" value="submit">');
					} );
				} );
			}
		}
	}

}( jQuery, window.elementorFrontend, window.elementor, window.JetHamburgerPanelSettings ) );
