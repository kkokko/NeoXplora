var MLinkerIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      "buttons": [
        '#add-person',
        '#add-object'
      ]
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.LinkerIndex.hookEvents();
    },
    
    hookEvents: function() {
      $.each(NeoX.Modules.LinkerIndex.getConfig().buttons, function(index, item) {
        NeoX.Modules.LinkerIndex.hookEvent("click", item, NeoX.Modules.EntityControl.addEntity);
      });
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.LinkerIndex", MLinkerIndex_Implementation);
