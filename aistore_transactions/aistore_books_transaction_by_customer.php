<?php
  // transaction List
  function aistore_transaction_by_customer()
    {
if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
    
 global $wpdb;
     $user_id = get_current_user_id();
                   $company = aistore_books_getCompany($user_id);
    
		      if (isset($_POST['submit']) and $_POST['action'] == 'delete_document')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
              $document_id = sanitize_text_field($_REQUEST['document_id']);
          
   
    
      $table = $wpdb->prefix.'aistore_books_customer_documents';
    $wpdb->delete( $table, array( 'id' => $document_id,'user_id' =>$user_id,'company_id' =>$company->id) );
        }
        if (isset($_POST['submit']) and $_POST['action'] == 'upload_document')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
          $customer_id = sanitize_text_field($_REQUEST['customer_id']);
                
            $fileType = $_FILES['file']['type'];
         $upload_dir = wp_upload_dir();

                if (!empty($upload_dir['basedir']))
                {

                    $user_dirname = $upload_dir['basedir'] . '/documents/' . $customer_id;
                    if (!file_exists($user_dirname))
                    {
                        wp_mkdir_p($user_dirname);
                    }

                    $filename = wp_unique_filename($user_dirname, $_FILES['file']['name']);
                    move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']) , $user_dirname . '/' . $filename);

                    $image = $upload_dir['baseurl'] . '/documents/' . $customer_id . '/' . $filename;
               
      
             $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_customer_documents ( image, company_id,user_id,customer_id ,name) VALUES (%s,%s,%s,%s,%s)", array(
            $image,
            $company->id,
            $user_id,
            $customer_id,
            $filename
           
        )));   
                     
         $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_customer
    SET image = '%s' WHERE id = '%d' and user_id=%s and company_id=%s", $image,$customer_id,$user_id,$company->id));
                    
     
}

}

 if( empty( $_REQUEST['customer_id'] ) ){
 $sql = "SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions order by transaction_id desc";
			}
		
		else{
		  
$customer_id=sanitize_text_field($_REQUEST['customer_id']);

 $customer = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_customer where id=%s and user_id = %s and company_id=%s ", $customer_id,$user_id,$company->id));
 
   $invoice = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_invoice where customer_id=%s and user_id = %s and company_id=%s ", $customer_id,$user_id,$company->id));
   
  $estimate = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_estimate where customer_id=%s and user_id = %s   and company_id=%s", $customer_id,$user_id,$company->id));
 
 $totalcredit = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) as totalamount FROM {$wpdb->prefix}aistore_books_wallet_transactions where  type = 'credit' and user_id = %s and customer=%s  and company_id=%s",$user_id,$customer->customer_name,$company->id));
 
  $totaldebit = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) as totalamount FROM {$wpdb->prefix}aistore_books_wallet_transactions where  type = 'debit' and user_id = %s and customer=%s  and company_id=%s",$user_id,$customer->customer_name,$company->id));
 
$totalsum=    abs($totalcredit - $totaldebit)  ;
 
$sql = ($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions where user_id = %s and customer=%s and company_id=%s ", $user_id,$customer->customer_name,$company->id));

}
   $results=   $wpdb->get_results($sql);


?></div>

 <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

 
<div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> <?php _e('Profile', 'aistore'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"> <?php _e('Invoice', 'aistore'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"> <?php _e('Estimate', 'aistore'); ?></a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab" aria-controls="transactions" aria-selected="false"><?php _e('Transactions', 'aistore'); ?></a>
  </li>
  
   <li class="nav-item">
    <a class="nav-link" id="documnets-tab" data-toggle="tab" href="#documnets" role="tab" aria-controls="documnets" aria-selected="false"><?php _e('Documents', 'aistore'); ?></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
    
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      <h2> 
  <?php echo esc_attr($customer->customer_name); ?><br>
  </h2>
  
  
  <?php _e('Email', 'aistore'); ?>: <?php echo esc_attr($customer->customer_email); ?><br>
   <?php _e('Mobile No', 'aistore'); ?>: <?php echo esc_attr($customer->mobile_no); ?><br>
   
    <?php _e('GST Number', 'aistore'); ?>: <?php echo esc_attr($customer->gst_number); ?><br>
   <?php _e('PAN Number', 'aistore'); ?>: <?php echo esc_attr($customer->pan_number); ?><br>
  <?php _e('Address', 'aistore'); ?>: <?php echo esc_attr($customer->address); ?>, <?php echo esc_attr($customer->city); ?>,  <?php echo esc_attr($customer->state); ?> <br>
    <?php echo esc_attr($customer->zip_code); ?>, <?php echo esc_attr($customer->country); ?><br>
<?php

 $details_customer_page_id_url = add_query_arg(array(
                    'page_id' =>get_option('aistore_edit_customer') ,
                    'customer_id' =>$customer_id,
                ) , home_url());
                
                ?>
<a href="<?php echo esc_url($details_customer_page_id_url); ?>">Edit Customer</a>


  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      
        <?php
     $aistore_add_invoice_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_invoice') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_invoice_page_id_url); ?>"  >Add  Invoice</a><br><br>
                
      <h2><?php _e('Invoice', 'aistore'); ?></h2>
    
      
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
          <th><?php _e('Customer Name', 'aistore'); ?></th>
           <th><?php _e('Customer Email', 'aistore'); ?></th>
            <th><?php _e('Product', 'aistore'); ?></th>
             <th><?php _e('Amount', 'aistore'); ?></th>
            <th><?php _e('Date', 'aistore'); ?></th>
       
</tr>

    <?php
            foreach ( $invoice as $row): 
    
  $aistore_invoice_details_url =  add_query_arg(array(
                    'page_id' =>get_option('aistore_invoice_details') ,
                    'invoice_id' => $row->id,
                ) , home_url());
				
				
                $customer = aistore_books_getcustomerbyid($user_id,$company->id,$row->customer_id);
$product = aistore_books_getproductbyid($user_id,$company->id,$row->product_id);
$total_amount= $row->amount+$row->fee;
?>    <tr>
          
		   <td> <a href="<?php echo esc_url($aistore_invoice_details_url); ?>"  ><?php echo esc_attr($row->id); ?> </a></td>
		  		 
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
	
	
 
            </tr>
    <?php
            endforeach;

         ?>

    </table>

      </div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">  <h2><?php _e('Estimate', 'aistore'); ?></h2>
     
     
        <?php
     $aistore_add_estimate_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_estimate') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_estimate_page_id_url); ?>"  >Add  Estimate</a><br><br>
                
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Customer Name', 'aistore'); ?></th>
           <th><?php _e('Customer Email', 'aistore'); ?></th>
            <th><?php _e('Product', 'aistore'); ?></th>
             <th><?php _e('Amount', 'aistore'); ?></th>
            <th><?php _e('Date', 'aistore'); ?></th>
       
</tr>

    <?php
            foreach ( $estimate as $row): 
    
  $aistore_estimate_details_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_estimate_details') ,
                    'estimate_id' => $row->id,
                ) , home_url()));
				
				
				
                $customer = aistore_books_getcustomerbyid($user_id,$company->id,$row->customer_id);
$product = aistore_books_getproductbyid($user_id,$company->id,$row->product_id);
$total_amount= $row->amount+$row->fee;
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
		   
	
	
 
            </tr>
    <?php
            endforeach;

         ?>

    </table>

  </div>
  
    <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab"> 
    
    <br><br>
    <h3><u><?php _e('Transactions', 'aistore'); ?></u> </h3>
<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Transactions List Not Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table">
     
<tr><td  colspan="4"><?php _e('Total Sum ', 'aistore'); ?>: </td><td colspan="4"><?php echo esc_attr($totalsum); ?>/-</td></tr>
    </table>
	
    <?php
}
?>
    </div>
    
    
     <div class="tab-pane fade" id="documnets" role="tabpanel" aria-labelledby="documnets-tab"> 
    
    <br><br>
    <h3><u><?php _e('Documents', 'aistore'); ?></u> </h3>
 <!--<img src="<?php echo esc_attr($customer->image); ?>">-->
  <form method="POST" action="" name="upload_document" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

<input class="input" type="hidden" id="customer_id" name="customer_id" value="<?php echo esc_attr($customer_id); ?>"><br><br>

<label for="title"><?php _e('Upload Documents', 'aistore'); ?></label><br>
<input class="input" type="file" id="file" name="file" ><br><br>
  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Upload', 'aistore') ?>"/>
<input type="hidden" name="action" value="upload_document" />
</form> 

<br>
 
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e(' Name', 'aistore'); ?></th>
         <th><?php _e('Image', 'aistore'); ?></th>
          <th><?php _e('Action', 'aistore'); ?></th>

</tr>

    <?php
     $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_customer_documents where user_id=%s and company_id=%s and customer_id = %s" , $user_id,$company->id,$customer_id));



            foreach ($results as $row):
    
 
?>    <tr>
          
		   <td> <?php echo esc_attr($row->id); ?></td>
		     <td> 	
 
   <?php echo esc_attr($row->name) ?>
		  </td>
		   
		  	 
		   <td>  <img src="<?php echo esc_attr($row->image); ?>" style="width:50px; height:50px;"></td>
  
		  <td>          
    <form method="POST" action="" name="delete_document" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input class="input" type="hidden" id="document_id" name="document_id" value="<?php echo esc_attr($row->id); ?>"><br>
 
  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Delete', 'aistore') ?>"/>
<input type="hidden" name="action" value="delete_document" />

</form></td>
	
 
            </tr>
    <?php
            endforeach;

       ?>

    </table>
    </div>
    
</div></div>
<br><br>
<?php
    }