var MLinkerTrainRequests_Implementation = {
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
    
    load: function() {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'loadPage'
        },
        success: NeoX.Modules.LinkerTrainRequests.loadCallback
      });
    },
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	if(json.length == 0) {
        $(NeoX.Modules.LinkerTrainIndex.getConfig().dataContainer).html("No pages available for training.");
    	} else {
    		NeoX.Modules.LinkerTrainIndex.loadData(json);
    		NeoX.Modules.LinkerTrainIndex.repaint();
    	}
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.LinkerTrainRequests", MLinkerTrainRequests_Implementation);
