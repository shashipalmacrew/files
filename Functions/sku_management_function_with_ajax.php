<?php
		/*=============================<-- Product SKU management plugin  -->==================================================*/
		
		add_action( 'woocommerce_product_options_sku', 'woo_add_custom_inventory_fields');
		function woo_add_custom_inventory_fields() {

            global $post;
            $post_id =$post->ID;

			$taxonomy_objects = get_categories('taxonomy=sku_main_part&post_type=sku-management');  
			if($taxonomy_objects){ ?>
				<div class="options_group csm_grp" data-product_id="<?php echo $post_id; ?>">
					<p class="form-field custom_field_type">
						<label for="custom_field_type"><?php echo __( "Get SKU Code", "woocommerce" ); ?></label>
							<span class="wrap">
								<select name="txt-category-name" onchange="getSelMainSKU(this);" id="sku_main_id">
									<option value="" >Choose SKU Main</option><?php
										foreach ($taxonomy_objects as $t_object) {
											if($t_object->count >0){ 
												$category_id = $t_object->term_id; ?>
												<option value="<?php echo $category_id; ?>"><?php echo $t_object->name; ?></option>
												<?php
											} 
										} ?>
								</select>
								<select name="SKU_sub_name" onchange="getSkuCode(this);" id="SKU_sub_id"></select>
                                <input type="text" name="sku_code" id="sku_code_id" value=""/>
							</span>
					</p>
				</div><?php
                update_post_meta( $post_id, 'product_code', $post_id);
                if(get_post_meta( $post_id,'product_code', true ) != ''){
                      $product_code = get_post_meta( $post_id, 'product_code', true );  
                    }else{
                        $product_code ="";
                    }?>
			
				<div class="options_group ">
					<p class="form-field custom_field_type">
						<label for="custom_field_type"><?php  echo __( "Product Code", "woocommerce" ); ?></label>
							<span class="wrap">
								<input type="text" name="sku_code" id="product_code_id" value="<?php echo $product_code; ?>"/>
							</span>
					</p>
				</div>
			<?php }
            
		
		}
        
        /*=============================<-- Get product code  -->==================================================*/
        add_action( 'save_post', 'get_product_id_from_hook' );

        function get_product_id_from_hook($post_id){
            if(get_post_type($post_id) == "product"){
                $product        = wc_get_product( $post_id);
                $product_id     = $product->get_id();

                update_post_meta( $post_id, 'product_code', $product_id);
            }
        }
		//add_action( 'woocommerce_product_options_inventory_product_data', 'woo_add_product_code_custom_inventory_fields',10);
		function woo_add_product_code_custom_inventory_fields() { 
			         global $product;
                     $id = $product->get_id();
                   echo $id;
                   die('mzzzzs');
		}
	
    /*=============================<-- get_sku_code -->==================================================*/
    add_action('wp_ajax_get_sku_code' , 'get_sku_code');
    add_action('wp_ajax_nopriv_get_sku_code','get_sku_code');

	function get_sku_code() {
		
        global $wpdb;
		$prefix         = '';
		$prefix         = $wpdb->prefix;
		$table_name     = $prefix.'sku_management';
		$category_id    = $_POST['cate_id'];
		$Sub_post_id    = $_POST['Sub_post_id'];
        if(  !empty($category_id) && !empty($Sub_post_id) ){
            $results    = $wpdb->get_results( "SELECT * FROM ". $table_name." WHERE sku_sub_post_id = '".$Sub_post_id."' AND sku_category_id = '".$category_id."' ");
            
           
            if($results){
                $sku_code_max = max(array_column($results, 'sku_code'));
                $sku_code  = $sku_code_max+1;
                
                echo str_pad($sku_code, 5, "0", STR_PAD_LEFT);
            }else{
                echo str_pad(1, 5, "0", STR_PAD_LEFT);
            }
        }
		
		die();
    }

    /*=============================<-- update_product_sku -->==================================================*/
    add_action( 'save_post', 'update_product_sku_in_table' );

    function update_product_sku_in_table($post_id){
        if(get_post_type($post_id) == "product"){
            global $wpdb;
		    $prefix         = '';
		    $prefix         = $wpdb->prefix;
		    $table_name     = $prefix.'sku_management';
            $product        = wc_get_product( $post_id);
            $product_id     = $product->get_id();
            if($product->get_sku()){
                $exploded_sku   = explode("-",$product->get_sku());
                $sku_cat_slug   = $exploded_sku[0];
                $sku_sub_slug   = $exploded_sku[1];
                $sku_code       = $exploded_sku[2];
                $term           = get_term_by( 'slug', $sku_cat_slug , 'sku_main_part' );
                $post           = get_page_by_path( $sku_sub_slug, OBJECT, 'sku-management' );
                $sku_cat_id     = $term->term_id;
                $sku_sub_id     = $post->ID;
                $result    = $wpdb->get_row( "SELECT * FROM ". $table_name." WHERE sku_sub_post_id = '".$sku_sub_id."' AND sku_category_id = '".$sku_cat_id."'  AND product_id = '".$product_id."'AND sku_code = '".$sku_code."'");
                if($result ){
                    echo "Already exists";
                }else{
                    $results = $wpdb->insert($table_name, array(
                        'sku_sub_post_id'   => $sku_sub_id,
                        'sku_category_id'   => $sku_cat_id,
                        'product_id'        => $product_id,
                        'sku_code'          => $sku_code 
                    ));
                    if($results){
                        echo 'successfully inserted';
                    }else{
                        echo 'some thing want worng';
                    }
                }
                
            }
        }
    }
    
    /*=============================<-- Delete product sku -->==================================================*/
    add_action( 'before_delete_post', 'delete_product_sku_from_table' );
    

    function delete_product_sku_from_table($post_id){
        if(get_post_type($post_id) == "product"){
            global $wpdb;
		    $prefix         = '';
		    $prefix         = $wpdb->prefix;
		    $table_name     = $prefix.'sku_management';
            $product        = wc_get_product( $post_id);
            $product_id     = $product->get_id();
            if($product->get_sku()){
                $exploded_sku   = explode("-",$product->get_sku());
                $sku_cat_slug   = $exploded_sku[0];
                $sku_sub_slug   = $exploded_sku[1];
                $sku_code       = $exploded_sku[2];
                $term           = get_term_by( 'slug', $sku_cat_slug , 'sku_main_part' );
                $post           = get_page_by_path( $sku_sub_slug, OBJECT, 'sku-management' );
                $sku_cat_id     = $term->term_id;
                $sku_sub_id     = $post->ID;
            
                $results = $wpdb->delete( $table_name, array( 'sku_sub_post_id' => $sku_sub_id,
                                                                'sku_category_id' => $sku_cat_id,
                                                                'product_id' => $product_id,
                                                                'sku_code' => $sku_code  
                                                            ) );
                if($results){
                    echo 'successfully delete';
                }else{
                    echo 'some thing want worng';
                }
            }
        }
    }