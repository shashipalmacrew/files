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

/********************Start product Category and subategory in functions.php*******************/


add_shortcode('custom_category_filter', 'filter_cats');

function filter_cats(){
	add_action('init', 'my_woo_cats');

	my_woo_cats();
}

//

function my_woo_cats(){
	//$cats = get_terms( array( 'taxonomy' => 'product_cat', 'orderby' => 'ASC') );


$taxonomy     = 'product_cat';
  //$orderby      = 'name';  
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no  
  $title        = '';  
  $empty        = 0;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
 $all_categories = get_categories( $args );

echo '<div class="col-md-12">
<h5 class="widget_title">PRODUCT CATEGORIES</h5>
						<ul class="product-cats">
							';

 foreach ($all_categories as $cat) {
    if($cat->category_parent == 0) {
       $category_id2 = $cat->term_id;   
       //echo  $category_id2;  
        

        $args2 = array(
                'taxonomy'     => 'product_cat',
                'child_of'     => 0,
                'parent'       => $category_id2,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
        );
        $sub_cats2 = get_categories( $args2 );
        if(!$sub_cats2) {
			echo '<li class="main-item" id="cat_'.$category_id2.'"><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a>';
        }else{
        	echo '<li class="cat-item" id="cat_'.$category_id2.'">'. $cat->name ;
        
        	echo '<ul class="subMenu sub-cat-wrap">';
            foreach($sub_cats2 as $sub_category2) {

                $category_id_3 = $sub_category2->term_id;
                $args3 = array(
                'taxonomy'     => 'product_cat',
                'child_of'     => 0,
                'parent'       => $category_id_3,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
		        );
		        $sub_sub_cats = get_categories( $args3 ); 
		        	if(!$sub_sub_cats) {
						echo '<li class="main-item" id="cat_'.$category_id_3.'"><a href="'. get_term_link($sub_category2->slug, 'product_cat') .'">'. $sub_category2->name .'</a>';
			        }else{
			        	echo '<li class="cat-item" id="cat_'.$category_id_3.'">'. $sub_category2->name;
			        
		        	echo '<ul class="subMenu sub-cat-child-wrap">';
		        	foreach($sub_sub_cats as $sub_category3){
		        		echo '<li class="main-item"><a href="'. get_term_link($sub_category3->slug, 'product_cat') .'">'. $sub_category3->name .'</a>';
		        	}
		        	echo '</ul>';
		        }
		        

		    }  
		    	echo '</li>'; 
		    echo '</ul>';
        }
        echo '</li>';
    }       
}

echo '</ul>
					</div>';


}

//echo do_shortcode('[custom_category_filter]');

/********************End product Category and subategory in functions.php*******************/


    function trekmedics_posts_shortcode( $atts ) {
    extract( shortcode_atts( array( 'category' => ''), $atts ) );

    global $paged;
    $q = new WP_Query(  array ( 
        'post_type' => 'post', 
        "orderby" => 'meta_value',
        "order" => 'ASC',
        'category_name' => $category
    ) );

    $list = '<div class="recent-events">';

    while ( $q->have_posts() ) {
        $q->the_post();
        //get_post_meta( get_the_ID(), 'evt_location', true );
        $post_thumbnail =  get_the_post_thumbnail( $post_id, 'thumbnail', array( 'class' => 'alignleft' ) );
        $post_link = get_field( "post_link", get_the_ID() );
        $list .= '<div class="custom-post-wrap"><div class="post-image"><a href="'.$post_link.'" target="_self" rel="bookmark noopener noreferrer">'.$post_thumbnail.'</a></div>
             <div class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></div></div>';
    }

    wp_reset_query();

    wp_reset_postdata(); 

    return $list . '</div>';
}
add_shortcode( 'trekmedics_posts_grid', 'trekmedics_posts_shortcode' );


//echo do_shortcode('[trekmedics_posts_grid category="in-the-news"]'); 

?>