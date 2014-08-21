var MLinkerBrowseRequests_Implementation = {
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
        url: NeoX.Modules.LinkerBrowseIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerBrowseIndex.getConfig().moduleType,
          'action': 'loadPage',
          'page': page
        },
        success: NeoX.Modules.LinkerBrowseRequests.loadCallback
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	if(json['data'].length == 0) {
        $(NeoX.Modules.LinkerBrowseIndex.getConfig().dataContainer).html("No pages trained.");
    	} else {
    		NeoX.Modules.LinkerBrowseIndex.getConfig().data = new Sky.TStringList();
    		NeoX.Modules.LinkerBrowseIndex.loadData(json['data']);
    		NeoX.Modules.LinkerBrowseIndex.repaint();
    	}
    	$(NeoX.Modules.LinkerBrowseIndex.getConfig().paginationContainer).html(json['pagination']);
      if(json['pagination'] == "") {
        $(".buttons.smaller").hide();
      } else {
        $(".buttons.smaller").show();
      }
    	
    }
  }

};

Sky.Class.Define("NeoX.Modules.LinkerBrowseRequests", MLinkerBrowseRequests_Implementation);
