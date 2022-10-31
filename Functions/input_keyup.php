<input type="text" class="form-control" required  name="propertyName" id="propertyName" placeholder="Type 3 letters of the property address or property title to auto pick full property address." onkeyup="property_removal_fatch_data()" data-sel_property_id="" >
                    <!-- onkeyup="property_removal_fatch_data()" -->
                    <div id="title_fetch"></div>


                    <!-- js -->
    <script>                

                    function property_removal_fatch_data(){
    var propertyName = jQuery('#property_removal_form').find('input[name="propertyName"]').val();
    var base_url = window.location.origin;
    if(jQuery.trim(propertyName) == "" || propertyName.length <= 2){
        jQuery('#title_fetch').html("");
        jQuery('#title_fetch').hide();
    }else{
        if(propertyName.length <= 2){
            return false;
        }
        // if(window.ajaxload == false){ 
        //     window.ajaxload = true;           
            jQuery('#propertyName').addClass("loading");
            jQuery.ajax({
                url: base_url+'/wp-admin/admin-ajax.php', 
                type: 'post',
                data: { action: 'fetch_property_name', propertyName: propertyName  },
                success: function(data) {
                    // window.ajaxload = false;
                    //console.log(data);
                    jQuery(document).find('#propertyName').removeClass("loading");
                    jQuery('#title_fetch').html( data );
                    jQuery('#title_fetch').show();
                },
                error : function(jqXHR, textStatus, errorThrown) {
                   //window.ajaxload = false;
                    jQuery(document).find('#propertyName').removeClass("loading");
                    loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
                   
                }
            });
       // }
    }
  }

</script>

  <?php

  //========================= fetch_property_name for property_removal ======================*/
            
    add_action('wp_ajax_fetch_property_name' , 'fetch_property_name');
    add_action('wp_ajax_nopriv_fetch_property_name','fetch_property_name');
    function fetch_property_name(){
        $propertyName = $_POST['propertyName'];
        global $wpdb;
        
        $results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."posts 
        RIGHT JOIN ".$wpdb->prefix."postmeta  
        ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id 
        WHERE ".$wpdb->prefix."postmeta.meta_key = 'fave_property_map_address' 
        AND (".$wpdb->prefix."postmeta.meta_value like '%".$propertyName."%' 

        OR ".$wpdb->prefix."posts.post_title LIKE '%".$propertyName."%')

        AND ".$wpdb->prefix."posts.post_status = 'publish' 
        
        AND post_type = 'property' ");

        
        if($results){
            foreach ($results as $key => $value) {
                //echo '<pre>';print_r($address); echo $value->post_id;echo $address_keywordss; echo "</pre>";
                $fave_property_map_address = get_post_meta( $value->ID, 'fave_property_map_address', true );
                echo '<div class="title-wrap" onclick="title_change_Value(this)" data-property_title="'.$value->post_title.'" data-property_id="'.$value->ID.'">'.$value->post_title.'<span class="property-adrs">'.$fave_property_map_address.'</span> </div>';
            }
        }else{
            echo '<h3>'.esc_html__('No Results Found', 'propertyName').'</h3>';
        }
        exit;
    } 


    /*js second step on select option*/
    /*==========================title_change_Value  ================================*/
    <script>
  function title_change_Value(o){
    document.getElementById('propertyName').value = o.getAttribute('data-property_title');
    document.getElementById('propertyName').setAttribute('data-sel_property_id',o.getAttribute('data-property_id'));
    jQuery('#title_fetch').html('');
    jQuery('#title_fetch').hide();
   }
   </script>
    