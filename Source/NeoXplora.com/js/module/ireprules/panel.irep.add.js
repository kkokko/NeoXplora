
var conditions = null;
var values = null;

var indexedConditionObj = [];
var deletedConditions = [];
var indexedValueObj = [];
var deletedValues = [];

var literals = {
	"ctAnd":"AND",
	"ctOr":"OR",
	'otDiffers':'!=',
	'otLessOrEqual':'<=',
	'otGreaterOrEqual':'>=',
	'otEquals':'=',
	'otLess':'<',
	'otGreater':'>',
	'ptAttribute':'<b>[Attribute]</b> ',
	'ptEvent':'<b>[Event]</b> '
};



$(function(){
	bindGUI();
	initRuleConditionsForm();
	initRuleValuesForm();
	getRuleData();
});

function getRuleData(){
	var ruleId = parseInt($("#ruleId").val());
	if(ruleId>=0){
		$("#postRuleNameButton").html("Update");
		$("#ruleConditionsForm .controls").toggle(true);
		$("#ruleValuesForm .controls").toggle(true);
		$("#pageHeadTitle").html("Edit IRep Rule");
		$.ajax({
			url:"panel.php",
			method:"POST",
			data:{
				type:'ireprules',
				action:"getRuleConditionsData",
				ruleId:ruleId
			}
		}).done(function(data){
			conditions = loadConditionData(JSON.parse(data));
			displayConditions();
			conditions.SetUpdated();
		});

		$.ajax({
			url:"panel.php",
			method:"POST",
			data:{
				type:'ireprules',
				action:"getRuleValuesData",
				ruleId:ruleId
			}
		}).done(function(data){
			var result = JSON.parse(data);
			if(result.result=="success"){
				loadRuleValues(result.data);
			}
			values.SetUpdated();
			deletedValues = [];
		});
		
	}
}

function bindGUI(){
	bindPostRuleNameButton();
	bindAddConditionButton();
	bindAddValueButton();
	bindSaveValuesButton();
	bindSaveConditionsButton();
}

function bindConditionsGUI(){

	$('.CTSelector').change(function(e){
		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
		indexedConditionObj[objectIndex].setConjunctionType($(e.currentTarget).val());
		indexedConditionObj[objectIndex].SetModified(true);
	});
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
		deletedConditions.push(indexedConditionObj[objectIndex]);
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
				data:{
					"ruleName":ruleName,
					"ruleId":ruleId,
					type:'ireprules',
					action:"postRuleName"
				}
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
			$('#conditionStringInput').val("");
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
			$('#valueStringInput').val("");
			displayValues();
		}catch(e){
			alert(e);
		}
		
		return false;
		
	});
}

function initRuleConditionsForm(){
	conditions = new NeoAI.TRuleGroup();
}

function initRuleValuesForm(){
	values = new NeoAI.TRuleGroup();
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
		resultHTML += '<li><div class="GroupHeader"  objIndex="'+objectIndex+'" >Conjunction Type:';
		var cType = conditionTree.getConjunctionType()=="ctAnd";
		resultHTML += '<select class="CTSelector"><option value="ctAnd" '+((cType)?'selected="selected"':'')+'>AND</option><option value="ctOr" '+((!cType)?'selected="selected"':'')+'>OR</option></select>';
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
		resultHTML += literals[conditionTree.getPropertyType()]+ conditionTree.getPropertyKey()+' '+literals[conditionTree.getOperatorType()]+' '+conditionTree.getPropertyValue()+'</li>';
	}
	return resultHTML;
}

function valuesToHTML(valueTree){
	var resultHTML = "";
	indexedValueObj.push(valueTree);
	var objectIndex = indexedValueObj.length-1;
	if(typeof valueTree.getChildren == 'function'){
		var children = valueTree.getChildren();
		if(children.length>0){
			resultHTML += '<li><div class="GroupHeader"  objIndex="'+objectIndex+'" >';
			
			resultHTML += '</div><ul class="subGroup">';
			var children = valueTree.getChildren();
			for(var i=0;i<children.length;i++){
				resultHTML += valuesToHTML(children[i]);
			}
			resultHTML += '</ul></li>';
		}else{
			return "";
		}
	}else{
		resultHTML += '<li objIndex="'+objectIndex+'">';
		if(valueTree.CanMoveUp()){
			resultHTML += '<button class="MoveUpButton ValueControl"></button>';
		}
		if(valueTree.CanMoveDown()){
			resultHTML += '<button class="MoveDownButton ValueControl"></button>';
		}
		resultHTML += '<button class="DeleteButton ValueControl"></button>';
		resultHTML += literals[valueTree.getPropertyType()]+ valueTree.getPropertyKey() + ' ' + literals[valueTree.getOperatorType()] + ' ' + valueTree.getPropertyValue() + '</li>';
	}
	return resultHTML;
}

// DB related

function bindSaveValuesButton(){
	$('#saveValuesButton').click(function(e){
		var modified = values.GetModifiedNodes();
		
		var uData = [];
		
		for(var i=0;i<modified.length;i++){
			if(modified[i].getDBId()==-1 && !(typeof modified[i].getChildren == 'function')){
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
					type:'ireprules',
					action:"updateRuleValues",
					ruleId:ruleId,
					updateData: uData
				}
			}).done(function(data){
				
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

function bindSaveConditionsButton(){
	
	$('#saveConditionsButton').click(function(e){
		
		var modified = conditions.GetModifiedNodes();
		
		var uData = [];
		
		for(var i=0;i<modified.length;i++){
			var nodeType = (typeof modified[i].getChildren == 'function')?"Group":"Value";
			if(nodeType=='Group'){
				uData.push({
					actionType:"update",
					nodeType:nodeType,
					Order:modified[i].getIndex(),
					dbId:modified[i].getDBId(),
					ParentLocalId:modified.indexOf(modified[i].getParent()),
					// group data
					ConjunctionType:modified[i].getConjunctionType()
				});
			}else{
				uData.push({
					actionType:"update",
					nodeType:nodeType,
					Order:modified[i].getIndex(),
					dbId:modified[i].getDBId(),
					ParentLocalId:modified.indexOf(modified[i].getParent()),
					// value data:
					PropertyType:modified[i].getPropertyType(),
					OperatorType:modified[i].getOperatorType(),
					PropertyKey:modified[i].getPropertyKey(),
					PropertyValue:modified[i].getPropertyValue()
				});
			}
		}
		for(var i=0;i<deletedConditions.length;i++){
			var nodeType = (typeof deletedConditions[i].getChildren == 'function')?"Group":"Value";
			if(deletedConditions[i].getDBId()>0){
				uData.push({
					actionType: "delete",
					nodeType:nodeType,
					dbId:deletedConditions[i].getDBId()
				});
			}
		}
		if(uData.length>0){
			var ruleId = $("#ruleId").val();
			$.ajax({
				url:"panel.php",
				method:"POST",
				data:{
					type:'ireprules',
					action:"updateRuleConditions",
					ruleId:ruleId,
					updateData:uData
				}
			}).done(function(data){
				conditions = loadConditionData(JSON.parse(data));
				displayConditions();
				conditions.SetUpdated();
			});
		}
	
	});
}

function loadConditionData(jsonData){
	if(typeof jsonData.Children != 'undefined'){
		var target = new NeoAI.TRuleGroup();
		target.setDBId(jsonData.id);
		target.setConjunctionType(jsonData.ConjunctionType);
		for(var i=0;i<jsonData.Children.length;i++){
			target.InsertChild(loadConditionData(jsonData.Children[i]));
		}
		return target;
	}else{
		var node = new NeoAI.TRuleValue(jsonData.PropertyType,jsonData.OperandType,jsonData.PropertyKey,jsonData.PropertyValue);
		node.setDBId(jsonData.id);
		return node;
	}
}



