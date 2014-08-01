var MLinkerTrainRequests_Implementation = {
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
    
    load: function() {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.LinkerTrainIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.LinkerTrainIndex.getConfig().moduleType,
          'action': 'load'
        },
        success: NeoX.Modules.LinkerTrainRequests.loadCallback
      });
    },
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
      NeoX.Modules.LinkerTrainIndex.loadData(json);
      $(NeoX.Modules.LinkerTrainIndex.getConfig().sentenceContainer).html(NeoX.Modules.LinkerTrainIndex.sentencesToHtml());
      $(NeoX.Modules.LinkerTrainIndex.getConfig().entityContainer).html(NeoX.Modules.LinkerTrainIndex.entitiesToHtml());
    }
    
  }

};

Sky.Class.Define("NeoX.Modules.LinkerTrainRequests", MLinkerTrainRequests_Implementation);
