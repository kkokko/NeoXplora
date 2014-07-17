var MInterpreterReviewRequests_Implementation = {
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
      $.ajax({
        type: "POST",
        url: NeoX.Modules.InterpreterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.InterpreterReviewIndex.getConfig().moduleType,
          'action': 'load',
          'page': page
        },
        success: NeoX.Modules.InterpreterReviewRequests.loadCallback
      });
    },
    
    approve: function(sentenceID, newValue) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.InterpreterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.InterpreterReviewIndex.getConfig().moduleType,
          'action': 'approve',
          'sentenceID': sentenceID,
          'newValue': newValue
        },
        success: NeoX.Modules.InterpreterReviewRequests.approveCallback(sentenceID, newValue)
      });
    },
    
    dismiss: function(sentenceID) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.InterpreterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.InterpreterReviewIndex.getConfig().moduleType,
          'action': 'dismiss',
          'sentenceID': sentenceID
        },
        success: NeoX.Modules.InterpreterReviewRequests.dismissCallback(sentenceID)
      });
    },
    
    approveAll: function(sentenceIDs, newValues) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.InterpreterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.InterpreterReviewIndex.getConfig().moduleType,
          'action': 'approveMultiple',
          'sentenceIDs': sentenceIDs,
          'newValues': newValues
        },
        success: NeoX.Modules.InterpreterReviewRequests.approveAllCallback
      });
    },
    
    dismissAll: function(sentenceIDs) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.InterpreterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.InterpreterReviewIndex.getConfig().moduleType,
          'action': 'dismissMultiple',
          'sentenceIDs': sentenceIDs
        },
        success: NeoX.Modules.InterpreterReviewRequests.dismissAllCallback
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.InterpreterReviewIndex.getConfig().dataContainer).html(json['data']);
    	$(NeoX.Modules.InterpreterReviewIndex.getConfig().paginationContainer).html(json['pagination']);
    },
    
    approveCallback: function(sentenceID, newValue) {
      return function(json) {
      	if(json && json['StrIndex']) {
        	var near = newValue.substr(json['StrIndex'], newValue.length);
          $("#s" + sentenceID).find('.rep-error').remove();
          $("#s" + sentenceID).find('.newValue').after("<div class='rep-error' style='color: red'><br/>" + json['ErrorString'] + " at \"" + near + "\"</div>");
      	} else {
          $("#s" + sentenceID).removeClass("dismissedSentence approvedSentence row1 row2").addClass("approvedSentence");
          setTimeout(function() {
            var flag = true;
            $(".areviewedsentence").each(function() {
              if(!$(this).hasClass('approved') || !$(this).hasClass('dismissed')) {
                flag = false;
              }
            });
            if(flag == true) {
              var page = parseInt($('.currentPage').html(), 10);
              if(!page) page = 1;
              NeoX.Modules.InterpreterReviewRequests.load(page);
            }
          }, 300);
      	}
      };
    },
    
    dismissCallback: function(sentenceID) {
      return function(json) {
      	$("#s" + sentenceID).removeClass("dismissedSentence approvedSentence row1 row2").addClass("dismissedSentence");
      	$("#s" + sentenceID).find('.rep-error').remove();
        setTimeout(function() {
          var flag = true;
          $(".areviewedsentence").each(function() {
            if(!$(this).hasClass('approved') || !$(this).hasClass('dismissed')) {
              flag = false;
            }
          });
          if(flag == true) {
            var page = parseInt($('.currentPage').html(), 10);
            if(!page) page = 1;
            NeoX.Modules.InterpreterReviewRequests.load(page);
          }
        }, 300);
      };
    },
    
    approveAllCallback: function(json) {
    	if(json['flag'] == false) {
        var page = parseInt($('.currentPage').html(), 10);
        if(!page) page = 1;
        NeoX.Modules.InterpreterReviewRequests.load(page);
      } else {
      	var response = json['sentences'];
      	for(var k in response) {
      		console.log(response[k]);
          if(response[k] instanceof Object) {
            $("#s" + k).find('.rep-error').remove();
          	$("#s" + k).find('.newValue').after("<div class='rep-error' style='color: red'><br/>" + response[k]['ErrorString'] + " at \"" + response[k]['StrIndex'] + "\"</div>");
          } else {
            $("#s" + k).removeClass("dismissedSentence approvedSentence row1 row2").addClass("approvedSentence");
          }
      	}
      }
    },
    
    dismissAllCallback: function() {
      var page = parseInt($('.currentPage').html(), 10);
      if(!page) page = 1;
      NeoX.Modules.InterpreterReviewRequests.load(page);
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.InterpreterReviewRequests", MInterpreterReviewRequests_Implementation);
