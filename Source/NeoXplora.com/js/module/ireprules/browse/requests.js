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
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    updateOrderCallback: function (data){
		if(data.trim()!="success"){
			alert("Error updating Rules Order");
		}
	}
       
    
  }

};

Sky.Class.Define("NeoX.Modules.IRepRuleBrowseRequests", MIRepRuleBrowseRequests_Implementation);