

var TRuleValue = function(propertyType,operatorType,propertyKey,propertyValue){ 

	TBaseRule.apply(this, arguments);//(extends TBaseRule)
  //properties
    this.PropertyType = propertyType;
    this.OperatorType = operatorType;
    this.PropertyKey = propertyKey;
    this.PropertyValue = propertyValue;
	
	this.toString = function(indent){
		indent = typeof indent !== 'undefined' ? indent : 0;
		var indentStr = "";
		while(indent>0){indentStr+="\t";indent--;}
		return (indentStr+"TRuleValue["+this.Index+"]:{PropertyType: "+this.PropertyType+
				", OperandType: "+this.OperatorType+
				", PropertyKey: "+this.PropertyKey+
				", PropertyValue: "+this.PropertyValue+"}"
				);
	}
	
}