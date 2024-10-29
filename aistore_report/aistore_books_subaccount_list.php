<?php
function aistore_list_subaccount(){

       if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
 global $wpdb;
 
   $user_id = get_current_user_id();
     $company = aistore_books_getCompany($user_id);
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_subaccounts where user_id=%s and company_id=%s", $user_id,$company->id));



?></div>

<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Account List Not Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table">
     
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e(' Name', 'aistore'); ?></th>
         <th><?php _e('Type', 'aistore'); ?></th>

</tr>

    <?php
            foreach ($results as $row):
    
 
?>    <tr>
          
		   <td> <?php echo esc_attr($row->id); ?></td>
		    <td>   <?php echo esc_attr($row->subaccount); ?> </td>
    <td> 	
 
   <?php echo esc_attr($row->type) ?>
		  </td>
		   
		  	 
		  
	
 
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
    
<?php }

?>