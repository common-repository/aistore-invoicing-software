<?php

  // transaction List
  function aistore_transaction_report()
    {
        
        if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    } 
             global $wpdb;
     $user_id = get_current_user_id();
     $company = aistore_books_getCompany($user_id);
     
           if (isset($_POST['submit']) and $_POST['action'] == 'transaction_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
           
     $account = sanitize_text_field($_REQUEST['account']);
     
    
  
      
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions where  account=%s and created_by=%s and company_id=%s",$account,$user_id,$company->id));
 
$totalamount = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) as totalamount FROM {$wpdb->prefix}aistore_books_wallet_transactions where account=%s and created_by=%s and company_id=%s",$account,$user_id,$company->id));

            // print_r($total);
            
           
        ?>
        
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
     
        <tr>
 <th><?php _e('Description', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
 
	
		  
		    
		    

		 
</tr>

    <?php
            foreach ($results as $row):
    
?>
		     <td> 		   <?php echo esc_attr($row->description) ?>  </td>
		  	   <td> 		   <?php echo esc_attr($row->amount)." ".esc_attr($row->currency) ?>  </td>
		  

            </tr>
    <?php
            endforeach;

        } ?>
<tr><td><?php _e('Total Amount', 'aistore'); ?></td><td><?php echo esc_attr($totalamount); ?>/-</td></tr>
    </table>
	<?php
        }
        
        
 $results = aistore_books_getAccount($user_id,$company->id);

?>
<div>
 <form method="POST" action="" name="transaction_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

<label for="title"><?php _e('Account', 'aistore'); ?>:</label>
       <select name="account" id="account" >
                <?php
            foreach ($results as $c)
            {
                 echo '	<option  value="' . esc_attr($c->account) . '">' . esc_attr($c->account ). '</option>';
        } ?> 
              
</select>

  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Search', 'aistore') ?>"/>
<input type="hidden" name="action" value="transaction_system" />
</form>
</div>
<?php

   
   

}


?>
  