<?php


function aistore_books_notification($n){
   global $wpdb;
   $q1=$wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_books_notification (  message,type, user_id,user_email ,reference_id) VALUES ( %s, %s, %s, %s, %s ) ", array( $n['message'],$n['type'],$n['user_id'],  $n['user_email'], $n['reference_id']));
	
	
     $wpdb->query($q1);
    
   
}

function aistore_books_send_email($n){
    $headers = array('Content-Type: text/html; charset=UTF-8');
    	$to= $n['user_email'];
    $message=	$n['message'];
    $subject=	$n['subject'];
wp_mail( $to, $subject, $message, $headers );
}

function aistore_books_getCompany($user_id){
   global $wpdb;
     $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_company where user_id=%s and status='Enabled'",$user_id));   
      if ($results == null)
        {
               $add_company_page_id_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_add_company') ,
                ) , home_url()));
                
              //  header("Location: ".$add_company_page_id_url);
      ?>
        
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($add_company_page_id_url); ?>" />  
    
   
    <?php     
        }
        else{
         return $results;
        }
}

function aistore_books_getpaymentbyid($user_id,$company_id)
{
   global $wpdb;
        $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_payment where user_id=%s and company_id =%s", $user_id,$company_id));
         if ($results == null)
        {
             $add_payment_instructions_page_id_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('aistore_payment_instructions') ,
                ) , home_url()));
                
                  ?>
        
    <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($add_payment_instructions_page_id_url); ?>" />  
    
   
    <?php     
              //  header("Location: ".$add_payment_instructions_page_id_url);
                
        }
        else{
        return $results;
        
        }
}


function aistore_books_getinvoicerbyid($user_id,$company_id,$invoice_id)
{
    global $wpdb;
        $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_invoice where user_id=%s and company_id =%s and id=%s ", $user_id,$company_id, $invoice_id));
         return $results;
}

function aistore_books_getestimaterbyid($user_id,$company_id,$estimate_id)
{ global $wpdb;
 $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_estimate where user_id=%s and company_id =%s and id=%s ", $user_id,$company_id,$estimate_id));
        return $results;
    
}
function aistore_books_getcustomerbyid($user_id,$company_id,$customer_id){
   global $wpdb;
     $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_customer where user_id=%s and company_id =%s and id =%s",$user_id,$company_id,$customer_id));   
 return $results;
 
}

function aistore_books_getproductbyid($user_id,$company_id,$product_id){
   global $wpdb;
     $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_product where user_id=%s and company_id =%s and id =%s",$user_id,$company_id,$product_id));   
 return $results;
 
}
function aistore_books_getuserCurrency($user_id,$company_id){
   global $wpdb;
     $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_currency where user_id=%s and company_id =%s ",$user_id,$company_id));   
 return $results;
 
}


function aistore_books_getVendors($user_id,$company_id){
    
 global $wpdb;
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_vendor where user_id=%s and company_id=%s", $user_id,$company_id));
 return $results;
 
}

function aistore_books_getAccount($user_id,$company_id){
 global $wpdb;
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_accounts where user_id=%s and company_id=%s", $user_id,$company_id));
 return $results;
 
}

function aistore_books_getSubaccountDebit($user_id,$company_id){
 global $wpdb;
     
  $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_subaccounts where user_id=%s and type='debit' and company_id=%s", $user_id,$company_id));
 return $results;
 
}


function aistore_books_getAccountDebit($user_id,$company_id){
 global $wpdb;
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_accounts where user_id=%s and type='debit' and company_id=%s", $user_id,$company_id));
 return $results;
 
}



function aistore_books_getSubaccountCredit($user_id,$company_id){
 global $wpdb;

  $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_subaccounts where user_id=%s and type='credit' and company_id=%s", $user_id,$company_id));
 return $results;
 
}


function aistore_books_getAccountCredit($user_id,$company_id){
 global $wpdb;
        
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_accounts where user_id=%s and type='credit'  and company_id=%s", $user_id,$company_id));
 return $results;
 
}

function aistore_books_getProducts($user_id,$company_id){
     global $wpdb;
     
      $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_product where user_id=%s   and company_id=%s", $user_id,$company_id));

   return $results;
}

function aistore_books_getCustomer($user_id,$company_id){
     global $wpdb;
  
$sql = ($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_customer where user_id=%s  and company_id=%s", $user_id,$company_id));
 $results=   $wpdb->get_results($sql);
 
    return $results;
}

function aistore_books_getEstimates($user_id,$company_id){
     global $wpdb;
 
$sql = ($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_books_estimate where user_id=%s  and company_id=%s ", $user_id,$company_id));
 $results=   $wpdb->get_results($sql);
 
    return $results;
}


