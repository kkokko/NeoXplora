
var conditions = new NeoAI.TRuleGroup();

$(function(){
	
	

});

function bindGui(){
	$("#addConditionButton").click(function(e){
		
		var string = $('#conditionInput').val();
		var parser = new NeoAI.TIRepConditionParser();
		try{
			var tRuleValue = parser.ParseString(string);
			
			conditions.InsertChild(tRuleValue);
			alert(conditions.toString());
		}catch(e){
			alert(e);
		}
		return false;
	});
}

function displayConditions(){
	
}