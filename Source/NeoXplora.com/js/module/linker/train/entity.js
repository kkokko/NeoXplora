var MEntityControl_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
    	Controls: {
    		"self": ".entity",
    		"portrait": '.portrait',
        "container": ".boxRight",
        "active": ".activeEntity",
        "single": ".singleEntity",
        "group": ".groupEntity"
    	}
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.EntityControl.hookEvents();
      NeoX.Modules.EntityControl.setup();
    },
    
    setup: function() {
    	$(document).ready(function() {
    		$(NeoX.Modules.EntityControl.getConfig().Controls.self).draggable({ helper: "clone" });
        $(NeoX.Modules.EntityControl.getConfig().Controls.self).droppable({
          drop: NeoX.Modules.EntityControl.handleDragAndDrop
        });
    	});
    	$(NeoX.Modules.EntityControl.getConfig().Controls.self).matchHeight(false);
    },
    
    hookEvents: function() {
      NeoX.Modules.EntityControl.hookEvent("click", NeoX.Modules.EntityControl.getConfig().Controls.self, this.select);
    },
    
    select: function() {
      var element = $(this);
      $(NeoX.Modules.EntityControl.getConfig().Controls.active).each(function() {
        if(!$(this).is(element)) {
          $(this).removeClass(NeoX.Modules.EntityControl.getConfig().Controls.active.substring(1));
        }
      });
      element.toggleClass(NeoX.Modules.EntityControl.getConfig().Controls.active.substring(1));
    },
    
    handleDragAndDrop: function(event, ui) {
      var droppedEntity = ui.draggable.find(NeoX.Modules.EntityControl.getConfig().Controls.portrait);
      var targetEntity = $(this).find(NeoX.Modules.EntityControl.getConfig().Controls.portrait);
      
      if(droppedEntity.html().trim() != "Group" && targetEntity.html().trim() == "Group") {
        console.log("yes");
      } else {
      	console.log("no");
      }
   
    },
    
    addEntity: function(type) {
      var colorID = 0;
      $.each(NeoX.Modules.EntityControl.getClassList($(NeoX.Modules.EntityControl.getConfig().Controls.self).last().find(NeoX.Modules.EntityControl.getConfig().Controls.portrait)), function(index, item) {
        if(item.indexOf('color') != -1) {
           colorID = parseInt(item.replace('color', ''), 10);
        }
      });
      colorID++;
      
      var newEntity = NeoX.Modules.EntityControl.newEntityHTML(colorID, type);
      $(NeoX.Modules.EntityControl.getConfig().Controls.container).append(newEntity);
      $(NeoX.Modules.EntityControl.getConfig().Controls.container).find('.clear').remove();
      $(NeoX.Modules.EntityControl.getConfig().Controls.container).append("<div class='clear'></div>");
      NeoX.Modules.EntityControl.setup();
    },
    
    newEntityHTML: function(id, type) {
    	if(!type) type = "Object";
      var entityHTML = " " + 
        "<div class='" + NeoX.Modules.EntityControl.getConfig().Controls.self.substring(1) + "'>" +
        "<div class='" + NeoX.Modules.EntityControl.getConfig().Controls.portrait.substring(1) + " color" + id + "'>" +
        type + 
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
