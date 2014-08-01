var TRepPropertyValue_Implementation = {
  
  construct: function(Parent, Id, TargetEntityId, TargetKeyId, TargetValueId, OperatorType, KeyId, Value) {
    this.base(this);
    this.Kids = new NeoX.TStringList();
    this.setParentObject(Parent);
    this.setId(Id);
    this.setTargetEntityId(TargetEntityId);
    this.setTargetKeyId(TargetKeyId);
    this.setTargetValueId(TargetValueId);
    this.setOperatorType(OperatorType);
    this.setKeyId(KeyId);
    this.setValue(Value);
  },
  
  properties: {
    Id: null, //int
    ParentObject: null, //RepPropertyKey
    Kids: null, //array of string : repPropertyKey
    TargetEntityId: null, //int
    TargetKeyId: null, //int
    TargetValueId: null, //int
    OperatorType: null, //(otNone, otEquals, otSmaller, otSmallerOrEqual, otGreater, otGreaterOrEqual, otDiffers)
    KeyId: null, //int
    Value: null, //string
    LinkObject: null //LinkType: (ltNone, ltEntity, ltAttrKey, ltEventKey)
  },
  
  methods: {
    
    addKid: function(key, repPropertyValue) {
      this.Kids.add(key, repPropertyValue);
    }
    
  }

};

Sky.Class.Define("NeoX.TRepPropertyValue", TRepPropertyValue_Implementation);