var TList_Implementation = {
  
  construct: function() {
    this.base(this);
    this.setItems([]);
  },
  
  properties: {
    Items: null
  },
  
  methods: {
    
    add: function(item) {
      this.Items.push(item);
    },
    
    count: function() {
      return this.Items.length;
    },
    
    pop: function() {
    	if(this.count()) {
    		var theItem = this.Items[this.Items.length - 1];
    		this.Items.splice(this.Items.length - 1, 1);
        return theItem;
    	}
    },
    
    item: function(index) {
    	if(this.count() > index) {
        return this.Items[index];
      } else {
        throw "ListIndexOutOfBounds";
      }
    },
    
    object: function(index) {
    	return this.item(index);
    },
    
    setItem: function(index, item) {
    	if(this.count() > index) {
        this.Items[index] = item;
    	} else {
  		  throw "ListIndexOutOfBounds";
    	}
    },
    
    insertAt: function(index, item) {
    	if(this.count() >= index) {
    		this.Items.splice(index, 0, item);
    	} else {
        throw "ListIndexOutOfBounds";
      }
    },
    
    remove: function(index) {
      if(this.count() > index) {
        this.Items.splice(index, 1);
      } else {
        throw "ListIndexOutOfBounds";
      }
    },
    
    itemExists: function(item) {
    	for(var i = 0; i < this.count(); i++) {
    		if(this.Items[i] == item) {
    			return true;
    		}
    	}
    	return false;
    }
    
  }

};

Sky.Class.Define("Sky.TList", TList_Implementation);