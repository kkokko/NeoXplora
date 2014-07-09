
var conditions = null;

var indexedObj = [];

$(function(){
	bindGUI();
	initRuleConditionsForm();

});

function bindGUI(){
	bindPostRuleNameButton();
	bindAddConditionButton();
}

function bindAddConditionButton(){
	$('#addConditionButton').click(function(e){
	
		var parser = new TIRepConditionParser();
		
		try{
			var irepStr = $('#ruleStringInput').val();
			var iRepRule = parser.ParseString(irepStr);
			conditions.InsertChild(iRepRule);
			displayConditions();
		}catch(e){
			alert(e);
		}
		
		return false;
		
	});
}

function initRuleConditionsForm(){
	conditions = new TRuleGroup()
}


function bindPostRuleNameButton(){
	
	$("#postRuleNameButton").click(function(e){
		var ruleName = $("#ruleNameInput").val();
		var ruleId = $("#ruleId").val();
		if(ruleName != ""){
			$.ajax({
				url:"panel.php",
				method:"POST",
				data:{"ruleName":ruleName,"ruleId":ruleId, action:"irep_postRuleName"}
			}).done(function(data){
				
				var result = JSON.parse(data);
				if(result.actionResult =="success"){
					$("#ruleId").val(result.ruleId);
					$("#postRuleNameButton").html("Update");
					
					$("#ruleConditionsForm .controls").toggle(true);
					
					initRuleConditionsForm();
				}else{
					alert(result.Message);
				}
			});
			
		}else{
			alert("Please fill the rule name field.")
		}
		
		return false;
	});
	
}

function displayConditions(){

	indexedObj = [];
	$('#rulesList').html(conditionsToHTML(conditions));
	bindConditionsGUI();
	
}

function conditionsToHTML(conditionTree){
	var resultHTML = "";
	indexedObj.push(conditionTree);
	var objectIndex = indexedObj.length-1;
	if(conditionTree.hasOwnProperty("Children")){
		resultHTML += '<li><div class="GroupHeader"  objIndex="'+objectIndex+'" >'+((conditionTree.Index>0)?conditionTree.ConjunctionType+" ":"");
		if(conditionTree.CanMoveUp()){
			resultHTML += '<button class="MoveUpButton ConditionControl"></button>';
		}
		if(conditionTree.CanMoveDown()){
			resultHTML += '<button class="MoveDownButton ConditionControl"></button>';
		}
		if(conditionTree.Parent!=null){
			resultHTML += '<button class="GroupButton ConditionControl"></button>';
		}
		resultHTML += '</div><ul class="subGroup">';
		for(var i=0;i<conditionTree.Children.length;i++){
			resultHTML += conditionsToHTML(conditionTree.Children[i]);
		}
		resultHTML += '</ul></li>';
	}else{
		resultHTML += '<li objIndex="'+objectIndex+'">';
		if(conditionTree.CanMoveUp()){
			resultHTML += '<button class="MoveUpButton ConditionControl"></button>';
		}
		if(conditionTree.CanMoveDown()){
			resultHTML += '<button class="MoveDownButton ConditionControl"></button>';
		}
		resultHTML += '<button class="GroupButton ConditionControl"></button>';
		resultHTML += conditionTree.PropertyKey+' '+conditionTree.OperatorType+' '+
					conditionTree.PropertyValue+((conditionTree.Index>0)?", "+conditionTree.ConjunctionType:"")+'</li>';
	}
	return resultHTML;
}

function bindConditionsGUI(){
	$('.MoveUpButton').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedObj[objectIndex].MoveUp();
		displayConditions();
	});
	
	$('.MoveDownButton').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedObj[objectIndex].MoveDown();
		displayConditions();
	});
	
	$('.GroupButton').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedObj[objectIndex].Parent.InsertChild(new TRuleGroup(),indexedObj[objectIndex].Index);
		indexedObj[objectIndex].MoveUp();
		displayConditions();
	});
}

