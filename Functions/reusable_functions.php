<?php
/***
 * With term id and property type get posts
 */
 function wp_transaction_shortcode() {
    // ob_start();

    $query = new WP_Query( array(
        'showposts' => -1,
    'post_type' => 'property',
    'tax_query' => array(
        array(
        'taxonomy' => 'property_status',
        'field' => 'term_id',
        'terms' => 108
    ))
    ) );
   
 if ( $query->have_posts() ) {

    $prices = [];
    while ($query->have_posts()): $query->the_post();
    $prices[] = get_post_meta( $post_id, 'fave_property_price' );
    //  $ID = get_the_id();
       
        //   echo $ID;
        
        
    endwhile;
    // echo count ($prices);

    wp_reset_postdata();

    return count ($prices);
    // return ob_get_clean();
}
}
add_shortcode( 'transaction', 'wp_transaction_shortcode' );
 
 
function propertyTotalPrices() {

     // ob_start();

     $query = new WP_Query( array(
        'showposts' => -1,
    'post_type' => 'property',
    'tax_query' => array(
        array(
        'taxonomy' => 'property_status',
        'field' => 'term_id',
        'terms' => 108
    ))
    ) );
   
 if ( $query->have_posts() ) {

    $prices = [];
    while ($query->have_posts()): $query->the_post();
    // $prices[] = get_post_meta( $post_id, 'fave_property_price' );
    $post_id = $query->post->ID;

    //  $ID = get_the_id();
    $val = get_post_meta( $post_id, 'fave_property_price' );
    
    $prices[] = $val[0];
       
        //   echo $ID;
        
        
    endwhile;
    // echo count ($prices);

    wp_reset_postdata();

    // return count ($prices);
    // print_r ($prices);
    return round(array_sum ($prices));
    // return ob_get_clean();
}
} 
  
add_shortcode( 'property_prices', 'propertyTotalPrices' );





///////////template part with variable////////////////
set_query_var( 'user_id', $user_id );
      get_template_part( 'production/compare/silver_stats_compare', 'part' );
	  
	  
/* To register taxonomy */

register_taxonomy( 'clients', array('the_custom_post_type'), array() );


//form submit ajax
$("#email_form").submit(function(e) {

		e.preventDefault(); 
		$('#modal').modal('hide'); //to hide modal
		var form = $(this);
		var url = form.attr('action');
		alert(form.serialize());
		$.ajax({
			   type: "POST",
			   url: url,
			   data: form.serialize(), // serializes the form's elements.
			   success: function(data)
			   {
				   alert(data); // show response from the php script.
				   
			   }
			 });

		
    });
    

// Order status changed if product category is crowdsource

add_action( 'woocommerce_thankyou', 'custom_woocommerce_change_order_status' );
function custom_woocommerce_change_order_status( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    // Specific categories: the term name/term_id/slug. Several could be added, separated by a comma
    $categories = array( 'crowdfunding',1843, 1903);

    $order = wc_get_order( $order_id );
    
    //$product_ids   = array('23'); // Here set your targeted product(s) Id(s)
    $product_found = false;
    
    // Loop through order items
    foreach ( $order->get_items() as $item ) {
        // Product ID
        $product_id = $item->get_variation_id() > 0 ? $item->get_variation_id() : $item->get_product_id();

        // Has term (product category)
        if ( has_term( $categories, 'product_cat', $product_id ) ) {
            $product_found = true;
            break;
        }
    }

   if($product_found ) {
        $order->update_status( 'wc-crowdsource-hold' );
    }
}


//Script/styles in function

/* Footer script / countdown script */
function myscript() {
?>
<script>
// Set the date we're counting down to
//var end_date = new Date("2021-06-12 23:59:59").getTime();
var end_date = jQuery('.campaign-end-date').data('end'); 
var countDownDate = new Date(end_date).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
  
	if(distance >= 0){
		jQuery('.categoryPosts').removeClass('product-pointer-none');
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  
  // Output the result in an element with id="demo"
  document.getElementById("days").innerHTML = days + " Days";
  document.getElementById("hours").innerHTML = hours + " Hours";
  document.getElementById("minutes").innerHTML = minutes + " Minutes";
  document.getElementById("seconds").innerHTML = seconds + " Seconds";
  
	}else{
		clearInterval(x);
		jQuery('.cap-timer').html('<h3 class="camp-expired" style="color:red;">Campaign Expired</h3>');
		
		jQuery('.categoryPosts').addClass('product-pointer-none');
		jQuery('.pledge-btn').hide();
	}
    
}, 1000);

/* Accordian goals */

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}

</script>
<style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc; 
}

.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
}
</style>
<?php
}
add_action( 'wp_footer', 'myscript' ); 

<?php
//Read more and read less
$result = substr($queried_object->description, 0, 200); //first 20 chars 
				echo '<p class="read-more">'.$result.'...<a>Read More</a></p>
				  <div class="full-text" style="display:none">
					'.$queried_object->description.'<a>Read Less</a>
				  </div>
				  ';
				}
?>
<script>
//Read more
    //jQuery(".full-text").hide();
    jQuery(".read-more").on("click", function () {
        jQuery('.full-text').fadeToggle(200);
		jQuery('.read-more').fadeToggle(200);
    });
	jQuery('.full-text a').click(function(){
		jQuery('.full-text').fadeToggle(200);
		jQuery('.read-more').fadeToggle(200);
	});
</script>


<?php

/* Filter by product attribute backend product list Admin dashboard */

add_action('restrict_manage_posts', 'product_attribute_sorting_dropdown');
function product_attribute_sorting_dropdown() {
    global $typenow;
    $taxonomy  = 'pa_product-sale-type';
    if ( $typenow == 'product' ) {
        $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        
        wp_dropdown_categories(array(
            'show_option_all' => __("{$info_taxonomy->labels->name}"),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => false,
        ));
    };
}

add_action('parse_query', 'product_attribute_sorting_query');
function product_attribute_sorting_query( $query ) {
    global $pagenow;
    $taxonomy  = 'pa_product-sale-type';
    $q_vars    = &$query->query_vars;

if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == 'product' && isset($_GET[$taxonomy]) && is_numeric($_GET[$taxonomy]) && $_GET[$taxonomy] != 0) {
        
        $tax_query = (array) $query->get('tax_query');
        $term = get_term_by('id', $_GET[$taxonomy], $taxonomy);

        $tax_query[] = array(
               'taxonomy' => $taxonomy,
               'field' => 'slug',
               'terms' => array($term->slug), 
               'operator' => 'AND'
        );
    
        $query->set( 'tax_query', $tax_query );
    }
}


/*Start Show metaboxes with html on product page backend or any other post type */

function wporg_add_custom_box() {
    $screens = [ 'post', 'product' ];
    foreach ( $screens as $screen ) {
        add_meta_box(
            'wporg_box_id',                 // Unique ID
            'Lost Keys and Zones',      // Box title
            'woo_add_custom_general_fields',  // Content callback, must be of type callable
            $screen                            // Post type
        );
    }
}
add_action( 'add_meta_boxes', 'wporg_add_custom_box' );

function wporg_custom_box_html( $post ) {
    ?>
    <label for="wporg_field">Description for this field</label>
    <select name="wporg_field" id="wporg_field" class="postbox">
        <option value="">Select something...</option>
        <option value="something">Something</option>
        <option value="else">Else</option>
    </select>
    <?php
} 
/*End Show metaboxes with html on product page backend or any other post type */

?>