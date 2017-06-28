<?php

require plugin_dir_path( dirname( __FILE__ ) ) . 'diagnosis-plugin/database.php';

class User{
	

	private $mobile;
	private $email;
	private $booked_date;
	private $first_name;
	private $last_name;
	private $file_url = array();
	private $comments;
	private $diagnosisdata,$userdata,$diagnosisfiledata = array();
	private $reg_errors = array();
	private $path ;
	private $uploadedfile = array();
	private $db;
	private $created_at;

	public function __construct(){
		// die(the_permalink());
		$this->reg_errors = new WP_Error;
		$this->path = plugin_dir_url( __FILE__ ).'images';
		$this->db = new DignosisDB();
		$this->created_at = date("Y-m-d H:i:s");

	}
	
	public function daignosis_plugin_css() {

      $plugin_url = plugin_dir_url( __FILE__ );

     // wp_enqueue_style( 'style1', $plugin_url . 'css/bootstrap.css' );
    }
 	
	public function registration_form() 
	 {
	 	echo '
	    <style>
	    div {
	        margin-bottom:2px;
	    }
	     
	    input{
	        margin-bottom:4px;
	    }
	    </style>
	    ';
	 
	    echo '
	    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">
	     
	    <div>
	    <label for="firstname">First Name</label>
	    <input type="text" name="first_name" required value="' . ( isset( $_POST['first_name']) ? $this->first_name : null ) . '">
	    </div>
	     
	    <div>
	    <label for="website">Last Name</label>
	    <input type="text" name="last_name" required value="' . ( isset( $_POST['last_name']) ? $this->last_name : null ) . '">
	    </div>
	    <div>
	    <label for="mobile">Mobile <strong>*</strong></label>
	    <input type="tel" name="mobile" required value="' . ( isset( $_POST['mobile'] ) ? $this->mobile : null ) . '">
	    </div>
	     
	    <div>
	    <label for="email">Email <strong>*</strong></label>
	    <input type="text" name="email"required value="' . ( isset( $_POST['email']) ? $this->email : null ) . '">
	    </div>

	    <div>
	    <label for="email">Date of Booking <strong>*</strong></label>
	    <input type="date" required name="booked_date">
	    </div>

	    <div>
	    <label for="email">File url <strong>*</strong></label>
	    <input type="file" required name="file_url[]" accept="image/*" size="50MB" multiple >
	    </div>
	     
	    <div>
	    <label for="comments">Comments</label>
	    <textarea required name="comments">' . ( isset( $_POST['comments']) ? $this->comments : null ) . '</textarea>
	    </div>
	    <br>
	    <div>
	    <input type="submit" name="submit" value="Register"/>
	    </div>
	    </form>
	    ';
	}
	public function getfileMime($value){
		$result = explode('/',$value);
		return $result[1];

	}
	public function validate(){
		if(isset($_POST['submit'])){
			
			$allowedExts = array("gif", "jpeg", "jpg", "png","GIF","JPEG","JPG","PNG");
			$extension = [];
			$i = 0;
			foreach ($_FILES["file_url"]["name"] as $fn) {	
				$check = explode(".", $fn);
				$extension[$i] = end($check);
				$i++;
			}

			if(empty(array_intersect($extension,$allowedExts))){
				return "Only accepts images ";
			}
			
			foreach ($_POST as $key => $value) {
				$$key = $value;
			}
		
			 if ( ! function_exists( 'wp_handle_upload' ) ) {
				    require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
					$files = $_FILES['file_url'];
					$ext = array();
					foreach($_FILES['file_url']['type'] as $key => $value){
					  $ext[$key]= $this->getfileMime($_FILES['file_url']['type'][$key]);
					}
					foreach($_FILES['file_url']['name'] as $key => $value){
						if($_FILES['file_url']['name'][$key]){
							$file = array(
								'name' => time().$key.'.'.$ext[$key],
								'type' =>$_FILES['file_url']['type'][$key],
								'tmp_name' =>$_FILES['file_url']['tmp_name'][$key],
								'error' =>$_FILES['file_url']['error'][$key],
								'size' =>$_FILES['file_url']['size'][$key],
								);
							
							$upload_overrides = array( 'test_form' => false );
							$this->uploadedfile[$key] = $file;
					        wp_handle_upload( $file, $upload_overrides );
						}
						
					}
					var_dump($this->uploadedfile);
				
			$this->mobile = sanitize_text_field($_POST['mobile']);
			$this->email = sanitize_text_field($_POST['email']);
			$this->file_url = $this->uploadedfile;
			$this->first_name = sanitize_text_field($_POST['first_name']);
			$this->last_name = sanitize_text_field($_POST['last_name']);
			$this->comments = sanitize_text_field($_POST['comments']);
			$this->booked_date = sanitize_text_field($_POST['booked_date']);
		
			if ( empty( $this->mobile ) || empty( $this->email ) ) {
	   		 $this->reg_errors->add('field', 'Required form field is missing');
			}
			if (20 < strlen($this->mobile) ) {
			$this->reg_errors->add( 'mobile_length', 'mobile too short. At most 15 characters is required' );
			}
	    	if ( !is_email( $this->email ) ) {
	 	    $this->reg_errors->add( 'email_invalid', 'Email is not valid' );
			}
			
		
			if ( is_wp_error( $this->reg_errors ) ) {
			    foreach ( $this->reg_errors->get_error_messages() as $error ) {
			     
			        echo '<div>';
			        echo '<strong>ERROR</strong>:';
			        echo $error . '<br/>';
			        echo '</div>';  
			    }
			    //return false;
			}


			$this->complete_registration();	
				
	 }
	}
	public function complete_registration() {
    //global $this->reg_errors, $mobile, $password, $email, $website, $first_name, $last_name, $nickname, $comments;
       
       global $wpdb;

       if(count($this->reg_errors->get_error_messages()) == 0){

	       	 $this->diagnosisdata = array(
	        'mobile' => $this->mobile,
	        'email' => $this->email,
	        'booked_date' => $this->booked_date,
	        'first_name'=> $this->first_name,
	        'last_name'=> $this->last_name,
	        
	        'comments' => $this->comments,
	        'created_at'=> $this->created_at
	        );

	       	 $this->diagnosisfiledata = array(
	       	 	'file_url' => $this->file_url
	       	  );
	       	 $date_uploaded = $this->created_at;
	       	 $file_url = $this->file_url;
	       	 $user_count = $wpdb->get_row( "SELECT * FROM $wpdb->users WHERE user_email LIKE '$this->email'" );
	       	 //var_dump ($user_count->ID);
	       	 // echo $user_count['ID'];
			 if(count($user_count) == 0)
			 {
			 	$this->userdata = array(
			 		'user_login'=>$this->first_name,
			 		'user_email'=>$this->email,
			 		'user_pass'=>'drviv',
			 		'user_nicename' => $this->last_name,
			 		'display_name' => $this->last_name. $this->first_name
			 		);
			 	$user= $this->db->storeUser($this->userdata);
			 	$this->diagnosisdata['user_id'] =$user->ID;
			    $diagnosis= $this->db->storeDiagnosis($this->diagnosisdata,$date_uploaded,$file_url);

			 }
			 else{
			 	$this->diagnosisdata['user_id']= $user_count->ID;
			    $diagnosis= $this->db->storeDiagnosis($this->diagnosisdata,$date_uploaded,$file_url);
			 }
			 //var_dump($user_count);
	        //var_dump($this->diagnosisdata);
	        // $user = wp_insert_user( $this->userdata );
	        // if(isset($user)){
	        // echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
	        // }
    	}
    }

    public function getDaignosisChat(){
    	echo 'hello baby';
    } 

}
?>
