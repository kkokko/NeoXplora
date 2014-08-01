var TStringList_Implementation = {
  
  construct: function() {
    this.base(this);
    this.setItems([]);
    this.setObjects([]);
  },
  
  properties: {
    Items: null,
    Objects: null
  },
  
  methods: {
    
    add: function(key, object) {
      this.Items.push(key);
      if(typeof object === 'undefined') {
        this.Objects.push(null);
      } else {
      	this.Objects.push(object);
      }
    },
    
    count: function() {
      return this.Items.length;
    },
    
    item: function(index) {
    	if(this.count() > index) {
        return this.Items[index];
      } else {
        throw "StringListIndexOutOfBounds";
      }
    },
    
    object: function(index) {
    	if(this.count() > index) {
        return this.Objects[index];
      } else {
        throw "StringListIndexOutOfBounds";
      }
    },

    setItem: function(index, item) {
    	if(this.count() > index) {
        this.Items[index] = item;
    	} else {
  		  throw "StringListIndexOutOfBounds";
    	}
    },
    
    setObject: function(index, object) {
    	if(this.count() > index) {
        this.Objects[index] = object;
      } else {
        throw "StringListIndexOutOfBounds";
      }
    },
    
    remove: function(index) {
      if(this.count() > index) {
        this.Items = this.Items.splice(index, 1);
      } else {
        throw "StringListIndexOutOfBounds";
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

Sky.Class.Define("NeoX.TStringList", TStringList_Implementation);