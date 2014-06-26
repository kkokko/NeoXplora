//var TRepEntityComponent
var TEntity = function () {
	var obj = new TObject(); 
	
  obj.config = {
    "selector": '.entity',
    "portraitSelector": '.portrait',
    "containerSelector": ".boxRight",
    "activeSelector": ".activeEntity",
  }
  
  obj.init = function(settings) {
  	$.extend(this.config, settings);
    obj.hookEvents();
    obj.setup();
  }
  
  obj.setup = function () {
    $(this.config.selector).droppable({
      drop: obj.switchEntity
    });
  }
  
  obj.hookEvents = function() {
  	obj.hookEvent("click", obj.config.selector, obj.select);
  }
  
  obj.select = function() {
  	var element = $(this);
    $(obj.config.activeSelector).each(function() {
      if(!$(this).is(element)) {
        $(this).removeClass(obj.config.activeSelector.substring(1));
      }
    });
    element.toggleClass(obj.config.activeSelector.substring(1));
  }
  
  obj.switchEntity = function(event, ui) {
    var word = ui.draggable;
    var entity = $(this).find(obj.config.portraitSelector);
    
    TWord.changeHookedEntity(word, entity);
  }
  
  obj.addEntity = function() {
    var colorID = 0;
    $.each(obj.getClassList($(obj.config.selector).last().find(obj.config.portraitSelector)), function(index, item) {
      if(item.indexOf('color') != -1) {
         colorID = parseInt(item.replace('color', ''), 10);
      }
    });
    colorID++;
    
    var newEntity = obj.newEntityHTML(colorID);
    $(obj.config.containerSelector).append(newEntity);
    $(obj.config.containerSelector).find('.clear').remove();
    $(obj.config.containerSelector).append("<div class='clear'></div>");
    obj.setup();
  }
  
  obj.newEntityHTML = function(id) {
  	var entityHTML = " " + 
      "<div class='" + obj.config.selector.substring(1) + "'>" +
      "<div class='" + obj.config.portraitSelector.substring(1) + " color" + id + "'>" +
      "</div>" +
      "<div class='info'>" +
      "<div class='label'>Name</div><div class='value'>undefined</div>" +
      "</div>" +
      "</div>";
      
    return entityHTML;
  }
  
  return obj;
}();