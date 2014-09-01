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
    
    skip: function(sentenceID) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'skip',
          'sentenceID': sentenceID
        },
        success: NeoX.Modules.SplitterTrainRequests.skipCallback
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
        success: NeoX.Modules.SplitterTrainIndex.load
      });
    },
    
    split: function(sentenceID, newSplitValue, level, approved) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'split',
          'sentenceID': sentenceID,
          'newValue': newSplitValue,
          'level': level,
          'approved': approved
        },
        success: NeoX.Modules.SplitterTrainRequests.splitCallback(sentenceID, level)
      });
    },
      
    dontSplit: function(sentenceID) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'dont_split',
          'sentenceID': sentenceID
        },
        success: NeoX.Modules.SplitterTrainRequests.dontSplitCallback
      });
    },
    
    approve: function(sentenceIDs) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'approve',
          'sentenceIDs': sentenceIDs
        },
        success: NeoX.Modules.SplitterTrainRequests.approveCallback
      });
    },
    
    reset: function(sentenceID, originalValue, deleteSentences) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterTrainIndex.getConfig().moduleType,
          'action': 'reset',
          'sentenceID': sentenceID,
          'originalValue': originalValue,
          'deleteSentences': deleteSentences
        },
        success: NeoX.Modules.SplitterTrainRequests.resetCallback(sentenceID)
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.SplitterTrainIndex.getConfig().dataContainer).html(json['data']);
    	NeoX.Modules.SplitterTrainIndex.splitValChangedInit();
      //$('.newRepValue').focus();
    },
    
    skipCallback: function() {
      NeoX.Modules.SplitterTrainIndex.load();
    },
    
    splitCallback: function(sentenceID, level) {
    	return function(json) {
        if(json['error']) {
          alert(json['error']);
        } else {
          var row = $("input[value='" + sentenceID + "']").parent().parent();
          row.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.newValue).prop('disabled', true);
          row.find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).css('display', 'none');
          row.find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.split).css('display', 'none');
          row.find('td:last-child').append(' <a href="javascript:void(0)" class="resetSplitButton button">Re-split</a>');
          if(level == 0) {
            $(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.skip).html('Next');
            $(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.skip).attr('class', 'nextSplitButton button');
            $(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).html('Done');
            $(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).css('display', 'inline-block');
            $(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).attr('class', 'nextSplitButton button');
          }
          if(json['newSentencesCount'] > 1) {
            row.after(json['data']);
          }
          if(json['newSentencesCount'] == 1 && json['level'] == 1) {
            NeoX.Modules.SplitterTrainIndex.load();
          } else {
            $(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).each(function() {
            	//with the introduction of Order field, below line won't be needed
              //if(json['newSentenceIDs'][$(this).val()]) $(this).val(json['newSentenceIDs'][$(this).val()]); 
            }); 
          }
          
          NeoX.Modules.SplitterTrainIndex.splitValChangedInit();
        }
    	};
    },
    
    dontSplitCallback: function(json) {
      
    },
    
    approveCallback: function(json) {
      NeoX.Modules.SplitterTrainIndex.load();
      //$(".sentencestbl tr td").animate({backgroundColor:'#73C96D'}, 300);
    },
    
    resetCallback: function(sentenceID) {
    	return function(json) {
        $("input[value='" + sentenceID + "']").val(json['newSentenceID']);
    	};
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.SplitterTrainRequests", MSplitterTrainRequests_Implementation);
