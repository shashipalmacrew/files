<div class="mob-ems-database-filter">
<!-- 	<p>
		Back to Global EMS Databse
	</p> -->
	<?php $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 

	 $country_label = 'Select a country';
    if( isset($_GET['country']) &&  $_GET['country'] !='')
    {
      $country_label = $_GET['country'];
    } 


	?>
	<div class="custom-select">
		<div class="col-md-12" id="archive-browser"> 
    <div class="select-value"><?php echo $country_label; ?></div>
    <div id="post-cat" class="postform" ><div class="cat-wrap">

<?php
		$args = array (
			'category_name' => "database",
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'		
		);
    $posts = new WP_Query($args);
    if($posts->have_posts()) : ?>
    
    	<?php
        while ($posts->have_posts()) : $posts->the_post();
		$country = strtok(get_the_title(), ':');
?>

				<div class="cat-option"><a href="<?php echo get_the_permalink().'?country='.$country; ?>" <?php if($url == get_the_permalink()){ echo 'selected'; } ?>><?php echo $country; ?></a></div> 
		
<?php
	$country = "";
        endwhile;        
    endif;
    wp_reset_postdata();
?>
	</div></div></div>
	</div>
	</div>
