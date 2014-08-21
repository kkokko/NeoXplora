var MSplitterTrainIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
  	this.base(this);
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
    	Buttons: {
    		skip: '.skipSplitButton',
        split: '.doneSplitButton',
        next: '.nextSplitButton',
        dontSplit: '.doneNoSplitButton',
        approve: '.approveSplitButton',
        reset: '.resetSplitButton'
    	},
      Inputs: {
      	newValue: '.newSplitValue',
        sentenceID: '.sentenceID',
        level: '.level',
        originalValue: ".originalValue"
      },
      moduleScript: 'train.php',
      moduleType: 'splitter',
      dataContainer: '.boxContent'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.SplitterTrainIndex.hookEvents();
      NeoX.Modules.SplitterTrainIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.SplitterTrainIndex.hookEvent('click', NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.skip, NeoX.Modules.SplitterTrainIndex.skip);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.split, NeoX.Modules.SplitterTrainIndex.split);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.next, NeoX.Modules.SplitterTrainIndex.load);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit, NeoX.Modules.SplitterTrainIndex.dontSplit);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.approve, NeoX.Modules.SplitterTrainIndex.approve);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.reset, NeoX.Modules.SplitterTrainIndex.reset);
      NeoX.Modules.SplitterTrainIndex.hookEvent('keypress', NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.newValue, NeoX.Modules.SplitterTrainIndex.splitKeyPress);
    },
    
    load: function() {
    	NeoX.Modules.SplitterTrainRequests.load();
    },
    
    skip: function() {
      var sentenceID = $(this).parent().parent().find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).val();
      
      NeoX.Modules.SplitterTrainRequests.skip(sentenceID);
    },
    
    split: function() {
      var sentenceID = $(this).parent().parent().find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).val();
      var newSplitValue = $(this).parent().parent().find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.newValue).val();
      var level = $(this).parent().parent().find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.level).val();
      var approved = ($(".checkApproved").length > 0) && $(".checkApproved").is(":checked");
      NeoX.Modules.SplitterTrainRequests.split(sentenceID, newSplitValue, level, approved);
    },
      
    dontSplit: function() {
    	var parent = $(this).parent().parent();
      var sentenceID = parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).val();
      NeoX.Modules.SplitterTrainRequests.dontSplit(sentenceID);
      if(parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.level).val() == 0) {
        NeoX.Modules.SplitterTrainIndex.load();
      } else  {
        var row = $("input[value='" + sentenceID + "']").parent().parent();
        row.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.newValue).prop('disabled', true);
        row.find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.split).css('display', 'none');
        row.find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).css('display', 'none');
        
        var count = 0;
        $(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).each(function () {
          if($(this).parent().parent().find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.split).css('display') != 'none') {
            count++;
          }
        });
        if(count == 0) {
          NeoX.Modules.SplitterTrainIndex.load();
        }
      }
    },
    
    approve: function() {
    	var sentenceIDs = [];
      $(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).each(function() {
        sentenceIDs.push($(this).val());
      });
      NeoX.Modules.SplitterTrainRequests.approve(sentenceIDs);
    },
    
    reset: function() {
    	var parent = $(this).parent().parent();
      var sentenceID = parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).val();
      var level = parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.level).val();
      var originalValue = parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.originalValue).val();
      var deleteSentences = [];
      parent.nextAll().find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.level).each(function() {
        if($(this).val() > level) {
          deleteSentences.push($(this).parent().find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).val());
          $(this).parent().parent().remove();
        } else {
          return false;
        }
      });
      NeoX.Modules.SplitterTrainRequests.reset(sentenceID, originalValue, deleteSentences);
      
      var row = $("input[value='" + sentenceID + "']").parent().parent();
      parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.newValue).prop('disabled', false);
      parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.newValue).val(originalValue);
      parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).html("No need");
      parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.split).css('display', 'inline-block');
      parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Buttons.dontSplit).css('display', 'inline-block');
      $(this).remove();
    },
    
    splitKeyPress: function(event) {
      if(event.which == 13) {
        var parent = $(this).parent().parent();
        var sentenceID = parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.sentenceID).val();
        var newSplitValue = parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.newValue).val();
        var level = parent.find(NeoX.Modules.SplitterTrainIndex.getConfig().Inputs.level).val();
        NeoX.Modules.SplitterTrainRequests.split(sentenceID, newSplitValue, level);
      }
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.SplitterTrainIndex", MSplitterTrainIndex_Implementation);
