
var conditions = null;
var values = null;

var indexedConditionObj = [];
var indexedValueObj = [];
var deletedValues = [];

$(function(){
	bindGUI();
	initRuleConditionsForm();
	initRuleValuesForm();

});

function bindGUI(){
	bindPostRuleNameButton();
	bindAddConditionButton();
	bindAddValueButton();
	bindSaveValuesButton();
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
		indexedConditionObj[objectIndex].getParent().InsertChild(new NeoAI.TRuleGroup(),indexedConditionObj[objectIndex].getIndex());
		indexedConditionObj[objectIndex].MoveUp();
		displayConditions();
	});
	
	$('.DeleteButton.ConditionControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedConditionObj[objectIndex].getParent().RemoveChild(indexedConditionObj[objectIndex].getIndex());
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
	
	$('.DeleteButton.ValueControl').click(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedValueObj[objectIndex].getParent().RemoveChild(indexedValueObj[objectIndex].getIndex());
		deletedValues.push(indexedValueObj[objectIndex]);
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
	
		var parser = new NeoAI.TIRepConditionParser();
		
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
	
		var parser = new NeoAI.TIRepConditionParser();
		
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
	conditions = new NeoAI.TRuleGroup()
}

function initRuleValuesForm(){
	values = new NeoAI.TRuleGroup()
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
	if(typeof conditionTree.getChildren == 'function'){
		resultHTML += '<li><div class="GroupHeader"  objIndex="'+objectIndex+'" >'+conditionTree.getConjunctionType();
		if(conditionTree.CanMoveUp()){
			resultHTML += '<button class="MoveUpButton ConditionControl"></button>';
		}
		if(conditionTree.CanMoveDown()){
			resultHTML += '<button class="MoveDownButton ConditionControl"></button>';
		}
		if(conditionTree.getParent() != null){
			resultHTML += '<button class="GroupButton ConditionControl"></button>';
			resultHTML += '<button class="DeleteButton ConditionControl"></button>';
		}
		
		resultHTML += '</div><ul class="subGroup">';
		var children = conditionTree.getChildren();
		for(var i=0;i<children.length;i++){
			resultHTML += conditionsToHTML(children[i]);
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
		resultHTML += conditionTree.getPropertyKey()+' '+conditionTree.getOperatorType()+' '+conditionTree.getPropertyValue()+'</li>';
	}
	return resultHTML;
}

function valuesToHTML(valueTree){
	var resultHTML = "";
	indexedValueObj.push(valueTree);
	var objectIndex = indexedValueObj.length-1;
	if(typeof valueTree.getChildren == 'function'){
		resultHTML += '<li><div class="GroupHeader"  objIndex="'+objectIndex+'" >'+valueTree.getConjunctionType();
		
		resultHTML += '</div><ul class="subGroup">';
		var children = valueTree.getChildren();
		for(var i=0;i<children.length;i++){
			resultHTML += valuesToHTML(children[i]);
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
		resultHTML += '<button class="DeleteButton ValueControl"></button>';
		resultHTML += valueTree.getPropertyKey() + ' ' + valueTree.getOperatorType() + ' ' + valueTree.getPropertyValue() + '</li>';
	}
	return resultHTML;
}

// DB related

function bindSaveValuesButton(){
	$('#saveValuesButton').click(function(e){
		var modified = values.GetModifiedNodes();
		alert(deletedValues.join("\n"));
		
		var uData = [];
		
		for(var i=0;i<modified.length;i++){
			if(modified[i].getDBId()>0){
				//uData.push({actionType:"update",dbId:modified[i].getDBId(),order:modified[i].getIndex()});
			}else{
				uData.push({
					actionType:"insert",
					nodeType:(typeof modified[i].getChildren == 'function')?"Group":"Value",
					PropertyType:modified[i].getPropertyType(),
					PropertyKey:modified[i].getPropertyKey(),
					OperatorType:modified[i].getOperatorType(),
					PropertyValue:modified[i].getPropertyValue()
				});
			}
		}
		for(var i=0;i<deletedValues.length;i++){
			if(deletedValues[i].getDBId()>0){
				uData.push({
					actionType: "delete",
					dbId:deletedValues[i].getDBId()
				});
			}
		}
		if(uData.length>0){
			var ruleId = $("#ruleId").val();
			$.ajax({
				url:"panel.php",
				method:"POST",
				data:{
					action:"irep_updateRuleValues",
					ruleId:ruleId,
					updateData: uData
				}
			}).done(function(data){
				
				alert(data);
				var result = JSON.parse(data);
				if(result.result=="success"){
					loadRuleValues(result.data);
				}
				values.SetUpdated();
				deletedValues = [];
			});
		}
	});
}

function loadRuleValues(jsonValues){
	values= new NeoAI.TRuleGroup();
	for(var i=0;i<jsonValues.length;i++){
		var value = new NeoAI.TRuleValue(jsonValues[i].PropertyType,jsonValues[i].OperandType,jsonValues[i].PropertyKey,jsonValues[i].PropertyValue);
		value.setDBId(jsonValues[i].Id);
		
		values.InsertChild(value);
		
	}
	displayValues();
}



