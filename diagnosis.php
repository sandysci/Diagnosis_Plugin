<?php
/*
  Plugin Name: Diagnosis Plugin
  Plugin URI: http://wwww.drviv.sci.ng
  Description: Diagnosis.
  Version: 1.0
  Author: Robotic Systems
  Author URI: http://gitlab.com/sandysci
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'diagnosis-plugin/userform.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'diagnosis-plugin/database.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'diagnosis-plugin/pages.php';

function addAdminMenu2(){
    add_menu_page('Diagnosis', 'Diagnosis', 'manage_options', 'diagnosis_page', 'diagnosis_page_function', '', 4);
    add_submenu_page('diagnosis_page','Diagnosis List', 'Diagnosis List','manage_options', 'diagnosis_list_page', 'diagnosis_page2_function');
    add_submenu_page(null,'Diagnosis Detail', 'Diagnosis Detail','manage_options', 'diagnosis_detail_page', 'diagnosis_detail_function');
    add_submenu_page(null,'Diagnosis Chat', 'Diagnosis Chat','manage_options', 'diagnosis_chat_page', 'diagnosis_chat_function');
}
add_action( 'admin_menu', 'addAdminMenu2' );


function admin_css() {

  wp_enqueue_style( 'custom_wp_admin_css', plugins_url('css/bootstrap.css', __FILE__) );
  wp_enqueue_style( 'custom_wp_admin_css2', plugins_url('css/stylechat.css', __FILE__) );
  wp_enqueue_style( 'custom_wp_admin_css3', 'https://fonts.googleapis.com/css?family=Lora|PT+Serif|Droid+Sans' );
  wp_enqueue_style( 'custom_wp_admin_css4', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
  wp_enqueue_script( 'custom_wp_admin_js', plugins_url('js/bootstrap.js', __FILE__) );
  wp_enqueue_script( 'custom_wp_admin_js1', plugins_url('js/styless.js', __FILE__) , array('jquery'));
  
  wp_register_script( 'image_script',  plugins_url('js/styless.js', __FILE__) );
  $image_array = array( 'pendingUrl' => plugins_url('images/pending.png', __FILE__),'completedUrl'=>plugins_url('images/complete.png', __FILE__));
  wp_localize_script( 'image_script', 'image_name', $image_array );
  wp_enqueue_script( 'image_script' );

  wp_register_script( 'ajax_script',  plugins_url('js/styless.js', __FILE__) );
  wp_localize_script( 'ajax_script', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
  wp_enqueue_script( 'ajax_script' );
}

//after wp_enqueue_script

add_action('admin_enqueue_scripts','admin_css');
add_action('wp_enqueue_scripts', 'admin_css');

function diagnosis_page_function(){
    
  $dpage =  new DiagnosisPage;
  $dpage->diagnosis_page_function();
}

function diagnosis_detail_function(){
    
  $dpage =  new DiagnosisPage;
  $dpage->diagnosis_detail_function();
}
function diagnosis_chat_function(){
    
  $dpage =  new DiagnosisPage;
  $dpage->diagnosis_chat_function();
}


function update_status(){
  $db = new DignosisDB;
  $db->update_status();
}
//
add_action('wp_ajax_update_status', 'update_status'); 
add_action('wp_ajax_nopriv_update_status', 'update_status'); 

add_action('init', 'do_output_buffer');
function do_output_buffer() {
    ob_start();
}

function diagnosis_page2_function(){
  $dpage =  new DiagnosisPage;
  $dpage->diagnosislist_page_function();
}
function wpse_load_plugin_css() {
  $user = new User;
  $user->daignosis_plugin_css();

  }

// add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );            

function create_plugin_database_table()
{
    $db = new DignosisDB;
    $db->Activate();
}
 
register_activation_hook( __FILE__, 'create_plugin_database_table' );
 
function userformshortcode() {
    ob_start();
    $user = new User;
    $user->registration_form();
    $user->validate();
   // $user->complete_registration();
    return ob_get_clean();
}

function  diagnosischatshortcode(){

    ob_start();
    $dp = new DiagnosisPage;
    $dp->diagnosis_chat_function();
    return ob_get_clean();
}
add_shortcode( 'diagnosis_form', 'userformshortcode' );
add_shortcode( 'diagnosis_chat_form', 'diagnosischatshortcode' );
?>