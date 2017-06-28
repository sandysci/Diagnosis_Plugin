
function changeStatus(statusid,statusname,id){
		alert("Are you sure you want to change this?")
		console.log(id);
	    var pendingurl = image_name.pendingUrl;
	    var completeurl = image_name.completedUrl;
		console.log(statusid);
		console.log(statusname);
		if(event.target.title == "pending"){
			updateStatus(id,"completed",completeurl,statusid,statusname);
		}
		else{
			updateStatus(id,"pending",pendingurl,statusid,statusname);
		}
	}

function updateStatus(id,status,url,statusid,statusname){
	document.getElementById(statusname).innerHTML =status;
	document.getElementById(statusid).src = url;
	document.getElementById(statusid).title = status;

	var link = MyAjax.ajaxurl;
	jQuery.ajax({
		type: 'POST',   // Adding Post method
		url: link, // Including ajax file
		data: {"action": "update_status", "id":id,"status":status}, // Sending data dname to post_word_count function.
		success: function(data){ // Show returned data using the function.
			document.getElementById(statusname).innerHTML =status;
			document.getElementById(statusid).src = url;
			document.getElementById(statusid).title = status;
		},
		error: function() { 
            alert("Error"); 
         }    
	});
}

