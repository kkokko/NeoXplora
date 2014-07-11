var TRuleValue_Implementation = {

  extend: "NeoAI.TBaseRule",
  
  construct: function(propertyType, operatorType, propertyKey, propertyValue) {
	this.base(this);
    this.setPropertyType(propertyType);
    this.setOperatorType(operatorType);
    this.setPropertyKey(propertyKey);
    this.setPropertyValue(propertyValue);
  },
  
  properties: {
    PropertyType: null,
    OperatorType: null,
    PropertyKey: null,
    PropertyValue: null
  },
  
  methods: {
  
    toString: function(indent) {
      indent = typeof indent !== 'undefined' ? indent : 0;
      var indentStr = "";
      while(indent > 0) {
        indentStr += "\t";
        indent--;
      }
      
      return ( indentStr + "TRuleValue[" + this.getIndex() + "]:{PropertyType: " + this.getPropertyType() +
          ", OperandType: " + this.getOperatorType() +
          ", PropertyKey: " + this.getPropertyKey() +
          ", PropertyValue: " + this.getPropertyValue() + "}"
      );
    }
    
  }

}

Sky.Class.Define("NeoAI.TRuleValue", TRuleValue_Implementation);