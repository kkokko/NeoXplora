var MMainControl_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      "buttonSelector": '.button',
      "buttonDropdownSelector": '.button-dropdown',
      "buttons": [
        '#add-person',
        '#add-object'
      ],
      "hidden": true
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.MainControl.hookEvents();
      NeoX.Modules.MainControl.setup();
    },
    
    setup: function () {
      $(NeoX.Modules.MainControl.getConfig().selector).droppable({
        drop: NeoX.Modules.MainControl.switchEntity
      });
    },
    
    hookEvents: function() {
      NeoX.Modules.MainControl.hookEvent("mouseenter", NeoX.Modules.MainControl.getConfig().buttonSelector, NeoX.Modules.MainControl.buttonMouseEnter);
      NeoX.Modules.MainControl.hookEvent("mouseleave", NeoX.Modules.MainControl.getConfig().buttonSelector, NeoX.Modules.MainControl.buttonMouseLeave);
      $.each(NeoX.Modules.MainControl.getConfig().buttons, function(index, item) {
        NeoX.Modules.MainControl.hookEvent("click", item, NeoX.Modules.EntityControl.addEntity);
      });
    },
    
    buttonMouseEnter: function() {
      $(this).find(NeoX.Modules.MainControl.getConfig().buttonDropdownSelector).show();
      NeoX.Modules.MainControl.getConfig().hidden = false;
    },
    
    buttonMouseLeave: function() {
      var button = $(this); 
      NeoX.Modules.MainControl.getConfig().hidden = true;
      setTimeout(function () {
        if(NeoX.Modules.MainControl.getConfig().hidden) {
          button.find(NeoX.Modules.MainControl.getConfig().buttonDropdownSelector).hide();
        }
      }, 200);
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.MainControl", MMainControl_Implementation);
