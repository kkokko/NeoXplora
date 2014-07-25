var TLinkerConditionParser_Implementation = {
  construct: function() {
    this.base(this);
  },
  
  properties: {
  	KeyEntity: null, // string (valid values: keOne, keTwo)
    KeyPropertyType: null, // string  (valid values: ptAttribute, ptEvent)
    PropertyKey: null, // string
    OperatorType: null, // string  (valid values: otEquals, otLess, otLessOrEqual, otGreater, otGreaterOrEqual, otDiffers)
    ValueEntity: null, // string (valid values: veOne, veTwo, veNone)
    ValuePropertyType: null, // string  (valid values: valid values: vptEvent, vptAttribute, vptNone)
    PropertyValue: null, // string
    
    __Position: 0,
    __String: null
  },
  
  methods: {
    ParseString: function(string) {
      this.set__String(string.trim());
      this.setKeyEntity(this.ParseKeyEntity());
      this.ParseSpaces(); // skips spaces between operand and key
      this.setKeyPropertyType(this.ParseKeyPropertyType());
      this.ParseSpaces(); // skips spaces between operand and key
      this.setPropertyKey(this.ParseOperand().trim());
      this.ParseSpaces();
      this.setOperatorType(this.ParseOperatorType());
      this.ParseSpaces();
      this.setValueEntity(this.ParseValueEntity());
      this.setValuePropertyType(this.ParseValuePropertyType());
      this.setPropertyValue(this.ParseOperand().trim());
      if (this.get__Position() < this.get__String().length) {
        throw "InvalidOperandException";
      }

      return new NeoAI.TLinkerRuleValue(
        this.getKeyEntity(), 
        this.getKeyPropertyType(),
        this.getPropertyKey(),
        this.getOperatorType(), 
        this.getValueEntity(), 
        this.getValuePropertyType(), 
        this.getPropertyValue()
      );
    },
    
    ParseKeyEntity: function() {
    	if(this.get__String().length > 0) {
        switch(this.get__String()[this.get__Position()]) {
          case '1':
            this.set__Position(this.get__Position() + 1);
            return 'keOne';
            break;
          case '2':
            this.set__Position(this.get__Position() + 1);
            return 'keTwo';
            break;
          default:
            throw "UndefinedKeyEntityException";
            break;
        }
      } else {
        throw "InvalidInputStringException";
      }
    },
      
    ParseKeyPropertyType: function() {
      if(this.get__String().length > 0) {
        switch(this.get__String()[this.get__Position()]) {
          case '.':
            this.set__Position(this.get__Position() + 1);
            return 'ptAttribute';
            break;
          case ':':
            this.set__Position(this.get__Position() + 1);
            return 'ptEvent';
            break;
          default:
            throw "UndefinedKeyPropertyTypeException";
            break;
        }
      } else {
        throw "InvalidInputStringException";
      }
    },
    
    ParseOperand: function() {
      var theInQuote = (this.get__String()[this.get__Position()] == '"');
      if (theInQuote) {
        this.set__Position(this.get__Position() + 1);
      }
      var theIncrement = theInQuote?2:0;
      var theStart = this.get__Position();
      
      while (
  			this.get__Position() < this.get__String().length && 
  			!theInQuote && 
  			("!=<>").indexOf(this.get__String()[this.get__Position()]) == -1
      ) {
        if((".,{}[]():+").indexOf(this.get__String()[this.get__Position()]) != -1) {
          throw "InvalidOperandException";
        }
        if(!theInQuote && ("\\\"").indexOf(this.get__String()[this.get__Position()]) != -1) {
          throw "InvalidOperandException";
        }
        if (this.get__String()[this.get__Position()] == '"'){
          theInQuote = false;
          break;
        }
        if (this.get__String()[this.get__Position()] == '\\'){
          this.set__Position(this.get__Position() + 1);
        }
        this.set__Position(this.get__Position() + 1);
      }
      
      while (this.get__Position() < this.get__String().length && theInQuote){
  		  if(this.get__String()[this.get__Position()] == '"') {
          theInQuote = false;
          break;
        }
  		  this.set__Position(this.get__Position() + 1);
      }
      
      if (theInQuote){
        throw "InvalidOperandException";
      }
      
      var theResult = this.get__String().slice(theStart, this.get__Position());
      this.set__Position(this.get__Position() + theIncrement);
      return theResult; 
    },
    
    ParseOperatorType: function() {
      var operatorTypes = { '!=':'otDiffers','<=':'otSmallerOrEqual','>=':'otGreaterOrEqual','=':'otEquals','<':'otSmaller','>':'otGreater' };
      var operatorLiteral = '';
      while(this.get__Position() < this.get__String().length && ("!=<>").indexOf(this.get__String()[this.get__Position()]) != -1) {
        operatorLiteral +=this.get__String()[this.get__Position()];
        this.set__Position(this.get__Position() + 1);
      }
      if(operatorTypes[operatorLiteral] != null) {
        return operatorTypes[operatorLiteral];
      } else {
        throw "InvalidOperatorException";
      }
    },
    
    ParseValueEntity: function() {
    	switch(this.get__String()[this.get__Position()]) {
        case '1':
          this.set__Position(this.get__Position() + 1);
          this.ParseSpaces();
          return 'veOne';
          break;
        case '2':
          this.set__Position(this.get__Position() + 1);
          this.ParseSpaces();
          return 'veTwo';
          break;
        default:
          return 'veUndefined';
          break;
      }	
    },
    
    ParseValuePropertyType: function() {
      var valuePropertyType = this.get__String()[this.get__Position()];
      if((".:").indexOf(valuePropertyType) != -1) {
      	if(this.getValueEntity() == "veUndefined") {
          throw "UndefinedValueEntityException";
      	}
        switch(valuePropertyType) {
          case '.':
            this.set__Position(this.get__Position() + 1);
            this.ParseSpaces();
            return 'vptAttribute';
            break;
          case ':':
            this.set__Position(this.get__Position() + 1);
            this.ParseSpaces();
            return 'vptEvent';
            break;
          default:
            throw "UndefinedValuePropertyTypeException";
            break;
        }
      } else if(this.getValueEntity() != "veUndefined") {
      	this.setValueEntity("veNone");
      	this.set__Position(this.get__Position() - 1);
        return "vptNone";
      } else {
      	this.setValueEntity("veNone");
      	return "vptNone";
      }

    },
    
    ParseSpaces: function() {
      while ((this.get__Position() < this.get__String().length) && (this.get__String()[this.get__Position()] == ' ')) {
        this.set__Position(this.get__Position() + 1);
      }
    },
    
    ToString: function() {
      return (
        "TLinkerConditionParser:{KeyPropertyType: " + this.getKeyPropertyType() +
        ", OperandType: " + this.getOperatorType() +
        ", PropertyKey: " + this.getPropertyKey() +
        ", PropertyValue: " + this.getPropertyValue() + "}"
      );
    }
  }  
};

Sky.Class.Define("NeoAI.TLinkerConditionParser", TLinkerConditionParser_Implementation);