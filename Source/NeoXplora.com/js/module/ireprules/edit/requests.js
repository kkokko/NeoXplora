var MIRepRuleEditRequests_Implementation = {
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
  			url:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleType,
  				action:"getRuleConditionsData",
  				ruleId:ruleId
  			},
  			success:NeoX.Modules.IRepRuleEditRequests.getRuleConditionsDataCallback
  		});
    },
  	
  	getRuleValuesData:function(ruleId){
  		$.ajax({
  			url:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleType,
  				action:"getRuleValuesData",
  				ruleId:ruleId
  			},
  			success:NeoX.Modules.IRepRuleEditRequests.getRuleValuesDataCallback
  		});
  	},
  	
  	postRuleName: function(ruleName,ruleId){
  		
  		$.ajax({
  			url:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				"ruleName":ruleName,
  				"ruleId":ruleId,
  				type:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleType,
  				action:"postRuleName"
  			},
  			success:NeoX.Modules.IRepRuleEditRequests.postRuleNameCallback
  		});
  	},
  	
  	saveValues:function(ruleId,uData){
  		$.ajax({
  			url:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleType,
  				action:"updateRuleValues",
  				ruleId:ruleId,
  				updateData: uData
  			},
  			success:NeoX.Modules.IRepRuleEditRequests.saveValuesCallback
  		});
  	},
  	
  	saveConditions: function(ruleId, uData){
  		$.ajax({
  			url:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleScript,
  			method:"POST",
  			data:{
  				type:NeoX.Modules.IRepRuleEditIndex.getConfig().moduleType,
  				action: "updateRuleConditions",
  				ruleId: ruleId,
  				updateData: uData
  			},
  			success:NeoX.Modules.IRepRuleEditRequests.saveCondtionsCallback
  		});
  	},
      
      /*
       * AJAX SUCCESS CALLBACKS
       */
      
      getRuleConditionsDataCallback:function(data){
  		NeoX.Modules.IRepRuleEditIndex.getConfig().conditions = NeoX.Modules.IRepRuleEditIndex.loadConditionData(JSON.parse(data));
  		NeoX.Modules.IRepRuleEditIndex.displayConditions();
  		NeoX.Modules.IRepRuleEditIndex.getConfig().conditions.SetUpdated();
  	},
  	
  	getRuleValuesDataCallback:function (data){
  		var result = JSON.parse(data);
  		if(result.result=="success"){
  			NeoX.Modules.IRepRuleEditIndex.loadRuleValues(result.data);
  		}
  		NeoX.Modules.IRepRuleEditIndex.getConfig().values.SetUpdated();
  		NeoX.Modules.IRepRuleEditIndex.getConfig().deletedValues = [];
  	},
  	
  	postRuleNameCallback:function(data){
  		var result = JSON.parse(data);
  		if(result.actionResult =="success"){
  			$("#ruleId").val(result.ruleId);
  			$("#postRuleNameButton").html("Update");
  			
  			$("#ruleConditionsForm .controls").toggle(true);
  			$("#ruleValuesForm .controls").toggle(true);
  			
  			NeoX.Modules.IRepRuleEditIndex.initRuleConditionsForm();
  		}else{
  			alert(result.Message);
  		}
  	},
  	
  	saveValuesCallback:function(data){
  		var result = JSON.parse(data);
  		if(result.result=="success"){
  			NeoX.Modules.IRepRuleEditIndex.loadRuleValues(result.data);
  		}
  		NeoX.Modules.IRepRuleEditIndex.getConfig().values.SetUpdated();
  		NeoX.Modules.IRepRuleEditIndex.getConfig().deletedValues = [];
  	},
  	
  	saveCondtionsCallback:function(data){
  		NeoX.Modules.IRepRuleEditIndex.getConfig().conditions = NeoX.Modules.IRepRuleEditIndex.loadConditionData(JSON.parse(data));
  		NeoX.Modules.IRepRuleEditIndex.displayConditions();
  		NeoX.Modules.IRepRuleEditIndex.getConfig().conditions.SetUpdated();
  	}
      
    }

};

Sky.Class.Define("NeoX.Modules.IRepRuleEditRequests", MIRepRuleEditRequests_Implementation);