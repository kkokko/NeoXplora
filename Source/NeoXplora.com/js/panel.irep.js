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
		data.push([el.attr('data-id'),el.index()]);
	});
	
	$.ajax({
		url:'panel.php',
		method:'POST',
		data:{
			action:'ireprules_UpdateRulePriority',
			priorityData:data
		}
	}).done(function(data){
		
	});
	
	

	
}