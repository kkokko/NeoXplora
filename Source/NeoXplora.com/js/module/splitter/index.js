var MSplitterIndex_Implementation = {
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
      NeoX.Modules.SplitterIndex.hookEvents();
      NeoX.Modules.SplitterIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.SplitterIndex.hookEvent('click', NeoX.Modules.SplitterIndex.getConfig().Buttons.skip, NeoX.Modules.SplitterIndex.skip);
      NeoX.Modules.SplitterIndex.hookEvent('click', NeoX.Modules.SplitterIndex.getConfig().Buttons.split, NeoX.Modules.SplitterIndex.split);
      NeoX.Modules.SplitterIndex.hookEvent('click', NeoX.Modules.SplitterIndex.getConfig().Buttons.next, NeoX.Modules.SplitterIndex.load);
      NeoX.Modules.SplitterIndex.hookEvent('click', NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit, NeoX.Modules.SplitterIndex.dontSplit);
      NeoX.Modules.SplitterIndex.hookEvent('click', NeoX.Modules.SplitterIndex.getConfig().Buttons.approve, NeoX.Modules.SplitterIndex.approve);
      NeoX.Modules.SplitterIndex.hookEvent('click', NeoX.Modules.SplitterIndex.getConfig().Buttons.reset, NeoX.Modules.SplitterIndex.reset);
      NeoX.Modules.SplitterIndex.hookEvent('keypress', NeoX.Modules.SplitterIndex.getConfig().Inputs.newValue, NeoX.Modules.SplitterIndex.splitKeyPress);
    },
    
    load: function() {
    	NeoX.Modules.SplitterRequests.load();
    },
    
    skip: function() {
      var sentenceID = $(this).parent().parent().find(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).val();
      
      NeoX.Modules.SplitterRequests.skip(sentenceID);
    },
    
    split: function() {
      var sentenceID = $(this).parent().parent().find(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).val();
      var newSplitValue = $(this).parent().parent().find(NeoX.Modules.SplitterIndex.getConfig().Inputs.newValue).val();
      var level = $(this).parent().parent().find(NeoX.Modules.SplitterIndex.getConfig().Inputs.level).val();
      
      NeoX.Modules.SplitterRequests.split(sentenceID, newSplitValue, level);
    },
      
    dontSplit: function() {
    	var parent = $(this).parent().parent();
      var sentenceID = parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).val();
      NeoX.Modules.SplitterRequests.dontSplit(sentenceID);
      if(parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.level).val() == 0) {
        NeoX.Modules.SplitterIndex.load();
      } else  {
        var row = $("input[value='" + sentenceID + "']").parent().parent();
        row.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.newValue).prop('disabled', true);
        row.find(NeoX.Modules.SplitterIndex.getConfig().Buttons.split).css('display', 'none');
        row.find(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).css('display', 'none');
        
        var count = 0;
        $(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).each(function () {
          if($(this).parent().parent().find(NeoX.Modules.SplitterIndex.getConfig().Buttons.split).css('display') != 'none') {
            count++;
          }
        });
        if(count == 0) {
          NeoX.Modules.SplitterIndex.load();
        }
      }
    },
    
    approve: function() {
    	var sentenceIDs = [];
      $(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).each(function() {
        sentenceIDs.push($(this).val());
      });
      NeoX.Modules.SplitterRequests.approve(sentenceIDs);
    },
    
    reset: function() {
    	var parent = $(this).parent().parent();
      var sentenceID = parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).val();
      var level = parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.level).val();
      var originalValue = parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.originalValue).val();
      var deleteSentences = [];
      parent.nextAll().find(NeoX.Modules.SplitterIndex.getConfig().Inputs.level).each(function() {
        if($(this).val() > level) {
          deleteSentences.push($(this).parent().find(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).val());
          $(this).parent().parent().remove();
        } else {
          return false;
        }
      });
      NeoX.Modules.SplitterRequests.reset(sentenceID, originalValue, deleteSentences);
      
      var row = $("input[value='" + sentenceID + "']").parent().parent();
      parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.newValue).prop('disabled', false);
      parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.newValue).val(originalValue);
      parent.find(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).html("No need");
      parent.find(NeoX.Modules.SplitterIndex.getConfig().Buttons.split).css('display', 'inline-block');
      parent.find(NeoX.Modules.SplitterIndex.getConfig().Buttons.dontSplit).css('display', 'inline-block');
      $(this).remove();
    },
    
    splitKeyPress: function(event) {
      if(event.which == 13) {
        var parent = $(this).parent().parent();
        var sentenceID = parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.sentenceID).val();
        var newSplitValue = parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.newValue).val();
        var level = parent.find(NeoX.Modules.SplitterIndex.getConfig().Inputs.level).val();
        NeoX.Modules.SplitterRequests.split(sentenceID, newSplitValue, level);
      }
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.SplitterIndex", MSplitterIndex_Implementation);
