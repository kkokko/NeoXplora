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
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterReviewIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterReviewIndex.getConfig().moduleType,
          'action': 'load',
          'page': page
        },
        success: NeoX.Modules.SplitterReviewRequests.loadCallback
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

    },
    
    dismissAll: function(protoIDs) {
      
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.SplitterReviewIndex.getConfig().dataContainer).html(json['data']);
    	$(NeoX.Modules.SplitterReviewIndex.getConfig().paginationContainer).html(json['pagination']);
    },
    
    revertCallback: function (protoID) {
    	return function(json) {
        $("#pr" + protoID).nextUntil(".aproto").fadeOut("slow");
    	};
    },
    
    revertCompletedCallback: function(protoID) {
    	return function(xhr) {
        var json = $.parseJSON(xhr.responseText);
        setTimeout(function() {
        	$("#pr" + protoID).removeClass("approved").removeClass("dismissed");
          $("#pr" + protoID).after(json['data']);
          $("#s" + json['sentenceID']).hide().fadeIn();
          $(".disabledRevertReviewSplitButton").attr('class', 'revertReviewSplitButton button');
        }, 600);
    	};
    },
    
    modifyCallback: function(sentenceID, newValue) {
    	return function(json) {
        if(json['error']) {
         alert(json['error']);
        } else {
        	$("#s" + sentenceID).prevAll(".aproto:first").removeClass("approved").removeClass("dismissed");
        	$("#s" + sentenceID).prevAll(".aproto:first").nextUntil(".aproto").each(function() {
            $(this).removeClass("dismissedSentence approvedSentence row1 row2");
        	});
        	
          $("#s" + sentenceID).fadeOut("slow");
          var protoRow = $("#s" + sentenceID).prevAll('.aproto').eq(0);
          //protoRow.find('td').animate({backgroundColor:'#ccc'}, 300);
          //find('td').animate({backgroundColor:'#fff'}, 300);
          protoRow.nextUntil(".aproto").each(function(i) {
            if(i%2 == 0) { 
              $(this).removeClass('row2');
              $(this).addClass('row1', 300);
              $(this).find('td').css('background-color', '');
            } else {
              $(this).removeClass('row1');
              $(this).addClass('row2', 300);
              $(this).find('td').css('background-color', '');
            }
          });
          setTimeout(function() {
            $("#s" + sentenceID).after(json['data']);
            $("#s" + sentenceID).remove();
            protoRow.nextUntil(".aproto").each(function(i) {
              if(i%2 == 0) { 
                $(this).removeClass('row2');
                $(this).addClass('row1', 300);
                $(this).find('td').css('background-color', '');
              } else {
                $(this).removeClass('row1');
                $(this).addClass('row2', 300);
                $(this).find('td').css('background-color', '');
              }
            });
          }, 600);
          
        }
    	};
    },
    
    approveCallback: function(protoID) {
      return function(json) {
        $("#pr" + protoID).nextUntil(".aproto").removeClass("dismissedSentence approvedSentence row1 row2").addClass("approvedSentence");
        $("#pr" + protoID).addClass("approved");
        setTimeout(function() {
          var flag = true;
          $(".aproto").each(function() {
            if(!$(this).hasClass('approved') || !$(this).hasClass('dismissed')) {
              flag = false;
            }
          });
          if(flag == true) {
            var page = parseInt($('.currentPage').html(), 10);
            if(!page) page = 1;
            loadReviewSplit(page);
          }
        }, 300);
      };
    },
    
    dismissCallback: function(protoID) {
      return function(json) {
      	
      	$("#pr" + protoID).nextUntil(".aproto").removeClass("dismissedSentence approvedSentence row1 row2").addClass("dismissedSentence");
        $("#pr" + protoID).addClass("dismissed");
        setTimeout(function() {
          var flag = true;
          $(".aproto").each(function() {
            if(!$(this).hasClass('approved') || !$(this).hasClass('dismissed')) {
              flag = false;
            }
          });
          if(flag == true) {
            var page = parseInt($('.currentPage').html(), 10);
            if(!page) page = 1;
            loadReviewSplit(page);
          }
        }, 300);
      };
    },
    
    approveAllCallback: function() {
      
    },
    
    dismissAllCallback: function() {
      
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.SplitterReviewRequests", MSplitterReviewRequests_Implementation);
