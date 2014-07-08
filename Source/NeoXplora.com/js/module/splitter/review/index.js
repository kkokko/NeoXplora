var MSplitterReviewIndex_Implementation = {
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
        revert: '.revertReviewSplitButton',
        modify: '.modifyReviewSplitButton',
        approve: '.approveReviewSplitButton',
        dismiss: '.dismissReviewSplitButton',
        approveAll: '.approveAllSplitButton',
        dismissAll: 'dismissAllSplitButton'
    	},
      Inputs: {
      	newValue: '.newValue',
        aproto: '.aproto',
        asentence: '.asentence'
      },
      moduleScript: 'review.php',
      moduleType: 'splitter',
      dataContainer: '.boxContent',
      paginationContainer: '.boxPagination'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.SplitterReviewIndex.hookEvents();
      NeoX.Modules.SplitterReviewIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.SplitterReviewIndex.hookEvent('click', NeoX.Modules.SplitterReviewIndex.getConfig().Buttons.goToPage, NeoX.Modules.SplitterReviewIndex.goToPage);
    	NeoX.Modules.SplitterReviewIndex.hookEvent('click', NeoX.Modules.SplitterReviewIndex.getConfig().Buttons.revert, NeoX.Modules.SplitterReviewIndex.revert);
    	NeoX.Modules.SplitterReviewIndex.hookEvent('click', NeoX.Modules.SplitterReviewIndex.getConfig().Buttons.modify, NeoX.Modules.SplitterReviewIndex.modify);
    	NeoX.Modules.SplitterReviewIndex.hookEvent('click', NeoX.Modules.SplitterReviewIndex.getConfig().Buttons.approve, NeoX.Modules.SplitterReviewIndex.approve);
    	NeoX.Modules.SplitterReviewIndex.hookEvent('click', NeoX.Modules.SplitterReviewIndex.getConfig().Buttons.dismiss, NeoX.Modules.SplitterReviewIndex.dismiss);
    	NeoX.Modules.SplitterReviewIndex.hookEvent('click', NeoX.Modules.SplitterReviewIndex.getConfig().Buttons.approveAll, NeoX.Modules.SplitterReviewIndex.approveAll);
    	NeoX.Modules.SplitterReviewIndex.hookEvent('click', NeoX.Modules.SplitterReviewIndex.getConfig().Buttons.dismissAll, NeoX.Modules.SplitterReviewIndex.dismissAll);
    },
    
    load: function() {
    	NeoX.Modules.SplitterReviewRequests.load(1);
    },
    
    goToPage: function() {
    	var page = parseInt($(this).html(), 10);
      NeoX.Modules.SplitterReviewRequests.load(page);
      $('html, body').animate({
          scrollTop: $('#content').offset().top
      }, 50);
    },
    
    revert: function () {
      $(this).attr('class', "disabledRevertReviewSplitButton button");
      var protoID = $(this).parent().parent().attr('id');
      protoID = parseInt(protoID.replace('pr', ''), 10);
      NeoX.Modules.SplitterReviewRequests.revert(protoID);
    },
    
    modify: function() {
    	var sentenceID = $(this).parent().parent().attr('id');
      sentenceID = parseInt(sentenceID.replace('s', ''), 10);
      var newValue = $(this).parent().parent().find('.newValue').val();
      NeoX.Modules.SplitterReviewRequests.modify(sentenceID, newValue);
    },
    
    approve: function() {
    	var protoID = $(this).parent().parent().attr('id');
      protoID = parseInt(protoID.replace('pr', ''), 10);
      NeoX.Modules.SplitterReviewRequests.approve(protoID);
    },
    
    dismiss: function() {
    	var protoID = $(this).parent().parent().attr('id');
      protoID = parseInt(protoID.replace('pr', ''), 10);
      NeoX.Modules.SplitterReviewRequests.dismiss(protoID);
    },
    
    approveAll: function() {
    	var protoIDs = [];
      $('.aproto').each(function() {
        var protoID = $(this).attr('id');
        protoID = parseInt(protoID.replace('pr', ''), 10);
        protoIDs.push(protoID);
      });
      NeoX.Modules.SplitterReviewRequests.approveAll(protoIDs);
    },
    
    dismissAll: function() {
    	var protoIDs = [];
      $('.aproto').each(function() {
        var protoID = $(this).attr('id');
        protoID = parseInt(protoID.replace('pr', ''), 10);
        protoIDs.push(protoID);
      });
      NeoX.Modules.SplitterReviewRequests.dismissAll(protoIDs);
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.SplitterReviewIndex", MSplitterReviewIndex_Implementation);
