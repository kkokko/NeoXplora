var TLinkerRuleValue_Implementation = {

  extend: "NeoAI.TRuleValue",
  
  construct: function(keyEntity, keyPropertyType, propertyKey, operatorType, valueEntity, valuePropertyType, propertyValue) {
    this.base(this, keyPropertyType, operatorType, propertyKey, propertyValue);
    this.setKeyEntity(keyEntity);
    this.setValueEntity(valueEntity);
    this.setValuePropertyType(valuePropertyType);
  },
  
  properties: {
    KeyEntity: null,
    ValueEntity: null,
    ValuePropertyType: null
  },
  
  methods: {
  
    toString: function(indent) {
      indent = typeof indent !== 'undefined' ? indent : 0;
      var indentStr = "";
      while(indent > 0) {
        indentStr += "\t";
        indent--;
      }
      
      return ( indentStr + "TRuleValue[" + this.getIndex() + "]:{KeyEntity: " + this.getKeyEntity() + 
      		", PropertyType: " + this.getPropertyType() +
      		", PropertyKey: " + this.getPropertyKey() +
          ", OperandType: " + this.getOperatorType() +
          ", ValueEntity: " + this.getValueEntity() +
          ", ValuePropertyType: " + this.getValuePropertyType() +
          ", PropertyValue: " + this.getPropertyValue() + "}"
      );
    }
    
  }

};

Sky.Class.Define("NeoAI.TLinkerRuleValue", TLinkerRuleValue_Implementation);