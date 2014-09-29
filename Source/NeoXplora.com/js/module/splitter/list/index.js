var MSplitterListIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
  	this.base(this);
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
    	Buttons: {
    		goToPage: '.goToPage'
    	},
      moduleScript: 'list.php',
      moduleType: 'splitter',
      dataContainer: '.boxContent',
      paginationContainer: '.boxPagination'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.SplitterListIndex.hookEvents();
      NeoX.Modules.SplitterListIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.SplitterListIndex.hookEvent('click', NeoX.Modules.SplitterListIndex.getConfig().Buttons.goToPage, NeoX.Modules.SplitterListIndex.goToPage);
    	NeoX.Modules.SplitterListIndex.hookEvent('click', "#dofilter", NeoX.Modules.SplitterListIndex.filter_click);
    },
    
    load: function() {
    	NeoX.Modules.SplitterListRequests.load(1);
    },
    
    goToPage: function() {
    	var page = parseInt($(this).html(), 10);
      NeoX.Modules.SplitterListRequests.load(page);
      $('html, body').animate({
          scrollTop: $('#content').offset().top
      }, 50);
    },
    
    filter_click: function() {
    	NeoX.Modules.SplitterListRequests.load(1);
    }
        
  }
  
};

Sky.Class.Define("NeoX.Modules.SplitterListIndex", MSplitterListIndex_Implementation);
