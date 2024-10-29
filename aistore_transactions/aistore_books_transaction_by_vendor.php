<?php
  // transaction List
  function aistore_transaction_by_vendor()
    {
if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    ?>
   

    <?php
    
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
          
   
    
      $table = $wpdb->prefix.'aistore_books_vendor_documents';
    $wpdb->delete( $table, array( 'id' => $document_id,'user_id' =>$user_id,'company_id' =>$company->id) );
        }
        
       if (isset($_POST['submit']) and $_POST['action'] == 'upload_document')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
          $vendor_id = sanitize_text_field($_REQUEST['vendor_id']);
                
            $fileType = $_FILES['file']['type'];
         $upload_dir = wp_upload_dir();

                if (!empty($upload_dir['basedir']))
                {

                    $user_dirname = $upload_dir['basedir'] . '/documents/' . $vendor_id;
                    if (!file_exists($user_dirname))
                    {
                        wp_mkdir_p($user_dirname);
                    }

                    $filename = wp_unique_filename($user_dirname, $_FILES['file']['name']);
                    move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']) , $user_dirname . '/' . $filename);

                    $image = $upload_dir['baseurl'] . '/documents/' . $vendor_id . '/' . $filename;
                    
         
             $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_vendor_documents ( image, company_id,user_id,vendor_id ,name) VALUES (%s,%s,%s,%s,%s)", array(
            $image,
            $company->id,
            $user_id,
            $vendor_id,
            $filename
           
        )));   
        
         $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_vendor
    SET image = '%s' WHERE id = '%d' and user_id=%s  and company_id=%s", $image,$vendor_id,$user_id,$company->id));
                    
     
}

}
 if( empty( $_REQUEST['vendor_id'] ) ){
     
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions where user_id=%s and company_id=%s  order by transaction_id desc ", $user_id,$company->id));
    
//  $sql = "SELECT * FROM {$wpdb->prefix}aistore_wallet_transactions order by transaction_id desc";
			}
		
		else{
		  
$vendor_id=sanitize_text_field($_REQUEST['vendor_id']);

 $vendors = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_vendor where id=%s and user_id=%s and company_id=%s ", $vendor_id,$user_id,$company->id));
 
  $invoice = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_invoice where id=%s and user_id=%s and company_id=%s ", $vendor_id,$user_id,$company->id));
  
  $estimate = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_estimate where id=%s and user_id=%s and company_id=%s  ", $vendor_id,$user_id,$company->id));
   
   
  
 $totalcredit = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) as totalamount FROM {$wpdb->prefix}aistore_books_wallet_transactions where  type = 'credit' and vendor=%s and user_id=%s and company_id=%s  ",$vendors->vendor_name,$user_id,$company->id));
 
  $totaldebit = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) as totalamount FROM {$wpdb->prefix}aistore_books_wallet_transactions where  type = 'debit' and vendor=%s and user_id=%s and company_id=%s ",$vendors->vendor_name,$user_id,$company->id));
 
$totalsum=    abs($totalcredit - $totaldebit)  ;
 
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions where user_id=%s and vendor=%s and company_id=%s  order by transaction_id desc ", $user_id,$vendors->vendor_name,$company->id));
     


}


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
    <h2><?php _e('Profile', 'aistore'); ?></h2>
   <?php _e('Name', 'aistore'); ?>: <?php echo esc_attr($vendors->vendor_name); ?><br>
   <?php _e('Email', 'aistore'); ?>: <?php echo esc_attr($vendors->vendor_email); ?><br>
    <?php _e('Mobile No', 'aistore'); ?>: <?php echo esc_attr($vendors->mobile_no); ?><br>
   
    <?php _e('GST Number', 'aistore'); ?>: <?php echo esc_attr($vendors->gst_number); ?><br>
   <?php _e('PAN Number', 'aistore'); ?>: <?php echo esc_attr($vendors->pan_number); ?><br>
  <?php _e('Address', 'aistore'); ?>: <?php echo esc_attr($vendors->address); ?> ,<?php echo esc_attr($vendors->city); ?> ,<?php echo esc_attr($vendors->state); ?> <br>
    <?php echo esc_attr($vendors->zip_code); ?>,  <?php echo esc_attr($vendors->country); ?><br>
    <?php
         $details_vendor_page_id_url = add_query_arg(array(
                    'page_id' =>get_option('aistore_edit_vendor') ,
                    'vendor_id' =>$vendor_id,
                ) , home_url());
                
                ?>
<a href="<?php echo esc_url($details_vendor_page_id_url); ?>">Edit Vendor</a>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      
        <?php
    $aistore_add_invoice_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_invoice') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_invoice_page_id_url); ?>"  >Add  Invoice</a><br><br>
      
      <h2><?php _e('No Invoice', 'aistore'); ?> </h2></div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab"> 
        <?php
     $aistore_add_estimate_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_estimate') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_estimate_page_id_url); ?>"  >Add  Estimate</a><br><br> <h2><?php _e('No Estimate', 'aistore'); ?></h2></div>
  
    <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab"> <br><br>
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

?></div>


     <div class="tab-pane fade" id="documnets" role="tabpanel" aria-labelledby="documnets-tab"> 
    
    <br><br>
    <h3><u><?php _e('Documents', 'aistore'); ?></u> </h3>

 
 
 
 
  <form method="POST" action="" name="upload_document" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

<input class="input" type="hidden" id="vendor_id" name="vendor_id" value="<?php echo esc_attr($vendor_id); ?>"><br><br>

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
     $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_vendor_documents where user_id=%s and company_id=%s and vendor_id = %s" , $user_id,$company->id,$vendor_id));



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
<?php
    }