<?php 

// add user id when fetch data

function aistore_all_sales_report(){
   if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
 global $wpdb;

    $user_id = get_current_user_id();
                $company = aistore_books_getCompany($user_id);
      
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions where type='credit' and created_by=%s  and company_id = %s group by account order by transaction_id desc", $user_id,$company->id));
?></div>

<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Sales List Not Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>

   
        <th><?php _e('Account', 'aistore'); ?></th>
         <th><?php _e('SubAccount', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
 
		  <th><?php _e('Customer', 'aistore'); ?></th>
		  
		   <th><?php _e('Reference Number', 'aistore'); ?></th> 
		        <th><?php _e('Status', 'aistore'); ?></th> 
		    <th><?php _e('Date', 'aistore'); ?></th> 
		
		    

		 
</tr>

    <?php
            foreach ($results as $row):
    
  $details_transaction_page_id_url = esc_url(add_query_arg(array(
                      'page_id' =>get_option('aistore_update_transaction') ,
                    'transaction_id' => $row->transaction_id,
                ) , home_url()));
?>    <tr>
          
		   <td>   <?php echo esc_attr($row->transaction_id); ?> </td>
		 
  <td> 	   <?php echo esc_attr($row->account); ?> </td>
    <td> 	
 
   <?php echo esc_attr($row->from_account) ?>
		  </td>
		   
		  	   <td> 		   <?php echo esc_attr($row->amount)." ".esc_attr($row->currency) ?>  </td>
		  
		    <td> 		   <?php echo esc_attr($row->customer); ?> </td>
		     <td> 		   <?php echo esc_attr($row->reference); ?> </td>
		     	     <td> 		   <?php echo esc_attr($row->status); ?> </td>
 <td> 		   <?php echo esc_attr($row->date); ?> </td>
 
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
    <?php

}