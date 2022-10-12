	//To scroll the section with id in the url
  jQuery(document).ready(function () { 
		  if (window.location.hash) scroll(0, 0);
		  setTimeout(scroll(0, 0), 1);
		  var hashLink = window.location.hash;
			if (jQuery(hashLink).length) {
			  jQuery(function () {
				  jQuery('html, body').animate({
					scrollTop: jQuery(window.location.hash).offset().top - 200
				  }, 2000);
			  });
			}
    });
	
	
	/*After ajax complete run jquery */
	
	jQuery('.booked-appt-list span.button-text').click(function(e){
	e.preventDefault();
		jQuery( document ).ajaxComplete(function() {
	  jQuery('#booked-textfield-single-line-text-label---8570083').val('Meri Valuie');
	});
		
	});