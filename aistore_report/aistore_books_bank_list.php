<?php 

function aistore_list_bank(){

       if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
 global $wpdb;
   $user_id = get_current_user_id();
   
                $company = aistore_books_getCompany($user_id);
      
      

          
         $aistore_add_bank_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_add_bank') ,
                ) , home_url()));
                
            ?>
        
   
    
    <a href="<?php echo $aistore_add_bank_url; ?>">Add Bank</a> <br>
      <?php
$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_bank where user_id=%s  and company_id=%s",$user_id,$company->id));



?></div>

<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Bank List Not Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Bank Name', 'aistore'); ?></th>
         <th><?php _e('Branch Name', 'aistore'); ?></th>
        
</tr>

    <?php
            foreach ($results as $row):
    
  $details_csv_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_csv_data') ,
                    'id' => $row->id,
                ) , home_url()));
                
         $bank_transaction_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_bank_transactions') ,
                    'id' => $row->id,
                ) , home_url()));         
                
?>    <tr>
          
		   <td> <a href="<?php echo esc_url($bank_transaction_page_id_url); ?>"  >
		       <?php echo esc_attr($row->id); ?> </a></td>
		    <td>   <?php echo esc_attr($row->bank_name); ?> </td>
    <td> 	
 
   <?php echo esc_attr($row->branch_name) ?>
		  </td>
		   
		  
		  
	
 
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
    
    <?php
}