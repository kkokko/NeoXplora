
var TIRepConditionParser = function (){
	
	this.PropertyType = null; // string  (valid values: ptAttribute, ptEvent)
	this.PropertyKey = null;  // string
	this.OperatorType = null;  // string  (valid values: otEquals, otLess, otLessOrEqual, otGreater, otGreaterOrEqual, otDiffers)
	this.PropertyValue = null;// string
	
	this.__position = 0;
	this.__string = null;
	
	this.ParseString = function (string){
		this.__string = string.trim();
		
		this.PropertyType = this.getPropertyType();
		this.parseSpaces(); // skipps spaces between operand and key
		this.PropertyKey = this.getOperand().trim();
		this.parseSpaces();
		this.OperatorType = this.getOperatorType();
		this.parseSpaces();
		this.PropertyValue = this.getOperand().trim();
		
		return new TRuleValue(this.PropertyType,this.OperatorType,this.PropertyKey,this.PropertyValue);
	}
	
	this.getPropertyType = function (){
		
		if(this.__string.length>0){
			switch(this.__string[this.__position]){
				case '.':
					this.__position++;
					return 'ptAttribute';
					break;
				case ':':
					this.__position++;
					return 'ptEvent';
					break;
				default:
					throw "UndefinedPropertyTypeException";
					break;
			}
		}else{
			throw "InvalidInputStringException";
		}
		
	}
	
	this.getOperand = function(){
		var theInQuote = (this.__string[this.__position] == '"');
		if (theInQuote) {
			this.__position++;
		}
		var theIncrement = theInQuote?2:0;
		var theStart = this.__position;
		while (this.__position < this.__string.length && !theInQuote && ("!=<>").indexOf(this.__string[this.__position])==-1) {
			if((".,{}[]():+").indexOf(this.__string[this.__position])!=-1) {
				throw "InvalidOperandException";
			}
			if(!theInQuote && ("\\\"").indexOf(this.__string[this.__position])!=-1) {
				throw "InvalidOperandException";
			}
			if (this.__string[this.__position] == '"'){
				dec(this.__position);
				theInQuote = false;
				break;
			}
			if (this.__string[this.__position] == '\\'){
				this.__position++;
			}
			this.__position++;
		}
		if (theInQuote){
			throw "InvalidOperandException";
		}
		var theResult = this.__string.slice(theStart, this.__position);
		this.__position += theIncrement;
		return theResult; 
	}
	
	this.getOperatorType = function (){
		var operatorTypes = { '!=':'otDiffers','<=':'otLessOrEqual','>=':'otGreaterOrEqual','=':'otEquals','<':'otLess','>':'otGreater' };
		var operatorLiteral = '';
		while(this.__position < this.__string.length && ("!=<>").indexOf(this.__string[this.__position])!=-1) {
			operatorLiteral +=this.__string[this.__position];
			this.__position++;
		}
		if(operatorTypes[operatorLiteral]!=null){
			return operatorTypes[operatorLiteral];
		}else throw "InvalidOperatorException";
	}
	
	this.parseSpaces = function(){
		while ((this.__position < this.__string.length) && (this.__string[this.__position] == ' ')) {
			this.__position++;
		}
	}
	
	this.toString = function (){
		return ("TIRepConditionParser:{PropertyType: "+this.PropertyType+
				", OperandType: "+this.OperatorType+
				", PropertyKey: "+this.PropertyKey+
				", PropertyValue: "+this.PropertyValue+"}"
				);
	}

};