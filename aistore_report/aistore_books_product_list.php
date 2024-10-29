<?php
function aistore_list_product(){

       if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
 global $wpdb;
 
   $user_id = get_current_user_id();
     $company = aistore_books_getCompany($user_id);
     
     $aistore_add_product_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_product') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_product_page_id_url); ?>"  >Add  Product</a>
                
                <?php
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_product where user_id=%s and company_id=%s", $user_id,$company->id));



?></div>

<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Product List Not Found', 'aistore');
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
         <th><?php _e('Amount', 'aistore'); ?></th>

</tr>

    <?php
            foreach ($results as $row):
    
 
?>    <tr>
          
		   <td> <?php echo esc_attr($row->id); ?></td>
		    <td>   <?php echo esc_attr($row->name); ?> </td>
    <td> 	
 
   <?php echo esc_attr($row->amount) ?>
		  </td>
		   
		  	 
		  
	
 
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
    
<?php }

?>