var MAPIXMLRequests_Implementation = {
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
    
    sendReq: function(request, container) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.APIXMLIndex.getConfig().moduleScript,
        data: {
          'type': NeoX.Modules.APIXMLIndex.getConfig().moduleType,
          'action': 'apixml_formatted',
          'req': request,
          'submit': 1
        },
        success: NeoX.Modules.APIXMLRequests.sendReqCallback(container)
      }); 
    },
    
    runReq: function(request, container) {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.APIXMLIndex.getConfig().moduleScript,
        data: {
          'type': NeoX.Modules.APIXMLIndex.getConfig().moduleType,
          'action': 'apixml_xml',
          'req': request,
          'submit': 1
        },
        success: NeoX.Modules.APIXMLRequests.loadReqCallback(container)
      }); 
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    sendReqCallback: function(container) {
    	return function(data) {
    		container.html(data);
    	}; 
    },
    
    loadReqCallback: function(container) {
      return function(data) {
        container.html(data);
      }; 
    }
    
    
  }

};

Sky.Class.Define("NeoX.Modules.APIXMLRequests", MAPIXMLRequests_Implementation);
