var MIRepRuleEditIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
  	this.base(this);
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
		// Main handles
		conditions : null,
		values : null,
		// helper structures
		indexedConditionObj : [],
		deletedConditions : [],
		indexedValueObj : [],
		deletedValues : [],
		literals : {
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
		},
		Buttons:{
			PostRuleName:"#postRuleNameButton",
			MoveUpCondtion:'.MoveUpButton.ConditionControl',
			MoveDownCondtion:'.MoveDownButton.ConditionControl',
			GroupCondtion:'.GroupButton.ConditionControl',
			DeleteCondition:'.DeleteButton.ConditionControl',
			
			DeleteValue:'.DeleteButton.ValueControl',
			
			AddValue:'#addValueButton',
			SaveValue:'#saveValuesButton',
			SaveConditions:'#saveConditionsButton'
			
		},
		Inputs:{
			RuleId:"#ruleId",
			RuleName:"#ruleNameInput",
			ConditionString:'#conditionStringInput',
			ValueString:'#valueStringInput'
		},
		Controls:{
			RuleConditionsForm:"#ruleConditionsForm .controls",
			RuleValuesForm:"#ruleValuesForm .controls",
			PageHeadTitle:"#pageHeadTitle",
			CTSelector:'.CTSelector',
			ConditionList:'#conditionsList',
			ValueList:'#valuesList'
		},
		moduleScript:'panel.php',
		moduleType: 'ireprules'
    }
  },
  
  methods: {
    
    init: function() {
		$(function(){
			NeoX.Modules.IRepRuleEditIndex.bindGUI();
			NeoX.Modules.IRepRuleEditIndex.initRuleConditionsForm();
			NeoX.Modules.IRepRuleEditIndex.initRuleValuesForm();
			NeoX.Modules.IRepRuleEditIndex.getRuleData();
		});
    },
	
	getRuleData: function (){
		var ruleId = parseInt($(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.RuleId).val());
		if(ruleId>=0){
			$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.PostRuleName).html("Update");
			$(NeoX.Modules.IRepRuleEditIndex.getConfig().Controls.RuleConditionsForm).toggle(true);
			$(NeoX.Modules.IRepRuleEditIndex.getConfig().Controls.RuleValuesForm).toggle(true);
			$(NeoX.Modules.IRepRuleEditIndex.getConfig().Controls.PageHeadTitle).html("Edit IRep Rule");
			
			NeoX.Modules.IRepRuleEditRequests.getRuleConditionsData(ruleId);
			NeoX.Modules.IRepRuleEditRequests.getRuleValuesData(ruleId);
		}
	},

	bindGUI: function (){
		NeoX.Modules.IRepRuleEditIndex.bindPostRuleNameButton();
		NeoX.Modules.IRepRuleEditIndex.bindAddConditionButton();
		NeoX.Modules.IRepRuleEditIndex.bindAddValueButton();
		NeoX.Modules.IRepRuleEditIndex.bindSaveValuesButton();
		NeoX.Modules.IRepRuleEditIndex.bindSaveConditionsButton();
	},

	bindConditionsGUI: function (){

		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Controls.CTSelector).change(function(e){
			var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].setConjunctionType($(e.currentTarget).val());
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].SetModified(true);
		});
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.MoveUpCondtion).click(function(e){
			var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].MoveUp();
			NeoX.Modules.IRepRuleEditIndex.displayConditions();
		});
		
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.MoveDownCondtion).click(function(e){
			var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].MoveDown();
			NeoX.Modules.IRepRuleEditIndex.displayConditions();
		});
		
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.GroupCondtion).click(function(e){
			var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getParent().InsertChild(new NeoAI.TRuleGroup(),NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getIndex());
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].MoveUp();
			NeoX.Modules.IRepRuleEditIndex.displayConditions();
		});
		
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.DeleteCondtion).click(function(e){
			var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getParent().RemoveChild(NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getIndex());
			NeoX.Modules.IRepRuleEditIndex.getConfig().deletedConditions.push(NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj[objectIndex]);
			NeoX.Modules.IRepRuleEditIndex.displayConditions();
		});
	},

	 bindValuesGUI: function(){
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.DeleteValue).click(function(e){
			var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
			NeoX.Modules.IRepRuleEditIndex.getConfig().indexedValueObj[objectIndex].getParent().RemoveChild(NeoX.Modules.IRepRuleEditIndex.getConfig().indexedValueObj[objectIndex].getIndex());
			NeoX.Modules.IRepRuleEditIndex.getConfig().deletedValues.push(NeoX.Modules.IRepRuleEditIndex.getConfig().indexedValueObj[objectIndex]);
			NeoX.Modules.IRepRuleEditIndex.displayValues();
		});
	},

	bindPostRuleNameButton: function (){
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.PostRuleName).click(function(e){
			var ruleName = $(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.RuleName).val();
			var ruleId = $(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.RuleId).val();
			if(ruleName != ""){
				NeoX.Modules.IRepRuleEditRequests.postRuleName(ruleName,ruleId);
			}else{
				alert("Please fill the rule name field.");
			}
			
			return false;
		});
		
	},

	bindAddConditionButton : function (){
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.AddCondition).click(function(e){
		
			var parser = new NeoAI.TIRepConditionParser();
			
			try{
				var irepStr = $(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.ConditionString).val();
				var iRepRule = parser.ParseString(irepStr);
				NeoX.Modules.IRepRuleEditIndex.getConfig().conditions.InsertChild(iRepRule);
				$(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.ConditionString).val("");
				NeoX.Modules.IRepRuleEditIndex.displayConditions();
			}catch(e){
				alert(e);
			}
			return false;
		});
	},

	bindAddValueButton: function (){
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.AddValue).click(function(e){
		
			var parser = new NeoAI.TIRepConditionParser();
			
			try{
				var irepStr = $(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.ValueString).val();
				var iRepRule = parser.ParseString(irepStr);
				NeoX.Modules.IRepRuleEditIndex.getConfig().values.InsertChild(iRepRule);
				$(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.ValueString).val("");
				NeoX.Modules.IRepRuleEditIndex.displayValues();
			}catch(e){
				alert(e);
			}
			
			return false;
			
		});
	},

	initRuleConditionsForm: function (){
		NeoX.Modules.IRepRuleEditIndex.getConfig().conditions = new NeoAI.TRuleGroup();
	},
	
	initRuleValuesForm: function (){
		NeoX.Modules.IRepRuleEditIndex.getConfig().values = new NeoAI.TRuleGroup();
	},
	
	displayConditions: function (){

		NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj = [];
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Controls.ConditionList).html(NeoX.Modules.IRepRuleEditIndex.conditionsToHTML(NeoX.Modules.IRepRuleEditIndex.getConfig().conditions));
		NeoX.Modules.IRepRuleEditIndex.bindConditionsGUI();
		
	},

	displayValues: function (){

		NeoX.Modules.IRepRuleEditIndex.getConfig().indexedValueObj = [];
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Controls.ValueList).html(NeoX.Modules.IRepRuleEditIndex.valuesToHTML(NeoX.Modules.IRepRuleEditIndex.getConfig().values));
		NeoX.Modules.IRepRuleEditIndex.bindValuesGUI();
		
	},

	conditionsToHTML: function (conditionTree){
		var resultHTML = "";
		NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj.push(conditionTree);
		var objectIndex = NeoX.Modules.IRepRuleEditIndex.getConfig().indexedConditionObj.length-1;
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
				resultHTML += NeoX.Modules.IRepRuleEditIndex.conditionsToHTML(children[i]);
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
			resultHTML += NeoX.Modules.IRepRuleEditIndex.getConfig().literals[conditionTree.getKeyPropertyType()]+ conditionTree.getPropertyKey()+' '+NeoX.Modules.IRepRuleEditIndex.getConfig().literals[conditionTree.getOperatorType()]+' '+conditionTree.getPropertyValue()+'</li>';
		}
		return resultHTML;
	},

	valuesToHTML :function (valueTree){
		var resultHTML = "";
		NeoX.Modules.IRepRuleEditIndex.getConfig().indexedValueObj.push(valueTree);
		var objectIndex = NeoX.Modules.IRepRuleEditIndex.getConfig().indexedValueObj.length-1;
		if(typeof valueTree.getChildren == 'function'){
			var children = valueTree.getChildren();
			if(children.length>0){
				resultHTML += '<li><div class="GroupHeader"  objIndex="'+objectIndex+'" >';
				
				resultHTML += '</div><ul class="subGroup">';
				var children = valueTree.getChildren();
				for(var i=0;i<children.length;i++){
					resultHTML += NeoX.Modules.IRepRuleEditIndex.valuesToHTML(children[i]);
				}
				resultHTML += '</ul></li>';
			}else{
				return "";
			}
		}else{
			resultHTML += '<li objIndex="'+objectIndex+'">';
			
			resultHTML += '<button class="DeleteButton ValueControl"></button>';
			resultHTML += NeoX.Modules.IRepRuleEditIndex.getConfig().literals[valueTree.getKeyPropertyType()]+ valueTree.getPropertyKey() + ' ' + NeoX.Modules.IRepRuleEditIndex.getConfig().literals[valueTree.getOperatorType()] + ' ' + valueTree.getPropertyValue() + '</li>';
		}
		return resultHTML;
	},

	// DB related

	bindSaveValuesButton: function (){
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.SaveValue).click(function(e){
			var modified = NeoX.Modules.IRepRuleEditIndex.getConfig().values.GetModifiedNodes();
			
			var uData = [];
			
			for(var i=0;i<modified.length;i++){
				if(modified[i].getDBId()==-1 && !(typeof modified[i].getChildren == 'function')){
					uData.push({
						actionType:"insert",
						nodeType:(typeof modified[i].getChildren == 'function')?"Group":"Value",
						KeyPropertyType:modified[i].getKeyPropertyType(),
						PropertyKey:modified[i].getPropertyKey(),
						OperatorType:modified[i].getOperatorType(),
						PropertyValue:modified[i].getPropertyValue()
					});
				}
			}
			for(var i=0;i<NeoX.Modules.IRepRuleEditIndex.getConfig().deletedValues.length;i++){
				if(NeoX.Modules.IRepRuleEditIndex.getConfig().deletedValues[i].getDBId()>0){
					uData.push({
						actionType: "delete",
						dbId:NeoX.Modules.IRepRuleEditIndex.getConfig().deletedValues[i].getDBId()
					});
				}
			}
			if(uData.length>0){
				var ruleId = $(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.RuleId).val();
				NeoX.Modules.IRepRuleEditRequests.saveValues(ruleId,uData);
			}
		});
	},

	loadRuleValues: function (jsonValues){
		NeoX.Modules.IRepRuleEditIndex.getConfig().values= new NeoAI.TRuleGroup();
		for(var i=0;i<jsonValues.length;i++){
			var value = new NeoAI.TRuleValue(jsonValues[i].KeyPropertyType,jsonValues[i].OperandType,jsonValues[i].PropertyKey,jsonValues[i].PropertyValue);
			value.setDBId(jsonValues[i].Id);
			
			NeoX.Modules.IRepRuleEditIndex.getConfig().values.InsertChild(value);
			
		}
		NeoX.Modules.IRepRuleEditIndex.displayValues();
	},

	bindSaveConditionsButton: function (){
		
		$(NeoX.Modules.IRepRuleEditIndex.getConfig().Buttons.SaveConditions).click(function(e){
			
			var modified = NeoX.Modules.IRepRuleEditIndex.getConfig().conditions.GetModifiedNodes();
			
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
						KeyPropertyType:modified[i].getKeyPropertyType(),
						OperatorType:modified[i].getOperatorType(),
						PropertyKey:modified[i].getPropertyKey(),
						PropertyValue:modified[i].getPropertyValue()
					});
				}
			}
			for(var i=0;i<NeoX.Modules.IRepRuleEditIndex.getConfig().deletedConditions.length;i++){
				var nodeType = (typeof NeoX.Modules.IRepRuleEditIndex.getConfig().deletedConditions[i].getChildren == 'function')?"Group":"Value";
				if(NeoX.Modules.IRepRuleEditIndex.getConfig().deletedConditions[i].getDBId()>0){
					uData.push({
						actionType: "delete",
						nodeType:nodeType,
						dbId:NeoX.Modules.IRepRuleEditIndex.getConfig().deletedConditions[i].getDBId()
					});
				}
			}
			if(uData.length>0){
				var ruleId = $(NeoX.Modules.IRepRuleEditIndex.getConfig().Inputs.RuleId).val();
				NeoX.Modules.IRepRuleEditRequests.saveConditions(ruleId,uData);
			}
		
		});
	},

	loadConditionData: function (jsonData){
		if(typeof jsonData.Children != 'undefined'){
			var target = new NeoAI.TRuleGroup();
			target.setDBId(jsonData.id);
			target.setConjunctionType(jsonData.ConjunctionType);
			for(var i=0;i<jsonData.Children.length;i++){
				target.InsertChild(NeoX.Modules.IRepRuleEditIndex.loadConditionData(jsonData.Children[i]));
			}
			return target;
		}else{
			var node = new NeoAI.TRuleValue(jsonData.KeyPropertyType,jsonData.OperandType,jsonData.PropertyKey,jsonData.PropertyValue);
			node.setDBId(jsonData.id);
			return node;
		}
	},
        
  }
  
};

Sky.Class.Define("NeoX.Modules.IRepRuleEditIndex", MIRepRuleEditIndex_Implementation);