<?php

class DignosisDB{
	
	public function Activate(){
    	global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name1 = $wpdb->prefix . 'diagnosis';
        $table_name2 = $wpdb->prefix . 'diagnosis_uploads';
        $table_name3 = $wpdb->prefix . 'diagnosis_messages';
        $user_table = $wpdb->prefix . 'users';
         
         if ( !function_exists('dbDelta') ) {
              require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            }
         if($wpdb->get_var( "show tables like '$table_name1'" ) != $table_name1) 
         {
            $sql = "CREATE TABLE IF NOT EXISTS $table_name1 (
            ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            first_name varchar(55) NOT NULL,
            last_name varchar(55) NOT NULL,
            email varchar(55) NOT NULL,
            mobile varchar(55) NOT NULL,
            booked_date date NOT NULL,
            comments TEXT NOT NULL,
            status ENUM('pending','completed') DEFAULT 'pending',
            user_id BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP NOT NULL,
            FOREIGN KEY (user_id) REFERENCES $user_table(ID)) $charset_collate;";
            dbDelta( $sql );
         }
         if($wpdb->get_var( "show tables like '$table_name2'" ) != $table_name2) 
         {
            $sql = "CREATE TABLE IF NOT EXISTS $table_name2 (
            ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            file_url varchar(255) NOT NULL,
            date_uploaded TIMESTAMP NOT NULL,
            diagnosis_id BIGINT UNSIGNED NOT NULL,
            FOREIGN KEY (diagnosis_id) REFERENCES $table_name1(ID)) $charset_collate;";
            dbDelta( $sql );
         }
         if($wpdb->get_var( "show tables like '$table_name3'" ) != $table_name3) 
         {
            $sql = "CREATE TABLE IF NOT EXISTS $table_name3 (
            ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            message TEXT NOT NULL,
            sender_user_id BIGINT UNSIGNED NOT NULL,
            receiver_user_id BIGINT UNSIGNED NOT NULL,
            diagnosis_id BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP NOT NULL,
            FOREIGN KEY (diagnosis_id) REFERENCES $table_name1(ID),
            FOREIGN KEY (sender_user_id) REFERENCES $user_table(ID),
            FOREIGN KEY (receiver_user_id) REFERENCES $user_table(ID)) $charset_collate;";
            dbDelta( $sql );
         }
	}

    public function storeUser($userdata = array()){
        global $wpdb;
        $user_id = wp_insert_user($userdata);
        $users = $wpdb->get_row( "SELECT * FROM $wpdb->users WHERE ID = $user_id" );
        return $users;
    }
    public function storeDiagnosis($diagnosisdata = array(),$dateuploaded,$diagnosisfiledata = array()){
        global $wpdb;
        $wpdb->insert('wp_diagnosis',$diagnosisdata);
        //echo($wpdb->insert_id);
        $diagnosis_id = $wpdb->insert_id;
        foreach($diagnosisfiledata as $key =>$value){
            $diagnosisfiles = array(
                'date_uploaded'=>$dateuploaded,
                'file_url' => $diagnosisfiledata[$key]['name'],
                'diagnosis_id' =>$diagnosis_id
                );
            $wpdb->insert('wp_diagnosis_uploads',$diagnosisfiles);
        }
        return $diagnosis_id;
    }
    public function getDiagnosisMessage($id){
        global $wpdb;
        $messages = $wpdb->get_results("SELECT * FROM wp_diagnosis_messages WHERE diagnosis_id = $id ");
        return $messages;
    }

    public function storeDiagnosisMessage($id,$message){
        global $wpdb;
        $user = wp_get_current_user();//get loggedin user
        $adminusers = get_users('role=Administrator'); //get admin users
        if($user->roles[0] == 'administrator'){
            $diagnosis = $wpdb->get_row( "SELECT * FROM wp_diagnosis WHERE ID = $id" );
            $diagnosismessagedata = array(
                'receiver_user_id' => $diagnosis->user_id,
                'sender_user_id' => $user->data->ID,
                'message' => $message,
                'created_at' => date("Y-m-d H:i:s"),
                'diagnosis_id' => $id,
                );
            $this->sendMail($diagnosis->email,$message,$diagnosis->first_name,$diagnosis->ID);
            $wpdb->insert('wp_diagnosis_messages',$diagnosismessagedata);
              if (!empty($_POST)) {
                    // do stuff
                   header("location: " . $_SERVER['REQUEST_URI']);
                }
           // var_dump($diagnosismessagedata);
        }
        else{
            $diagnosis = $wpdb->get_row( "SELECT * FROM wp_diagnosis WHERE ID = $id" );
            $diagnosismessagedata = array(
                'receiver_user_id' => $adminusers[0]->ID,
                'sender_user_id' => $user->data->ID,
                'message' => $message,
                'created_at' => date("Y-m-d H:i:s"),
                'diagnosis_id' => $id,
                );
            $wpdb->insert('wp_diagnosis_messages',$diagnosismessagedata);
            //sends reply to all admin
            foreach ($adminusers as $key => $admin) {
                  # code...
                $this->sendMail2($admin[$key]->user_email,$message,$diagnosis->first_name,$diagnosis->ID);
            }
              if (!empty($_POST)) {
                    // do stuff
                   header("location: " . $_SERVER['REQUEST_URI']);
                }
        }
        

    }
    public function sendMail($emailto,$messageform,$username,$id){
        $message  = '';
        $to      = $emailto;
        $subject = 'DR Vivan Oputa';
        $message .= "Hello, you just received this message:<br/>";
        $message .= $messageform;
        $message .= 'Use the following credentials to login<br/>';
        $message .= 'Username: '.$username .' Password : drviv';
        $message .= get_home_url().'/index.php/diagnosischat?diagnosis_id='.$id;
        $mail = wp_mail( $to, $subject, $message);
    }
    public function sendMail2($emailto,$messageform,$firstname,$id){
        $message  = '';
        $to       =  $emailto;
        $subject  = 'DR Vivan Oputa';
        $message .= "Hello, you just received this message from ".$firstname;
        $message .= "<br>";
        $message .= $messageform;
        // $message .= get_site_url().'/index.php/diagnosischat?diagnosis_id='.$id;
        $mail = wp_mail( $to, $subject, $message);
    }

    public function getSendername($id){
         global $wpdb;
         $user = $wpdb->get_row("SELECT * FROM wp_users WHERE ID = $id" );
         return $user->user_login;
    }

    public function  getDateFormat($created_at){
         return  date("F j, Y, g:i a", strtotime($created_at));
    } 


    public function testStatus($status){
        return $status == "pending"?plugins_url("images/pending.png", __FILE__):plugins_url("images/complete.png", __FILE__);
    }
    public function update_status(){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $table_name = 'wp_diagnosis';
        global $wpdb;
        if($wpdb->update($table_name, array('status'=>$status), array('ID'=>$id))){
            return true;
        }
        return false;
    }
} 
?>