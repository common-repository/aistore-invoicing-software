<?php

// currency need to be dynamic and  when user create entry need to select currency as well



function aistore_invoice_details(){
       $user_id = get_current_user_id();
    $invoice_id = sanitize_text_field($_REQUEST['invoice_id']);
     global $wpdb;
     
                    $company = aistore_books_getCompany($user_id);
      
    $invoice = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_invoice where id=%s and company_id=%s ", $invoice_id,$company->id));
    
    $customer = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_customer where id=%s ", $invoice->customer_id));
         $payment=   aistore_books_getpaymentbyid($user_id,$company->id);
 $total=   $invoice->amount + $invoice->fee ;  
    ?>
  
  <div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2><?php _e('Invoice', 'aistore'); ?>  # <?php echo esc_attr($invoice_id); ?></h2>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-left">
    				<address>
    				<strong><?php _e('Billed To', 'aistore'); ?>:</strong><br>
    					<?php echo esc_attr($customer->customer_name); ?><br>
    						<?php echo esc_attr($customer->customer_email); ?><br>
    				<?php echo esc_attr($invoice->bill_to); ?>
    				</address><br>
    			</div>
    			<div class="col-right">
    				<address>
        			<strong><?php _e('Shipped To', 'aistore'); ?>:</strong><br>
        				<?php echo esc_attr($customer->customer_name); ?><br>
        					<?php echo esc_attr($customer->customer_email); ?><br>
    					<?php echo esc_attr($invoice->ship_to); ?>
    				</address><br>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong><?php _e('Payment Method', 'aistore'); ?>:</strong><br>
    		<?php echo esc_attr($payment->payment_instructions) ; ?><br>
    					<?php echo esc_attr($customer->customer_email); ?><br>
    				</address><br>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
    					<strong> <?php _e('Date', 'aistore'); ?> :</strong><br>
    					<?php echo esc_attr($invoice->created_at); ?><br><br>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong><?php _e('Description', 'aistore'); ?></strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong><?php _e('Product', 'aistore'); ?></strong></td>
        							<td class="text-center"><strong><?php _e('Price', 'aistore'); ?></strong></td>
        						<td class="text-center"><strong><?php _e('Quantity', 'aistore'); ?></strong></td>
        							<td class="text-right"><strong><?php _e('Totals', 'aistore'); ?></strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<!-- foreach ($order->lineItems as $line) or some such thing here -->
    							
    							<?php 
    							  $str_arr = explode(",", $invoice->product_id);
    							$total_amount =0;
        foreach ($str_arr as $key => $product_id)
        {
    						  $product = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_product where id=%s ", $product_id));
    						  
    						  
    							?>
    							<tr>
    								<td><?php echo esc_attr($product->name); ?></td>
    								<td class="text-center">	<?php echo esc_attr($invoice->currency)." " .esc_attr($product->amount); ?></td>
    								<td class="text-center">1</td>
    								<td class="text-right">	<?php echo esc_attr($invoice->currency)." " .esc_attr($product->amount); ?></td>
    								
    									<?php
    									
    									$total_amount = $total_amount+$product->amount;
    								}
    								?>
    							</tr>
        
    							<tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center"><strong><?php _e('Subtotal', 'aistore'); ?></strong></td>
    								<td class="thick-line text-right">	<?php echo esc_attr($invoice->currency)." " .esc_attr($total_amount); ?></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong><?php _e('Shipping', 'aistore'); ?></strong></td>
    								<td class="no-line text-right">	<?php echo esc_attr($invoice->currency)." " .esc_attr($invoice->fee); ?></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong><?php _e('Total', 'aistore'); ?></strong></td>
     <td class="no-line text-right">	<?php echo esc_attr($invoice->fee)+esc_attr($total_amount); ?></td>
    							</tr>
    							
    							
    						
    						</tbody>
    					</table>
    					
    	<?php
		      if (isset($_POST['submit']) and $_POST['action'] == 'paid_invoice')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
            
              $user_id = sanitize_text_field($_REQUEST['user_id']);
               $invoice_id = sanitize_text_field($_REQUEST['invoice_id']);
                $total_amount = sanitize_text_field($_REQUEST['total_amount']);
                
                  $wallet = new AistoreWallet();
    $description="Invoice Completed";
    $currency = $invoice->currency;
    
    $balance = $wallet->aistore_balance($user_id, $currency);
$amount=$total_amount;


if($balance>=$amount){
     $res=$wallet->aistore_debit($user_id, $total_amount,$currency,$description,$invoice_id);
     $res=$wallet->aistore_credit(1, $total_amount,$currency,$description,$invoice_id);
     
      $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_invoice
    SET status = '%s' WHERE id = '%d' and user_id=%s", 'Paid',$invoice_id,$user_id));
            
            
}
else{
    _e( 'Insufficient Balance', 'aistore' ); 
}
        } 
        if($invoice->status == 'pending'){
   ?>
    			          
    <form method="POST" action="" name="paid_invoice" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input class="input" type="hidden" id="user_id" name="user_id" value="<?php echo esc_attr($user_id); ?>">
    <input class="input" type="hidden" id="invoice_id" name="invoice_id" value="<?php echo esc_attr($invoice_id); ?>">
    
      <input class="input" type="hidden" id="total_amount" name="total_amount" value="<?php echo esc_attr($total_amount); ?>">
 
  <input 
 type="submit" class="btn" name="submit" value="<?php _e('Paid', 'aistore') ?>"/>
<input type="hidden" name="action" value="paid_invoice" />

</form>
<?php } ?>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
    <?php
}
?>
