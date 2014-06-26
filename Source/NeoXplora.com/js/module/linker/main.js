var TMain = function () {
  var obj = new TObject(); 
  
  obj.config = {
    "buttonSelector": '.button',
    "buttonDropdownSelector": '.button-dropdown',
    "buttons": [
      '#add-person',
      '#add-object'
    ],
    "hidden": true
  }
  
  obj.init = function(settings) {
    $.extend(this.config, settings);
    obj.hookEvents();
  }
  
  obj.hookEvents = function() {
  	obj.hookEvent("mouseenter", obj.config.buttonSelector, obj.buttonMouseEnter);
  	obj.hookEvent("mouseleave", obj.config.buttonSelector, obj.buttonMouseLeave);
    $.each(obj.config.buttons, function(index, item) {
    	obj.hookEvent("click", item, TEntity.addEntity);
    });
  }
  
  obj.buttonMouseEnter = function() {
  	$(this).find(obj.config.buttonDropdownSelector).show();
    obj.config.hidden = false;
  }
  
  obj.buttonMouseLeave = function() {
  	var button = $(this); 
    obj.config.hidden = true;
    setTimeout(function () {
      if(obj.config.hidden) {
        button.find(obj.config.buttonDropdownSelector).hide();
      }
    }, 200);
  }
  
  return obj;
}();