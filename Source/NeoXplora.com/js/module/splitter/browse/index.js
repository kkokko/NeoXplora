var MSplitterBrowseIndex_Implementation = {
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
      moduleType: 'splitter',
      dataContainer: '.boxContent',
      paginationContainer: '.boxPagination'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.SplitterBrowseIndex.hookEvents();
      NeoX.Modules.SplitterBrowseIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.SplitterBrowseIndex.hookEvent('click', NeoX.Modules.SplitterBrowseIndex.getConfig().Buttons.goToPage, NeoX.Modules.SplitterBrowseIndex.goToPage);
    	NeoX.Modules.SplitterBrowseIndex.hookEvent('click', NeoX.Modules.SplitterBrowseIndex.getConfig().Buttons.firstPage, NeoX.Modules.SplitterBrowseIndex.goToFirst);
    	NeoX.Modules.SplitterBrowseIndex.hookEvent('click', NeoX.Modules.SplitterBrowseIndex.getConfig().Buttons.previousPage, NeoX.Modules.SplitterBrowseIndex.goToPrevious);
    	NeoX.Modules.SplitterBrowseIndex.hookEvent('click', NeoX.Modules.SplitterBrowseIndex.getConfig().Buttons.nextPage, NeoX.Modules.SplitterBrowseIndex.goToNext);
    	NeoX.Modules.SplitterBrowseIndex.hookEvent('click', NeoX.Modules.SplitterBrowseIndex.getConfig().Buttons.lastPage, NeoX.Modules.SplitterBrowseIndex.goToLast);
    },
    
    load: function() {
    	NeoX.Modules.SplitterBrowseRequests.load(1);
    },
    
    goToPage: function() {
    	var page = parseInt($(this).html(), 10);
      NeoX.Modules.SplitterBrowseRequests.load(page);
      $('html, body').animate({
          scrollTop: $('#content').offset().top
      }, 50);
    },
    
    goToFirst: function() {
      NeoX.Modules.SplitterBrowseRequests.load(1);
    },
    
    goToPrevious: function() {
    	var previousPage = parseInt($(".currentPage").html(), 10) - 1;
    	if(previousPage > 0) {
        NeoX.Modules.SplitterBrowseRequests.load(previousPage);
    	}
    },
    
    goToNext: function() {
      var nextPage = parseInt($(".currentPage").html(), 10) + 1;
      var lastPage = parseInt($(".goToPage").last().html());
      if(nextPage <= lastPage) {
        NeoX.Modules.SplitterBrowseRequests.load(nextPage);
      }
    },
    
    goToLast: function() {
      var lastPage = parseInt($(".goToPage").last().html());
      NeoX.Modules.SplitterBrowseRequests.load(lastPage);
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.SplitterBrowseIndex", MSplitterBrowseIndex_Implementation);
