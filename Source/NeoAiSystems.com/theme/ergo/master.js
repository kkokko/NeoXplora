  var isIOS = ((/iphone|ipad/gi).test(navigator.appVersion));
  var myevent = isIOS ? "touchstart" : "click";
  $(document).ready(function () {
      $.jStyling({
          'fileButtonText': 'Browse...'
      })
      $.jStyling.createFileInput($('input[type=file]'));
      $.jStyling.createSelect($('select.custombox'));
      $.jStyling.createCheckbox($('input[type=checkbox]'));
      $.jStyling.createRadio($('input[type=radio]'));
      var viewportWidth = $('body').innerWidth();
	  
      /* == Fancy box == */
      $(".fancybox").fancybox({
          padding: 5,
          autoSize: false
      });
      $('.ajax').fancybox({
          'type': 'ajax',
          'autoScale': false
      });
      $('.iframe').fancybox({
          'type': 'iframe',
		  'autoScale': false
      });
      $('.video')
          .attr('data-media', 'media')
          .fancybox({
          openEffect: 'none',
          closeEffect: 'none',
          prevEffect: 'none',
          nextEffect: 'none',
          arrows: false,
          helpers: {
              media: {},
              buttons: {}
          }
      });
      $('.zoom').hoverZoom();
	  
      /* == Language switcher == */
      $('a.langchange').click(function () {
          var target = $(this).attr('href');
          $.cookie("LANG_CMSPRO", $(this).attr('data-lang'), {
              expires: 120,
              path: '/'
          });
          $('body').addClass('loadbg').fadeOut(1000, function () {
              window.location.href = target;
          });
          return false
      });
      /* == Menus == */
      $("ul#topmenu li:has(ul)").find("span:first").addClass("down");
      $("ul#topmenu li ul li:has(ul)").find("span:first").removeClass("down");
      $("ul#topmenu li ul li:has(ul)").find("a:first").addClass("fly");
      $("ul#topmenu li li:last-child").css("border-bottom", "0");
      var menuClone = $('#topmenu').clone().attr('id', 'menu-mobile').removeClass('menu-parent');
      menuClone.children().find('ul.menu-submenu').parent().addClass('li-submenu').append('<span class="li-sub-arrow"></span>');

      function createMobileMenu() {
          windowWidth = $(window).width();
          if (windowWidth < 768) {
              if (!$('#menu-mobile-holder').length) {
                  $('.menu-mobile-wrapper').append('<div id="menu-mobile-holder"></div>');
                  $('#menu-mobile-holder').prepend(menuClone);
                  $('span.li-sub-arrow').click(function () {
                      $(this).siblings('ul.menu-submenu').slideToggle();
                  });
              }
          } else {
              $('#menu-mobile-holder').remove();
          }
      }
      createMobileMenu();
      $(window).resize(function () {
          createMobileMenu();
      });
      $('.menu-mobile-wrapper').find('#menu-mobile-trigger').on(myevent, function () {
          $('#menu-mobile-holder').stop().slideToggle(200);
      });
      $('ul#topmenu').superfish({
          delay: 300,
          animation: {
              opacity: 'show',
              height: 'show'
          },
          speed: 'fast',
          autoArrows: false,
          dropShadows: false
      });
	  
      /* ==Tabs == */
      $(".tab-content div").hide();
      $(".tabs li:first").attr("id", "current");
      $(".tab-content  div:first").fadeIn();
      $('.tabs a').on(myevent, function (e) {
          e.preventDefault();
          $(".tab-content  div").hide();
          $(".tabs li").attr("id", "");
          $(this).parent().attr("id", "current");
          $('#' + $(this).attr('data-title')).fadeIn();
          return false;
      });

      /* == Accordion == */
      $('.accowrap h4').on(myevent, function (e) {
          e.preventDefault();
          var tab = $(this).parent();
          var current = false;
          if (!tab.hasClass('current')) {
              tab.find('> :last-child').slideDown(300);
              current = true;
          }
          tab.parent().find('.current').removeClass('current').find('> :last-child').stop(true, true).slideToggle(300);
          if (current == true) {
              tab.addClass('current');
          }
          return false;
      });
	  
      /* == Carousel == */
      $('.carousel').flexslider({
          animation: "slide",
          animationLoop: false,
          slideshow: false,
          controlNav: false,
      });
	  
      /* == Tooltip == */
      var targets = $('body'),
          target = false,
          tooltip = false,
          title = false;
      targets.on("mouseenter", ".tooltip", function () {
          target = $(this);
          tip = target.attr('title');
          tooltip = $('<div id="tooltip"></div>');
          if (!tip || tip == '') return false;
          target.removeAttr('title');
          tooltip.css('opacity', 0)
              .html(tip)
              .appendTo('body');

          var init_tooltip = function () {
              if ($(window).width() < tooltip.outerWidth() * 1.5) tooltip.css('max-width', $(window).width() / 2);
              else tooltip.css('max-width', 340);
              var pos_left = target.offset().left + (target.outerWidth() / 2) - (tooltip.outerWidth() / 2),
                  pos_top = target.offset().top - tooltip.outerHeight() - 20;
              if (pos_left < 0) {
                  pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                  tooltip.addClass('left');
              } else tooltip.removeClass('left');
              if (pos_left + tooltip.outerWidth() > $(window).width()) {
                  pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                  tooltip.addClass('right');
              } else tooltip.removeClass('right');
              if (pos_top < 0) {
                  var pos_top = target.offset().top + target.outerHeight();
                  tooltip.addClass('top');
              } else tooltip.removeClass('top');
              tooltip.css({
                  left: pos_left,
                  top: pos_top
              })
                  .animate({
                  top: '+=10',
                  opacity: 1
              }, 50);
          };
          init_tooltip();

          $(window).resize(init_tooltip);
          var remove_tooltip = function () {
              tooltip.animate({
                  top: '-=10',
                  opacity: 0
              }, 50, function () {
                  $(this).remove();
              });
              target.attr('title', tip);
          };
          target.on("mouseleave", remove_tooltip);
          tooltip.on(myevent, remove_tooltip);
      });
	  
	  
    $('table.datatable').responsiveTable({
      displayResponsiveCallback : function() {
        return $(window).width() < 769;
      },
    });
    $(window).bind("orientationchange", function(e) {
      setTimeout("$('table.datatable').responsiveTableUpdate()", 100);
    });

    $(window).resize(function() {
      $('table.datatable').responsiveTableUpdate();
    });
	
	  if (($.browser.msie && $.browser.version < 9)) {
		  $.fancybox('<p class="msgInfo">It appears that you are using a <em>very</em> old version of MS Internet Explorer (MSIE) v.' + $.browser.version + '.<br />If you seriously want to continue to use MSIE, at least <a href="http://www.microsoft.com/windows/internet-explorer/">upgrade</a></p>', {
			  'autoDimensions': false,
			  'width': '350',
			  'height': 'auto'
		  });
	  }
  });