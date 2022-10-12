<?php

// Add theme image sizes
function mobfm_add_image_sizes() {
  add_theme_support( 'post-thumbnails' );

  if(function_exists('add_theme_support')) {

    if(function_exists('add_image_size')) {

      //talent images
      add_image_size('mobfm-talent-retina', 1080, 1080, true);
      add_image_size('mobfm-talent-full', 540, 540, true);

      //event images
      add_image_size('mobfm-event-retina', 1600, 1100, true);
      add_image_size('mobfm-event-full', 800, 550, true);
      add_image_size('mobfm-event-thumb', 320, 220, true);

      //sponsor images
      add_image_size('mobfm-sponsor-small', 300, 300, true);
      add_image_size('mobfm-sponsor', 400, 400, true);

      //ad images
      add_image_size('mobfm-sidebar-ad', 600, 500, true);
      add_image_size('mobfm-header-ad', 728, 90, true);

      //homepage images
      add_image_size('mobfm-tile-featured-r', 1254, 888, true);
      add_image_size('mobfm-tile-featured', 627, 444, true);
      add_image_size('mobfm-tile-large-r', 452, 494, true);
      add_image_size('mobfm-tile-large', 226, 247, true);
      add_image_size('mobfm-tile-small-r', 526, 412, true);
      add_image_size('mobfm-tile-small', 263, 206, true);

      //newsletter images
      add_image_size('mobfm_newsletter-featured', 600, 600, true);
      add_image_size('mobfm_newsletter-stories', 600,420, true);
      add_image_size('mobfm_newsletter-stories-small', 270,350, true);
    }
  }
}
add_action('after_setup_theme', 'mobfm_add_image_sizes');


//This upsizes thumbnails if uploaded too small for the wordpress image size
//For instance, if the image 'mobfm-event-full' is 846x477 and the image uploaded is only 800x400
//this function will upsize the image to 846x477
if (!function_exists('mobfm_thumbnail_upscale')) {
    function mobfm_thumbnail_upscale($default, $orig_w, $orig_h, $new_w, $new_h, $crop)
    {
        if (!$crop) {
            return null;
        } // let the wordpress default function handle this

        $aspect_ratio = $orig_w / $orig_h;
        $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

        $crop_w = round($new_w / $size_ratio);
        $crop_h = round($new_h / $size_ratio);

        $s_x = floor(($orig_w - $crop_w) / 2);
        $s_y = floor(($orig_h - $crop_h) / 2);

        return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
    }
}
//add_filter('image_resize_dimensions', 'mobfm_thumbnail_upscale', 10, 6);




function aw_custom_responsive_image_sizes($sizes, $size) {
  $width = $size[0];
  // blog posts
  if ( is_singular('mobfm_event') ) {
    // half width images - medium size
    if ( $width === 600 ) {
      return '(min-width: 768px) 322px, (min-width: 576px) 255px, calc( (100vw - 30px) / 2)';
    }
    // full width images - large size
    if ( $width === 1642 ) {
      return '(min-width: 821px) 821px, (min-width: 480px) 300px, calc(100vw - 30px)';
    }
    // default to return if condition is not met
    return '(max-width: ' . $width . 'px) 100vw, ' . $width . 'px';
  }
  elseif( is_post_type_archive('mobfm_event') && is_main_query() || is_home() ){
    if ( $width === 821 ) {
      echo "TESTING";
      return '(min-width: 768px) 266px, (max-width: 767px) 767px, 100vw';
    }
  }

  // default to return if condition is not met
  return '(max-width: ' . $width . 'px) 100vw, ' . $width . 'px';
}
//add_filter('wp_calculate_image_sizes', 'aw_custom_responsive_image_sizes', 10 , 2);


/**
 * Change the default "sizes" attribute created by WordPress
 * for the content archive thumbnails
 */

function lc_content_archive_thumbnail_image_sizes( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
  if ( is_post_type_archive('mobfm_event') && is_main_query() || is_home() ) {

    if(!is_search()):
		    $sizes = '(min-width: 769px) 266px, 100vw';
    endif;
	}
	return $sizes;
}
//add_filter( 'wp_calculate_image_sizes', 'lc_content_archive_thumbnail_image_sizes', 10, 5 );



function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'mobfm-event-full' === $size && !is_single() ) {
		$attr['sizes'] = '(min-width: 769px) 266px, 100vw';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );



?>
