
var conditions = null;
var values = null;

var indexedConditionObj = [];
var indexedValueObj = [];

$(function(){
	bindGUI();
	initRuleConditionsForm();
	initRuleValuesForm();

});

function bindGUI(){
	bindPostRuleNameButton();
	bindAddConditionButton();
	bindAddValueButton();
}

function bindConditionsGUI(){
	$('.MoveUpButton.ConditionControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedConditionObj[objectIndex].MoveUp();
		displayConditions();
	});
	
	$('.MoveDownButton.ConditionControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedConditionObj[objectIndex].MoveDown();
		displayConditions();
	});
	
	$('.GroupButton.ConditionControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedConditionObj[objectIndex].Parent.InsertChild(new TRuleGroup(),indexedConditionObj[objectIndex].Index);
		indexedConditionObj[objectIndex].MoveUp();
		displayConditions();
	});
	
	$('.DeleteButton.ConditionControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedConditionObj[objectIndex].Parent.RemoveChild(indexedConditionObj[objectIndex].Index);
		displayConditions();
	});
}

function bindValuesGUI(){
	$('.MoveUpButton.ValueControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedValueObj[objectIndex].MoveUp();
		displayValues();
	});
	
	$('.MoveDownButton.ValueControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedValueObj[objectIndex].MoveDown();
		displayValues();
	});
	
	$('.GroupButton.ValueControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedValueObj[objectIndex].Parent.InsertChild(new TRuleGroup(),indexedValueObj[objectIndex].Index);
		indexedValueObj[objectIndex].MoveUp();
		displayValues();
	});
	
	$('.DeleteButton.ValueControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedValueObj[objectIndex].Parent.RemoveChild(indexedValueObj[objectIndex].Index);
		displayValues();
	});
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
					$("#ruleValuesForm .controls").toggle(true);
					
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

function bindAddConditionButton(){
	$('#addConditionButton').click(function(e){
	
		var parser = new TIRepConditionParser();
		
		try{
			var irepStr = $('#conditionStringInput').val();
			var iRepRule = parser.ParseString(irepStr);
			conditions.InsertChild(iRepRule);
			displayConditions();
		}catch(e){
			alert(e);
		}
		
		return false;
		
	});
}

function bindAddValueButton(){
	$('#addValueButton').click(function(e){
	
		var parser = new TIRepConditionParser();
		
		try{
			var irepStr = $('#valueStringInput').val();
			var iRepRule = parser.ParseString(irepStr);
			values.InsertChild(iRepRule);
			displayValues();
		}catch(e){
			alert(e);
		}
		
		return false;
		
	});
}

function initRuleConditionsForm(){
	conditions = new TRuleGroup()
}

function initRuleValuesForm(){
	values = new TRuleGroup()
}



function displayConditions(){

	indexedConditionObj = [];
	$('#conditionsList').html(conditionsToHTML(conditions));
	bindConditionsGUI();
	
}

function displayValues(){

	indexedValueObj = [];
	$('#valuesList').html(valuesToHTML(values));
	bindValuesGUI();
	
}

function conditionsToHTML(conditionTree){
	var resultHTML = "";
	indexedConditionObj.push(conditionTree);
	var objectIndex = indexedConditionObj.length-1;
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
			resultHTML += '<button class="DeleteButton ConditionControl"></button>';
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
		resultHTML += '<button class="DeleteButton ConditionControl"></button>';
		resultHTML += conditionTree.PropertyKey+' '+conditionTree.OperatorType+' '+
					conditionTree.PropertyValue+'</li>';
	}
	return resultHTML;
}

function valuesToHTML(valueTree){
	var resultHTML = "";
	indexedValueObj.push(valueTree);
	var objectIndex = indexedValueObj.length-1;
	if(valueTree.hasOwnProperty("Children")){
		resultHTML += '<li><div class="GroupHeader"  objIndex="'+objectIndex+'" >'+((valueTree.Index>0)?valueTree.ConjunctionType+" ":"");
		if(valueTree.CanMoveUp()){
			resultHTML += '<button class="MoveUpButton ValueControl"></button>';
		}
		if(valueTree.CanMoveDown()){
			resultHTML += '<button class="MoveDownButton ValueControl"></button>';
		}
		if(valueTree.Parent!=null){
			resultHTML += '<button class="GroupButton ValueControl"></button>';
			resultHTML += '<button class="DeleteButton ValueControl"></button>';
		}
		
		resultHTML += '</div><ul class="subGroup">';
		for(var i=0;i<valueTree.Children.length;i++){
			resultHTML += valuesToHTML(valueTree.Children[i]);
		}
		resultHTML += '</ul></li>';
	}else{
		resultHTML += '<li objIndex="'+objectIndex+'">';
		if(valueTree.CanMoveUp()){
			resultHTML += '<button class="MoveUpButton ValueControl"></button>';
		}
		if(valueTree.CanMoveDown()){
			resultHTML += '<button class="MoveDownButton ValueControl"></button>';
		}
		resultHTML += '<button class="GroupButton ValueControl"></button>';
		resultHTML += '<button class="DeleteButton ValueControl"></button>';
		resultHTML += valueTree.PropertyKey+' '+valueTree.OperatorType+' '+
					valueTree.PropertyValue+'</li>';
	}
	return resultHTML;
}



