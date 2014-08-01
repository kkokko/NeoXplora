var TRepEntity_Implementation = {
  
  construct: function(Parent, Id, EntityNumber, EntityType, Name, PageId) {
    this.base(this);
    this.Kids = new NeoX.TStringList();
    this.setParentObject(Parent);
    this.setId(Id);
    this.setEntityNumber(EntityNumber);
    this.setEntityType(EntityType);
    this.setName(Name);
    this.setPageId(PageId);
  },
  
  properties: {
    Id: null, //int
    ParentObject: null, //RepRecord 
    EntityNumber: null, //int
    EntityType: null, //(etPerson, etObject, etGroup)
    Kids: null, //string, TRepPropertyKey
    Name: null, //string
    PageId: null //int
  },
  
  methods: {
    
    addKid: function(key, repPropertyKid) {
    	this.Kids.add(key, repPropertyKid);
    }
    
  }

};

Sky.Class.Define("NeoX.TRepEntity", TRepEntity_Implementation);