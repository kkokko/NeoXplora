var MButtonComponent_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      "buttonSelector": '.button',
      "buttonDropdownSelector": '.button-dropdown',
      "hidden": true
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.ButtonComponent.hookEvents();
    },
    
    hookEvents: function() {
      NeoX.Modules.ButtonComponent.hookEvent("mouseenter", NeoX.Modules.ButtonComponent.getConfig().buttonSelector, NeoX.Modules.ButtonComponent.buttonMouseEnter);
      NeoX.Modules.ButtonComponent.hookEvent("mouseleave", NeoX.Modules.ButtonComponent.getConfig().buttonSelector, NeoX.Modules.ButtonComponent.buttonMouseLeave);
    },
    
    buttonMouseEnter: function() {
      $(this).find(NeoX.Modules.ButtonComponent.getConfig().buttonDropdownSelector).show();
      NeoX.Modules.ButtonComponent.getConfig().hidden = false;
    },
    
    buttonMouseLeave: function() {
      var button = $(this); 
      NeoX.Modules.ButtonComponent.getConfig().hidden = true;
      setTimeout(function () {
        if(NeoX.Modules.ButtonComponent.getConfig().hidden) {
          button.find(NeoX.Modules.ButtonComponent.getConfig().buttonDropdownSelector).hide();
        }
      }, 200);
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.ButtonComponent", MButtonComponent_Implementation);
