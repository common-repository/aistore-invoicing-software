<?php
  
 function aistore_notification_list()
    {
        if (!is_user_logged_in())
        {
            return "<div class='loginerror'>Kindly login and then visit this page</div>";
        }

     
        $user_id= get_current_user_id();
        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_notification WHERE user_id=%s order by id desc ", $user_id));

?>
<h3><u><?php _e('Notifications', 'aistore'); ?></u> </h3>
<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('No Notifications Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  

     
     

    <?php
            foreach ($results as $row):

               
?>
<div>
	#    <?php echo $row->id; ?> 
		   <?php echo $row->message; ?> <br>
		      <?php echo $row->created_at; ?>
		 
  <hr>
            
 </div>   <?php
            endforeach;

        } ?>

    </table>
	
	
	
	
	

    <?php
        return ob_get_clean();

    }