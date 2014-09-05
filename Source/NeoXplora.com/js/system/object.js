var TBaseObject_Implementation = {
	
  construct: function() {
    this.base(this);
  },
  
  methods: {
  	
    hookEvent: function(event, selector, func, data) {
      $(document).ready(function() {
      	if(data) {
    		  $(document).on(event, selector, data, func);
      	} else {
          $(document).on(event, selector, func);
      	}
      });
    },
    
    getClassList: function(element) {
    	var classAttr = element.attr('class');
    	if(classAttr) {
        return classAttr.split(/\s+/);
    	} else {
    		return [""];
    	}
    },
    
    getParameterByName: function(name) {
      name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
          results = regex.exec(location.search);
      return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    
  }
  
};

Sky.Class.Define("NeoX.TBaseObject", TBaseObject_Implementation);