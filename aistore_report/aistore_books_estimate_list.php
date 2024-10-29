<?php 

function aistore_list_estimate(){

       if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
 global $wpdb;
    $user_id = get_current_user_id();
    
    
		      if (isset($_POST['submit']) and $_POST['action'] == 'estimate_email')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
              $estimate_id = sanitize_text_field($_REQUEST['estimate_id']);
             $customer_id = sanitize_text_field($_REQUEST['customer_id']);
             
  $aistore_estimate_email_details_url = add_query_arg(array(
                    'page_id' =>get_option('aistore_estimate_email_page') ,
                    'estimate_id' => $estimate_id,
                     'customer_id' => $customer_id,
                ) , home_url());
                
                
                ?>
         <meta http-equiv="refresh" content="0; URL=<?php echo esc_url($aistore_estimate_email_details_url); ?>" />
                
                <?php
        }


    $company = aistore_books_getCompany($user_id);
        
$results = aistore_books_getEstimates($user_id,$company->id);

        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Estimate List Not Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Customer Name', 'aistore'); ?></th>
           <th><?php _e('Customer Email', 'aistore'); ?></th>
            <th><?php _e('Product', 'aistore'); ?></th>
             <th><?php _e('Amount', 'aistore'); ?></th>
            <th><?php _e('Date', 'aistore'); ?></th>
          <th><?php _e('Action', 'aistore'); ?></th>
       
       
</tr>

    <?php
            foreach ($results as $row):
    
  $aistore_estimate_details_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_estimate_details') ,
                    'estimate_id' => $row->id,
                ) , home_url()));
                
                 $customer = aistore_books_getcustomerbyid($user_id,$company->id,$row->customer_id);
                 
                   $str_arr = explode(",", $row->product_id);
    				$total_amount =0;
        foreach ($str_arr as $key => $product_id)
        {
$product = aistore_books_getproductbyid($user_id,$company->id,$product_id);

$total_amount= $total_amount + $product->amount;
}

$total_amount= $total_amount+$row->fee;

?>    <tr>
          
		   <td> <a href="<?php echo esc_url($aistore_estimate_details_url); ?>"  ><?php echo esc_attr($row->id); ?> </a></td>
		    <td> 	
 
   <?php echo esc_attr($customer->customer_name) ?>
		  </td>
		   <td> 	
 
   <?php echo esc_attr($customer->customer_email) ?>
		  </td>
		   
		       <td> 	
 
   <?php echo esc_attr($product->name) ?>
		  </td>
		  
		  <td> 	
 
   <?php echo esc_attr($total_amount)." ".esc_attr($row->currency) ?>
		  </td>
		  
		    <td> 	
 
   <?php echo esc_attr($row->created_at) ?>
		  </td>
		   
		  <td>  
     
             
    <form method="POST" action="" name="estimate_email" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input class="input" type="hidden" id="estimate_id" name="estimate_id" value="<?php echo esc_attr($row->id); ?>"><br>
   <input class="input" type="hidden" id="customer_id" name="customer_id" value="<?php echo esc_attr($row->customer_id); ?>"><br>
  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Send Email', 'aistore') ?>"/>
<input type="hidden" name="action" value="estimate_email" />

</form>

		  </td>
	
 
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
    
    <?php
}

?>