var TObject = function() {
	var obj = {};
	
  obj.hookEvent = function(event, selector, func) {
  	$(document).on(event, selector, func);
  }
  
  obj.getClassList = function(element) {
    return element.attr('class').split(/\s+/);
  }
  
  return obj;
}