<?php

//require plugin_dir_path( dirname( __FILE__ ) ) . 'diagnosis-plugin/database.php';
class DiagnosisPage{

	private $database;

	public function __construct(){
		$this->database = new DignosisDB();

	} 

	function diagnosis_page_function(){
	
	    if(!current_user_can('manage_options')){
	        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	    }
	    /*add any form processing code here in PHP:*/
	    echo '
	    <div class="container" style ="margin-top:20px">
	    <div class="well well-lg">
	    <h3><span style="position:relative;top:-7px">Insert this  shortcut into any page "[diagnosis_form]"</span></h3></div></div>';
	  }
	  
	function diagnosislist_page_function(){
	    global $wpdb;
	    $diagnosis= $wpdb->get_results( "SELECT * FROM wp_diagnosis ORDER BY created_at DESC" );
		//var_dump($diagnosis);
	    if(!current_user_can('manage_options')){
	       wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	    }
	    
	    echo '
		  <div class ="container" style="margin-top:40px">
			<p>Diagnosis List</p> 
			<table class="wp-list-table widefat fixed striped posts">
			<thead>
			<tr>
				
				<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a style ="color:#444;font-weight:bold;" ><span >Status</span><span class="sorting-indicator"></span></a>
				</th>
				<th scope="col" id="date" class="manage-column column-date sortable asc"><a style ="color:#444; font-weight:bold;" ><span>Firstname</span><span class="sorting-indicator"></span></a>
				</th>
				<th style ="color:#444; font-weight:bold;" scope="col" id="thumbnail" class="manage-column column-thumbnail">Lastname
				</th>
				<th style ="color:#444; font-weight:bold;" scope="col" id="to" class="manage-column column-to">Email
				</th>
				<th style ="color:#444; font-weight:bold;" scope="col" id="read_count" class="manage-column column-read_count">
				Mobile
				</th>
				<th style ="color:#444; font-weight:bold;" scope="col" id="read_count2" class="manage-column column-read_count">
				Date Booked
				</th>
				<th style ="color:#444; font-weight:bold;" scope="col" id="read_count1" class="manage-column column-read_count">
				Change Status
				</th>
			</tr>
		</thead>';
	    if(isset($diagnosis) && count($diagnosis) > 0 ){
	    	echo '<tbody id="the\-list">';
	    	foreach ($diagnosis as $key => $value) {
	    		# code...
				      //<a href ='admin.php?page=diagnosis_detail_page'>
	    			  $statusid = 'statusid'.$value->ID;
	    			  $statusname ='statusname'.$value->ID; 
				      echo
				      	"
				      	<tr class='no-items' style='color:ash;font-family:courier'>
						 
				        <td scope='col' id='read_count' class='manage-column column-read_count'><a style='color:inherit; font-size:18px;font-weight:bolder' href = 'admin.php?page=diagnosis_detail_page&id=$value->ID' id ='$statusname'>$value->status</a></td>
				        <td scope='col' id='read_count' class='manage-column column-read_count'><a style='color:inherit;font-family:Lora;font-size:1.1em;text-transform:capitalize' href = 'admin.php?page=diagnosis_detail_page&id=$value->ID'>$value->first_name</a></td>
				        <td scope='col' id='read_count' class='manage-column column-read_count'><a style='color:inherit; font-family:Lora;font-size:1.1em;text-transform:capitalize' href = 'admin.php?page=diagnosis_detail_page&id==$value->ID'>$value->last_name</a></td>
				        <td scope='col' id='read_count' class='manage-column column-read_count'><a style='color:inherit;font-family:Lora;font-size:1.1em' href = 'admin.php?page=diagnosis_detail_page&id=$value->ID'>$value->email</a></td>
				        <td scope='col' id='read_count' class='manage-column column-read_count'><a style='color:inherit;font-family:Lora;font-size:1.1em' href = 'admin.php?page=diagnosis_detail_page&id=$value->ID'>$value->mobile</a></td>
				        <td scope='col' id='read_count' class='manage-column column-read_count'><a style='color:inherit;font-family:Lora;font-size:1.1em' href = 'admin.php?page=diagnosis_detail_page&id=$value->ID'>$value->booked_date</a></td>
				        <td scope='col' id='read_count' class='manage-column column-read_count'>
				        <a style='color:inherit' href='javascript:void(0)' onclick =changeStatus('$statusid','$statusname','$value->ID')>  
				        <img id ='$statusid' width ='30' src='".$this->database->testStatus($value->status)."'  title = '$value->status'></a></td>
				      </tr>";
				      //</a>
			    }
			    echo ' </tbody>
			    <tfoot>
				<tr>
				
						<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a style ="color:#444; font-weight:bold;"><span>Status</span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="date" class="manage-column column-date sortable asc"><a style ="color:#444; font-weight:bold;" ><span>Firstname</span><span class="sorting-indicator"></span></a>
						</th>
						<th style ="color:#444; font-weight:bold;" scope="col" id="thumbnail" class="manage-column column-thumbnail">Lastname
						</th>
						<th style ="color:#444; font-weight:bold;" scope="col" id="to" class="manage-column column-email">Email
						</th>
						<th style ="color:#444; font-weight:bold;" scope="col" id="read_count" class="manage-column column-phone">
						Mobile
						</th>
						<th style ="color:#444; font-weight:bold;" scope="col" id="read_count2" class="manage-column column-thumbnail">
						Date Booked
						</th>
						<th style ="color:#444; font-weight:bold;" scope="col" id="read_count1" class="manage-column column-read_count">
						Change Status
						</th>
						
				</tr>
				</tfoot>


			    </table>
				</div>
				</div>';
	    }
	    else{
	    	echo'
		    <tbody>
		      <tr class="no-item">
		        <td class="colspanchange" colspan="8">No Record found</td>
		        
		      </tr>
		    </tbody>
		    <tfoot>
			<tr>
				
					<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a style ="color:#444; font-weight:bold;" ><span>Status</span><span class="sorting-indicator"></span></a>
					</th>
					<th scope="col" id="date" class="manage-column column-date sortable asc"><a style ="color:#444; font-weight:bold;" ><span>Firstname</span><span class="sorting-indicator"></span></a>
					</th>
					<th style ="color:#444; font-weight:bold;" scope="col" id="thumbnail" class="manage-column column-thumbnail">Lastname
					</th>
					<th style ="color:#444; font-weight:bold;" scope="col" id="to" class="manage-column column-email">Email
					</th>
					<th style ="color:#444; font-weight:bold;" scope="col" id="read_count" class="manage-column column-phone">
					Mobile
					</th>
					<th style ="color:#444; font-weight:bold;" scope="col" id="read_count1" class="manage-column column-read_count">
					Status
					</th>
					<th style ="color:#444; font-weight:bold;" scope="col" id="read_count2" class="manage-column column-thumbnail">
					Date Booked
					</th>
					
			</tr>
		</tfoot>

	    </table>
		</div>
		</div>';
	   }
	}
	function diagnosis_detail_function(){

	    global $wpdb;
	    if(!current_user_can('manage_options')){
	        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	    }
	    /*add any form processing code here in PHP:*/
	    if(isset($_GET['id']) && is_numeric($_GET['id'])){
			 $id = $_GET['id'];
			 $diagnosisdetail = $wpdb->get_results("SELECT d.first_name,d.last_name,d.email,d.booked_date,d.mobile,d.status,d.comments, GROUP_CONCAT(du.file_url,'---') as fileurl FROM wp_diagnosis_uploads AS du 
				INNER JOIN wp_diagnosis AS d ON du.diagnosis_id = d.ID WHERE d.ID = $id AND du.diagnosis_id = $id GROUP BY du.diagnosis_id");

			 if(count($diagnosisdetail)  > 0){
			 	//var_dump(plugin_dir_url( __FILE__ ));
	        	$files = $diagnosisdetail[0]->fileurl;
	        	$file_url = explode('---', $files);
		        echo 
		        '<div class="container" style = "margin-top:10px;">	
		        <div>
                    <h4 class="card-header">Diagnosis Profile</h4>
                    <a class= "btn btn-info" style =" margin-top:5px; font-size:0.8em; float:right" href ="admin.php?page=diagnosis_chat_page&diagnosis_id='.$id.'"> Chat User</a>
                </div>
	                <span><br></span>
	                <div class="row justify-content-sm-center">
	                    <div class="col-sm-10">
	                        <div class="form-group row">
	                            <label for="text-input" class="col-2 col-form-label offset-1">First Name: </label>
	                            <div class="col-6 align-self-start">
	                            '.$diagnosisdetail[0]->first_name .'
	                            </div>
	                        </div>

	                    <div class="form-group row">
	                        <label for="text-input" class="col-2 col-form-label offset-1">Last Name: </label>
	                        <div class="col-6 align-self-start">
	                        '.$diagnosisdetail[0]->last_name .'  
	                        </div>
	                    </div>

	                    <div class="form-group row">
	                        <label for="text-input" class="col-2 col-form-label offset-1">Date of booking: </label>
	                        <div class="col-6 align-self-start">
	                      '.$diagnosisdetail[0]->booked_date .'
	                        </div>
	                    </div>

	                    
	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Email Addresss: </label>
	                    <div class="col-6">
	                    '.$diagnosisdetail[0]->email .'
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Mobile Number: </label>
	                    <div class="col-6">
	                   '.$diagnosisdetail[0]->mobile .'
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Status: </label>
	                    <div class="col-6">
	                    '.$diagnosisdetail[0]->status .'
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Comment: </label>
	                    <div class="col-6">
	                   '.$diagnosisdetail[0]->comments .'
	                    </div>
	                </div>
                 </div>   
	            </div>';
				if(isset($file_url) && count($file_url) >0) //if file exist
		            {
		             echo '<p style="font-weight:bolder; font-family:Lora; font-size:1em;">File Upload</p>
		               <div class="card-deck">';
			          for ($i = 0 ; $i < count($file_url) - 1 ; $i++) {
			          	if (strpos($file_url[$i], ',') !== false) {
						   $file_url[$i] = str_replace(",","",$file_url[$i]);
						}
						echo '<div class="card">
								<a href ="'.wp_upload_dir()['url'].'/'.$file_url[$i].'" target ="_blank">
			                    <img style ="max-height:300px;max-width:300px;" class="card-img-top img-responsive" src='.wp_upload_dir()['url'].'/'.$file_url[$i].' alt="diagnosis image">
			                    </a>
			                </div>';
			           }
			           echo '</div>';
		            }

		            echo '</div>'; //container div closed
				           
				}

			 else if( count($diagnosisdetail)  == 0){ //if no file is uploades
		     	$diagnosis = $wpdb->get_row("SELECT * FROM wp_diagnosis where ID = $id"); //check only diagnosis table
		     	if(count($diagnosis)>0){
		     	'<div class="container" style = "margin-top:10px;">	
	            	 <div>
                    <h4 class="card-header">Diagnosis Profile</h4>
                    <a class= "btn btn-info" style =" margin-top:5px; font-size:0.8em; float:right" href ="admin.php?page=diagnosis_chat_page&diagnosis_id='.$id.'"> Chat User</a>
                	</div>
	                <span><br></span>
	                <div class="row justify-content-sm-center">
	                    <div class="col-sm-10">
	                        <div class="form-group row">
	                            <label for="text-input" class="col-2 col-form-label offset-1">First Name: </label>
	                            <div class="col-6 align-self-start">
	                            '.$diagnosisdetail[0]->first_name .'
	                            </div>
	                        </div>

	                    <div class="form-group row">
	                        <label for="text-input" class="col-2 col-form-label offset-1">Last Name: </label>
	                        <div class="col-6 align-self-start">
	                        '.$diagnosisdetail[0]->last_name .'  
	                        </div>
	                    </div>

	                    <div class="form-group row">
	                        <label for="text-input" class="col-2 col-form-label offset-1">Date of booking: </label>
	                        <div class="col-6 align-self-start">
	                      '.$diagnosisdetail[0]->booked_date .'
	                        </div>
	                    </div>

	                    
	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Email Addresss: </label>
	                    <div class="col-6">
	                    '.$diagnosisdetail[0]->email .'
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Mobile Number: </label>
	                    <div class="col-6">
	                   '.$diagnosisdetail[0]->mobile .'
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Status: </label>
	                    <div class="col-6">
	                    '.$diagnosisdetail[0]->status .'
	                    </div>
	                </div>

	                <div class="form-group row">
	                    <label for="text-input" class="col-2 col-form-label offset-1">Comment: </label>
	                    <div class="col-6">
	                   '.$diagnosisdetail[0]->comments .'
	                    </div>
	                </div> 
	               </div>   
	             </div>
	           </div>'; //container close

		     	}
		     	else{
		     		wp_die( "No Result found");
		     		
		     	}

		     }
		  else{
	     	wp_die( "No Result found");
	      }
	     }

	     else{
	     	wp_die( "No Result found");
	     }
	   
	  }

	  public function diagnosis_chat_function(){
	  	$database = new DignosisDB();
	  	if(!is_user_logged_in()){
	  		wp_login_form();
	  	}
	  	else{
	    
		    if(isset($_GET['diagnosis_id']) && !empty($_GET['diagnosis_id']) && is_numeric($_GET['diagnosis_id'])){
		    	$id = $_GET['diagnosis_id'];
		    	$messages = $database->getDiagnosisMessage($id);
			  	echo '<div class="content container bootstrap snippets">
			      <div class="row row-broken">
			        <div class="col-sm-9 col-xs-12 col-lg-8 col-md-10 chat" style="overflow-y:scroll; outline: none;" tabindex="5001">
			          <div class="col-inside-lg decor-default">
			            <div class="chat-body">
			              <h6>Diagnosis Chat</h6>';
							if(count($messages) > 0){
							    foreach ($messages as $key => $message) {
								    if($message->sender_user_id == wp_get_current_user()->data->ID){
								    	echo ' <div class="answer left">
							                <div class="avatar">
							                  <img width ="30" src="'.plugins_url("images/usericon.jpg", __FILE__).'" alt="Dr vivan">
							                  <div class="status online"></div>
							                </div>
							                <div class="name" style="font-weight:bold;text-transform:lowercase;font-size:0.6em;" >'.$database->getSendername($message->sender_user_id).'</div>
							                <div class="text">
							                 '.$message->message.'
							                </div>
							                <div class="time" style="font-family: \'Droid Sans\', sans-serif;font-size:0.6em;">'.$database->getDateFormat($message->created_at).'</div>
							              </div>';
								    }
								    else{
								    	echo ' <div class="answer right">
							                <div class="avatar">
							                  <img src="'.plugins_url("images/usericon.jpg", __FILE__).' " alt="Dr vivan">
							                  <div class="status online"></div>
							                </div>
							                <div class="name" style="font-weight:bold;text-transform:lowercase;font-size:0.6em;">'.$database->getSendername($message->sender_user_id).'</div>
							                <div class="text">
							                 '.$message->message.'
							                </div>
							                <div class="time" style="font-family: \'Droid Sans\', sans-serif; font-size:0.6em">'.$database->getDateFormat($message->created_at).'</div>
							              </div>';
								    }
							   	
								}
							}
							else{
								echo ' <div class="answer left">
					                <div class="text">
					                 No Message yet
					                </div>
					              </div>';
							}
			                echo'
				              <div class="answer-add">
				              	<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
				                <input placeholder="Write a message" type ="text" name ="message" required>
				                <input type="submit" class="answer-btn answer-btn-2" id ="submitmessage" value =" " name ="submit">
				            	</form>
				              </div>
				            </div>
				          </div>
				        </div>
				      </div>
				    </div>';
				     if(isset($_POST['submit'])){
			    	// echo "hellosandy";
			    	$message =  sanitize_text_field($_POST['message']);
			    	$diagnosis_id = $id;
			    	$database->storeDiagnosisMessage($id,$message);

			    	}
			}
			else{
		    	wp_die( __( 'You cant access this page.' ) );
		    }
		}
	  }
}
?>