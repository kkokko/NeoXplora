var MMainControl_Implementation = {
  extend: "TBaseObject",
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
  
  members: {
    
    init: function() {
      MMainControl.hookEvents();
      MMainControl.setup();
    },
    
    setup: function () {
      $(MMainControl.getConfig().selector).droppable({
        drop: MMainControl.switchEntity
      });
    },
    
    hookEvents: function() {
      MMainControl.hookEvent("mouseenter", MMainControl.getConfig().buttonSelector, MMainControl.buttonMouseEnter);
      MMainControl.hookEvent("mouseleave", MMainControl.getConfig().buttonSelector, MMainControl.buttonMouseLeave);
      $.each(MMainControl.getConfig().buttons, function(index, item) {
        MMainControl.hookEvent("click", item, MEntityControl.addEntity);
      });
    },
    
    buttonMouseEnter: function() {
      $(this).find(MMainControl.getConfig().buttonDropdownSelector).show();
      MMainControl.getConfig().hidden = false;
    },
    
    buttonMouseLeave: function() {
      var button = $(this); 
      MMainControl.getConfig().hidden = true;
      setTimeout(function () {
        if(MMainControl.getConfig().hidden) {
          button.find(MMainControl.getConfig().buttonDropdownSelector).hide();
        }
      }, 200);
    }
    
  }
  
};

Sky.Class.Define("MMainControl", MMainControl_Implementation);
