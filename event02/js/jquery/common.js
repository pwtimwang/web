jQuery(document).ready(function(){

// FIXED MENU SHOW AND HIDE

    jQuery(".fixed-nav").hide();

    jQuery(function () {
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 150) {
                jQuery('.fixed-nav').fadeIn();
            } else {
                jQuery('.fixed-nav').fadeOut();
            }
        });
    });



jQuery('.one-page').waypoint(function(event, direction) {
       jQuery('.navul li a').removeClass('active');
       getid = jQuery(this).attr('class').split(' ')[1];
       jQuery('.navul li .'+getid).addClass('active');
}, {
   offset: '0'
});





    // HOVER-IMAGES


        jQuery('.header-links ul li a').hover(function(){
           jQuery('div',this).stop().animate({top: '-16px'},300);
        },function(){
           jQuery('div',this).stop().animate({top: '0'},300);
        });

	jQuery("a.anchorLink").anchorAnimate()


    jQuery(function() {

        jQuery("<select />").appendTo(".nav");

        // Create default option "Go to..."
        jQuery("<option />", {
         "selected": "selected",
         "value"   : "",
         "text"    : "Go to..."
        }).appendTo("nav select");

        // Populate dropdown with menu items
        jQuery(".nav a").each(function() {

            var el = jQuery(this);

            jQuery("<option />", {
               "value"   : el.attr("href"),
               "text"    : el.text()
            }).appendTo(".nav select");
        });

           // To make dropdown actually work
           // To make more unobtrusive: http://css-tricks.com/4064-unobtrusive-page-changer/
        jQuery(".nav  select").change(function() {       
   
        var elementClick = jQuery(this).find("option:selected").val();
        var destination = jQuery(elementClick).offset().top;
  
            jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 1000, function() {
                window.location.hash = elementClick
            });
        });

    });


        jQuery(function() {

        jQuery("<select />").appendTo(".fixed-nav-center");

        // Create default option "Go to..."
        jQuery("<option />", {
         "selected": "selected",
         "value"   : "",
         "text"    : "Go to..."
        }).appendTo("nav select");

        // Populate dropdown with menu items
        jQuery(".fixed-nav-center a").each(function() {

            var el = jQuery(this);

            jQuery("<option />", {
               "value"   : el.attr("href"),
               "text"    : el.text()
            }).appendTo(".fixed-nav-center select");
        });

           // To make dropdown actually work
           // To make more unobtrusive: http://css-tricks.com/4064-unobtrusive-page-changer/
        jQuery(".fixed-nav-center  select").change(function() {

        var elementClick = jQuery(this).find("option:selected").val();
        var destination = jQuery(elementClick).offset().top;

            jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 1000, function() {
                window.location.hash = elementClick
            });
        });

    });

jQuery('.home-nav ul li:first-child a ').addClass('current');

    jQuery('.home-nav .navul li a').click(function() {
        jQuery('.home-nav .navul li a').each(function(){
            jQuery(this).removeClass('current');
        });
        jQuery(this).addClass('current');
    });



jQuery('.sponsors-list .sponsors-wrap').hover(function(){

jQuery(this).animate({
backgroundColor:'#d4d4d4'
  }, 200, function() {
    // Animation complete.
  });
}, function () {
    jQuery(this).animate({
    backgroundColor:'#f2f2f2'
      }, 200, function() {
    // Animation complete.
  });
});

jQuery('.sponsors-wrap').each(function(){
    jQuery(this).hover(function(){
    jQuery('.sponsor-hover', this).animate({
        opacity:0.8,
        marginTop:0
      }, 200, function() {
        // Animation complete.
      });
    }, function () {
        jQuery('.sponsor-hover', this).animate({
        opacity:0,
        marginTop:20
          }, 200, function() {
        // Animation complete.
      });
    });
});

jQuery(".single-speakers").hover(
      function () {
            jQuery(this).animate({
                borderTopColor: "#d4d4d4",
                borderBottomColor: "#d4d4d4",
                borderRightColor: "#d4d4d4",
                borderLeftColor: "#d4d4d4"}, 'fast');
      },
      function () {
            jQuery(this).animate({
                borderTopColor: "#f2f2f2",
                borderBottomColor: "#f2f2f2",
                borderRightColor: "#f2f2f2",
                borderLeftColor: "#f2f2f2"}, 'fast');
      }
    );
});




jQuery.fn.anchorAnimate = function(settings) {

 	settings = jQuery.extend({
		speed : 1100
	}, settings);

	return this.each(function(){
		var caller = this
		jQuery(caller).click(function (event) {
			event.preventDefault()
			var locationHref = window.location.href
			var elementClick = jQuery(caller).attr("href")

			var destination = jQuery(elementClick).offset().top;
			jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination+10}, settings.speed, function() {
				window.location.hash = elementClick
			});
		  	return false;
		})
	})
}




