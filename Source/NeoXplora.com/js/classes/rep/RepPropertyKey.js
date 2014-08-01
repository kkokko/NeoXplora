var TRepPropertyKey_Implementation = {
  
  construct: function(Parent, Id, Key, ParentId, PropertyType) {
    this.base(this);
    this.Kids = new NeoX.TStringList();
    this.Values = new NeoX.TStringList();
    this.setKey(Key);
    this.setParentObject(Parent);
    this.setId(Id);
    this.setParentId(ParentId);
    this.setPropertyType(PropertyType);
  },
  
  properties: {
    Id: null, //int
    ParentObject: null, //RepEntity / RepPropertyValue
    Key: null, //string
    Kids: null, //array of string: TRepPropertyKey
    ParentId: null, //int
    PropertyType: null, //ptAttribute, ptEvent
    Values: null //array of string: TRepPropertyValue
  },
  
  methods: {
  	
    addKid: function(key, repPropertyKey) {
      this.Kids.add(key, repPropertyKey);
    },
    
    addValue: function(key, repPropertyValue) {
    	this.Values.add(key, repPropertyValue);
    }
    
  }

};

Sky.Class.Define("NeoX.TRepPropertyKey", TRepPropertyKey_Implementation);