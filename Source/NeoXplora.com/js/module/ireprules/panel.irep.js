$(function(){
	
	$('#rulesSortable').sortable({
		placeholder: "ui-state-highlight",
		update: IRepRulesUpdateHandler
	});
	$( "#rulesSortable" ).disableSelection();

});

function IRepRulesUpdateHandler(event,ui){

	var data = [];
	
	$('#rulesSortable li').each(function(index){
		var el = $(this);
		data.push([el.attr('data-id'),el.index()+1]);
	});
	
	$.ajax({
		url:'panel.php',
		method:'POST',
		data:{
			type:'ireprules',
			action:'updateRulePriority',
			priorityData:data
		}
	}).done(function(data){
		if(data.trim()!="success"){
			alert("Error updating Rules Order");
		}
	});
}

