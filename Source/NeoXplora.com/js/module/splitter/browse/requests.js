var MSplitterBrowseRequests_Implementation = {
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
        url: NeoX.Modules.SplitterBrowseIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterBrowseIndex.getConfig().moduleType,
          'action': 'load',
          'page': page
        },
        success: NeoX.Modules.SplitterBrowseRequests.loadCallback
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.SplitterBrowseIndex.getConfig().dataContainer).html(json['data']);
    	$(NeoX.Modules.SplitterBrowseIndex.getConfig().paginationContainer).html(json['pagination']);
    	if(json['pagination'] == "") {
        $(".buttons.smaller").hide();
    	} else {
    		$(".buttons.smaller").show();
    	}
    }
       
    
  }

};

Sky.Class.Define("NeoX.Modules.SplitterBrowseRequests", MSplitterBrowseRequests_Implementation);
