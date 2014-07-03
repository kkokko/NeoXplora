var MWordControl_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      "selector": '.word'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.WordControl.hookEvents();
      NeoX.Modules.WordControl.setup();
    },
    
    setup: function () {
    	$(document).ready(function() {
    		$(NeoX.Modules.WordControl.getConfig().selector).draggable({ helper: "clone" });
    		$(NeoX.Modules.WordControl.getConfig().selector).parent(NeoX.Modules.WordControl.getConfig().selector).each(function() {
          $(this).css('padding', 0);
        });
    	});      
    },
    
    hookEvents: function() {
      NeoX.Modules.WordControl.hookEvent("click", NeoX.Modules.WordControl.getConfig().selector, NeoX.Modules.WordControl.switchColors);
    },
    
    switchColors: function() {
      if($(NeoX.Modules.EntityControl.getConfig().activeSelector).length) {
        var word = $(this);
        var entity = $(NeoX.Modules.EntityControl.getConfig().activeSelector).find(NeoX.Modules.EntityControl.getConfig().portraitSelector);
        
        NeoX.Modules.WordControl.changeHookedEntity(word, entity);
      } else {
        alert("Please select an entity by clicking a box from the right side");
      }
    },
    
    changeHookedEntity: function (word, entity) {
      $.each(NeoX.Modules.WordControl.getClassList(word), function(index, item) {
        if(item.indexOf('color') != -1) {
          word.removeClass(item);
        }
      });
      
      $.each(NeoX.Modules.WordControl.getClassList(entity), function(index, item) {
        if(item.indexOf('color') != -1) {
          word.addClass(item);
        }
      });
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.WordControl", MWordControl_Implementation);
