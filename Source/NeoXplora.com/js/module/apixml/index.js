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
                "If this wouldn't be a demo." 
              );
            });
          }
        });
      });
    },
    
    hookEvents: function() {
      NeoX.Modules.APIXMLIndex.hookEvent("click", ".apiXMLRequest", NeoX.Modules.APIXMLIndex.sendReq);
      NeoX.Modules.APIXMLIndex.hookEvent("click", "#run", NeoX.Modules.APIXMLIndex.runReq);
      NeoX.Modules.APIXMLIndex.hookEvent("click", ".loadXML", NeoX.Modules.APIXMLIndex.loadXML);
    },
    
    sendReq: function() {
    	var request = $(this).attr("id").replace("#", "");
      NeoX.Modules.APIXMLRequests.sendReq(request, $(this).parents(".ui-tabs-panel").first());
    },
    
    runReq: function() {
    	var request = $("#req").val();
    	NeoX.Modules.APIXMLRequests.runReq(request, $(this).parents(".ui-tabs-panel").last());
    },
    
    loadXML: function() {
    	var request = $(this).attr("id");
    	var theXML = '';
    	switch(request) {
    		case 'generateRep':
    		  theXML = "<ApiRequestGenerateRep><ApiKey>abc</ApiKey><SentenceText>My name is Mimi</SentenceText></ApiRequestGenerateRep>";
    		break
    		case 'generateRep2':
    		  theXML = "<ApiRequestGenerateRep><ApiKey>abc</ApiKey><SentenceText>My name is Mimi</SentenceText><OutputSentence>True</OutputSentence></ApiRequestGenerateRep>";
        break
        case 'guessProto':
          theXML = "<ApiRequestGenerateProtoGuess><ApiKey>abc</ApiKey><SentenceText>My name is Mimi</SentenceText><OutputSentence>True</OutputSentence></ApiRequestGenerateProtoGuess>";
        break
    	}
    	
    	$("#req").val(theXML);
    	
    	
    }

  }
  
};

Sky.Class.Define("NeoX.Modules.APIXMLIndex", MAPIXMLIndex_Implementation);
