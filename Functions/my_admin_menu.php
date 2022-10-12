<?php 
////////////////////Data table enqueue in admin/////////////////////////////
add_action('admin_enqueue_scripts', 'webroom_add_custom_js_file_to_admin');
function webroom_add_custom_js_file_to_admin( $hook ) {
	wp_enqueue_style( 'dataTables-style',"https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" );
  wp_enqueue_script ( 'dataTables', 'https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js' );
  wp_enqueue_script ( 'custom-js-admin', get_stylesheet_directory_uri()	 . '/assets/js/custom_admin.js', array(), true );
}
//==============Admin menu=====================*/
  function my_admin_menu() {
	add_menu_page(
		__( 'Credit Refund Plugin', 'credit-refund-plugin' ),
		__( 'Credit Refund', 'credit-refund' ),
		'manage_options',
		'credit-refund',
		'my_admin_page_contents',
		'dashicons-schedule',
		3
	);
}

add_action( 'admin_menu', 'my_admin_menu' );


function my_admin_page_contents() {
	get_template_part( 'template/credit_refund', 'page' ); 
	
}

/////////////////////credit_refund page content template file////////////////////////////////

admin_page_html();

function admin_page_html() {
  // check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  //Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

    global $wpdb;

  ?>
  <!-- Our admin page content should all be inside .wrap -->
  <div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      <a href="?page=credit-refund" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'All Info', 'my-project-info' ); ?></a>
      <a href="?page=credit-refund&tab=last-24-hrs" class="nav-tab <?php if($tab==='last-24-hrs'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Last 24 Hrs', 'my-project-info' ); ?></a>
      
    </nav>

    <div class="tab-content">
    <?php echo '<style>.info_table th {
    width: 50%;
    }
    .info_table{
        margin-bottom: 15px;
        margin-top: 15px;
    }
</style>';

    switch($tab) :
        
      case 'last-24-hrs':
       echo 'last24hrs_schools';
       
       
        break; 
       default:    
        
        $listing_wise_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."refund_package"); ?>
        <table id="listing_tab_table" class="display">
            <thead>
                <tr>
                    <th><?php echo esc_html__('First Name', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Last Name', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Email', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Refund Credit', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Reason', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Status', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Ticket', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Date', 'credit-refund-plugin'); ?></th>
                    <th><?php echo esc_html__('Action', 'credit-refund-plugin'); ?></th>
                </tr>
            </thead>
            <tbody><?php
                    foreach ($listing_wise_data as $key => $data) {
                      $the_user   =  get_user_by('id', $data->user_id); 
	                    $firstname  =  $the_user->user_firstname;
	                    $lastname   =  $the_user->user_lastname;
	                    $email      =  $the_user->user_email;
                      ?>
                         <tr> 
                            <td><?php echo ucfirst($firstname); ?> </td>
                            <td><?php echo ucfirst($lastname); ?> </td>
                            <td><a href="mailto:<?php echo $email; ?>" ><?php echo $email; ?></a></td>
                            <td><?php echo  $data->refund_credit; ?></td>
                            <td><?php echo  $data->refund_reason; ?></td>
                            <td><?php echo  $data->status; ?></td>
                            <td><?php echo  $data->ticket; ?></td>
                            <td><?php echo  $data->created_at; ?></td>
                            <td><button class="btn btn-info"  onclick="send_data_to_popup(this)">Send mail</button></td>
                          </tr>
                          
                    <?php  } ?>
            </tbody>
        </table>
       <?php
       break;  
           endswitch; ?>
           </div>
         </div>
         <?php
       }