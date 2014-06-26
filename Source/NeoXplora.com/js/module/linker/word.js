var TWord = function () {
  var obj = new TObject(); 
  
  obj.config = {
    "selector": '.word'
  }
  
  obj.init = function(settings) {
    $.extend(Object.config, settings);
    obj.hookEvents();
    obj.setup();
  }
  
  obj.setup = function () {
    $(obj.config.selector).draggable({ helper: "clone" });
  
    $(obj.config.selector).parent(obj.config.selector).each(function() {
      $(this).css('padding', 0);
    });
  }
  
  obj.hookEvents = function() {
    obj.hookEvent("click", obj.config.selector, obj.switchColors);
  }
  
  obj.switchColors = function() {
    if($(TEntity.config.activeSelector).length) {
      var word = $(this);
      var entity = $(TEntity.config.activeSelector).find(TEntity.config.portraitSelector);
      
      obj.changeHookedEntity(word, entity);
    } else {
      alert("Please select an entity by clicking a box from the right side");
    }
  }
  
  obj.changeHookedEntity = function (word, entity) {
    $.each(obj.getClassList(word), function(index, item) {
      if(item.indexOf('color') != -1) {
        word.removeClass(item);
      }
    });
    
    $.each(obj.getClassList(entity), function(index, item) {
      if(item.indexOf('color') != -1) {
        word.addClass(item);
      }
    });
  }
  
  return obj;
}();