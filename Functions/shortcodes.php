<?php 
function get_video( $atts){
 		$terms=$atts['category'];
 		//echo $terms;
 		  	$args = array(
			  'post_type' => 'attachment',
			  'numberposts' => -1,
			  'post_status' => null,
			  'post_parent' => null,
			  'post_mime_type' => 'video/mp4',
			  'tax_query' => array(
			  	array( 'taxonomy' => 'attachment_category', 'field' => 'slug', 'terms' => $terms)
				)
			); 
		$attachments = get_posts( $args );
		$video="";
			if ( $attachments ) { 
				$video ='<div class="vid-wrap">'; ?>
 					<?php foreach ( $attachments as $post ) {
  					 // print_r($post);
					    $link=$post->guid;
					    $title=$post->post_title;
					    $video.='<div id="vidBox">
					  	<div id="videCont">
						    <video autoplayid="demo" loop controls>
						        <source src="'.$link.'" type="video/mp4">
						    </video>
					  	</div>
						<div class="title-name">'.$title.'</div>
					</div>';
					 } 
 				$video.='</div>';?>
			<?php }
		//print_r($video);
		return $video;
	}
add_shortcode('video-short-code', 'get_video');  

function check_user_and_active_user() { 
 	if ( is_user_logged_in() ) {
		$user=wp_get_current_user();
		if(!is_super_admin()){
			$user_active = get_user_meta( $user->ID,'active_user_key',true );
			if($user_active!=1 ){  ?> 
				<div class="wrap-login">
					<img class="img-login" src="<?php echo site_url('/wp-content/uploads/2020/05/access_issue.jpg'); ?>">
					<div class="txt-login">We are working to make your account active. Please come check back later! Thanks.</div>
				</div>
				<script type="text/javascript">
					non_active_user();
				</script>
				 <?php
			}	
		}
	}
}
add_shortcode('check-active-user', 'check_user_and_active_user'); 



/*get messages to agents on dashboard*/

function get_messages_data(){

    $current_user_id = get_current_user_id();

    $man_code = get_user_meta( $current_user_id,'manager_code',true ); 

    //echo $man_code;

    global $wpdb;

    $user = new WP_User( $current_user_id );

	if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
	    foreach ( $user->roles as $role )
	        $role;
	}

	$where = '';
	if($role == 'user-management-manager'){
		$where .= 'WHERE manager_message = 1 AND sent_by = '.$current_user_id;
		$listing_wise_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."message ".$where." ORDER BY created_at DESC");

		$message_data = '<h4>My Messages to the agents</h4>';
	}else{
		if($man_code != ''){
			$listing_wise_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."message WHERE agent_message = 1 AND manager_access_code = '".$man_code."' ORDER BY created_at DESC");
			//echo $wpdb->last_query;die;
		}
			$message_data = '<h4>Messages From Manager</h4>';			
	}

	if(!empty($listing_wise_data)){

	    $message_data .= '<table id="agent_list" class="table table-striped table-bordered" style="width:100%">
	                    <thead>
	                    <tr>
	                    <th>Message</th>
	                    <th>Sent By</th>
	                    <th>Date & Time</th>
	                    </tr>
	                    </thead>
	                    <tbody> 
	                    ';
	    foreach ( $listing_wise_data as $data) {
	        // echo '<pre>';
	        // print_r($data);
	        // echo '</pre>';

	        // Get user by ID, email, slug or login
			$user = get_user_by( 'ID', $data->sent_by );

			// Get display name from user object
			$display_name = $user->display_name;

	        $message_data .= '<tr>';
	            $message_data .= '<td>' . esc_html( $data->Message ).'</td>'; 
	            $message_data .= '<td>' . esc_html( $display_name ).'</td>'; 
	            $message_data .= '<td>' . esc_html( $data->created_at ).'</td>'; 

	        $message_data .= '</tr>';
	    }
    	$message_data .= '</tbody></table>';
     
     
    	return $message_data;
    }
}

 add_shortcode('get_messages', 'get_messages_data');


 /*get all admin messages on manager dasboard*/

function get_admin_messages_data(){

    global $wpdb;

    $admin_users = get_users( array( 'role__in' => array( 'author', 'administrator', 'editor' ) ) );
		// Array of WP_User objects.

    	$i = 1;
    	$admin_ids = '';
		foreach ( $admin_users as $user ) {
		    //echo '<span>' . esc_html( $user->ID ) . '</span>';

		    if($i == 1){
		    	$admin_ids .= $user->ID;
		    }else{
		    	$admin_ids .= ','.$user->ID;
		    }
		    $i++; 
		}

	//$where = 'WHERE sent_by IN('.$admin_ids.')';


	$where = '';
	if($role == 'user-management-manager'){
		$where = 'WHERE manager_message = 1 AND sent_by IN('.$admin_ids.')';
		$listing_wise_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."message ".$where." ORDER BY created_at DESC");

	}else{
			$where = 'WHERE agent_message = 1 AND sent_by IN('.$admin_ids.')';
			$listing_wise_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."message ".$where." ORDER BY created_at DESC");	
		
	}

    //$listing_wise_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."message ".$where." ORDER BY created_at DESC");

    if(!empty($listing_wise_data)){

	    $message_data = '<h4>Admin Messages</h4><table id="agent_list" class="table table-striped table-bordered" style="width:100%">
	                    <thead>
	                    <tr>
	                    <th>Message</th>
	                    <th>Sent By</th>
	                    <th>Date & Time</th>
	                    </tr>
	                    </thead>
	                    <tbody> 
	                    ';
	    foreach ( $listing_wise_data as $data) {
	        // echo '<pre>';
	        // print_r($data);
	        // echo '</pre>';

	        // Get user by ID, email, slug or login
			$user = get_user_by( 'ID', $data->sent_by );

			// Get display name from user object
			$display_name = $user->display_name;

	        $message_data .= '<tr>';
	            $message_data .= '<td>' . esc_html( $data->Message ).'</td>'; 
	            $message_data .= '<td>' . esc_html( $display_name ).'</td>'; 
	            $message_data .= '<td>' . esc_html( $data->created_at ).'</td>'; 

	        $message_data .= '</tr>';
	    }
	    $message_data .= '</tbody></table>';
	     
	    return $message_data;
	}else{
    	return 'No Messages Found';
    }
}

 add_shortcode('get_admin_messages', 'get_admin_messages_data');


/*Send messages on manager dashboard not admin dashboard*/

if(isset($_POST['message_submit'])){
       // print_r($_POST);die;
         $message = $_POST['message'] ? $_POST['message'] : '';
    $agent_message = $_POST['agent_message'] ? $_POST['agent_message'] : 0;
    $manager_message = $_POST['manager_message'] ? $_POST['manager_message'] : 0;
    $status = $_POST['status'];
    $sent_by = get_current_user_id();
    $manager_access_code = get_user_meta( get_current_user_id(),'access_code',true ); 

        global $wpdb;
        $table_name = $wpdb->prefix . 'message';
        $wpdb->insert($table_name, array
            ('message' => $message, 'agent_message' => $agent_message, 'manager_message' => $manager_message, 'sent_by'=> $sent_by,'manager_access_code' => $manager_access_code, 'status' => $status)
        );

}
      

function dashboard_send_message(){	 

	$send_message_layout = '<form method="POST">
		    <div style="margin-bottom:10px;">
		        <label for="litespeed_vpi_list">Message</label>
		        <textarea style="width:100%" rows="5" id="litespeed_vpi_list" name="message" required spellcheck="false"></textarea>
		        
		    </div>
		    <div style="margin-bottom:10px;align-items: center;gap: 2ch;justify-content: space-between;">
		        <label for="litespeed_no_cache">Enable this to show the message to the Agent</label>
		        <input class="litespeed-tiny-toggle" id="agent_message" name="agent_message" type="checkbox" value="1">';
		        
		        $user = wp_get_current_user();
				$allowed_roles = array('editor', 'administrator', 'author');
				if( array_intersect($allowed_roles, $user->roles ) ) {  
				   // Stuff here for allowed roles
					$send_message_layout .= '<label for="litespeed_no_cache">Enable this to show the message to the Manager</label>
		        	<input class="litespeed-tiny-toggle" id="manager_message" name="manager_message" type="checkbox" value="1">';
				 }

		    $send_message_layout .= '</div>
		    <div style="margin-bottom:10px;">
		        <label for="litespeed_no_cache">Status</label>
		        <select name="status" size="1">
		                <option value="active">Active
		                <option value="inactive">Inactive
		            </select>
		    </div>
		    <input type="submit" class="button add-location-group" name="message_submit" value="SUBMIT">
		    <input type="reset" class="button add-location-group" value="CLEAR">
		</form>';

		return $send_message_layout;
}

add_shortcode('manages_send_messages', 'dashboard_send_message');


/*get agents list on dashboard*/

function get_agents_data(){

    $current_user_id = get_current_user_id();

    $man_code = get_user_meta( $current_user_id,'access_code',true ); 

    $args = array(
    'role'    => 'agent',
    'meta_key' => 'manager_code',
    'meta_value' => $man_code,
    'orderby' => 'user_nicename',
    'order'   => 'ASC'
    );

    // if($man_code !== ''){
    //     $args['meta_key'] = 'manager_code';
    //     $args['meta_value'] = $man_code;

    // }

    $users = get_users( $args );

    $users_data = '<table id="agent_list" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                    <th>Agent ID</th>
                    <th>Agent Name</th>
                    <th>Email</th>
                    <th>Manager Code</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';
    foreach ( $users as $user ) {
        // echo '<pre>';
        // print_r($user);
        // echo '</pre>';

        $manager_code = get_user_meta( $user->ID,'manager_code',true );

        $users_data .= '<tr>';
            $users_data .= '<td>' . esc_html( $user->ID ).'</td>';
            $users_data .= '<td>' . esc_html( $user->display_name ).'</td>';
            $users_data .= '<td>' . esc_html( $user->user_email ).'</td>';        
            $users_data .= '<td>' . $manager_code.'</td>';        
        $users_data .= '</tr>';
    }
    $users_data .= '</tbody></table>';
     
    return $users_data;
}

 add_shortcode('get_agents', 'get_agents_data');


 /* Get menu on dashboard */
 function get_menu($args){
    $menu = isset($atts['menu']) ? $atts['menu'] : '';
    ob_start();
    wp_nav_menu(array(
        'menu' => $menu,
		'menu_class'		=> "et-menu nav", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
		'menu_id'			=> "", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
		'container'			=> "", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
		'container_class'	=> "", // (string) Class that is applied to the container
    ) );
    return ob_get_clean();
}
add_shortcode('get_menu', 'get_menu');
?>