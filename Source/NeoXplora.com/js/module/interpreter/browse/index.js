var MInterpreterBrowseIndex_Implementation = {
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
        firstPage: '.firstBtn',
        previousPage: '.previousBtn',
        nextPage: '.nextBtn',
        lastPage: '.lastBtn'  
    	},
      Inputs: {
      	newValue: '.newValue',
        aproto: '.aproto',
        asentence: '.asentence'
      },
      moduleScript: 'browse.php',
      moduleType: 'interpreter',
      dataContainer: '.boxContent',
      paginationContainer: '.boxPagination'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.InterpreterBrowseIndex.hookEvents();
      NeoX.Modules.InterpreterBrowseIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.InterpreterBrowseIndex.hookEvent('click', NeoX.Modules.InterpreterBrowseIndex.getConfig().Buttons.goToPage, NeoX.Modules.InterpreterBrowseIndex.goToPage);
    	NeoX.Modules.InterpreterBrowseIndex.hookEvent('click', NeoX.Modules.InterpreterBrowseIndex.getConfig().Buttons.firstPage, NeoX.Modules.InterpreterBrowseIndex.goToFirst);
      NeoX.Modules.InterpreterBrowseIndex.hookEvent('click', NeoX.Modules.InterpreterBrowseIndex.getConfig().Buttons.previousPage, NeoX.Modules.InterpreterBrowseIndex.goToPrevious);
      NeoX.Modules.InterpreterBrowseIndex.hookEvent('click', NeoX.Modules.InterpreterBrowseIndex.getConfig().Buttons.nextPage, NeoX.Modules.InterpreterBrowseIndex.goToNext);
      NeoX.Modules.InterpreterBrowseIndex.hookEvent('click', NeoX.Modules.InterpreterBrowseIndex.getConfig().Buttons.lastPage, NeoX.Modules.InterpreterBrowseIndex.goToLast);
    },
    
    load: function() {
    	NeoX.Modules.InterpreterBrowseRequests.load(1);
    },
    
    goToPage: function() {
    	var page = parseInt($(this).html(), 10);
      NeoX.Modules.InterpreterBrowseRequests.load(page);
      $('html, body').animate({
          scrollTop: $('#content').offset().top
      }, 50);
    },
    
    goToFirst: function() {
      NeoX.Modules.InterpreterBrowseRequests.load(1);
    },
    
    goToPrevious: function() {
      var previousPage = parseInt($(".currentPage").html(), 10) - 1;
      if(previousPage > 0) {
        NeoX.Modules.InterpreterBrowseRequests.load(previousPage);
      }
    },
    
    goToNext: function() {
      var nextPage = parseInt($(".currentPage").html(), 10) + 1;
      var lastPage = parseInt($(".goToPage").last().html());
      if(nextPage <= lastPage) {
        NeoX.Modules.InterpreterBrowseRequests.load(nextPage);
      }
    },
    
    goToLast: function() {
      var lastPage = parseInt($(".goToPage").last().html());
      NeoX.Modules.InterpreterBrowseRequests.load(lastPage);
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.InterpreterBrowseIndex", MInterpreterBrowseIndex_Implementation);
