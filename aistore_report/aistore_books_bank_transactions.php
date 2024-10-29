<?php 


function aistore_bank_transactions(){
global $wpdb;
      $bank_id = sanitize_text_field($_REQUEST['id']);
        $user_id = get_current_user_id();

  $company = aistore_books_getCompany($user_id);
  $details_csv_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_csv_data') ,
                    'id' => $bank_id,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($details_csv_page_id_url); ?>"  >Import CSV Transactions</a>
   <?php
   
      global $wpdb;            
 $banks = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_bank where id=%s and company_id=%s and user_id=%s ",$bank_id,$company->id, $user_id));
                
                
               // echo $banks->bank_name;
 $result = ($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_wallet_transactions where bank_name=%s and company_id=%s and created_by=%s order by transaction_id desc",$banks->bank_name,$company->id, $user_id));
//  echo $result;
$results=$wpdb->get_results($result);
// print_r($results);

?></div>
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
      
    <th><?php _e('ID', 'aistore'); ?></th>

   
        <th><?php _e('Type', 'aistore'); ?></th>
         <th><?php _e('Balance', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
 
		  <th><?php _e('Currency', 'aistore'); ?></th>
		  <th><?php _e('Bank Name', 'aistore'); ?></th>
		   <th><?php _e('Description', 'aistore'); ?></th> 
		    <th><?php _e('Date', 'aistore'); ?></th> 
		     <th><?php _e('Action', 'aistore'); ?></th> 
		    

		 
</tr>

    <?php
            foreach ($results as $row):
    
  $details_transaction_page_id_url = esc_url(add_query_arg(array(
                     'page_id' =>get_option('aistore_update_transaction') ,
                    'transaction_id' => $row->transaction_id,
                ) , home_url()));
?>    <tr>
          
		   <td>   <?php echo esc_attr($row->transaction_id); ?> </td>
		 
  <td> 	   <?php echo esc_attr($row->type); ?> </td>
    <td> 	
 
   <?php echo esc_attr($row->balance) ?>
		  </td>
		   
		  	   <td> 		   <?php echo esc_attr($row->amount) ?>  </td>
		  
		    <td> 		   <?php echo esc_attr($row->currency); ?> </td>
		       <td> 		   <?php echo esc_attr($row->bank_name); ?> </td>
		     <td> 		   <?php echo esc_attr($row->description); ?> </td>
 <td> 		   <?php echo esc_attr($row->date); ?> </td>
   <td> 	<a href="<?php echo esc_url($details_transaction_page_id_url); ?>" >Update </a> </td>
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	<?php
}
?>

