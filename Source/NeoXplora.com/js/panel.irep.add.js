
var conditions = new TRuleGroup();

$(function(){
	
	
	$("#conditionContainer").html(displayConditions(conditions));
	bindGui();
});

function bindGui(){
	$("#addConditionButton").click(function(e){
		
		var string = $('#conditionInput').val();
		var parser = new TIRepConditionParser();
		try{
			var tRuleValue = parser.ParseString(string);
			
			conditions.InsertChild(tRuleValue);
			$("#conditionContainer").html(displayConditions(conditions));
			bindGui();
			
		}catch(e){
			alert(e);
		}
		return false;
	});
	
	$("#addConditionGroupButton").click(function(e){
		
		conditions.InsertChild(new TRuleGroup);
		$("#conditionContainer").html(displayConditions(conditions));
		bindGui();
			
		return false;
	});
	bindSelection();
}

function bindSelection(){
	$('.ruleItem').click(function(e){
	
		var item = $(e.currentTarget);
		item.toggleClass("selected");
		
		
	});
}

function displayConditions(ARuleGroup){
	
	var result = '<input type="text" id="conditionInput" value=". a = b"><button id="addConditionButton">Add condition</button>';
	result += '<button id="addConditionGroupButton">Add condition group</button>';
	result += '<ul class="ruleGroup">';
	for(var i=0;i<ARuleGroup.Children.length;i++){
		result += '<li class="ruleItem">'+ARuleGroup.Children[i].toString()+'</li>';
	}
	
	return result + '</ul>';
}
