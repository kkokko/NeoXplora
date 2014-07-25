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
    }
    
  }
  
};

Sky.Class.Define("NeoX.TBaseObject", TBaseObject_Implementation);