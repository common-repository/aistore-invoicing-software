<?php

function aistore_add_bank(){
    
    if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
       if (isset($_POST['submit']) and $_POST['action'] == 'bank_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
               global $wpdb;
               $user_id = get_current_user_id();
                $company = aistore_books_getCompany($user_id);
                  
              $bank_name = sanitize_text_field($_REQUEST['bank_name']);
            
              $branch_name = sanitize_text_field($_REQUEST['branch_name']);
            
             $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_bank ( bank_name, branch_name,user_id,company_id ) VALUES (%s,%s,%s,%s)", array(
            $bank_name,
          
            $branch_name,
            $user_id,
            $company->id
           
        )));
        
           $id = $wpdb->insert_id;
                    ?>
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script type="text/javascript">
		
	toastr.success('Add Bank Successfully');
	</script>
<?php
           $message='Add Bank Successfully';
            sendNotification($id,$message);  
        
           
         $aistore_list_bank_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_list_bank') ,
                ) , home_url()));
                
            ?>
        
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_list_bank_url); ?>" />  
    
   
    <?php 
       
}
else{
?>
    
    <form method="POST" action="" name="bank_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

  <label for="title"><?php _e('Bank Name', 'aistore'); ?></label><br>
  <input class="input" type="text" id="bank_name" name="bank_name"><br>

  
  <label for="title"><?php _e('Branch Name', 'aistore'); ?></label><br>
  <input class="input" type="text" id="branch_name" name="branch_name"><br>
  
  <br>
         <input 
 type="submit" class="btn" name="submit" value="<?php _e('Submit', 'aistore') ?>"/>
<input type="hidden" name="action" value="bank_system" />
</form>  
<?php
    
}   
    
    
}