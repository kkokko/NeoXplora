
var conditions = new TRuleGroup();

$(function(){
	
	

});

function bindGui(){
	$("#addConditionButton").click(function(e){
		
		var string = $('#conditionInput').val();
		var parser = new TIRepConditionParser();
		try{
			var tRuleValue = parser.ParseString(string);
			
			conditions.InsertChild(tRuleValue);
		}catch(e){
			$('#resultError').html('<p style="color:red;">'+e+'</p>');
		}
		
	});
}

function displayConditions(){
	
}