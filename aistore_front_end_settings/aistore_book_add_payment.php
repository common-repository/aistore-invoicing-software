<?php 


function aistore_payment_instructions(){
    
       global $wpdb;
             $user_id = get_current_user_id();
             $company = aistore_books_getCompany($user_id);
     $results = $wpdb->get_row($wpdb->prepare("SELECT  * FROM {$wpdb->prefix}aistore_books_payment where user_id=%s and company_id=%s", $user_id,$company->id));
   
//   print_r($results);
     
  if (isset($_POST['submit']) and $_POST['action'] == 'payment_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
            
            $payment_instructions = sanitize_text_field($_REQUEST['payment_instructions']);
            
        
            
             
            if($results !== null){
                   $pid = $results->id;
                   
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_payment
    SET payment_instructions = '%s' WHERE company_id = '%d' and user_id=%s", $payment_instructions, $company->id,$user_id));
      $message='Update Payment Successfully';
            }
            
            else{
    
            
             $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_payment ( payment_instructions, company_id,user_id ) VALUES (%s,%s,%s)", array(
            $payment_instructions,
            $company->id,
            $user_id
           
        )));
         $pid = $wpdb->insert_id;
           $message='Add Payment Successfully';
            }
      
            sendNotification($pid,$message)
        ?>
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script type="text/javascript">
		
	toastr.success('Payment Successfully');
	</script>
	
<?php
       
            $aistore_add_product_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_add_product') ,
                ) , home_url()));
                
            ?>
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_add_product_url); ?>" /> 
    
    <?php   
             
}

?>
   
<form method="POST" action="" name="payment_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

  
  <label for="country"><?php _e('Payment Instructions', 'aistore'); ?></label><br>
  <textarea id="payment_instructions" name="payment_instructions" rows="3" cols="40">
      <?php
        $results = $wpdb->get_row($wpdb->prepare("SELECT  * FROM {$wpdb->prefix}aistore_books_payment where user_id=%s and company_id=%s", $user_id,$company->id));
   
     if($results !== null){
echo $results->payment_instructions; }?>
</textarea><br><br>
  
  
      
 <input type="submit" class="btn" name="submit" value="<?php _e('Submit', 'aistore') ?>"/>
<input type="hidden" name="action" value="payment_system" />

</form>  

   <?php
   
 
}


