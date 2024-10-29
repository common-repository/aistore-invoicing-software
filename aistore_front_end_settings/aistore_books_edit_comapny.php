<?php 


function aistore_edit_company(){
      global $wpdb;
        
             $user_id = get_current_user_id();
      $company_id = sanitize_text_field($_REQUEST['company_id']);
      
       $company = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_company where id=%s and user_id=%s", $company_id,$user_id));
       
       ?>
       <div>
         
       <img src="<?php echo esc_attr($company->logo); ?>"><br></div>
       
       <?php
       
  if (isset($_POST['submit']) and $_POST['action'] == 'company_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
            $company_name = sanitize_text_field($_REQUEST['company_name']);
            $pan_number = sanitize_text_field($_REQUEST['pan_number']);
            $gst_number = sanitize_text_field($_REQUEST['gst_number']);
            $address = sanitize_text_field($_REQUEST['address']);
            //  $logo = sanitize_text_field($_REQUEST['file']);
            
            
            
                 
            $fileType = $_FILES['file']['type'];
         $upload_dir = wp_upload_dir();

                if (!empty($upload_dir['basedir']))
                {

                    $user_dirname = $upload_dir['basedir'] . '/documents/' . $company_id;
                    if (!file_exists($user_dirname))
                    {
                        wp_mkdir_p($user_dirname);
                    }

                    $filename = wp_unique_filename($user_dirname, $_FILES['file']['name']);
                    move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']) , $user_dirname . '/' . $filename);

                    $image = $upload_dir['baseurl'] . '/documents/' . $company_id . '/' . $filename;
                    
                    
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_company
    SET logo = '%s' WHERE id = '%d' and user_id=%s", $image,$company_id,$user_id));
     ?>
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script type="text/javascript">
		
	toastr.success('Update Company Successfully');
	</script>
<?php
     $message='Update Company Successfully';
            sendNotification($company_id,$message);           

  //echo "<script>alert('Update Company Successfully');</script>";
 
       
                }      
}
else{
?>
   
<form method="POST" action="" name="company_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

  <label for="title"><?php _e('Company Name', 'aistore'); ?></label><br>
  <input class="input" type="text" id="company_name" name="company_name" value="<?php echo $company->company_name; ?>"><br>

  <label for="title"><?php _e('PAN Number', 'aistore'); ?></label><br>
  <input class="input" type="text" id="pan_number" name="pan_number" value="<?php echo $company->pan_number; ?>"><br>

  <label for="title"><?php _e('GST Number', 'aistore'); ?></label><br>
  <input class="input" type="text" id="gst_number" name="gst_number" value="<?php echo $company->gst_number; ?>"><br>
  
  <label for="country"><?php _e('Address', 'aistore'); ?></label><br>
  <textarea id="address" name="address" rows="3" cols="40">
<?php echo $company->address; ?>"
</textarea><br><br>
  
  
<label for="title"><?php _e('Upload Logo', 'aistore'); ?></label><br>
<input class="input" type="file" id="file" name="file" ><br><br>
      
 <input type="submit" class="btn" name="submit" value="<?php _e('Submit', 'aistore') ?>"/>
<input type="hidden" name="action" value="company_system" />

</form>  

   <?php
   
}   
}


