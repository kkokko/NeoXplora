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
    	var pageId = NeoX.Modules.LinkerTrainIndex.getParameterByName("pageId");
    	
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'loadPage',
          'pageId': pageId
        },
        success: NeoX.Modules.LinkerTrainRequests.loadCallback
      });
    },
    
    save: function(data) {
    	var pageId = NeoX.Modules.LinkerTrainIndex.getParameterByName("pageId");
    	
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'save',
          'data': data,
          'pageId': pageId
        },
        success: NeoX.Modules.LinkerTrainRequests.saveCallback
      });
    },
    
    catChanged: function(categoryId) {
    	var pageId = NeoX.Modules.LinkerTrainIndex.getParameterByName("pageId");
    	
    	if(!pageId) {
      	$.ajax({
          type: "POST",
          url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
          dataType: 'json',
          data: {
            'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
            'action': 'catChanged',
            'categoryId': categoryId
          },
          success: NeoX.Modules.LinkerTrainRequests.load
        });
      }
    },
    
    finish: function(data) {
    	var pageId = NeoX.Modules.LinkerTrainIndex.getParameterByName("pageId");
    	
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'finish',
          'data': data,
          'pageId': pageId
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
    	if(json['data'].length == 0) {
        $(NeoX.Modules.LinkerTrainIndex.getConfig().dataContainer).html("No pages available for training.");
    	} else {
    		NeoX.Modules.LinkerTrainIndex.getConfig().data = new Sky.TStringList();
    		NeoX.Modules.LinkerTrainIndex.loadData(json['data']);
    		NeoX.Modules.LinkerTrainIndex.repaint();
    		$(".storyTitle").html(json['pageTitle']);
    		
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
