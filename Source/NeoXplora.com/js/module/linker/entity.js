var MEntityControl_Implementation = {
  extend: "TBaseObject",
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
  
  members: {
    
    init: function() {
      MEntityControl.hookEvents();
      MEntityControl.setup();
    },
    
    setup: function() {
      $(MEntityControl.getConfig().selector).droppable({
        drop: MEntityControl.switchEntity
      });
    },
    
    hookEvents: function() {
      MEntityControl.hookEvent("click", MEntityControl.getConfig().selector, this.select);
    },
    
    select: function() {
      var element = $(this);
      $(MEntityControl.getConfig().activeSelector).each(function() {
        if(!$(this).is(element)) {
          $(this).removeClass(MEntityControl.getConfig().activeSelector.substring(1));
        }
      });
      element.toggleClass(MEntityControl.getConfig().activeSelector.substring(1));
    },
    
    switchEntity: function(event, ui) {
      var word = ui.draggable;
      var entity = $(this).find(MEntityControl.getConfig().portraitSelector);
      
      MWordControl.changeHookedEntity(word, entity);
    },
    
    addEntity: function() {
      var colorID = 0;
      $.each(MEntityControl.getClassList($(MEntityControl.getConfig().selector).last().find(MEntityControl.getConfig().portraitSelector)), function(index, item) {
        if(item.indexOf('color') != -1) {
           colorID = parseInt(item.replace('color', ''), 10);
        }
      });
      colorID++;
      
      var newEntity = MEntityControl.newEntityHTML(colorID);
      $(MEntityControl.getConfig().containerSelector).append(newEntity);
      $(MEntityControl.getConfig().containerSelector).find('.clear').remove();
      $(MEntityControl.getConfig().containerSelector).append("<div class='clear'></div>");
      MEntityControl.setup();
    },
    
    newEntityHTML: function(id) {
      var entityHTML = " " + 
        "<div class='" + MEntityControl.getConfig().selector.substring(1) + "'>" +
        "<div class='" + MEntityControl.getConfig().portraitSelector.substring(1) + " color" + id + "'>" +
        "</div>" +
        "<div class='info'>" +
        "<div class='label'>Name</div><div class='value'>undefined</div>" +
        "</div>" +
        "</div>";
        
      return entityHTML;
    }
    
  }
  
};

Sky.Class.Define("MEntityControl", MEntityControl_Implementation);
