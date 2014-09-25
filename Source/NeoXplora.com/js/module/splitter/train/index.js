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
    	NeoX.Modules.SplitterTrainIndex.hookEvent('click', ".finish", NeoX.Modules.SplitterTrainIndex.finish);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', '.modifyReviewSplitButton', NeoX.Modules.SplitterTrainIndex.modify);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', ".skip", NeoX.Modules.SplitterTrainIndex.skip);
      NeoX.Modules.SplitterTrainIndex.hookEvent('click', '.revertReviewSplitButton', NeoX.Modules.SplitterTrainIndex.revert);
      NeoX.Modules.SplitterTrainIndex.hookEvent("change", "#categoryId", NeoX.Modules.SplitterTrainIndex.catChanged);
      $(document).ready(function() {
        $( window ).resize(function() {
          NeoX.Modules.SplitterTrainIndex.resizeInputs();
        });
      });
    },
    
    load: function() {
    	$(".trainer").addClass("loading");
    	NeoX.Modules.SplitterTrainRequests.load();
    },
    
    skip: function() {
      var protoID = $(this).parent().parent().data("id");
      
      NeoX.Modules.SplitterTrainRequests.skip(protoID);
    },
    
    revert: function () {
      $(this).attr('class', "disabledRevertReviewSplitButton button");
      var protoID = $(this).parent().parent().data('id');
      NeoX.Modules.SplitterTrainRequests.revert(protoID);
    },
    
    modify: function() {
      var sentenceID = $(this).parent().parent().data('id');
      var newValue = $(this).parent().parent().find('.newValue').val();
      NeoX.Modules.SplitterTrainRequests.modify(sentenceID, newValue);
    },
    
    resizeInputs: function() {
      $(".trainer tr").each(function() {
        $(this).find("td").first().find(".content-indent").width(0);
      });
      $(".trainer tr").each(function() {
        
        var cell = $(this).find("td").first();
        var totalWidth = cell.width();
        
        var indentWidth = cell.find(".level-indent-wrapper").width();
        var newWidth = totalWidth - indentWidth - 10;
        cell.find(".content-indent").width(newWidth);
      });
    },
    
    catChanged: function() {
      NeoX.Modules.SplitterTrainRequests.catChanged($(this).val());
    },
    
    finish: function() {
    	var protoID = $(this).parent().parent().data('id');
    	NeoX.Modules.SplitterTrainRequests.finish(protoID);
    }
  }
  
};

Sky.Class.Define("NeoX.Modules.SplitterTrainIndex", MSplitterTrainIndex_Implementation);
