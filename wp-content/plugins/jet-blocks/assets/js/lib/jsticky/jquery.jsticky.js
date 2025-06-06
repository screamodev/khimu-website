/* jSticky Plugin
 * =============
 * Author: Andrew Henderson (@AndrewHenderson)
 * Contributor: Mike Street (@mikestreety)
 * Date: 9/7/2012
 * Update: 09/20/2016
 * Website: http://github.com/andrewhenderson/jsticky/
 * Description: A jQuery plugin that keeps select DOM
 * element(s) in view while scrolling the page.
 */

;(function($) {

  $.fn.jetStickySection = function(options) {
    var defaults = {
      topSpacing: 0, // No spacing by default
      zIndex: '', // No default z-index
      stopper: $('.sticky-stopper'), // Default stopper class, also accepts number value
      stickyClass: false // Class applied to element when it's stuck
    };
    var settings = $.extend({}, defaults, options); // Accepts custom stopper id or class

    // Checks if custom z-index was defined
    function checkIndex() {
      if (typeof settings.zIndex == 'number') {
        return true;
      } else {
        return false;
      }
    }

    var hasIndex = checkIndex(); // True or false

    // Checks if a stopper exists in the DOM or number defined
    function checkStopper() {
      if (0 < settings.stopper.length || typeof settings.stopper === 'number') {
        return true;
      } else {
        return false;
      }
    }
    var hasStopper = checkStopper(); // True or false
    return this.each(function() {

      var $this = $(this);
      var topSpacing = settings.topSpacing;
      var thisHeight = $this.outerHeight();
      var thisWidth = $this.outerWidth();
      var originalLeft = $this.offset().left;
      var zIndex = settings.zIndex;
      var pushPoint = $this.offset().top - topSpacing; // Point at which the sticky element starts pushing
      var placeholder = $('<div></div>').width(thisWidth ).height(thisHeight).addClass('sticky-placeholder'); // Cache a clone sticky element
      var stopper = settings.stopper;
      var $window = $(window);
      var detached = false;
      var stick = false;
      var isSection = $this.hasClass('elementor-section');
      var isOuterContainer = $this.hasClass('e-parent');

      function stickyScroll() {
        if (detached) {
          return;
        }

        var thisHeight = $this.outerHeight();
        var windowTop = $window.scrollTop(); // Check window's scroll position
        var stopPoint = stopper;
        var parentWidth = $this.parent().width();

        placeholder.width(thisWidth);

        if (hasStopper) {
          if (typeof settings.stopper !== 'number') {
            var stopperTop = settings.stopper.offset().top;
            stopPoint = stopperTop - thisHeight - topSpacing;
          } else if (typeof settings.stopper === 'number') {
            stopPoint = settings.stopper - thisHeight - topSpacing;
          }
        }

        if (pushPoint < windowTop) {
          // Create a placeholder for sticky element to occupy vertical real estate
          if(settings.stickyClass)
            $this.addClass(settings.stickyClass);

          var cssOptions = {
            position: 'fixed',
            top: topSpacing,
            width: thisWidth
          };
          if (!isSection && !isOuterContainer) {
            cssOptions.left = originalLeft;
          }

          $this.after(placeholder).css(cssOptions);

          if (hasIndex) {
            $this.css({
              zIndex: zIndex
            });
          }

          if (hasStopper) {
            if (stopPoint < windowTop) {
              var diff = (stopPoint - windowTop) + topSpacing;
              $this.css({
                top: diff
              });
            }
          }
          if ( ! stick ) {
            $this.trigger( 'jetStickySection:stick' );
          }
          stick = true;
        } else {
          if(settings.stickyClass)
            $this.removeClass(settings.stickyClass);

          $this.css({
            position: '',
            top: '',
            left: '',
            width: ''
          });

          placeholder.remove();
          if ( stick ) {
            $this.trigger( 'jetStickySection:unstick' );
          }
          stick = false;
        }
      }

      function detachStickyScroll() {
        detached = true;
        //$window.off('load', stickyScroll);
        $window.off('scroll', stickyScroll);
        $window.off('touchmove', stickyScroll);
        $window.off('resize', stickyScroll);

        if(settings.stickyClass)
          $this.removeClass(settings.stickyClass);

        $this.css({
          position: '',
          top: '',
          left: '',
          width: ''
        });

        placeholder.remove();
      }

      if($window.innerHeight() > thisHeight) {
        //$window.on('load', stickyScroll);
        $this.on( 'jetStickySection:activated', stickyScroll );

        $window.on('scroll', stickyScroll);
        $window.on('touchmove', stickyScroll);
        $window.on('resize', stickyScroll);

        $this.on( 'jetStickySection:detach', detachStickyScroll );
      }
    });
  };
})(jQuery);
