(function($) {
  
  "use strict";
  
  /* Page Loader active
  ========================================================*/
  $('#preloader').fadeOut();

  /* Testimonials Carousel 
  ========================================================*/






  
  var owl = $("#client_testimonial");
    owl.owlCarousel({
      navigation: true,
      pagination: true,
      slideSpeed: 1000,
      stopOnHover: true,
      autoPlay: true,
      margin:10,
      items: 3,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      addClassActive: true,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [980,2],
      itemsTablet: [768,1],
      itemsTablet: [767,1],
      itemsTabletSmall: [480,1],
      itemsMobile : [479,1],
    });   
    $('#client_testimonial').find('.owl-prev').html('<img src="assets/front/images/icons/left.png" />');
    $('#client_testimonial').find('.owl-next').html('<img src="assets/front/images/icons/right.png" />');


    /* showcase Slider
    =============================*/

    var owl = $("#ourAppsScreenshots_slider");
      owl.owlCarousel({
        navigation: false,
        pagination: true,
        slideSpeed: 3000,
        loop:true,
        margin:10,
        stopOnHover: true,
        autoPlay: true,
        items: 1,
        itemsCustom: [1600, 1],
        itemsDesktop: [1200,1],
        itemsDesktopSmall: [1024, 1],
        itemsTablet: [600, 1],
        itemsMobile: [479, 1]
        
      });






  /* 
   Sticky Nav
   ========================================================================== */
  /* 
 VIDEO POP-UP
 ========================================================================== */
  $('.video-popup').magnificPopup({
      disableOn: 700,
      type: 'iframe',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      fixedContentPos: false,
  });

  /* 
   Back Top Link
   ========================================================================== */
    var offset = 200;
    var duration = 500;
    $(window).scroll(function() {
      if ($(this).scrollTop() > offset) {
        $('.back-to-top').fadeIn(400);
      } else {
        $('.back-to-top').fadeOut(400);
      }
    });

    $('.back-to-top').on('click',function(event) {
      event.preventDefault();
      $('html, body').animate({
        scrollTop: 0
      }, 600);
      return false;
    })

  /* 
   One Page Navigation
   ========================================================================== */


    $(window).on('load', function() {
       
        $('body').scrollspy({
            target: '.navbar-collapse',
            offset: 195
        });

        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 30) {
                $('.fixed-top').addClass('menu-bg');
            } else {
                $('.fixed-top').removeClass('menu-bg');
            }
        });

    });

  /* Auto Close Responsive Navbar on Click
  ========================================================*/
  function close_toggle() {
      if ($(window).width() <= 1199) {
          $('.navbar-collapse a').on('click', function () {
              $('.navbar-collapse').collapse('hide');
          });
      }
      else {
          $('.navbar .navbar-inverse a').off('click');
      }
  }
  close_toggle();
  $(window).resize(close_toggle);

  /* Nivo Lightbox
  ========================================================*/   
   $('.lightbox').nivoLightbox({
    effect: 'fadeScale',
    keyboardNav: true,
  });

}(jQuery));

