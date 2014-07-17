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
    		goToPage: '.goToPage'
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
    }
    
    
    
  }
  
};

Sky.Class.Define("NeoX.Modules.InterpreterBrowseIndex", MInterpreterBrowseIndex_Implementation);
