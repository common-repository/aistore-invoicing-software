<?php


function aistore_add_invoice(){
    
   
   $user_id = get_current_user_id();
        $user_role = get_user_meta($user_id, 'user_role', true);
           $company = aistore_books_getCompany($user_id);
           
         $payment=   aistore_books_getpaymentbyid($user_id,$company->id);
           
    if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
            $editor = array(
                'tinymce' => array(
                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright, link,unlink,  ',
                    'toolbar2' => '',
                    'toolbar3' => ''

                ) ,
                'textarea_rows' => 1,
                'teeny' => true,
                'quicktags' => false,
                'media_buttons' => false
            );

?>
    
<h3><?php _e("Add Invoice", "blank"); ?></h3>
<?php
            if (isset($_POST['submit']) and $_POST['action'] == 'product')
            {

                if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                {
                    return _e('Sorry, your nonce did not verify', 'aistore');

                }

               
                $ship_to = sanitize_text_field($_REQUEST['ship_to']);
                $amount = intval(sanitize_text_field($_REQUEST['amount']));
                 $invoice_id = sanitize_text_field($_REQUEST['invoice_id']);
                $description = sanitize_text_field($_REQUEST['description']);
                $product_id = ($_REQUEST['product_id']);

                $customer_id = sanitize_text_field($_REQUEST['customer_id']);
                $bill_to = sanitize_text_field($_REQUEST['bill_to']);
                
                $currency = sanitize_text_field($_REQUEST['currency']);
                $fee = sanitize_text_field($_REQUEST['fee']);
                
               $chk = "";
    foreach ($product_id as $chk1)
    {
        $chk .= $chk1 . ",";
    }

    $pid = substr_replace($chk, "", -1);
               

               
 global $wpdb;
$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_invoice ( invoice_id,ship_to,amount, user_id ,description,product_id,customer_id,bill_to,currency,fee,company_id) VALUES (%s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s)", array(
            $invoice_id,
            $ship_to,
            $amount,
            $user_id,
            $description,
            $pid,
            $customer_id,
            $bill_to,
            $currency,
            $fee,
            $company->id
            
           
        )));   
          $id = $wpdb->insert_id;
         ?>
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script type="text/javascript">
		
	toastr.success('Add Invoice Successfully');
	</script>
<?php
    
               $message='Add Invoice Successfully';
            sendNotification($id,$message);    
            
    $aistore_list_invoice_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_list_invoice') ,
                ) , home_url()));
                
             //   header("Location: ".$aistore_list_invoice_url);
   ?>
        
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_list_invoice_url); ?>" />  
    
   
    <?php 
       
                
            }
            else
            {

?>
      <div >
      <form method="POST" action="" name="product" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

<label>Payment Instructions: </label><br/>
<label><?php echo esc_attr($payment->payment_instructions ); ?></label><br/>
 
   
    
   <br>  
   <div class="mb-3">   
<label><?php _e('Invoice Id', 'aistore'); ?></label><br/>
  <input class="input" type="text" id="invoice_id" name="invoice_id" required></div><br><br>
    <br>   
    
        <div class="mb-3">   
<label><?php _e('Amount', 'aistore'); ?></label><br/>
  <input class="input" type="text" id="amount" name="amount" required></div><br><br>
    <br>      


   <div class="mb-3">   
<label><?php _e('Fee', 'aistore'); ?></label><br/>
  <input class="input" type="text" id="fee" name="fee" required></div><br><br>
    <br>      
   
     <div class="mb-3">   
<label><?php _e('Currency', 'aistore'); ?></label> <?php $aistore_add_product_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_currency') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_product_page_id_url); ?>"  >   (Add  Currency)</a><br/>

<?php
 global $wpdb;
 $results =aistore_books_getuserCurrency($user_id,$company->id);?>
  <select name="currency" id="currency">
      <?php
       foreach ($results as $currency)
                {

                    echo ' <option value="' . esc_attr($currency->currency) . '">' . esc_attr($currency->currency) . '   </option>';

                }

?>

</select>
 </div><br><br>
    <br>   
   



    <div class="mb-3">   
<label><?php _e('Products', 'aistore'); ?></label> <?php $aistore_add_product_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_product') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_product_page_id_url); ?>"  >   (Add  Product)</a><br>
   <div class="multiselect">

    <div id="checkboxes">
           <?php
        global $wpdb;
     
   $results_product=  aistore_books_getProducts( $user_id,$company->id);
                foreach ($results_product as $product)
                {
?>
      <label for="one">
        <input type="checkbox" id="<?php echo esc_attr($product->id);?>" name="product_id[]" value="<?php echo esc_attr($product->id);?>" /><?php echo esc_attr($product->name);?></label><br>
   <?php } ?>
    </div>
  </div><br><br>
  
  <?php 
   global $wpdb;
 

   
 $results= aistore_books_getCustomer($user_id, $company->id);
   ?>
    
    <div class="mb-3">   
<label><?php _e('Customer', 'aistore'); ?></label><?php $aistore_add_product_page_id_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_add_customer') ,
                ) , home_url()));
                
                ?>
                <a href="<?php echo esc_url($aistore_add_product_page_id_url); ?>"  >   (Add  Customer)</a><br>
  <select name="customer_id" id="customer_id">
      <?php

                foreach ($results as $row)
                {

                    echo ' <option value="' . esc_attr($row->id) . '">' . esc_attr($row->customer_name) . '   </option>';

                }

?>
  </select></div><br><br>



 <br>     <div class="mb-3">   <label><?php _e('Bill To', 'aistore'); ?></label>
 <br/>
   <?php
                $content = '';
                $editor_id = 'bill_to';

                $bill_to = $editor;

                wp_editor($content, $editor_id, $bill_to);

?></div>
 

  
  <br>    <div class="mb-3">   <label><?php _e('Ship To', 'aistore'); ?></label><br/>

   <?php
                $content = '';
                $editor_id = 'ship_to';

                $ship_to = $editor;

                wp_editor($content, $editor_id, $ship_to);

?></div>

 
  <br>    <div class="mb-3">   <label><?php _e('Description', 'aistore'); ?></label><br/>

   <?php
                $content = '';
                $editor_id = 'description';

                $description = $editor;

                wp_editor($content, $editor_id, $description);

?></div>
<br>


  

  
  

  
  <input  type="submit"  name="submit" value="<?php _e('Submit', 'aistore') ?>">
  <input type="hidden" name="action" value="product" />
</form></div>
  </div><br><br>

      <?php
            }
        
}