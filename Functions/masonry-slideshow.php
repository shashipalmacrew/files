<?php

/**
 * Masonary Slick slider shortcode
 */


function masonarySlickSlider(){
  $args = array(
    'p'         => 871, // ID of inside domaine post in Inside domaine slider post type
    'post_type' => 'inside_domain_slider'
  );
  $query = new WP_Query($args);

  $min=3;
  $max=8;
  

  if($query->have_posts()) :

    $slider_html = '<style>
      
      </style>';

     $i = 1;
       while($query->have_posts()) :

           $query->the_post() ;
            $gallery = get_field('gallery');

            $count = 1;
            $slider_html = '<div class="arch-slider">';
                  foreach($gallery as $gallery_image):
                      if ($count%3 == 1)
                      {
                          $slider_html .= "<div class='arch-slider-item 3'>";
                      }
                      $slider_html .= '<div class="arc-images"><img src="'.$gallery_image.'" alt="Architectural Mill Work 1" style="width:241px"></div>';
                      if ($count%3 == 0)
                      {
                          $slider_html .= "</div>";
                      }
                      $count++;
                  endforeach;
                  if ($count%3 != 1) $slider_html .= "</div>"; //This is to ensure there is no open div if the number of elements is not a multiple of 3

          $slider_html .= '</div>';
       endwhile;
       
       wp_reset_postdata();

    endif;
return  $slider_html;
 }

 add_shortcode( 'masonary_slick_slider', 'masonarySlickSlider' );



/**
 * Masonary Bx slider shortcode -- In case we need bxslider
 */

 function masonarySlider(){
  $args = array(
    'p'         => 871, // ID of inside domaine post in Inside domaine slider post type
    'post_type' => 'inside_domain_slider'
  );
  $query = new WP_Query($args);

  $min=3;
  $max=8;
  

  if($query->have_posts()) :

    $slider_html = '<style>
      
      </style>';

     $i = 1;
       while($query->have_posts()) :

           $query->the_post() ;
            $gallery = get_field('gallery');

            $count = 1;
            $slider_html .= '<div class="bxslider">';
            foreach($gallery as $gallery_image):
                $span_val = rand($min,$max);
                
                
                if ($count%6 == 1)
                {
                    $slider_html .= "<ul>";
                }
                $slider_html .= '<li class="span'.$span_val.' height2"><img src="'.$gallery_image.'" ></li>';
                if ($count%6 == 0)
                {
                    $slider_html .= "</ul>";
                }
                $count++;
            endforeach;
            if ($count%6 != 1) $slider_html .= "</ul>"; //This is to ensure there is no open div if the number of elements is not a multiple of 3
            $slider_html .= '</div>';
       endwhile;
       
       wp_reset_postdata();

    endif;
return  $slider_html;
 }

 add_shortcode( 'masonary_slider', 'masonarySlider' );


?>

<script>
/***
 * Masonary slick slider
 */


jQuery(document).ready(function() {
  jQuery('.arch-slider').slick({
      slidesToShow:2,
      slidesToScroll: 2,
      //centerMode: true,
      infinite: true,
      autoplay:true,
      autoplaySpeed:2000,
      arrows: true,
      variableWidth: true,
      prevArrow: '<span class="icon-arrow right-icon"></span>',
      nextArrow: '<span class="icon-arrow left-icon"></span>',
      responsive: [{
              breakpoint: 1000,
              settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1
              }
          },
          {
              breakpoint: 650,
              settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
          }
      ]
  });


  /*****************************
   * bxslider for inside domaine -- In case we need bxslider
  *
 jQuery('.bxslider').bxSlider({
  mode: 'fade',
  auto: true,
  easing: 'ease',
  controls:true,
  nextText: '<span class="icon-arrow left-icon"></span>',
  prevText: '<span class="icon-arrow right-icon"></span>'
});
*/
</script>
