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
    		goToPage: '.goToPage'
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
    }
    
    
    
  }
  
};

Sky.Class.Define("NeoX.Modules.SplitterBrowseIndex", MSplitterBrowseIndex_Implementation);
