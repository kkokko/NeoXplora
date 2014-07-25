var MLinkerRuleEditRequests_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  construct: function() {
  	this.base(this);
  },
  
  methods: {
    
    init: function() {
      //nothing to init
    },
        
    /*
     * AJAX REQUESTS
     */
    
    getRuleConditionsData:  function(ruleId){
  		$.ajax({
  			url:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleType,
  				action:"getRuleConditionsData",
  				ruleId:ruleId
  			},
  			success:NeoX.Modules.LinkerRuleEditRequests.getRuleConditionsDataCallback
  		});
    },
  	
  	getRuleValuesData:function(ruleId){
  		$.ajax({
  			url:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleType,
  				action:"getRuleValuesData",
  				ruleId:ruleId
  			},
  			success:NeoX.Modules.LinkerRuleEditRequests.getRuleValuesDataCallback
  		});
  	},
  	
  	postRuleName: function(ruleName,ruleId,ruleType, ruleScore){
  		
  		$.ajax({
  			url:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				"ruleScore":ruleScore,
  				"ruleType":ruleType,
  				"ruleName":ruleName,
  				"ruleId":ruleId,
  				type:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleType,
  				action:"postRuleName"
  			},
  			success:NeoX.Modules.LinkerRuleEditRequests.postRuleNameCallback
  		});
  	},
  	
  	saveValues:function(ruleId,uData){
  		$.ajax({
  			url:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleType,
  				action:"updateRuleValues",
  				ruleId:ruleId,
  				updateData: uData
  			},
  			success:NeoX.Modules.LinkerRuleEditRequests.saveValuesCallback
  		});
  	},
  	
  	saveConditions: function(ruleId, uData){
  		$.ajax({
  			url:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.LinkerRuleEditIndex.getConfig().moduleType,
  				action: "updateRuleConditions",
  				ruleId: ruleId,
  				updateData: uData
  			},
  			success:NeoX.Modules.LinkerRuleEditRequests.saveCondtionsCallback
  		});
  	},
      
      /*
       * AJAX SUCCESS CALLBACKS
       */
      
    getRuleConditionsDataCallback:function(data){
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions = NeoX.Modules.LinkerRuleEditIndex.loadConditionData(JSON.parse(data));
  		NeoX.Modules.LinkerRuleEditIndex.displayConditions();
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions.SetUpdated();
  	},
  	
  	getRuleValuesDataCallback:function (data){
  		var result = JSON.parse(data);
  		if(result.result=="success"){
  			NeoX.Modules.LinkerRuleEditIndex.loadRuleValues(result.data);
  		}
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().values.SetUpdated();
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().deletedValues = [];
  	},
  	
  	postRuleNameCallback:function(data){
  		var result = JSON.parse(data);
  		if(result.actionResult =="success"){
  			$("#ruleId").val(result.ruleId);
  			$("#postRuleNameButton").html("Update");
  			
  			$("#ruleConditionsForm .controls").toggle(true);
  			$("#ruleValuesForm .controls").toggle(true);
  			
  			NeoX.Modules.LinkerRuleEditIndex.initRuleConditionsForm();
  		}else{
  			alert(result.Message);
  		}
  	},
  	
  	saveValuesCallback:function(data){
  		var result = JSON.parse(data);
  		if(result.result=="success"){
  			NeoX.Modules.LinkerRuleEditIndex.loadRuleValues(result.data);
  		}
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().values.SetUpdated();
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().deletedValues = [];
  	},
  	
  	saveCondtionsCallback:function(data){
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions = NeoX.Modules.LinkerRuleEditIndex.loadConditionData(JSON.parse(data));
  		NeoX.Modules.LinkerRuleEditIndex.displayConditions();
  		NeoX.Modules.LinkerRuleEditIndex.getConfig().conditions.SetUpdated();
  	}
      
  }

};

Sky.Class.Define("NeoX.Modules.LinkerRuleEditRequests", MLinkerRuleEditRequests_Implementation);