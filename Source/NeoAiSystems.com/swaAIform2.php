<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<title>Untitled Document</title>
<script>
var ajaxLoading = false;
$(document).ready(function (K) {

	$('input[name=action]').click(function () {			

    var action  = $(this).attr("value");
	$('#swafom').attr('action', action );
	
//	var string1 = K('textarea[name=string1]');
//	var string2 = K('textarea[name=string2]');	
	
 

/*	
 if(!ajaxLoading) {
       ajaxLoading = true;		
		//start the ajax
		K.ajax({
			//this is the php file that processes the data and send mail
			url: action,	
			type: "POST",
			data: {string1: string1.val(), string2: string2.val(),action:action },	
			cache: false,
			success: function (html) {	
				//if process.php returned 1/true (send mail success)
		alert(html);		
				if (html==1) {					
					K('.msgOk').fadeIn('slow');
		K('input[name=storytitle]').val('');
		K('textarea[name=storydetail]').val('');
					
				} else alert('Sorry, unexpected error. Please try again later.');				
			}		
		});
	}	
		
	*/
	});
})
</script>
</head>

<body>
<form id='swafom' action="" method="post">
<label>String 1 </label>
<textarea name="sequence" cols="80" rows="5"></textarea><br />
<label>String 2 </label>
<textarea name="sequence2" cols="80" rows="5"></textarea><br/>

<input type="submit" name="action" value="code.php"/>
<!--<input type="submit"  name="action" value="swaAIPOS.php"/>-->

</form>
</body>
</html>
