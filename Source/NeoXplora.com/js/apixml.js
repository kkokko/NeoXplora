$(document).ready(function() {
  
	$('#executeRequest').click(function() {
		$("input[name='ApiKey']").val($("#ApiKey").val());
    $("#requestForm").submit();
	});
	
});