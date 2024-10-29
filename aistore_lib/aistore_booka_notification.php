 <?php
 
 
 function sendNotification($id,$message){
     

    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;
    $subject = $message;

    $n = array();
    $n['message'] = $message;
     $n['reference_id'] = $id;
    $n['type'] = "success";
    $n['user_id'] = $user_id;
    $n['user_email'] = $user_email;
   $n['subject'] = $subject;
    aistore_books_notification($n);
    aistore_books_send_email($n);

  }
  
  function sendEmail($id,$message,$m){
   
     $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;
    $subject = $message;

    $n = array();
    $n['message'] = $m;
     $n['user_email'] = $user_email;
   $n['subject'] = $subject;

    aistore_books_send_email($n);
  }
  
  ?>
  