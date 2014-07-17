var MInterpreterBrowseRequests_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  construct: function() {
  	this.base(this);
  },
  
  methods: {
    
    init: function() {
      //nothing to init
    },
        
    /*
     * AJAX REQUESTS
     */
    
    load: function(page) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.InterpreterBrowseIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.InterpreterBrowseIndex.getConfig().moduleType,
          'action': 'load',
          'page': page
        },
        success: NeoX.Modules.InterpreterBrowseRequests.loadCallback
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.InterpreterBrowseIndex.getConfig().dataContainer).html(json['data']);
    	$(NeoX.Modules.InterpreterBrowseIndex.getConfig().paginationContainer).html(json['pagination']);
    }
       
    
  }

};

Sky.Class.Define("NeoX.Modules.InterpreterBrowseRequests", MInterpreterBrowseRequests_Implementation);
