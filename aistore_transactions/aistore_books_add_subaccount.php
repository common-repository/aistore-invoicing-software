<?php

function aistore_add_subaccount(){
    if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
    $user_id = get_current_user_id();
      $company = aistore_books_getCompany($user_id);
      
       if (isset($_POST['submit']) and $_POST['action'] == 'subcategory_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
             global $wpdb;
              
              $account = sanitize_text_field($_REQUEST['account']);
                  $subaccount = sanitize_text_field($_REQUEST['subaccount']);
                $type = sanitize_text_field($_REQUEST['type']);
           
           
                         
            
           $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_subaccounts ( account,subaccount, type, user_id ,company_id) VALUES (%s ,%s,%s,%s,%s)", array(
            $account,
            $subaccount,
            $type,
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
		
	toastr.success('Add Subaccount Successfully');
	</script>
<?php
      
               $message='Add Subaccount Successfully';
            sendNotification($id,$message);    
         $aistore_list_subaccount_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_list_subaccount') ,
                ) , home_url()));
                
               // header("Location: ".$aistore_list_subaccount_url);
               
                 ?>
        
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_list_subaccount_url); ?>" />  
    
   
    <?php 
       
                
        
}
else{
?>
    
    <form method="POST" action="" name="subcategory_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

<?php 
  global $wpdb;
          
              
 $results = aistore_books_getAccount($user_id,$company->id)
 
 
        
        
?>
<label for="title"><?php _e('Account', 'aistore'); ?></label><br>
       <select name="account" id="account" >
                <?php
            foreach ($results as $c)
            {

                echo '	<option  value="' . esc_attr($c->id) . '">' . esc_attr($c->account) . '</option>';

            }
?>
           
  
</select><br>

   
         
           
           
  <label for="title"><?php _e('Subaccount', 'aistore'); ?></label><br>
  <input class="input" type="text" id="subaccount" name="subaccount"><br>

        <label for="title"><?php _e('Type', 'aistore'); ?></label><br>
       <select name="type" id="type" >
	<option   value="debit"><?php _e('Debit', 'aistore'); ?></option>
	<option  value="credit"><?php _e('Credit', 'aistore'); ?></option>


           </select><br>    <br>   
         <input 
 type="submit" class="btn" name="submit" value="<?php _e('Submit', 'aistore') ?>"/>
<input type="hidden" name="action" value="subcategory_system" />
</form>  
<?php
    
}   
    
    
}