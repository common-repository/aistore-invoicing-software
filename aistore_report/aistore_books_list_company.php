

<?php
function aistore_list_company(){

       if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
 global $wpdb;
 
   $user_id = get_current_user_id();
   
   	      if (isset($_POST['submit']) and $_POST['action'] == 'company_email')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
              $company_id = sanitize_text_field($_REQUEST['company_id']);
          
             $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_company where user_id=%s and status='Enabled'",$user_id));
             
             if ($results != null)
        {
             $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_company
    SET status = '%s'  WHERE id = '%d'", 'Disabled', $results->id));
        }
        
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_company
    SET status = '%s'  WHERE id = '%d'", 'Enabled', $company_id));
          
        
        }
  
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_company where user_id=%s ", $user_id));



?></div>

<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Company List Not Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e(' Name', 'aistore'); ?></th>
         <th><?php _e('Pan Number', 'aistore'); ?></th>
 <th><?php _e('GST Number', 'aistore'); ?></th>
 <th><?php _e('Action', 'aistore'); ?></th>
</tr>

    <?php
            foreach ($results as $row):
                
      $aistore_company_details_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_edit_company') ,
                    'company_id' => $row->id,
                ) , home_url()));
 
?>    <tr>
  <td> <a href="<?php echo esc_url($aistore_company_details_url); ?>"  ><?php echo esc_attr($row->id); ?> </a></td>
	
		    <td>   <?php echo esc_attr($row->company_name); ?> </td>
    <td> 	
 
   <?php echo esc_attr($row->pan_number) ?>
		  </td>
		   <td> 	
 
   <?php echo esc_attr($row->gst_number) ?>
		  </td>   
		  	 
		    <td>  
		    
		    <?php  
		    
		    if($row->status == "Disabled"){
		        
		    
	
        ?>
      
    <form method="POST" action="" name="company_email" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input class="input" type="hidden" id="company_id" name="company_id" value="<?php echo esc_attr($row->id); ?>"><br>
 
  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Enable', 'aistore') ?>"/>
<input type="hidden" name="action" value="company_email" />

</form>

<?php

}

else{

 
 echo esc_attr($row->status) ;
   
	


}
?>
		  </td>
	
 
	
 
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
    
<?php }

?>