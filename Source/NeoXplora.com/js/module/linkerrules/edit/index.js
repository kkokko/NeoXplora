var MLinkerRuleEditIndex_Implementation = {
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
  			'otSmallerOrEqual':'<=',
  			'otGreaterOrEqual':'>=',
  			'otEquals':'=',
  			'otSmaller':'<',
  			'otGreater':'>',
  			'ptAttribute':'<b>[Attribute]</b> ',
  			'ptEvent':'<b>[Event]</b> ',
  			'keOne': "<b>[1]</b>",
  			'keTwo': "<b>[2]</b>",
  			'vptAttribute': "<b>[Attribute]</b> ",
  			'vptEvent': "<b>[Event]</b> ",
  			'vptNone': "",
  			'veOne': "<b>[1]</b>",
  			'veTwo': "<b>[2]</b>",
  			'veNone': ""
  		},
  		Buttons:{
  			PostRuleName:"#postRuleNameButton",
  			MoveUpCondition: '.MoveUpButton',
  			MoveDownCondtion:'.MoveDownButton.ConditionControl',
  			GroupCondtion:'.GroupButton.ConditionControl',
  			DeleteCondition:'.DeleteButton',
  			AddCondition: '#addConditionButton',
  			DeleteValue:'.DeleteButton.ValueControl',
  			AddValue:'#addValueButton',
  			SaveValue:'#saveValuesButton',
  			SaveConditions:'#saveConditionsButton'
  			
  		},
  		Inputs:{
  			RuleId:"#ruleId",
  			RuleName:"#ruleNameInput",
  			RuleType:"#ruleTypeInput",
  			RuleScore:"#ruleScore",
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
  		moduleType: 'linkerrule'
    }
  },
  
  methods: {
    
    init: function() {
  		$(function() {
  			NeoX.Modules.LinkerRuleEditIndex.bindGUI();
  			NeoX.Modules.LinkerRuleEditIndex.initRuleConditionsForm();
  			NeoX.Modules.LinkerRuleEditIndex.getRuleData();
  		});
  		this.bindConditionsGUI();
    },
	
  	getRuleData: function() {
  		var ruleId = parseInt($(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleId).val());
  		if(ruleId>=0){
  			$(NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.PostRuleName).html("Update");
  			$(NeoX.Modules.LinkerRuleEditIndex.getConfig().Controls.RuleConditionsForm).toggle(true);
  			$(NeoX.Modules.LinkerRuleEditIndex.getConfig().Controls.PageHeadTitle).html("Edit Rule");
  			
  			NeoX.Modules.LinkerRuleEditRequests.getRuleConditionsData(ruleId);
  		}
  	},
  
  	bindGUI: function() {
      this.hookEvent("click", NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.PostRuleName, NeoX.Modules.LinkerRuleEditIndex.bindPostRuleNameButton);
  		this.hookEvent("click", NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.AddCondition, NeoX.Modules.LinkerRuleEditIndex.bindAddConditionButton);
      this.hookEvent("click", NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.SaveConditions, NeoX.Modules.LinkerRuleEditIndex.bindSaveConditionsButton);
      this.hookEvent("change", NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleType, NeoX.Modules.LinkerRuleEditIndex.onRuleTypeChange)
  		
  	},
  
  	onConjunctionTypeChange: function(e) {
  		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
      NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].setConjunctionType($(e.currentTarget).val());
      NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].SetModified(true);
  	},
  	
  	onRuleTypeChange: function(e) {
      if($(this).val() == "rtScoring") {
        $(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleScore).show();
      } else {
      	$(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleScore).hide();
      }
    },
  	
  	onConditionMoveUp: function(e) {
  		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
      NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].MoveUp();
      NeoX.Modules.LinkerRuleEditIndex.displayConditions();
  	},
  	
  	onConditionMoveDown: function(e) {
  		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
      NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].MoveDown();
      NeoX.Modules.LinkerRuleEditIndex.displayConditions();
  	},
  	
  	onConditionsGroup: function(e) {
  		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
      NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getParent().InsertChild(new NeoAI.TRuleGroup(),NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getIndex());
      NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].MoveUp();
      NeoX.Modules.LinkerRuleEditIndex.displayConditions();
  	},
  	
  	onConditionDelete: function(e) {
  		var objectIndex = parseInt($(e.currentTarget).parent().attr("objIndex"));
      NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getParent().RemoveChild(NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex].getIndex());
      NeoX.Modules.LinkerRuleEditIndex.getConfig().deletedConditions.push(NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj[objectIndex]);
      NeoX.Modules.LinkerRuleEditIndex.displayConditions();
  	},
  	
  	bindConditionsGUI: function() {
  		this.hookEvent("change", NeoX.Modules.LinkerRuleEditIndex.getConfig().Controls.CTSelector, NeoX.Modules.LinkerRuleEditIndex.onConjunctionTypeChange);
  		this.hookEvent("click", NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.MoveUpCondition, NeoX.Modules.LinkerRuleEditIndex.onConditionMoveUp);
  		this.hookEvent("click", NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.MoveDownCondtion, NeoX.Modules.LinkerRuleEditIndex.onConditionMoveDown);
  		this.hookEvent("click", NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.GroupCondtion, NeoX.Modules.LinkerRuleEditIndex.onConditionsGroup);
  		this.hookEvent("click", NeoX.Modules.LinkerRuleEditIndex.getConfig().Buttons.DeleteCondition, NeoX.Modules.LinkerRuleEditIndex.onConditionDelete);
  	},
  
  	bindPostRuleNameButton: function (){
  		var ruleType = $(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleType).val();
  		var ruleScore = $(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleScore).val();
  		var ruleName = $(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleName).val();
  		var ruleId = $(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleId).val();
  		if(ruleName == ""){
  			alert("Please fill the rule name field.");
  		} else if(ruleScore == "" && ruleType == "rtScoring") {
  		  alert("Please fill the rule score field.");
  		} else {
  			NeoX.Modules.LinkerRuleEditRequests.postRuleName(ruleName,ruleId,ruleType,parseInt(ruleScore, 10));
  		}
  		return false;
  	},
  
  	bindAddConditionButton : function (e) {
  		var parser = new NeoAI.TLinkerConditionParser();
  		
  		try{
  			var irepStr = $(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.ConditionString).val();
  			var iRepRule = parser.ParseString(irepStr);
  			NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions.InsertChild(iRepRule);
  			$(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.ConditionString).val("");
  			NeoX.Modules.LinkerRuleEditIndex.displayConditions();
  		} catch(e) {
  			alert(e);
  		}
  		return false;
  	},
  
  	initRuleConditionsForm: function (){
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions = new NeoAI.TRuleGroup();
  	},
  	
  	displayConditions: function (){
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj = [];
  		$(NeoX.Modules.LinkerRuleEditIndex.getConfig().Controls.ConditionList).html(NeoX.Modules.LinkerRuleEditIndex.conditionsToHTML(NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions));
  		//NeoX.Modules.LinkerRuleEditIndex.bindConditionsGUI();
  		
  	},
  
  	conditionsToHTML: function (conditionTree){
  		var resultHTML = "";
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj.push(conditionTree);
  		var objectIndex = NeoX.Modules.LinkerRuleEditIndex.getConfig().indexedConditionObj.length-1;
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
  				resultHTML += NeoX.Modules.LinkerRuleEditIndex.conditionsToHTML(children[i]);
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
  			resultHTML += NeoX.Modules.LinkerRuleEditIndex.getConfig().literals[conditionTree.getKeyEntity()]+NeoX.Modules.LinkerRuleEditIndex.getConfig().literals[conditionTree.getKeyPropertyType()]+conditionTree.getPropertyKey()+' '+NeoX.Modules.LinkerRuleEditIndex.getConfig().literals[conditionTree.getOperatorType()]+' '+NeoX.Modules.LinkerRuleEditIndex.getConfig().literals[conditionTree.getValueEntity()]+NeoX.Modules.LinkerRuleEditIndex.getConfig().literals[conditionTree.getValuePropertyType()]+conditionTree.getPropertyValue()+'</li>';
  		}
  		return resultHTML;
  	},
  
  	// DB related
  
  	bindSaveConditionsButton: function() {
  		var modified = NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions.GetModifiedNodes();
  		
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
  					KeyEntity:modified[i].getKeyEntity(),
  					KeyPropertyType:modified[i].getKeyPropertyType(),
  					PropertyKey:modified[i].getPropertyKey(),
  					OperatorType:modified[i].getOperatorType(),
  					ValueEntity:modified[i].getValueEntity(),
            ValuePropertyType:modified[i].getValuePropertyType(),
  					PropertyValue:modified[i].getPropertyValue()
  				});
  			}
  		}
  		for(var i=0;i<NeoX.Modules.LinkerRuleEditIndex.getConfig().deletedConditions.length;i++){
  			var nodeType = (typeof NeoX.Modules.LinkerRuleEditIndex.getConfig().deletedConditions[i].getChildren == 'function')?"Group":"Value";
  			if(NeoX.Modules.LinkerRuleEditIndex.getConfig().deletedConditions[i].getDBId()>0){
  				uData.push({
  					actionType: "delete",
  					nodeType:nodeType,
  					dbId:NeoX.Modules.LinkerRuleEditIndex.getConfig().deletedConditions[i].getDBId()
  				});
  			}
  		}
  		if(uData.length>0){
  			var ruleId = $(NeoX.Modules.LinkerRuleEditIndex.getConfig().Inputs.RuleId).val();
  			NeoX.Modules.LinkerRuleEditRequests.saveConditions(ruleId,uData);
  		}
  	},
  
  	loadConditionData: function (jsonData){
  		if(typeof jsonData.Children != 'undefined'){
  			var target = new NeoAI.TRuleGroup();
  			target.setDBId(jsonData.id);
  			target.setConjunctionType(jsonData.ConjunctionType);
  			for(var i=0;i<jsonData.Children.length;i++){
  				target.InsertChild(NeoX.Modules.LinkerRuleEditIndex.loadConditionData(jsonData.Children[i]));
  			}
  			return target;
  		} else {
  			var node = new NeoAI.TLinkerRuleValue(
          jsonData.KeyEntity, 
          jsonData.KeyPropertyType,
          jsonData.PropertyKey,
          jsonData.OperandType,
          jsonData.ValueEntity, 
          jsonData.ValuePropertyType,
          jsonData.PropertyValue
        );
  			node.setDBId(jsonData.id);
  			return node;
  		}
  	}
        
  }
  
};

Sky.Class.Define("NeoX.Modules.LinkerRuleEditIndex", MLinkerRuleEditIndex_Implementation);