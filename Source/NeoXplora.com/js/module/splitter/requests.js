var MSplitterRequests_Implementation = {
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
        url: NeoX.Modules.SplitterIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterIndex.getConfig().moduleType,
          'action': 'load'
        },
        success: NeoX.Modules.SplitterRequests.loadCallback
      });
    },
    
    skip: function(sentenceID) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterIndex.getConfig().moduleType,
          'action': 'skip',
          'sentenceID': sentenceID
        },
        success: NeoX.Modules.SplitterRequests.skipCallback
      });
    },
    
    split: function(sentenceID, newSplitValue, level) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterIndex.getConfig().moduleType,
          'action': 'split',
          'sentenceID': sentenceID,
          'newValue': newSplitValue,
          'level': level
        },
        success: NeoX.Modules.SplitterRequests.splitCallback(sentenceID, level)
      });
    },
      
    dontSplit: function(sentenceID) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterIndex.getConfig().moduleType,
          'action': 'dont_split',
          'sentenceID': sentenceID
        },
        success: NeoX.Modules.SplitterRequests.dontSplitCallback
      });
    },
    
    approve: function(sentenceIDs) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterIndex.getConfig().moduleType,
          'action': 'approve',
          'sentenceIDs': sentenceIDs
        },
        success: NeoX.Modules.SplitterRequests.approveCallback
      });
    },
    
    reset: function(sentenceID, originalValue, deleteSentences) {
    	$.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterIndex.getConfig().moduleType,
          'action': 'reset',
          'sentenceID': sentenceID,
          'originalValue': originalValue,
          'deleteSentences': deleteSentences
        },
        success: NeoX.Modules.SplitterRequests.resetCallback(sentenceID)
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.SplitterIndex.getConfig().dataContainer).html(json['data']);
      //$('.newRepValue').focus();
    },
    
    skipCallback: function() {
      NeoX.Modules.SplitterIndex.load();
    },
    
    splitCallback: function(sentenceID, level) {
    	return function(json) {
        if(json['error']) {
          alert(json['error']);
        } else {
          var row = $("input[value='" + sentenceID + "']").parent().parent();
          row.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.newValue).prop('disabled', true);
          row.find(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).css('display', 'none');
          row.find(NeoX.Modules.SplitterIndex.getConfig().Buttons.split).css('display', 'none');
          row.find('td:last-child').append(' <a href="javascript:void(0)" class="resetSplitButton button">Re-split</a>');
          if(level == 0) {
            $(NeoX.Modules.SplitterIndex.getConfig().Buttons.skip).html('Next');
            $(NeoX.Modules.SplitterIndex.getConfig().Buttons.skip).attr('class', 'nextSplitButton button');
            $(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).html('Done');
            $(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).css('display', 'inline-block');
            $(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).attr('class', 'nextSplitButton button');
          }
          if(json['newSentencesCount'] > 1) {
            row.after(json['data']);
          }
          if(json['newSentencesCount'] == 1 && json['level'] == 1) {
            NeoX.Modules.SplitterIndex.load();
          } else {
            $(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).each(function() {
            	//with the introduction of Order field, below line won't be needed
              //if(json['newSentenceIDs'][$(this).val()]) $(this).val(json['newSentenceIDs'][$(this).val()]); 
            }); 
          }
        }
    	}
    },
    
    dontSplitCallback: function(json) {
      
    },
    
    approveCallback: function(json) {
      NeoX.Modules.SplitterIndex.load();
      //$(".sentencestbl tr td").animate({backgroundColor:'#73C96D'}, 300);
    },
    
    resetCallback: function(sentenceID) {
    	return function(json) {
        $("input[value='" + sentenceID + "']").val(json['newSentenceID']);
    	};
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.SplitterRequests", MSplitterRequests_Implementation);
