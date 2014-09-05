Sky.Class.Define("NeoX.Modules.APIXMLRequests", {
  extend: "NeoX.TBaseObject",
  type: "module",
  construct: function() {
    this.base(this);
  },
  
  properties: {
    moduleScript: 'panel.php',
    moduleType: 'pages'
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
    
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    sendReqCallback: function(container) {
      return function(data) {
        container.html(data);
      }; 
    }
  }

});
