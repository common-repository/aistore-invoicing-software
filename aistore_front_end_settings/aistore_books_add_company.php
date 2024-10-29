<?php 


function aistore_add_company(){

  if (isset($_POST['submit']) and $_POST['action'] == 'company_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
               global $wpdb;
             $user_id = get_current_user_id();
            
            $company_name = sanitize_text_field($_REQUEST['company_name']);
            $pan_number = sanitize_text_field($_REQUEST['pan_number']);
            $gst_number = sanitize_text_field($_REQUEST['gst_number']);
            $address = sanitize_text_field($_REQUEST['address']);
            
            
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_company
    SET status = '%s' WHERE  user_id=%s", 'Disabled',$user_id));
        
            
             $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_company ( company_name, pan_number,gst_number,address,user_id,status ) VALUES (%s ,%s,%s,%s,%s,%s)", array(
            $company_name,
            $pan_number,
            $gst_number,
            $address,
            $user_id,
            'Enabled'
           
        )));
        
       $company_id= $wpdb->insert_id;
  
         $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_currency ( currency, symbol ,user_id,company_id ) VALUES ( %s ,%s, %s ,%s)", array(
                'USD',
                'USD',
                $user_id,
               $company_id
            )));
            
 $sql=" INSERT INTO `{$wpdb->prefix}aistore_books_accounts` (`account`, `type`, `user_id`, `company_id`) VALUES
('Income', 'debit', $user_id,$company_id),
( 'Expense', 'debit', $user_id,$company_id),
('Cash', 'debit', $user_id,$company_id),
( 'Accounts Receivable', 'debit', $user_id,$company_id),
( 'Fixed Asset','debit', $user_id,$company_id),
('Other Current Asset', 'debit', $user_id,$company_id),
( 'Accounts Payable','debit', $user_id,$company_id),
( 'Other Current Liability', 'debit', $user_id,$company_id),
('Equity', 'debit', $user_id,$company_id),
( 'Other Expense', 'debit', $user_id,$company_id),
( 'Other Current Asset', 'debit', $user_id,$company_id),
('Other Liability', 'debit', $user_id,$company_id),
( 'Stock', 'debit', $user_id,$company_id),
( 'Other Current Liability','debit', $user_id,$company_id),
( 'Long Term Liability','debit', $user_id,$company_id),
( 'Cost Of Goods Sold', 'debit', $user_id,$company_id),
( 'Bank', 'debit', $user_id,$company_id),
('Fixed Asset','debit', $user_id,$company_id)";
 $results=   $wpdb->get_results($sql);
 
  $sql=" INSERT INTO `{$wpdb->prefix}aistore_books_subaccounts` (`account`,`subaccount`, `type`, `user_id`, `company_id` ) VALUES
('Income', 'Other Charges','debit', $user_id,$company_id),
( 'Expense', 'Lodging','debit', $user_id,$company_id),
('Cash','Undeposited Funds','debit', $user_id,$company_id),
( 'Accounts Receivable','Accounts Receivable', 'debit', $user_id,$company_id),
( 'Fixed Asset','Furniture and Equipment','debit', $user_id,$company_id),
('Other Current Asset','Advance Tax', 'debit', $user_id,$company_id),
( 'Accounts Payable','Accounts Payable','debit', $user_id,$company_id),
( 'Other Current Liability','Tax Payable', 'debit', $user_id,$company_id),
('Equity', 'Retained Earnings', 'debit', $user_id,$company_id),
( 'Other Expense', 'Exchange Gain or Loss','debit', $user_id,$company_id),
( 'Other Current Asset', 'Employee Advance','debit', $user_id,$company_id),
('Other Liability', 'Opening Balance Adjustments','debit', $user_id,$company_id),
( 'Stock', 'Inventory Asset','debit', $user_id,$company_id),
( 'Other Current Liability','TDS Payable','debit', $user_id,$company_id),
( 'Long Term Liability','Mortgages','debit', $user_id,$company_id),
( 'Cost Of Goods Sold','Labor', 'debit', $user_id,$company_id),
( 'Bank','Paytm', 'debit', $user_id,$company_id),
( 'Bank','State Bank of india', 'debit', $user_id,$company_id),
('Fixed Asset','Credit Card Bills','debit', $user_id,$company_id),
('Fixed Asset','Donation to ISKON','debit', $user_id,$company_id)";

 $results=   $wpdb->get_results($sql);
   //echo "<script>alert('Add Company Successfully');</script>";
        ?>
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script type="text/javascript">
		
	toastr.success('Add Company Successfully');
	</script>
<?php


            
$m='<h2>Company Details </h2><br><br>Company Name :' .$company_name.'<br>
PAN Number : '.$pan_number.'<br>
GST Number : '.$gst_number.'<br>
Address : '.$address.'<br>.';

       $message='Add Company Successfully';
            sendNotification($company_id,$message);   
            sendEmail($company_id,$message,$m);
   
  $aistore_add_currency_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_add_currency') ,
                ) , home_url()));
                
            ?>
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_add_currency_url); ?>" /> 
    
    <?php
    
    // echo "sfsd";
}
else{
?>
   
<form method="POST" action="" name="company_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

  <label for="title"><?php _e('Company Name', 'aistore'); ?></label><br>
  <input class="input" type="text" id="company_name" name="company_name"><br>

  <label for="title"><?php _e('PAN Number', 'aistore'); ?></label><br>
  <input class="input" type="text" id="pan_number" name="pan_number"><br>

  <label for="title"><?php _e('GST Number', 'aistore'); ?></label><br>
  <input class="input" type="text" id="gst_number" name="gst_number"><br>
  
  <label for="country"><?php _e('Address', 'aistore'); ?></label><br>
  <textarea id="address" name="address" rows="3" cols="40">
<?php _e('Address', 'aistore'); ?>
</textarea><br><br>
  
  
      
 <input type="submit" class="btn" name="submit" value="<?php _e('Submit', 'aistore') ?>"/>
<input type="hidden" name="action" value="company_system" />

</form>  

   <?php
   
}   
}


