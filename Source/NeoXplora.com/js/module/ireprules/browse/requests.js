var MIRepRuleBrowseRequests_Implementation = {
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
    
    updateOrder: function(data){
  		$.ajax({
  			url:NeoX.Modules.IRepRuleBrowseIndex.getConfig().moduleScript,
  			method:'POST',
  			data:{
  				type:NeoX.Modules.IRepRuleBrowseIndex.getConfig().moduleType,
  				action:'updateRulePriority',
  				priorityData:data
  			},
  			success:NeoX.Modules.IRepRuleBrowseRequests.updateOrderCallback
  		});
  	},
  	
  	deleteRule: function(rule) {
      var ruleId = parseInt(rule.attr("data-id"), 10);
      $.ajax({
        url: NeoX.Modules.IRepRuleBrowseIndex.getConfig().moduleScript,
        method: 'POST',
        data: {
          type: NeoX.Modules.IRepRuleBrowseIndex.getConfig().moduleType,
          action: 'deleteRule',
          ruleId: ruleId
        },
        success: NeoX.Modules.IRepRuleBrowseRequests.deleteRuleCallback(rule)
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    updateOrderCallback: function (data){
  		if(data.trim()!="success"){
  			alert("Error updating Rules Order");
  		}
    },
    
    deleteRuleCallback: function(rule) {
      return function(json) {
        var ul = rule.parent();
        rule.fadeOut(200).remove();
        if(ul.html().trim() == "") {
          $(".rulesContainer").html("<p>No rules found.</p>");
        }
        
      };
    }
       
    
  }

};

Sky.Class.Define("NeoX.Modules.IRepRuleBrowseRequests", MIRepRuleBrowseRequests_Implementation);