var MInterpreterReviewIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
  	this.base(this);
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
    	Buttons: {
    		goToPage: '.goToPage',
        approve: '.approveBtn',
        dismiss: '.dismissBtn',
        approveAll: '.approveAllBtn',
        dismissAll: '.dismissAllBtn'
    	},
      Inputs: {
      	newValue: '.newValue',
        asentence: '.areviewedsentence'
      },
      moduleScript: 'review.php',
      moduleType: 'interpreter',
      dataContainer: '.boxContent',
      paginationContainer: '.boxPagination'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.InterpreterReviewIndex.hookEvents();
      NeoX.Modules.InterpreterReviewIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.InterpreterReviewIndex.hookEvent('click', NeoX.Modules.InterpreterReviewIndex.getConfig().Buttons.goToPage, NeoX.Modules.InterpreterReviewIndex.goToPage);
    	NeoX.Modules.InterpreterReviewIndex.hookEvent('click', NeoX.Modules.InterpreterReviewIndex.getConfig().Buttons.approve, NeoX.Modules.InterpreterReviewIndex.approve);
    	NeoX.Modules.InterpreterReviewIndex.hookEvent('click', NeoX.Modules.InterpreterReviewIndex.getConfig().Buttons.dismiss, NeoX.Modules.InterpreterReviewIndex.dismiss);
    	NeoX.Modules.InterpreterReviewIndex.hookEvent('click', NeoX.Modules.InterpreterReviewIndex.getConfig().Buttons.approveAll, NeoX.Modules.InterpreterReviewIndex.approveAll);
    	NeoX.Modules.InterpreterReviewIndex.hookEvent('click', NeoX.Modules.InterpreterReviewIndex.getConfig().Buttons.dismissAll, NeoX.Modules.InterpreterReviewIndex.dismissAll);
    },
    
    load: function() {
    	NeoX.Modules.InterpreterReviewRequests.load(1);
    },
    
    goToPage: function() {
    	var page = parseInt($(this).html(), 10);
      NeoX.Modules.InterpreterReviewRequests.load(page);
      $('html, body').animate({
          scrollTop: $('#content').offset().top
      }, 50);
    },
    
    approve: function() {
    	var sentenceID = $(this).parent().parent().attr('id');
      sentenceID = parseInt(sentenceID.replace('s', ''), 10);
      var newValue = $(this).parent().parent().find(NeoX.Modules.InterpreterReviewIndex.getConfig().Inputs.newValue).val();
      NeoX.Modules.InterpreterReviewRequests.approve(sentenceID, newValue);
    },
    
    dismiss: function() {
    	var sentenceID = $(this).parent().parent().attr('id');
      sentenceID = parseInt(sentenceID.replace('s', ''), 10);
      NeoX.Modules.InterpreterReviewRequests.dismiss(sentenceID);
    },
    
    approveAll: function() {
    	var sentenceIDs = [];
    	var newValues = [];
      $(NeoX.Modules.InterpreterReviewIndex.getConfig().Inputs.asentence).each(function() {
        var sentenceID = $(this).attr('id');
        sentenceID = parseInt(sentenceID.replace('s', ''), 10);
        sentenceIDs.push(sentenceID);
        var newValue = $(this).find(NeoX.Modules.InterpreterReviewIndex.getConfig().Inputs.newValue).val();
        newValues.push(newValue);
      });
      NeoX.Modules.InterpreterReviewRequests.approveAll(sentenceIDs, newValues);
    },
    
    dismissAll: function() {
    	var sentenceIDs = [];
      $(NeoX.Modules.InterpreterReviewIndex.getConfig().Inputs.asentence).each(function() {
        var sentenceID = $(this).attr('id');
        sentenceID = parseInt(sentenceID.replace('s', ''), 10);
        sentenceIDs.push(sentenceID);
      });
      NeoX.Modules.InterpreterReviewRequests.dismissAll(sentenceIDs);
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.InterpreterReviewIndex", MInterpreterReviewIndex_Implementation);
