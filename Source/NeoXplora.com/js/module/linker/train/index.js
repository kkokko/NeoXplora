var MLinkerTrainIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      Buttons: {
        addPerson: '#add-person',
        addObject: '#add-object',
        addGroup: '#add-group'
      }
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.LinkerTrainIndex.hookEvents();
      $(document).ready( function() {
      	$(NeoX.Modules.EntityControl.getConfig().Controls.self).matchHeight(false);
      });
    },
    
    hookEvents: function() {
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.addPerson, NeoX.Modules.LinkerTrainIndex.addEntityEvent,  {type: "Person"});
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.addObject, NeoX.Modules.LinkerTrainIndex.addEntityEvent, {type: "Object"});
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.addGroup, NeoX.Modules.LinkerTrainIndex.addEntityEvent, {type: "Group"});
    },
    
    addEntityEvent: function(e) {
    	NeoX.Modules.EntityControl.addEntity(e.data.type);
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.LinkerTrainIndex", MLinkerTrainIndex_Implementation);
