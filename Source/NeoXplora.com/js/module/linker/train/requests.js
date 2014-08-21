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
    
    save: function(data) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'save',
          'data': data
        },
        success: NeoX.Modules.LinkerTrainRequests.saveCallback
      });
    },
    
    finish: function(data) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'finish',
          'data': data
        },
        success: NeoX.Modules.LinkerTrainRequests.finishCallback
      });
    },
    
    skip: function(skip) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'skip'
        },
        success: NeoX.Modules.LinkerTrainRequests.skipCallback
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	if(json.length == 0) {
        $(NeoX.Modules.LinkerTrainIndex.getConfig().dataContainer).html("No pages available for training.");
    	} else {
    		NeoX.Modules.LinkerTrainIndex.getConfig().data = new Sky.TStringList();
    		NeoX.Modules.LinkerTrainIndex.loadData(json);
    		NeoX.Modules.LinkerTrainIndex.repaint();
    	}
    },
    
    saveCallback: function(json) {
    	//
    },
    
    finishCallback: function() {
    	NeoX.Modules.LinkerTrainRequests.load();
    },
    
    skipCallback: function() {
      NeoX.Modules.LinkerTrainRequests.load();
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.LinkerTrainRequests", MLinkerTrainRequests_Implementation);
