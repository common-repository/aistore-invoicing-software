<?php 

function aistore_list_invoice(){

       if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
 global $wpdb;
 
 
    $user_id = get_current_user_id();
                  $company = aistore_books_getCompany($user_id);
  
       
$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_invoice where user_id=%s and company_id = %s ", $user_id,$company->id));



?></div>

<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Invoice List Not Found', 'aistore');
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
    
  $aistore_invoice_details_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_invoice_details') ,
                    'invoice_id' => $row->id,
                ) , home_url()));
                
 $customer = aistore_books_getcustomerbyid($user_id,$company->id,$row->customer_id);
 
 
     $str_arr = explode(",", $row->product_id);
    				$total_amount =0;
        foreach ($str_arr as $key => $product_id)
        {
$product = aistore_books_getproductbyid($user_id,$company->id,$product_id);

$total_amount= $total_amount + $product->amount;
}

// $product = aistore_books_getproductbyid($user_id,$company->id,$row->product_id);

$total_amount= $total_amount+$row->fee;

?>    <tr>
          
		   <td> <a href="<?php echo $aistore_invoice_details_url; ?>"  ><?php echo esc_attr($row->id); ?> </a></td>
		 
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
		   
	  <td>  <?php  
		      if (isset($_POST['submit']) and $_POST['action'] == 'invoice_email')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
              $invoice_id = sanitize_text_field($_REQUEST['invoice_id']);
          
  $aistore_invoice_email_details_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_email_page') ,
                    'invoice_id' => $invoice_id,
                     'customer_id' => $row->customer_id,
                ) , home_url()));
                ?>
         <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_invoice_email_details_url); ?>" />
                
                <?php
        }
        
        else{
            
        ?>
             
    <form method="POST" action="" name="invoice_email" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input class="input" type="hidden" id="invoice_id" name="invoice_id" value="<?php echo esc_attr($row->id); ?>"><br>
 
  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Send Email', 'aistore') ?>"/>
<input type="hidden" name="action" value="invoice_email" />

</form>

<?php } ?>
		  </td>
	
 
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
    
    <?php
}

?>