<?php
/*
Plugin Name: Aistore invoicing software
Version:  1.0
Plugin URI: #
Author: susheelhbti
Author URI: http://www.aistore2030.com/
Description: Aistore invoicing software  offers a invoicing/billing solution for your small business.   

*/

if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
    
}



require_once 'vendor/autoload.php';

 
 
use Dompdf\Dompdf;

$dompdf = new Dompdf();



// Check if the menu exists
// $menu_name   = 'My First Menu';
// $menu_exists = wp_get_nav_menu_object( $menu_name );
 
// // If it doesn't exist, let's create it.
// if ( ! $menu_exists ) {
//     $menu_id = wp_create_nav_menu($menu_name);
 
//     // Set up default menu items
//     wp_update_nav_menu_item( $menu_id, 0, array(
//         'menu-item-title'   =>  __( 'Account List', 'textdomain' ),
//         'menu-item-classes' => 'Account List',
//         'menu-item-url'     => home_url( '/account-list' ), 
//         'menu-item-status'  => 'publish'
//     ) );
 
//     wp_update_nav_menu_item( $menu_id, 0, array(
//         'menu-item-title'  =>  __( 'Custom Page', 'textdomain' ),
//         'menu-item-url'    => home_url( '/custom/' ), 
//         'menu-item-status' => 'publish'
//     ) );
// }


function aistore_plugin_table_install()
{
    global $wpdb;

  

  $table_aistore_wallet_transactions = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_wallet_transactions  (
     	id  bigint(20)  NOT NULL AUTO_INCREMENT,
   	transaction_id  bigint(20)  NOT NULL,
  user_id bigint(20)  NOT NULL,
   reference bigint(20)   NULL,
   type   varchar(100)  NOT NULL,
   amount  double    NOT NULL,
  balance  double    NOT NULL,
    description  text  NOT NULL,
   currency  varchar(100)   NOT NULL,
    account  varchar(100)   NOT NULL,
from_account  varchar(100)   NOT NULL,
 received_via  varchar(100)   NOT NULL,
 tags  varchar(100)   NOT NULL,
 vendor  varchar(100)   NOT NULL,
  expense_account  varchar(100)   NOT NULL,
 status  varchar(100)   NOT NULL,
   bank_name  varchar(100)   NOT NULL,
  customer  varchar(100)   NOT NULL,
  created_by  int(100)   NOT NULL,
    company_id  int(100)   NOT NULL,
   date  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";



  $table_aistore_category = "CREATE TABLE IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_accounts  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)  NOT NULL,
     type   varchar(100)  NOT NULL,
   account  varchar(100)   NOT NULL,
     company_id  int(100)   NOT NULL,
   date  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";


  $table_aistore_customer_documents = "CREATE TABLE IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_customer_documents  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)  NOT NULL,
     image    longtext   NOT NULL,
   name  varchar(100)   NOT NULL,
     company_id  int(100)   NOT NULL,
     customer_id int(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";



  $table_aistore_vendor_documents = "CREATE TABLE IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_vendor_documents  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)  NOT NULL,
     image    longtext   NOT NULL,
   name  varchar(100)   NOT NULL,
    vendor_id int(100)   NOT NULL,
     company_id  int(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";



  $table_aistore_subcategory = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_subaccounts (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)  NOT NULL,
     type   varchar(100)  NOT NULL,
   account  varchar(100)   NOT NULL,
    subaccount  varchar(100)   NOT NULL,
      company_id  int(100)   NOT NULL,
   date  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";


      $table_aistore_bank = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_bank  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)   NULL,
     bank_name   varchar(100)  NOT NULL,
    branch_name  varchar(100)   NOT NULL,
      company_id  int(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";
    
    
      $table_aistore_vendor = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_vendor  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)   NULL,
     vendor_name   varchar(100)  NOT NULL,
   vendor_email  varchar(100)   NOT NULL,
     company_id  int(100)   NOT NULL,
       alternate_email   varchar(100)  NOT NULL,
   cc  varchar(100)   NOT NULL,
       vendor_type  varchar(100)   NOT NULL,
    mobile_no   varchar(100)  NOT NULL,
   first_name  varchar(100)   NULL,
     last_name  varchar(100)    NULL,
     country  varchar(100)    NULL,
    address   varchar(100) NULL,
   city  varchar(100)    NULL,
     state  varchar(100)    NULL,
     
      zip_code  varchar(100)   NULL,
    image   varchar(100)  NULL,
   pan_number  varchar(100)   NULL,
     gst_number  varchar(100)    NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";


     $table_aistore_invoice = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_invoice  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
   	invoice_id  bigint(20)  NOT NULL,
    user_id bigint(20)   NULL,
     product_id   int(100)  NOT NULL,
   vendor_id  int(100)   NOT NULL,
    bill_to   varchar(100)  NOT NULL,
   ship_to  varchar(100)   NOT NULL,
    description   varchar(100)  NOT NULL,
   amount  int(100)   NOT NULL,
   currency  varchar(100)   NOT NULL,
   fee int(100)   NOT NULL,
   customer_id int(100)   NOT NULL,
    status varchar(100) NOT NULL DEFAULT 'pending',   
     company_id  int(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";


$table_aistore_customer = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_customer  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)   NULL,
     customer_name   varchar(100)  NOT NULL,
   customer_email  varchar(100)   NOT NULL,
    alternate_email   varchar(100)  NOT NULL,
   cc  varchar(100)   NOT NULL,
     company_id  int(100)   NOT NULL,
     
     customer_type  varchar(100)   NOT NULL,
    mobile_no   varchar(100)  NOT NULL,
   first_name  varchar(100)   NOT NULL,
     last_name  varchar(100)   NOT NULL,
     
      
     country  varchar(100)   NULL,
    address   varchar(100)   NULL,
   city  varchar(100)   NULL,
     state  varchar(100)   NULL,
     
      zip_code  varchar(100)   NULL,
    image   varchar(100)   NULL,
   pan_number  varchar(100)   NULL,
     gst_number  varchar(100)    NULL,
     
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";


     $table_aistore_estimate = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_estimate  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)   NULL,
    estimate_id bigint(20)   NULL,
     product_id   int(100)  NOT NULL,
   customer_id  int(100)   NOT NULL,
    bill_to   varchar(100)  NOT NULL,
   ship_to  varchar(100)   NOT NULL,
    description   varchar(100)  NOT NULL,
   amount  int(100)   NOT NULL,
    currency  varchar(100)   NOT NULL,
   fee int(100)   NOT NULL,
     company_id  int(100)   NOT NULL,
       status varchar(100) NOT NULL DEFAULT 'pending',   
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";


     $table_aistore_product = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_product  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)   NULL,
     terms_condtion   varchar(100)  NOT NULL,
   category  int(100)   NOT NULL,
     name   varchar(100)  NOT NULL,
   short_description  varchar(100)   NOT NULL,
    full_description   varchar(100)  NOT NULL,
     tags  varchar(100)   NOT NULL,
    product_type   varchar(100)  NOT NULL,
   amount  int(100)   NOT NULL,
      price  int(100)   NOT NULL,
   company_id  int(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

 $table_aistore_currency = "CREATE TABLE  IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_currency  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  currency varchar(100) NOT NULL,
   symbol  varchar(100)   NOT NULL,
     user_id int(100) NOT NULL,
   company_id  int(100)   NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

 $table_aistore_company = "CREATE TABLE  IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_company  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  company_name varchar(100) NOT NULL,
   pan_number  varchar(100)   NOT NULL,
     user_id int(100) NOT NULL,
   gst_number  int(100)   NOT NULL,
   address varchar(100) NOT NULL,
   status varchar(100) NOT NULL DEFAULT 'Disabled',   
     logo longtext(100)  NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";
  

$table_aistore_payment = "CREATE TABLE  IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_payment  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  payment_instructions varchar(100) NOT NULL,
   company_id  varchar(100)   NOT NULL,
     user_id int(100) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";



  $table_aistore_notification = "CREATE TABLE IF NOT EXISTS  " . $wpdb->prefix . "aistore_books_notification  (
   	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
    user_id bigint(20)  NOT NULL,
     type   varchar(100)  NOT NULL,
   user_email  varchar(100)   NOT NULL,
      message  varchar(100)   NOT NULL,
     reference_id  int(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');




dbDelta($table_aistore_customer_documents);
      dbDelta($table_aistore_vendor_documents);


  dbDelta($table_aistore_currency);
      dbDelta($table_aistore_company);
      dbDelta($table_aistore_wallet_transactions);
      dbDelta($table_aistore_category);
      dbDelta($table_aistore_subcategory);
      dbDelta($table_aistore_bank);
      dbDelta($table_aistore_vendor);
     dbDelta($table_aistore_invoice);
      dbDelta($table_aistore_customer);
     dbDelta($table_aistore_estimate);
       dbDelta($table_aistore_product);
     dbDelta($table_aistore_payment);
     dbDelta($table_aistore_notification);
     

}
register_activation_hook(__FILE__, 'aistore_plugin_table_install');

    


add_action( 'init', 'aistore_books_inc_func');
 
function aistore_books_inc_func() {
     
if (!is_user_logged_in())
    {                            
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
    


$files = glob(dirname(__FILE__). '/*/*.php');

// print_r($files);
foreach ($files as $file) {
    
    // echo $file."<br><br>";
    require($file);   
}

}






 
 function aistore_books_scripts(){
	wp_register_style('bootstrap', plugin_dir_url(__FILE__).'assets/css/bootstrap.min.css', null, 1.0);
	wp_register_script('bootstrap', plugin_dir_url(__FILE__).'assets/js/bootstrap.min.js', array('jquery'),  1.0);

	wp_enqueue_style('bootstrap');
	wp_enqueue_script('bootstrap');
	
	

     
 }
 
add_action('wp_enqueue_scripts','aistore_books_scripts');


 function wpbootstrap_enqueue_styles() {
 
wp_enqueue_style( 'my-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'wpbootstrap_enqueue_styles');


//   $post_details = array(
//   'post_title'    => 'Bank Transaction',
//   'post_content'  => '[aistore_bank_transactions]',
//   'post_status'   => 'publish',
//   'post_author'   => 1,
//   'post_type' => 'page'
//   );
//   wp_insert_post( $post_details );



