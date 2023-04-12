<?php
function get_reply_thread($parent_mail_id) {
    $CI =& get_instance();
    $session = $CI->session->userdata(USER_SESSION); 

    $current_user_id = $session['user_id'];
    $CI->load->model('Communicationemailmodel');
    
    $mail_data = $CI->Communicationemailmodel->get_mail_data($parent_mail_id);
    $sent_by = $mail_data->sent_by;

    $from_user_data = $CI->Communicationemailmodel->get_user_detail($sent_by);


    $return_reply = '';
    if (!empty($from_user_data) && !empty($mail_data)) {
      $parent_user_name = $from_user_data->name;
      $parent_user_email = $from_user_data->email;
      $message = $mail_data->message;
      
      $return_reply .= '<div class="mail-wrapper multiple">';
      $return_reply .= '<p class="mtop-20">' . $parent_user_name . ' wrote:<p>';
      $return_reply .= '<p class="mtbot-20">On '.$mail_data->date_time.', ' . $parent_user_name . ' <span><</span>' . $parent_user_email . '<span>></span>Wrote:</p>';
      $return_reply .= '<p>' . $message . '</p>';
      $return_reply .= '</div>';

      //echo 'here: '.$mail_data->parent_mail_id;

      // Check if the parent mail has a parent_email_id
      if ($mail_data->parent_mail_id != '') {
        // Get the reply thread for the parent mail recursively
        $parent_reply = get_reply_thread($mail_data->parent_mail_id);
        if (!empty($parent_reply)) {
          $return_reply .= '<div class="mail-wrapper">' . $parent_reply . '</div>';
        }
      }
    }

    return $return_reply;
  }