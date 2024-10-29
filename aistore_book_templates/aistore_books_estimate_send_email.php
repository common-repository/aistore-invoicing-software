<?php

 function aistore_estimate_email_page(){
     if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
      
      
         $user_id = get_current_user_id();
      $customer_id = sanitize_text_field($_REQUEST['customer_id']);
       $estimate_id = sanitize_text_field($_REQUEST['estimate_id']);
        global $wpdb;
        

         $company = aistore_books_getCompany($user_id);
         
        $estimate= aistore_books_getestimaterbyid($user_id,$company->id,$estimate_id);
            $payment=   aistore_books_getpaymentbyid($user_id,$company->id);
           
          $customer  = aistore_books_getcustomerbyid($user_id,$company->id,$customer_id);
           
       if (isset($_POST['submit']) and $_POST['action'] == 'email_system')
        {
            
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }
             
             global $wpdb;
           
              $alternate_email = sanitize_text_field($_REQUEST['alternate_email']);
              $cc = sanitize_text_field($_REQUEST['cc']);
              
              $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}aistore_books_customer
    SET alternate_email = '%s' , cc = '%s'  WHERE id = '%d' and user_id=%s", $alternate_email,$cc,$customer_id,$user_id));
    
    
  
 
    
 $headers = array('Content-Type: text/html; charset=UTF-8');
   $subject = 'Estimate';
   
//   $message =' <div class="container">
//     <div class="row">
//         <div class="col-xs-12">
//     		<div class="invoice-title">
//     			<h2>Estimate  # '. $estimate_id.'</h2>
//     		</div>
//     		<hr>
//     		<div class="row">
//     			<div class="col-left">
//     				<address>
//     				<strong>Billed To:</strong><br>
//     				'. $customer->customer_name.'<br>
//     					'. $customer->customer_email.'<br>
//     			'.$estimate->bill_to.'
//     				</address><br>
//     			</div>
//     			<div class="col-right">
//     				<address>
//         			<strong>Shipped To:</strong><br>
//         				'. $customer->customer_name.'<br>
//         				'.$customer->customer_email.'<br>
//     				'.$estimate->ship_to.'
//     				</address><br>
//     			</div>
//     		</div>
//     		<div class="row">
//     			<div class="col-xs-6">
//     				<address>
//     					<strong>Payment Method:</strong><br>
//     				'.$payment->payment_instructions.'<br>
//     				'. $customer->customer_email.'<br>
//     				</address><br>
//     			</div>
//     			<div class="col-xs-6 text-right">
//     				<address>
//     					<strong> Date:</strong><br>
//     				'.$estimate->created_at.'<br><br>
//     				</address>
//     			</div>
//     		</div>
//     	</div>
//     </div>
    
//     <div class="row">
//     	<div class="col-md-12">
//     		<div class="panel panel-default">
//     			<div class="panel-heading">
//     				<h3 class="panel-title"><strong>Description</strong></h3>
//     			</div>
//     			<div class="panel-body">
//     				<div class="table-responsive">
//     					<table class="table table-condensed">
//     						<thead>
//                                 <tr>
//         							<td><strong>Product</strong></td>
//         							<td class="text-center"><strong> Price</strong></td>
//         							<td class="text-center"><strong> Quantity</strong></td>
//         							<td class="text-right"><strong> Total</strong></td>
//                                 </tr>
//     						</thead>
//     						<tbody>';
    				
    							    $str_arr = explode(",", $estimate->product_id);
    				$total_amount =0;
        foreach ($str_arr as $key => $product_id)
        {
$product = aistore_books_getproductbyid($user_id,$company->id,$product_id);

$total_amount= $total_amount + $product->amount;
   
         


    						 
    				// 	 $message .=	'<tr>
    				// 				<td>'.$product->name.'</td>
    				// 				<td class="text-center">
    				// 			' .$estimate->currency . ' '.$product->amount.'</td>
    				// 				<td class="text-center">1</td>
    				// 				<td class="text-right">
    				// 				'.$estimate->currency . ' '.$product->amount.'</td>
    				// 			</tr>';
    							
        }
        
              $fee = $estimate->fee;
    
    
    
    				// 		 $message .=		'<tr>
    				// 				<td class="thick-line"></td>
    				// 				<td class="thick-line"></td>
    				// 				<td class="thick-line text-center"><strong>Subtotal</strong></td>
    				// 				<td class="thick-line text-right">
    				// 			'.$estimate->currency.' '.$total_amount.'</td>
    				// 			</tr>
    				// 			<tr>
    				// 				<td class="no-line"></td>
    				// 				<td class="no-line"></td>
    				// 				<td class="no-line text-center"><strong>Shipping</strong></td>
    				// 				<td class="no-line text-right">'.$estimate->currency .' '.$fee.'</td>
    				// 			</tr>';
    							
    							$total_amount = $total_amount+$fee;
    							
//     						 $message .= 	'<tr>
//     								<td class="no-line"></td>
//     								<td class="no-line"></td>
//     								<td class="no-line text-center"><strong>Total</strong></td>
//     								<td class="no-line text-right">
//     								'.$estimate->currency .' '.$total_amount.'</td>
//     							</tr>
//     						</tbody>
//     					</table>
//     				</div>
//     			</div>
//     		</div>
//     	</div>
//     </div>
// </div>';


 $html='<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Estimate</title>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
								 <img src="'.$company->logo.'" style="width: 100%; max-width: 200px">
									
								</td>

								<td>
									Estimate #: '. $estimate_id.'<br />
									Created: '.$estimate->created_at.'<br />
								<!--	Due: February 1, 2015-->
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									'. $customer->customer_name.'<br>
    					'. $customer->customer_email.'<br>
    			'.$estimate->bill_to.'
								</td>

								<td>
									'. $customer->customer_name.'<br>
    					'. $customer->customer_email.'<br>
    			'.$estimate->ship_to.'
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>Payment Method</td>

					<td> #</td>
				</tr>

				<tr class="details">
					<td>'.$payment->payment_instructions.'<br>
    				</td>

					<td>'.$product->amount.'</td>
				</tr>

				<tr class="heading">
					<td>Item</td>

					<td>Price</td>
				</tr>

				<tr class="item">
					<td>'.$product->name.'</td>

					<td>'.$estimate->currency ." ".$product->amount.'</td>
				</tr>

				<tr class="item">
					<td>Fee</td>

					<td>'.$estimate->currency ." ". $fee.'</td>
				</tr>

				

				<tr class="total">
					<td></td>

					<td>Total: 	'.$estimate->currency .' '.$total_amount.'</td>
				</tr>
			</table>
		</div>
	</body>
</html>
';

$file= aistore_books_html_pdf( $html,'aistore_book_estimate_'.$estimate_id.'.pdf' );
$attachments = array($file);
     wp_mail($alternate_email, $subject, $html ,$headers ,$attachments );
//   echo "<script>alert('Email Sent Successfully. Check your inbox');</script>";
 ?>
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script type="text/javascript">
		
	toastr.success('Email Sent Successfully. Check your inbox');
	</script>
<?php

    $message='Email Sent Successfully. Check your inbox';
            sendNotification($estimate_id,$message); 
            
  $aistore_list_estimate_url = esc_url(add_query_arg(array(
                    'page_id' =>get_option('aistore_list_estimate')
                ) , home_url()));
                
        // header('Location: '.$aistore_list_estimate_url);      
    ?>
        
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($aistore_list_estimate_url); ?>" />  
    
   
    <?php    
}
else{
?>   
    
    <form method="POST" action="" name="email_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

           
  <label for="title"><?php _e('Email', 'aistore'); ?></label><br>
  <input class="input" type="text" id="alternate_email" name="alternate_email" value="<?php echo esc_attr($customer->alternate_email); ?>"><br>


  <label for="title"><?php _e('Cc', 'aistore'); ?></label><br>
  <input class="input" type="text" id="cc" name="cc" value="<?php echo esc_attr($customer->cc); ?>"><br><br>
 
         <input 
 type="submit" class="btn" name="submit" value="<?php _e('Submit', 'aistore') ?>"/>
<input type="hidden" name="action" value="email_system" />
</form>  
<?php
    
}   
}