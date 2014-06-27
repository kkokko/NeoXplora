
Sky.Class.Define("MWordControl", {
  extend: "TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      "selector": '.word'
    }
  },
  
  members: {
    
    init: function() {
      MWordControl.hookEvents();
      MWordControl.setup();
    },
    
    setup: function () {
      $(MWordControl.getConfig().selector).draggable({ helper: "clone" });
    
      $(MWordControl.getConfig().selector).parent(MWordControl.getConfig().selector).each(function() {
        $(this).css('padding', 0);
      });
    },
    
    hookEvents: function() {
      MWordControl.hookEvent("click", MWordControl.getConfig().selector, MWordControl.switchColors);
    },
    
    switchColors: function() {
      if($(MEntityControl.getConfig().activeSelector).length) {
        var word = $(this);
        var entity = $(MEntityControl.getConfig().activeSelector).find(MEntityControl.getConfig().portraitSelector);
        
        MWordControl.changeHookedEntity(word, entity);
      } else {
        alert("Please select an entity by clicking a box from the right side");
      }
    },
    
    changeHookedEntity: function (word, entity) {
      $.each(MWordControl.getClassList(word), function(index, item) {
        if(item.indexOf('color') != -1) {
          word.removeClass(item);
        }
      });
      
      $.each(MWordControl.getClassList(entity), function(index, item) {
        if(item.indexOf('color') != -1) {
          word.addClass(item);
        }
      });
    }
    
  }
  
});
