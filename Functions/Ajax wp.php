<script>
jQuery(document).ready(function(){
	
	var base_url = window.location.origin;
	
	jQuery('#customer_email_form').submit(function(e){
	
        e.preventDefault();
	
		var title = jQuery('input[name="title"]').val();
		var product_url = jQuery('input[name="url"]').val();
		var sku = jQuery('input[name="sku"]').val();
		var photo = jQuery('input[name="photo"]').val();
		var regular_price = jQuery('input[name="regular_price"]').val();
		var customer_name = jQuery('input[name="customer_name"]').val();
		var customer_email = jQuery('input[name="customer_email"]').val();
		//alert(customer_name);
		
		jQuery.ajax({
            type: 'POST',
			data: {action: 'share_with_customer', customer_name: customer_name, customer_email: customer_email, title: title, product_url: product_url, photo: photo, sku: sku, regular_price: regular_price},
			url: base_url+'/wp-admin/admin-ajax.php',
            success: function(response){ 
			//alert(response);
			jQuery('.customer_email_wrap').html(response);
                //statements on success
            }
        }); 
	
    });
});

</script>

<?php

//to send html in email
function wpse27856_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );

//Send customer email
//Mail function (send email to customer with product details)

add_action('wp_ajax_share_with_customer', 'share_with_customer');
add_action('wp_ajax_nopriv_share_with_customer', 'share_with_customer');
function share_with_customer(){
	$customer_name = $_POST['customer_name'];   
	$customer_email = $_POST['customer_email'];   
	$title = $_POST['title'];   
	$product_url = $_POST['product_url'];   
	$regular_price = $_POST['regular_price'];   
	$photo = $_POST['photo'];   
	$sku = $_POST['sku']; 
	
	//Request quote data
	
	//$attachments = array( WP_CONTENT_DIR . '/uploads/2021/05/'.$files );
	$attachments = '';
	
		$message = '<html><body><h1>Greetings!</h1>
					<p>Please find the <a href="'.$product_url.'">'.$title.'</a> product!<p>
					<p>You can visit our website to know more <a href="'.site_url().'">'.site_url().'</a></p>
					<p>Thanks!</p>
					</body></html>';

		//php mailer variables
		$to = $customer_email;
		$subject = "Product Quote from Eurocarkeys";
		$headers = array('Content-Type: text/html; charset=UTF-8','From: Euro Car keys <info@eurocarkeystore.com.au>');
		
		 //Here put your Validation and send mail
		$sent = wp_mail($to, $subject, $message, $headers, $attachments);
		  if($sent) {
			 echo 'Mail has been sent!';die;
		  }//message sent!
		  else  {
			echo 'Something went wrong!';die;
		  }//message wasn't sent 
		   
}  

?>