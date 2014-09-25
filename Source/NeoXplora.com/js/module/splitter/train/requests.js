var MSplitterTrainRequests_Implementation = {
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
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'load'
        },
        success: NeoX.Modules.SplitterTrainRequests.loadCallback
      });
    },
    
    skip: function(protoID) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'skip',
          'protoID': protoID
        },
        success: NeoX.Modules.SplitterTrainRequests.skipCallback
      });
    },
    
    finish: function(protoID) {
    	var approve = ($(".checkApproved:checked").length)?true:false;
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'finish',
          'approved': approve,
          'protoID': protoID
        },
        success: NeoX.Modules.SplitterTrainRequests.finishCallback
      });
    },
    
    catChanged: function(categoryId) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'catChanged',
          'categoryId': categoryId
        },
        success: NeoX.Modules.SplitterTrainRequests.load
      });
    },
    
    revert: function (protoID) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'revert',
          'protoID': protoID
        },
        success: NeoX.Modules.SplitterTrainRequests.revertCallback(protoID),
        complete: NeoX.Modules.SplitterTrainRequests.revertCompletedCallback(protoID)
      });
    },
    
    modify: function(sentenceID, newValue) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'modify',
          'sentenceID': sentenceID,
          'newValue': newValue
        },
        success: NeoX.Modules.SplitterTrainRequests.modifyCallback(sentenceID, newValue)
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.SplitterTrainIndex.getConfig().dataContainer).html(json['data']);
    	$(".storyTitle").html(json['pageTitle']);
    	$(".trainer").removeClass("loading");
      
      NeoX.Modules.SplitterTrainIndex.resizeInputs();
    },
    
    skipCallback: function() {
      NeoX.Modules.SplitterTrainIndex.load();
    },
    
    revertCallback: function (protoID) {
      return function(json) {
        NeoX.Modules.SplitterTrainRequests.load();
      };
    },
    
    revertCompletedCallback: function(protoID) {
      return function(json) {
        NeoX.Modules.SplitterTrainRequests.load();
      };
    },
    
    modifyCallback: function(sentenceID, newValue) {
      return function(json) {
        if(json['exception']) {
          $(".boxContent").prepend('<h3 style="color:red; text-align: center; padding: 5px;">Error: ' + json['exception'] + '</h3>');
        } else if(json['error']) {
          alert(json['error']);
        } else {
          NeoX.Modules.SplitterTrainRequests.load();
        }
      };
    },
    
    finishCallback: function(json) {
      NeoX.Modules.SplitterTrainIndex.load();
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.SplitterTrainRequests", MSplitterTrainRequests_Implementation);
