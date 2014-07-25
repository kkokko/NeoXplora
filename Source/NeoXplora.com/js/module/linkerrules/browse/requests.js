var MLinkerRuleBrowseRequests_Implementation = {
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
    
    updateOrder: function(data) {
  		$.ajax({
  			url: NeoX.Modules.LinkerRuleBrowseIndex.getConfig().moduleScript,
  			method: 'POST',
  			data: {
  				type: NeoX.Modules.LinkerRuleBrowseIndex.getConfig().moduleType,
  				action: 'updateRulePriority',
  				priorityData: data
  			},
  			success: NeoX.Modules.LinkerRuleBrowseRequests.updateOrderCallback
  		});
    },
    
    deleteRule: function(rule) {
    	var ruleId = parseInt(rule.attr("data-id"), 10);
      $.ajax({
        url: NeoX.Modules.LinkerRuleBrowseIndex.getConfig().moduleScript,
        method: 'POST',
        data: {
          type: NeoX.Modules.LinkerRuleBrowseIndex.getConfig().moduleType,
          action: 'delete',
          ruleId: ruleId
        },
        success: NeoX.Modules.LinkerRuleBrowseRequests.deleteRuleCallback(rule)
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    updateOrderCallback: function (data){
  		if(data.trim() != "success"){
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

Sky.Class.Define("NeoX.Modules.LinkerRuleBrowseRequests", MLinkerRuleBrowseRequests_Implementation);