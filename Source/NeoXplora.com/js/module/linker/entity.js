var MEntityControl_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      "selector": '.entity',
      "portraitSelector": '.portrait',
      "containerSelector": ".boxRight",
      "activeSelector": ".activeEntity"
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.EntityControl.hookEvents();
      NeoX.Modules.EntityControl.setup();
    },
    
    setup: function() {
    	$(document).ready(function() {
        $(NeoX.Modules.EntityControl.getConfig().selector).droppable({
          drop: NeoX.Modules.EntityControl.switchEntity
        });
    	});
    },
    
    hookEvents: function() {
      NeoX.Modules.EntityControl.hookEvent("click", NeoX.Modules.EntityControl.getConfig().selector, this.select);
    },
    
    select: function() {
      var element = $(this);
      $(NeoX.Modules.EntityControl.getConfig().activeSelector).each(function() {
        if(!$(this).is(element)) {
          $(this).removeClass(NeoX.Modules.EntityControl.getConfig().activeSelector.substring(1));
        }
      });
      element.toggleClass(NeoX.Modules.EntityControl.getConfig().activeSelector.substring(1));
    },
    
    switchEntity: function(event, ui) {
      var word = ui.draggable;
      var entity = $(this).find(NeoX.Modules.EntityControl.getConfig().portraitSelector);
      
      NeoX.Modules.WordControl.changeHookedEntity(word, entity);
    },
    
    addEntity: function() {
      var colorID = 0;
      $.each(NeoX.Modules.EntityControl.getClassList($(NeoX.Modules.EntityControl.getConfig().selector).last().find(NeoX.Modules.EntityControl.getConfig().portraitSelector)), function(index, item) {
        if(item.indexOf('color') != -1) {
           colorID = parseInt(item.replace('color', ''), 10);
        }
      });
      colorID++;
      
      var newEntity = NeoX.Modules.EntityControl.newEntityHTML(colorID);
      $(NeoX.Modules.EntityControl.getConfig().containerSelector).append(newEntity);
      $(NeoX.Modules.EntityControl.getConfig().containerSelector).find('.clear').remove();
      $(NeoX.Modules.EntityControl.getConfig().containerSelector).append("<div class='clear'></div>");
      NeoX.Modules.EntityControl.setup();
    },
    
    newEntityHTML: function(id) {
      var entityHTML = " " + 
        "<div class='" + NeoX.Modules.EntityControl.getConfig().selector.substring(1) + "'>" +
        "<div class='" + NeoX.Modules.EntityControl.getConfig().portraitSelector.substring(1) + " color" + id + "'>" +
        "</div>" +
        "<div class='info'>" +
        "<div class='label'>Name</div><div class='value'>undefined</div>" +
        "</div>" +
        "</div>";
        
      return entityHTML;
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.EntityControl", MEntityControl_Implementation);
