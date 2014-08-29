var MAPIXMLIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
    	Buttons: {
        retrainBtn: '.retrainBtn'
      },
      moduleScript: 'panel.php',
      moduleType: 'tests',
      dataContainer: '.boxContent'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.APIXMLIndex.hookEvents();
      
      $(document).ready(function() {
      	$( "#tabs" ).tabs({
          beforeLoad: function( event, ui ) {
            ui.jqXHR.error(function() {
              ui.panel.html(
                "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                "If this wouldn't be a demo." );
            });
          }
        });
      });
      
    },
    
    hookEvents: function() {
      NeoX.Modules.APIXMLIndex.hookEvent("click", ".apiXMLRequest", NeoX.Modules.APIXMLIndex.sendReq);
    },
    
    sendReq: function() {
    	var request = $(this).attr("id").replace("#", "");
      NeoX.Modules.APIXMLRequests.sendReq(request, $(this).parents(".ui-tabs-panel").first());    	
    }

  }
  
};

Sky.Class.Define("NeoX.Modules.APIXMLIndex", MAPIXMLIndex_Implementation);
