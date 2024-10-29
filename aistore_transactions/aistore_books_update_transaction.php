<?php

function aistore_update_transaction(){
       global $wpdb;
       $user_id = get_current_user_id();
       
      $transaction_id = sanitize_text_field($_REQUEST['transaction_id']);
                    $company = aistore_books_getCompany($user_id);
      
       $transaction = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions where transaction_id=%s and created_by=%s and company_id=%s", $transaction_id,$user_id,$company->id));
       
    if (isset($_POST['submit']) and $_POST['action'] == 'transaction_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }

            $account = sanitize_text_field($_REQUEST['account']);
       
            $date = sanitize_text_field($_REQUEST['date']);
            $amount = sanitize_text_field($_REQUEST['amount']);
            $reference = sanitize_text_field($_REQUEST['reference']);
           
            $description = sanitize_text_field($_REQUEST['description']);
            $tags = sanitize_text_field($_REQUEST['tags']);
            
            if($transaction->type=='debit'){
             $expense_account = sanitize_text_field($_REQUEST['expense_account']);
            $vendor = sanitize_text_field($_REQUEST['vendor_name']);
            
 $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_wallet_transactions
    SET account = '%s', tags = '%s' ,date='%s'  ,description= '%s',expense_account  = '%s' ,vendor  = '%s' , reference = '%s' ,status='Completed' WHERE transaction_id = '%d' and created_by='%d' and company_id=%s", $account,$tags,$date,$description,$expense_account,$vendor,$reference,$transaction_id,$user_id,$company->id));
    
    
     
                  $wallet = new AistoreWallet();
    $description="Update Transaction";
    $currency = 'INR';
    
    $balance = $wallet->aistore_balance($user_id, $currency);


if($balance>=$amount){
     $res=$wallet->aistore_debit($user_id, $amount,$currency,$description,$transaction_id);
     $res=$wallet->aistore_credit(1, $amount,$currency,$description,$transaction_id);
     
  
            
}
else{
    _e( 'Insufficient Balance', 'aistore' ); 
            }
            
            }
     if($transaction->type=='credit'){
   $from_account = sanitize_text_field($_REQUEST['from_account']);
    $received_via = sanitize_text_field($_REQUEST['received_via']);
      $customer = sanitize_text_field($_REQUEST['customer_name']);
     
       $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_wallet_transactions
    SET account = '%s', from_account  = '%s' , tags = '%s' ,date='%s' ,received_via ='%s' , reference = '%s' ,description= '%s' ,customer  = '%s',status='Completed' WHERE transaction_id = '%d' and created_by='%d' and company_id=%s", $account,$from_account,$tags,$date,$received_via,$reference,$description,$customer,$transaction_id,$user_id,$company->id));
    
    
                  $wallet = new AistoreWallet();
    $description="Update Transaction";
    $currency = 'INR';
    
    $balance = $wallet->aistore_balance(1, $currency);


if($balance>=$amount){
     $res=$wallet->aistore_debit(1, $amount,$currency,$description,$transaction_id);
     $res=$wallet->aistore_credit($user_id, $amount,$currency,$description,$transaction_id);
     
  
            
}
else{
    _e( 'Insufficient Balance', 'aistore' ); 
            }
     }
     
        $aistore_transaction_history_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_books_transaction_history') ,
                ) , home_url()));
                
               // header("Location: ".$aistore_transaction_history_url);
                
                
             ?>
        
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_transaction_history_url); ?>" />  
    
   
    <?php     
        }
        
        else{
            
 
  
        
    
    //  print_r($transaction);
     
    ?>
    
      
    <form method="POST" action="" name="transaction_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

<?php 
if( $transaction->type == 'credit'){

  global $wpdb;
         $results=aistore_books_getAccountCredit($user_id,$company->id);       

?>
<label for="title"><?php _e('Accounts', 'aistore'); ?></label><br>
       <select name="account" id="account" >
                <?php
            foreach ($results as $c)
            {
    if ($c->account == $transaction->account)
            {
                echo '	<option selected value="' . esc_attr($c->account ). '">' .esc_attr( $c->account ). '</option>';

            }
            else
            {

                echo '	<option value="' .esc_attr( $c->account ). '">' . esc_attr($c->account) . '</option>';

            }
        } ?> 
              
</select><br>
<?php
global $wpdb;
           $results=aistore_books_getSubaccountCredit($user_id,$company->id);      

        
?>
<label for="title"><?php _e('From Account', 'aistore'); ?></label><br>
       <select name="from_account" id="from_account" >
                <?php
            foreach ($results as $c)
            {
 if ($c->subaccount == $transaction->from_account)
            {
                echo '	<option selected value="' .esc_attr( $c->subaccount) . '">' . esc_attr($c->subaccount ). '</option>';
            }
            else
            {

                echo '	<option value="' .esc_attr( $c->subaccount) . '">' .esc_attr($c->subaccount) . '</option>';
            }
            
            }
?>
           
  
</select><br>

        
<?php } ?>  


<?php 
if( $transaction->type == 'debit'){


 global $wpdb;
      $results=aistore_books_getAccountDebit($user_id,$company->id);   

        
?>
<label for="title"><?php _e('Account', 'aistore'); ?></label><br>
       <select name="account" id="account" >
                <?php
            foreach ($results as $c)
            {

               if ($c->account == $transaction->account)
            {
                echo '	<option selected value="' . esc_attr($c->account) . '">' .esc_attr( $c->account) . '</option>';
            }
            else
            {

                echo '	<option value="' .esc_attr( $c->account ). '">' . esc_attr($c->account ). '</option>';
            }
                }
?>

           </select><br>        
       
       
       
       <?php
global $wpdb;
   $results=aistore_books_getSubaccountDebit($user_id,$company->id);   

        
?>
<label for="title"><?php _e('Expense Account', 'aistore'); ?></label><br>
       <select name="expense_account" id="expense_account" >
                <?php
            foreach ($results as $c)
            {

   if ($c->subaccount == $transaction->expense_account)
            {
                echo '	<option selected value="' .esc_attr( $c->subaccount ). '">' .esc_attr( $c->subaccount ). '</option>';
            }
            else
            {
                echo '	<option value="' . $c->subaccount . '">' . esc_attr($c->subaccount) . '</option>';
            }
               

            }
?>
</select><br>
  
<?php } ?>          
          
          
   <label for="title"><?php _e('Date', 'aistore'); ?></label><br>
  <input class="input" type="text" id="date" name="date" value="<?php echo esc_attr($transaction->date); ?>"><br>
  
  
     <label for="title"><?php _e('Amount', 'aistore'); ?></label><br>
      <input class="input" type="text" readonly="true" id="amount" name="amount" value="<?php echo esc_attr($transaction->amount)." ". esc_attr($transaction->currency); ?>"><br>
  
          <?php 
if( $transaction->type == 'credit'){
?>
             
<label for="title"><?php _e('Received Via', 'aistore'); ?></label><br>
<select name="received_via" id="received_via" >
    
 <option selected value="<?php echo esc_attr($transaction->received_via); ?>">	<?php echo esc_attr($transaction->received_via); ?></option>
<option  value="Cash">Cash</option>
           </select><br>     

  <?php } ?>
  
  
             
<label for="title"><?php _e('Reference', 'aistore'); ?></label><br>
  <input class="input" type="text" id="reference" name="reference" value="<?php echo esc_attr($transaction->reference); ?>"><br>
  
  
   <label for="term_condition"> <?php _e('Description', 'aistore') ?></label><br>
 
  <?php
            $content =  $transaction->description;
            $editor_id = 'description';

            $settings = array(
                'tinymce' => array(
                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright   ',
                    'toolbar2' => '',
                    'toolbar3' => ''

                ) ,
                'textarea_rows' => 1,
                'teeny' => true,
                'quicktags' => false,
                'media_buttons' => false
            );

            wp_editor($content, $editor_id, $settings);

 global $wpdb;
             
 if($transaction->type=='debit'){
        
       $results= aistore_books_getVendors($user_id,$company->id);
?>
<label for="title"><?php _e('Vendor', 'aistore'); ?></label><br>
       <select name="vendor_name" id="vendor_name" >
                <?php
            foreach ($results as $c)
            {
    if ($c->vendor_name == $transaction->category)
            {
                echo '	<option selected value="' . esc_attr($c->vendor_name) . '">' . esc_attr($c->vendor_name) . '</option>';

            }
            else
            {

                echo '	<option value="' . esc_attr($c->vendor_name) . '">' . esc_attr($c->vendor_name) . '</option>';

            }
        } ?> 
              
</select><br>

     <?php } 
     
      if($transaction->type=='credit'){
        
       $results= aistore_books_getCustomer($user_id,$company->id);
?>
<label for="title"><?php _e('Customer', 'aistore'); ?></label><br>
       <select name="customer_name" id="customer_name" >
                <?php
            foreach ($results as $c)
            {
    if ($c->customer_name == $transaction->category)
            {
                echo '	<option selected value="' . esc_attr($c->customer_name) . '">' . esc_attr($c->customer_name) . '</option>';

            }
            else
            {

                echo '	<option value="' . esc_attr($c->customer_name) . '">' . esc_attr($c->customer_name) . '</option>';

            }
        } ?> 
              
</select><br>

     <?php } ?>
     
     
<label for="title"><?php _e('Reporting Tags', 'aistore'); ?></label><br>
  <input class="input" type="text" id="tags" name="tags" value="<?php echo esc_attr($transaction->tags); ?>"><br><br>
  
  
  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Update', 'aistore') ?>"/>
<input type="hidden" name="action" value="transaction_system" />
</form>
  <?php
}
}