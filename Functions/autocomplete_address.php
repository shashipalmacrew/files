<?php 
/*To add the address before shipping on cart page*/
add_action( 'woocommerce_cart_totals_before_shipping', 'add_autocomplete_field_on_cart' );  
 
function add_autocomplete_field_on_cart() {
	echo '<div id="locationField">
		<label for="autocomplete" class="">Search Address</label>
      <input id="autocomplete" autocomplete="off" onfocus="autocomplete" placeholder="Enter your address" type="text" >
    </div>';
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
	
	if(is_page('cart')|| is_page('checkout')
	){
		wp_enqueue_script( 'autocomplete-script', get_stylesheet_directory_uri().'/assets/autocomplete.js',array(),time(), true );
	  //wp_enqueue_script( 'map_api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD1z_r7OzHyBnwnpiLSKUq54LFcz9fqE-o&libraries=places' );

	  //Google map javascript API key: AIzaSyApXkY3L0vrtWXOYf37ab9vakNu9MbcA24
	  wp_enqueue_script( 'map_api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyApXkY3L0vrtWXOYf37ab9vakNu9MbcA24&libraries=places' );
		
	}

}

?>

<!-- autocomplete.js -->


     /* Autocomplete js */
    let placeSearch;
    let autocomplete;

  jQuery(document).bind('ready ajaxComplete',function(){
    
      initAutocomplete();
    }); 

  function get_autocomplete_field_id(){
   
    if (document.body.classList.contains('woocommerce-checkout')) {
      return 'billing_address_1';
    }
    else if (document.body.classList.contains('woocommerce-cart')) {
      return 'autocomplete';
    }else{
      return 'autocomplete';
    }
  }
function initAutocomplete() {
  //console.log('return',get_autocomplete_field_id());
	const componentForm = {
                    street_number: "short_name",
                    route: "long_name",
                    locality: "long_name",
                    administrative_area_level_1: "short_name",
                    country: "long_name",
                    postal_code: "short_name",
                    administrative_area_level_2: "long_name",
                };
    
        autocomplete = new google.maps.places.Autocomplete(
          document.getElementById(get_autocomplete_field_id()),
          { types: ["geocode"] }
        );
  // Avoid paying for data that you don't need by restricting the set of
  // place fields that are returned to just the address components.
  autocomplete.setFields(["address_component"]);
  // Set initial restrict to the greater list of countries.

  autocomplete.setComponentRestrictions({
    country: ["ID"],
  });

  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", fillInAddress);
}

// [START maps_places_autocomplete_addressform_fillform]
function fillInAddress() {

	const componentForm = {
          street_number: "short_name",
          route: "long_name",
          locality: "long_name",
          administrative_area_level_1: "short_name",
          country: "long_name",
          postal_code: "short_name",
          administrative_area_level_2: "long_name",
        };

  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();
  jQuery('#calc_shipping_postcode').val('');
  jQuery('#select2-calc_shipping_country-container').val('');
  jQuery('#calc_shipping_city').val('');
  jQuery('#select2-calc_shipping_state-container').val('');

  jQuery( "#billing_postcode" ).val('');
		jQuery( "#billing_city" ).val('');
		

  /*for (const component in componentForm) {
    document.getElementById(component).value=" ";
    document.getElementById(component).disabled = false;
  }*/
  //console.log('sds',place);
  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  for(const component of place.address_components) {
      const addressType = component.types[0];

    if(componentForm[addressType]) {
       const val = component[componentForm[addressType]];
      // console.table(val);
      //document.getElementById(addressType).value = val;
    
      // if(addressType=='country'){
      // 	var country_short_name = component.short_name;
      // 	var c = new Option(val, country_short_name);
			//   /// jquerify the DOM object 'o' so we can use the html method
      //   jQuery(c).html(val);
      //   jQuery(".country_select").append(c);
      //   jQuery('.country_select option[value="'+country_short_name+'"]').attr('selected','selected');
      //   // Check browser support
      //   if (typeof(Storage) !== "undefined") {
      //       // Store
       //         address_object.country_name = val;
       //         address_object.country_short_name = country_short_name;
      //       window.sessionStorage.setItem("country_name", val);
      //       window.sessionStorage.setItem("country_short_name", country_short_name);
      //     }
      // }

      if(addressType=='administrative_area_level_1'){
      	var state_short_name = component.short_name;
      	var state_long_name = component.long_name;
      	var s = new Option(state_long_name, state_short_name);
			/// jquerify the DOM object 's' so we can use the html method
			jQuery(s).html(state_long_name);
				jQuery(".state_select").append(s);
			  jQuery('.state_select option[value="'+state_short_name+'"]').attr('selected','selected');
			  // Check browser support
			
			if (typeof(Storage) !== "undefined") {
  				// Store
          // address_object.state_name = state_long_name;
          // address_object.state_short_name = state_short_name;
  				window.sessionStorage.setItem("state_name", state_long_name);
  				window.sessionStorage.setItem("state_short_name", state_short_name);
  			}
      }

      if(addressType=='administrative_area_level_2'){
      	
      	// Check browser support
		  	if (typeof(Storage) !== "undefined") {
          jQuery('#calc_shipping_city').val(val);
  				// Store
          // address_object.locality = val;
          jQuery( "#billing_city" ).val('');
          jQuery( "#billing_city" ).val(val);
  				window.sessionStorage.setItem("administrative_area_level_2", val);
  			}

      }if(addressType=='locality'){
      	
      	// Check browser support
		  	if (typeof(Storage) !== "undefined") {
          jQuery('#calc_shipping_city').val(val);
  				// Store
          // address_object.locality = val;
          jQuery( "#billing_city" ).val('');
          jQuery( "#billing_city" ).val(val);
  				window.sessionStorage.setItem("locality", val);
  			}
      }

      if(addressType=='postal_code'){
      	jQuery('#calc_shipping_postcode').val(val);
      	// Check browser support
        if (typeof(Storage) !== "undefined") {
  				// Store
          // address_object.postal_code = val;
          jQuery( "#billing_postcode" ).val(val);
  				window.sessionStorage.setItem("postal_code", val);
  			}
      }

      if(addressType=='street_number'){
      	  // Check browser support
          //console.log(val);
				if (typeof(Storage) !== "undefined") {
  				// Store	
           address_object.street_number = val;	  
  				window.sessionStorage.setItem("street_number", val);
  			}else{
          window.sessionStorage.setItem("street_number", '');
        }
      }

      if(addressType=='route'){
      	  // Check browser support
				if (typeof(Storage) !== "undefined") {
  				// Store
           address_object.route = val;
  				window.sessionStorage.setItem("route", val);
  			}else{
          window.sessionStorage.setItem("route", '');
        }
      }
    }
    if(address_object.street_number != null){
			var street_number = address_object.street_number;
		}else{
			var street_number = '';
		}
		if(address_object.route != null){
			var route = address_object.route;
		}else{
			var route = '';
		}
    jQuery( "#billing_address_1" ).val('');
    jQuery( "#billing_address_1" ).val(street_number+' '+route);
  }
  jQuery('.shipping-calculator-form button[name ="calc_shipping"]').trigger("click");

  // setTimeout(function(){
  //   jQuery( "#billing_postcode" ).val('');
	// 	jQuery( "#billing_city" ).val('');
	// 	jQuery( "#billing_address_1" ).val('');
  
	// 	jQuery( "#billing_postcode" ).val(window.sessionStorage.getItem("postal_code"));
	// 	jQuery( "#billing_city" ).val(window.sessionStorage.getItem("locality"));
	// 	jQuery( "#billing_address_1" ).val(window.sessionStorage.getItem("street_number")+','+window.sessionStorage.getItem("route"));
  //  }, 3000);
  
}

// jQuery(document).ready(function(){
//     jQuery(document).on('click', '.checkout-button', function(){
// 		var usps_shipping_selected_val = jQuery('#shipping_method li input[checked="checked"]').val();
// 		window.sessionStorage.setItem("usps_shipping_selected", usps_shipping_selected_val);
// 	});
    
// });


