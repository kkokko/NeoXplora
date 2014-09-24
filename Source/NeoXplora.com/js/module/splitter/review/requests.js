var MSplitterReviewRequests_Implementation = {
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
    
    load: function(page) {
    	var pageId = NeoX.Modules.SplitterReviewIndex.getParameterByName("pageId");

      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'load',
          'page': page,
          'pageId': pageId
        },
        success: NeoX.Modules.SplitterReviewRequests.loadCallback
      });
    },
    
    createProto: function(protoId, sentenceList) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'createProto',
          'protoId': protoId,
          'sentences': sentenceList
        },
        success: NeoX.Modules.SplitterReviewRequests.createProtoCallback,
        error: function() {
          $(".trainer").removeClass("loading");
        }
      });
    },
    
    revert: function (protoID) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'revert',
          'protoID': protoID
        },
        success: NeoX.Modules.SplitterReviewRequests.revertCallback(protoID),
        complete: NeoX.Modules.SplitterReviewRequests.revertCompletedCallback(protoID)
      });
    },
    
    modify: function(sentenceID, newValue) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'modify',
          'sentenceID': sentenceID,
          'newValue': newValue
        },
        success: NeoX.Modules.SplitterReviewRequests.modifyCallback(sentenceID, newValue)
      });
    },
    
    editProto: function(protoID, newVal) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'editProto',
          'protoID': protoID,
          'newValue': newVal
        },
        success: NeoX.Modules.SplitterReviewRequests.editProtoCallback
      });
    },
    
    approve: function(protoID) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'approve',
          'protoID': protoID
        },
        success: NeoX.Modules.SplitterReviewRequests.approveCallback(protoID)
      });
    },
    
    dismiss: function(protoID) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'dismiss',
          'protoID': protoID
        },
        success: NeoX.Modules.SplitterReviewRequests.dismissCallback(protoID)
      });
    },
    
    approveAll: function(protoIDs) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'approveMultiple',
          'protoIDs': protoIDs
        },
        success: NeoX.Modules.SplitterReviewRequests.approveAllCallback
      });
    },
    
    dismissAll: function(protoIDs) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'dismissMultiple',
          'protoIDs': protoIDs
        },
        success: NeoX.Modules.SplitterReviewRequests.dismissAllCallback
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    reload: function(json) {
    	var page = parseInt($('.currentPage').html(), 10);
      if(!page) page = 1;
      NeoX.Modules.SplitterReviewRequests.load(page);
    },
    
    loadCallback: function(json) {
    	$(NeoX.Modules.SplitterReviewIndex.getConfig().dataContainer).html(json['data']);
    	$(NeoX.Modules.SplitterReviewIndex.getConfig().paginationContainer).html(json['pagination']);
    	$(".trainer").removeClass("loading");
    	
    	$(".trainer tr").each(function() {
    		
    		var cell = $(this).find("td").first();
        var totalWidth = cell.width();
        
        var indentWidth = cell.find(".level-indent-wrapper").width();
        var newWidth = totalWidth - indentWidth - 10;
        cell.find(".content-indent").width(newWidth);
    	});
    },
    
    createProtoCallback: function() {
      NeoX.Modules.SplitterReviewRequests.reload();
    },
    
    revertCallback: function (protoID) {
    	return function(json) {
        NeoX.Modules.SplitterReviewRequests.reload();
    	};
    },
    
    revertCompletedCallback: function(protoID) {
    	return function(json) {
        NeoX.Modules.SplitterReviewRequests.reload();
    	};
    },
    
    modifyCallback: function(sentenceID, newValue) {
    	return function(json) {
    		if(json['exception']) {
          $(".boxContent").prepend('<h3 style="color:red; text-align: center; padding: 5px;">Error: ' + json['exception'] + '</h3>');
        } else if(json['error']) {
          alert(json['error']);
        } else {
        	NeoX.Modules.SplitterReviewRequests.reload();
        }
    	};
    },
    
    approveCallback: function(protoID) {
      return function(json) {
        NeoX.Modules.SplitterReviewRequests.reload();
      };
    },
    
    dismissCallback: function(protoID) {
      return function(json) {
      	NeoX.Modules.SplitterReviewRequests.reload();
      };
    },
    
    approveAllCallback: function(json) {
      NeoX.Modules.SplitterReviewRequests.reload();
    },
    
    dismissAllCallback: function() {
      NeoX.Modules.SplitterReviewRequests.reload();
    },
    
    editProtoCallback: function() {
    	NeoX.Modules.SplitterReviewRequests.reload();
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.SplitterReviewRequests", MSplitterReviewRequests_Implementation);
