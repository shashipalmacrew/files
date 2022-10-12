<?php

function AllProductLoadMore_Shortcode() {  ?>
    <div id="ajax-posts" class="row">
    <?php
        
        $args = array(
          'post_type' => 'product',
          'posts_per_page' => 6,
          
                
        );
    
        $loop = new WP_Query($args);
    
        while ($loop->have_posts()) : $loop->the_post(); 
        $product = wc_get_product( get_the_id() ); ?>
          <div class="dokan-products-list">
             <a href="<?php echo get_the_permalink();?>"> <?php 
             
            if (has_post_thumbnail()) echo get_the_post_thumbnail();
            else echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?></a>
            
            <h1><?php the_title(); ?></h1>
            
            <?php 
            $terms = get_the_terms( get_the_ID() , 'product_cat' );
            if ( !empty( $terms ) ) 
            {
              foreach ( $terms as $term ) 
              {
                echo '<a href="/product-category/'.$term->slug.'">'.$term->name.'</a>';
              }
            } 
            $vendor_id = get_post_field( 'post_author', get_the_id() );
            echo '<div class="item-vendor">
            <span>Sold by </span>
            <a href="'.dokan_get_store_url($vendor_id).'">'.get_the_author_meta( 'display_name'  ).'</a>
            </div>';
            
           ?>
           <span class="price_product"><?php echo $product->get_price_html(); ?></span>
          </div>
    
     <?php
            endwhile;
    wp_reset_postdata();
     ?>
    </div>
    <div id="more_posts">Load More</div>
     <?php  }
     add_shortcode('AllProductLoadMore', 'AllProductLoadMore_Shortcode'); 

     //=====================================================
     add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
     add_action('wp_ajax_more_post_ajax', 'more_post_ajax');
function more_post_ajax(){

   
$page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;



$args = array(
    'suppress_filters' => true,
    'post_type' => 'product',
    'posts_per_page' => 6,
    'paged'    => $page,
);

$loop = new WP_Query($args);

$out = '';

if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
    $product = wc_get_product( get_the_id() );

    $out .= '<div class="dokan-products-list">';
    $out .= '<a href="'.get_the_permalink().'">' ; 
    
   if (has_post_thumbnail()){
    $out .=  get_the_post_thumbnail();
   } 
   else{
    $out .=  '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; 
   } 
   $out .='</a>';
   $out .='<h1>'.get_the_title().'</h1>';
   
   
   $terms = get_the_terms( get_the_ID() , 'product_cat' );
   if ( !empty( $terms ) ) 
   {
     foreach ( $terms as $term ) 
     {
        $out .= '<a href="/product-category/'.$term->slug.'">'.$term->name.'</a>';
     }
   } 
   $vendor_id = get_post_field( 'post_author', get_the_id() );
    
   $out .='<div class="item-vendor">
   <span>Sold by </span>
   <a href="'.dokan_get_store_url($vendor_id).'">'.get_the_author_meta( 'display_name'  ).'</a>
   </div>';
   $out .= '<span class="price_product">'.$product->get_price_html().'</span>';
 $out .='</div>';
   
endwhile;
endif;
echo $out;
wp_reset_postdata();
exit;
}


?>


<script>
//==================js code for loadmore==============================
jQuery(document).ready(function()
{
  window.ajaxload = false;
var pageNumber = 1;
function load_posts(){
  if(window.ajaxload == false){
  pageNumber++;
  var base_url = window.location.origin; 
  var ppp = '6'; 
  


  var str = '&pageNumber=' + pageNumber + '&ppp=' + ppp + '&action=more_post_ajax';
  
    window.ajaxload = true;
    jQuery('#more_posts').addClass("loading");
  jQuery.ajax({
    url: base_url+'/wp-admin/admin-ajax.php', 
    type: 'POST',
     dataType: "html",
      data: str,
      success: function(data){
        jQuery('#more_posts').removeClass("loading");
          var data = jQuery(data);
          if(data.length){
            jQuery("#ajax-posts").append(data);
            
            //jQuery("#more_posts").attr("disabled",false);
          } else{
            //jQuery("#more_posts").attr("disabled",true);
            jQuery("#more_posts").addClass("dis");
          }
          window.ajaxload = false;
      },
      error : function(jqXHR, textStatus, errorThrown) {
        jQuery('#more_posts').removeClass("loading");
        loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
        window.ajaxload = false;
      }
  });
  }
  
  return false;
}
  
  // jQuery("#more_posts").on("click",function(){ // When btn is pressed.
  //   jQuery("#more_posts").attr("disabled",true); // Disable the button, temp.
  //   load_posts();
  // });
  
  jQuery(window).on('scroll', function () 
  {
      if(jQuery('#more_posts').hasClass('dis'))
      {
        return false;
      }
      else
      {
        if (jQuery(window).scrollTop() + jQuery(window).height()  >= jQuery(document).height() - 100) 
        {
          load_posts();
        }
      }
  });
  
    
  
  
  
  });
  </script>